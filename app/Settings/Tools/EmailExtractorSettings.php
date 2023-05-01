<?php

namespace App\Settings\Tools;

use Spatie\LaravelSettings\Settings;

class EmailExtractorSettings extends BaseToolSetting {
    public static function group(): string {
        return 'tool-email-extractor';
    }
}