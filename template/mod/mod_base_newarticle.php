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

	$ReimuCMS[output_title] = "New Article";
	$ReimuCMS[output_body] = '
<form method="POST" action="./?act=newarticle" name="newarticle">
<div class="emulate-p">
';
if ($error) {
	$ReimuCMS[output_body] = $ReimuCMS[output_body] . '<font color="red">' . $ReimuCMS[msg_allrequired] . '<font><br><br>';
}
$ReimuCMS[output_body] = $ReimuCMS[output_body] . '<table>
<tr><td>Title: </td><td><input required type="text" class="post-form-input" name="title" id="title" placeholder="My Life Story" value="' . $_POST[title] . '"></td></tr>
<tr><td>Contents: </td><td><textarea required name="pcontents" id="pcontent" class="post-form-input post-form-textarea" placeholder="&lt;p&gt;In other news, Sony sued someone again...&lt;/p&gt;">' . $_POST[pcontents] . '</textarea></td></tr>
<tr><td></td><td><input type="submit" value="Post!" class="btn primary" name="submit" id="submit"></td></tr>
</table>
</div>
</form>';
?>
