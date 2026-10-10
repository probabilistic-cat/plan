<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/migrations',
        __DIR__ . '/src',
        __DIR__ . '/templates',
        __DIR__ . '/tests',
    ])
    ->withRules([
        Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector::class,
    ])
    ->withSets([
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        LevelSetList::UP_TO_PHP_85,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::INSTANCEOF,
        SetList::PRIVATIZATION,
        SetList::TYPE_DECLARATION,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ])
    ->withAttributesSets(symfony: true, doctrine: true, phpunit: true)
    ->withComposerBased(twig: true, doctrine: true, phpunit: true, symfony: true)
    ->withImportNames(importShortClasses: false)
    ->withSkip([
        __DIR__ . '/src/Kernel.php',
        __DIR__ . '/tests/bootstrap.php',
        Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector::class,
        Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector::class,
    ])
;
