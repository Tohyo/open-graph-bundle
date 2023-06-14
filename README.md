# Open Graph Bundle for Symfony

Description
===========

This bundle provide a simple service that will take a url and then return a object containing the open graph data for this url/ 

Requirements
------------

This bundle works with `php 8.2`

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require tohyo/open-graph-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require tohyo/open-graph-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    Tohyo\OpenGraphBundle\TohyoOpenGraphBundle::class => ['all' => true],
];
```


Usage 
=====

Example
-------

You can see below a example of how to use the service:

```php
public function index(OpenGraph $openGraph): Response
{
    dd($openGraph->getData('https://symfony.com'));
}
```

And this call will return this kind of object:

```php
Tohyo\OpenGraphBundle\Dto\OpenGraphData {#12498 ▼
  +title: "Symfony, High Performance PHP Framework for Web Development"
  +url: null
  +type: "website"
  +image: Tohyo\OpenGraphBundle\Dto\OpenGraphImageData {#13207 ▶
    +url: "https://symfony.com/images/opengraph/symfony.png"
    +secureUrl: null
    +type: null
    +width: ""
    +height: ""
    +alt: ""
  }
  +video: Tohyo\OpenGraphBundle\Dto\OpenGraphVideoData {#12615 ▶
    +url: null
    +secureUrl: null
    +type: null
    +width: ""
    +height: ""
    +alt: ""
  }
  +audio: Tohyo\OpenGraphBundle\Dto\OpenGraphAudioData {#12534 ▶
    +url: null
    +secureUrl: null
    +type: null
  }
  +description: "Symfony is a set of reusable PHP components and a PHP framework to build web applications, APIs, microservices and web services."
  +locale: null
  +determiner: null
  +siteName: null
}
```

Options
------

By default the Data returned is not validated, you can activate it by a config parameters

```php
tohyo_open_graph:
    validate_graph_data: true
```

When this parameters is activated property that does not pass validation will be set to `null`