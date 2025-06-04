<?php

namespace Mohachi\CommandLine\Parser;

use Mohachi\CommandLine\Exception\InvalidArgumentException;
use Mohachi\CommandLine\SyntaxTree\OptionsNode;
use Mohachi\CommandLine\Exception\ParserException;
use Mohachi\CommandLine\TokenStack;

class OptionsParser implements ParserInterface
{
    
    /**
     * @var object[] $parsers
     */
    private array $parsers = [];
    
    public function append(OptionParser $parser, int $min = 0, int $max = -1)
    {
        if( $min < 0 )
        {
            throw new InvalidArgumentException("unexpected minimum value");
        }
        
        if( $max == 0 || (0 < $max && $max < $min) )
        {
            throw new InvalidArgumentException("unexpected maximum value");
        }
        
        $this->parsers[] = (object) [
            "min" => $min,
            "max" => $max,
            "parser" => $parser
        ];
    }
    
    public function parse(TokenStack $tokens): OptionsNode
    {
        $rest = $this->parsers;
        $node = new OptionsNode;
        
        do
        {
            $parsed = false;
            
            foreach( $rest as $i => $parser)
            {
                try
                {
                    $node->append($parser->parser->parse($tokens));
                    $parser->min--;
                    $parser->max--;
                    $parsed = true;
                }
                catch( ParserException )
                {
                    continue;
                }
                
                if( 0 == $parser->max )
                {
                    unset($rest[$i]);
                }
            }
        }
        while( $parsed );
        
        foreach( $rest as $parser )
        {
            if( 0 < $parser->min )
            {
                throw new ParserException();
            }
        }
        
        return $node;
    }
    
}
