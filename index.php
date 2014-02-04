<?php
	/*
	* This file is a part of ReimuCMS
	* 
	* Copyright (c) 2012 ReimuHakurei
	* Copyright (c) 2012 angelXwind
	* 
	* Permission is hereby granted, free of charge, to any person obtaining a copy
	* of this software and associated documentation files (the "Software"), to deal
	* in the Software without restriction, including without limitation the rights
	* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	* copies of the Software, and to permit persons to whom the Software is
	* furnished to do so, subject to the following conditions:
	* 
	* The above copyright notice and this permission notice shall be included in
	* all copies or substantial portions of the Software.
	* 
	* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	* THE SOFTWARE.
	*/
	$time=microtime();
	$starttime=substr($time,11).substr($time,1,9);
	global $ReimuCMS;
	
	//error_reporting(0);
	
	$ReimuCMS['output_body'] = "";
	$ReimuCMS['output_title'] = "";
	$ReimuCMS['api'] = "";
	
	$ReimuCMS['automagic_header'] = true;
	
	// If MagicQuotes(TM) are enabled, reverse them...
	if (get_magic_quotes_gpc()) {
		$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
		while (list($key, $val) = each($process)) {
			foreach ($val as $k => $v) {
				unset($process[$key][$k]);
				if (is_array($v)) {
					$process[$key][stripslashes($k)] = $v;
					$process[] = &$process[$key][stripslashes($k)];
				} else {
					$process[$key][stripslashes($k)] = stripslashes($v);
				}
			}
		}
		unset($process);
	}
	
	if ($_GET["act"] == "getmenu") {
		$ReimuCMS["api"] = true;
	}
	
	
	function LoadModule($module) {
		include("modules/" . $module . ".php");
	}
	
	function AppendContent() {
		global $ReimuCMS;
		$ReimuCMS['output_body'] = $ReimuCMS['output_body'] . $ReimuCMS['output_snippet'];
	}
	
	function reimucms_add_handler($act, $mod, $func) {
		global $ReimuCMS;
		$ReimuCMS["handler_" . $act] = "mod_" . $mod . "_" . $func;
	}
	
	
	if (file_exists("data/mysql.php")) {
		include("data/mysql.php");
		$ReimuCMS["config_mysql_exists"] = true;
	} else {
		$ReimuCMS["config_mysql_exists"] = false;
	}
	
	if (file_exists("config.php")) {
		include("config.php");
	}
	
	if (!isset($ReimuCMS['locale'])) {
		$ReimuCMS['locale'] = "en_us";
	}
	
	include("locales/" . $ReimuCMS['locale'] . ".php");
	
	if (file_exists("modules.php")) {
		include("modules.php");
	}
	
	// Autoload removed for now, will be replaced later with an auto-installer function.
	/*if ($handle = opendir('modules/autoload')) { 
		while (false !== ($file = readdir($handle))) { 
			if ($file != "." && $file != "..") { 
				include("modules/autoload/$file"); 
			} 
		} 
		closedir($handle); 
	}*/
	
	$verq = mysql_query("select version() as ve");
	$r = mysql_fetch_object($verq);
	
	header('X-Powered-By: ReimuCMS 1.0a1 (PHP/' . phpversion() . ' + MySQL/' . $r->ve . ')');
	
	if (isset($_SERVER['QUERY_STRING'])) {
		$request_parts = preg_split('/\//', urldecode($_SERVER['QUERY_STRING']), -1, PREG_SPLIT_NO_EMPTY);
	}
	
	if (isset($_GET['act']) || isset($_SERVER["QUERY_STRING"])) {
		if (isset($ReimuCMS["handler_" . $_GET['act']])) {
			$ReimuCMS["handler_" . $_GET['act']]();
			
			$ReimuCMS["act"] = $_GET['act'];
		} else if (isset($ReimuCMS["handler_" . $request_parts[0]])) {
			if (isset($request_parts[1])) {
				$_GET[$request_parts[0]] = $request_parts[1];
			}
			$ReimuCMS["handler_" . $request_parts[0]]();
			
			$ReimuCMS["act"] = $request_parts[0];
		} else {
			$ReimuCMS["handler_" . $ReimuCMS['defaultact']]();
		}
	} else {
		$ReimuCMS["handler_" . $ReimuCMS['defaultact']]();
	}

	if (!$ReimuCMS['api']) {
		include("template/layout.php");
	}
	if ($ReimuCMS['api_echobody'] == true) {
		echo $ReimuCMS['output_body'];
	}
?>