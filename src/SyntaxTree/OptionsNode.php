<?php

namespace Mohachi\CommandLine\SyntaxTree;

class OptionsNode implements NodeInterface
{
    
    /**
     * @var OptionNode[] $options
     */
    public array $options = [];
    
    public function append(OptionNode $option)
    {
        $this->options[] = $option;
    }
    
}
