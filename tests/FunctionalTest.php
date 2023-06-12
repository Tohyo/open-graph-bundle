<?php

namespace Tohyo\OpenGraphBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Tohyo\OpenGraphBundle\OpenGraph;
use Tohyo\OpenGraphBundle\TohyoOpenGraphBundle;

class FunctionalTest extends TestCase
{
    public function testServiceWiring()
    {
        $kernel = new TohyoOpenGraphTestingKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();
        
        $openGraph = $container->get('tohyo_open_graph.open_graph');

        $this->assertInstanceOf(OpenGraph::class, $openGraph);
    }
}

class TohyoOpenGraphTestingKernel extends Kernel
{
    public function registerBundles(): array
    {
        return [
            new TohyoOpenGraphBundle(),
            new FrameworkBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
    }
}