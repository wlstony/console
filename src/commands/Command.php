<?php
namespace Console\Commands;


abstract class Command
{
    protected $description = 'demo';
    protected $command = 'cmd:demo';

    protected $longops = [];
    protected $shortops = [];
    protected $keywordargs = [];
    protected $positionalargs = [];
    
    public $real_longops = [];
    public $real_shortops = [];
    public $real_keywordargs = [];
    public $real_positionalargs = [];

    public $loopCounter = 86400;

    
    final function endLoop() {
        $this->loopCounter--;
        $pid = $GLOBALS['mypid'];
        $file = "/tmp/stop_{$pid}";
        //暂不判断时间戳的内容
        if (is_file($file)) {
            $this->loopCounter = 0;
        }

        return $this->loopCounter <= 0;
    }

    final function exitSmoothly() {
        $pid = $GLOBALS['mypid'];
        $file = "/tmp/stop_{$pid}";
        //暂不判断时间戳的内容
        if (is_file($file)) {
            $this->loopCounter = 0;
        }
        echo "{$pid} exit smoothly";
        exit;
    }


    //验证参数要求与实际参数是否一致
    final function beforeExec() {
        //to do
    }
    abstract function exec();

    final function getOptions($long=false) {
        return $long ? $this->real_longops : $this->real_shortops;
    }

    final function getArguments($keyword=false) {
        return $keyword ? $this->real_keywordargs : $this->real_positionalargs;
    }
}
