<?php

namespace App\Settings\Tools;

use Spatie\LaravelSettings\Settings;

class ROT13DecoderSettings extends BaseToolSetting {
    public static function group(): string {
        return 'tool-rot13-decoder';
    }
}