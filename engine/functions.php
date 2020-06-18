<?
function mb_ucfirst($text) {
    return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
}

function css($file) {
    return App\Asset::GetCSSFilePath($file);
}
function js($file) {
    return App\Asset::GetJSFilePath($file);
}
function img($file) {
    return App\Asset::GetImageFilePath($file);
}
function cdn($cdn, $file) {
    return App\Asset::GetCDNFilePath($cdn, $file);
}
function googlecdn($file) {
    return App\Asset::GetCDNFilePath(App\Asset::CDN_GOOGLE, $file);
}
function appPath() {
    return App\Config::Get('app_path');
}

function request() {
    return new App\Pagesystem\Request();
}

function user() {
    // return new App\Models\User();
    return false;
}

function pagecontents() {
    return App\Pagesystem\Buffer::Get();
}

$RESPONSE = null;

function __setresponse($response) {
    global $RESPONSE;
    $RESPONSE = $response;
}

function params() {
    global $RESPONSE;
    return $RESPONSE->GetParams();
}

function cookie($name, $value = false, $data = []) {
    if($value === FALSE) {
        return App\Cookie::Get($name);
    } else {
        App\Cookie::Set($name, $value, $data);
        return true;
    }
}

function redirect($page, $replace = false) {
    header('Location: ' . appPath() . $page, $replace);
}

function redirectBack($force = false) {
    redirect(params()["back"] ?? $_SERVER['HTTP_REFERER'] ?? ($force ? "/" : ""), true);
}

function preventLoggined() {
    if(user()) {
        redirect('/404');
    }
}

function preventNotLoggined() {
    if(!user()) {
        redirect('/404');
    }
}

function noCache() {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

function langs() {
    return \App\I18n\Language::GetLanguagesData();
}
function lang($full = false) {
    return $full ? \App\I18n\Language::GetLanguageData() : \App\I18n\Language::GetLanguage();
}

function __($code, $language = false) {
    return \App\I18n\Language::Translate($code, $language);
}

function query($table, $driver = '') {
    return \App\DataBase\Query::Full(...func_get_args());
}

function collect($array) {
    return new \App\Collection($array);
}