<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use FFI\CData;

class Function_ extends Type
{
    private Type $return;
    private array $params;
    private bool $isVariadic;

    /**
     * @param Type[] $params
     */
    public function __construct(Type $return, array $params, bool $isVariadic)
    {
        parent::__construct('');
        $this->return = $return;
        $this->params = $params;
        $this->isVariadic = $isVariadic;
    }

    public function getCType($pointer = ''): string
    {
        $params = array_map(fn (Type $param) => $param->getCType(), $this->params);
        if ($this->isVariadic()) {
            $params[] = '...';
        }

        $return = $this->return->getCType('__REPLACE__');

        $function = sprintf(
            '(%s)(%s)',
            parent::getCType($pointer),
            implode(', ', $params)
        );

        return str_replace('__REPLACE__', $function, $return);
    }

    public function getPhpTypes(): string
    {
        return '';
    }

    public function getPhpDocTypes(): string
    {
        return '\\' . CData::class . '|\Closure';
    }

    public function isVariadic(): bool
    {
        return $this->isVariadic;
    }


    public function getReturn(): Type
    {
        return $this->return;
    }

    /**
     * @return Type[]
     */
    public function getParams()
    {
        return $this->params;
    }
}
