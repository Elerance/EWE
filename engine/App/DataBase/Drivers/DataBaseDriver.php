<?

namespace App\DataBase\Drivers;

interface DataBaseDriver
{

    /**
     * Initialize driver
     *
     * @param array $credentials DB Credentials
     */
    public function __construct($credentials);

    /**
     * Get driver name
     *
     * @return string
     */
    public function Name();
    /**
     * Get driver version
     *
     * @return string
     */
    public function Version();

    /**
     * Execute query
     *
     * @param string $query
     * @return void
     */
    public function Exec($query);
    /**
     * Execute query and read result
     *
     * @param string $query
     * @return array
     */
    public function Read($query, $assoc = true);

}
