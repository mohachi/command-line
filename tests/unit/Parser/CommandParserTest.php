<?php

use Mohachi\CommandLine\Parser\CommandParser;
use Mohachi\CommandLine\Exception\ParserException;
use Mohachi\CommandLine\Exception\TokenizerException;
use Mohachi\CommandLine\Tokenizer\ArgumentTokenizer;
use Mohachi\CommandLine\Tokenizer\Identifier\LiteralIdentifierTokenizer;
use Mohachi\CommandLine\Tokenizer\Identifier\LongIdentifierTokenizer;
use Mohachi\CommandLine\TokenStack;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(CommandParser::class)]
class CommandParserTest extends TestCase
{
    
    /* METHOD: construct */
    
    #[Test]
    public function construct_empty_name()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new CommandParser("", new LiteralIdentifierTokenizer("cmd"));
    }
    
    /* METHOD: parse */
    
    #[Test]
    public function parse_empty_stack()
    {
        $cli = ["cmd"];
        $tokens = new TokenStack($cli);
        $parser = new CommandParser("cmd", new LongIdentifierTokenizer("cmd"));
        $cli = [];
        
        $this->expectException(UnderflowException::class);
        
        $parser->parse($tokens);
    }
    
    #[Test]
    public function parse_unsatisfied_id()
    {
        $cli = ["unexpected"];
        $tokens = new TokenStack($cli);
        $parser = new CommandParser("cmd", new LiteralIdentifierTokenizer("expected"));
        
        $this->expectException(ParserException::class);
        
        $parser->parse($tokens);
        
        $this->assertEquals(["unexpected"], $cli);
    }
    
    #[Test]
    public function parse_unsatisfied_argument()
    {
        $cli = ["--num", "unexpected"];
        $tokens = new TokenStack($cli);
        $parser = new CommandParser("number", new LongIdentifierTokenizer("num"));
        $parser->appendArgument(new ArgumentTokenizer("arg", fn($v) => is_numeric($v)));
        
        $this->expectException(TokenizerException::class);
        
        $parser->parse($tokens);
        
        $this->assertEquals(["unexpected"], $cli);
    }
    
}
