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

	$ReimuCMS["output_title"] = "New Page";
	$ReimuCMS["output_body"] = '
<script type="text/javascript" src="rsrc/lib/js/midori.min.js"></script>
<script type="text/javascript">
window.reimucms_lastID = "";

function validateForm() {
	if (document.getElementById("pid").value != "") {
		var ajaxCheckID = new midoriAjax(function() {
			if (ajaxCheckID.responseText != "ERROR") {
				window.reimucms_idInUse = "false";
				document.getElementById("form-id").innerHTML = "<span class=\"label success\">OK</span>";
				document.getElementById("submit-note").innerHTML = "";
			} else {
				window.reimucms_idInUse = "true";
				document.getElementById("form-id").innerHTML = "<span class=\"label important\">ID in use</span>";
				document.getElementById("submit-note").innerHTML = "Errors in form!";
				
			}
		}, "", true);
		if (window.reimucms_lastID != document.getElementById("pid").value) {
			window.reimucms_lastID = document.getElementById("pid").value;
			document.getElementById("form-id").innerHTML = "<span class=\"label notice\">Checking ID...</span>";
			ajaxCheckID.post("./?act=ajax_admin_newpage_checkid&page=" + document.getElementById("pid").value, "", "GET");
		}
	} else {
		window.reimucms_idInUse = "true";
		document.getElementById("pid").style.backgroundColor = "";
		document.getElementById("form-id").innerHTML = "";
		document.getElementById("submit-note").innerHTML = "";
	}
	
	if (document.getElementById("title").value == "" || document.getElementById("pid").value == "") {
		window.reimucms_emptyForms = "true";
		document.getElementById("submit-note2").innerHTML = "All fields are required!";
	} else {
		window.reimucms_emptyForms = "false";
		document.getElementById("submit-note2").innerHTML = "";
	}
}
</script>
<form method="POST" action="./?act=newpage" name="newpage">
<div class="emulate-p">
';
if ($exists) {
	alert("error",$ReimuCMS["msg_pageexists"]);
	$validate = '<script type="text/javascript">validateForm();</script>';
}
$ReimuCMS["output_body"] = $ReimuCMS["output_body"] . '<table>
<tr><td style="width: 120px;">Page Title: </td><td><input required type="text" class="post-form-input" name="title" onfocus="validateForm();" onblur="validateForm();" id="title" placeholder="My Awesome Page" value="' . $_POST["title"] . '"></td></tr>
<tr><td style="width: 120px;">Page ID: </td><td><input required type="text" class="post-form-input" name="pid" id="pid" onfocus="validateForm();" onblur="validateForm();" placeholder="my_awesome_page" value="' . $_POST["pid"] . '">&nbsp;&nbsp;&nbsp;&nbsp;<span id="form-id"></span></td></tr>
<tr><td style="width: 120px;">Page Type: </td><td><input type="radio" name="bbcode" id="bbfalse" value="0" checked> <b>HTML</b> or <input type="radio" name="bbcode" id="bbtrue" value="1"> <b>BBCode</b></td></tr>
<tr><td style="width: 120px;">Page Contents: </td><td><textarea required name="pcontents" id="pcontent" class="post-form-input post-form-textarea" onfocus="validateForm();" onblur="validateForm();" placeholder="&lt;p&gt;This is the text of My Awesome Page&lt;/p&gt;">' . $_POST["pcontents"] . '</textarea></td></tr>
<tr><td style="width: 120px;"></td><td><input type="submit" value="Create Page" class="btn primary" name="submit" id="submit">&nbsp;<span id="submit-note"></span>&nbsp;<span id="submit-note2"></span></td></tr>
</table>
</div>
</form>' . $validate;
?>
