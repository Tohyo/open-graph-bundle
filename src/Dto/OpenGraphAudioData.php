<?php

namespace Tohyo\OpenGraphBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class OpenGraphAudioData
{
    #[Assert\Url]
    public ?string $url = null;

    #[Assert\Url]
    public ?string $secureUrl = null;

    public ?string $type = null;
}