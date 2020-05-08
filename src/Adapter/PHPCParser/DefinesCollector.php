<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser;

use Klitsche\FFIGen\Define;
use Klitsche\FFIGen\DefinesCollection;
use Klitsche\FFIGen\Types\Builtin;
use PHPCParser\PreProcessor\Token;

class DefinesCollector
{
    private ValueEvaluator $evaluator;

    public function __construct(?ValueEvaluator $evaluator = null)
    {
        $this->evaluator = $evaluator ?? new ValueEvaluator();
    }

    /**
     * @param Token[] $defineTokens
     */
    public function collect(array $defineTokens): DefinesCollection
    {
        $defines = [];
        foreach ($defineTokens as $identifier => $token) {
            if ($token === null) {
                continue;
            }
            $value = '';
            $next = $token;
            do {
                if ($next instanceof Token && $next->type === Token::IDENTIFIER) {
                    // cast basic types, eg ((int32_t)-1) => ((int)-1)
                    $mapped = Builtin::map($next->value);
                    if ($mapped !== null) {
                        $value .= $mapped;
                    } else {
                        continue 2;
                    }
                } elseif ($next instanceof Token && $next->type === Token::LITERAL) {
                    continue 2;
                } elseif ($next instanceof Token && $next->type !== Token::WHITESPACE) {
                    $value .= $next->value;
                }
                $next = $next->next;
            } while ($next !== null);

            $value = $this->evaluator->evalulate($value);
            if ($value !== null) {
                $defines[] = new Define($identifier, $value);
            }
        }

        return new DefinesCollection(...$defines);
    }
}
