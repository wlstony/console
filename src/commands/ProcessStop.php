<?php
namespace Console\Commands;

class ProcessStop extends Command
{
    public $description = '停止进程命令';
    public $command = 'command:stop';

    public $positionalargs = [
        ['name' => 'command', 'description' => '需要停止的命令', 'required' => true],
    ];   

    function exec() {

    }

}