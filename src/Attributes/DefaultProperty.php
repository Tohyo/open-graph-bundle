<?php

namespace Tohyo\OpenGraphBundle\Attributes;

#[\Attribute]
class DefaultProperty
{
    public function __construct(
        public string $property
    ) {}
}