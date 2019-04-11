<?php
namespace Console\ArgsParser;

class Input
{
    public $globalHelp = false;

    public $subcommandHelp = false;

    public $callSubcommand = false;

    public $subcommand = '';

    private $shortops = [];
    private $longops = [];

    private $posargs = [];
    private $keyargs = [];


    public function getShortops() {
        return $this->shortops;
    }
    public function getLongops() {
        return $this->longops;
        
    }
    public function getPosargs() {
        return $this->posargs;
    }
    public function getKeyargs() {
        return $this->keyargs;
    }

    public function addlongOps($name, $value) {
        if (! isset($this->longops[$name])) {
            $this->longops[$name] = $value;
        } else {
            //多个值使用二维数组保存
            $tmp = $this->longops[$name];
            unset($this->longops[$name]);
            $this->longops[$name][] = $tmp;
            $this->longops[$name][] = $value;
        }
    }

    public function addshortOps($name, $value) {
        if (! isset($this->shortops[$name])) {
            $this->shortops[$name] = $value;
        } else {
            //多个值使用二维数组保存
            $tmp = $this->shortops[$name];
            unset($this->shortops[$name]);
            $this->shortops[$name][] = $tmp;
            $this->shortops[$name][] = $value;
        }
    }
    //覆盖的方式
    public function addkeywordArgs($name, $value) {
        $this->keyargs[$name] = $value;
    }
    //append的方式
    public function addpositionalArgs($value) {
        $this->posargs[] = $value;
    }
}