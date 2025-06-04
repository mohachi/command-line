<?php

namespace Mohachi\CommandLine\Parser;

use Mohachi\CommandLine\SyntaxTree\ArgumentsNode;
use Mohachi\CommandLine\Tokenizer\ArgumentTokenizer;
use Mohachi\CommandLine\TokenStack;

class ArgumentsParser implements ParserInterface
{
    
    /**
     * @var ArgumentTokenizer[] $tokenizers
     */
    private array $tokenizers = [];
    
    public function append(ArgumentTokenizer $tokenizer)
    {
        $this->tokenizers[] = $tokenizer;
    }
    
    public function parse(TokenStack $tokens): ArgumentsNode
    {
        $node = new ArgumentsNode;
        
        foreach( $this->tokenizers as $tokenizer )
        {
            $node->append($tokenizer->tokenize($tokens));
        }
        
        return $node;
    }
    
}
