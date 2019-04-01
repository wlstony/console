<?php
namespace Console\Commands;


class Command
{
    protected $description = 'demo';
    protected $command = 'cmd:demo';

    protected $longargs = '';
    protected $shortargs = '';
    protected $keywordargs = '';
    protected $positionalargs = '';
    

    abstract function exec();
}
