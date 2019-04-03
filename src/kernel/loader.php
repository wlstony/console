<?php
namespace Console\Kernel;


class Loader
{
    private $_fromDir = [];

    function __construct($fromDir=[]) {
        if (! $fromDir) {
            $fromDir[0] = dirname(__DIR__, 5) . '/Console';
        }
        $commandDir =  dirname(__DIR__) . '/commands';
        array_push($fromDir, $commandDir);
        $this->_fromDir = $fromDir;
    }


    function loadClassesFromDir() {
        $classes = [];
        foreach($this->_fromDir as $dir) {
            $handler = opendir($dir);
            $namespace = '';
            while (($file = readdir($handler)) !== false) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (is_dir($file)) {
                    echo "is_dir true {$file} \n";
                    $recursiveClasses = $this->loadClassesFromDir();
                    $classes = array_merge($classes, $recursiveClasses);
                }
                if (substr($file, -4) != '.php') {
                    continue;
                }
                //从每个目录下选择一个文件分析出命名空间
                $fullPath = "{$dir}/{$file}";
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