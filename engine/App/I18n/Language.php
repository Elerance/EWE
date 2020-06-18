<?

namespace App\I18n;

use \App\Asset;
use \App\Config;
use \App\Cookie;
use \App\Filesystem\File;
use \App\Interfaces\Initiable;
use \App\Pagesystem\Request;

class Language implements Initiable
{

    private static $INITED = false;

    private static $translations = [];

    private static $translationsPath;

    public static $languages = [];

    public static function INIT()
    {

        self::$translationsPath = APPLICATION_PATH . Asset::GetTranslationsPath();

        $translations = File::GetFiles(self::$translationsPath, false, "/");

        if (!$translations) {
            return false;
        }

        foreach ($translations as $translation) {
            $translation_namespace = self::ComputeTranslationNamespace($translation);
            self::$translations[$translation_namespace] = include self::$translationsPath . $translation;
        }

        $masterTranslations = array_filter(self::$translations, function($value) {
            return strpos($value, ".master") !== FALSE;
        }, ARRAY_FILTER_USE_KEY);

        $currentLanguage = Cookie::get("lang");

        if (!$currentLanguage) {
            $currentLanguage = Config::get("i18n:language");
        }

        foreach ($masterTranslations as $key => $translation) {
            if ($translation["language"]["code"] == $currentLanguage) {
                $translation["language"]["current"] = true;
            }
            self::$languages[$key] = $translation["language"];
        }

        self::$INITED = true;

        return true;

    }

    public static function GetLanguagesData()
    {
        return self::$languages;
    }

    public static function GetLanguageData()
    {
        return array_filter(self::$languages, function($value) {
            return $value["language"]["active"];
        })[0];
    }

    private static function ComputeTranslationNamespace($translation)
    {

        $translation_namespace = trim($translation, '/');
        $translation_namespace = strtolower($translation_namespace);
        $translation_namespace = preg_replace('/(.*)\.php/', '$1', $translation_namespace);
        $translation_namespace = str_replace('/', '.', $translation_namespace);

        return $translation_namespace;

    }

    public static function Translate($code, $currentLanguage = false)
    {

        if (!$currentLanguage) {
            $currentLanguage = Cookie::get("lang");

            if (!$currentLanguage) {
                $currentLanguage = Config::get("i18n:language");
            }
        }

        if (strpos($code, ":") !== false) {
            $code = explode(":", $code);
            $namespace = $code[0];
            $code = $code[1];
        } else {
            $namespace = "main";
        }

        $translation = self::$translations[$currentLanguage . "." . $namespace];
        
        $languagesPriority = Config::get("i18n:priority");

        while(!$translation) {
            $currentLanguagePriority = array_keys($languagesPriority, $currentLanguage)[0];

            if (is_int($currentLanguagePriority) && $currentLanguagePriority <= 0) {
                break;
            }

            if (!isset($currentLanguagePriority)) {
                $currentLanguage = Config::get("i18n:language");
            } else {
                $currentLanguage = $languagesPriority[$currentLanguagePriority - 1];
            }

            $translation = self::$translations[$currentLanguage . "." . $namespace];
        }

        $code_value = explode('.', $code);

        foreach ($code_value as $value) {
            if (isset($translation[$value])) {
                $translation = $translation[$value];
            } else {
                return null;
            }
        }

        return $translation;

    }

    public static function GetLanguage()
    {
        return Cookie::get("lang") ?? Config::get("i18n:language");
    }

    public static function SetLanguage($code)
    {
        Cookie::set("lang", $code, ["path" => "/"]);
        return true;
    }

    public static function SetLanguageByRequest(Request $request)
    {
        $params = $request->GetUri()->GetParams();

        if (!$params["lang"]) {
            $params["lang"] = Config::get("i18n:language");
        }

        $_COOKIE["lang"] = $params["lang"];

        return self::SetLanguage($params["lang"]);
    }

}
