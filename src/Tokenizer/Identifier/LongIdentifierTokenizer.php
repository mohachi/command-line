<?php

namespace Mohachi\CommandLine\Tokenizer\Identifier;

use Mohachi\CommandLine\Exception\InvalidArgumentException;

class LongIdentifierTokenizer implements IdentifierTokenizerInterface
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
            throw new InvalidArgumentException("invalid long identifier '$id'");
        }
        
        $this->id = "--$id";
    }
    
}
