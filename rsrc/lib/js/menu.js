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

function initDragAndDrop() {
	$( "#menuitems" ).sortable({ placeholder: "ui-state-highlight" });
	$( "#menuitems" ).disableSelection();
	$( "#drop" ).disableSelection();
	$( "#drop" ).droppable({
		accept: "#menuitems div",
		drop: function( event, ui ) {
			$(ui.draggable).effect( "clip", {}, 100, function() {
				$.post("./?act=menuedit&act2=del", "id=" + ui.draggable.attr("name"), function(data){
					$("#menuitems").load("./?act=menuedit&getmenucontents=true");
				});
			});
		}
	});
	
	$( "#pages" ).sortable({ placeholder: "ui-state-highlight" });
	$( "#pages" ).disableSelection();
	$( "#pagedrop" ).disableSelection();
	$( "#pagedrop" ).droppable({
		accept: "#pages div",
		drop: function( event, ui ) {		
			$('#pageToDeleteName').html(ui.draggable.html());
				
			$('#deleteConfirm').fadeIn("slow");
			
			window.deleteItem = ui.draggable;
		}
	});
	
	$( "#pageedit" ).disableSelection();
	$( "#pageedit" ).droppable({
		accept: "#pages div",
		drop: function( event, ui ) {		
			CurrentPage = ui.draggable.attr("name");
			editPage();
		}
	});
}

function postEditMenu() {
	$.post("./?act=menuedit", $("#menuitems").sortable('serialize'), function(data){
		$("#menuitems").load("./?act=menuedit&getmenucontents=true");
		$('#mstatus').html(data);
	});
}

function addMenu() {
	$('#addmenu').fadeTo(200, 0, function() {
		$('#addmenu').animate({height: 'toggle'}, 'slow', function() {
			$.post("./?act=menuedit&act2=add", $("#menunew").serialize(), function(data){
				$("#menuitems").load("./?act=menuedit&getmenucontents=true");
				$('#mstatus').html(data);
			});
		});
	});
}

initDragAndDrop();

