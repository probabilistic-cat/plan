<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('bin')
    ->exclude('public')
    ->exclude('var')
    ->exclude('vendor')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'list_syntax' => ['syntax' => 'short'],
        'phpdoc_summary' => false,
        'yoda_style' => false,
    ])
    ->setFinder($finder)
;
