<?php

namespace App\Jobs;

use App\Models\Log;
use App\Models\LuminaireFamily;
use App\Models\User;
use App\Notifications\FamilyCardGeneratedNotification;
use App\Settings\FamilyCardSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use File;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Batchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Spatie\Browsershot\Browsershot;

class GenerateFamilyCardJob implements ShouldQueue
{
    use Queueable, Batchable, Dispatchable, InteractsWithQueue, SerializesModels;
    public LuminaireFamily $luminaireFamily;
    public array $options;
    public User $user;
    public array $parsedData;

    /**
     * Create a new job instance.
     */
    public function __construct(LuminaireFamily $luminaireFamily, array $options, User $user)
    {
        $this->luminaireFamily = $luminaireFamily;
        $this->options = $options;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */

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

    public function parseData()
    {
        $products = $this->luminaireFamily->luminaires;
        $productData = [];
        foreach ($products as $product) {
            $productData[] = $product->values;
        }

        $data_langs = [];
        $firstValuesRows = $productData[1];

        $light_spread = [];
        $tables = [];
        $daliFlag = 0;

        $dimensions = [];

        $configurator = [];
        $familyCardSettings = new FamilyCardSettings();
        $maping = $familyCardSettings->maping;


        foreach ($productData as $key => $singularProduct) {
            if ($key == 0)
                continue;
            if ($singularProduct[$this->excelToInt('ES')] != "TAK")
                continue;
            if ($singularProduct[$this->excelToInt('CZ')] == "DALI" && $daliFlag)
                $daliFlag = 1;


            $exploded_sku = explode('.', $singularProduct[$this->excelToInt('A')]);
            $sku = $exploded_sku[0] . '.' . $exploded_sku[1] . '.' . $exploded_sku[2] . '.' . $exploded_sku[3];
            $name = $exploded_sku[0] . '.' . $exploded_sku[1][0];
            if (!isset($dimensions[$name])) {
                $dimensions[$name] = [];
                $dimensions[$name]['h'] = @$singularProduct[$this->excelToInt($maping['dimensions']['h'])];
                $dimensions[$name]['l'] = @$singularProduct[$this->excelToInt($maping['dimensions']['l'])];
                $dimensions[$name]['w'] = @$singularProduct[$this->excelToInt($maping['dimensions']['w'])];
                $dimensions[$name]['package'] = @$singularProduct[$this->excelToInt($maping['package']['package'])];
                $dimensions[$name]['palette'] = @$singularProduct[$this->excelToInt($maping['package']['palette'])];
                $dimensions[$name]['pallete-sizes'] = @$singularProduct[$this->excelToInt($maping['package']['palette-sizes'])];
            }


            if (!isset($light_spread[$singularProduct[$this->excelToInt('AI')]])) {
                $light_spread[$singularProduct[$this->excelToInt('AI')]] = $singularProduct[$this->excelToInt("HY")];
            }

            if (!isset($tables[$singularProduct[$this->excelToInt('JL')]])) {
                $tables[$singularProduct[$this->excelToInt('JL')]] = [];
                $tables[$singularProduct[$this->excelToInt('JL')]]['name'] = $singularProduct[$this->excelToInt('JL')];
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'] = [];
            }
            if (!isset($tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku])) {
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku] = [];
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['sku'] = $sku;
            }

            if (!isset($tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['power']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['power'] = array();
            if (!in_array($singularProduct[$this->excelToInt("AG")], $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['power']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['power'][] = $singularProduct[$this->excelToInt("AG")];


            if (!isset($tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['raw_luminous_flux']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['raw_luminous_flux'] = array();
            if (!in_array($singularProduct[$this->excelToInt('AF')], $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['raw_luminous_flux']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['raw_luminous_flux'][] = $singularProduct[$this->excelToInt('AF')];

            if (!isset($tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['tone']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['tone'] = array();
            if (!in_array($singularProduct[$this->excelToInt('CA')], $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['tone']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['tone'][] = $singularProduct[$this->excelToInt('CA')];
            sort($tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['tone']);

            if (!isset($tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['cri']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['cri'] = array();
            if (!in_array($singularProduct[$this->excelToInt("CB")], $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['cri']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['cri'][] = $singularProduct[$this->excelToInt("CB")];

            if (!isset($tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['ip']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['ip'] = array();
            if (!in_array($singularProduct[$this->excelToInt("X")], $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['ip']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['ip'][] = $singularProduct[$this->excelToInt("X")];

            if (!isset($tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['beam_angle']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['beam_angle'] = array();
            if (!in_array($singularProduct[$this->excelToInt('AI')], $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['beam_angle']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['beam_angle'][] = $singularProduct[$this->excelToInt('AI')];

            if (!isset($tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['weight']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['weight'] = array();
            if (!in_array($singularProduct[$this->excelToInt('BM')], $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['weight']))
                $tables[$singularProduct[$this->excelToInt('JL')]]['records'][$sku]['weight'][] = $singularProduct[$this->excelToInt('BM')];

            $sku = $singularProduct[0];
            $exploded_sku = explode('.', $sku);

            //Configurator Column 1 
            if (!isset($configurator[0]))
                $configurator[0] = [];
            if (!in_array($exploded_sku[0], $configurator[0]))
                $configurator[0][] = $exploded_sku[0];

            //Configurator Column 2
            if (!isset($configurator[1]))
                $configurator[1] = [];
            if (!in_array($exploded_sku[1][0], $configurator[1])) {
                $configurator[1][] = $exploded_sku[1][0];
            }

            //Configurator Column 3
            if (!isset($configurator[2]))
                $configurator[2] = [];
            if (!in_array($exploded_sku[1][1], $configurator[2])) {
                $configurator[2][] = $exploded_sku[1][1];
            }

            //Configurator Column 4
            if (!isset($configurator[3]))
                $configurator[3] = [];
            if (!in_array($exploded_sku[2], $configurator[3])) {
                $configurator[3][] = $exploded_sku[2];
            }

            //Configurator Column 5
            if (!isset($configurator[4]))
                $configurator[4] = [];
            if (!in_array($exploded_sku[3][0], $configurator[4])) {
                $configurator[4][] = $exploded_sku[3][0];
            }

            //Configurator Column 6
            if (!isset($configurator[5]))
                $configurator[5] = [];
            if (!in_array($exploded_sku[3][1], $configurator[5])) {
                $configurator[5][] = $exploded_sku[3][1];
            }

            //Configurator Column 7
            if (!isset($configurator[6]))
                $configurator[6] = [];
            if (!in_array(substr($exploded_sku[3], -4), $configurator[6])) {
                $configurator[6][] = substr($exploded_sku[3], -4);
            }

            //Configurator Column 8
            if (!isset($configurator[7]))
                $configurator[7] = [];
            if (!in_array($exploded_sku[4][0], $configurator[7])) {
                $configurator[7][] = $exploded_sku[4][0];
            }

            //Configurator Column 9
            if (!isset($configurator[8]))
                $configurator[8] = [];
            if (!in_array(substr($exploded_sku[4], -3), $configurator[8])) {
                $configurator[8][] = substr($exploded_sku[4], -3);
            }

            //Configurator Column 10
            if (!isset($configurator[9]))
                $configurator[9] = [];
            if (!in_array($exploded_sku[5], $configurator[9])) {
                $configurator[9][] = $exploded_sku[5];
            }

            //Configurator Column 11
            if (!isset($configurator[10]))
                $configurator[10] = [];
            if (!in_array($exploded_sku[6], $configurator[10])) {
                $configurator[10][] = $exploded_sku[6];
            }

            //Configurator Column 12
            if (!isset($configurator[11]))
                $configurator[11] = [];
            if (!in_array($exploded_sku[7], $configurator[11])) {
                $configurator[11][] = $exploded_sku[7];
            }

            //Configurator Column 13
            if (!isset($configurator[12]))
                $configurator[12] = [];
            if (!in_array($exploded_sku[8], $configurator[12])) {
                $configurator[12][] = $exploded_sku[8];
            }

            //Configurator Column 14
            if (!isset($configurator[13]))
                $configurator[13] = [];
            if (!in_array($exploded_sku[9], $configurator[13])) {
                $configurator[13][] = $exploded_sku[9];
            }
        }

        foreach ($configurator as $key => $config_item) {
            sort($configurator[$key]);
        }

        foreach ($tables as &$product) {

            foreach ($product['records'] as &$record) {
                if (!isset($record['raw_luminous_flux'][1]))
                    $record['luminous_flux'] = $record['raw_luminous_flux'][0];
                else {
                    sort($record['raw_luminous_flux']);
                    $record['luminous_flux'] = reset($record['raw_luminous_flux']) . ' - ' . end($record['raw_luminous_flux']);
                }
            }
            ksort($product['records']);
        }
        ksort($tables);


        $mapingData = [];
        foreach ($maping as $key => $value) {
            if ($key == 'header') {
                if (!isset($mapingData['header'])) {
                    $mapingData['header'] = [];
                }
                foreach ($value as $v) {
                    $mapingData['header'][] = $firstValuesRows[$this->excelToInt($v)];
                }
                continue;
            }
            if ($key == 'dimensions' || $key == 'package') {
                $d = [];
                foreach ($value as $k => $v) {
                    if (isset($firstValuesRows[$this->excelToInt($v)])) {
                        $d[$k] = $firstValuesRows[$this->excelToInt($v)];
                    }
                }
                $mapingData[$key] = $d;
                continue;
            }


            if (is_array($value)) {
                $d = [];
                foreach ($value as $k => $v) {
                    if (is_array($v)) {
                        @$d[$v['slug']] = $firstValuesRows[$this->excelToInt($v['column'])];
                    } else {
                        $d = $v;
                    }
                }
                $mapingData[$key] = $d;
            } else {
                if (in_array($key, ['subtitle', 'lightspread', 'configuration', 'example', 'footer', 'under_tables'])) {
                    $mapingData[$key] = $value;
                } else {
                    $mapingData[$key] = $firstValuesRows[$this->excelToInt($value)];
                }
            }
        }

        foreach (['pl' => $familyCardSettings->pl_maping, 'de' => $familyCardSettings->de_maping, 'en' => $familyCardSettings->en_maping] as $lang => $lang_maping) {
            $data = [];

            $data['pros'] = [];
            foreach ($lang_maping as $value) {
                $data['pros'][] = $firstValuesRows[$this->excelToInt($value)];
            }
            $data['icons']['psu'] = 'DALI';
            $data['dimensions_values'] = $dimensions;
            $data['configurator'] = $configurator;
            $data['tables'] = [];
            $data['tables'] = $tables;
            $data['light_spread'] = $light_spread;
            $data['maping'] = $mapingData;
            $data['translations'] = [];
            $data['installations'] = [];
            $data['columns'] = [];
            switch ($lang) {
                case 'pl':
                    $data['translations'] = $familyCardSettings->pl_translations;
                    $data['installations'] = $familyCardSettings->pl_installations;
                    $data['columns'] = $familyCardSettings->pl_columns;
                    break;
                case 'de':
                    $data['translations'] = $familyCardSettings->de_translations;
                    $data['installations'] = $familyCardSettings->de_installations;
                    $data['columns'] = $familyCardSettings->de_columns;
                    break;
                case 'en':
                    $data['translations'] = $familyCardSettings->en_translations;
                    $data['installations'] = $familyCardSettings->en_installations;
                    $data['columns'] = $familyCardSettings->en_columns;
                    break;
            }
            $data_langs[$lang] = [];
            $data_langs[$lang] = $data;
        }
        $this->parsedData = $data_langs;
    }

    public static function transformData(array $data): array
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

        // Przetwarzanie sekcji 'icons'
        if (isset($data['icons']) && is_array($data['icons'])) {
            $transformed['icons'] = [];
            foreach ($data['icons'] as $iconCategory) {
                if (isset($iconCategory['title']) && isset($iconCategory['values']) && is_array($iconCategory['values'])) {
                    $categoryTitle = $iconCategory['title'];
                    foreach ($iconCategory['values'] as $iconItem) {
                        if (isset($iconItem['value']) && isset($iconItem['icon'])) {
                            $transformed['icons'][$categoryTitle][$iconItem['value']] = $iconItem['icon'];
                        }
                    }
                }
            }
        }

        return $transformed;
    }
    public function transposeArray($array)
    {
        $maxLength = max(array_map('count', $array));
        $transposedArray = [];
        for ($i = 0; $i < $maxLength; $i++) {
            $row = [];
            foreach ($array as $subArray) {
                $row[] = isset($subArray[$i]) ? $subArray[$i] : null;
            }
            $transposedArray[] = $row;
        }
        return $transposedArray;
    }

    public function render_and_save_files()
    {

        $familyCardSettings = new FamilyCardSettings();

        $icons = $familyCardSettings->icons;
        $transformedIcons = $this->transformData($icons);

        $data = $this->parsedData['pl'];
        //Header Image and Icons
        $header = '<div class="header__image"><div class="header__icons">';
        $icons_urls = [];
        foreach (array_unique(array_values($data['maping']['header'])) as $header_icon) {
            if (isset($transformedIcons['header'][$header_icon]))
                $icons_urls[] = $transformedIcons['header'][$header_icon];
        }
        foreach (array_unique($icons_urls) as $icon) {
            $header .= '<img src="' . asset($icon) . '" alt="" class="header__icon">';
        }
        $header .= '</div><div class="header__thumbnail">';
        $header .= '<img src="' . $data['maping']['thumbnail'] . '" alt="Product Thumbnail">';
        $header .= '</div></div>';

        //Icons 
        $iconsSet = '<section class="iconsSet">';
        foreach ($data['maping']['icons'] as $key => $icon) {
            @$iconPath = $transformedIcons['icons'][$key][$icon];
            if ($iconPath) {
                $iconsSet .= '<img src="' . asset($iconPath) . '" class="iconsSet__icon iconsSet__icon--' . $key . '" alt="">';
            }
        }
        foreach ($data['maping']['icons_text'] as $key => $value) {

            $name = '';
            if ($key == 'voltage') {
                switch ($value) {
                    case '12VDC':
                        $name = "12VDC";
                        break;
                    case '24VDC':
                        $name = "24VDC";
                        break;
                    case '12-24VDC':
                        $name = "12-24VDC";
                        break;
                    case '18-32VDC':
                        $name = '18-32VDC';
                        break;
                    case '230V':
                        $name = "230V";
                        break;
                }
                // $name = $value;
            } else if ($key == 'ip') {
                if ($value && $value != 'nie dotyczy') {
                    $exploded = explode('/', $value);
                    if (count($exploded) > 1) {
                        foreach ($exploded as $k => $e) {
                            if ($k) {
                                $name .= '/';
                            }
                            $name .= '<b>' . strtoupper($key) . '</b>' . $e;
                        }
                    } else {
                        $name = '<b>' . strtoupper($key) . '</b>' . $value;
                    }
                }
            } else if (in_array($key, ['ik'])) {
                if ($value && $value != 'nie dotyczy') {
                    $name = '<b>' . strtoupper($key) . '</b>' . $value;
                }
            }
            if ($name != '') {
                $iconsSet .= '<span class="iconsSet__text iconsSet__icon--' . $key . '">' . $name . '</span>';
            }
        }

        if (str_contains($data['maping']['sku'], 'IND')) {
            $iconsSet .= '<img src="' . asset($icons['IND']) . '" class="iconsSet__icon iconsSet__icon--IND" alt="">';
        }
        $iconsSet .= '</section>';

        $first_table_flag = 1;
        //Tables
        $under_table_flag = 0;
        $tables = '';
        foreach ($data['tables'] as $table) {
            if ($first_table_flag) {
                $tables .= '<section class="table table--first">';
            } else {
                $tables .= '<section class="table">';
            }
            $tables .= '<div style="padding-bottom: 100px;page-break-inside: avoid;"><h2 class="table__title">' . $table['name'] . '</h2>';


            $tables .= '<table class="themeTable" border="0" >
            <tr>
                <th><img src="' . asset("/assets/img/tableIcons/code.e5dde0cb.svg") . '" alt=""></th>
                <th><img src="' . asset("/assets/img/tableIcons/p.f132c1c3.svg") . '" alt=""></th>
                <th><img src="' . asset("/assets/img/tableIcons/lm.14cf938f.svg") . '" alt=""></th>
                <th><img src="' . asset("/assets/img/tableIcons/temp.8fac35bd.svg") . '" alt=""></th>
                <th><img src="' . asset("/assets/img/tableIcons/Ra.f310419e.svg") . '" alt=""></th>
                <th><img src="' . asset("/assets/img/tableIcons/IP.94668dc4.svg") . '" alt=""></th>
                <th><img src="' . asset("/assets/img/tableIcons/angle.e0ae4307.svg") . '" alt=""></th>
                <th><img src="' . asset("/assets/img/tableIcons/weight.ef6a24e6.svg") . '" alt=""></th>
            </tr>';

            $tables .= '<tr style="">';
            $tables .= '<td style="border-radius: 16px 0 0 16px;">' . $table['records'][array_key_first($table['records'])]['sku'] . '</td>';
            $tables .= '<td>' . $table['records'][array_key_first($table['records'])]['power'][0] . '</td>';
            $tables .= '<td>' . $table['records'][array_key_first($table['records'])]['luminous_flux'] . '</td>';
            $tables .= '<td>' . $table['records'][array_key_first($table['records'])]['tone'][0] . 'K</td>';
            $tables .= '<td>' . $table['records'][array_key_first($table['records'])]['cri'][0] . '</td>';
            $tables .= '<td>IP' . $table['records'][array_key_first($table['records'])]['ip'][0] . '</td>';
            $tables .= '<td>' . implode(' ', $table['records'][array_key_first($table['records'])]['beam_angle']) . '</td>';
            $tables .= '<td  style="border-radius: 0 16px 16px 0">' . $table['records'][array_key_first($table['records'])]['weight'][0] . 'kg</td>';
            $tables .= '</tr>';

            $tables .= '</table></div>';
            $i = 1;
            $last = count($table['records']);
            foreach ($table['records'] as $record) {
                if (str_contains($record['luminous_flux'], ' - ')) {
                    $under_table_flag = 1;
                }
                if ($i == 1) {
                    $i = $i + 1;
                    continue;
                }
                if ($i == 1 && $last != 1) {
                    $tables .= '<div style="page-break-inside: avoid; padding-bottom:100px; margin-top: -100px;">';
                } else if ($i == $last && $i != 1) {
                    $tables .= '<div style="page-break-inside: avoid; margin-top: -100px">';
                } else if ($last == 1) {
                    $tables .= '<div style="page-break-inside: avoid; margin-top: -100px">';
                } else {
                    $tables .= '<div style="page-break-inside: avoid; padding-bottom:100px;margin-top:-100px">';
                }
                $tables .= '<table class="themeTable" border="0" ' . $last . '|' . $i . '>
            <tr style="display:none">
                <th><img src="' . asset("assets/img/tableIcons/code.e5dde0cb.svg") . '" alt=""></th>
                <th><img src="' . asset("assets/img/tableIcons/p.f132c1c3.svg") . '" alt=""></th>
                <th><img src="' . asset("assets/img/tableIcons/lm.14cf938f.svg") . '" alt=""></th>
                <th><img src="' . asset("assets/img/tableIcons/temp.8fac35bd.svg") . '" alt=""></th>
                <th><img src="' . asset("assets/img/tableIcons/Ra.f310419e.svg") . '" alt=""></th>
                <th><img src="' . asset("assets/img/tableIcons/IP.94668dc4.svg") . '" alt=""></th>
                <th><img src="' . asset("assets/img/tableIcons/angle.e0ae4307.svg") . '" alt=""></th>
                <th><img src="' . asset("assets/img/tableIcons/weight.ef6a24e6.svg") . '" alt=""></th>
            </tr>';
                if ($i % 2 == 0) {
                    $tables .= '<tr style="background-color: rgba(235, 235, 239, 0.5019607843)">';
                } else {
                    $tables .= '<tr>';
                }
                $tables .= '<td style="border-radius: 16px 0 0 16px">' . $record['sku'] . '</td>';
                $tables .= '<td>' . $record['power'][0] . '</td>';
                $tables .= '<td>' . $record['luminous_flux'] . '</td>';
                $tables .= '<td>' . $record['tone'][0] . 'K</td>';
                $tables .= '<td>' . $record['cri'][0] . '</td>';
                $tables .= '<td>IP' . $record['ip'][0] . '</td>';
                $tables .= '<td>' . implode(' ', $record['beam_angle']) . '</td>';
                $tables .= '<td  style="border-radius: 0 16px 16px 0">' . $record['weight'][0] . 'kg</td>';
                $tables .= '</tr>';
                $tables .= '</table></div>';
                $i = $i + 1;
            }






            $tables .= '</section>';
            if ($first_table_flag)
                $first_table_flag = 0;
        }


        // Dimensions

        $dimensions = '<section class="dimensions">';
        $dimensions .= '<div class="dimensions__image"><img src="' . @$data['maping']['dimensions_image'] . '" alt=""></div>';
        $dimensions .= ' <div class="dimensions__grid"><div class="dimensions__table"><table class="themeTable"><tr><th><img src="' . asset('assets/img/tableIcons/code.e5dde0cb.svg') . '" alt=""></th><th><img src="' . asset('assets/img/tableIcons/h.9ac8637e.svg') . '" alt=""></th><th><img src="' . asset('assets/img/tableIcons/w.b7dc0e49.svg') . '" alt=""></th><th><img src="' . asset('assets/img/tableIcons/l.062a3e66.svg') . '" alt=""></th> </tr>';


        foreach ($data['dimensions_values'] as $sheet_code => $values) {
            $dimensions .= '<tr>';
            $dimensions .= '<td>' . $sheet_code . '</td>';
            $dimensions .= '<td>' . $values['h'] . '</td>';
            $dimensions .= '<td>' . $values['l'] . '</td>';
            $dimensions .= '<td>' . $values['w'] . '</td>';
            $dimensions .= '</tr>';
        }


        $dimensions .= '</table></div>';



        $package_flag = 0;

        foreach ($data['dimensions_values'] as $sheet_code => $values) {
            if ($values['package'] && $values['palette'] && $values['pallete-sizes']) {
                $package_flag = 1;
            }
        }

        if ($package_flag) {

            $dimensions .= '<div class="dimensions__table"><table class="themeTable"><tr><th><img src="' . asset('assets/img/tableIcons/code.e5dde0cb.svg') . '" alt=""></th><th><img src="' . asset('assets/img/tableIcons/package.912a0818.svg') . '" alt=""></th><th><img src="' . asset('assets/img/tableIcons/palette.e73988d8.svg') . '" alt=""></th><th><img src="' . asset('assets/img/tableIcons/palette-sizes.56cb0441.svg') . '" alt=""></th></tr>';
            foreach ($data['dimensions_values'] as $sheet_code => $values) {
                if ($values['package']) {
                    $dimensions .= '<tr>';
                    $dimensions .= '<td>' . $sheet_code . '</td>';
                    $dimensions .= '<td>' . $values['package'] . '</td>';
                    $dimensions .= '<td>' . $values['palette'] . '</td>';
                    $dimensions .= '<td>' . $values['pallete-sizes'] . '</td>';
                    $dimensions .= '</tr>';
                }
            }
            $dimensions .= '</table></div>';
        }


        $dimensions .= '</div>';
        $dimensions .= '</section>';

        //LiteAngles




        foreach ($this->parsedData as $lang => $data) {
            // if (!$postData[$lang]['html'] && !$postData[$lang]['pdf'])
            //     continue;
            $html = '';
            $html .= '<!DOCTYPE html><html lang=""><head><title>' . $data['maping']['title'] . '</title><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Docs</title><style> @font-face {font-family: Sora; src: url("' . asset('assets/fonts/Sora-VariableFont_wght.ttf') . '") format("truetype-variations"); font-weight: 100 900; font-display: swap}body{ font-family: Sora}html{width:794px; zoom: 120%; margin: 0 auto; padding: 36px; box-sizing: border-box;}*{ margin: 0; padding: 0; box-sizing: border-box}.header{ display: grid; grid-template-columns: 1fr auto; align-items: stretch}.header__title{ font-size: 26.67px; font-weight: 400; color: #151c33; max-width: 200px; position: relative}.header__title::after{ content: ""; position: absolute; width: 0; height: 0; border-left: 27.5px solid #069aff; border-top: 35.2px solid transparent; border-bottom: 35.2px solid transparent; top: 0; left: -36px}.header__subtitle{ font-size: 16px; color: #151c33; margin-top: 16px; font-weight: 400}.header__list{ font-size: 10.67px; text-decoration: none; list-style: none; display: flex; flex-direction: column; gap: 15px; margin-top: 16px}.header__list li{ font-size: 10.67px; color: #1e274a; position: relative; padding-left: 10px}.header__list li::after{ content: ""; position: absolute; width: 4.16px; height: 4.16px; background-color: #069aff; border-radius: 50%; left: 0; top: 4px}.header__text{ height: fit-content}.header__image{ display: flex; flex-direction: row; gap: 18px; height: auto}.header__icons{ display: flex; flex-direction: column; gap: 18px}.header__icon{ width: 22px; height: 22px; object-fit: contain; object-position: center}.header__thumbnail{ max-height: 100%; border: 1px solid #069aff; border-radius: 18px; padding: 16px; width: 350px; position: relative}.header__thumbnail img{ inset: 16px; position: absolute; height: calc(100% - 32px); width: calc(100% - 32px); display: block; object-fit: contain; object-position: center}.themeTable{ width: 100%; margin-top: 12px; border: none; border-collapse: collapse; outline: 0}.themeTable tr:first-of-type{ position:relative; background: transparent !important;}  .themeTable tr:first-of-type::after{position: absolute; z-index: -1; content: ""; inset:0; width:100%; height:100%; background-color: #151c33 !important; border-radius: 16px;}.themeTable tr{ border: 0 !important; outline: 0}.themeTable tr th{ border: 0; outline: 0; height: 54px !important; min-height: 54px !important; max-height: 54px !important; padding: 0 16px;}.themeTable tr th:first-of-type{ //border-radius: 16px 0 0 16px}.themeTable tr th:last-of-type{ //border-radius: 0 16px 16px 0}.themeTable tr td{ border: 0; color: #151c33; font-family: Sora; font-size: 9.333px; font-weight: 400; text-align: center; padding: 16px}.themeTable tr:nth-of-type(2n -1){ background-color: rgba(235, 235, 239, 0.5019607843)}.themeTable tr:nth-of-type(2n -1) td:first-of-type{ border-radius: 16px 0 0 16px}.themeTable tr:nth-of-type(2n -1) td:last-of-type{ border-radius: 0 16px 16px 0}.footer{ background-color: #069aff; padding: 28px 36px; display: grid; grid-template-columns: 1fr auto; gap: 53px}.footer__text{ color: #ffffff; font-size: 9.333px}.footer__text a{ color: #ffffff}footer{ position: relative; left: -36px; margin-top: 32px; width: calc(100% + 72px)} @media print{ body{margin-bottom: 0px !important;}footer{ position: fixed; bottom:0px; width: 100%; left: 0; right: 0; margin-top: 0;}}.iconsSet{ background-color: rgba(235, 235, 239, 0.5019607843); border-radius: 15px; padding: 14px 36px; display: flex; flex-direction: row; margin-top: 20px; justify-content: space-between; align-items: center}.iconsSet__icon{ display: block; max-height: 36px; max-width: 36px}.table{  padding-top: 44px}.table__title{ color: #069aff; font-family: Sora; font-size: 16px; margin-left: 16px; font-weight: 400; line-height: normal}.dimensions{ page-break-inside: avoid; padding-top: 36px}.dimensions__grid{ margin-top: 30px; display: grid; gap: 30px; grid-template-columns: repeat(2, 1fr)}.dimensions__image{ border: 1px solid #069aff; border-radius: 18px; padding: 16px; position: relative; height: 270px}.dimensions__image img{ position: absolute; inset: 24px; width: calc(100% - 48px); height: calc(100% - 48px)}.installation__blueprint,.installation__icon{max-width:100%}.installation{ page-break-inside: avoid; display: flex; padding-top: 50px; gap: 30px}.installation__image{ width: calc(50% - 15px); height: 346px; position: relative}.installation__image img{ width: 100%; height:100%; display:block; position:relative; object-fit: contain; object-position: center}.installation__title{ color: #069aff; font-family: Sora; font-size: 16px; font-style: normal; font-weight: 400; line-height: normal; position: absolute; left: 8px; top: 0; z-index: 10;}.installation__cards{ margin-top: 60px; display: flex; flex-direction: column; gap: 6px}.installation__card{ color: #151c33; font-family: Sora; font-size: 10.667px; font-style: normal; font-weight: 400; line-height: normal; gap: 10px; text-align: center; display: grid; grid-template-columns: 36px 1fr 140px; align-items: center; padding: 16px 20px; border: 1px solid #069aff; border-radius: 18px; min-height: 68px}.lightAngle{ padding-top: 36px; }.lightAngle__title{ color: #069aff; font-family: Sora; font-size: 16px; margin-left: 16px; font-style: normal; font-weight: 400; line-height: normal}.lightAngle__cards{    width: calc(100% - 52px); display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px 18px; margin: 16px 26px 0}.lightAngle__card{ width: 100%; color: #069aff; text-align: center; font-family: Sora; font-size: 16px; font-style: normal; font-weight: 400; line-height: normal; display: grid; grid-template-rows: 1fr auto}.lightAngle__image{ width: 100%; height: auto; display: block; border: 1px solid #069aff; border-radius: 18px; padding: 6px; margin-bottom: 6px}.lightAngle__image img{ width: 100%}.configurations{ padding-top: 36px; page-break-inside: avoid}.configurations__title{ color: #069aff; font-family: Sora; font-size: 16px; font-style: normal; font-weight: 400; margin-left: 16px; line-height: normal}.configurations__table{ width: 100%; margin-top: 12px; border: none; outline: 0}.configurations__table tr th{ color: #fff; font-family: Sora; font-size: 6.667px; font-style: normal; background-color: #069aff; text-align: center; font-weight: 400; line-height: normal; padding: 3px; border-radius: 4px; height: 32px; width: 50px !important; max-width: 50px !important; word-wrap: break-word;}.configurations__table tr td{ background-color: rgba(235, 235, 239, 0.5019607843); padding: 6px; height: 60px; width: 50px !important; max-width:50px !important; word-wrap: break-all;overflow-wrap: break-word; hyphens: auto; border-radius: 4px; color: #151c33; text-align: center; font-family: Sora; font-size: 6px; font-style: normal; font-weight: 400; line-height: normal}.configurations__table tr td span{ color: #069aff; display: block}.example{ padding-top: 36px; page-break-inside: avoid; }.example__title{ color: #069aff; font-family: Sora; font-size: 16px; font-style: normal; font-weight: 400; margin-left: 16px; line-height: normal}.example__text{ color: #151c33; font-family: Sora; font-size: 16px; font-style: normal; margin-left: 16px; font-weight: 400; margin-top: 6px; line-height: normal}</style></head>';
            $html .= " <style> .configurations__table tr td{padding: 16px 6px 6px 6px; vertical-align: top;} .iconsSet__icon--ip{order:10}  p .iconsSet__icon--voltage{order:20}   .iconsSet__icon--protection{order:30; max-width: 32px; max-height: 32px;}   .iconsSet__icon--indoor{order:40; max-width:24px;}   .iconsSet__icon--outdoor{order:40; max-width:24px;}   .iconsSet__icon--ce{order:50}   .iconsSet__icon--psu{order:60}   .iconsSet__icon--ik{order:11}   .iconsSet__icon--IND{order:80}       .table{padding-bottom: 100px !important; } .table + .table{margin-top: -100px !important;}    
             .example, .lightAngle,    .installation ,.dimensions{margin-top: -100px; padding-bottom: 100px;}
    .configurations{
        
    margin-top: -100px;}
    
    .table{
            /* page-break-inside:avoid;*/
    }
    
    
    .table:not(.table--first){
    /* margin-top:-80px;*/
     padding-top: 22px;
    }
    
            /* footer {
             flow: static(footer-html);
              position:relative;  
              display:block;
           }
           @page {
             margin-bottom: 80px;
             @bottom {
               content: flow(footer-html);  
             }
           }*/
            @page{
                margin-top: 20px;
            /* >*:is(table){
                 margin-top: -80px
                 }*/
                .header{
                margin-top: 0;
                }
            }
    
            .lightAngle{
            padding-bottom: 0px;
            page-breake-inside:avoid;
            }

            .lightAngle__cards{
            display:flex;
            flex-direction:column;
            gap:10px;
            page-breake-inside: avoid;
            }

            .lightAngle__row{
            display:flex;
            flex-direction: row;
            margin-top: -100px;page-break-inside: avoid;
            padding-bottom:100px;
            gap:18px;
            }
            .lightAngle__row:first-of-type{
            margin-top: 0;
            }
            .lightAngle__card{
            width: calc((100% - 36px) / 3)
        }
            .underTables{margin-top: -100px; padding-bottom: 100px; text-align:center; color:rgba(30, 39, 74, 0.9333333333);page-break-inside: avoid; font-size: 14px; font-weight: 700; padding-top:44px;}
            </style>";
            $html .= '<body><div>';
            if (in_array('hero', $this->options['sections'])) {
                $html .= '<header class="header"><div class="header__text">';
                $html .= ' <h1 class="header__title">' . preg_replace('/LED (\d\.\d)/', "LED&nbsp;$1", $data['maping']['title']) . '</h1>';
                $html .= '  <h2 class="header__subtitle">' . $data['translations']['subtitle'] . '</h2>';
                $html .= '<ul class="header__list">';
                foreach ($data['pros'] as $pro) {
                    if (!in_array($pro, ['nie dotyczy', 'n/a', 'k.A.', '', '#N/A'])) {
                        $html .= '<li><span></span>' . $pro . '</li>';
                    }
                }
                $html .= '</ul>';
                $html .= '</div>';
                $html .= $header;
            }

            $html .= '</header>';
            if (in_array('iconsBar', $this->options['sections'])) {
                $html .= $iconsSet;
            }
            if (in_array('tables', $this->options['sections'])) {
                $html .= $tables;
                if ($under_table_flag) {
                    $html .= '<section class="underTables">' . $data['translations']['under_tables'] . '</section>';
                }
            }

            if (in_array('dimensions', $this->options['sections'])) {
                $html .= $dimensions;
            }


            if (in_array('montage', $this->options['sections'])) {
                $html .= '<section class="installation">';
                $html .= '     <div class="installation__image">
        <div class="installation__title">' . $data['translations']['name'] . '</div><img src="' . $data['maping']['installation_image'] . '" alt="">
    </div>';
                $html .= '<div class="installation__cards">';

                foreach ($data['maping']['installation'] as $key => $installation) {
                    if ($installation == 'TAK' && $key != 'image') {
                        foreach ($familyCardSettings->maping['installation'] as $i) {
                            if ($i['slug'] == $key) {
                                $html .= '<div class="installation__card">
                            <img src="' . asset($i['icon']) . '" alt="" class="installation__icon">
                             ' . $data['installations'][$key] . ' 
                            <img src="' . asset($i['blueprint']) . '" alt="" class="installation__blueprint">
                            </div>';
                            }
                        }
                    }
                }

                $html .= '</div></section>';
            }
            if (in_array('light', $this->options['sections'])) {
                $html .= ' <section class="lightAngle">';
                $html .= ' <h2 class="lightAngle__title" style="padding-bottom: 230px; page-break-inside:avoid;">' . $data['translations']['lightspread'] . '</h2><div class="lightAngle__cards" style="margin-top: -200px;">';
                $ligth_spread_counter = 1;

                $ligth_spread_count = count($data['light_spread']);

                $light_spread_last_row = 0;
                // if ($ligth_spread_count % 3 == 0) {
                //     $light_spread_last_row = $ligth_spread_count - 3;
                // } else {
                //     $light_spread_last_row = floor($ligth_spread_count / 3) * 3;
                // }
                // if ($ligth_spread_counter <= 3) {
                //     $html .= 'style="page-break-inside:avoid;padding-bottom:100px;"';
                // } else if ($ligth_spread_counter > 3 && $ligth_spread_counter <= $light_spread_last_row) {
                //     $html .= 'style="page-break-inside:avoid;padding-bottom:100px; margin-top:-100px;"';
                // } else {
                //     $html .= 'style="page-break-inside:avoid;margin-top:-100px;"';
                // }
                foreach ($data['light_spread'] as $key => $value) {
                    if ($ligth_spread_counter % 3 == 1) {
                        $html .= '<div class="lightAngle__row">';
                    }

                    $html .= ' <div class="lightAngle__card"';

                    $html .= ' >
                    <div class="lightAngle__image"><img src="' . $value . '" alt=""></div>' . $key . '
                </div>';

                    if ($ligth_spread_counter % 3 == 0) {
                        $html .= '</div>';
                    }

                    $ligth_spread_counter = $ligth_spread_counter + 1;
                }
                if ($ligth_spread_counter % 3 != 0) {
                    $html .= '</div>';
                }

                $html .= ' </div></section>';
            }
            if (in_array('configurator', $this->options['sections'])) {
                $html .= '<section class="configurations"><h2 class="configurations__title">' . $data['translations']['configuration'] . '</h2>';

                $configurator_head = '<table class="configurations__table" style="margin-top:-100px; padding-bottom:100px;">';
                $configurator_head .= '<tr style="display:none" >';
                foreach ($data['columns'] as $column) {
                    $configurator_head .= '<th>' . $column . '</th>';
                }
                $configurator_head .= '</tr>';


                $html .= '<table class="configurations__table" style="padding-bottom:100px;">';
                $html .= '<tr> ';
                foreach ($data['columns'] as $column) {
                    $html .= '<th>' . $column . '</th>';
                }
                $html .= '</tr>';
                $html .= '</table>';


                if ($lang == 'pl') {
                    $configurator_translations = $familyCardSettings->pl_configurator;
                }

                if ($lang == 'en') {
                    $configurator_translations = $familyCardSettings->en_configurator;
                }

                if ($lang == 'de') {
                    $configurator_translations = $familyCardSettings->de_configurator;
                }
                $transposed_configurator = $this->transposeArray($data['configurator']);
                foreach ($transposed_configurator as $row) {
                    $html .= $configurator_head;
                    $html .= '<tr>';
                    foreach ($row as $key => $value) {
                        $text = '';

                        switch ($key) {
                            case 0:
                                $text = $data['maping']['title'];
                                break;
                            case 1:
                                $text = $value;
                                break;
                            case 2:
                                $text = $value != '' ? $configurator_translations[$key + 1][$value] : '';
                                break;
                            case 3:
                                $text = $value != '' ? intval($value) . 'W' : '';
                                break;
                            case 4:
                                $text = $value != '' ? '>' . (intval($value) * 10) : '';
                                break;
                            case 5:
                                $text = $value != '' ? $configurator_translations[$key + 1][$value] : '';
                                break;
                            case 6:
                                $text = $value != '' ? intval($value) . 'K' : '';
                                break;
                            case 7:
                                $text = $value != '' ? $configurator_translations[$key + 1][$value] : '';
                                break;
                            case 8:
                                $text = ($value != '' && $configurator_translations[$key + 1][substr($value, 0, 2)] != '???') ? $configurator_translations[$key + 1][substr($value, 0, 2)] . ' ' . $configurator_translations[$key + 1][substr($value, -1)] : '';
                                break;
                            case 9:
                                $text = $value != '' ? intval(substr($value, 0, 3)) . '°x' . intval(substr($value, -3)) . '°' : '';
                                break;
                            case 10:
                                $text = $value != '' ? $configurator_translations[$key + 1][$value] : '';
                                break;
                            case 11:
                                $text = $value != '' ? '-' . intval(substr($value, 0, 2)) . '°C÷' . intval(substr($value, -2)) . '°C' : '';
                                break;
                            case 12:
                                $text = $value != '' ? intval(substr($value, 0, 2)) . ' ' . $configurator_translations[$key + 1] : '';
                                break;
                            case 13:
                                $text = ($value != '' && $configurator_translations[$key + 1][$value] != '???') ? $configurator_translations[$key + 1][$value] : '';
                                break;
                        }

                        $html .= '<td ><span>' . $value . '</span>';
                        $html .= $value != '' ? $text : '';
                        $html .= '</td>';
                    }
                    $html .= '</tr>';
                    $html .= '</table>';
                }

                $html .= '</section>';
            }
            if (in_array('example', $this->options['sections'])) {
                $html .= ' <section class="example">
                <h2 class="example__title">' . $data['translations']['example'] . '</h2>
                <p class="example__text">' . $data['maping']['sku'] . '</p></section>';
            }
            $html .= '</div><footer class="footer"><p class="footer__text">' . $data['translations']['footer'] . '</p><img src="' . asset($icons['logo']) . '" alt="" class="footer__image"></footer>';
            $html .= '</body></html>';
            if ($lang == 'pl') {
                $create_html = isset($this->options['pl_create_html']) ? $this->options['pl_create_html'] : false;
                $html_filename = isset($this->options['pl_html_filename']) ? $this->options['pl_html_filename'] : '';
                $html_externalDisk = isset($this->options['pl_html_externalDisk']) ? $this->options['pl_html_externalDisk'] : false;
                $html_externalDisk_path = isset($this->options['pl_html_externalDisk_path']) ? $this->options['pl_html_externalDisk_path'] : '';

                $create_pdf = isset($this->options['pl_create_pdf']) ? $this->options['pl_create_pdf'] : false;
                $pdf_filename = isset($this->options['pl_pdf_filename']) ? $this->options['pl_pdf_filename'] : '';
                $pdf_externalDisk = isset($this->options['pl_pdf_externalDisk']) ? $this->options['pl_pdf_externalDisk'] : false;
                $pdf_externalDisk_path = isset($this->options['pl_pdf_externalDisk_path']) ? $this->options['pl_pdf_externalDisk_path'] : '';

                $ftp_account = 'family_luxon_ftp_pl';
            }

            if ($lang == 'de') {
                $create_html = isset($this->options['de_create_html']) ? $this->options['de_create_html'] : false;
                $html_filename = isset($this->options['de_html_filename']) ? $this->options['de_html_filename'] : '';
                $html_externalDisk = isset($this->options['de_html_externalDisk']) ? $this->options['de_html_externalDisk'] : false;
                $html_externalDisk_path = isset($this->options['de_html_externalDisk_path']) ? $this->options['de_html_externalDisk_path'] : '';

                $create_pdf = isset($this->options['de_create_pdf']) ? $this->options['de_create_pdf'] : false;
                $pdf_filename = isset($this->options['de_pdf_filename']) ? $this->options['de_pdf_filename'] : '';
                $pdf_externalDisk = isset($this->options['de_pdf_externalDisk']) ? $this->options['de_pdf_externalDisk'] : false;
                $pdf_externalDisk_path = isset($this->options['de_pdf_externalDisk_path']) ? $this->options['de_pdf_externalDisk_path'] : '';

                $ftp_account = 'family_luxon_ftp_de';
            }

            if ($lang == 'en') {
                $create_html = isset($this->options['en_create_html']) ? $this->options['en_create_html'] : false;
                $html_filename = isset($this->options['en_html_filename']) ? $this->options['en_html_filename'] : '';
                $html_externalDisk = isset($this->options['en_html_externalDisk']) ? $this->options['en_html_externalDisk'] : false;
                $html_externalDisk_path = isset($this->options['en_html_externalDisk_path']) ? $this->options['en_html_externalDisk_path'] : '';

                $create_pdf = isset($this->options['en_create_pdf']) ? $this->options['en_create_pdf'] : false;
                $pdf_filename = isset($this->options['en_pdf_filename']) ? $this->options['en_pdf_filename'] : '';
                $pdf_externalDisk = isset($this->options['en_pdf_externalDisk']) ? $this->options['en_pdf_externalDisk'] : false;
                $pdf_externalDisk_path = isset($this->options['en_pdf_externalDisk_path']) ? $this->options['en_pdf_externalDisk_path'] : '';

                $ftp_account = 'family_luxon_ftp_en';
            }


            $luminaireFamily = LuminaireFamily::find($this->luminaireFamily->id);
            $html_filePaths = json_decode($luminaireFamily->html_filepath, true) ?? [];
            $html_filePaths[$lang] = '';

            if ($create_html) {
                $filename = $html_filename != '' ? $html_filename : $this->luminaireFamily->name . '-' . $lang . '.html';

                if ($html_externalDisk) {
                    $externalDisk_path = $html_externalDisk_path != '' ? $html_externalDisk_path : '/';
                    $fullpath = $externalDisk_path . $filename;

                    Storage::disk($ftp_account)->put($fullpath, $html);

                    if($lang == 'pl'){
                        $html_filePaths[$lang] = 'https://luxon.pl/PIM' . $fullpath;
                    }else if($lang == 'de'){
                        $html_filePaths[$lang] = 'https://luxonled.de/PIM' . $fullpath;
                    }else if($lang == 'en'){
                        $html_filePaths[$lang] = 'https://luxonled.eu/PIM' . $fullpath;
                    }
                    // $html_filePaths[$lang] = 'https://devst.pl/generator/' . $lang . $fullpath;
                } else {
                    $localPath = '/' . $this->luminaireFamily->name . '/' . $filename;
                    Storage::disk('public')->put($localPath, $html);

                    $html_filePaths[$lang] = 'https://generator.luxon.pl/storage/' . $this->luminaireFamily->name . '/' . $filename;
                }

                $luminaireFamily->html_filepath = json_encode($html_filePaths);
                $luminaireFamily->save();
            }
            $pdf_filePaths = json_decode($luminaireFamily->pdf_filepath, true) ?? [];
            $pdf_filePaths[$lang] = '';
            if ($create_pdf) {
                $filename = '';
                if ($pdf_filename != '') {
                    $filename = $pdf_filename;
                } else {
                    $filename = $this->luminaireFamily->name . '-' . $lang . '.pdf';
                }

                if ($pdf_externalDisk) {
                    if ($pdf_externalDisk_path != '') {
                        $externalDisk_path = $pdf_externalDisk_path;
                    } else {
                        $externalDisk_path = '/';
                    }
                    $fullpath = $externalDisk_path . $filename;

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
                        ->setNpmBinary(env('NPMBinary'))
                        // ->setChromePath(env('Chrome'))
                        ->setNodeBinary(env('NodeBinary'))
                        ->save(storage_path('app/private/pdf_temp/' . $filename));

                    $pdfFile = Storage::disk('local')->get('pdf_temp/' . $filename);
                    Storage::disk($ftp_account)->put($fullpath, $pdfFile);


                    if($lang == 'pl'){
                        $pdf_filePaths[$lang] = 'https://luxon.pl/PIM' . $fullpath;
                    }else if($lang == 'de'){
                        $pdf_filePaths[$lang] = 'https://luxonled.de/PIM' . $fullpath;
                    }else if($lang == 'en'){
                        $pdf_filePaths[$lang] = 'https://luxonled.eu/PIM' . $fullpath;
                    }
                    // $pdf_filePaths[$lang] = 'https://devst.pl/generator/pl/' . $fullpath;
                } else {
                    if (!Storage::disk('public')->exists($this->luminaireFamily->name)) {
                        Storage::disk('public')->makeDirectory($this->luminaireFamily->name);
                    }
                    Browsershot::html($html)
                        ->format('A4')
                        ->showBackground()
                        // ->setChromePath(env('Chrome'))
                        ->setNpmBinary(env('NPMBinary'))
                        ->setNodeBinary(env('NodeBinary'))
                        ->save(storage_path('app/public/' . $this->luminaireFamily->name . '/' . $filename));

                    $pdf_filePaths[$lang] = 'https://generator.luxon.pl/storage/' . $this->luminaireFamily->name . '/' . $filename;
                }

                $luminaireFamily->pdf_filepath = json_encode($pdf_filePaths);
                $luminaireFamily->save();
                Log::create(
                    [
                        'subject' => 'Generowanie Karty Katalogowej Rodziny: ' . $luminaireFamily->name,
                        'user_id' => $this->user->id,
                        'info' => json_encode($pdf_filePaths),
                        'error' => '',
                        'status' => 'success'
                    ]
                );
            }
        }
    }
    public function handle(): void
    {
        $this->parseData();
        $this->render_and_save_files();
        Notification::make('')
            ->title("Karta rodziny " . $this->luminaireFamily->name . " została wygenerowana")
            ->success()
            ->actions([
                \Filament\Notifications\Actions\Action::make('complete')
                    ->label('Zobacz')
                    ->button()
                    ->url(route('filament.admin.resources.luminaire-families.view', $this->luminaireFamily), shouldOpenInNewTab: true),
            ])
            ->sendToDatabase($this->user);
    }
    public function failed(?Throwable $exception): void
    {
        // Send user notification of failure, etc...
        Log::create(
            [
                'subject' => 'Generowanie Karty Katalogowej Rodziny: ' . $this->luminaireFamily->name,
                'user_id' => $this->user->id,
                'info' => '',
                'error' => $exception,
                'status' => 'error'
            ]
        );
    }
}
