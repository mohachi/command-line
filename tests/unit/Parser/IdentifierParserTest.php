<?php

use Mohachi\CommandLine\Exception\UnderflowException;
use Mohachi\CommandLine\Exception\ParserException;
use Mohachi\CommandLine\Parser\IdentifierParser;
use Mohachi\CommandLine\Tokenizer\Identifier\LiteralIdentifierTokenizer;
use Mohachi\CommandLine\Tokenizer\Identifier\LongIdentifierTokenizer;
use Mohachi\CommandLine\TokenStack;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(IdentifierParser::class)]
class IdentifierParserTest extends TestCase
{
    
    /* METHOD: parse */
    
    #[Test]
    public function parse_empty_stack()
    {
        $cli = ["cmd"];
        $tokens = new TokenStack($cli);
        $cli = [];
        $parser = new IdentifierParser();
        $parser->append(new LiteralIdentifierTokenizer("cmd"));
        
        $this->expectException(UnderflowException::class);
        
        $parser->parse($tokens);
    }
    
    #[Test]
    public function parse_against_empty_tokenizers()
    {
        $cli = ["cmd"];
        $tokens = new TokenStack($cli);
        $parser = new IdentifierParser();
        
        $this->expectException(ParserException::class);
        
        $parser->parse($tokens);
        
        $this->assertEquals(["unexpected"], $cli);
    }
    
    #[Test]
    public function parse_invalid_id()
    {
        $cli = ["unexpected"];
        $tokens = new TokenStack($cli);
        $parser = new IdentifierParser();
        $parser->append(new LiteralIdentifierTokenizer("expected"));
        
        $this->expectException(ParserException::class);
        
        $parser->parse($tokens);
        
        $this->assertEquals(["unexpected"], $cli);
    }
    
    #[Test]
    public function parse_valid_id()
    {
        $cli = ["first", "--second"];
        $tokens = new TokenStack($cli);
        $parser = new IdentifierParser();
        $parser->append(new LongIdentifierTokenizer("second"));
        $parser->append(new LiteralIdentifierTokenizer("first"));
        
        $node1 = $parser->parse($tokens);
        $node2 = $parser->parse($tokens);
        
        $this->assertEquals("first", $node1->getValue());
        $this->assertEquals("--second", $node2->getValue());
    }
    
}
