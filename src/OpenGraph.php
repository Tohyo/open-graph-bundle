<?php

namespace Tohyo\OpenGraphBundle;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Tohyo\OpenGraphBundle\Dto\OpenGraphData;

class OpenGraph
{
    public function __construct(
        public HttpClientInterface $client
    ) {
    }

    public function getData(string $url): OpenGraphData
    {
        $openGraphData = new OpenGraphData();

        $crawler = new Crawler($this->client->request('GET', $url)->getContent());
        $crawler->filterXPath('//meta[contains(@property, "og:")]')->each(function (Crawler $node) use ($openGraphData) {
            if (property_exists($openGraphData, str_replace('og:', '',$node->attr('property')))) {
                $openGraphData->{str_replace('og:', '',$node->attr('property'))} = $node->attr('content');
            } else {
                $openGraphData->others = [
                    str_replace('og:', '', $node->attr('property')) => $node->attr('content')
                ];
            }
        });

        return $openGraphData;
    }
}