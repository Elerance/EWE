<?

namespace App\Pagesystem;

class Request
{

    private $uri;

    public function __construct()
    {
        $this->uri = URI::GetCurrent();
    }

    public function GetUri()
    {
        return $this->uri;
    }

}
