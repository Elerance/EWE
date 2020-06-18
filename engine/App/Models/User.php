<?

namespace App\Models;

use \App\Cookie;

class User extends Model
{

    public static function GetTable()
    {
        return "users";
    }

    public static function Logout()
    {
        Cookie::Remove("UATOKEN");
        Cookie::Remove("URTOKEN");
    }

    public function ComputeAlias()
    {
        return dechex($this->id);
    }

}
