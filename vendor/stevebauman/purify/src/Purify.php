<?php

namespace Stevebauman\Purify;

use HTMLPurifier;
use HTMLPurifier_Config;

class Purify
{
    /**
     * The HTML Purifier instance.
     *
     * @var HTMLPurifier
     */
    protected $purifier;

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(HTMLPurifier_Config $config)
    {
        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Sanitize the given input.
     *
     * @param array|string $input
     *
     * @return array|string
     */
    public function clean($input)
    {
        return is_array($input)
            ? $this->purifier->purifyArray($input)
            : $this->purifier->purify($input);
    }
}
