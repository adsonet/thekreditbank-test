<?php 
namespace App\Traits;

trait Numbers {

    public function currencyFormat($value=0)
    {
        $value = empty($value) ? 0 : $value;
        
        return '₦' . number_format($value);
    }

    public function floatValue($str, $nullable = false)
    {
        $float = (float) floatval(preg_replace('/[^\d.]/', '', $str));

        return ($float == 0 && $nullable) ? NULL : $float;
    }
}