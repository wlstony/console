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


    abstract function exec();

    final function getOptions($long=false) {
        return $long ? $this->real_longops : $this->real_shortops;
    }

    final function getArguments($keyword=false) {
        return $keyword ? $this->real_keywordargs : $this->real_positionalargs;
    }
}
