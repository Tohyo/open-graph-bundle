<?php

namespace Tohyo\OpenGraphBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class OpenGraphVideoData
{
    #[Assert\Url]
    public ?string $url = null;

    #[Assert\Url]
    public ?string $secureUrl = null;

    public ?string $type = null;

    public ?string $width = "";

    public ?string $height = "";

    public ?string $alt = "";
}