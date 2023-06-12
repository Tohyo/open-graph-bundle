<?php

namespace Tohyo\OpenGraphBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Validator\ValidatorBuilder;
use Tohyo\OpenGraphBundle\Dto\OpenGraphData;
use Tohyo\OpenGraphBundle\OpenGraph;

class UnitTest extends TestCase
{
    public function test_it_populates_dto_correctly()
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

        $openGraphService = new OpenGraph(
            new MockHttpClient($response),
            (new ValidatorBuilder())->getValidator()
        );
        $openGraphData = $openGraphService->getData('http://test-open-graph-url.com');

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

    public function test_it_throws_a_invalid_argument_exception_when_url_is_not_valid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $openGraphService = new OpenGraph(
            new MockHttpClient(),
            (new ValidatorBuilder())->getValidator()
        );

        $openGraphService->getData('not-a-url');
    }
}