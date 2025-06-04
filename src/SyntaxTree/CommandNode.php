<?php

namespace Mohachi\CommandLine\SyntaxTree;

class CommandNode implements NodeInterface
{
    
    public function __construct(
        public string $name,
        public IdentifierNode $id,
        public OptionsNode $options,
        public ArgumentsNode $arguments
    )
    {
        
    }
    
}
