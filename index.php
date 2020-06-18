<?
// define("DIR_TPL", "tpl");
// define("DIR_ENGINE", "engine");

// $user_agent = $_SERVER["HTTP_USER_AGENT"]; //user agent (browser)

// /* uries */
// $_CORE["VAR"]["FULLHOST"] = ($_SERVER['HTTPS']==='on'?'https':'http').$_CORE["VAR"]["HOST"];
// $server_host = $_SERVER['HTTP_HOST'];
// $server_requri = explode('?', $_SERVER['REQUEST_URI'], 2);
// $server_uri = mb_strtolower($server_requri[0]);
// $server_uries = explode("/",$server_uri);
// $server_self = mb_strtolower($_SERVER['PHP_SELF']);
// /* /uries */

// $title = "Elerance - будущее";
// $description = "Elerance - мы те, кто изменят этот мир.";

// require_once "./".DIR_TPL."/index.html";

require_once 'engine/core.php';

$response = App\Pagesystem\Page::GetCurrent(new App\Pagesystem\Request());
__setresponse($response);

App\Pagesystem\Buffer::Start();
require_once $response->GetPageFile();
App\Pagesystem\Buffer::Stop();

require_once App\Pagesystem\Page::Master();
?>