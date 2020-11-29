<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(
        'sets',
        [
            'psr12',
            'php70',
            'php71',
            'common',
            'clean-code',
            'dead-code',
        ]
    );
    $parameters->set(
        'skip',
        [
            ClassAttributesSeparationFixer::class => '~',
            OrderedClassElementsFixer::class => '~',
            PhpUnitStrictFixer::class => [
                'tests/Adapter/PHPCParser/TypesCollectorTest.php',
                'tests/Adapter/PHPCParser/DefinesCollectorTest.php',
            ],
        ]
    );
    $services = $containerConfigurator->services();
    $services->set(OrderedImportsFixer::class)
        ->call(
            'configure',
            [
                [
                    'imports_order' => [
                        'class',
                        'const',
                        'function',
                    ],
                ],
            ]
        );
};