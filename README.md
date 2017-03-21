# Services

This library is lightweight **dependency injection**.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/819b712f-9c26-4d0d-b24a-2fecd984b0d3/big.png)](https://insight.sensiolabs.com/projects/819b712f-9c26-4d0d-b24a-2fecd984b0d3)

**Diclaimer :** This component is part of the [WOK](https://github.com/web-operational-kit/) (Web Operational Kit) framework. It however can be used as a standalone library.

## Install

It is recommanded to install that component as a dependency using [Composer](https://getcomposer.org/) :

    composer require wok/services


You're also free to get it with [git](https://git-scm.com/) or by [direct download](https://github.com/web-operational-kit/router/archive/master.zip) while this package has no dependencies.

    git clone https://github.com/web-operational-kit/services.git


## Features

While a lot of dependencies injectors have been developed (such as [pimple/pimple](https://packagist.org/packages/pimple/pimple), or [league/container](https://packagist.org/packages/league/container)), this library roadmap has some specificities :

- The Services component is a dependency injector (usual).
- Depencies instances MUST be cached throughout the Services object life (that's the dependency injection pattern).
- **Dependencies instances constructors CAN accept parameters without having any trouble with the previous points.**

Let's see this [in usage](#usage).



## Usage



## Basic usage

``` php
use \WOK\Services\Services;
$services = new Services();

// Sample with Doctrine/Cache as `cache` service.
$services->addService('cache', function() {

    return new \Doctrine\Common\Cache\FilesystemCache(
        './var/cache');

});


// Far far away ...


// Retrieve the service
$cache = $services->getService('cache');
```

## With parameters

``` php
use \WOK\Services\Services;
$services = new Services();

// Sample with Symfony/Translation as `translator` service and the locale code as parameter
$services->addService('translator', function($locale) {

    $translator = new \Symfony\Component\Translation\Translator($locale);
    $translator->addLoader('ini', new \Symfony\Component\Translation\Loader\IniFileLoader());

    $files = new DirectoryIterator($path);
    foreach($files as $resource) {

        if($resource->getExtension() != 'ini') {
            continue;
        }

        // Only accepted files as `domain.locale.(ini|yml)`
        $basename = $resource->getBasename();
        $domain = mb_substr($basename, 0, mb_strpos($basename, '.'));

        $translator->addResource($resource->getExtension(), $resource->getPathname(), $locale, $domain);

    }

    return $translator;

});


// Far far away ...


$translator = $services->getService('translator', array('fr_FR'));
```
