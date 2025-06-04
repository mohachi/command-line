<?php

use Mohachi\CommandLine\Parser\ArgumentsParser;
use Mohachi\CommandLine\Exception\TokenizerException;
use Mohachi\CommandLine\Tokenizer\ArgumentTokenizer;
use Mohachi\CommandLine\TokenStack;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ArgumentsParser::class)]
class ArgumentsParserTest extends TestCase
{
    
    /* METHOD: parse */
    
    #[Test]
    public function parse_empty_stack()
    {
        $cli = ["cmd"];
        $tokens = new TokenStack($cli);
        $cli = [];
        $parser = new ArgumentsParser();
        $parser->append(new ArgumentTokenizer("arg"));
        
        $this->expectException(UnderflowException::class);
        
        $parser->parse($tokens);
    }
    
    #[Test]
    public function parse_unsatisfied_argument()
    {
        $cli = ["unexpected"];
        $tokens = new TokenStack($cli);
        $parser = new ArgumentsParser();
        $parser->append(new ArgumentTokenizer("arg", fn($v) => $v == "expected"));
        
        $this->expectException(TokenizerException::class);
        
        $parser->parse($tokens);
        
        $this->assertEquals(["unexpected"], $cli);
    }
    
    #[Test]
    public function parse_against_empty_tokenizers()
    {
        $cli = ["cmd"];
        $tokens = new TokenStack($cli);
        $parser = new ArgumentsParser();
        
        $node = $parser->parse($tokens);
        
        $this->assertEquals(["cmd"], $cli);
        $this->assertEmpty($node->arguments);
    }
    
    #[Test]
    public function parse_satisfied_arguments()
    {
        $cli = ["value_1", "value_2", "extra"];
        $tokens = new TokenStack($cli);
        $parser = new ArgumentsParser();
        $checker = fn($v): bool => str_starts_with($v, "value_");
        $parser->append(new ArgumentTokenizer("arg_1", $checker));
        $parser->append(new ArgumentTokenizer("arg_2", $checker));
        
        $node = $parser->parse($tokens);
        
        $this->assertEquals(["extra"], $cli);
        $this->assertCount(2, $node->arguments);
        $this->assertArrayHasKey("arg_1", $node->arguments);
        $this->assertArrayHasKey("arg_2", $node->arguments);
        $this->assertEquals("value_1", $node->arguments["arg_1"]->getValue());
        $this->assertEquals("value_2", $node->arguments["arg_2"]->getValue());
    }
    
}
