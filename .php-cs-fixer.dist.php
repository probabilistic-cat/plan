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
        'phpdoc_separation' => false,
        'phpdoc_summary' => false,
        'statement_indentation' => false,
        'yoda_style' => false,
    ])
    ->setFinder($finder)
;
