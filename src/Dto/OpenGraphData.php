<?php

namespace Tohyo\OpenGraphBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Tohyo\OpenGraphBundle\Attributes\DefaultProperty;

 class OpenGraphData
{
        public ?string $title = null;

        #[Assert\Url]
        public ?string $url = null;

        public ?string $type = null;

        #[DefaultProperty('url')]
        public OpenGraphImageData $image;

        #[DefaultProperty('url')]
        public OpenGraphVideoData $video;

        public ?string $description = null;

        public ?string $locale = null;

        public function __construct()
        {
            $this->image = new OpenGraphImageData();
            $this->video = new OpenGraphVideoData();
        }
}