<?php

declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Doctrine\Functions\Sqlite;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

final class RegexReplace extends FunctionNode
{
    public ?Node $colunn;
    public ?Node $regex;
    public ?Node $substitution;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->colunn = $parser->StringPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->regex = $parser->StringPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->substitution = $parser->StringPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return \sprintf(
            'REGEXP_REPLACE(%s, %s, %s)',
            $this->colunn->dispatch($sqlWalker),
            $this->regex->dispatch($sqlWalker),
            $this->substitution->dispatch($sqlWalker)
        );
    }
}
