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
	
	$curpid = mysql_real_escape_string($_GET["page"]);
	$sql2 = reimucms_do_query("SELECT * FROM `reimucms_pages` WHERE `shortname` = '$curpid'");
	$r2 = mysql_fetch_array($sql2, MYSQL_BOTH);	

	$ReimuCMS["output_title"] = "Editing " . $r2["longname"];
	$ReimuCMS["output_body"] = '
<form method="POST" action="./?act=editpage&page=' . $_GET["page"] . '" name="editpage">
<div class="emulate-p">
<table>
<tr><td style="width: 120px;">Page Title: </td><td><input required type="text" class="post-form-input" name="title" onfocus="validateForm();" onblur="validateForm();" id="title" placeholder="My Awesome Page" value="' . $r2["longname"] . '"></td></tr>
<tr><td style="width: 120px;">Page ID: </td><td><input required type="text" class="post-form-input" name="pid" id="pid" onfocus="validateForm();" onblur="validateForm();" placeholder="my_awesome_page" value="' . $r2["shortname"] . '">&nbsp;&nbsp;&nbsp;&nbsp;<span id="form-id"></span></td></tr>
<tr><td style="width: 120px;">Page Contents: </td><td><textarea required name="pcontents" id="pcontent" class="post-form-input post-form-textarea" onfocus="validateForm();" onblur="validateForm();" placeholder="&lt;p&gt;This is the text of My Awesome Page&lt;/p&gt;">' . $r2["contents"] . '</textarea></td></tr>
<tr><td style="width: 120px;"></td><td><input type="submit" value="Save" class="btn primary" name="submit" id="submit">&nbsp;<span id="submit-note"></span>&nbsp;<span id="submit-note2"></span> <a onclick="return confirm(\'Are you sure you want to delete this page?\nTHIS OPERATION CANNOT BE UNDONE!\');" href="./?act=delpage&page=' . $_GET["page"] . '" class="btn danger">Delete page</a></td></tr>
</table>
</div>
</form>' . $validate;
?>