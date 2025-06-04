<?php

namespace Mohachi\CommandLine\Tokenizer;

use Mohachi\CommandLine\Exception\InvalidArgumentException;
use Mohachi\CommandLine\SyntaxTree\ArgumentNode;
use Mohachi\CommandLine\Exception\TokenizerException;
use Mohachi\CommandLine\Tokenizer\TokenizerInterface;
use Mohachi\CommandLine\TokenStack;

class ArgumentTokenizer implements TokenizerInterface
{
    
    /**
     * @var callable $checker
     */
    private $checker;
    
    public function __construct(private string $name, ?callable $checker = null)
    {
        if( $name == "" )
        {
            throw new InvalidArgumentException("empty string name");
        }
        
        $this->checker = $checker ?? fn($v): bool => true;
    }
    
    public function tokenize(TokenStack $tokens): ArgumentNode
    {
        if( ! call_user_func($this->checker, $tokens->getHead()) )
        {
            throw new TokenizerException();
        }
        
        return new ArgumentNode($this->name, $tokens->shift());
    }
    
}
