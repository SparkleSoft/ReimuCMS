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
	
	global $ReimuCMS;
	
	date_default_timezone_set('America/Los_Angeles');
	
	function chmodr($path, $filemode) {
		if (!is_dir($path))
			return chmod($path, $filemode);

		$dh = opendir($path);
		while (($file = readdir($dh)) !== false) {
			if($file != '.' && $file != '..') {
				$fullpath = $path.'/'.$file;
				if(is_link($fullpath))
					return FALSE;
				elseif(!is_dir($fullpath) && !chmod($fullpath, $filemode))
						return FALSE;
				elseif(!chmodr($fullpath, $filemode)) 
					return FALSE;
			}
		}

		closedir($dh);

		if(chmod($path, $filemode)) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	$ReimuCMS["alert"] = "";
	
	if (!$ReimuCMS["config_mysql_exists"]) {
		if ($_SERVER["QUERY_STRING"] != "install") {
			header("Location: ./?install");
		} else {
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				echo '<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Installer -&gt; ReimuCMS</title>
	<style type="text/css">
	body,table,tr,td {
		font-family: sans-serif;
		font-size: 12px;
	}
	</style>
</head>
<body><span style="font-size: 24px;">ReimuCMS: Installing</span><pre>Changing file permissions... ';

				chmodr(".",0777);

				echo "\n" . 'Connecting to MySQL server... ';
				flush();
				$link = @mysql_connect($_POST["server"], $_POST["username"], $_POST["password"]);
				
				if (!$link) {
					die('<span style="color: red;">ERROR!</span>' . "\n" . 'Could not connect to MySQL server: ' . mysql_error() . "\n\nINSTALL FAILED</pre></body></html>");
				} else {
					mysql_select_db($_POST["database"]);
				}
				flush();
				echo '<span style="color: green;">DONE</span>' . "\n";
				flush();
				
				echo 'Inserting tables... ';
				/* mysql_query("CREATE TABLE IF NOT EXISTS `reimucms_forum_forums` ( `id` int(255) NOT NULL AUTO_INCREMENT, `title` text CHARACTER SET latin1 NOT NULL, `desc` mediumtext CHARACTER SET latin1 NOT NULL, `posts` int(255) NOT NULL, `topics` int(255) NOT NULL, `lastpost` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, `powerneeded` int(255) NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
				mysql_query("CREATE TABLE IF NOT EXISTS `reimucms_forum_posts` ( `id` int(255) NOT NULL AUTO_INCREMENT, `title` int(255) NOT NULL, `poster` int(255) NOT NULL, `posted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `editor` int(255) NOT NULL, `edited` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
				mysql_query("CREATE TABLE IF NOT EXISTS `reimucms_forum_topics` ( `id` int(255) NOT NULL AUTO_INCREMENT, `forum` int(11) NOT NULL, `title` text CHARACTER SET latin1 NOT NULL, `desc` mediumtext CHARACTER SET latin1 NOT NULL, `creator` int(255) NOT NULL COMMENT 'The creator''s UID', `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `lastposter` int(255) NOT NULL, `lastposted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, `posts` int(255) NOT NULL, `views` int(255) NOT NULL, `locked` tinyint(1) NOT NULL, `sticky` tinyint(1) NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin"); */
				mysql_query("CREATE TABLE IF NOT EXISTS `reimucms_menu` ( `id` int(11) NOT NULL AUTO_INCREMENT, `pos` int(11) NOT NULL, `is_url` tinyint(1) NOT NULL, `linkname` text CHARACTER SET latin1 NOT NULL, `linktarget` text CHARACTER SET latin1 NOT NULL, KEY `id` (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
				mysql_query("CREATE TABLE IF NOT EXISTS `reimucms_pages` ( `id` int(255) NOT NULL AUTO_INCREMENT, `shortname` text CHARACTER SET latin1 NOT NULL, `longname` mediumtext CHARACTER SET latin1 NOT NULL, `contents` longtext CHARACTER SET latin1 NOT NULL, `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `creator` int(11) NOT NULL, `edited` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, `editor` int(11) NOT NULL, `bbcode_mode` int(11) NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
				mysql_query("CREATE TABLE IF NOT EXISTS `reimucms_portal` ( `id` int(11) NOT NULL AUTO_INCREMENT, `title` text CHARACTER SET latin1 NOT NULL, `contents` longtext CHARACTER SET latin1 NOT NULL, `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, `creator` int(11) NOT NULL, `edited` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `editor` int(11) NOT NULL, `bbcode_mode` int(11) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `id` (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
				mysql_query("CREATE TABLE IF NOT EXISTS `reimucms_portal_comments` ( `id` int(11) NOT NULL AUTO_INCREMENT, `pid` int(11) NOT NULL, `uid` text COLLATE utf8_bin NOT NULL, `isguest` tinyint(1) NOT NULL DEFAULT '0', `contents` longtext COLLATE utf8_bin NOT NULL, `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, `edited` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `editor` int(11) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM	DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
				mysql_query("CREATE TABLE IF NOT EXISTS `reimucms_security_log` ( `id` int(255) NOT NULL AUTO_INCREMENT, `type` text CHARACTER SET latin1 NOT NULL, `ip` text CHARACTER SET latin1 NOT NULL, `username` text CHARACTER SET latin1 NOT NULL, `extra` text CHARACTER SET latin1 NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
				mysql_query("CREATE TABLE IF NOT EXISTS `reimucms_users` ( `id` int(255) NOT NULL AUTO_INCREMENT, `username` text CHARACTER SET latin1 NOT NULL, `displayname` text CHARACTER SET latin1 NOT NULL, `password` text CHARACTER SET latin1 NOT NULL, `email` text CHARACTER SET latin1 NOT NULL, `joined` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `active` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, `postcount` int(255) NOT NULL DEFAULT '0', `powerlevel` int(255) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
				echo '<span style="color: green;">DONE</span>' . "\n" . 'Generating config.php... ';
				
				$configfile = '<?php
	// This configuration file has been automaticly generated by ReimuCMS
	
	$ReimuCMS["locale"] = "' . $_POST["locale"] . '";
	$ReimuCMS["sitename"] = "' . $_POST["sitename"] . '";
	$ReimuCMS["key"] = "' . $_POST["key"] . '";
	$ReimuCMS["defaultact"] = "' . $_POST["module"] . '";
?>';

				$file_out_config = "config.php";
				$fh_config = fopen($file_out_config, 'w');
				fwrite($fh_config, $configfile);
				fclose($fh_config);
				
				echo '<span style="color: green;">DONE</span>' . "\n" . 'Generating data/mysql.php... ';
	
	
				$mysqlfile = '<?php
	// This configuration file has been automaticly generated by ReimuCMS

	$ReimuCMS["mysql_server"] = "' . $_POST["server"] . '";
	$ReimuCMS["mysql_user"] = "' . $_POST["username"] . '";
	$ReimuCMS["mysql_pass"] = "' . $_POST["password"] . '";
	$ReimuCMS["mysql_db"] = "' . $_POST["database"] . '";
?>';

				$file_out_mysql = "data/mysql.php";
				$fh_mysql = fopen($file_out_mysql, 'w');
				fwrite($fh_mysql, $mysqlfile);
				fclose($fh_mysql);
	
				$adminuser = mysql_real_escape_string(strtolower($_POST["regusername"]));
				$admindisplay = mysql_real_escape_string($_POST["regusername"]);
				$adminpass = mysql_real_escape_string($_POST["regpassword"]);
	
				$date = date('Y-m-d H:i:s', time());
				$pass = hash("sha256",$_POST['key'] . $adminuser . $adminpass);
				reimucms_do_query("INSERT INTO `reimucms_users` VALUES(NULL, '$adminuser', '$admindisplay', '$pass', '$adminemail', '$date', NULL, 0, 9001)");
				
			} else {
				$chars = "abcdefghijklmnopqrstuvwxyz0123456789`~!@#$%^*()_+-=[]{}\\|;:,./'?";
				srand((double)microtime()*1000000);
				$i = 0;
				$pass = '' ;

				while ($i <= 64) {
					$num = rand() % 64;
					$tmp = substr($chars, $num, 1);
					$pass = $pass . $tmp;
					$i++;
					$pass = hash("sha512", $pass);
				}
				
				echo '<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Installer -&gt; ReimuCMS</title>
	<style type="text/css">
	body {
		font-family: sans-serif;
		font-size: 12px;
	}
	</style>
</head>
<body>
	<form method="POST" action="./?install">
		<table>
			<tr><td style="font-size: 24px;">ReimuCMS Installer</td></tr>
			<tr><td>Please enter the MySQL login info below:</td></tr>
			<tr><td>MySQL Server: </td><td><input type="text" value="localhost" required name="server"></td><td>Note: Almost always localhost</td></tr>
			<tr><td>MySQL Username: </td><td><input type="text" required value="" name="username"></td></tr>
			<tr><td>MySQL Password: </td><td><input type="password" value="" name="password"></td></tr>
			<tr><td>MySQL Database: </td><td><input type="text" required value="" name="database"></td></tr>
			<tr><td><br></td></tr>
			<tr><td>Site configuration:</td></tr>
			<tr><td>Site name: </td><td><input type="text" name="sitename" required></td></tr>
			<tr><td>Default module: </td><td><input type="radio" name="module" value="page" id="page" checked required><label for="page">Website</label><input type="radio" name="module" value="post" id="blog"><label for="blog">Blog</label></td></tr>
			<tr><td>Locale: </td><td><input type="radio" value="en_us" id="en_us" name="locale" checked required><label for="en_us">English (USA)</label><input type="radio" value="jp" id="jp" name="locale" required><label for="jp">Japanese(日本語)(JP)</label></td></tr>
			<tr><td>Password encryption key: </td><td><input name="key" type="text" value="' . $pass . '" required></td></td><td>Just leave this at the default setting unless you have good reason not to.</td></tr>
			<tr><td><br></td></tr>
			<tr><td>Admin account:</td></tr>
			<tr><td>Username: </td><td><input type="text" name="regusername" required></td></tr>
			<tr><td>Password: </td><td><input type="password" name="regpassword" required></td></tr>
		</table>
	<input type="submit" value="Install">
	</form>
</body>
</html>';
	
			}
			exit();
		}
	}
	
	// MySQL connect
	mysql_connect($ReimuCMS['mysql_server'], $ReimuCMS['mysql_user'], $ReimuCMS['mysql_pass']);
	mysql_select_db($ReimuCMS['mysql_db']);
	
	$queries = 0;
	function reimucms_do_query($query_string) {
		global $queries;
		$sql_query_result = mysql_query($query_string);
		$queries = $queries + 1;
		
		return $sql_query_result;
		
	}
	
	// Alert code
	function alert($type,$message) {
		global $ReimuCMS;
		$ReimuCMS["alert"] = $ReimuCMS["alert"] . '<div class="alert-message fade in ' . $type . '" data-alert="alert" ><a class="close" href="#">&times;</a><p>' . $message . '</p></div>';
	}
	
	// Prevent SQL Injection
	if (isset($_COOKIE['ReimuCMS_User'])) {
		$username = mysql_real_escape_string($_COOKIE['ReimuCMS_User']);
	} else {
		$username = "";
	}
	if (isset($_COOKIE['ReimuCMS_Password'])) {
		$password = mysql_real_escape_string($_COOKIE['ReimuCMS_Password']);
	} else {
		$password = "";
	}
	
	// Track "last active" date for user, if the user is logged in.
	reimucms_do_query("UPDATE `reimucms_users` SET `active` = NULL WHERE `username` = '$username' AND `password` = '$password'");

	$sql_usr = reimucms_do_query("SELECT * FROM `reimucms_users` WHERE `username` = '$username' AND `password` = '$password'");
	$r_usr = mysql_fetch_array($sql_usr);
	
	$ReimuCMS['usr_displayname'] = $r_usr['displayname'];
	$ReimuCMS['usr_id'] = $r_usr['id'];
	
	if ($r_usr['powerlevel'] == 1) {
		$ReimuCMS['auth_isLoggedIn'] = true;
		$ReimuCMS['auth_username'] = $r_usr['username'];
		$ReimuCMS['auth_displayname'] = $r_usr['displayname'];
	}
	
	if ($r_usr['powerlevel'] == 9001) {
		$ReimuCMS['auth_isLoggedIn'] = true;
		$ReimuCMS['auth_isAdmin'] = true;
		$ReimuCMS['auth_username'] = $r_usr['username'];
		$ReimuCMS['auth_displayname'] = $r_usr['displayname'];
	}

	
	// Generate the menu~
	
	$sql_menu = reimucms_do_query("SELECT * FROM `reimucms_menu` ORDER BY `pos` ASC");

	$loop1 = true;

	$ReimuCMS['menu_data'] = "";
	
	while($r_menu = mysql_fetch_array($sql_menu)) {
		if ($r_menu['is_url'] == "0") {
			$ReimuCMS['menu_data'] = $ReimuCMS['menu_data'] . "<li><a href=\"./?page/" . $r_menu['linktarget'] . "\" class=\"white-link\">" . $r_menu['linkname'] . "</a></li>";
		} else if ($r_menu['is_url'] == "1") {
			$ReimuCMS['menu_data'] = $ReimuCMS['menu_data'] .	"<li><a href=\"" . $r_menu['linktarget'] . "\" target='_blank' class=\"white-link\">" . $r_menu['linkname'] . "</a></li>";
		} else if ($r_menu['is_url'] == "2") {
			$ReimuCMS['menu_data'] = $ReimuCMS['menu_data'] . "<li><a href=\"./?post/" . $r_menu['linktarget'] . "\" class=\"white-link\">" . $r_menu['linkname'] . "</a></li>";
		}
	}
	
	function BBCode($Text) {	
		$Text = str_replace("<", "&lt;", $Text);
				$Text = str_replace(">", "&gt;", $Text); 
		
		// Convert newlines to HTML <br> tags.
		$Text = nl2br($Text);
		
		$URLSearchString = " a-zA-Z0-9\:\/\-\?\&\.\=\_\~\#\'";
		
		$MAILSearchString = $URLSearchString . " a-zA-Z0-9\.@";

		$Text = preg_replace("/\[url\]([$URLSearchString]*)\[\/url\]/", '<a href="$1" target="_blank">$1</a>', $Text);
		$Text = preg_replace("(\[url\=([$URLSearchString]*)\](.+?)\[/url\])", '<a href="$1" target="_blank">$2</a>', $Text); 
			
		$Text = preg_replace("(\[mail\]([$MAILSearchString]*)\[/mail\])", '<a href="mailto:$1">$1</a>', $Text);
		$Text = preg_replace("/\[mail\=([$MAILSearchString]*)\](.+?)\[\/mail\]/", '<a href="mailto:$1">$2</a>', $Text);
		
		$Text = preg_replace("(\[b\](.+?)\[\/b])is",'<span class="bold">$1</span>',$Text);
		$Text = preg_replace("(\[i\](.+?)\[\/i\])is",'<span class="italics">$1</span>',$Text);
		$Text = preg_replace("(\[u\](.+?)\[\/u\])is",'<span class="underline">$1</span>',$Text);
		$Text = preg_replace("(\[s\](.+?)\[\/s\])is",'<span class="strikethrough">$1</span>',$Text);
		$Text = preg_replace("(\[o\](.+?)\[\/o\])is",'<span class="overline">$1</span>',$Text);
		$Text = preg_replace("(\[color=(.+?)\](.+?)\[\/color\])is","<span style=\"color: $1\">$2</span>",$Text);
		$Text = preg_replace("(\[size=(.+?)\](.+?)\[\/size\])is","<span style=\"font-size: $1px\">$2</span>",$Text);

		$Text = preg_replace("/\[list\](.+?)\[\/list\]/is", '<ul class="listbullet">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=1\](.+?)\[\/list\]/is", '<ul class="listdecimal">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=i\](.+?)\[\/list\]/s", '<ul class="listlowerroman">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=I\](.+?)\[\/list\]/s", '<ul class="listupperroman">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=a\](.+?)\[\/list\]/s", '<ul class="listloweralpha">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=A\](.+?)\[\/list\]/s", '<ul class="listupperalpha">$1</ul>' ,$Text);
		$Text = str_replace("[*]", "<li>", $Text);

		$Text = preg_replace("(\[font=(.+?)\](.+?)\[\/font\])","<span style=\"font-family: $1;\">$2</span>",$Text);

		$CodeLayout = '<table class="bbcode-block"><tr><td class="quotecodeheader"> Code:</td></tr><tr><td class="codebody">$1</td></tr></table>';
		$Text = preg_replace("/\[code\](.+?)\[\/code\]/is","$CodeLayout", $Text);
		
		$phpLayout = '<table class="bbcode-block"><tr><td class="quotecodeheader"> Code:</td></tr><tr><td class="codebody">$1</td></tr></table>';
		$Text = preg_replace("/\[php\](.+?)\[\/php\]/is",$phpLayout, $Text);

		$QuoteLayout = '<table class="bbcode-block"><tr><td class="quotecodeheader"> Quote:</td></tr><tr><td class="quotebody">$1</td></tr></table>';
		$QuoteLayout2 = '<table class="bbcode-block"><tr><td class="quotecodeheader">$1 wrote:</td></tr><tr><td class="quotebody">$2</td></tr></table>';
		while(preg_match("/\[quote=(.+?)\](.+?)\[\/quote\]/is", $Text)) {
			$Text = preg_replace("/\[quote=(.+?)\](.+?)\[\/quote\]/is","$QuoteLayout2", $Text);
		}
		while(preg_match("/\[quote\](.+?)\[\/quote\]/is", $Text)) {
			$Text = preg_replace("/\[quote\](.+?)\[\/quote\]/is","$QuoteLayout", $Text);
				}
 
		$Text = preg_replace("/\[img\](.+?)\[\/img\]/", '<img src="$1">', $Text);
		$Text = preg_replace("/\[img\=([0-9]*)x([0-9]*)\](.+?)\[\/img\]/", '<img src="$3" height="$2" width="$1">', $Text);
		 
		return $Text;
	}
	
	// Core functions start here:
	
	function mod_auth_login() {
		global $ReimuCMS;	
		
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			// Prevent SQL Injection
			$post_user = mysql_real_escape_string(strtolower($_POST['user']));
			$post_pass = mysql_real_escape_string($_POST['pass']);
			
			$sql = reimucms_do_query("SELECT * FROM `reimucms_users` WHERE `username` = '" . $_POST['user'] . "'");
			$r = mysql_fetch_array($sql);
			if ($r['username'] == $post_user && $r['password'] == hash("sha256",$ReimuCMS['key'] . $post_user . $post_pass)) {
				setcookie("ReimuCMS_User", $post_user, 2147483647, "/");
				setcookie("ReimuCMS_Password", hash("sha256",$ReimuCMS['key'] . $post_user . $post_pass), 2147483647, "/");
				reimucms_do_query("INSERT INTO `reimucms_security_log` VALUES(NULL, 'login-success', '" . $_SERVER['REMOTE_ADDR'] . "', '$post_user', 'The user successfully logged in.");
				
				header("Location: ?");
			} else {
				reimucms_do_query("INSERT INTO `reimucms_security_log` VALUES(NULL, 'login-error', '" . $_SERVER['REMOTE_ADDR'] . "', '$post_user', 'The user entered an invalid username or password.')");
				
				alert("error","<strong>Login failed:</strong> Invalid username or password.");

				$ReimuCMS["handler_" . $ReimuCMS['defaultact']]();
			}
		} else {
			header("Location: ?");
		}
	}
	reimucms_add_handler("login", "auth", "login");
	
	
	
	function mod_auth_register() {
		global $ReimuCMS;	
	
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$reg_user = mysql_real_escape_string(strtolower($_POST['reg_user']));
			$reg_display = mysql_real_escape_string($_POST['reg_user']);
			$reg_pass = mysql_real_escape_string($_POST['reg_pass']);
			$reg_pass2 = mysql_real_escape_string($_POST['reg_pass2']);
			$reg_email = mysql_real_escape_string(strtolower($_POST['reg_email']));
			
			if (empty($reg_user) || empty($reg_pass) || empty($reg_pass2) || empty($reg_email)) {
				//echo '<font color="red">All fields are required.</font>';
				$badfield = true;
				
			}
			$sql = reimucms_do_query("SELECT * FROM `reimucms_users` WHERE `username` = '$reg_user'");
			$row = mysql_fetch_row($sql);	

			if ($reg_pass != $reg_pass2) {
				$badpass = true;
			}
			if ($row<1) {
				if ($reg_pass == $reg_pass2) {
					if ($badfield == false) {
						$date = date('Y-m-d H:i:s', time());
						$pass = hash("sha256",$ReimuCMS['key'] . $reg_user . $reg_pass);
						reimucms_do_query("INSERT INTO `reimucms_users` VALUES(NULL, '$reg_user', '$reg_display', '$pass', '$reg_email', '$date', NULL, 0, 1)");
						
						setcookie("ReimuCMS_User", $reg_user, 2147483647, "/");
						setcookie("ReimuCMS_Password", $pass, 2147483647, "/");
							
						reimucms_do_query("INSERT INTO `reimucms_security_log` VALUES(NULL, 'register-success', '" . $_SERVER['REMOTE_ADDR'] . "', '$post_user', 'The user created an account.')");
				
						header("Location: ?");
						
						$reg_success = true;
					}
				} else {
					$badpass = true;
				}
			} else {
				$baduser = true;
			}
			
			if ($badfield || $baduser || $badpass || $reg_failure) {
				include "template/mod/mod_auth_register.php";
			}
		} else if (!$reg_success) {
			include "template/mod/mod_auth_register.php";
		}
	}
	reimucms_add_handler("register", "auth", "register");
	
	function mod_auth_logout() {
		global $ReimuCMS;	

		setcookie("ReimuCMS_User", "", 2147483647, "/");
		setcookie("ReimuCMS_Password", "", 2147483647, "/");
		reimucms_do_query("INSERT INTO `reimucms_security_log` VALUES(NULL, 'logout', '" . $_SERVER['REMOTE_ADDR'] . "', '$username', 'The user has logged out.')");
	
		header("Location: ?");
	}
	reimucms_add_handler("logout", "auth", "logout");
	
	
	function mod_base_getpage() {
		global $ReimuCMS;	
		
		$ReimuCMS['automagic_header'] = false;
		
		$page = mysql_real_escape_string($_GET['page']);
		
		if ($page == "") {
			$page = "index"; 
		}
		
		$sql = reimucms_do_query("SELECT * FROM `reimucms_pages` WHERE `shortname` = '$page'");

		$r = mysql_fetch_array($sql, MYSQL_BOTH);
		
		if ($r>1) {
			$admin = "";
			
			if ($ReimuCMS['auth_isAdmin']) {
				$admin = "[<a href=\"./?act=editpage&page=" . $r['shortname'] . "\">Edit</a>]";
			}
			
			if ($r["bbcode_mode"] != 1) {
				$contents = $r["contents"];
			} else {
				$contents = "<p>" . BBCode($r["contents"]) . "</p>"; 
			}
			
			$sqledit = reimucms_do_query("SELECT * FROM `reimucms_users` WHERE `id` = " . $r['editor']);
			$redit = mysql_fetch_array($sqledit);
			$sqlpost = reimucms_do_query("SELECT * FROM `reimucms_users` WHERE `id` = " . $r['creator']);
			$rpost = mysql_fetch_array($sqlpost);
											
			$header = $header . '<div class="contentarea">' . "\n" . '<h3>' . $r['longname'] . ' <small>' . $admin . "</small></h3>";
					

			/*$footer = 'Created on ' . date("F jS, Y ", strtotime($r['created'])) . "at" . date(" g:i A", strtotime($r['created'])) . ", by <b>" . $rpost['displayname'] . "</b>. ";

			if ($r['edited'] != $r['created']) {
				$footer = $footer . 'Last edited on ' . date("F jS, Y ", strtotime($r['edited'])) . "at" . date(" g:i A", strtotime($r['editor'])) . "	 by <b>" . $redit['displayname'] . "</b>.";
			}*/
			
			$footer = $footer . "</p>";
			
			$ReimuCMS['output_body'] = $header . "\n" . $contents . "\n" . $footer;
			$ReimuCMS['output_title'] = $r['longname'];
		} else {
			$ReimuCMS['output_title'] = $ReimuCMS['title_notfound'];
			
			$ReimuCMS['output_body'] = "<p>" . $ReimuCMS['msg_notfound'] . "</p>";
		}
		
	}
	reimucms_add_handler("page", "base", "getpage");
	
	
	function mod_base_admin() {
		global $ReimuCMS;	
		if ($ReimuCMS['auth_isAdmin']) {
			include "template/mod/mod_base_admin.php";
		} else {
			$ReimuCMS['output_title'] = $ReimuCMS['title_accessdenied'];
			$ReimuCMS['output_body'] = "<p>" . $ReimuCMS['msg_accessdenied'] . "</p>";
		}
	}
	reimucms_add_handler("admin", "base", "admin");
	
	function mod_base_newpage() {
		global $ReimuCMS;	
		if ($ReimuCMS['auth_isAdmin']) {
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				$title = mysql_real_escape_string($_POST['title']);
				$pid = mysql_real_escape_string($_POST['pid']);
				$content = mysql_real_escape_string($_POST['pcontents']);
				$bbcode = mysql_real_escape_string($_POST['bbcode']);
				$date = date('Y-m-d H:i:s', time());
						
				$sql = reimucms_do_query("SELECT * FROM `reimucms_pages` WHERE `shortname` = '$pid'");
				$rows = mysql_fetch_array($sql);
				
				if ($rows<1) {
					reimucms_do_query("INSERT INTO `reimucms_security_log` VALUES(NULL, 'page-create', '" . $_SERVER['REMOTE_ADDR'] . "', '" . $ReimuCMS['usr_displayname'] . "', 'The user created the page $pid.')");
	
				reimucms_do_query("INSERT INTO `reimucms_pages` VALUES(NULL, '$pid', '$title', '$content', '$date', '" . $ReimuCMS['usr_id'] . "', '$date', '" . $ReimuCMS['usr_id'] . "', " . $bbcode . ")");
				
					header("Location: ?page/" . $pid);
				} else {
					$exists = true;
					
					include "template/mod/mod_base_newpage.php";
				}
			} else {
				include "template/mod/mod_base_newpage.php";
			}
		} else {
			$ReimuCMS['output_title'] = $ReimuCMS['title_accessdenied'];
			$ReimuCMS['output_body'] = "<p>" . $ReimuCMS['msg_accessdenied'] . "</p>";
		}
	}
	reimucms_add_handler("newpage", "base", "newpage");
	
	function mod_base_editpage() {
		global $ReimuCMS;	
		if ($ReimuCMS['auth_isAdmin']) {
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				$curpid = mysql_real_escape_string($_GET['page']);
				$title = mysql_real_escape_string($_POST['title']);
				$pid = mysql_real_escape_string($_POST['pid']);
				$content = mysql_real_escape_string($_POST['pcontents']);
				$date = date('Y-m-d H:i:s', time());
				
				reimucms_do_query("INSERT INTO `reimucms_security_log` VALUES(NULL, 'page-edit', '" . $_SERVER['REMOTE_ADDR']. "', '" . $ReimuCMS['usr_id'] . "', 'The user edited the page $curpid.')");
				
				reimucms_do_query("UPDATE `reimucms_pages` SET `shortname` = '$pid', `longname` = '$title', `contents` = '$content', `editor` = '" . $ReimuCMS['usr_id'] . "' WHERE `shortname` = '$curpid';");
				
				header("Location: ?page/" . $pid);
			} else {
				include "template/mod/mod_base_editpage.php";
			}
		} else {
			$ReimuCMS['output_title'] = $ReimuCMS['title_accessdenied'];
			$ReimuCMS['output_body'] = "<p>" . $ReimuCMS['msg_accessdenied'] . "</p>";
		}
	}
	reimucms_add_handler("editpage", "base", "editpage");
	
	function mod_base_delpage() {
		global $ReimuCMS;	
		if ($ReimuCMS['auth_isAdmin']) {
			$page = mysql_real_escape_string($_GET["page"]);
			
			reimucms_do_query("DELETE FROM `reimucms_pages` WHERE `shortname` = '$page' LIMIT 1");
			
			header("Location: ?page");
		} else {
			$ReimuCMS['output_title'] = $ReimuCMS['title_accessdenied'];
			$ReimuCMS['output_body'] = "<p>" . $ReimuCMS['msg_accessdenied'] . "</p>";
		}
	}
	reimucms_add_handler("delpage", "base", "delpage");
	
	function mod_base_ajax_admin_newpage_checkid() {
		global $ReimuCMS;	
		$ReimuCMS['api'] = true;
		
		$page = mysql_real_escape_string($_GET['page']) ;
		
		$sql = reimucms_do_query("SELECT * FROM `reimucms_pages` WHERE `shortname` = '$page'");

		$r = mysql_fetch_array($sql, MYSQL_BOTH);
		
		if ($r>1) {
			echo "ERROR";
		} else {
			echo "OK";
		}
	}
	reimucms_add_handler("ajax_admin_newpage_checkid", "base", "ajax_admin_newpage_checkid");
	
	
	function mod_base_blog() {
		global $ReimuCMS;	
		
		$ReimuCMS['content_multizone'] = true;
		
		$ReimuCMS['automagic_header'] = false;
		
		$publickey = "6LfPsccSAAAAAHAos8FsWyVFVOhUgG5wHk8mKLrq";
		$privatekey = "6LfPsccSAAAAAM566OaSdNVeX2n_rBqx4nkmLWST";
				
		require_once('thirdparty/recaptchalib.php');
				
		# the response from reCAPTCHA
		$resp = null;
		# the error code from reCAPTCHA, if any
		$error = null;
		
		if (isset($_GET['post'])) {
			$id = mysql_real_escape_string($_GET['post']);
		} else {
			$id = "";
		}
		
		$footer = "";
		
		if (isset($_GET['post'])) {
			$sql = reimucms_do_query("SELECT * FROM `reimucms_portal` WHERE `id` = '$id'");
		} else {
			$sql = reimucms_do_query("SELECT * FROM `reimucms_portal` ORDER BY `created` DESC");
		}

		$first = true;
		
		while($r = mysql_fetch_array($sql, MYSQL_BOTH)) {
			
			$admin = "";
			$header = "";
			$footer = "";
			$comments = "";
			
			if ($first) {
				$first = false;
			} else {
				$header = "<hr>";
			}
	
			if ($ReimuCMS['auth_isAdmin']) {
				$admin = "[<a href=\"./?act=editpost&post=" . $r['id'] . "\">Edit</a>]";
			}
				
			$sqledit = reimucms_do_query("SELECT * FROM `reimucms_users` WHERE `id` = " . $r['editor']);
			$redit = mysql_fetch_array($sqledit);
			$sqlpost = reimucms_do_query("SELECT * FROM `reimucms_users` WHERE `id` = " . $r['creator']);
			$rpost = mysql_fetch_array($sqlpost);
				
			if ($id == "") {
				$header = $header . '<div class="contentarea">' . "\n" . '<h3>' . '<a href="?post/' . $r["id"] . '" style="color: black;">' . $r['title'] . '</a> <small>' . $admin . "</small></h3>";
			} else {
				$header = $header . '<div class="contentarea">' . "\n" . '<h3>' . $r['title'] . ' <small>' . $admin . "</small></h3>";
			}
			
			$csql = reimucms_do_query("SELECT * FROM `reimucms_portal_comments` WHERE `pid` = '" . $r['id'] . "'");
			$comments = mysql_num_rows($csql);
				
			if ($comments == 1) { $commentname = "comment"; }
			else { $commentname = "comments"; }
				
			$footer = $footer . "<p>";/* . 'Created on ' . date("F jS, Y ", strtotime($r['created'])) . "at" . date(" g:i A", strtotime($r['created'])) . ", by <b>" . $rpost['displayname'] . "</b>. ";*/
			
			if (!isset($_GET['post'])) {
				$footer = $footer . "<a href='./?post/" . $r['id'] . "'>" . $comments . " " . $commentname . "</a>. ";
			}	
				
			/*if ($r['edited'] != $r['created']) {
				$footer = $footer . 'Last edited on ' . date("F jS, Y ", strtotime($r['edited'])) . "at" . date(" g:i A", strtotime($r['edited'])) . ", by <b>" . $redit['displayname'] . "</b>.";
			}*/
			
			
			$footer = $footer . "</p></div>\n"; 
			
			if (isset($_GET['post'])) {	
				
				if ($_SERVER['REQUEST_METHOD'] == "POST") {
				
				$resp = recaptcha_check_answer ($privatekey,
				       $_SERVER["REMOTE_ADDR"],
				       $_POST["recaptcha_challenge_field"],
				       $_POST["recaptcha_response_field"]);
					if (!$resp->is_valid) {
						$ReimuCMS["output_title"] = $ReimuCMS['title_badcaptcha'];
						$ReimuCMS["output_body"] = "<p class='content'>"  . $ReimuCMS['msg_badcaptcha'] . "</p>";
						return false;
					}

					if ($ReimuCMS["auth_isLoggedIn"] == true) {
						$name = $ReimuCMS["usr_id"];
						$guest = 0;
					} else {
						$guest = 1;
						$name = mysql_real_escape_string($_POST["name"]);
					}
					
					$date = date('Y-m-d H:i:s', time());
					
					reimucms_do_query("INSERT INTO `reimucms_portal_comments` VALUES ('', " . $id . ", \"" . $name . "\", " . $guest . ", \"" . mysql_real_escape_string($_POST["comment"]) . "\", '" . $date . "', '" . $date . "', '" . $ReimuCMS["usr_id"] . "')");
				}
				
				$ReimuCMS['output_title'] = $r['title'];
				
				$csql = reimucms_do_query("SELECT * FROM `reimucms_portal_comments` WHERE `pid` = '" . $r['id'] . "' ORDER BY `id` ASC");
				
				$comments = "";
				while($cr = mysql_fetch_array($csql)) { 			       
					$usr = "";
					
					if (!$cr['isguest']) {
						if (isset($rCMSUIDCache[$cr['uid']])) {
							$usr = $rCMSUIDCache[$cr['uid']];
						} else {
							$sqluid = reimucms_do_query("SELECT * FROM `reimucms_users` WHERE `id` = " . $cr['uid']);
							$ruid = mysql_fetch_array($sqluid);
						
							$usr = $ruid['displayname'];
							$rCMSUIDCache[$cr['uid']] = $usr;
						}
					} else {
						$usr = $cr['uid'] . "<br>Guest";
					}
					$commentdate = "";
					$commenttime = "";
					
					if ($cr['created'] != "0000-00-00 00:00:00") {
						$commentdate = date("F jS, Y ", strtotime($cr['created']));
						$commenttime = date(" g:i A", strtotime($cr['created']));
					} else {
						$commentdate = "an unknown date ";
						$commenttime = " an unknown time";
					}
					
					if ($ReimuCMS["auth_isAdmin"]) {
						$comments = $comments . "<div class='comment pie'><table class='wide'><tr><td class='comment_user'><p>" . $usr . "</p></td><td><p class='small-font'><a href='./?act=delcomment&ctype=post&id=" . $cr["id"] . "&pageid=" . $id . "' onclick='return confirm(\"Are you sure you want to delete this comment? This operation cannot be undone.\")'>[Delete]</a> Comment posted on " . $commentdate . "at" . $commenttime . ":</p><p>" . BBCode($cr['contents']) . "</p></td></tr></table></div>";
					} else {
						$comments = $comments . "<div class='comment pie'><table class='wide'><tr><td class='comment_user'><p>" . $usr . "</p></td><td><p class='small-font'>Comment posted on " . $commentdate . "at" . $commenttime . ":</p><p>" . BBCode($cr['contents']) . "</p></td></tr></table></div>";

					}
					
				}
				
				if ($comments == "") {
					$comments = "<p>There are no comments.</p>";
				}
				
				$comments = $comments . "<p>Post Comment:</p><form method='POST' action='./?post/" . $_GET[post] . "'>";
				
				if (!$ReimuCMS['auth_isLoggedIn']) {
					$comments = $comments . "<label for='name'>Name:&nbsp;</label><input required type='text' name='name' id='name'><br>";
				}
				
				$comments = $comments . "<label for='comment'>Comment:&nbsp;</label><textarea required name='comment' id='comment' class='commentform post-form-textarea'></textarea><br>" . recaptcha_get_html($publickey, $error) . "<input type='submit' value='Post' class='btn primary'></form></div>";
				
				$footer = $footer . "<div class='content'><p class='header-text'>Comments:</p>" . $comments .	"</div>";
			}
			
			$ReimuCMS['output_snippet'] = $header . "\n" . $r['contents'] . "\n" . $footer;
			AppendContent();
		}
	}
	reimucms_add_handler("post", "base", "blog");
	
	function mod_base_editpost() {
		global $ReimuCMS;	
		if ($ReimuCMS['auth_isAdmin']) {
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				$id = mysql_real_escape_string($_GET['post']);
				$title = mysql_real_escape_string($_POST['title']);
				$content = mysql_real_escape_string($_POST['pcontents']);
				$date = date('Y-m-d H:i:s', time());
				
				reimucms_do_query("INSERT INTO `reimucms_security_log` VALUES(NULL, 'page-edit', '" . $_SERVER['REMOTE_ADDR'] . "', '" . $ReimuCMS['usr_id'] . "', 'The user edited the page $curpid.')");
				
				reimucms_do_query("UPDATE `reimucms_portal` SET `title` = '$title', `contents` = '$content', `editor` = '" . $ReimuCMS['usr_id'] . "' WHERE `id` = '$id';");
				
				header("Location: ?post/" . $id); 
			} else {
				include "template/mod/mod_base_editpost.php";
			}
		} else {
			$ReimuCMS['output_title'] = $ReimuCMS['title_accessdenied'];
			$ReimuCMS['output_body'] = "<p>" . $ReimuCMS['msg_accessdenied'] . "</p>";
		}
	}
	reimucms_add_handler("editpost", "base", "editpost");

	function mod_base_delpost() {
		global $ReimuCMS;	
		if ($ReimuCMS['auth_isAdmin']) {
			$post = mysql_real_escape_string($_GET["post"]);
			
			reimucms_do_query("DELETE FROM `reimucms_portal` WHERE `id` = '$post'");
			
			header("Location: ?portal/");
		} else {
			$ReimuCMS['output_title'] = $ReimuCMS['title_accessdenied'];
			$ReimuCMS['output_body'] = "<p>" . $ReimuCMS['msg_accessdenied'] . "</p>";
		}
	}
	reimucms_add_handler("delpost", "base", "delpost");
	
	
	function mod_base_newarticle() {
		global $ReimuCMS;	
		if ($ReimuCMS['auth_isAdmin']) {
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				$title = mysql_real_escape_string($_POST['title']);
				$content = mysql_real_escape_string($_POST['pcontents']);
				$date = date('Y-m-d H:i:s', time());
		
				// reimucms_do_query("INSERT INTO `reimucms_security_log` VALUES(NULL, 'page-create', '$_SERVER['REMOTE_ADDR']', '$ReimuCMS['usr_displayname']', 'The user created the page $pid.')");
	
				reimucms_do_query("INSERT INTO `reimucms_portal` VALUES(NULL, '$title', '$content', '$date', '" . $ReimuCMS['usr_id'] . "', '$date', '" . $ReimuCMS['usr_id'] . "', 0)");
	
				header("Location: ?post/");
			} else {
				include "template/mod/mod_base_newarticle.php";
			}
		} else {
			$ReimuCMS['output_title'] = $ReimuCMS['title_accessdenied'];
			$ReimuCMS['output_body'] = "<p>" . $ReimuCMS['msg_accessdenied'] . "</p>";
		}
	}
	reimucms_add_handler("newarticle", "base", "newarticle");
	
	function mod_base_delcomment() {
		global $ReimuCMS;	
		if ($ReimuCMS['auth_isAdmin']) {
			if ($_GET["ctype"] == "post") {
				$id = mysql_real_escape_string($_GET["id"]);
				reimucms_do_query("DELETE FROM `reimucms_portal_comments` WHERE `id` = '$id' LIMIT 1");
				
				header("Location: ./?post/" . $_GET["pageid"]);
			}
			
		} else {
			$ReimuCMS['output_title'] = $ReimuCMS['title_accessdenied'];
			$ReimuCMS['output_body'] = "<p>" . $ReimuCMS['msg_accessdenied'] . "</p>";
		}
	}
	reimucms_add_handler("delcomment", "base", "delcomment");	
	
	function mod_base_menuedit() {
		global $ReimuCMS;	
		if ($ReimuCMS['auth_isAdmin']) {
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				if ($_GET["act2"] == "add") {
					$ReimuCMS['api_echobody'] = true;
					$ReimuCMS["api"] = true;
					$title = mysql_real_escape_string($_POST["title"]);
					$shortname = mysql_real_escape_string($_POST["shortname"]);
					$url = mysql_real_escape_string($_POST["url"]);
					
					if ($_POST["type"] == "page") {
						if ($_POST["post"] == "yes") {
							reimucms_do_query("INSERT INTO `reimucms_menu` VALUES('', '0', '2', '" . $title . "', '" . $shortname . "');");
						} else {
							reimucms_do_query("INSERT INTO `reimucms_menu` VALUES('', '0', '0', '" . $title . "', '" . $shortname . "');");
						}
					} else if ($_POST["type"] == "url") {
						reimucms_do_query("INSERT INTO `reimucms_menu` VALUES('', '0', '1', '" . $title . "', '" . $url . "');");
					}
					
				
					AppendContent();
				} else if ($_GET["act2"] == "del") {
					$item = mysql_real_escape_string($_POST[id]);

					reimucms_do_query("DELETE FROM `reimucms_menu` WHERE `id` = '$item';");
					
					$ReimuCMS['api_echobody'] = true;
					$ReimuCMS["api"] = true;
					} else {
					$menuItems = $_POST["id"];
					foreach($menuItems as $key => $value) {
						$menuItems[$key]++;
						$key2 = mysql_real_escape_string($key);
						$value2 = mysql_real_escape_string($value);

						reimucms_do_query("UPDATE `reimucms_menu` SET `pos` = '" . $key2 . "' WHERE `linktarget` = '" . $value2 . "';");
					}
					
					$ReimuCMS['api_echobody'] = true;
					$ReimuCMS["api"] = true;
				}
			} else {
				include "template/mod/mod_base_menuedit.php";
			}
		} else {
			$ReimuCMS['output_title'] = $ReimuCMS['title_accessdenied'];
			$ReimuCMS['output_body'] = "<p>" . $ReimuCMS['msg_accessdenied'] . "</p>";
		}
	}
	reimucms_add_handler("menuedit", "base", "menuedit");

	reimucms_add_handler("getmenu", "base", "menuedit");

?>