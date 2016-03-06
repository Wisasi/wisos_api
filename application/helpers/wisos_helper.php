<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	if ( ! function_exists('filter'))
	{
	    function filter($param)
	    {
	        $CI =& get_instance();

	        $result = $CI->db->escape_str($param);
	        return $result;
	    }
	}