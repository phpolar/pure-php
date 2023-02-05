<p align="center">
  <a href="https://github.com/phpolar"><img src="phpolar.svg" width="240" alt="Phpolar Logo" /></a>
</p>

# PHP Templating

Support using [pure PHP templates](#pure-php-templates) with automatic XSS mitigation.

[![Coverage Status](https://coveralls.io/repos/github/phpolar/php-templating/badge.svg?branch=main)](https://coveralls.io/github/phpolar/php-templating?branch=main) [![Version](http://poser.pugx.org/phpolar/php-templating/version)](https://packagist.org/packages/phpolar/php-templating) [![PHP Version Require](http://poser.pugx.org/phpolar/php-templating/require/php)](https://packagist.org/packages/phpolar/php-templating) [![License](http://poser.pugx.org/phpolar/php-templating/license)](https://packagist.org/packages/phpolar/php-templating) [![Total Downloads](http://poser.pugx.org/phpolar/php-templating/downloads)](https://packagist.org/packages/phpolar/php-templating)

## Table of Contents

1. [Installation](#installation)
1. [Usage](#usage)
1. [Example](#example-1)
1. [API Documentation](#api-documentation)

## Installation

```bash
composer require phpolar/php-templating
```

## Usage
```php
$page = new Page();
$safeContext = new HtmlSafeContext($page);
$templateEng->render("path/to/template.php", $safeContext);
// or...
echo $templateEng->apply("path/to/template.php", $safeContext /* optional */);
```

## Example 1

### Pure PHP Templates

```php
// template.php

<!DOCTYPE html>
<?php
/**
 * @var Page $view
 */
$view = $this;
?>
<html>
    <head>
        <style>
            body {
                font-family: <?= $view->font ?>;
                padding: 0;
                margin: 0;
            }
            form th {
                text-align: right;
            }
            form  td {
                text-align: left;
            }
            .container {
                background-color: <?= $view->backgroundColor ?>;
                padding: 20px 0 90px
            }
        </style>
    </head>
    <body style="text-align:center">
        <h1><?= $view->title ?></h1>
        <div class="container">
        </div>
    </body>
</html>
```
```php
// Page.php

class Page
{
    public string $title;

    public string $backgroundColor = "#fff";

    public string $font = "Arial";

    public function __construct(string $title)
    {
        $this->title = $title;
    }
}

```

## [API Documentation](https://phpolar.github.io/php-templating-api/)

[Back to top](#php-templating)

