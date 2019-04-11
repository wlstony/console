# console
job端入口封装, 包含参数解析和平滑重启

1.在composer.json中新增
"repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:wlstony/console.git"
        }
    ],
2.执行composer require xianghuanji/console

3. 在项目根目录新建Console目录, 在Console目录下新建命令
<?php
namespace Test\Console;

use Console\Commands\Command;

class Demo extends Command
{
    public $description = '命令示范';
    public $command = 'command:demo';

    public $positionalargs = [
        ['name' => 'demo1', 'description' => '第一个位置参数', 'required' => true, 'type' => 'int'],
        ['name' => 'demo2', 'description' => '第二个位置参数', 'required' => true, 'type' => 'string'],
    ];
    public $keywordargs = [
        ['name' => 'demo1', 'description' => '第一个keyword参数', 'required' => true, 'type' => 'float'],
        ['name' => 'demo2', 'description' => '第二个keyworkd参数', 'required' => true],
    ];   

    public $longops = [
        ['name' => 'demo1', 'description' => '第一个keyword参数', 'required' => true],
        ['name' => 'demo2', 'description' => '第二个keyworkd参数', 'required' => true],
    ];   
    public $shortops = [
        ['name' => 't', 'description' => '第一个短option参数t', 'required' => true],
        ['name' => 'v', 'description' => '第二个短option参数v', 'required' => true],
    ];  

    function exec() {
        echo "code 1\n";
        while (true) {
            echo "executed\n";
            sleep(1);
            if($this->endLoop()) break;
        }
        echo "code 2\n";
        $this->exitSmoothly();
    }

}

4.执行 php vendor/bin/console 可以看到帮助
