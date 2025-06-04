<?php

namespace Mohachi\CommandLine\Tokenizer\Identifier;

use Mohachi\CommandLine\Exception\InvalidArgumentException;

class LiteralIdentifierTokenizer implements IdentifierTokenizerInterface
{
    use IdentifierTokenizerTrait;
    
    public function __construct(private string $id)
    {
        if( empty($id) )
        {
            throw new InvalidArgumentException("empty identifier");
        }
        
        if( $id == "-" )
        {
            throw new InvalidArgumentException("invalid literal identifier '$id'");
        }
    }
    
}
