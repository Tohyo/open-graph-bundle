<?php

namespace Tohyo\OpenGraphBundle\Dto;

 class OpenGraphData
{
        public ?string $title = null;

        public ?string $url = null;

        public ?string $type = null;

        public ?string $image = null;

        public ?string $description = null;

        public ?string $locale = null;

        public array $others = [];
}