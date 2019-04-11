<?php
namespace Console\ArgsParser;

use Console\Commands\Command;
class Output 
{
    function outputGLobalHelper($userCommand, $systemCommand) {
        echo "\nType 'php vendor/bin/console <subcommand>' for help on a specific subcommand.\n";

        echo "\n\e[0;31;40m[user command]\e[0m\n";
        foreach ($userCommand as $cmd => $desc) {
            echo "\t{$cmd}\t\t{$desc}\n";
        }

        echo "\n\e[0;31;40m[system command]\e[0m\n";
        foreach ($systemCommand as $cmd => $desc) {
            echo "\t{$cmd}\t\t{$desc}\n";
        }
        echo "\n";
    }

    function outputCommandHelper(Command $commad) {
        echo "usage: php vendor/bin/console {$commad->command}";
        echo "\n\npositional arguments\n";
        foreach ($commad->positionalargs as $item) {
            echo "  {$item['name']} {$item['description']}\n";
        }
        echo "\nkeyword arguments\n";
        foreach ($commad->keywordargs as $item) {
            echo "  {$item['name']} {$item['description']}\n";
        }

        echo "\noptional arguments\n";
        foreach ($commad->longops as $item) {
            echo "  --{$item['name']} {$item['description']}\n";
        }
        foreach ($commad->shortops as $item) {
            echo "  -{$item['name']} {$item['description']}\n";
        }
        echo "\n\n";
    }
}