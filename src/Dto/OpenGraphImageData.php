<?php

namespace Tohyo\OpenGraphBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class OpenGraphImageData
{
    public ?string $url = null;

    public ?string $secureUrl = null;

    public ?string $type = null;

    public ?string $width = "";

    public ?string $height = "";

    public ?string $alt = "";
}