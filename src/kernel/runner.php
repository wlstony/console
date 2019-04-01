<?php
namespace Console\Kernel;

use Console\ArgsParser\Parser;

class Runner
{
    static function start(Parser $parser) {
        $parser->parseInput();
    }
}