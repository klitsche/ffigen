<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Enum;

class ConstantsCollector
{
    public function collect(DefinesCollection $defines, TypesCollection $types): ConstantsCollection
    {
        $constants = [];

        foreach ($defines as $define) {
            $constants[] = new Constant($define->getName(), $define->getValue(), 'define');
        }

        foreach ($types as $type) {
            if ($type instanceof Enum) {
                foreach ($type->getValues() as $name => $value) {
                    $constants[] = new Constant($name, $value, 'enum ' . $type->getName());
                }
            }
        }

        return new ConstantsCollection(...$constants);
    }
}
