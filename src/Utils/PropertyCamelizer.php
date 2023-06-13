<?php

namespace Tohyo\OpenGraphBundle\Utils;

class PropertyCamelizer
{
    public static function camelize(string $string): string
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }
}