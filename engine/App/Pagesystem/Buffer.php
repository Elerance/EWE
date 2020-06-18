<?

namespace App\Pagesystem;

class Buffer {
    
    public static $pageContents = "";

    public static function Start() {
        ob_start();
    }

    public static function Stop() {
        self::Save(ob_get_clean());
    }

    public static function Save($pageContents) {
        self::$pageContents = $pageContents;
    }

    public static function Get() {
        return self::$pageContents;
    }

}