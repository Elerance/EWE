<?

namespace App\Filesystem;

class File
{

    public static function GetContents($path)
    {
        return file_get_contents($path);
    }
    public static function GetJSONContents($path)
    {
        return json_decode(self::GetContents($path), true);
    }

    public static function ScanDir($dir)
    {

        if (!is_dir($dir)) {
            return false;
        }

        $dir = scandir($dir);

        $dir = array_diff($dir, ['.', '..']);

        return $dir;

    }

    public static function GetFiles($dir, $ontop = true, $pathprefix = false)
    {

        if (!$pathprefix) {
            $pathprefix = $dir;
        }

        $entities = self::ScanDir($dir);

        if (!$entities) {
            return false;
        }

        $result = [];

        foreach ($entities as $entity) {
            if (is_dir($dir . "/" . $entity)) {
                if (!$ontop) {
                    $result = array_merge($result,
                        self::GetFiles($dir . "/" . $entity, $ontop, $pathprefix . $entity . "/")
                    );
                }
            } else {
                $result[] = $pathprefix . $entity;
            }
        }

        return $result;

    }

    public static function SubstrPathToLast($path, $char)
    {

        if (strrpos($path, $char) === false) {
            return false;
        }

        return substr($path, 0, strrpos($path, $char));

    }

    public static function SubstrPathFromLast($path, $char)
    {

        if (strrpos($path, $char) === false) {
            return false;
        }

        return strrchr($path, $char);

    }

    public static function WithoutExtension($file)
    {
        return self::SubstrPathToLast($file, '.');
    }

}
