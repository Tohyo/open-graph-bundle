<?php

namespace Tohyo\OpenGraphBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tohyo\OpenGraphBundle\Dto\OpenGraphData;
use Tohyo\OpenGraphBundle\OpenGraph;

class UnitTest extends TestCase
{
    public function testUnit()
    {
        $response = new MockResponse(
            <<<'HTML'
<!DOCTYPE html>
<html>
    <head>
    <meta property="og:title" content="website-title">
    <meta property="og:url" content="website-url">
    <meta property="og:type" content="website-type">
    <meta property="og:image" content="website-image">
    <meta property="og:description" content="website-description">
    <meta property="og:locale" content="website-locale">
    <meta property="og:nook" content="nook">
</head>
    <body>
        
    </body>
</html>
HTML
        );

        $openGraphService = new OpenGraph(new MockHttpClient($response));
        $openGraphData = $openGraphService->getData('url');

        $this->assertInstanceOf(OpenGraphData::class, $openGraphData);

        $this->assertSame('website-title', $openGraphData->title);
        $this->assertSame('website-url', $openGraphData->url);
        $this->assertSame('website-type', $openGraphData->type);
        $this->assertSame('website-image', $openGraphData->image);
        $this->assertSame('website-description', $openGraphData->description);
        $this->assertSame('website-locale', $openGraphData->locale);
        $this->assertArrayHasKey('nook', $openGraphData->others);
        $this->assertContains('nook', $openGraphData->others);
    }
}