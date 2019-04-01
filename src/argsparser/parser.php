<?php
namespace Console\ArgsParser;

class Parser
{
    private $_shorts;
    private $_longs;
    private $_argv;
    function __construct($argv=null) {
        if ($argv == null) {
            $this->_argv = $_SERVER['argv'];
        }
        
    }

    function parseInput() {
        if ($this->_argv[1] == '-h' || $this->_argv[1] == '--hlep') {
            
        }
        if (isset($this->_argv[1]) && strpos($this->_argv[1], '-') == 0) {

        }
    }

    function parseFromClass() {

    }
}
