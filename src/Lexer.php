<?php

namespace Mohachi\CommandLine;

use Mohachi\CommandLine\Exception\InvalidArgumentException;
use Mohachi\CommandLine\Exception\TokenizerException;
use Mohachi\CommandLine\IdTokenizer\IdTokenizerInterface;
use Mohachi\CommandLine\Token\ArgumentToken;
use Mohachi\CommandLine\TokenQueue;

class Lexer
{
    
    /**
     * @var IdTokenizerInterface[] $tokenizers
     */
    private array $tokenizers = [];
    
    public function append(string $name, IdTokenizerInterface $tokenizer)
    {
        if( isset($this->tokenizers[$name]) )
        {
            throw new InvalidArgumentException();
        }
        
        $this->tokenizers[$name] = $tokenizer;
    }
    
    public function __set($name, $value)
    {
        $this->append($name, $value);
    }
    
    public function __get($name): IdTokenizerInterface
    {
        return $this->tokenizers[$name];
    }
    
    public function lex(array &$args): TokenQueue
    {
        switch( true )
        {
            case empty($args):
            case ! array_is_list($args):
            case ! array_all($args, fn($v) => is_string($v)):
                throw new InvalidArgumentException();
        }
        
        reset($args);
        $arg = current($args);
        $queue = new TokenQueue;
        
        while( false !== $arg )
        {
            $tokens = [];
            
            foreach( $this->tokenizers as $tokenizer )
            {
                try
                {
                    $tokens = $tokenizer->tokenize($arg);
                    break;
                }
                catch( TokenizerException $e )
                {
                    
                }
            }
            
            if( empty($tokens) )
            {
                $tokens = [new ArgumentToken($arg)];
            }
            
            
            foreach( $tokens as $token )
            {
                $queue->enqueue($token);
            }
            
            $arg = next($args);
        }
        
        return $queue;
    }
    
}
