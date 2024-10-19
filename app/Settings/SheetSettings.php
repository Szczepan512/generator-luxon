<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SheetSettings extends Settings
{
    protected array $casts = [
        'sheets_ids',
        'sheet_headings'
    ];
    public array $sheets_ids = [];
    public array $sheet_headings = [];
    public static function group(): string
    {
        return 'adminGroup';

    }
}