<?php

namespace Tohyo\OpenGraphBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

 class OpenGraphData
{
        public ?string $title = null;

        #[Assert\Url]
        public ?string $url = null;

        public ?string $type = null;

        public ?string $image = null;

        public ?string $description = null;

        public ?string $locale = null;

        public array $others = [];
}