<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('adminGroup.sheets_ids', [
            ['sheet_id' => '1ZmkrAdQ10A3waNprrj1v4YHJf6HR5lmhZTVSKildkIw'],
            ['sheet_id' => '1r19ejYPxwWBBzEHi1KNueWp4yjc5VliURjwitlWMAgk'],
            ['sheet_id' => '1oSm4YW7owDAvvkWre0twgKLFglasA6YmuVR2TvMpXqk']
        ]);
        $this->migrator->add('adminGroup.sheet_headings', []);



    }
};
