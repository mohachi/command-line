<?php

namespace Mohachi\CommandLine\Parser;

use Mohachi\CommandLine\Exception\InvalidArgumentException;
use Mohachi\CommandLine\SyntaxTree\OptionNode;
use Mohachi\CommandLine\Tokenizer\ArgumentTokenizer;
use Mohachi\CommandLine\Tokenizer\Identifier\IdentifierTokenizerInterface;
use Mohachi\CommandLine\TokenStack;

class OptionParser implements ParserInterface
{
    
    private IdentifierParser $id;
    private ArgumentsParser $arguments;
    
    public function __construct(private string $name, IdentifierTokenizerInterface $id)
    {
        if( "" == $name )
        {
            throw new InvalidArgumentException();
        }
        
        $this->id = new IdentifierParser;
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
    
    public function parse(TokenStack $tokens): OptionNode
    {
        return new OptionNode(
            $this->name,
            $this->id->parse($tokens),
            $this->arguments->parse($tokens)
        );
    }
    
}
