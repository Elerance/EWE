<?

namespace App\DataBase\Drivers;

class MySQLDriver implements DataBaseDriver
{

    private $credentials;
    private $connection;

    /**
     * Initialize driver
     *
     * @param array $credentials DB Credentials
     */
    public function __construct($credentials)
    {
        $this->credentials = $credentials;
        $this->connection = $this->Connect();
    }

    /**
     * Get driver name
     *
     * @return string
     */
    public function Name()
    {
        return "MySQL";
    }

    /**
     * Get driver version
     *
     * @return string
     */
    public function Version()
    {
        return "1.0";
    }

    /**
     * Execute query
     *
     * @param string $query
     * @return void
     */
    public function Exec($query)
    {
        if ($this->CheckConnection()) {
            mysqli_query($this->connection, $query) or die(mysqli_error($this->connection));
        } else {
            throw new \Exception("MySQL request failed");
        }
    }

    /**
     * Execute query and read result
     *
     * @param string $query
     * @return array
     */
    public function Read($query, $assoc = true)
    {
        if ($this->CheckConnection()) {
            $result = mysqli_query($this->connection, $query) or die(mysqli_error($this->connection));
            return $this->FetchArray($result, $assoc);
        } else {
            throw new \Exception("MySQL request failed");
        }
    }

    /**
     * Checks mysqli connection and opens it if closed
     *
     * @return void
     */
    private function CheckConnection()
    {
        if (!mysqli_ping($this->connection)) {
            $this->connection = $this->Connect();

            return mysqli_ping($this->connection);
        }
        return true;
    }

    /**
     * Connect to MySQL DataBase
     *
     * @return mysqli_connection
     */
    private function Connect()
    {
        $conn = mysqli_connect($this->credentials['host'], $this->credentials['user'], $this->credentials['password']);
        mysqli_query($conn, "SET NAMES utf8");

        if ($this->credentials['database']) {
            $select = mysqli_select_db($conn, $this->credentials['database']);
        }

        return $conn;
    }

    /**
     * Fetch database result array
     *
     * @param mysqli_result $result
     * @return array
     */
    private function FetchArray($result, $assoc = true)
    {
        $res = [];
        $count = 0;

        if ($assoc) {
            while ($row = mysqli_fetch_assoc($result)) {
                $res[$count++] = $row;
            }
        } else {
            while ($row = mysqli_fetch_array($result)) {
                $res[$count++] = $row;
            }
        }

        return $res;
    }

}
