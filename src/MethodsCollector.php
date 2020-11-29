<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Function_;

class MethodsCollector
{
    public function collect(TypesCollection $types): MethodsCollection
    {
        $methods = [];
        foreach ($types as $type) {
            if ($type instanceof Function_) {
                $methods[] = $this->createMethod($type);
            }
        }

        return new MethodsCollection(...$methods);
    }

    private function createMethod(Function_ $type): Method
    {
        $params = [];
        foreach ($type->getParams() as $name => $paramType) {
            $params[] = new MethodParameter(
                $paramType,
                $name,
                $paramType->getCType()
            );
        }
        if ($type->isVariadic()) {
            $params[] = new MethodParameter(null, 'args', '', true);
        }

        $return = new MethodReturnParameter(
            $type->getReturn(),
            $type->getReturn()->getCType()
        );

        return new Method($type->getName(), $params, $return);
    }
}
