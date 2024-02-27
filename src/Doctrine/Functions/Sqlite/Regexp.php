<?php

declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Doctrine\Functions\Sqlite;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

final class Regexp extends FunctionNode
{
    public ?Node $value;
    public ?Node $regexp;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->value = $parser->StringPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->regexp = $parser->StringExpression();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return \sprintf(
            '(%s REGEXP %s)',
            $this->value->dispatch($sqlWalker),
            $this->regexp->dispatch($sqlWalker)
        );
    }
}
