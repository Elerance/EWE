<?

namespace App;

class Collection implements \ArrayAccess
{

    private $value = [];

    # Interface ArrayAccess
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->value[] = $value;
        } else {
            $this->value[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->value[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->value[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->value[$offset]) ? $this->value[$offset] : null;
    }
    # /Interface ArrayAccess

    public function __construct($array)
    {
        if (is_array($array)) {
            $this->value = $array;
        } else if ($array instanceof $this) {
            $this->value = $array->GetValue();
        }
    }

    public function GetValue()
    {
        return $this->value;
    }

    public function Transform($callback)
    {
        foreach ($this->value as $key => &$value) {
            $value = $callback($value, $key);
        }
        unset($value);

        return $this;
    }

    public function Map($callback)
    {
        $newCollection = new Collection($this->value);
        return $newCollection->Transform($callback);
    }

    public function Surround($char = "'", $safe = false)
    {
        $this->Transform(function ($value) use ($char, $safe) {
            return $char . ($safe ? str_replace($char, '', $value) : $value) . $char;
        });
        return $this;
    }

    public function SurroundSafe($char = "'")
    {
        return $this->Surround($char, true);
    }

    public function Count()
    {
        return count($this->value);
    }

    public function KeyValueFormat()
    {
        $this->Transform(function($value, $key) {
            if (is_bool($value)) {
                $value = $value ? "true" : "false";
            }

            return "`" . $key . "`='" . $value . "'";
        });

        return $this;
    }

    public function Get($id)
    {
        return $this->offsetGet($id);
    }

    public function Implode($char)
    {
        return implode($char, $this->value);
    }

    public function __debugInfo() {
        return ['value' => $this->value];
    }

    public function __serialize()
    {
        return $this->value;
    }

    public function __unserialize(array $data)
    {
        $this->value = $data;
    }

}
