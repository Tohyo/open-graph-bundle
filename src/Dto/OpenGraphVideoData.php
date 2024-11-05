<?php

namespace Tohyo\OpenGraphBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class OpenGraphVideoData
{
    #[Assert\Url(
        requireTld: true
    )]
    public ?string $url = null;

    #[Assert\Url(
        requireTld: true
    )]
    public ?string $secureUrl = null;

    public ?string $type = null;

    public ?string $width = null;

    public ?string $height = null;

    public ?string $alt = null;
}

