<?php

namespace Generator\App;

/**
* 
*/
class Helper
{

    // no including 
	public static  function replace_between($str, $needle_start, $needle_end, $replacement) {
		$pos = strpos($str, $needle_start);
		$start = $pos === false ? 0 : $pos + strlen($needle_start);

		$pos = strpos($str, $needle_end, $start);
		$end = $pos === false ? strlen($str) : $pos;

		return substr_replace($str, $replacement, $start, $end - $start);
	}

	public static  function get_instruction_toExecute($str){
		preg_match_all('/<%do(.*?)%>/', $str, $match);
		return $match;
	}

	public static  function get_instruction_toWrite($str){
		preg_match_all('/<%write(.*?)%>/', $str, $match);
		return $match;
	}



}







?>