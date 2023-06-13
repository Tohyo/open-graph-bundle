<?php

namespace Tohyo\OpenGraphBundle;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Tohyo\OpenGraphBundle\Attributes\DefaultProperty;
use Tohyo\OpenGraphBundle\Dto\OpenGraphData;

class OpenGraph
{
    private const OG_XPATH = '//meta[contains(@property, "og:")]';

    private OpenGraphData $openGraphData;

    public function __construct(
        public HttpClientInterface $client,
        public ValidatorInterface $validator
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

        $this->resetPropertyWhenValidationFails($this->validator->validate($this->openGraphData));

        return $this->openGraphData;
    }



    private function buildProperty(string $domProperty, string $content): void
    {
        $reflection = new \ReflectionClass($this->openGraphData);
        preg_match('/og:([a-zA-Z0-9]+):*([a-zA-Z0-9]*)/', $domProperty, $matches);

        if (property_exists($this->openGraphData, $matches[1])) {
            $defaultProperty = $reflection->getProperty($matches[1])->getAttributes(DefaultProperty::class);

            if (empty($defaultProperty)) {
                $this->openGraphData->{$matches[1]} = $content;
            } else {
                $this->openGraphData->{$matches[1]}->{$defaultProperty[0]->getArguments()[0]} = $content;
            }
        }
    }

    private function resetPropertyWhenValidationFails(ConstraintViolationList $constraintViolationList): void
    {
        foreach ($constraintViolationList as $constraintViolation) {
            $this->openGraphData->{$constraintViolation->getPropertyPath()} = null;
        }
    }
}