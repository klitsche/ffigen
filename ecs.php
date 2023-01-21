<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $config): void {
    $config->sets([
        SetList::PSR_12,
        SetList::COMMON,
        SetList::CLEAN_CODE,
    ]);

    $config->skip([
        ClassAttributesSeparationFixer::class => '~',
        OrderedClassElementsFixer::class => '~',
        PhpUnitStrictFixer::class => [
            'tests/Adapter/PHPCParser/TypesCollectorTest.php',
            'tests/Adapter/PHPCParser/DefinesCollectorTest.php',
        ],
    ]);

    $config->ruleWithConfiguration(OrderedImportsFixer::class, [
        'imports_order' => [
            'class',
            'const',
            'function',
        ],
    ]);
};