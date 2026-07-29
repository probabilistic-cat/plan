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
        'src/Kernel.php',
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
        'modifier_keywords' => ['elements' => ['const', 'method']],
        'no_multiline_whitespace_around_double_arrow' => false,
        'no_unneeded_control_parentheses' => [
            'statements' => ['break', 'continue', 'echo_print', 'others', 'return', 'switch_case', 'yield', 'yield_from'],
        ],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_line_span' => ['case' => 'single', 'class' => 'single', 'const' => 'single', 'function' => 'single', 'method' => 'single', 'other' => 'single', 'property' => 'single', 'trait_import' => 'single'],
        'phpdoc_separation' => false,
        'phpdoc_summary' => false,
        'phpdoc_to_comment' => false,
        'single_line_comment_spacing' => false,
        'single_line_empty_body' => true,
        'single_line_throw' => false,
        'single_space_around_construct' => [
            'constructs_contain_a_single_space' => ['yield_from'],
            'constructs_followed_by_a_single_space' => ['abstract', 'as', 'attribute', 'break', 'case', 'catch', 'class', 'comment', 'const', 'const_import', 'continue', 'do', 'echo', 'else', 'elseif', 'enum', 'extends', 'final', 'finally', 'for', 'foreach', 'function', 'function_import', 'global', 'goto', 'if', 'implements', 'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'match', 'named_argument', 'namespace', 'new', 'open_tag_with_echo', 'php_doc', 'php_open', 'print', 'private', 'private_set', 'protected', 'protected_set', 'public', 'public_set', 'readonly', 'require', 'require_once', 'return', 'static', 'switch', 'throw', 'trait', 'try', 'type_colon', 'use', 'use_lambda', 'use_trait', 'var', 'while', 'yield', 'yield_from'],
            'constructs_preceded_by_a_single_space' => ['as', 'use_lambda'],
        ],
        'trailing_comma_in_multiline' => [
            'elements' => ['arguments', 'array_destructuring', 'arrays', 'match', 'parameters'],
        ],
        'yoda_style' => false,
    ])
    ->setFinder($finder)
;
