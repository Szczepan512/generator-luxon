<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class IndividualCardSettings extends Settings
{

    protected array $casts = [
        'maping',
        'pl_links',
        'pl_maping',
        'pl_fixed_translations',
        'pl_translations',
        'en_links',
        'en_maping',
        'en_fixed_translations',
        'en_translations',
        'de_links',
        'de_maping',
        'de_fixed_translations',
        'de_translations',
        'en_montage',
        'pl_montage',
        'de_montage',
    ];
    public string $ftp_html_path_polish = '';
    public string $ftp_html_path_deutch = '';
    public string $ftp_html_path_english = '';
    public string $ftp_pdf_path_polish = '';
    public string $ftp_pdf_path_deutch = '';
    public string $ftp_pdf_path_english = '';
    public array $maping = [];
    public array $pl_links = [];
    public array $pl_maping = [];
    public array $pl_fixed_translations = [];
    public array $pl_translations = [];
    public string $pl_description = '';
    public array $en_links = [];
    public array $en_maping = [];
    public array $en_fixed_translations = [];
    public array $en_translations = [];
    public string $en_description = '';
    public array $de_links = [];
    public array $de_maping = [];
    public array $de_fixed_translations = [];
    public array $de_translations = [];
    public string $de_description = '';

    public array $en_montage = [];
    public array $pl_montage = [];
    public array $de_montage = [];
    public static function group(): string
    {
        return 'individualCard';
    }
}