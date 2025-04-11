<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->exclude('var')
    ->exclude('public')
    ->exclude('config')
    ->exclude('bin')
    ->exclude('tests')
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'phpdoc_trim' => true,
        'phpdoc_types_order' => true,
        'single_line_throw' => false,
        'yoda_style' => false,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true); 