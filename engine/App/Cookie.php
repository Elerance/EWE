<?

namespace App;

class Cookie {

    public static function Get($name) {
        return $_COOKIE[$name];
    }

    public static function Set($name, $value, $data = []) {
        setcookie($name, $value, 
            $data['expires'], $data['path'], $data['domain'], $data['secure'], $data['httponly']);
    }

    public static function Remove($name) {
        unset($_COOKIE[$name]);
        setcookie($name, null, -1, '/');
    }

}