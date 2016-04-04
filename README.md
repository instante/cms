Instante CMS
============

[![Build Status](https://travis-ci.org/instante/cms.svg?branch=master)](https://travis-ci.org/instante/cms)
[![Downloads this Month](https://img.shields.io/packagist/dm/instante/cms.svg)](https://packagist.org/packages/instante/cms)
[![Latest stable](https://img.shields.io/packagist/v/instante/cms.svg)](https://packagist.org/packages/instante/cms)

**WARNING: this component's development is in the beginnings. It is not usable yet for using in real projects.**

Tools for creating Nette websites with client-editable content.

Installation
------------

The best way to install Instante CMS is using  [Composer](http://getcomposer.org/):

1)
```sh
$ composer require instante/cms
```

2) Register EditorExtension in Nette config:
extensions:
    icns: Instante\CMS\DI\EditorExtension

3) Register route for Editor Api in Nette router
$router[] = Instante\CMS\DI\EditorExtension::createRoute();

4) Copy files from assets directory to your project and register instante/icmsEditor to requirejs

5) Setup instante/icmsEditor in presenter
$this->jsModulesContainer->useModule('instante/icmsEditor', [
            'saveUrl'  => $this->getHttpRequest()->url->getBaseUrl() . "icms-api/save",    // url registered in Router and its save action
            'autoInit' => TRUE,                     // or you can call instante/icmsEditor init() manually
        ]);

6) Link less files to page

7) Load font awesome
 <link href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">

8) Move pen.png to your www dir and check icms.less due to img path
