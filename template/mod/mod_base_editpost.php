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
	
	$id = mysql_real_escape_string($_GET["post"]);
	$sql2 = reimucms_do_query("SELECT * FROM `reimucms_portal` WHERE `id` = '$id'");
	$r2 = mysql_fetch_array($sql2, MYSQL_BOTH);	

	$ReimuCMS[output_title] = "Editing " . $r2["title"];
	$ReimuCMS[output_body] = '
<form method="POST" action="./?act=editpost&amp;post=' . $_GET["post"] . '" name="editpost">
<div class="emulate-p">
<table>
<tr><td>Title: </td><td><input required type="text" class="post-form-input" name="title" value="' . $r2["title"] . '"></td><td class="middle"><div class="form-tip" id="form-title"></div></td></tr>
<tr><td>Contents: </td><td><textarea required name="pcontents" id="pcontent" class="post-form-input post-form-textarea">' . $r2["contents"] . '</textarea></td><td><div class="form-tip textarea-tip" id="form-contents"></div></td></tr>
<tr><td></td><td><input type="submit" value="Save" class="btn primary" name="submit" id="submit">&nbsp;<span id="submit-note"></span>&nbsp;<span id="submit-note2"></span> <a onclick="return confirm(\'Are you sure you want to delete this post?\nTHIS OPERATION CANNOT BE UNDONE!\');" href="./?act=delpost&post=' . $_GET["post"] . '" class="btn danger">Delete post</a></td></tr>
</table>
</div>
</form>'
?>