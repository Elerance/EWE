<?

namespace App\Pagesystem;

use \App\Config;

class URI
{

    private $uri = "";

    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    public static function GetCurrent()
    {
        return new self($_SERVER['REQUEST_URI']);
    }

    public function GetAbsoluteHostPath()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        return $protocol . "://" . $_SERVER["HTTP_HOST"] . $this->GetAbsolutePath();
    }

    public function GetAbsolutePath()
    {
        return explode('?', $this->uri)[0];
    }

    public function GetRelativePath($relativeto = false)
    {
        if (!$relativeto) {
            $relativeto = Config::Get('app_path');
        }

        $relativeto = explode('?', $relativeto)[0];
        $relativeto = trim($relativeto, '/');
        $relativeto = explode('/', $relativeto);

        $path = $this->GetAbsolutePath();
        $path = trim($path, '/');
        $path = explode('/', $path);

        $resultpath = "/";

        foreach ($path as $key => $entity) {
            if (!isset($relativeto[$key]) || !$relativeto[$key]) {
                $resultpath .= $entity . "/";
            } else if ($entity != $relativeto[$key]) {
                $resultpath .= "../";
            }
        }
        return "/" . trim($resultpath, '/');

    }

    public function GetParams()
    {
        $params = explode('?', $this->uri)[1];

        if (!$params) {
            return [];
        }

        $params = explode('&', $params);

        $result = [];

        foreach ($params as $param) {
            $param = explode('=', $param);
            if (count($param) > 1) {
                $result[$param[0]] = $param[1];
            } else {
                $result[] = $param[0];
            }

        }
        return $result;
    }

}
