<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser;

class ValueEvaluator
{
    /**
     * @return string|int|array|float|null
     */
    public function evalulate(string $value)
    {
        $warning = false;
        set_error_handler(
            function () use (&$warning): void {
                $warning = true;
            },
            E_WARNING
        );

        try {
            $value = eval('return ' . trim($value) . ';');
        } catch (\ParseError $exception) {
            $value = null;
        }

        restore_error_handler();
        if ($warning === true) {
            return null;
        }

        return $value;
    }
}
