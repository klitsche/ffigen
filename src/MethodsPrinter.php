<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

class MethodsPrinter
{
    public const TEMPLATE = <<<PHPTMP
    <?php
    /**
     * This file is generated! Do not edit directly.
     */

    declare(strict_types=1);
    
    namespace {{namespace}};

    trait Methods
    {
        abstract public static function getFFI(): \FFI;

    {{methods}}
    }

    PHPTMP;
    public const IDENT = '    ';

    private MethodsCollection $methods;

    public function __construct(MethodsCollection $methods)
    {
        $this->methods = $methods;
    }

    public function print(string $namespace, string $template = self::TEMPLATE, string $ident = self::IDENT): string
    {
        return strtr(
            $template,
            [
                '{{namespace}}' => $namespace,
                '{{methods}}' => implode(
                    "\n\n",
                    array_map(
                        fn (Method $method) => $method->getPhpCode($ident),
                        iterator_to_array($this->methods)
                    ),
                ),
            ]
        );
    }
}
