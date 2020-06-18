<?
spl_autoload_register(function ($classname) {
    
    include APPLICATION_PATH . '/engine/' . str_replace('\\', '/', $classname) . '.php';
    print_r([$classname, 'App\Interfaces\Initiable']);
    if (is_string($classname) && $classname != "App\Interfaces\Initiable" && in_array('App\Interfaces\Initiable', class_implements($classname))) {
        $class::INIT();
    }

});
