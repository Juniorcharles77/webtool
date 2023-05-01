<?php

namespace App\Http\Controllers;

use App\Bitflan;
use App\Models\Page;
use App\Settings\AdSettings;
use Illuminate\Support\Facades\View;
use App\Settings\GeneralSettings;
use App\Settings\LanguageSettings;
use App\Settings\SassFeatures;
use Illuminate\Http\Request;

class Base extends Controller {
    public function __construct(GeneralSettings $settings, AdSettings $adSettings, LanguageSettings $languageSettings, SassFeatures $sassSettings, Request $request)
    {
        View::share([
            'navigationPages'  => Page::query()->select([ 'title', 'slug', 'location' ])->orderBy('order')->get(),
            'generalSettings'  => $settings,
            'adSettings'       => $adSettings,
            'bitflanSettings'  => Bitflan::class,
            'languageSettings' => $languageSettings,
            'sass'             => $sassSettings,
            'locale'           => get_locale(),
            'theme'            => $settings->darkTheme ? $request->cookie('theme', $settings->defaultTheme) : $settings->defaultTheme
        ]);
    }
}