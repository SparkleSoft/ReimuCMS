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
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    
	<title><?php
	if ($ReimuCMS['output_title'] != "") {
		echo $ReimuCMS['output_title'] . " -&gt; " . $ReimuCMS['sitename'];
	} else {
		echo $ReimuCMS['sitename'];
	}
	?></title>
    <meta name="description" content="<?php echo $ReimuCMS["out_description"]; ?>">
    <meta name="author" content="<?php echo $ReimuCMS["out_author"]; ?>">
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<!-- ReimuCMS doesn't work in less than IE 8, so only fixing IE 8 -->
    <!--[if IE 8]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link href="rsrc/lib/css/bootstrap.min.css" rel="stylesheet">
	
	<script type="text/javascript" src="rsrc/lib/js/jquery-1.6.4.min.js"></script>
	<script type="text/javascript" src="rsrc/lib/js/bootstrap-alerts.js"></script>
	<script type="text/javascript" src="rsrc/lib/js/bootstrap-dropdown.js"></script>
	<!-- <script type="text/javascript" src="rsrc/lib/js/bootstrap-modal.js"></script> -->
	<!-- <script type="text/javascript" src="rsrc/lib/js/bootstrap-popover.js"></script> -->
	<!-- <script type="text/javascript" src="rsrc/lib/js/bootstrap-scrollspy.js"></script> -->
	<!-- <script type="text/javascript" src="rsrc/lib/js/bootstrap-tabs.js"></script> -->
	<!-- <script type="text/javascript" src="rsrc/lib/js/bootstrap-twipsy.js"></script> -->
    <style type="text/css">
      html, body {
	background-color: #eee;
      }
      body {
	padding-top: 40px;
      }
      .container > footer p {
	text-align: center;
      }
      .container {
	width: 820px;
      }

      .content {
	background-color: #fff;
	padding: 20px;
	margin: 0 -20px;
	-webkit-border-radius: 0 0 6px 6px;
	   -moz-border-radius: 0 0 6px 6px;
		border-radius: 0 0 6px 6px;
	-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
	   -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
		box-shadow: 0 1px 2px rgba(0,0,0,.15);
      }

      .page-header {
	background-color: #f5f5f5;
	padding: 20px 20px 10px;
	margin: -20px -20px 20px;
      }

      .content .span10,
      .content .span4 {
	min-height: 500px;
      }

      .content .span4 {
	margin-left: 0;
	padding-left: 19px;
	border-right: 1px solid #eee;
      }

      .topbar .btn {
	border: 0;
      }

	  footer > p {
		font-size: 10px;
	  }
	  
	  .iewarning {
	    color: red;
		font-size: 16px;
		text-align: center;
		font-weight: bold;
		padding: 8px;
	  }
    </style>
	<!--[if lte IE 6]>
	<style type="text/css">
	  .iewarning {
	    font-size: 20px;
	  }
	</style>
	<![endif]-->
  </head>

  <body onload="$('#topbar').dropdown()">
    <div class="topbar" id="topbar">
      <div class="fill">
	<div class="container">
	  <a class="brand" href="?"><?php
			if (isset($ReimuCMS['logo_image'])) {
				echo '<img src="' . $ReimuCMS['logo_image'] . " alt=" . $ReimuCMS['sitename'] . ">";	
			} else {
				echo $ReimuCMS['sitename'];
			}
		  ?></a>

	  <ul class="nav">
			<?php echo $ReimuCMS['menu_data']; ?>
	  </ul>
		      
			  
		<?php
			if (!$ReimuCMS["auth_isLoggedIn"]) {
				echo '<ul class="nav secondary-nav">
					  <li><form action="?login" class="pull-right" method="POST">
					  <input class="input-small" type="text" placeholder="Username" name="user">
					  <input class="input-small" type="password" placeholder="Password" name="pass">
					  <button class="btn" type="submit">Login</button>
					  </form></li><li><a href="?register">Register</a></li>
					  </ul>';
			} else {
				echo '<ul class="nav secondary-nav">
					  <li class="dropdown" data-dropdown="dropdown">
					  <a href="#" class="dropdown-toggle">' . $ReimuCMS['usr_displayname'] . '</a>
					  <ul class="dropdown-menu">
					  <li><a href="?logout">Logout</a></li>
					  <li><a href="#">Settings</a></li>';
					  
				if ($ReimuCMS["auth_isAdmin"]) {
					echo '<li class="divider"></li>
					<li><a href="?admin">Admin</a></li>';
				}
					  
				echo  '</ul>
					  </li>
					  </ul>';
			}
		?>
		
		<!--[if lte IE 7]>
			<br><br><br><br><br>
			<div class="iewarning">Warning: Please upgrade your browser to something compatible with the internet.<br><a href="http://www.browserchoice.eu/" target="_blank">There are many browsers to choose from, any except the one you are using is good.</a></div>
		<![endif]-->
	</div>
      </div>	  
    </div>

    <div class="container">

      <div class="content">
		<!--[if lte IE 7]>
			<br><br><br><br><br>
		<![endif]-->

		<?php if (isset($ReimuCMS["alert"])) {echo $ReimuCMS["alert"];} ?>
		
	<div class="row">
		  <!-- <div class="span4">
	    <h3>Sidebar</h3>
	  </div>-->
	  <div class="span10 span14">
	    <?php
				if ($ReimuCMS['automagic_header']) {
						echo '<h3>' . $ReimuCMS['output_title'] . '</h3>';
					}
					
				echo $ReimuCMS['output_body'];
				
				$time=microtime();
				$endtime=substr($time,11).substr($time,1,9);
				
				$RAM["raw"] = memory_get_peak_usage(true);
				$unit=array('bytes','KiB','MiB','GiB','TiB','PiB');
				$RAM["converted"] = @round($RAM["raw"]/pow(1024,($i=floor(log($RAM["raw"],1024)))),2).' '.$unit[$i];
			?>
	  </div>
	</div>
      </div>

      <footer>
	<p>Powered by <a href="http://code.google.com/p/reimucms/">ReimuCMS 1.0</a> | Generated in <?php echo round($endtime - $starttime,3); ?> seconds with <?php global $queries; echo $queries; ?> queries, using <?php echo $RAM["converted"]; ?> of memory.</p>
		</footer>

    </div> <!-- /container -->

  </body>
</html>
