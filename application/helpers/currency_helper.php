<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('currency_method'))
{
    function currency_method($var = '')
    {
        $currency = array(
        	'en_AU' => 'AUD $',
        	'pt_BR' => 'R$',
        	'en_CA' => 'CAD $',
        	'cs_CZ' => 'Kč',
        	'da_DK' => 'kr',
        	'nl_NL' => '€',
        	'de_DE' => '€',
        	'el_GR' => '€',
        	'hu_HU' => 'Ft',
        	'he_IL' => '₪',
        	'it_IT' => '€',
        	'ja_JP' => '¥',
        	'ko_KR' => '₩',
        	'en_NZ' => 'NZD $',
        	'pl_PL' => 'zł',
        	'ru_RU' => 'руб',
        	'de_CH' => 'CHF',
        	'en_GB' => '£',
        	'en_US' => '$'
        );
        
        return $currency[$var];
    }   
}