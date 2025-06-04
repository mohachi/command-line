<?php

use Mohachi\CommandLine\Exception\InvalidArgumentException;
use Mohachi\CommandLine\Exception\UnderflowException;
use Mohachi\CommandLine\TokenStack;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(TokenStack::class)]
class TokenStackTest extends TestCase
{
    
    /* METHOD: __construct */
    
    #[Test]
    public function construct_empty_buffer()
    {
        $this->expectException(InvalidArgumentException::class);
        
        $cli = [];
        new TokenStack($cli);
    }
    
    #[Test]
    public function construct_non_list_buffer()
    {
        $this->expectException(InvalidArgumentException::class);
        
        $cli = ["name" => "token"];
        new TokenStack($cli);
    }
    
    #[Test]
    public function construct_non_string_buffer()
    {
        $this->expectException(InvalidArgumentException::class);
        
        $cli = [new stdClass];
        new TokenStack($cli);
    }
    
    /* METHOD: getHead */
    
    #[Test]
    public function get_head_of_empty_stack()
    {
        $cli = ["cmd"];
        $tokens = new TokenStack($cli);
        $cli = [];
        
        $this->expectException(UnderflowException::class);
        
        $tokens->getHead();
    }
    
    #[Test]
    public function get_head_of_non_empty_stack()
    {
        $cli = ["cmd"];
        $tokens = new TokenStack($cli);
        
        $this->assertEquals($cli[0], $tokens->getHead());
    }
    
    /* METHOD: shift */
    
    #[Test]
    public function shift_from_empty_stack()
    {
        $cli = ["cmd"];
        $tokens = new TokenStack($cli);
        $cli = [];
        
        $this->expectException(UnderflowException::class);
        
        $tokens->getHead();
    }
    
    #[Test]
    public function shift_from_non_empty_stack()
    {
        $cli = ["cmd"];
        $tokens = new TokenStack($cli);
        
        $this->assertEquals($cli[0], $tokens->shift());
        $this->assertEmpty($cli);
    }
    
    /* METHOD: unshift */
    
    #[Test]
    public function unshift_element_into_stack()
    {
        $cli = ["arg"];
        $tokens = new TokenStack($cli);
        
        $tokens->unshift("cmd");
        
        $this->assertEquals("cmd", $cli[0]);
    }
    
}
