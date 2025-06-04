<?php

use Mohachi\CommandLine\Exception\InvalidArgumentException;
use Mohachi\CommandLine\Tokenizer\Identifier\LiteralIdentifierTokenizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(LiteralIdentifierTokenizer::class)]
class LiteralIdentifierTokenizerTest extends TestCase
{
    
    /* METHOD: consturct */
    
    #[Test]
    public function construct_empty_id()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new LiteralIdentifierTokenizer("");
    }
    
    #[Test]
    public function construct_hyphenated_id()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new LiteralIdentifierTokenizer("-");
    }
    
}
