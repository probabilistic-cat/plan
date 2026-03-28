<?php

declare(strict_types=1);

$finder = new PhpCsFixer\Finder()
    ->in(__DIR__)
    ->exclude([
        'bin',
        'public',
        'var',
        'vendor',
    ])
    ->notPath([
        'config/bundles.php',
        'config/preload.php',
        'config/reference.php',
        'tests/bootstrap.php',
    ])
;

return new PhpCsFixer\Config()
    ->setRules([
        '@Symfony' => true,
        'blank_line_before_statement' => false,
        'braces_position' => [
            'allow_single_line_anonymous_functions' => true,
            'allow_single_line_empty_anonymous_classes' => true,
            'functions_opening_brace' => 'same_line',
        ],
        'cast_spaces' => ['space' => 'none'],
        'concat_space' => ['spacing' => 'one'],
        'increment_style' => ['style' => 'post'],
        'list_syntax' => ['syntax' => 'short'],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_separation' => false,
        'phpdoc_summary' => false,
        'single_line_comment_spacing' => false,
        'single_line_empty_body' => true,
        'single_line_throw' => false,
        'trailing_comma_in_multiline' => ['elements' => ['arguments', 'arrays', 'parameters']],
        'yoda_style' => false,
    ])
    ->setFinder($finder)
;
