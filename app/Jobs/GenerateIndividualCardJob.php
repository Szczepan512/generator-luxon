<?php

namespace App\Jobs;

use App\Models\Log;
use App\Models\Luminaire;
use App\Models\User;
use App\Notifications\CardGeneratedNotification;
use App\Notifications\IndividualCardGeneratedNotification;
use App\Services\FtpService;
use App\Settings\IndividualCardSettings;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Event\Code\Throwable;
use Spatie\Browsershot\Browsershot;

class GenerateIndividualCardJob implements ShouldQueue
{
    use Queueable, Batchable, Dispatchable, InteractsWithQueue, SerializesModels;
    public Luminaire $luminaire;
    public array $options;
    public User $user;
    public $timeout = 0;

    public $tries = 5;
    public $backoff = [1]; // Czas w sekundach przed ponowną próbą

    /**
     * Create a new job instance.
     */
    public function __construct(Luminaire $luminaire, array $options, User $user)
    {
        $this->luminaire = $luminaire;
        $this->options = $options;
        $this->user = $user;
    }

    public function excelToInt($column)
    {
        $columnNumber = 0;
        $length = strlen($column);

        for ($i = 0; $i < $length; $i++) {
            $charCode = ord($column[$i]) - 64; // 'A' ma kod ASCII 65
            $columnNumber = ($columnNumber * 26) + $charCode;
        }
        $columnNumber--;
        return $columnNumber;
    }


    public function transformData(array $data): array
    {
        $transformed = [];

        // Przetwarzanie sekcji 'header'
        if (isset($data['header']) && is_array($data['header'])) {
            $transformed['header'] = [];
            foreach ($data['header'] as $headerItem) {
                if (isset($headerItem['title']) && isset($headerItem['image'])) {
                    $transformed['header'][$headerItem['title']] = $headerItem['image'];
                }
            }
        }

        // // Przetwarzanie sekcji 'icons'
        // if (isset($data['icons']) && is_array($data['icons'])) {
        //     $transformed['icons'] = [];
        //     foreach ($data['icons'] as $iconCategory) {
        //         if (isset($iconCategory['title']) && isset($iconCategory['values']) && is_array($iconCategory['values'])) {
        //             $categoryTitle = $iconCategory['title'];
        //             foreach ($iconCategory['values'] as $iconItem) {
        //                 if (isset($iconItem['value']) && isset($iconItem['icon'])) {
        //                     $transformed['icons'][$categoryTitle][$iconItem['value']] = $iconItem['icon'];
        //                 }
        //             }
        //         }
        //     }
        // }

        return $transformed;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $luminaire_values = $this->luminaire->values;

        $allowedValues = array_values($this->options['fields']);

        $filtered_values = [];
        foreach ($luminaire_values as $key => $value) {
            if (in_array($key, $allowedValues)) {
                $filtered_values[$key] = $value;
            }
        }

        $settings = new IndividualCardSettings();
        $transformedIcons = $this->transformData($settings->maping);

        $html = '';

        //Head

        $html .= '<!DOCTYPE html><html lang="%%lang%%"><head>	<meta charset="UTF-8">	<meta name="viewport" content="width=device-width, initial-scale=1.0">	<title>%%pdf_title%%</title>    <style>    @font-face {  font-family: Sora;  src: url("' . asset('/assets/fonts/Sora-VariableFont_wght.ttf') . '") format("truetype-variations");  font-weight: 100 900;  font-display: swap;}body {  font-family: Sora;}html {  padding: 40px 28px;  box-sizing: border-box;  width: 595px;}* {  margin: 0;  padding: 0;  box-sizing: border-box;}.header {  display: grid;  grid-template-columns: 1fr auto;  align-items: stretch;}.header__title {  font-family: Sora;  font-size: 20px;  font-style: normal;  font-weight: 400;  line-height: normal;  color: #151c33;  max-width: 200px;  position: relative;  margin-top: 13px;}.header__title::after {  content: "";  position: absolute;  width: 0;  height: 0;  border-left: 20.5px solid #069aff;  border-top: 26.2px solid transparent;  border-bottom: 26.2px solid transparent;  top: -6px;  left: -36px;}.header__title span {  color: #069aff;  font-family: Sora;  font-size: 8px;  font-style: normal;  font-weight: 400;  margin-top: 0px;  display: block;  line-height: normal;}.header__subtitle {  color: #069aff;  font-family: Sora;  font-size: 12px;  font-style: normal;  font-weight: 400;  line-height: normal;  margin-top: 0px;}.header__list {  font-size: 10.67px;  text-decoration: none;  list-style: none;  display: flex;  flex-direction: column;  gap: 11px;  margin-top: 12px;}.header__list li {  position: relative;  padding-left: 9px;  color: #1E274A;  font-family: Sora;  font-size: 8px;  font-style: normal;  font-weight: 400;  line-height: normal;}.header__list li::after {  content: "";  position: absolute;  width: 3.16px;  height: 3.16px;  background-color: #069aff;  border-radius: 50%;  left: 0;  top: 3px;}.header__textArea {  height: fit-content;  margin-top: 14px;  color: #151c33;  font-family: Sora;  font-size: 8px;  font-style: normal;  font-weight: 400;  line-height: normal;}.header__image {  display: flex;  flex-direction: row;  gap: 18px;  height: auto;}.header__icons {  display: flex;  flex-direction: row;  gap: 18px;  position: absolute;  width: 100%;  height: 16px;  bottom: 13px;  right: 0;  padding: 0 23px;  justify-content: flex-end;  left: 0;}.header__icon {  width: 16px;  display: block;  height: 16px;  object-fit: contain;  object-position: center;}.header__thumbnail {  max-height: 100%;  border: 1px solid #069aff;  border-radius: 18px;  padding: 16px;  width: 260px;  position: relative;  aspect-ratio: 261/201;  height: auto;}.header__thumbnail > img {  inset: 16px;  position: absolute;  height: calc(100% - 32px);  width: calc(100% - 32px);  display: block;  object-fit: contain;  object-position: center;}.header__h3 {  color: #151c33;  margin-top: 14px;  font-family: Sora;  font-size: 12px;  font-style: normal;  font-weight: 400;  line-height: normal;}.iconsCards {  margin-top: 20px;}.iconsCards__header {  color: #069AFF;  font-family: Sora;  font-size: 12px;  padding-left: 12px;  font-style: normal;  font-weight: 400;  line-height: normal;}.iconsCards__grid {  display: grid;  grid-template-columns: repeat(7, 1fr);  gap: 8px;  margin-top: 8px;}.iconsCards__card {  background-color: #EBEBEF;  display: grid;  grid-template-rows: 1fr auto;  align-items: center;  justify-content: center;  height: 83.878px;  border-radius: 10px;  gap: 10px;  text-align: center;  padding: 17px 0;}.iconsCards__card img {  margin: 0 auto;}.iconsCards__card span {  color: #151c33;  font-family: Sora;  font-size: 8px;  font-style: normal;  font-weight: 400;  line-height: normal;}.textArea {  padding: 35px 26px;  color: #1E274A;  font-family: Sora;  font-size: 8px;  font-style: normal;  font-weight: 400;  line-height: normal;  page-break-after: always;}.themeTable {  width: 100%;  margin-top: 0;  border: none;  border-collapse: collapse;  outline: 0;}.themeTable tr {  border: 0 !important;  outline: 0;  display: grid;  grid-template-columns: 1fr 1fr;  gap: 4px;  margin-bottom: 4px;}.themeTable tr:has(th) {  background-color: #151c33 !important;  border-radius: 16px;}.themeTable tr th {  border: 0;  outline: 0;  height: 18px;  padding: 0 10px;  border-radius: 16px;  color: #ffffff;  display: flex;  flex-direction: row;  align-items: center;  font-family: Sora;  font-size: 7px;  font-style: normal;  font-weight: 400;  line-height: normal;  text-align: left;}.themeTable tr td {  border: 0;  background-color: rgba(235, 235, 239, 0.4862745098);  text-align: left;  color: #151C33;  font-family: Sora;  font-size: 7px;  font-style: normal;  font-weight: 400;  height: 18px;  display: flex;  flex-direction: row;  align-items: center;  line-height: normal;  border-radius: 16px !important;  padding: 0 10px;}.themeTable tr td a {  color: #069AFF;  font-family: Sora;  font-size: 7px;  font-style: normal;  font-weight: 400 !important;  line-height: normal;  text-decoration-line: underline;}.themeTable tr td a + img {  display: block;  margin-left: 7px;}.table {  padding-top: 24px;  padding-bottom: 100px;  page-break-inside: avoid;  display: block;}.table__title {  color: #069AFF;  font-family: Sora;  font-size: 12px;  padding-left: 12px;  font-style: normal;  font-weight: 400;  line-height: normal;  margin-bottom: 12px;}.table + .table {  margin-top: -100px;}.table + *:not(.gallery) {  margin-top: -100px;}.footer {  background-color: #069aff;  padding: 20px 32px;  display: grid;  grid-template-columns: 223px 190px 88px;  gap: 20px;}.footer__text {  color: #FFF;  font-family: Sora;  font-size: 7px;  font-style: normal;  font-weight: 0;  line-height: normal;}.footer__text a {  color: #ffffff;}.footer__image {  display: block;  width: 100%;}footer {  position: relative;  left: -36px;  margin-top: 32px;  width: calc(100% + 72px);}.iconsSet {  background-color: rgba(235, 235, 239, 0.5019607843);  border-radius: 15px;  padding: 14px 36px;  display: flex;  flex-direction: row;  margin-top: 20px;  justify-content: space-between;  align-items: center;}.iconsSet__icon {  display: block;  max-height: 36px;  max-width: 50px;}.dimensions {  page-break-inside: avoid;  padding-top: 20px;  page-break-before: always;}.dimensions__grid {  margin-top: 14px;  width: 100%;}.dimensions__image {  grid-template-columns: 1/3;  border: 1px solid #069aff;  border-radius: 18px;  padding: 16px;  position: relative;  height: 200px;}.dimensions__image img {  position: absolute;  inset: 24px;  width: calc(100% - 48px);  height: calc(100% - 48px);}.lightAngle {  page-break-inside: avoid;}.lightAngle__cards {  width: 100%;  display: grid;  grid-template-columns: repeat(4, 1fr);  gap: 10px 18px;  margin: 10px 0 0;}.lightAngle__card {  width: 100%;  color: #069aff;  text-align: center;  font-family: Sora;  font-size: 16px;  font-style: normal;  font-weight: 400;  line-height: normal;  display: grid;  grid-template-rows: 1fr auto;}.lightAngle__image {  width: 100%;  height: auto;  display: block;  border: 1px solid #069aff;  border-radius: 18px;  padding: 6px;  margin-bottom: 6px;}.lightAngle__image img {  width: 100%;}.gallery {  page-break-before: always;  page-break-inside: avoid;  padding-top: 30px;}.gallery__header {  color: #069AFF;  font-family: Sora;  font-size: 12px;  padding-left: 12px;  font-style: normal;  font-weight: 400;  line-height: normal;}.gallery__grid {  display: grid;  margin-top: 14px;  grid-template-columns: repeat(6, 1fr);  grid-template-rows: 200px 160px 160px;  gap: 15px;}.gallery__image {  page-break-inside: avoid;  border: 1px solid #069AFF;  border-radius: 14px;  display: block;  width: 100%;  height: 100%;  overflow: hidden;  aspect-ratio: 1/1;  padding: 17px;}.gallery__image:nth-of-type(1) {  grid-column: 1/7;  grid-row: 1/2;  aspect-ratio: unset;  margin-top: 0;}.gallery__image:nth-of-type(2) {  grid-column: 1/3;  grid-row: 2/3;}.gallery__image:nth-of-type(3) {  grid-column: 3/5;  grid-row: 2/3;}.gallery__image:nth-of-type(4) {  grid-column: 5/7;  grid-row: 2/3;}.gallery__image:nth-of-type(5) {  grid-column: 2/4;  grid-row: 3/4;}.gallery__image:nth-of-type(6) {  grid-column: 4/6;  grid-row: 3/4;}.gallery__image img {  width: 100%;  height: 100%;  display: block;  object-fit: contain;  object-position: center;}html {  zoom: 1.75;  margin: 0 auto;}@media print {  html {    margin-bottom: 100px;    zoom: 1.5;  }  footer {    position: fixed;    bottom: 0;    width: 100%;    left: 0;    right: 0;    margin-top: 0;  }  .table {    page-break-inside: avoid;  }  @page {    margin-top: 0px;  }}/*# sourceMappingURL=styles.css.map */</style>
        <style>
            .gallery{
            margin-top: -100px;
        }
            @media print{
            .gallery{
            margin-top:0;
    
        }
            }
        </style>
        </head><body>';


        //HEADER
        if (in_array('hero', $this->options['sections'])) {
            $html .= '<header class="header">
            <div class="header__text">
                <h2 class="header__subtitle">
                    %%card%%
                </h2>
                <h1 class="header__title">
                    ' . $filtered_values[$this->excelToInt($settings->maping['title'])] . '
                    <span>' . $filtered_values[$this->excelToInt($settings->maping['sku'])] . '</span>
                </h1>
                <p class="header__textArea">
                %%short_description%%
                </p>
                <h3 class="header__h3">
                    %%application_title%%
                </h3>
                <ul class="header__list">
                    %%application_list%%
                 </ul>
    
            </div>
            <div class="header__image">
                <div class="header__thumbnail">
';
            if (isset($filtered_values[$this->excelToInt($settings->maping['main_image'])])) {

                $html .= '<img src="' . asset($filtered_values[$this->excelToInt($settings->maping['main_image'])]) . '" alt="Product Thumbnail">';
            }
            $html .= '                  
                    <div class="header__icons">
                            ';
            $header_icons = [];
            foreach ($settings->pl_maping['application'] as $application) {
                if (!isset($filtered_values[$this->excelToInt($application)]))
                    continue;
                $value_from_luminaire = $filtered_values[$this->excelToInt($application)];
                if ($value_from_luminaire) {
                    $header_icons[] = $value_from_luminaire;
                }
            }
            $icons_urls = [];
            foreach (array_unique(array_values($header_icons)) as $header_icon) {
                if (isset($transformedIcons['header'][$header_icon]))
                    $icons_urls[] = $transformedIcons['header'][$header_icon];
            }
            foreach (array_unique($icons_urls) as $icon) {
                $html .= '<img src="' . asset($icon) . '" alt="" class="header__icon">';
            }
            // <img src="./assets/img/header_icon_1.svg" alt="" class="header__icon">
            $html .= '</div>
                </div>
            </div>
        </header>';
        }

        //ICONS BAR
        if (in_array('iconsBar', $this->options['sections'])) {
            $html .= '<section class="iconsCards">
            <h2 class="iconsCards__header">
                %%iconsBar_title%%
            </h2>
            <div class="iconsCards__grid">';

            foreach ($settings->maping['icons'] as $iconData) {
                if (!isset($filtered_values[$this->excelToInt($iconData['column'])]))
                    continue;
                $value = $filtered_values[$this->excelToInt($iconData['column'])];
                if ($value) {
                    $html .= '<div class="iconsCards__card">
                    <img src="' . asset($iconData['icon']) . '" class="iconsSet__icon" alt="">
                    <span>' . $value . '</span>
                </div>';
                }
            }
            $html .= '</div>
            </div>
        </section>';

        }

        // DESCRIPTION
        if (in_array('description', $this->options['sections'])) {
            $html .= '	<div class="textArea">
            %%description%%
            </div>';
        }


        // DEFAULT TABLE
        if (in_array('default', $this->options['sections'])) {

            $table_rows = [];
            foreach ($settings->maping['table_general_information'] as $row_title => $row_value) {
                if ($row_value == "%%montage__value%%") {
                    $table_rows[$row_title] = $row_value ;
                    continue;
                }
                if (!isset($filtered_values[$this->excelToInt($row_value)]))
                    continue;
                $filtered_row_value = $filtered_values[$this->excelToInt($row_value)];
                if ($filtered_row_value) {
                    $table_rows[$row_title] = $filtered_row_value;
                }
            }

            if (isset($table_rows['luminaire_operating_temperature_1']) && isset($table_rows['luminaire_operating_temperature_2'])) {
                $table_rows['luminaire_operating_temperature'] = $table_rows['luminaire_operating_temperature_1'] . ' ÷ ' . $table_rows['luminaire_operating_temperature_2'];

            }

            $filtered_table_rows = array();
            foreach ($table_rows as $key => $value) {
                if ($key === 'luminaire_operating_temperature_1') {
                    $filtered_table_rows['luminaire_operating_temperature'] = $value;
                } elseif ($key !== 'luminaire_operating_temperature_2' && $key !== 'luminaire_operating_temperature_1') {
                    $filtered_table_rows[$key] = $value;
                }
            }


            if (count($filtered_table_rows)) {
                $html .= '
                <section class="table">
                        <h2 class="table__title">
                        %%general%%
                    </h2>
                    <table class="themeTable" border="0">
                    <tr>
                    <th colspan="2">
                        %%general_information%%
                    </th>
                </tr>';

                foreach ($filtered_table_rows as $title => $value) {
                    $html .= '<tr>
                        <td>%%' . $title . '%%</td>
                        <td>' . $value . '</td>
                    </tr>';
                }

                $html .= '</table></section>';
            }
        }

        // DEFAULT TABLE
        if (in_array('electrical', $this->options['sections'])) {

            $table_rows = [];
            foreach ($settings->maping['tabel_electrical_information'] as $row_title => $row_value) {
                if (!isset($filtered_values[$this->excelToInt($row_value)]))
                    continue;
                $filtered_row_value = $filtered_values[$this->excelToInt($row_value)];
                if ($filtered_row_value) {
                    $table_rows[$row_title] = $filtered_row_value;
                }
            }

            if (count($table_rows)) {
                $html .= '
                <section class="table">
                    <table class="themeTable" border="0">
                    <tr>
                    <th colspan="2">
                        %%electrical_information%%
                    </th>
                </tr>';

                foreach ($table_rows as $title => $value) {
                    $html .= '<tr>
                        <td>%%' . $title . '%%</td>
                        <td>' . $value . '</td>
                    </tr>';
                }

                $html .= '</table></section>';
            }
        }


        if (in_array('psu', $this->options['sections'])) {

            $table_rows = [];
            foreach ($settings->maping['table_psu_information'] as $row_title => $row_value) {
                if (!isset($filtered_values[$this->excelToInt($row_value)]))
                    continue;
                $filtered_row_value = $filtered_values[$this->excelToInt($row_value)];
                if ($filtered_row_value) {
                    $table_rows[$row_title] = $filtered_row_value;
                }
            }

            if (count($table_rows)) {
                $html .= '
                <section class="table">
                    <table class="themeTable" border="0">
                    <tr>
                    <th colspan="2">
                        %%psu_table_title%%
                    </th>
                </tr>';

                foreach ($table_rows as $title => $value) {
                    $html .= '<tr>
                        <td>%%' . $title . '%%</td>
                        <td>' . $value . '</td>
                    </tr>';
                }

                $html .= '</table></section>';
            }
        }

        if (in_array('light_source', $this->options['sections'])) {

            $table_rows = [];
            foreach ($settings->maping['table_light_source'] as $row_title => $row_value) {
                if (!isset($filtered_values[$this->excelToInt($row_value)]))
                    continue;
                $filtered_row_value = $filtered_values[$this->excelToInt($row_value)];
                if ($filtered_row_value) {
                    $table_rows[$row_title] = $filtered_row_value;
                }
            }

            if (count($table_rows)) {
                $html .= '
                <section class="table">
                    <table class="themeTable" border="0">
                    <tr>
                    <th colspan="2">
                        %%light_source_data%%
                    </th>
                </tr>';

                foreach ($table_rows as $title => $value) {
                    $html .= '<tr>
                        <td>%%' . $title . '%%</td>
                        <td>' . $value . '</td>
                    </tr>';
                }

                $html .= '</table></section>';
            }
        }

        if (in_array('materials', $this->options['sections'])) {
            $html .= '%%materials_table%%';
        }

        if (in_array('fotometric', $this->options['sections'])) {
            $table_rows = [];
            foreach ($settings->maping['table_fotometric_information'] as $row_title => $row_value) {
                if (!isset($filtered_values[$this->excelToInt($row_value)]))
                    continue;
                $filtered_row_value = $filtered_values[$this->excelToInt($row_value)];
                if ($filtered_row_value) {
                    $table_rows[$row_title] = $filtered_row_value;
                }
            }

            if (isset($table_rows['ugr_min_max_1']) && isset($table_rows['ugr_min_max_2'])) {
                $table_rows['ugr_min_max'] = $table_rows['ugr_min_max_1'] . ' - ' . $table_rows['ugr_min_max_2'];
            }

            $filtered_table_rows = array();
            foreach ($table_rows as $key => $value) {
                if ($key === 'ugr_min_max_1') {
                    $filtered_table_rows['ugr_min_max'] = $value;
                } elseif ($key !== 'ugr_min_max_2' && $key !== 'ugr_min_max_1') {
                    $filtered_table_rows[$key] = $value;
                }
            }



            if (count($filtered_table_rows)) {
                $html .= '
                <section class="table">
                    <table class="themeTable" border="0">
                    <tr>
                    <th colspan="2">
                        %%photometric_data%%
                    </th>
                </tr>';

                foreach ($filtered_table_rows as $title => $value) {
                    $html .= '<tr>
                        <td>%%' . $title . '%%</td>
                        <td>' . $value . '</td>
                    </tr>';
                }

                $html .= '</table></section>';

            }
        }
        // FOTOMETRIC IMAGE
        if (in_array('img', $this->options['sections']) && isset($filtered_values[$this->excelToInt($settings->maping['fotometric_image_url'])])) {
            $fotometric_url = $filtered_values[$this->excelToInt($settings->maping['fotometric_image_url'])];
            if ($fotometric_url) {
                $html .= '<section class="lightAngle">
            <div class="lightAngle__cards">
            <div class="lightAngle__card">
            <div class="lightAngle__image">
            <img src="' . asset($fotometric_url) . '" alt="">
            </div>
            </div>
            </div>
            </section>';
            }
        }


        // DIMENSIONS
        if (in_array('dimensions', $this->options['sections'])) {


            $html .= '	<section class="dimensions" style="page-break-inside:avoid;">
        <h2 class="table__title">
            %%dimensions%%
        </h2>
        <div class="dimensions__grid">
            <div class="dimensions__image">';
            if (isset($filtered_values[$this->excelToInt($settings->maping['dimensions_image_url'])])) {
                $html .= '<img src="' . asset($filtered_values[$this->excelToInt($settings->maping['dimensions_image_url'])]) . '" alt="">';
            }

            $html .= '</div>
        </div>
        </section>
        <section class="table">
            <table class="themeTable" border="0"><tr>
                    <th colspan="2">%%dimensions%%</th>
                </tr>';
            foreach ($settings->maping['dimensions'] as $key => $value) {
                $filtered = $filtered_values[$this->excelToInt($value)];
                if ($filtered) {
                    $html .= '<tr>
                    <td>%%' . $key . '%%</td>
                    <td>' . $filtered . '</td>
                </tr>';
                }
            }
            $html .= '
            </table>
        </section>';
        }

        if (in_array('files', $this->options['sections'])) {

            $html .= '<section class="table">
            <h2 class="table__title">
                %%files_title%%
            </h2>
            <table class="themeTable" border="0">
                <tr>
                    <th colspan="2">
                        %%files_heading%%
                    </th>
                </tr>
                %%files_rows%%
            </table>
        </section>
    
        <section class="table">
            <table class="themeTable" border="0">
                <tr>
                    <th colspan="2">
                        %%files_heading%%
                    </th>
                </tr>
                %%links_rows%%
            </table>
        </section>';
        }

        if (in_array('gallery', $this->options['sections'])) {
            $html .= '<section class="gallery">
            <h2 class="gallery__header">
                %%gallery%%
            </h2>
            <div class="gallery__grid">
                <div class="gallery__image">';
            if (isset($filtered_values[$this->excelToInt($settings->maping['main_image'])])) {
                $html .= '<img src="' . asset($filtered_values[$this->excelToInt($settings->maping['main_image'])]) . '" alt="">';
            }

            $html .= '</div>
                ';
            foreach ($settings->maping['gallery'] as $image) {
                if (!isset($filtered_values[$this->excelToInt($image)]))
                    continue;
                $url = $filtered_values[$this->excelToInt($image)];
                if ($url) {
                    $html .= '<div class="gallery__image"><img src="' . asset($url) . '" alt=""></div>';
                }
            }
            $html .= '		</div>
        </section>';
        }

        $currentDateTime = new \DateTime();
        $html .= '	<footer class="footer">
            <p class="footer__text">
                <span>' . $currentDateTime->format('d.m.Y H:i') . '</span>
                <span>' . $filtered_values[$this->excelToInt($settings->maping['sku'])] . '</span>
            </p>
            <p class="footer__text">
                %%footer_copyright%% <br /> %%footer_release%%
            </p>
            <img src="' . asset('/assets/img/logo.svg') . '" alt="" class="footer__image">
        </footer>';

        $html .= '</body></html>';

        $html_template = $html;
        foreach (['pl', 'de', 'en'] as $lang) {
            $html = $html_template;

            if ($lang == 'pl') {
                $create_html = isset($this->options['pl_create_html']) ? $this->options['pl_create_html'] : false;
                $html_filename = isset($this->options['pl_html_filename']) ? $this->options['pl_html_filename'] : '';
                $html_externalDisk = isset($this->options['pl_html_externalDisk']) ? $this->options['pl_html_externalDisk'] : false;
                $create_pdf = isset($this->options['pl_create_pdf']) ? $this->options['pl_create_pdf'] : false;
                $pdf_filename = isset($this->options['pl_pdf_filename']) ? $this->options['pl_pdf_filename'] : '';
                $pdf_externalDisk = isset($this->options['pl_pdf_externalDisk']) ? $this->options['pl_pdf_externalDisk'] : false;
                $ftp_account = 'individual_luxon_ftp_pl';
            }

            if ($lang == 'de') {
                $create_html = isset($this->options['de_create_html']) ? $this->options['de_create_html'] : false;
                $html_filename = isset($this->options['de_html_filename']) ? $this->options['de_html_filename'] : '';
                $html_externalDisk = isset($this->options['de_html_externalDisk']) ? $this->options['de_html_externalDisk'] : false;
                $create_pdf = isset($this->options['de_create_pdf']) ? $this->options['de_create_pdf'] : false;
                $pdf_filename = isset($this->options['de_pdf_filename']) ? $this->options['de_pdf_filename'] : '';
                $pdf_externalDisk = isset($this->options['de_pdf_externalDisk']) ? $this->options['de_pdf_externalDisk'] : false;
                $ftp_account = 'individual_luxon_ftp_de';
            }

            if ($lang == 'en') {
                $create_html = isset($this->options['en_create_html']) ? $this->options['en_create_html'] : false;
                $html_filename = isset($this->options['en_html_filename']) ? $this->options['en_html_filename'] : '';
                $html_externalDisk = isset($this->options['en_html_externalDisk']) ? $this->options['en_html_externalDisk'] : false;
                $create_pdf = isset($this->options['en_create_pdf']) ? $this->options['en_create_pdf'] : false;
                $pdf_filename = isset($this->options['en_pdf_filename']) ? $this->options['en_pdf_filename'] : '';
                $pdf_externalDisk = isset($this->options['en_pdf_externalDisk']) ? $this->options['en_pdf_externalDisk'] : false;
                $ftp_account = 'individual_luxon_ftp_en';
            }

            if (!$create_html && !$create_pdf)
                continue;

            switch ($lang) {
                case 'pl':
                    $maping = $settings->pl_maping;
                    $links = $settings->pl_links;
                    $translations = $settings->pl_fixed_translations;
                    $descriptionSetting = $settings->pl_description;
                    $html_path = $settings->ftp_html_path_polish;
                    $pdf_path = $settings->ftp_pdf_path_polish;
                    $files_translat = $settings->pl_translations;
                    $montage = $settings->pl_montage;
                    break;
                case 'de':
                    $maping = $settings->de_maping;
                    $links = $settings->de_links;
                    $translations = $settings->de_fixed_translations;
                    $descriptionSetting = $settings->de_description;
                    $html_path = $settings->ftp_html_path_deutch;
                    $pdf_path = $settings->ftp_pdf_path_deutch;
                    $files_translat = $settings->de_translations;
                    $montage = $settings->de_montage;
                    break;
                case 'en':
                    $maping = $settings->en_maping;
                    $links = $settings->en_links;
                    $translations = $settings->en_fixed_translations;
                    $descriptionSetting = $settings->en_description;
                    $html_path = $settings->ftp_html_path_english;
                    $pdf_path = $settings->ftp_pdf_path_english;
                    $files_translat = $settings->en_translations;
                    $montage = $settings->en_montage;
                    break;
            }


            $application_list = '';
            if (in_array('hero', $this->options['sections'])) {
                $applications = [];
                foreach ($maping['application'] as $application) {
                    if (!isset($filtered_values[$this->excelToInt($application)]))
                        continue;
                    $value = $filtered_values[$this->excelToInt($application)];
                    if ($value) {
                        $applications[] = $value;
                    }
                }
                foreach (array_unique($applications) as $application) {
                    if(!in_array($application,['','n/a', 'N/A']) )
                    $application_list .= '<li><span></span>' . $application . '</li>';
                }

            }
            $html = str_replace('%%application_list%%', $application_list, $html);



            $materials_table = ' ';
            if (in_array('materials', $this->options['sections'])) {
                $table_rows = [];
                foreach ($maping['table_materials'] as $row_title => $row_value) {
                    if (!isset($filtered_values[$this->excelToInt($row_value)]))
                        continue;

                    $filtered_row_value = $filtered_values[$this->excelToInt($row_value)];
                    if ($filtered_row_value) {
                        $table_rows[$row_title] = $filtered_row_value;
                    }
                }

                if (count($table_rows)) {
                    $materials_table .= '
                <section class="table">
                    <table class="themeTable" border="0">
                    <tr>
                    <th colspan="2">
                        %%materials_table_title%%
                    </th>
                </tr>';

                    foreach ($table_rows as $title => $value) {
                        $materials_table .= '<tr>
                        <td>%%' . $title . '%%</td>
                        <td>' . $value . '</td>
                    </tr>';
                    }

                    $materials_table .= '</table></section>';
                }
            }
            $html = str_replace('%%materials_table%%', $materials_table, $html);

            $files_rows = '';
            $links_rows = '';

            if (in_array('files', $this->options['sections'])) {

                foreach ($maping['files'] as $name => $column) {
                    if (!isset($filtered_values[$this->excelToInt($column)]))
                        continue;
                    $url = $filtered_values[$this->excelToInt($column)];
                    if (!$url)
                        continue;
                    $files_rows .= '<tr>
                    <td>%%' . $name . '%%</td>
                    <td><a href="' . asset($url) . '">%%download%%</a><img src="' . asset('assets/img/download.svg') . '" alt=""></td>
                </tr>';
                }

                foreach ($links as $name => $url) {
                    $links_rows .= '
                <tr>
                    <td>' . $name . ' </td>
                    <td><a href="' . $url . '">%%see%%</a></td>
                </tr>';
                }
            }

            $html = str_replace('%%files_rows%%', $files_rows, $html);
            $html = str_replace('%%links_rows%%', $links_rows, $html);

            $pattern = "/\[[A-Z]{1,2}\]/";


            $description = '';
            if (in_array('hero', $this->options['sections'])) {
                $exploded_description = explode(PHP_EOL, $descriptionSetting);
                foreach ($exploded_description as $sentense) {
                    if ($sentense == '')
                        continue;
                    $exploded_sentence = explode('.', $sentense);
                    foreach ($exploded_sentence as $text) {

                        $text = ltrim($text);

                        if (str_contains($text, '[applications]')) {
                            $first = true;
                            $applications = '';
                            foreach ($maping['application'] as $application) {
                                if (!isset($filtered_values[$this->excelToInt($application)]))
                                    continue;
                                $value_from_luminaire = $filtered_values[$this->excelToInt($application)];
                                if ($value_from_luminaire) {
                                    if (!$first) {
                                        $applications .= ', ';
                                    }
                                    $applications .= $value_from_luminaire;
                                    $first = false;
                                }
                            }

                            $text = str_replace('[applications]', $applications, $text);

                            $description .= $text . '. ';
                            continue;
                        }



                        preg_match_all($pattern, $text, $matches);

                        $number_of_patterns = count($matches[0]);

                        if (!$number_of_patterns) {
                            $description .= $text;
                            continue;
                        }


                        if ($number_of_patterns == 1) {
                            if (!isset($filtered_values[$this->excelToInt(str_replace('[', '', str_replace(']', '', $matches[0][0])))])) {
                                continue;
                            }

                            $required_data = $filtered_values[$this->excelToInt(str_replace('[', '', str_replace(']', '', $matches[0][0])))];
                            if (!$required_data) {
                                continue;
                            }
                            $text = str_replace($matches[0][0], $required_data, $text);
                            $description .= $text . '. ';
                        }

                        if ($number_of_patterns == 2) {

                            $missing_flag = true;
                            foreach ($matches[0] as $match) {
                                if (!isset($filtered_values[$this->excelToInt(str_replace('[', '', str_replace(']', '', $match)))])) {
                                    $missing_flag = false;
                                    continue;
                                }

                                $required_data = $filtered_values[$this->excelToInt(str_replace('[', '', str_replace(']', '', $match)))];
                                if (!$required_data) {
                                    $missing_flag = false;
                                    continue;
                                }


                                $text = str_replace($match, $required_data, $text);
                            }
                            if ($missing_flag) {
                                $description .= $text . '. ';
                            }

                        }

                        if ($number_of_patterns > 2) {

                            $multiPattern = "/\{\{(.*?)\}\}/";
                            preg_match_all($multiPattern, $text, $multimatches);
                            $previous_successfull = false;

                            $number_of_multiPatterns = count($multimatches[0]);
                            $failed = 0;
                            foreach ($multimatches[0] as $match) {

                                preg_match_all($pattern, $match, $mm);

                                if (!isset($filtered_values[$this->excelToInt(str_replace('[', '', str_replace(']', '', $mm[0][0])))])) {
                                    $previous_successfull = false;
                                    $text = str_replace($match, '', $text);
                                    $failed++;
                                    continue;
                                }

                                $required_data = $filtered_values[$this->excelToInt(str_replace('[', '', str_replace(']', '', $mm[0][0])))];
                                if (!$required_data) {
                                    $previous_successfull = false;
                                    $text = str_replace($match, '', $text);
                                    $failed++;
                                    continue;
                                }
                                $newMatch = '';
                                if ($previous_successfull) {
                                    $newMatch .= ', ';
                                }
                                $newMatch = str_replace('{{', '', str_replace('}}', '', $match));
                                $newMatch = str_replace($mm[0][0], $required_data, $newMatch);
                                $text = str_replace($match, $newMatch, $text);
                                $previous_successfull = true;
                            }
                            if ($failed == $number_of_multiPatterns) {
                                continue;
                            }
                            $description .= $text . '. ';

                        }
                    }
                    $description .= '<br/><br/>';
                }

            }

            if (str_contains($html, '%%montage__value%%')) {
                $col_value = '';
                foreach ($montage as $col => $value) {
                    if (isset($filtered_values[$this->excelToInt($col)]) && $filtered_values[$this->excelToInt($col)] == "TAK")
                        $col_value != '' ? $col_value .= ', '.$value :   $col_value .= $value;
                }
                $html = str_replace('%%montage__value%%', $col_value, $html);
            }



            $html = str_replace('%%pdf_title%%', $translations['card'] . ' ' . $filtered_values[$this->excelToInt($settings->maping['sku'])], $html);
            $html = str_replace('%%description%%', $description, $html);
            $html = str_replace('%%short_description%%', $filtered_values[$this->excelToInt($maping['short_description'])], $html);
            foreach ($translations as $name => $translation) {
                $html = str_replace('%%' . $name . '%%', $translation, $html);
            }
            foreach ($files_translat as $name => $translation) {
                $html = str_replace('%%' . $name . '%%', $translation, $html);
            }


            $luminaire = Luminaire::find($this->luminaire->id);
            $html_filePaths = json_decode($luminaire->html_filepath, true) ?? [];
            $html_filePaths[$lang] = '';

            if ($create_html) {
                $filename = $html_filename != '' ? $html_filename : $this->luminaire->name . '-' . $lang . '.html';

                if ($html_externalDisk) {
                    $fullpath = $html_path . $filename;

                    $attempts = 5;
                    while ($attempts) {
                        try {
                            Storage::disk($ftp_account)->put($fullpath, $html);
                            break;
                        } catch (\Exception $e) {
                            $attempts--;
                            sleep(1);
                        }
                    }

                    if($lang == 'pl'){
                        $html_filePaths[$lang] = 'https://luxon.pl/Karty_Katalogowe' . $fullpath;
                    }else if($lang == 'de'){
                        $html_filePaths[$lang] = 'https://luxonled.de/Katalogkarten' . $fullpath;
                    }else if($lang == 'en'){
                        $html_filePaths[$lang] = 'https://luxonled.eu/Catalog_Cards' . $fullpath;
                    }

                } else {
                    $localPath = '/' . $this->luminaire->name . '/' . $filename;
                    Storage::disk('public')->put($localPath, $html);
                    $html_filePaths[$lang] = 'https://generator.luxon.pl/storage/' . $this->luminaire->name . '/' . $filename;
                }

                $luminaire->html_filepath = json_encode($html_filePaths);
                $luminaire->save();
            }
            $pdf_filePaths = json_decode($luminaire->pdf_filepath, true) ?? [];
            $pdf_filePaths[$lang] = '';
            if ($create_pdf) {
                // dd(shell_exec('whoami'));

                $filename = '';
                if ($pdf_filename != '') {
                    $filename = $pdf_filename;
                } else {
                    $filename = $this->luminaire->name . '-' . $lang . '.pdf';
                }

                if ($pdf_externalDisk) {

                    $fullpath = $pdf_path . $filename;

                    $path = public_path("/pdf_temp/");
                    if (!Storage::exists($path)) {
                        Storage::makeDirectory($path);
                    }
                    if (Storage::disk('local')->exists('pdf_temp/' . $filename)) {
                        Storage::disk('local')->delete('pdf_temp/' . $filename);
                    }
                    Browsershot::html($html)
                        ->format('A4')
                        ->showBackground()
                        // ->setChromePath(env('Chrome'))
                        ->setNpmBinary(env('NPMBinary'))
                        ->setNodeBinary(env('NodeBinary'))
                        ->save(storage_path('app/private/pdf_temp/' . $filename));

                    $pdfFile = Storage::disk('local')->get('pdf_temp/' . $filename);
                    if ($pdfFile) {
                        $attempts = 5;
                        while ($attempts) {
                            try {
                                Storage::disk($ftp_account)->put($fullpath, $pdfFile);
                                break;
                            } catch (\Exception $e) {
                                $attempts--;
                                sleep(1);
                            }
                        }
                        Storage::disk('local')->delete('pdf_temp/' . $filename);
                    }

                    if($lang == 'pl'){
                        $pdf_filePaths[$lang] = 'https://luxon.pl/Karty_Katalogowe' . $fullpath;
                    }else if($lang == 'de'){
                        $pdf_filePaths[$lang] = 'https://luxonled.de/Katalogkarten' . $fullpath;
                    }else if($lang == 'en'){
                        $pdf_filePaths[$lang] = 'https://luxonled.eu/Catalog_Cards' . $fullpath;
                    }
                } else {
                    if (!Storage::disk('public')->exists($this->luminaire->name )) {
                        Storage::disk('public')->makeDirectory($this->luminaire->name );
                    }
                    Browsershot::html($html)
                        ->format('A4')
                        ->showBackground()
                        // ->setChromePath(env('Chrome'))
                        ->setNpmBinary(env('NPMBinary'))
                        ->setNodeBinary(env('NodeBinary'))
                        ->save(storage_path('app/public/' . $this->luminaire->name . '/' . $filename));

                    $pdf_filePaths[$lang] = 'https://generator.luxon.pl/storage/' . $this->luminaire->name . '/' . $filename;
                }

                $luminaire->pdf_filepath = json_encode($pdf_filePaths);
                $luminaire->save();

                Log::create(
                    [
                        'subject' => 'Generowanie Karty Katalogowej Rodziny: ' . $luminaire->name,
                        'user_id' => $this->user->id,
                        'info' => json_encode($pdf_filePaths),
                        'error' => '',
                        'status' => 'success'
                    ]
                );
            }
        }

        Notification::make()
            ->title('Pomyślnie wygenerowano ' . $this->luminaire->name)
            ->success()
            ->sendToDatabase($this->user);
    }
    public function failed(?Throwable $exception): void
    {
        // Send user notification of failure, etc...
        Log::create(
            [
                'subject' => 'Generowanie Karty Katalogowej Rodziny: ' . $this->luminaire->name,
                'user_id' => $this->user->id,
                'info' => '',
                'error' => $exception,
                'status' => 'error'
            ]
        );
    }
}
