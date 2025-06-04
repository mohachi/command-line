<?php

namespace Mohachi\CommandLine\SyntaxTree;

class OptionNode implements NodeInterface
{
    
    public function __construct(
        public string $name,
        public IdentifierNode $id,
        public ArgumentsNode $arguments
    )
    {
        
    }
    
}
