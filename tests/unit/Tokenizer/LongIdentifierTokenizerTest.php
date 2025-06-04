<?php

use Mohachi\CommandLine\Exception\InvalidArgumentException;
use Mohachi\CommandLine\Tokenizer\Identifier\LongIdentifierTokenizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(LongIdentifierTokenizer::class)]
class LongIdentifierTokenizerTest extends TestCase
{
    
    /* METHOD: construct */
    
    #[Test]
    public function construct_empty_id()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new LongIdentifierTokenizer("");
    }
    
    #[Test]
    public function construct_hyphenated_id()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new LongIdentifierTokenizer("-");
    }
    
}
