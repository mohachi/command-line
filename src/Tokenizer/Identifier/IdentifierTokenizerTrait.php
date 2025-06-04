<?php

namespace Mohachi\CommandLine\Tokenizer\Identifier;

use Mohachi\CommandLine\SyntaxTree\IdentifierNode;
use Mohachi\CommandLine\Exception\TokenizerException;
use Mohachi\CommandLine\TokenStack;

trait IdentifierTokenizerTrait
{
    
    private string $id;
    
    public function tokenize(TokenStack $tokens): IdentifierNode
    {
        if( $tokens->getHead() != $this->id )
        {
            throw new TokenizerException();
        }
        
        return new IdentifierNode($tokens->shift());
    }
    
}
