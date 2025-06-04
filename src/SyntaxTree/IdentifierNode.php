<?php

namespace Mohachi\CommandLine\SyntaxTree;

class IdentifierNode implements LeafNodeInterface
{
    
    public function __construct(public string $value)
    {
        
    }
    
    public function getValue(): string
    {
        return $this->value;
    }
    
    public function setValue(string $value)
    {
        $this->value = $value;
    }
    
}
