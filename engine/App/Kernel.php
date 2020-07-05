<?
spl_autoload_register(function ($classname) {
    
    if (substr($classname, 0, 4) != 'Page') {
        if (file_exists($f = APPLICATION_PATH . '/engine/' . str_replace('\\', '/', $classname) . '.php')) {
            include $f;
            if (is_string($classname) && $classname != "App\Interfaces\Initiable" && in_array('App\Interfaces\Initiable', class_implements($classname))) {
                $classname::INIT();
            }
        }
    } else {
        if (file_exists($f = APPLICATION_PATH . '/tpl/' . str_replace('\\', '/', $classname) . '.php')) {
            include $f;
        }
    }

});
