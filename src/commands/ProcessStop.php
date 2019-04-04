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
        $args = $this->getArguments();
        if (empty($args[0])) {
            echo "缺少要停止的命令表识\n";
            exit;
        }
        $cmd = trim($args[0]);
        $vendorBinDir = dirname(__file__, 6) . '/vendor/bin';
        exec("ps -eo pid,command | grep {$cmd}", $output);
        //根据output提取目标信息
        foreach ($output as $item) {
            $item = preg_replace('!\s+!', ' ', $item);
            $arr = explode(' ', $item);
            //第一段必须包含php
            if (strpos($arr[1], 'php') === false) {
                continue;
            }
            //第二段必须包含console
            if (strpos($arr[2], 'console') === false) {
                continue;
            }
            //第三段必须和命令本身相等
            if ($arr[3] != $cmd) {
                continue;
            }
            $absPath = $arr[2];
            /*现在来比较执行路径
              不以绝对路径开头的,需要计算绝对路径
            */
            if (strpos($absPath, '/') !== 0) {
                $cwd = [];
                exec("lsof -p {$arr[0]} | grep [c]wd | awk '{print $9}'", $cwd);
                $workingDir = $cwd[0];
                $absPath = "{$workingDir}/{$arr[2]}";
            }
            if (strpos($absPath, $vendorBinDir) === 0) {
                echo "kill pid {$arr[0]}\n";
            }
        }
     }
}