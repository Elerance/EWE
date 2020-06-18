<?

namespace App\Models;

class FileRequest {

    const CODE_CHARS = '0123456789abcdeghjkmnpqrstuwxyz';
    const CODE_LEN = 5;

    public static function GenerateCode() {

        $code = "";

        for ($i=0; $i < self::CODE_LEN; $i++) { 
            $code .= self::CODE_CHARS[rand(0, strlen(self::CODE_CHARS) - 1)];
        }
        
        return $code;

    }

}