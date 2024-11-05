<?php

namespace Tohyo\OpenGraphBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class OpenGraphAudioData
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
}

