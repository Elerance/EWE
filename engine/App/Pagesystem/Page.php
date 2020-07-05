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
    const CONTROLLER_PREFIX = 'Pages\\';

    public static function INIT()
    {
        self::GetPages();
    }

    private static function GetResponse($page, $params, $request, $pageuri)
    {
        $controller = self::GetControllerByPage($page);
        $controllerResult = $controller->Get($request, $page, $params);

        if (is_bool($controllerResult) && !$controllerResult) {
            $pageuri = self::PAGES_PATH . self::_404PAGE . '.php';
        } else if (is_string($controllerResult)) {
            $pageuri = self::PAGES_PATH . '/' . $controllerResult . '.php';
        } else if ($controllerResult instanceof Response) {
            return $controllerResult;
        }

        return (new Response($pageuri))->AddParams($params);
    }

    private static function GetControllerByPage($page)
    {
        $page = explode('/', trim($page, '\\/'));

        foreach ($page as &$part) {
            $part = ucfirst($part);
        }
        unset($part);
        $page = implode('/', $page);

        if (is_numeric(substr($page, 0, 1))) {
            $page = '_' . $page;
        }

        $controller = self::CONTROLLER_PREFIX . str_replace(['/', '.php'], ['\\', ''], $page . 'Controller');
        if (class_exists($controller)) {
            return new $controller();
        } else {
            return new Controller();
        }
    }

    public static function GetCurrent(Request $request): Response
    {

        $uri = $request->GetUri();
        $params = $uri->GetParams();
        $path = $uri->GetRelativePath();

        $pageuri = self::PAGES_PATH . self::$pages[$path];

        if (is_file($pageuri)) {
            return self::GetResponse(self::$pages[$path], $params, $request, $pageuri);
        }

        do {

            $params[] = trim(File::SubstrPathFromLast($path, '/'), '/');
            $path = File::SubstrPathToLast($path, '/');
            $pageuri = self::PAGES_PATH . self::$pages[$path];

            if (is_file($pageuri)) {
                return self::GetResponse(self::$pages[$path], $params, $request, $pageuri);
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
