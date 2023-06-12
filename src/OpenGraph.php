<?php

namespace Tohyo\OpenGraphBundle;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Tohyo\OpenGraphBundle\Dto\OpenGraphData;

class OpenGraph
{
    private const OG_XPATH = '//meta[contains(@property, "og:")]';

    private OpenGraphData $openGraphData;

    public function __construct(
        public HttpClientInterface $client
    ) {
        $this->openGraphData = new OpenGraphData();
    }

    public function getData(string $url): OpenGraphData
    {
        $crawler = new Crawler($this->client->request('GET', $url)->getContent());
        $crawler->filterXPath(self::OG_XPATH)->each(function (Crawler $node) {

            $this->buildOpenGraphData(
                $this->sanitizePropertyName($node->attr('property')),
                $node->attr('content')
            );
        });

        return $this->openGraphData;
    }

    private function buildOpenGraphData(string $propertyName, string $propertyValue): void
    {
        if (property_exists($this->openGraphData, $propertyName)) {
            $this->openGraphData->{$propertyName} = $propertyValue;
        } else {
            $this->openGraphData->others = [$propertyName => $propertyValue];
        }
    }

    private function sanitizePropertyName(string $property): string
    {
        return str_replace('og:', '', $property);
    }
}