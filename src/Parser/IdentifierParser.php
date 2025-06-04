<?php

namespace Mohachi\CommandLine\Parser;

use Mohachi\CommandLine\Exception\ParserException;
use Mohachi\CommandLine\SyntaxTree\IdentifierNode;
use Mohachi\CommandLine\Exception\TokenizerException;
use Mohachi\CommandLine\Tokenizer\Identifier\IdentifierTokenizerInterface;
use Mohachi\CommandLine\TokenStack;

class IdentifierParser implements ParserInterface
{
    
    /**
     * @var IdentifierTokenizerInterface[] $tokenizers
     */
    private array $tokenizers = [];
    
    public function append(IdentifierTokenizerInterface $tokenizer)
    {
        $this->tokenizers[] = $tokenizer;
    }
    
    public function parse(TokenStack $tokens): IdentifierNode
    {
        foreach( $this->tokenizers as $tokenizer )
        {
            try
            {
                return $tokenizer->tokenize($tokens);
            }
            catch( TokenizerException )
            {
                
            }
        }
        
        throw new ParserException();
    }
    
}
