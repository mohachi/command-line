<?php

use Mohachi\CommandLine\Exception\InvalidArgumentException;
use Mohachi\CommandLine\Exception\ParserException;
use Mohachi\CommandLine\Exception\TokenizerException;
use Mohachi\CommandLine\Exception\UnderflowException;
use Mohachi\CommandLine\Parser\OptionParser;
use Mohachi\CommandLine\Parser\OptionsParser;
use Mohachi\CommandLine\Tokenizer\ArgumentTokenizer;
use Mohachi\CommandLine\Tokenizer\Identifier\LiteralIdentifierTokenizer;
use Mohachi\CommandLine\Tokenizer\Identifier\LongIdentifierTokenizer;
use Mohachi\CommandLine\TokenStack;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(OptionsParser::class)]
class OptionsParserTest extends TestCase
{
    
    /* METHOD: append */
    
    #[Test]
    public function appent_negative_min()
    {
        $parser = new OptionsParser;
        
        $this->expectException(InvalidArgumentException::class);
        
        $parser->append(new OptionParser("opt", new LongIdentifierTokenizer("opt")), -1);
    }
    
    #[Test]
    public function appent_max_equal_to_zero()
    {
        $parser = new OptionsParser;
        
        $this->expectException(InvalidArgumentException::class);
        
        $parser->append(new OptionParser("opt", new LongIdentifierTokenizer("opt")), 0, 0);
    }
    
    #[Test]
    public function appent_positive_max_less_than_min()
    {
        $parser = new OptionsParser;
        
        $this->expectException(InvalidArgumentException::class);
        
        $parser->append(new OptionParser("opt", new LongIdentifierTokenizer("opt")), 5, 2);
    }
    
    /* METHOD: parse */
    
    #[Test]
    public function parse_empty_stack()
    {
        $cli = ["cmd"];
        $parser = new OptionsParser();
        $tokens = new TokenStack($cli);
        $parser->append(new OptionParser("opt", new LiteralIdentifierTokenizer("cmd")));
        $cli = [];
        
        $this->expectException(UnderflowException::class);
        
        $parser->parse($tokens);
    }
    
    #[Test]
    public function parse_against_empty_parsers()
    {
        $cli = ["cmd"];
        $parser = new OptionsParser();
        $tokens = new TokenStack($cli);
        
        $node = $parser->parse($tokens);
        
        $this->assertEmpty($node->options);
        $this->assertEquals(["cmd"], $cli);
    }
    
    #[Test]
    public function parse_insufficient_option()
    {
        $cli = ["extra"];
        shuffle($cli);
        $parser = new OptionsParser;
        $tokens = new TokenStack($cli);
        $opt = new OptionParser("opt", new LongIdentifierTokenizer("opt"));
        $parser->append($opt, 1);
        
        $this->expectException(ParserException::class);
        
        $parser->parse($tokens);
    }
    
    #[Test]
    public function parse_overwhelmed_option()
    {
        $cli = ["--opt", "--opt"];
        $tokens = new TokenStack($cli);
        $opt = new OptionParser("opt", new LongIdentifierTokenizer("opt"));
        $parser = new OptionsParser;
        $parser->append($opt, 0, 1);
        
        $node = $parser->parse($tokens);
        
        $this->assertEquals(["--opt"], $cli);
        $this->assertCount(1, $node->options);
        $this->assertEquals("opt", $node->options[0]->name);
    }
    
    #[Test]
    public function parse_unsatisfied_option()
    {
        $cli = ["unexpected"];
        $parser = new OptionsParser();
        $tokens = new TokenStack($cli);
        $parser->append(new OptionParser("opt", new LiteralIdentifierTokenizer("expected")));
        
        $node = $parser->parse($tokens);
        
        $this->assertEmpty($node->options);
        $this->assertEquals(["unexpected"], $cli);
    }
    
    #[Test]
    public function parse_unsatisfied_option_arguments()
    {
        $cli = ["--opt", "unexpected"];
        $opt = new OptionParser("opt", new LongIdentifierTokenizer("opt"));
        $opt->appendArgument(new ArgumentTokenizer("arg", fn($v) => $v == "expected"));
        $tokens = new TokenStack($cli);
        $parser = new OptionsParser();
        $parser->append($opt, 1);
        
        $this->expectException(TokenizerException::class);
        
        $parser->parse($tokens);
        
        $this->assertEquals(["unexpected"], $cli);
    }
    
}
