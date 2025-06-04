<?php

namespace Mohachi\CommandLine\SyntaxTree;

class ArgumentNode implements LeafNodeInterface
{
    
    public function __construct(public string $name, public string $value)
    {
        
    }
    
    public function getName(): string
    {
        return $this->name;
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
