<?php
namespace Msingi\Doctrine\DBAL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * Geodistance function call
 *
 * @package Msingi\Doctrine\DBAL
 */
class GeoDistance extends FunctionNode
{
    protected $from_lat;
    protected $from_lon;
    protected $to_lat;
    protected $to_lon;

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'GEODISTANCE(' . implode(', ', array(
            $this->from_lat->dispatch($sqlWalker),
            $this->from_lon->dispatch($sqlWalker),
            $this->to_lat->dispatch($sqlWalker),
            $this->to_lon->dispatch($sqlWalker)
        )) . ')';
    }

    /**
     * @param \Doctrine\ORM\Query\Parser $parser
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->from_lat = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->from_lon = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->to_lat = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->to_lon = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
