<?php

namespace Mohachi\CommandLine;

use Mohachi\CommandLine\Exception\InvalidArgumentException;
use Mohachi\CommandLine\Exception\UnderflowException;
use Stringable;

class TokenStack
{
    
    /**
     * @param string[] $buffer
     */
    public function __construct(private array &$buffer)
    {
        if( empty($buffer) )
        {
            throw new InvalidArgumentException("empty tokens");
        }
        
        if( ! array_is_list($buffer) )
        {
            throw new InvalidArgumentException("non list tokens");
        }
        
        if( ! array_all($buffer, fn($v) => is_string($v) || $v instanceof Stringable) )
        {
            throw new InvalidArgumentException("non stringable tokens list");
        }
    }
    
    public function getHead(): string
    {
        $this->validate();
        return $this->buffer[0];
    }
    
    public function shift(): string
    {
        $this->validate();
        return array_shift($this->buffer);
    }
    
    public function unshift(string $token): void
    {
        array_unshift($this->buffer, $token);
    }
    
    private function validate()
    {
        if( empty($this->buffer) )
        {
            throw new UnderflowException();
        }
    }
    
}
