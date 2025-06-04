<?php

use Mohachi\CommandLine\Exception\InvalidArgumentException;
use Mohachi\CommandLine\Exception\ParserException;
use Mohachi\CommandLine\Exception\TokenizerException;
use Mohachi\CommandLine\Parser\OptionParser;
use Mohachi\CommandLine\Tokenizer\ArgumentTokenizer;
use Mohachi\CommandLine\Tokenizer\Identifier\LiteralIdentifierTokenizer;
use Mohachi\CommandLine\Tokenizer\Identifier\LongIdentifierTokenizer;
use Mohachi\CommandLine\TokenStack;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(OptionParser::class)]
class OptionParserTest extends TestCase
{
    
    /* METHOD: construct */
    
    #[Test]
    public function construct_empty_name()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new OptionParser("", new LongIdentifierTokenizer("opt"));
    }
    
    /* METHOD: parse */
    
    #[Test]
    public function parse_empty_stack()
    {
        $cli = ["cmd"];
        $tokens = new TokenStack($cli);
        $parser = new OptionParser("opt", new LongIdentifierTokenizer("opt"));
        $cli = [];
        
        $this->expectException(UnderflowException::class);
        
        $parser->parse($tokens);
    }
    
    #[Test]
    public function parse_unsatisfied_id()
    {
        $cli = ["unexpected"];
        $tokens = new TokenStack($cli);
        $parser = new OptionParser("opt", new LiteralIdentifierTokenizer("expected"));
        
        $this->expectException(ParserException::class);
        
        $parser->parse($tokens);
        
        $this->assertEquals(["unexpected"], $cli);
    }
    
    #[Test]
    public function parse_unsatisfied_argument()
    {
        $cli = ["--num", "unexpected"];
        $tokens = new TokenStack($cli);
        $parser = new OptionParser("number", new LongIdentifierTokenizer("num"));
        $parser->appendArgument(new ArgumentTokenizer("arg", fn($v) => is_numeric($v)));
        
        $this->expectException(TokenizerException::class);
        
        $parser->parse($tokens);
        
        $this->assertEquals(["unexpected"], $cli);
    }
    
    #[Test]
    public function parse_valid_option()
    {
        $cli = ["--num", "12"];
        $tokens = new TokenStack($cli);
        $parser = new OptionParser("number", new LongIdentifierTokenizer("num"));
        $parser->appendArgument(new ArgumentTokenizer("arg", fn($v): bool => is_numeric($v)));
        
        $node = $parser->parse($tokens);
        
        $this->assertEquals("--num", $node->id->value);
        $this->assertArrayHasKey("arg", $node->arguments->arguments);
        $this->assertEquals("12", $node->arguments->arguments["arg"]->getValue());
    }
    
}
