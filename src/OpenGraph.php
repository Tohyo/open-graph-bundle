<?php

namespace Tohyo\OpenGraphBundle;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
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
        $extractedData = [];
        if (count($this->validator->validate($url, new Url())) > 0) {
            throw new \InvalidArgumentException(sprintf("This value is not a valid URL: %s", $url));
        }

        $crawler = new Crawler($this->client->request('GET', $url)->getContent());
        $crawler->filterXPath(self::OG_XPATH)->each(function (Crawler $node) use (&$extractedData) {
            $extractedData = array_merge(
                $extractedData,
                $this->buildPropertyArray($node->attr('property'), $node->attr('content'))
            );
        });

        $this->buildOpenGraphData($extractedData);

        $this->resetPropertyWhenValidationFails($this->validator->validate($this->openGraphData));

        return $this->openGraphData;
    }

    private function buildOpenGraphData(array $extractedData): void
    {
        foreach ($extractedData as $propertyName => $propertyValue)
        if (property_exists($this->openGraphData, $propertyName)) {
            $this->openGraphData->{$propertyName} = $propertyValue;
        } else {
            $this->openGraphData->others = [$propertyName => $propertyValue];
        }
    }

    private function buildPropertyArray(string $domProperty, string $content): array
    {
        preg_match('/og:([a-zA-Z0-9]+):*([a-zA-Z0-9]*)/', $domProperty, $matches);

        if ($matches[2] !== "") {
            return [
                $matches[1] => [
                    $matches[2] => $content
                ]
            ];
        }

        return [
            $matches[1] => $content
        ];
    }
    
    private function resetPropertyWhenValidationFails(ConstraintViolationList $constraintViolationList): void
    {
        foreach ($constraintViolationList as $constraintViolation) {
            $this->openGraphData->{$constraintViolation->getPropertyPath()} = null;
        }
    }
}