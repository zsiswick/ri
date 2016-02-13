<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('status_flag_classes'))
{
    function status_flag_classes($array)
    {
      $status_flags = unserialize(STATUS_FLAGS);

      if ($status_flags[$array['status']] == 'DRAFT'){
        return('draft');
      }

      if ($status_flags[$array['status']] == 'OPEN') {
        return('open');
      }

      if ($status_flags[$array['status']] == 'PAID') {
        return('paid');
      }

      if ($status_flags[$array['status']] == 'DUE') {
        return('due');
      }

      if ($status_flags[$array['status']] == 'PARTIAL') {
        return('partial');
      }
    }
}
