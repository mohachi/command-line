<?php

use Mohachi\CommandLine\SyntaxTree\IdentifierNode;
use Mohachi\CommandLine\Exception\TokenizerException;
use Mohachi\CommandLine\Tokenizer\Identifier\IdentifierTokenizerInterface;
use Mohachi\CommandLine\Tokenizer\Identifier\IdentifierTokenizerTrait;
use Mohachi\CommandLine\TokenStack;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversTrait("IdentifierTokenizerTrait")]
class IdentifierTokenizerTraitTest extends TestCase
{
    
    /* METHOD: tokenize */
    
    #[Test]
    public function tokenize_empty_stack()
    {
        $cli = ["cmd"];
        $tokens = new TokenStack($cli);
        $tokenizer = new class implements IdentifierTokenizerInterface
        {
            use IdentifierTokenizerTrait;
            
            public function __construct()
            {
                $this->id = "cmd";
            }
            
        };
        $cli = [];
        
        $this->expectException(UnderflowException::class);
        
        $tokenizer->tokenize($tokens);
    }
    
    #[Test]
    public function tokenize_invalid_token()
    {
        $cli = ["unexpected"];
        $tokens = new TokenStack($cli);
        
        $this->expectException(TokenizerException::class);
        
        new class implements IdentifierTokenizerInterface
        {
            use IdentifierTokenizerTrait;
            
            public function __construct()
            {
                $this->id = "expected";
            }
            
        }->tokenize($tokens);
        
        $this->assertSame(["unexpected"], $cli);
    }
    
    #[Test]
    public function tokenize_valid_token()
    {
        $cli = ["expected"];
        $tokens = new TokenStack($cli);
        $tokenizer = new class implements IdentifierTokenizerInterface
        {
            use IdentifierTokenizerTrait;
            
            public function __construct()
            {
                $this->id = "expected";
            }
            
        };
        
        $node = $tokenizer->tokenize($tokens);
        
        $this->assertEquals("expected", $node->getValue());
        $this->assertInstanceOf(IdentifierNode::class, $node);
    }
    
}


