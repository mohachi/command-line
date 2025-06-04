<?php

use Mohachi\CommandLine\Exception\InvalidArgumentException;
use Mohachi\CommandLine\SyntaxTree\ArgumentNode;
use Mohachi\CommandLine\Tokenizer\ArgumentTokenizer;
use Mohachi\CommandLine\Exception\TokenizerException;
use Mohachi\CommandLine\TokenStack;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ArgumentTokenizer::class)]
class ArgumentTokenizerTest extends TestCase
{
    
    /* METHOD: __construct */
    
    #[Test]
    public function construct_empty_name()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new ArgumentTokenizer("");
    }
    
    /* METHOD: tokenize */
    
    #[Test]
    public function tokenize_empty_stack()
    {
        $cli = ["cmd"];
        $tokens = new TokenStack($cli);
        $tokenizer = new ArgumentTokenizer("arg");
        $cli = [];
        
        $this->expectException(UnderflowException::class);
        
        $tokenizer->tokenize($tokens);
    }
    
    #[Test]
    public function tokenize_invalid_argument()
    {
        $cli = ["unexpected"];
        $tokens = new TokenStack($cli);
        $tokenizer = new ArgumentTokenizer("arg", fn($v) => $v == "expected");
        
        $this->expectException(TokenizerException::class);
        
        $tokenizer->tokenize($tokens);
        
        $this->assertSame(["unexpected"], $cli);
    }
    
    #[Test]
    public function tokenize_valid_argument()
    {
        $cli = ["expected"];
        $tokens = new TokenStack($cli);
        $tokenizer = new ArgumentTokenizer("arg", fn($v) => $v == "expected");
        
        $node = $tokenizer->tokenize($tokens);
        
        $this->assertEquals("expected", $node->getValue());
        $this->assertInstanceOf(ArgumentNode::class, $node);
    }
    
}
