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

        #[DefaultProperty('url')]
        public OpenGraphAudioData $audio;

        public ?string $description = null;

        public ?string $locale = null;

        public ?string $determiner = null;

        public ?string $siteName = null;

        public function __construct()
        {
            $this->image = new OpenGraphImageData();
            $this->video = new OpenGraphVideoData();
            $this->audio = new OpenGraphAudioData();
        }
}