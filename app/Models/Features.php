<?php

namespace App\Models;

class Features
{
    public function RomanConversion($values)
    {
        $romans = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC'	=> 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        if (is_numeric($values)) {
            $result = '';
            foreach ($romans as $key => $value) {
                $matches 	= intval($values/$value);
                $result		.= str_repeat($key, $matches);
                $values		= $values % $value;
            }
        } else {
            $result = 0;
            foreach ($romans as $key => $value) {
                while (strpos($values, $key) === 0) {
                    $result += intval($value);
                    $values = substr($values, strlen($key));
                }
            }
        }
        return $result;
    }

    public function lcm($array = array(), $count = NULL)
    {
        if (is_null($count)) {
            $count = count($array);
        }
        // Find maximum values
        $max = 0;
        for ($i=0; $i < $count; $i++) {
            $max = ($max < $array[$i]) ? $array[$i] : $max ;
        }

        // Initialize result
        $result = 1;

        // Find all factors that are present in two or more array elements
        $x = 2; // Current factor
        while ($x <= $max) {
            // To store indexes of all array elements that are divisible by x
            $indexes = array();
            for ($j=0; $j < $count ; $j++) {
                if ($array[$j] % $x == 0) {
                    array_push($indexes, $j);
                }
            }
            // If there are 2 or more array elements that are divisible by x
            if (count($indexes) >= 2) {
                // Reduce all array elements divisible by x
                for ($j=0; $j < count($indexes) ; $j++) {
                    $array[$indexes[$j]] = (int)($array[$indexes[$j]] / $x);
                }
                $result = $result * $x;
            }else{
                $x++;
            }
        }
        // Multiply all reduced array elements
        for ($i=0; $i < $count; $i++) {
            $result = $result*$array[$i];
        }
        return $result;
    }
}
