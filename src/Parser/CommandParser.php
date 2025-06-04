<?php

namespace Mohachi\CommandLine\Parser;

use Mohachi\CommandLine\Exception\InvalidArgumentException;
use Mohachi\CommandLine\SyntaxTree\CommandNode;
use Mohachi\CommandLine\Tokenizer\ArgumentTokenizer;
use Mohachi\CommandLine\Tokenizer\Identifier\IdentifierTokenizerInterface;
use Mohachi\CommandLine\TokenStack;

class CommandParser implements ParserInterface
{
    
    private IdentifierParser $id;
    private OptionsParser $options;
    private ArgumentsParser $arguments;
    
    public function __construct(private string $name, IdentifierTokenizerInterface $id)
    {
        if( "" == $name )
        {
            throw new InvalidArgumentException();
        }
        
        $this->id = new IdentifierParser;
        $this->options = new OptionsParser;
        $this->arguments = new ArgumentsParser;
        $this->id->append($id);
    }
    
    public function appendIdentifier(IdentifierTokenizerInterface $id)
    {
        $this->id->append($id);
    }
    
    public function appendArgument(ArgumentTokenizer $argument)
    {
        $this->arguments->append($argument);
    }
    
    public function appendOption(OptionParser $option, int $min = 0, int $max = -1)
    {
        $this->options->append($option, $min, $max);
    }
    
    public function parse(TokenStack $tokens): CommandNode
    {
        return new CommandNode(
            $this->name,
            $this->id->parse($tokens),
            $this->options->parse($tokens),
            $this->arguments->parse($tokens)
        );
    }
    
}
