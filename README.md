<p align="center">
  <a href="https://github.com/phpolar"><img src="phpolar.svg" width="240" alt="Phpolar Logo" /></a>
</p>

# PHP Templating

Support using [pure PHP templates](#pure-php-templates) with automatic XSS mitigation.

[![Coverage Status](https://coveralls.io/repos/github/phpolar/php-templating/badge.svg?branch=main)](https://coveralls.io/github/phpolar/php-templating?branch=main)[![Version](http://poser.pugx.org/phpolar/php-templating/version)](https://packagist.org/packages/phpolar/php-templating)[![PHP Version Require](http://poser.pugx.org/phpolar/php-templating/require/php)](https://packagist.org/packages/phpolar/php-templating)[![License](http://poser.pugx.org/phpolar/php-templating/license)](https://packagist.org/packages/phpolar/php-templating)[![Total Downloads](http://poser.pugx.org/phpolar/php-templating/downloads)](https://packagist.org/packages/phpolar/php-templating)

### Pure PHP Templates

#### Example 1

```php
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
