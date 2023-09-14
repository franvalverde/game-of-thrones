<?php

declare(strict_types=1);

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__.'/config',
        __DIR__.'/migrations',
        __DIR__.'/public',
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->append([
        __FILE__,
        __DIR__.'/rector.php',
    ]);

$config = new PhpCsFixer\Config();

$filesystem = new Filesystem();
$filesystem->mkdir(Path::normalize(__DIR__.'/var/cache/php-cs-fixer'));
$config->setCacheFile(__DIR__.'/var/cache/php-cs-fixer/.php-cs-fixer.cache');

$config->setFinder($finder);

$config->setRiskyAllowed(true);

$config->setRules([
    /* Rule sets */
    '@DoctrineAnnotation' => true,
    '@PHP74Migration' => true,
    '@PHP74Migration:risky' => true,
    '@PHPUnit84Migration:risky' => true,
    '@PSR12' => true,
    '@PSR12:risky' => true,
    '@Symfony' => true,
    '@Symfony:risky' => true,

    /* Rules */
    'ordered_imports' => [
        'sort_algorithm' => 'alpha',
        'imports_order' => ['class', 'function', 'const'],
    ],
    'nullable_type_declaration_for_default_null_value' => true,
    'phpdoc_align' => ['align' => 'left'],
    'phpdoc_separation' => false,
    'phpdoc_to_comment' => ['ignored_tags' => ['use']],
    'php_unit_method_casing' => ['case' => 'camel_case'],
    'single_line_throw' => false,
    'yoda_style' => false,
    'native_function_invocation' => false,
    'no_superfluous_phpdoc_tags' => false,
]);

return $config;
