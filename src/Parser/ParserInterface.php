<?php

namespace Mohachi\CommandLine\Parser;

use Mohachi\CommandLine\SyntaxTree\NodeInterface;
use Mohachi\CommandLine\TokenStack;

interface ParserInterface
{
    public function parse(TokenStack $tokens): NodeInterface;
}
