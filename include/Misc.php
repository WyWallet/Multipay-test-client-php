<?
	function generateguid($intLength)
	{
		return substr(strtoupper(md5(uniqid(rand(),true))),0,$intLength);
	}
?>