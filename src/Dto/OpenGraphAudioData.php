<?php

namespace Tohyo\OpenGraphBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class OpenGraphAudioData
{
    public ?string $url = null;

    public ?string $secureUrl = null;

    public ?string $type = null;
}