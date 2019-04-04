<?php
namespace Console\Kernel;


class Loader
{
    private $_fromDir = [];

    function __construct($fromDir=[]) {
        if (! $fromDir) {
            $fromDir[0] = dirname(__DIR__, 5) . '/app/Command';
        }
        $commandDir =  dirname(__DIR__) . '/commands';
        array_push($fromDir, $commandDir);
        $this->_fromDir = $fromDir;
    }


    function loadClassesFromDir($fromDir=[]) {
        $fromDir = $fromDir ? : $this->_fromDir;
        $classes = [];
        foreach((array)$fromDir as $dir) {
            $handler = opendir($dir);
            $namespace = '';
            while (($file = readdir($handler)) !== false) {
                //.开头的文件不予考虑
                if (strpos($file, '.') === 0) {
                    continue;
                }
                $fullPath = "{$dir}/{$file}";
                if (is_dir($fullPath)) {
                    // echo "is_dir true {$fullPath} \n";
                    $recursiveClasses = $this->loadClassesFromDir($fullPath);
                    $classes = array_merge($classes, $recursiveClasses);
                }
                if (substr($file, -4) != '.php') {
                    continue;
                }
                //从每个目录下选择一个文件分析出命名空间
                if (! $namespace) {
                    $content = file_get_contents($fullPath);
                    preg_match('/<\?php[\s]*namespace[ ]*([\w\d_\\\\]*)[ ]*;/', $content, $matches);
                    $namespace = empty($matches[1]) ? : $matches[1];
                }
                $basename = basename($fullPath);
                //去掉基类
                if ($namespace == 'Console\Commands' && $basename == 'Command.php') {
                    continue;
                }
                $classes[$namespace][] = substr($basename, 0, -4);
            }
            $namespace = '';
        }
        
        return $classes;
    }
   
}