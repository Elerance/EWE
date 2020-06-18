<?

namespace App;

use \App\Config;
use \App\Interfaces\Initiable;

class Asset implements Initiable
{

    private static $basedir = '';
    private static $tpl_path = '/tpl';
    private static $css_path = '/tpl/css';
    private static $js_path = '/tpl/js';
    private static $images_path = '/tpl/img';
    private static $lang_path = '/tpl/lang';

    public static function GetTPLPath()
    {
        return self::$tpl_path;
    }
    public static function GetCSSPath()
    {
        return self::$css_path;
    }
    public static function GetJSPath()
    {
        return self::$js_path;
    }
    public static function GetImagesPath()
    {
        return self::$images_path;
    }
    public static function GetTranslationsPath()
    {
        return self::$lang_path;
    }

    public static function INIT()
    {
        
        self::$basedir = Config::Get('app_path');
        self::$tpl_path = self::$basedir . '/tpl';
        self::$css_path = self::$tpl_path . '/css';
        self::$js_path = self::$tpl_path . '/js';
        self::$images_path = self::$tpl_path . '/img';

    }

    public static function GetCSSFilePath($file)
    {
        return self::$css_path . '/' . $file;
    }
    public static function GetJSFilePath($file)
    {
        return self::$js_path . '/' . $file;
    }
    public static function GetImageFilePath($file)
    {
        return self::$images_path . '/' . $file;
    }
    public static function GetTranslationFilePath($lang, $file)
    {
        return self::$lang_path . '/' . $lang . '/' . $file;
    }

    const CDN_GOOGLE = 0;

    public static function GetCDNFilePath($cdn, $path)
    {

        if (!is_integer($cdn)) {
            return null;
        }

        $cdn_template = "";
        switch ($cdn) {
            case self::CDN_GOOGLE:
                $cdn_template = "https://ajax.googleapis.com/ajax/libs/%s";
                break;
            default:
                return false;
                break;
        }

        return sprintf($cdn_template, $path);

    }

}
