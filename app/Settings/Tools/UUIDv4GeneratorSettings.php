<?php

namespace App\Settings\Tools;

use Spatie\LaravelSettings\Settings;

class UUIDv4GeneratorSettings extends BaseToolSetting {
    public static function group(): string {
        return 'tool-uuidv4-generator';
    }
}