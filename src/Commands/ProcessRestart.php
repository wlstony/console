<?php
namespace Console\Commands;

class ProcessRestart extends Command
{
    public $description = '重新启动命令';
    public $command = 'command:restart';

    public $positionalargs = [
        ['name' => 'command', 'description' => '需要停止的命令', 'required' => true],
    ];   

    function exec() {
        $longops = $this->getOptions();
        var_dump($longops);
    }

}