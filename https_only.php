<?php
/**
    *@file https_only.php
    *@author Patrik Reizinger
    *@brief
    *Handle HTTPS - only theoretical.
    */
if ($_SERVER["HTTPS"] != "on")
{
	header("Location: https://".$_SERVER["HTTP_HOST"].
		$_SERVER["REQUEST_URI"]);
	exit();
}
?>