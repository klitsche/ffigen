<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

class ConstantsPrinter
{
    public const TEMPLATE = <<<PHPTMP
    <?php
    /**
     * This file is generated! Do not edit directly.
     */
     
    declare(strict_types=1);
    
    namespace {{namespace}};
    
    {{constants}}
    
    PHPTMP;


    private ConstantsCollection $constants;

    public function __construct(ConstantsCollection $constants)
    {
        $this->constants = $constants;
    }

    public function print(string $namespace, string $template = self::TEMPLATE, string $ident = ''): string
    {
        return strtr(
            $template,
            [
                '{{namespace}}' => $namespace,
                '{{constants}}' => implode(
                    "\n",
                    array_map(
                        fn (Constant $constant) => $constant->getPhpCode($ident),
                        iterator_to_array($this->constants)
                    ),
                ),
            ]
        );
    }
}
