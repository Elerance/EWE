<?

namespace App\Pagesystem;

class Response {

    private $pageFile;

    public function __construct($pageFile) {
        $this->pageFile = $pageFile;
    }

    public function GetPageFile() {
        return $this->pageFile;
    }

    private $params = [];

    public function GetParams() {
        return $this->params;
    }

    public function SetParams($params) {
        $this->params = $params;
        return $this;
    }

    public function AddParams($params) {
        self::SetParams(array_merge(
            self::GetParams(),
            $params
        ));
        return $this;
    }

}