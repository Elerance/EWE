<?

namespace App;

use \App\Filesystem\File;
use \App\Interfaces\Initiable;

class Config implements Initiable
{

    const CONFIG_PATH = APPLICATION_PATH . '/config';

    private static function ComputeConfigNamespace($config)
    {

        $config_namespace = trim($config, '/');
        $config_namespace = strtolower($config_namespace);
        $config_namespace = preg_replace('/(.*)\.php/', '$1', $config_namespace);
        $config_namespace = str_replace('/', '.', $config_namespace);

        return $config_namespace;

    }

    private static $INITED = false;

    private static $config = [];

    public static function INIT()
    {

        $configs = File::GetFiles(self::CONFIG_PATH, false, "/");

        if (!$configs) {
            return false;
        }

        foreach ($configs as $config) {
            $config_namespace = self::ComputeConfigNamespace($config);
            self::$config[$config_namespace] = include self::CONFIG_PATH . $config;
        }
        
        self::$INITED = true;

        return true;

    }

    public static function Get($value)
    {

        $value = explode(':', $value);

        if (!count($value)) {
            return null;
        }

        if (count($value) < 2) {
            $value = [
                "main",
                $value[0],
            ];
        }

        $config = self::$config[$value[0]];

        if (!$config) {
            return null;
        }

        $config_value = explode('.', $value[1]);

        foreach ($config_value as $conf_value) {
            if (isset($config[$conf_value])) {
                $config = $config[$conf_value];
            } else {
                return null;
            }

        }

        return $config;

    }

}
