<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Adapter\PHPCParser\ValueEvaluator;
use Klitsche\FFIGen\Types\Enum;
use Klitsche\FFIGen\Types\Type;
use PHPCParser\Node\Decl\NamedDecl\TypeDecl\TagDecl\EnumDecl;
use PHPCParser\Node\Stmt\ValueStmt\Expr;
use PHPCParser\Node\Stmt\ValueStmt\Expr\DeclRefExpr;
use PHPCParser\NodeAbstract;

class EnumDeclTranslator extends AbstractTranslator
{
    private const EXPR_UNARY_OPERATORS = [
        Expr\UnaryOperator::KIND_PLUS => '+',
        Expr\UnaryOperator::KIND_MINUS => '-',
        Expr\UnaryOperator::KIND_BITWISE_NOT => '~',
        Expr\UnaryOperator::KIND_LOGICAL_NOT => '!',
    ];
    private const EXPR_BINARY_OPERATORS = [
        Expr\BinaryOperator::KIND_ADD => '+',
        Expr\BinaryOperator::KIND_SUB => '-',
        Expr\BinaryOperator::KIND_MUL => '*',
        Expr\BinaryOperator::KIND_DIV => '/',
        Expr\BinaryOperator::KIND_REM => '%',
        Expr\BinaryOperator::KIND_SHL => '<<',
        Expr\BinaryOperator::KIND_SHR => '>>',
        Expr\BinaryOperator::KIND_LT => '<',
        Expr\BinaryOperator::KIND_GT => '>',
        Expr\BinaryOperator::KIND_LE => '<=',
        Expr\BinaryOperator::KIND_GE => '=>',
        Expr\BinaryOperator::KIND_EQ => '===',
        Expr\BinaryOperator::KIND_NE => '!==',
        Expr\BinaryOperator::KIND_BITWISE_AND => '&',
        Expr\BinaryOperator::KIND_BITWISE_OR => '|',
        Expr\BinaryOperator::KIND_BITWISE_XOR => '^',
        Expr\BinaryOperator::KIND_LOGICAL_AND => '&&',
        Expr\BinaryOperator::KIND_LOGICAL_OR => '||',
        Expr\BinaryOperator::KIND_COMMA => ',',
    ];

    private ValueEvaluator $valueEvaluator;

    public function __construct(?ValueEvaluator $valueEvaluator = null)
    {
        $this->valueEvaluator = $valueEvaluator ?? new ValueEvaluator();
    }

    public function matches(NodeAbstract $node): bool
    {
        return $node instanceof EnumDecl;
    }

    public function compile(NodeAbstract $node): Type
    {
        $enum = new Enum($node->name ?: '');

        $autoIncrement = 0;
        $lastValue = 0;
        foreach ((array) $node->fields as $field) {
            if ($field->value instanceof DeclRefExpr) {
                $values = $enum->getValues();
                $lastValue = $values[$field->value->name];
                $autoIncrement = 0;
            } elseif ($field->value !== null) {
                $lastValue = $this->compileExpression($field->value);
                $autoIncrement = 0;
            }
            $value = sprintf('(%s) + %s', $lastValue, $autoIncrement);
            $enum->add(
                $field->name,
                $this->valueEvaluator->evalulate($value)
            );
            $autoIncrement++;
        }

        return $enum;
    }

    private function compileExpression(Expr $expression): string
    {
        if ($expression instanceof Expr\IntegerLiteral) {
            $value = str_replace(['u', 'U', 'l', 'L'], '', $expression->value);
            return (string) intval($value);
        } elseif ($expression instanceof Expr\AbstractConditionalOperator\ConditionalOperator) {
            return '('
                . $this->compileExpression($expression->cond)
                . ' ? ' . $this->compileExpression($expression->ifTrue)
                . ' : ' . $this->compileExpression($expression->ifFalse)
                . ')';
        } elseif ($expression instanceof Expr\UnaryOperator && array_key_exists($expression->kind, self::EXPR_UNARY_OPERATORS)) {
            return '('
                . self::EXPR_UNARY_OPERATORS[$expression->kind]
                . $this->compileExpression($expression->expr)
                . ')';
        } elseif ($expression instanceof Expr\BinaryOperator && array_key_exists($expression->kind, self::EXPR_BINARY_OPERATORS)) {
            return '('
                . $this->compileExpression($expression->left)
                . ' ' . self::EXPR_BINARY_OPERATORS[$expression->kind] . ' '
                . $this->compileExpression($expression->right)
                . ')';
        }

        var_dump($expression);
        throw new \LogicException(sprintf('Unsupported expression type: %s', $expression->getType()));
    }
}
