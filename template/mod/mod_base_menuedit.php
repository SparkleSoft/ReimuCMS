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

	$ReimuCMS["output_title"] = "Menu Editor";
	
	if ($_GET["act"] == "getmenu") {
		$basic = true;
		$ReimuCMS["api"] = true;
		$ReimuCMS['api_echobody'] = true;
	}
	
	$sql_menu = reimucms_do_query("SELECT * FROM `reimucms_menu` ORDER BY `pos` ASC");
			
	if ($basic != "true") {
		$ReimuCMS["output_snippet"] = '	
		<script src="rsrc/lib/js/jquery-ui-1.8.11.custom.min.js"></script>
		<style type="text/css">.hidden { display: none; }</style>
		<script type="text/javascript">
			function delMenuItem(name) {
				$.post("./?act=menuedit&act2=del", "id=" + name, function(data) {
					$("#menuitems").load("./?act=getmenu");
				});
			}
			
			function initDragAndDrop() {
				$( "#menuitems" ).sortable({ placeholder: "ui-state-highlight" });
				$( "#menuitems" ).disableSelection();
			}

			function postEditMenu() {
				$.post("./?act=menuedit", $("#menuitems").sortable("serialize"), function(data){
					$("#menuitems").load("./?act=menuedit&getmenucontents=true");
				});
			}

			initDragAndDrop();

		</script>
		<div id="menuitems">';
				
		AppendContent();
	}
	
	$menu = false;
	while($r_menu = mysql_fetch_array($sql_menu)) {
		$menu = true;
		$ReimuCMS["output_snippet"] = "<div class='alert-message info' id='id-" . $r_menu["id"] . "' name='" . $r_menu["id"] . "'><a class='close' href='#' onclick='delMenuItem(\"" . $r_menu["id"] . "\");return false;'>×</a>" . $r_menu["linkname"] . " (" . $r_menu["linktarget"] . ")</div>";
		AppendContent();
	}

	if ($basic != "true") {
		if (!$menu) {
			$ReimuCMS["output_snippet"] = '<div class="alert-message info block-message">No menu items exist yet.</div>';
			AppendContent();
		}
	
		$ReimuCMS["output_snippet"] = '</div>';
		AppendContent();		
		$ReimuCMS["output_snippet"] = '<input type="button" class="btn primary" onclick="postEditMenu();" value="Update"><br><br>';
		AppendContent();
		$ReimuCMS["output_snippet"] = '<script src="rsrc/lib/js/menu.js"></script>';
		AppendContent();
		
		$ReimuCMS["output_snippet"] = '<form method="POST" action="?act=menuedit&act2=add" id="menunew" name="menunew" class="emulate-p">
		<input type="hidden" name="post" id="post" value="no">
		<table>
		<tr>
		<td>Link Title:</td>
		<td><input class="contentFormText" name="title" id="title" type="text"></td>
		</tr>
		<tr id="addmurl" class="hidden">
		<td>URL:</td>
		<td><input class="contentFormText" name="url" id="url" type="text"></td>
		</tr>
		<tr id="addmpage">
		<td>Page:</td>
		<td><select name="shortname" id="shortname" class="select">';

		AppendContent();
		
		$sql_menu = reimucms_do_query("SELECT * FROM `reimucms_pages` ORDER BY `id` ASC");

		while($r_menu = mysql_fetch_array($sql_menu)) {
			$ReimuCMS["output_snippet"] = "<option value='" . $r_menu[shortname] . "' onclick='document.getElementById(\"post\").value=\"no\"'>" . $r_menu[longname] . " (" . $r_menu[shortname] . ")</option>";
			AppendContent();
		}
		
		$sql_menu = reimucms_do_query("SELECT * FROM `reimucms_portal` ORDER BY `id` ASC");

		while($r_menu = mysql_fetch_array($sql_menu)) {
			$ReimuCMS["output_snippet"] = "<option value='" . $r_menu["id"] . "' onclick='document.getElementById(\"post\").value=\"yes\"'>" . $r_menu["title"] . " (Posted " . date("F jS, Y ", strtotime($r_menu['created'])) . ")</option>";
			AppendContent();
		}
		
		$ReimuCMS["output_snippet"] = '</select></td>
		</tr><tr><td>Link Type:</td><td><select name="type"><option value="page" selected onClick="$(\'#addmpage\').show();$(\'#addmurl\').hide();" class="noBorder" id="addpage">Page</option><option value="url" onClick="$(\'#addmurl\').show();$(\'#addmpage\').hide();" class="noBorder" id="addurl">URL</option></select>
		</td></tr>
		</table>
		<input onclick=\'$.post("./?act=menuedit&act2=add", $("#menunew").serialize(), function(data){$("#menuitems").load("./?act=getmenu");});return false;\' type="submit" value="Add Item" class="btn primary"></form>';
		
		AppendContent();
	}
?>