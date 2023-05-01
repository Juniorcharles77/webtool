<?php

use App\Models\BlogPost;
use App\Models\Page;
use App\Settings\GeneralSettings;
use App\Settings\LanguageSettings;
use App\Settings\SassFeatures;
use App\Settings\ToolSlugSettings;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

if( ! function_exists( 'storage_url' ) ) {
    function storage_url($param) {
        return url( Storage::url($param) );
    }
}

if( ! function_exists( 'unique_public_id' ) ) {
    function unique_public_id($limit = 9) {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
}

if( ! function_exists( 'is_cloudflare_https' ) ) {
    function is_cloudflare_https() {
        return isset($_SERVER['HTTPS']) ||
            (isset($_SERVER['HTTP_CF_VISITOR']) && (($visitor = json_decode($_SERVER['HTTP_CF_VISITOR'])) &&
                $visitor->scheme == 'https'));
    }
}

if( ! function_exists( 'country_name' ) ) {
    function country_name( $code ) {
        $countryList = array(
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "BQ" => "British Antarctic Territory",
            "IO" => "British Indian Ocean Territory",
            "VG" => "British Virgin Islands",
            "BN" => "Brunei",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CT" => "Canton and Enderbury Islands",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos [Keeling] Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo - Brazzaville",
            "CD" => "Congo - Kinshasa",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "CI" => "Côte d’Ivoire",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "NQ" => "Dronning Maud Land",
            "DD" => "East Germany",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "FQ" => "French Southern and Antarctic Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GG" => "Guernsey",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and McDonald Islands",
            "HN" => "Honduras",
            "HK" => "Hong Kong SAR China",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IM" => "Isle of Man",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JE" => "Jersey",
            "JT" => "Johnston Island",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Laos",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macau SAR China",
            "MK" => "Macedonia",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "FX" => "Metropolitan France",
            "MX" => "Mexico",
            "FM" => "Micronesia",
            "MI" => "Midway Islands",
            "MD" => "Moldova",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "ME" => "Montenegro",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar [Burma]",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NT" => "Neutral Zone",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "KP" => "North Korea",
            "VD" => "North Vietnam",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PC" => "Pacific Islands Trust Territory",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territories",
            "PA" => "Panama",
            "PZ" => "Panama Canal Zone",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "YD" => "People's Democratic Republic of Yemen",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn Islands",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RO" => "Romania",
            "RU" => "Russia",
            "RW" => "Rwanda",
            "RE" => "Réunion",
            "BL" => "Saint Barthélemy",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "MF" => "Saint Martin",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "CS" => "Serbia and Montenegro",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "KR" => "South Korea",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syria",
            "ST" => "São Tomé and Príncipe",
            "TW" => "Taiwan",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UM" => "U.S. Minor Outlying Islands",
            "PU" => "U.S. Miscellaneous Pacific Islands",
            "VI" => "U.S. Virgin Islands",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "SU" => "Union of Soviet Socialist Republics",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "ZZ" => "Unknown or Invalid Region",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VA" => "Vatican City",
            "VE" => "Venezuela",
            "VN" => "Vietnam",
            "WK" => "Wake Island",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe",
            "AX" => "Åland Islands",
        );

        $code = strtoupper($code);

        return isset($countryList[ $code ]) ? $countryList[ $code ] : 'Unknown';
    }
}

function is_valid_locale($code) {
    $settings = app(LanguageSettings::class);

    foreach($settings->languages as $language) {
        if($language['code'] == $code)
            return true;
    }

    return false;
}

if( !function_exists('get_locale') ) {
    function get_locale() {
        $settings = app(LanguageSettings::class);

        $code = strtolower(trim(app()->getLocale()));

        foreach($settings->languages as $language)
            if($code == strtolower(trim($language['code'])))
                return $language;
            
        if($code == 'en')
            return [
                'label' => 'English',
                'flag'  => 'GB',
                'code'  => 'en',
                'direction' => 'ltr'
            ];

        return null;
    }
}

if( !function_exists('get_tool_title_from_key') ) {
    function get_tool_title_from_key($name) {
        $settings = app(LanguageSettings::class);

        if($settings->translateTools) {
            foreach(config('tools.categories') as $cats) {
                foreach($cats['tools'] as $toolKey => $tool) {
                    if($toolKey == $name) {
                        return trans('webtools/tools/' . $tool['name'] . '.title');
                        break;
                    }
                }
            }
        }
            
    
        foreach(config('tools.categories') as $cats) {
            foreach($cats['tools'] as $toolKey => $tool) {
                if($toolKey == $name) {
                    $setts = app($tool['settings']);

                    return $setts->title;
                    break;
                }
            }
        }

        return 'Unknown';
    }
}

if( !function_exists('get_tool_title') ) {
    function get_tool_title($name, $default) {
        $settings = app(LanguageSettings::class);

        if($settings->translateTools)
            return str_replace("\\/", '/', trans('webtools/tools/' . $name . '.title'));
    
        return str_replace("\\/", '/', $default);
    }
}

if( !function_exists('get_tool_summary') ) {
    function get_tool_summary($name, $default) {
        $settings = app(LanguageSettings::class);

        if($settings->translateTools)
            return str_replace("\\/", '/', trans('webtools/tools/' . $name . '.summary'));
    
        return str_replace("\\/", '/', $default);
    }
}

if( !function_exists('get_tool_description') ) {
    function get_tool_description($name, $default) {
        $settings = app(LanguageSettings::class);

        if($settings->translateTools)
            return str_replace("\\/", '/', trans('webtools/tools/' . $name . '.description'));
    
        return str_replace("\\/", '/', $default);
    }
}

function web_protocol() {
	return ((isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on")) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == "https") ? "https" : "http") . "://";
}

function get_domain($url) {
	if(preg_match("#https?://#", $url) === 0) {
		$url = web_protocol() . $url;
	}
    $exp = explode('.', $url);
    if(count($exp) == 2) {
        return strtolower(parse_url($url, PHP_URL_HOST));
    } else {
        if(strpos($exp[0], 'www.') !== false) {
            $exp[0] = str_ireplace('www.', '', $exp[0]);
        }
        return strtolower(parse_url(join('.', $exp), PHP_URL_HOST));
    }
}

function get_tld($domain) {
	$domain = "https://".$domain;
	$ext = pathinfo($domain, PATHINFO_EXTENSION);
	return "." . $ext;
}

function getClientIp() {
    // foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
    //     if (array_key_exists($key, $_SERVER) === true){
    //         foreach (explode(',', $_SERVER[$key]) as $ip){
    //             $ip = trim($ip); // just to be safe
    //             if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
    //                 return $ip;
    //             }
    //         }
    //     }
    // }

    $ipAddress = '';
    if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
        // to get shared ISP IP address
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // check for IPs passing through proxy servers
        // check if multiple IP addresses are set and take the first one
        $ipAddressList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($ipAddressList as $ip) {
            if (! empty($ip)) {
                // if you prefer, you can check for valid IP address here
                $ipAddress = $ip;
                break;
            }
        }
    } else if (! empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (! empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } else if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (! empty($_SERVER['HTTP_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED'];
    } else if (! empty($_SERVER['REMOTE_ADDR'])) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }
    
    if($ipAddress)
        return $ipAddress;

    return 'Not Found';
}

function generate_new_sitemap() {
    $slugs   = app(ToolSlugSettings::class);
    $sitemap = SitemapGenerator::create( url('/') )->getSitemap();

    $sitemap->add(
        Url::create("/")
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        ->setPriority(1.0)
    );

    foreach(config('tools.categories') as $category) {
        foreach($category['tools'] as $key => $tool) {
            if(isset($slugs->{$key})) {
                $sitemap->add(
                    Url::create("/tool/{$slugs->{$key}}")
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.8)
                );
            }
        }
    }

    foreach(Page::all() as $page) {
        $sitemap->add(
            Url::create("/page/{$page->slug}")
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.6)
        );
    }

    if(app(GeneralSettings::class)->contactPage) {
        $sitemap->add(
            Url::create("/contact")
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.6)
        );
    }

    if(app(GeneralSettings::class)->blogSection) {
        $sitemap->add(
            Url::create("/blog")
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.6)
        );

        foreach(BlogPost::all() as $post) {
            $sitemap->add(
                Url::create("/blog/{$post->slug}")
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.6)
            );
        }
    }

    $sitemap->writeToFile(public_path('sitemap.xml'));
}

function can_use($tool) {
    $settings = app(SassFeatures::class);

    if(auth()->check() && auth()->user()->premium())
        return true;

    if(!in_array($tool, $settings->premiumTools))
        return true;

    return false;
}

function str_rot($s, $n = 13) {
    $n = (int)$n % 26;
    if (!$n) return $s;
    for ($i = 0, $l = strlen($s); $i < $l; $i++) {
        $c = ord($s[$i]);
        if ($c >= 97 && $c <= 122) {
          $s[$i] = chr(($c - 71 + $n) % 26 + 97);
        } else if ($c >= 65 && $c <= 90) {
          $s[$i] = chr(($c - 39 + $n) % 26 + 65);
        }
    }
    return $s;
  }