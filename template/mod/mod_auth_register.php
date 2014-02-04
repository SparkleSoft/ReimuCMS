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
	
	$ReimuCMS["output_title"] = "Register";
	
	$ReimuCMS["output_body"] = '<form method="POST" action="?register">
	<table>
	<tr><td class="wide">Username</td><td><input name="reg_user" type="text" class="padded-input form-hax" value="' . $reg_display . '"></td></tr>
	<tr><td class="wide">Password</td><td><input name="reg_pass" type="password" class="padded-input form-hax"></td></tr>
	<tr><td class="wide">Confirm Password</td><td><input name="reg_pass2" type="password" class="padded-input form-hax"></td></tr>
	<tr><td class="wide">Email</td><td><input name="reg_email" type="text" class="padded-input form-hax" value="' . $reg_email . '"></td></tr>
	</table>
	<input type="submit" id="inputLogin" name="submit" class="btn primary" value="Register">
	</form>';
	
	if ($baduser) {
		alert("error",$ReimuCMS["auth_register_baduser"]);
	}
	if ($badpass) {
		alert("error",$ReimuCMS["auth_register_badpass"]);
	}
	if ($badfield) {
		alert("error",$ReimuCMS["auth_register_badfield"]);
	}
?>