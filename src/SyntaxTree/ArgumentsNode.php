<?php

namespace Mohachi\CommandLine\SyntaxTree;

class ArgumentsNode implements NodeInterface
{
    
    /**
     * @var ArgumentNode[] $arguments
     */
    public array $arguments = [];
    
    public function append(ArgumentNode $argument)
    {
        $this->arguments[$argument->name] = $argument;
    }
    
}
