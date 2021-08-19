<?php

declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Doctrine\Functions\Mysql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

final class RegexReplace extends FunctionNode
{
    public ?Node $colunn;
    public ?Node $regex;
    public ?Node $substitution;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->colunn = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->regex = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->substitution = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return \sprintf(
            'REGEXP_REPLACE(%s, %s, %s)',
            $this->colunn->dispatch($sqlWalker),
            $this->regex->dispatch($sqlWalker),
            $this->substitution->dispatch($sqlWalker)
        );
    }
}
