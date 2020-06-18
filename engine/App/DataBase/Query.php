<?

namespace App\DataBase;

use \App\Collection;
use \App\Config;
use \App\DataBase\Drivers\DataBaseDriver;

class Query
{
    private $condition = [];
    private $table = "";
    private $driver = null;

    private $orderBy = "id";
    private $orderDirection = "ASC";
    private $limitLeft = 0;
    private $limitRight = 0;

    public function __construct()
    {
        $this->Driver('');
    }

    public static function Full($table, $driver = '')
    {
        return (new static())->Table($table)->Driver($driver);
    }

    public function Table($table = null)
    {
        if (is_null($table)) {
            return $this->table;
        }

        $this->table = $table;
        return $this;
    }

    public function Driver($driver)
    {
        if (!($driver instanceof DataBaseDriver)) {
            if (!$driver) {
                $driver = Config::Get('database:defaultDriver');
            }

            $driver = Config::Get('database:drivers.' . $driver);

            if (!$driver) {
                return $this;
            }

            $driverClass = $driver['driverClass'];
            $driver = new $driverClass($driver);
        }

        $this->driver = $driver;
        return $this;
    }

    public function OrderBy($by, $direction = "ASC")
    {
        $this->orderBy = $by;
        $this->orderDirection = $direction;
        return $this;
    }

    public function Limit($left, $right = 0)
    {
        $this->limitLeft = $left;

        if ($right != 0) {
            $this->limitRight = $right;
        }
        return $this;
    }

    public function Where($field, $symb, $value)
    {
        $this->condition[] = ['AND', '`' . $field . '` ' . $symb . " '" . $value . "'"];
        return $this;
    }

    public function OrWhere($field, $symb, $value)
    {
        $this->condition[] = ['OR', '`' . $field . '` ' . $symb . " '" . $value . "'"];
        return $this;
    }

    public function WhereComplex($callback)
    {
        $subQuery = new Query();
        $subQuery = $callback($subQuery);
        $this->condition[] = ['AND', $subQuery->BuildCondition()];
        return $this;
    }

    public function OrWhereComplex($callback)
    {
        $subQuery = new Query();
        $subQuery = $callback($subQuery);
        $this->condition[] = ['OR', $subQuery->BuildCondition()];
        return $this;
    }

    public function WhereRaw($condition)
    {
        $this->condition[] = ['AND', $condition];
        return $this;
    }

    public function OrWhereRaw($condition)
    {
        $this->condition[] = ['OR', $condition];
        return $this;
    }

    public function WhereIn($field, $collection)
    {
        $collection = new Collection($collection);
        $collection->SurroundSafe();

        $this->condition[] = ['AND', '`' . $field . '` IN (' . $collection->Implode(', ') . ')'];
        return $this;
    }

    public function OrWhereIn($field, $collection)
    {
        $collection = new Collection($collection);
        $collection->SurroundSafe();

        $this->condition[] = ['OR', '`' . $field . '` IN (' . $collection->Implode(', ') . ')'];
        return $this;
    }

    public function WhereNotIn($field, $collection)
    {
        $collection = new Collection($collection);
        $collection->SurroundSafe();

        $this->condition[] = ['AND', '`' . $field . '` NOT IN (' . $collection->Implode(', ') . ')'];
        return $this;
    }

    public function OrWhereNotIn($field, $collection)
    {
        $collection = new Collection($collection);
        $collection->SurroundSafe();

        $this->condition[] = ['OR', '`' . $field . '` NOT IN (' . $collection->Implode(', ') . ')'];
        return $this;
    }

    public function ClearQuery()
    {
        $this->condition = [];
        return $this;
    }

    public function BuildCondition()
    {
        $conditionIndex = 0;
        $condition = "";

        foreach ($this->condition as $value) {
            $condition .= ($conditionIndex++ > 0 ? ' ' . $value[0] . ' ' : '') . $value[1];
        }

        return $condition;
    }

    public function QueryGet($attributes = [])
    {
        if (!$this->table) {
            return false;
        }

        $collection = new Collection($attributes);
        $collection->SurroundSafe('`');

        $query = 'SELECT ' . ($collection->Count() ? $collection->Implode(', ') : '*')
        . ' FROM `' . $this->table . '`' . (count($this->condition) ? ' WHERE ' . $this->BuildCondition() : '')
        . ' ORDER BY `' . $this->orderBy . '` ' . $this->orderDirection
            . (($this->limitLeft || $this->limitRight) ? ' LIMIT ' . $this->limitLeft . ($this->limitRight ? ', ' . $this->limitRight : '') : '');

        return $query;
    }

    public function QueryFirst($attributes = [])
    {
        return $this->Limit(1)->QueryGet(...func_get_args());
    }

    public function QueryLast($attributes = [])
    {
        return $this->OrderBy('id', 'DESC')->Limit(1)->QueryGet(...func_get_args());
    }

    public function QueryRemove($safe = true)
    {
        if (!$this->table || $safe && !count($this->condition)) {
            return false;
        }

        $query = 'DELETE FROM `' . $this->table . '`' . (count($this->condition) ? ' WHERE ' . $this->BuildCondition() : '');

        return $query;
    }

    public function QueryEdit($attributes, $safe = true)
    {
        if (!$this->table || $safe && !count($this->condition)) {
            return false;
        }

        $collection = new Collection($attributes);
        $collection->KeyValueFormat();

        if (!$collection->Count()) {
            return false;
        }

        $query = 'UPDATE `' . $this->table . '` SET ' . $collection->Implode(', ') . (count($this->condition) ? ' WHERE ' . $this->BuildCondition() : '');

        return $query;
    }

    public function QueryCreate($attributes)
    {
        if (!$this->table) {
            return false;
        }

        $collection = new Collection($attributes);
        $collection->KeyValueFormat();

        $query = 'INSERT INTO `' . $this->table . '` SET ' . $collection->Implode(', ');

        return $query;
    }

    public function Assoc($assoc = null)
    {
        if (is_null($assoc)) {
            return $this->assoc;
        }

        $this->assoc = $assoc;
        return $this;
    }

    public function Get($attributes = [])
    {
        $query = $this->QueryGet(...func_get_args());
        return new Collection($this->driver->read($query));
    }

    public function First($attributes = [])
    {
        $query = $this->QueryFirst(...func_get_args());
        return new Collection($this->driver->read($query));
    }

    public function Last($attributes = [])
    {
        $query = $this->QueryLast(...func_get_args());
        return new Collection($this->driver->read($query));
    }

    public function Remove($safe = true)
    {
        $query = $this->QueryRemove(...func_get_args());
        $this->driver->exec($query);
    }

    public function Edit($attributes, $safe = true)
    {
        $query = $this->QueryEdit(...func_get_args());
        $this->driver->exec($query);
    }

    public function Create($attributes)
    {
        $query = $this->QueryCreate(...func_get_args());
        $this->driver->exec($query);
    }

    public function Raw($query)
    {
        return new Collection($this->driver->read($query));
    }

    public function RawExec($query)
    {
        $this->driver->exec($query);
    }

}
