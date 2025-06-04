<?php

namespace Mohachi\CommandLine\Tokenizer;

use Mohachi\CommandLine\SyntaxTree\LeafNodeInterface;
use Mohachi\CommandLine\TokenStack;

interface TokenizerInterface
{
    public function tokenize(TokenStack $tokens): LeafNodeInterface;
}
