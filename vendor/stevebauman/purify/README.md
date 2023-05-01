# Purify

[![GitHub Actions](https://img.shields.io/github/workflow/status/stevebauman/purify/run-tests.svg?style=flat-square)](https://github.com/stevebauman/purify/actions)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/stevebauman/purify.svg?style=flat-square)](https://scrutinizer-ci.com/g/stevebauman/purify/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/stevebauman/purify.svg?style=flat-square)](https://packagist.org/packages/stevebauman/purify)
[![Total Downloads](https://img.shields.io/packagist/dt/stevebauman/purify.svg?style=flat-square)](https://packagist.org/packages/stevebauman/purify)
[![License](https://img.shields.io/packagist/l/stevebauman/purify.svg?style=flat-square)](https://packagist.org/packages/stevebauman/purify)

Purify is a Laravel wrapper around [HTMLPurifier](https://github.com/ezyang/htmlpurifier) by [ezyang](https://github.com/ezyang).

### Requirements

-   PHP >= 7.4
-   Laravel >= 7.0

### Installation

To install Purify, run the following command in the root of your project:

```bash
composer require stevebauman/purify
```

Then, publish the configuration file using:

```bash
php artisan vendor:publish --provider="Stevebauman\Purify\PurifyServiceProvider"
```

### Upgrading from v4 to v5

To upgrade from v4, install the latest version by running the below command in the root of your project:

```bash
composer require stevebauman/purify
```

Then, navigate into your published `config/purify.php` configuration file and
copy the `settings` array -- except for the following keys:

-   `HTML.DocType`:
-   `Core.Encoding`:
-   `Cache.SerializerPath`:

```diff
'settings' => [
-   'Core.Encoding' => 'utf-8',
-   'Cache.SerializerPath' => storage_path('app/purify'),
-   'HTML.Doctype' => 'XHTML 1.0 Strict',
+   'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span,img[width|height|alt|src]',
+   'HTML.ForbiddenElements' => '',
+   'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
+   'AutoFormat.AutoParagraph' => false,
+   'AutoFormat.RemoveEmpty' => false,
],
```

> **Important**: If you've created a unique storage path for `Cache.SerializerPath`,
> take note of this as well, so you can migrate it into the new configuration file.

Once copied, delete the `config/purify.php` file, and run the below command:

```bash
php artisan vendor:publish --provider="Stevebauman\Purify\PurifyServiceProvider"
```

Then, inside the newly published `config/purify.php` configuration file, paste
the keys (overwriting the current) into the `configs.default` array:

```diff
'configs' => [
    'default' => [
        'Core.Encoding' => 'utf-8',
        'HTML.Doctype' => 'HTML 4.01 Transitional',
+       'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span,img[width|height|alt|src]',
+       'HTML.ForbiddenElements' => '',
+       'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
+       'AutoFormat.AutoParagraph' => false,
+       'AutoFormat.RemoveEmpty' => false,
    ],
],
```

If you've created a unique serializer path (previously set via the old `Cache.SerializerPath` configuration
key mentioned above), then you may reconfigure this in the new `serializer` configuration key:

```php
'serializer' => storage_path('app/purify'),
```

You're all set!

### Usage

##### Cleaning a String

To clean a users input, simply use the clean method:

```php
$input = '<script>alert("Harmful Script");</script> <p style="border:1px solid black" class="text-gray-700">Test</p>';

// Returns '<p>Test</p>'
$cleaned = Purify::clean($input);
```

##### Cleaning an Array

Need to purify an array of user input? Just pass in an array:

```php
$array = [
    '<script>alert("Harmful Script");</script> <p style="border:1px solid black" class="text-gray-700">Test</p>',
    '<script>alert("Harmful Script");</script> <p style="border:1px solid black" class="text-gray-700">Test</p>',
];

$cleaned = Purify::clean($array);

// array [
//  '<p>Test</p>',
//  '<p>Test</p>',
// ]
var_dump($cleaned);
```

##### Dynamic Configuration

Need a different configuration for a single input? Pass in a configuration array into the second parameter:

> **Note**: Configuration passed into the second parameter
> is **not** merged with your default configuration.

```php
$config = ['HTML.Allowed' => 'div,b,a[href]'];

$cleaned = Purify::config($config)->clean($input);
```

### Configuration

Inside the configuration file, multiple HTMLPurifier configuration sets
can be specified, similar to Laravel's built-in `database`, `mail` and `logging` config.
Simply call `Purify::config($name)->clean($input)` to use another set of configuration.

For HTMLPurifier configuration documentation, please visit the HTMLPurifier Website:

http://htmlpurifier.org/live/configdoc/plain.html

### Practices

If you're looking into sanitization, you're likely wanting to sanitize inputted user HTML
content that is then stored in your database to be rendered onto your application.

In this scenario, it's likely best practice to sanitize on the _way out_ instead of
the on the _way in_. The **database doesn't care what text it contains**.

This way you can allow anything to be inserted in the database, and have strong sanization rules on the way out.

To accomplish this, you may use the provided `PurifyHtmlOnGet` cast class on your Eloquent model:

```php
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;

class Post extends Model
{
    protected $casts = [
        'content' => PurifyHtmlOnGet::class,
    ];
}
```

Or, implement it yourself via an Eloquent attribute mutator:

```php
use Stevebauman\Purify\Facades\Purify;

class Post extends Model
{
    public function getContentAttribute($value)
    {
        return Purify::clean($value);
    }
}
```

You can even configure the configuration that is used when casting by appending it's name to the cast:

```php
// config/purify.php

'configs' => [
    // ...

    'other' => [
        // Some configuration ...
    ],
]
```

```php
protected $casts = [
    'content' => PurifyHtmlOnGet::class.':other',
];
```

This helps tremendously if you change your sanization requirements later down
the line, then all rendered content will follow these sanization rules.

If you'd like to purify HTML while setting the value, you can use the inverse `PurifyHtmlOnSet` cast instead.

#### Custom HTML definitions

The `HTML.Doctype` configuration option denotes the schema to ultimately abide to.
You may want to extend these schema definitions to support custom elements or
attributes (e.g. `<foo>...</foo>`, or `<span foo="...">`) by specifying a
custom HTML element "definitions".

Purify ships with additional HTML5 definitions that HTMLPurifier does
not (yet) support of the box (via the `Html5Definition` class).

To create your own HTML definition, create a new class and have it implement `Definition`:

```php
namespace App;

use HTMLPurifier_HTMLDefinition;
use Stevebauman\Purify\Definitions\Definition;

class CustomDefinition implements Definition
{
    /**
     * Apply rules to the HTML Purifier definition.
     *
     * @param HTMLPurifier_HTMLDefinition $definition
     *
     * @return void
     */
    public static function apply(HTMLPurifier_HTMLDefinition $definition)
    {
        // Customize the HTML purifier definition.
    }
}
```

Then, reference this class in the `config/purify.php` file in the `definitions` key:

```php
// config/purify.php

'definitions' => \App\CustomDefinitions::class,
```

Here's an example for customizing the definition in order to support Basecamp's Trix WYSIWYG editor
(credit to [Antonio Primera](https://github.com/stevebauman/purify/issues/7)):

```php
namespace App;

use HTMLPurifier_HTMLDefinition;
use Stevebauman\Purify\Definitions\Definition;

class TrixPurifierDefinitions implements Definition
{
    /**
     * Apply rules to the HTML Purifier definition.
     *
     * @param HTMLPurifier_HTMLDefinition $definition
     *
     * @return void
     */
    public static function apply(HTMLPurifier_HTMLDefinition $definition)
    {
        $definition->addElement('figure', 'Inline', 'Inline', 'Common');
        $definition->addAttribute('figure', 'class', 'Text');
        $definition->addElement('figcaption', 'Inline', 'Inline', 'Common');
        $definition->addAttribute('figcaption', 'class', 'Text');
        $definition->addAttribute('figcaption', 'data-trix-placeholder', 'Text');

        $definition->addAttribute('a', 'rel', 'Text');
        $definition->addAttribute('a', 'tabindex', 'Text');
        $definition->addAttribute('a', 'contenteditable', 'Enum#true,false');
        $definition->addAttribute('a', 'data-trix-attachment', 'Text');
        $definition->addAttribute('a', 'data-trix-content-type', 'Text');
        $definition->addAttribute('a', 'data-trix-id', 'Number');

        $definition->addElement('span', 'Block', 'Flow', 'Common');
        $definition->addAttribute('span', 'data-trix-cursor-target', 'Enum#right,left');
        $definition->addAttribute('span', 'data-trix-serialize', 'Enum#true,false');

        $definition->addAttribute('img', 'data-trix-mutable', 'Enum#true,false');
        $definition->addAttribute('img', 'data-trix-store-key', 'Text');
    }
}
```
