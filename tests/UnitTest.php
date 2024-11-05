<?php

namespace Tohyo\OpenGraphBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Validator\Validation;
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
        <meta property="og:url" content="https://www.good-url.com">
        <meta property="og:type" content="website-type">
        <meta property="og:image" content="website-image">
        <meta property="og:video" content="website-video">
        <meta property="og:description" content="website-description">
        <meta property="og:locale" content="website-locale">
        <meta property="og:determiner" content="website-determiner">
        <meta property="og:site_name" content="website-sitename">
    </head>
    <body>
        
    </body>
</html>
HTML
        );

        $openGraphService = new OpenGraph(
            new MockHttpClient($response),
            Validation::createValidatorBuilder()
                ->enableAttributeMapping()
                ->getValidator()
        );

        $openGraphData = $openGraphService->getData('http://test-open-graph-url.com');

        $this->assertInstanceOf(OpenGraphData::class, $openGraphData);

        $this->assertSame('website-title', $openGraphData->title);
        $this->assertSame('https://www.good-url.com', $openGraphData->url);
        $this->assertSame('website-type', $openGraphData->type);
        $this->assertSame('website-image', $openGraphData->image->url);
        $this->assertSame('website-video', $openGraphData->video->url);
        $this->assertSame('website-description', $openGraphData->description);
        $this->assertSame('website-locale', $openGraphData->locale);
        $this->assertSame('website-determiner', $openGraphData->determiner);
        $this->assertSame('website-sitename', $openGraphData->siteName);
    }

    public function test_it_reset_property_when_validation_fails()
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
        <meta property="og:locale" content="fr_FR">
        <meta property="og:nook" content="nook">
    </head>
    <body>
        
    </body>
</html>
HTML
        );

        $openGraphService = new OpenGraph(
            new MockHttpClient($response),
            Validation::createValidatorBuilder()
                ->enableAttributeMapping()
                ->getValidator(),
            true
        );

        $openGraphData = $openGraphService->getData('http://test-open-graph-url.com');

        $this->assertInstanceOf(OpenGraphData::class, $openGraphData);

        $this->assertSame('website-title', $openGraphData->title);
        $this->assertSame(null, $openGraphData->url);
        $this->assertSame('website-type', $openGraphData->type);
        $this->assertSame(null, $openGraphData->image->url);
        $this->assertSame('website-description', $openGraphData->description);
        $this->assertSame('fr_FR', $openGraphData->locale);
    }

    public function test_it_populate_correctly_image_structured_data()
    {
        $response = new MockResponse(
            <<<'HTML'
<!DOCTYPE html>
<html>
    <head>

        <meta property="og:image:url" content="image-url">
        <meta property="og:image:secure_url" content="image-secure-url">
        <meta property="og:image:type" content="image-type">
        <meta property="og:image:width" content="image-width">
        <meta property="og:image:height" content="image-height">
        <meta property="og:image:alt" content="image-alt">
    </head>
    <body>
        
    </body>
</html>
HTML
        );

        $openGraphService = new OpenGraph(
            new MockHttpClient($response),
            Validation::createValidatorBuilder()
                ->enableAttributeMapping()
                ->getValidator()
        );

        $openGraphData = $openGraphService->getData('http://test-open-graph-url.com');

        $this->assertInstanceOf(OpenGraphData::class, $openGraphData);

        $this->assertSame('image-url', $openGraphData->image->url);
        $this->assertSame('image-secure-url', $openGraphData->image->secureUrl);
        $this->assertSame('image-type', $openGraphData->image->type);
        $this->assertSame('image-width', $openGraphData->image->width);
        $this->assertSame('image-height', $openGraphData->image->height);
        $this->assertSame('image-alt', $openGraphData->image->alt);
    }

    public function test_it_populate_correctly_video_structured_data()
    {
        $response = new MockResponse(
            <<<'HTML'
<!DOCTYPE html>
<html>
    <head>
        <meta property="og:video:url" content="video-url">
        <meta property="og:video:secure_url" content="video-secure-url">
        <meta property="og:video:type" content="video-type">
        <meta property="og:video:width" content="video-width">
        <meta property="og:video:height" content="video-height">
        <meta property="og:video:alt" content="video-alt">
    </head>
    <body>
        
    </body>
</html>
HTML
        );

        $openGraphService = new OpenGraph(
            new MockHttpClient($response),
            Validation::createValidatorBuilder()
                ->enableAttributeMapping()
                ->getValidator()
        );

        $openGraphData = $openGraphService->getData('http://test-open-graph-url.com');

        $this->assertInstanceOf(OpenGraphData::class, $openGraphData);

        $this->assertSame('video-url', $openGraphData->video->url);
        $this->assertSame('video-secure-url', $openGraphData->video->secureUrl);
        $this->assertSame('video-type', $openGraphData->video->type);
        $this->assertSame('video-width', $openGraphData->video->width);
        $this->assertSame('video-height', $openGraphData->video->height);
        $this->assertSame('video-alt', $openGraphData->video->alt);
    }

    public function test_it_populate_correctly_audio_structured_data()
    {
        $response = new MockResponse(
            <<<'HTML'
<!DOCTYPE html>
<html>
    <head>
        <meta property="og:audio:url" content="audio-url">
        <meta property="og:audio:secure_url" content="audio-secure-url">
        <meta property="og:audio:type" content="audio-type">
    </head>
    <body>
        
    </body>
</html>
HTML
        );

        $openGraphService = new OpenGraph(
            new MockHttpClient($response),
            Validation::createValidatorBuilder()
                ->enableAttributeMapping()
                ->getValidator()
        );

        $openGraphData = $openGraphService->getData('http://test-open-graph-url.com');

        $this->assertInstanceOf(OpenGraphData::class, $openGraphData);

        $this->assertSame('audio-url', $openGraphData->audio->url);
        $this->assertSame('audio-secure-url', $openGraphData->audio->secureUrl);
        $this->assertSame('audio-type', $openGraphData->audio->type);
    }
    public function test_it_throws_a_invalid_argument_exception_when_url_is_not_valid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $openGraphService = new OpenGraph(
            new MockHttpClient(),
            Validation::createValidatorBuilder()
                ->enableAttributeMapping()
                ->getValidator()
        );

        $openGraphService->getData('not-a-url');
    }
}

