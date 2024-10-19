<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FamilyCardSettings extends Settings
{
    protected array $casts = [
        'maping',
        'pl_translations',
        'pl_installations',
        'pl_columns',
        'pl_maping',
        'pl_configurator',
        'de_translations',
        'de_installations',
        'de_columns',
        'de_maping',
        'de_configurator',
        'en_translations',
        'en_installations',
        'en_columns',
        'en_maping',
        'en_configurator',
        'icons'
    ];
    public array $icons = [];
    public array $maping = [];
    public array $pl_translations = [];
    public array $pl_installations = [];
    public array $pl_columns = [];
    public array $pl_maping = [];
    public array $pl_configurator = [];
    public array $de_translations = [];
    public array $de_installations = [];
    public array $de_columns = [];
    public array $de_maping = [];
    public array $de_configurator = [];
    public array $en_translations = [];
    public array $en_installations = [];
    public array $en_columns = [];
    public array $en_maping = [];
    public array $en_configurator = [];
    public static function group(): string
    {
        return 'familyCard';
    }
}