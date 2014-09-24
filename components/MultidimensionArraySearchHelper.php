<?php
namespace app\components;

class MultidimensionArraySearchHelper
{
    public static function Search($searchVal, $array)
    {
        if(is_array($array) && count($array) > 0)
        {
            $foundKey = array_search($searchVal, $array);
            if($foundKey === FALSE)
            {
                foreach ($array as $key => $value)
                {
                    if(is_array($value) && count($value) > 0)
                    {
                        $foundKey = self::Search($searchVal, $value);
                        if($foundKey != FALSE)
                            return $foundKey;
                    }
                }
            }
            else {
                return $foundKey;
            }
        }
    }
}
?>
