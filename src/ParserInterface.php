<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

interface ParserInterface
{
    public function getDefines(): DefinesCollection;

    public function getTypes(): TypesCollection;

    public function getCDef(): string;
}
