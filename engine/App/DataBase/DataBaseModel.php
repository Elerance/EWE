<?

namespace App\DataBase;

abstract class DataBaseModel
{

    public static $driver = '';

    public static function GetTable()
    {
        return self::GetClassTableName(get_called_class());
    }

    public static function GetClassTableName($class)
    {
        return mb_strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1_', preg_replace('/\s+/u', '', basename(str_replace('\\', '/', $class)))));
    }

    public static function Driver($driver)
    {
        static::$driver = $driver;
        return new static();
    }

    public static function Query()
    {
        return query(static::GetTable(), static::$driver);
    }
    
    public static function OrderBy($by, $direction = "ASC")
    {
        return static::Query()->OrderBy(...func_get_args());
    }

    public static function Limit($left, $right = 0)
    {
        return static::Query()->Limit(...func_get_args());
    }

    public static function Where($field, $symb, $value)
    {
        return static::Query()->Where(...func_get_args());
    }

    public static function OrWhere($field, $symb, $value)
    {
        return static::Query()->OrWhere(...func_get_args());
    }

    public static function WhereComplex($callback)
    {
        return static::Query()->WhereComplex(...func_get_args());
    }

    public static function OrWhereComplex($callback)
    {
        return static::Query()->OrWhereComplex(...func_get_args());
    }

    public static function WhereRaw($condition)
    {
        return static::Query()->WhereRaw(...func_get_args());
    }

    public static function OrWhereRaw($condition)
    {
        return static::Query()->OrWhereRaw(...func_get_args());
    }

    public static function WhereIn($field, $collection)
    {
        return static::Query()->WhereIn(...func_get_args());
    }

    public static function OrWhereIn($field, $collection)
    {
        return static::Query()->OrWhereIn(...func_get_args());
    }

    public static function WhereNotIn($field, $collection)
    {
        return static::Query()->WhereNotIn(...func_get_args());
    }

    public static function OrWhereNotIn($field, $collection)
    {
        return static::Query()->OrWhereNotIn(...func_get_args());
    }
    
    public static function Assoc($assoc = null)
    {
        return static::Query()->Assoc(...func_get_args());
    }

    public static function QueryGet($attributes = [])
    {
        return static::Query()->QueryGet(...func_get_args());
    }

    public static function QueryFirst($attributes)
    {
        return static::Query()->QueryFirst(...func_get_args());
    }

    public static function QueryLast($attributes)
    {
        return static::Query()->QueryLast(...func_get_args());
    }

    public static function QueryRemove($safe = true)
    {
        return static::Query()->QueryRemove(...func_get_args());
    }

    public static function QueryEdit($attributes, $safe = true)
    {
        return static::Query()->QueryEdit(...func_get_args());
    }

    public static function Create($attributes)
    {
        return static::Query()->Create(...func_get_args());
    }

    public static function Get($attributes = [])
    {
        return static::Query()->Get(...func_get_args());
    }

    public static function First($attributes = [])
    {
        return static::Query()->First(...func_get_args());
    }

    public static function Last($attributes = [])
    {
        return static::Query()->Last(...func_get_args());
    }

    public static function Remove($safe = true)
    {
        return static::Query()->Remove(...func_get_args());
    }

    public static function Edit($attributes, $safe = true)
    {
        return static::Query()->Edit(...func_get_args());
    }

    public static function Raw($query)
    {
        return static::Query()->Raw(...func_get_args());
    }

    public static function RawExec($query)
    {
        return static::Query()->RawExec(...func_get_args());
    }

}
