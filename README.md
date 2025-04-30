<p align="center">
  <a href="https://github.com/phpolar"><img src="phpolar.svg" width="240" alt="Phpolar Logo" /></a>
</p>

# Pure PHP

Templating that's just PHP.  That's it.  Seriously.

Support using [pure PHP templates](#pure-php-templates) with automatic XSS mitigation.

[![Coverage Status](https://coveralls.io/repos/github/phpolar/pure-php/badge.svg?branch=main)](https://coveralls.io/github/phpolar/pure-php?branch=main) [![Version](https://poser.pugx.org/phpolar/pure-php/version)](https://packagist.org/packages/phpolar/pure-php) [![PHP Version Require](https://poser.pugx.org/phpolar/pure-php/require/php)](https://packagist.org/packages/phpolar/pure-php) [![Total Downloads](https://poser.pugx.org/phpolar/pure-php/downloads)](https://packagist.org/packages/phpolar/pure-php) [![Weekly Check](https://github.com/phpolar/pure-php/actions/workflows/weekly.yml/badge.svg)](https://github.com/phpolar/pure-php/actions/workflows/weekly.yml)

## Table of Contents

1. [Installation](#installation)
1. [Usage](#usage)
1. [Example](#example-1)
1. [API Documentation](#api-documentation)

## Installation

```bash
composer require phpolar/pure-php
```

## Usage

```php
$page = new Page();
$safeContext = new HtmlSafeContext($page);
$templateEng->render("path/to/template.php", $safeContext);
// or...
echo $templateEng->apply("path/to/template", $safeContext /* optional */);
```

### Template Basename Only

```php
// or...
echo $templateEng->apply("template", $safeContext /* optional */);
// or...
echo $templateEng->apply("template", $safeContext /* optional */);
```

> The template engine will look for files with .php, .phtml, or .html
extensions in `src/templates` directory relative to the current
working directory.

## Example 1

### Pure PHP Templates

```php
// template.php or template.phtml
<?php
(function (Page $view) {
?>
<!DOCTYPE html>
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
<?php
})($this);
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

## [API Documentation](https://phpolar.github.io/pure-php/)

[Back to top](#pure-php)
