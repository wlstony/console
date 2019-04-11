<?php
namespace Console\Kernel;

use Console\ArgsParser\Parser;
use Console\ArgsParser\Output;
use Console\ArgsParser\Input;
use Console\Kernel\Loader;

class Runner
{
    static function start(Parser $parser, Loader $loader) {
        $classes = $loader->loadClassesFromDir();
        $commands = $parser->parseFromNameSpace($classes);
        $input = $parser->parseInput();
        $output = new Output();
        self::doStart($input, $output, $commands);
    }
    private static function doStart($input, $output, $commands) {
        //全局帮助
        if ($input->globalHelp) {
            $userCmd = [];
            $sysCmd = [];
            foreach ($commands as $cmd => $instance) {
                $class = get_class($instance);
                if (strpos($class, 'Console\Commands') === 0) {
                    $sysCmd[$cmd] = $instance->description;
                } else {
                    $userCmd[$cmd] = $instance->description;
                }
            }
            call_user_func([$output, 'outputGLobalHelper'], $userCmd, $sysCmd);
            exit;
        }
        //子命令判断
        if (empty($commands[$input->subcommand])) {
            throw new \Exception("不存在命令{$input->subcommand}");
        }
        //某个子命令帮助
        if ($input->subcommandHelp) {
            call_user_func([$output, 'outputCommandHelper'], $commands[$input->subcommand]);
            exit;
        }
        //执行子命令
        $instance = $commands[$input->subcommand];
        $instance->real_longops = $input->getLongops();
        $instance->real_shortops = $input->getShortops();
        $instance->real_keywordargs = $input->getKeyargs();
        $instance->real_positionalargs = $input->getPosargs();
        $instance->beforeExec();

        call_user_func([$instance, 'exec']);
    }
}