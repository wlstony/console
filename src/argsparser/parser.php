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
        $input = new Input();

        if (empty($this->_argv[1]) || $this->_argv[1] == '-h' || $this->_argv[1] == '--help') {
            $input->globalHelp = true;
        }
        if (! empty($this->_argv[1])) {
            $input->subcommand = $this->_argv[1];
        }
        if (! empty($this->_argv[2]) && ($this->_argv[2] == '-h' || $this->_argv[2] == '--help')) {
            $input->subcommandHelp = true;
        }
        if (count($this->_argv) >= 2) {
            //从第二个参数开始处理
            $params = array_slice($this->_argv, 2);
            $len = count($params);
            for ($i=0; $i < $len; $i++) {
                $param = $params[$i];
                //长参数可能格式:--demo=test
                if (strpos($param, '--') === 0) {
                    $namval = substr($param, 2);
                    list($name, $value) = explode('=', $namval);
                    $input->addlongOps($name, $value);

                } elseif (strpos($param, '-') === 0) {
                    //短参数
                    $name = substr($param, 1, 1);
                    $value = substr($param, 2);
                    $input->addshortOps($name, $value);
                } elseif (strpos($param, '=') !== false) {
                    //keywork args
                    list($name, $value) = explode('=', $param);
                    $input->addkeywordArgs($name, $value);
                } else {
                    //position args
                    $input->addpositionalArgs($param);
                }
            }
        }

        return $input;
    }

    function parseFromNameSpace($classes) {
        $commands = [];
        foreach ($classes as $namespace => $classnames) {
            foreach ($classnames as $classname) {
                $instance = $this->parseFromClass($classname, $namespace);
                if (isset($commands[$instance->command]) && $commands[$instance->command]) {
                    throw new \Exception("{$instance->command} 命令冲突" . get_class($instance) . ' 和 '
                        . get_class($commands[$instance->command]));
                }
                $commands[$instance->command] = $instance;
            }
        }
        return $commands;
    }

    function parseFromClass($classname, $namespace) {
        $reflection = new \ReflectionClass("{$namespace}\\{$classname}");
        $instance = $reflection->newInstance();
        
        return $instance;
    }
}
