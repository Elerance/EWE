<?

namespace App\Pagesystem;

use \App\Filesystem\File;
use \App\Interfaces\Initiable;

class Page implements Initiable
{

    const TPL_PATH = APPLICATION_PATH . '/tpl';
    const MASTER = self::TPL_PATH . '/master';
    const PAGES_PATH = self::TPL_PATH . '/pages';
    const MAINPAGE = '/main';
    const _404PAGE = '/404';

    public static function INIT()
    {
        self::GetPages();
    }

    public static function GetCurrent(Request $request): Response
    {

        $uri = $request->GetUri();
        $params = $uri->GetParams();
        $path = $uri->GetRelativePath();

        $pageuri = self::PAGES_PATH . self::$pages[$path];

        if (is_file($pageuri)) {
            return (new Response($pageuri))->AddParams($params);
        }

        do {

            $params[] = trim(File::SubstrPathFromLast($path, '/'), '/');
            $path = File::SubstrPathToLast($path, '/');

            if (is_file(self::PAGES_PATH . self::$pages[$path])) {
                return (new Response(self::PAGES_PATH . self::$pages[$path]))->AddParams($params);
            }

        } while ($path);

        return new Response(self::PAGES_PATH . self::_404PAGE . '.php');

    }

    private static $pages = [];

    public static function GetPages()
    {

        $pagesfiles = File::GetFiles(self::PAGES_PATH, false, "/");

        foreach ($pagesfiles as $file) {
            $filepos = File::WithoutExtension($file);

            if ($filepos == self::MAINPAGE) {
                $filepos = "/";
            }

            self::$pages[$filepos] = $file;
        }

        return self::$pages;

    }

    public static function Master()
    {
        return self::MASTER . ".php";
    }

}
