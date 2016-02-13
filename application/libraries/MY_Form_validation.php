<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form validation for UK Postcodes
 * 
 * Check that its a valid postcode
 * @author James Mills <james@koodoocreative.co.uk>
 * @version 1.0
 * @package FriendsSavingMoney
 */

class MY_Form_validation extends CI_Form_validation
{

    function __construct()
    {
        parent::__construct();  
        log_message('debug', '*** Hello from MY_Form_validation ***');
    }

    /*
        * validate date from 3 selects
        *
        * example : $rule['year'] = 'valid_selectsdate[month,day]';
        *
        */
        function valid_selectsdate($str,$params)
        {
           $explode = explode(',',$params);
           $month = $this->CI->input->post($explode[0]);
           $day = $this->CI->input->post($explode[1]);
           if(!$day) // year and month don't need to be validated
           {
              return true;
           }
           if(checkdate($month,$day,$str))
           {
               return true;
           }
           return false;
        }
}