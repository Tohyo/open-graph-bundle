<?php

namespace Tohyo\OpenGraphBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Tohyo\OpenGraphBundle\OpenGraph;

class UnitTest extends TestCase
{
    public function testUnit()
    {

        $openGraphService = new OpenGraph(HttpClient::create());
        dd($openGraphService->getData('https://stackoverflow.com/questions/10771248/how-to-remove-a-substring-from-a-string-using-php'));
        dd($openGraphService->getData('https://symfony.com/blog/symfony-ux-2-0-and-stimulus-3-support'));
    }
}