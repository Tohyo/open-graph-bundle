<?php

namespace Tohyo\OpenGraphBundle;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Tohyo\OpenGraphBundle\Attributes\DefaultProperty;
use Tohyo\OpenGraphBundle\Dto\OpenGraphData;
use Tohyo\OpenGraphBundle\Utils\PropertyCamelizer;

class OpenGraph
{
    private const OG_XPATH = '//meta[contains(@property, "og:")]';

    private OpenGraphData $openGraphData;

    public function __construct(
        public HttpClientInterface $client,
        public ValidatorInterface $validator,
        public bool $validateData = false
    ) {
        $this->openGraphData = new OpenGraphData();
    }

    public function getData(string $url): OpenGraphData
    {
        if (count($this->validator->validate($url, new Url())) > 0) {
            throw new \InvalidArgumentException(sprintf("This value is not a valid URL: %s", $url));
        }

        $crawler = new Crawler($this->client->request('GET', $url)->getContent());
        $crawler->filterXPath(self::OG_XPATH)->each(function (Crawler $node) {
            $this->buildProperty($node->attr('property'), $node->attr('content'));
        });

        if ($this->validateData) {
            $this->resetPropertyWhenValidationFails($this->validator->validate($this->openGraphData));
        }

        return $this->openGraphData;
    }

    private function buildProperty(string $domProperty, string $content): void
    {
        $reflection = new \ReflectionClass($this->openGraphData);
        preg_match('/og:([a-zA-Z0-9_]+):*([a-zA-Z0-9_]*)/', $domProperty, $matches);

        $propertyName = PropertyCamelizer::camelize($matches[1]);

        if (property_exists($this->openGraphData, $propertyName)) {
            $defaultProperty = $reflection->getProperty($propertyName)->getAttributes(DefaultProperty::class);

            if (empty($defaultProperty)) {
                $this->openGraphData->{$propertyName} = $content;
            } else {
                if ($matches[2]) {
                    $this->openGraphData->{$matches[1]}->{PropertyCamelizer::camelize($matches[2])} = $content;
                } else {
                    $this->openGraphData->{$matches[1]}->{PropertyCamelizer::camelize($defaultProperty[0]->getArguments()[0])} = $content;
                }
            }
        }
    }

    private function resetPropertyWhenValidationFails(ConstraintViolationList $constraintViolationList): void
    {
        foreach ($constraintViolationList as $constraintViolation) {
            $propertyPath = explode('.', $constraintViolation->getPropertyPath());
            try {
                if (isset($propertyPath[1])) {
                    $this->openGraphData->{$propertyPath[0] ?? ''}->{$propertyPath[1] ?? ''} = null;
                } else {
                    $this->openGraphData->{$constraintViolation->getPropertyPath()} = null;
                }
            } catch (\Error $e) {
                continue;
            }
        }
    }
}

