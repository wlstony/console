<?php
namespace Console\Commands;

class ProcessStop extends Command
{
    public $description = '停止进程命令';
    public $command = 'command:stop';
    public $loopCounter = 60;

    public $positionalargs = [
        ['name' => 'command', 'description' => '需要停止的命令', 'required' => true],
    ];   

    function exec() {
        $args = $this->getArguments();
        if (empty($args[0])) {
            echo "缺少要停止的命令表识\n";
            exit;
        }
        $killPid = [];
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
                $killPid[] = $arr[0];
            }
        }
        if (empty($killPid)) {
            echo "nothing to kill !\n";
            return;
        }
        $this->_sendKillSignal($killPid);
        $this->_checkStatus($killPid);
    }

    private function _sendKillSignal($pids) {
        foreach ($pids as $pid) {
            echo "send signal to pid {$pid}\n";
            file_put_contents("/tmp/stop_{$pid}", time());
        }
    }
    //在操作系统分派pid工程中, 小概率会产生bug
    private function _checkStatus($pids) {
        //最多循环60s,检测pid是否存在
        $killedPid = [];
        while (true) {
            echo '.';
            foreach ($pids as $key => $pid) {
                if (! empty($killedPid[$pid])) {
                    continue;
                }
                $output = [];
                exec("ps -ao pid | grep '^{$pid}$'", $output, $ret);
                if ($ret && $output == []) {
                    echo "{$pid} has stop\n";
                    $killedPid[$pid] = $pid;
                }
            }
            sleep(1);
            if($this->endLoop() || count($pids) == count($killedPid)) break;
        }
    }

}