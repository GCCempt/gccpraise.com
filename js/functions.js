// JavaScript Document
var root= document.compatMode=='BackCompat'? document.body : document.documentElement;
var isVerticalScrollbar= root.scrollHeight>root.clientHeight;
var isHorizontalScrollbar= root.scrollWidth>root.clientWidth;

autoScroll = true;
scrollSpeed = 250;
var scr;
var allowscroll = localStorage.getItem("allowscr");
var maxFontSize = localStorage.getItem("maxfont");
var hideList = localStorage.getItem("hidesetlist");
if (!maxFontSize)
{
	maxFontSize = 20;
}
var csong = parseInt(localStorage.getItem("cursong"));
$(function() {
	$("pre").transpose();
});
/*$(function() {
$('#autoscr').dialog({
				autoOpen: true,
				width: 100,
				title: "Scroll",
				position: [root.clientWidth, root.clientHeight/2],
				close: function() {modScroll(0)}
			});
		   });
*/
//alert(document.documentElement.clientHeight);

var logLength = localStorage.length;
var slist = new Array();

if (logLength > 0)
{
	 for (i = 0; i <= logLength-1; i++)
	 {
		 if (localStorage.key(i) == "slist")
		 {
			 loc = localStorage.getItem("slist").split(';');
			 slist = loc;
		 }
	 }
}

$(function() {
$('#setlist').dialog({
				autoOpen: true,
				width: 400,
				title: "Set List",
				position: [root.clientWidth, 0],
				buttons: {
					"Show/Hide": function () {
						 doShowHide();
						 if (hideList == 1)
						 {
							 localStorage.setItem("hidesetlist", 0);
						 }
						 else
						 {
							 localStorage.setItem("hidesetlist", 1);
						 }
					}
				},
				close: function() {$('#menu').append("<a id='setlink' style='display:none' href='javascript:void(0)' onclick='$(\"#setlist\").dialog(\"open\");'>Set List</a>");	$('#setlink').fadeIn();$('#setlink').effect("pulsate")},
				open: function() {$('#setlink').remove()},
				hide: "drop"
			});
	printSetlist();
	
	if (hideList == 0)
	{
		doShowHide()
	}
	if (allowscroll !=0)
	{
		adjustFont();
	}
});

function doShowHide()
{
	$('#setlist').animate({
				opacity: 'toggle',
				height: 'toggle'
			  }, 1000, function() {
				 
			  });
}

function setSong(num)
{
	localStorage.setItem("cursong", num);
	csong = num;
	return true;
}

function addToSetlist(song)
{
	slist.push(song.replace(/_/g, " "));
	localStorage.removeItem("slist");
	localStorage.setItem("slist", slist.join(';'));
	if (!csong)
	{
		setSong(0);
	}
	printSetlist();
}

/*NEED TO UPDATE Next/Previous ON REMOVE/UP/DOWN*/
function removeFromSetlist(song)
{
	$(song).remove();
	var id= song.substr(song.lastIndexOf('-')+1, (song.length-song.lastIndexOf('-'))+1);
	slist.splice(id, 1);		
	$('#setlist').html("");
	if (id <= csong)
	{
		setSong(csong-1)	
	}
	printSetlist();
	
}

function moveUp(id)
{
	if(id >0)
	{
		var tmp = slist[id-1];
		slist[id-1] = slist[id];
		slist[id] = tmp;
		printSetlist();
	}
}

function moveDown(id)
{
	if(id < slist.length-1)
	{
		var tmp = slist[id+1];
		slist[id+1] = slist[id];
		slist[id] = tmp;
		printSetlist();
	}
}

function printSetlist()
{
	var sclass = 'normal';
	localStorage.removeItem("slist");
	$('#setlist').html("");
	
	var listOptions = "";
	
	if (slist.length >0)
	{	
		localStorage.setItem("slist", slist.join(';'));
		for (i = 0; i <= slist.length-1; i++)
		{
			sclass = 'normal'
			if (i==csong)
			{
				sclass = 'current';
			}
			$('#setlist').append('<span id="' + slist[i].replace(/ /g, "_") + '-' + i +'" class="' + sclass + '">' +
			'<a href="preview_song.php?s=' + slist[i] + '" onclick="return setSong(' + i + ');">' + slist[i] + 
			'</a> (<a href="javascript:void(0)" onclick="removeFromSetlist(\'#' + 
			slist[i].replace(/ /g, "_") + '-' + i + '\');">Remove</a>) ' +
			'(<a href="javascript:void(0)" onclick="moveUp(' + i + ');">Up</a>) ' +
			'(<a href="javascript:void(0)" onclick="moveDown(' + i + ');">Down</a>)<br /></span>');
		}
		listOptions = "| <a href='preview_song.php?s=" + slist[0] + 
		"' onclick='return setSong(0);'>Start</a> | " + "<a href='preview_song.php?s=" + slist[csong] + "'>Continue</a>"
		+ " | <a href='javascript:void(0)' onclick='clearList()'>Clear Set List</a>";
	}
	$('#setlist').dialog("option", "title", "Set List" + listOptions);
	var curpage = window.location.pathname;
	//alert(curpage.charAt(curpage.length-1));
	if (curpage.toUpperCase().indexOf('INDEX') == -1 && curpage.charAt(curpage.length-1)!='/')
	{
		updateSongNav();
		$('#setlist').append("<br /><br />" + $('#songnav').html());
	}
}
function updateSongNav()
{
	//alert(csong);
	$('#songnav').html("");
	if (csong > 0)
	{
		$('#songnav').append("<a href='preview_song.php?s=" + slist[csong-1] + "' onclick='prev();'>Previous Song</a> | ");
	}
	if (csong < slist.length-1)
	{
		$('#songnav').append("<a href='preview_song.php?s=" + slist[csong+1] + "' onclick='next();'>Next Song</a>");
	}

}
function next()
{
	localStorage.setItem("cursong", csong+1);
}
function prev()
{
	localStorage.setItem("cursong", csong-1);
}
function pageScroll() 
{
	if (autoScroll)
	{
		window.scrollBy(0,5); // horizontal and vertical scroll increments
		//var position = $( "#autoscr" ).dialog( "option", "position" );
		//alert(position);
		//$("#autoscr" ).dialog( "option", "position", position);
		scr = setTimeout('pageScroll()',scrollSpeed); // scrolls every 100 milliseconds
	}
}

function modScroll(speed)
{
	if (speed==0)
	{
		clearTimeout(scr);
		scr = null;
		scrollSpeed = speed;
		autoScroll = false;
		$("#autoscr").html("<a href='javascript:void(0)' onclick='modScroll(250);'>Start</a>");
	}
	else
	{
		scrollSpeed = speed;
		autoScroll = true;
		$("#autoscr").html("<a href='javascript:void(0)' onclick='modScroll(0);'>Stop</a><br /><br /><a href='javascript:void(0)' onclick='modScroll(" + (scrollSpeed+100) + ");'>Slower</a><br /><br /><a href='javascript:void(0)' onclick='modScroll(" + (scrollSpeed-100) + ");'>Faster</a>");
		if(!scr)
		{
			setTimeout('pageScroll()',scrollSpeed);
		}
	}
}

function clearList()
{
	if(confirm("Are you sure you want to clear the set list?"))
	{
		localStorage.setItem("cursong", 0);
		slist = new Array();
		printSetlist();
	}
}

function adjustFont()
{
	var currentFontSize = $('pre').css('font-size');
	var currentFontSizeNum = parseFloat(currentFontSize, 10);
	while(!isHorizontalScrollbar)
	{
		 currentFontSize = $('pre').css('font-size');
		 currentFontSizeNum = parseFloat(currentFontSize, 10);
		 newFontSize = currentFontSizeNum+1;
		 if (newFontSize <= maxFontSize-1)
		 {
			$('pre').css('font-size', newFontSize);
			isHorizontalScrollbar= root.scrollWidth>root.clientWidth;
		 }
		 else
		 {
			 break;
		 }
	}
}

/*
if (csong > 0)
{
	$('#songnav').append("<a href='preview_song.php?s=" + slist[csong-1] + "' onclick='prev();'>Previous Song</a> | ");
}
if (csong < slist.length-1)
{
	$('#songnav').append("<a href='preview_song.php?s=" + slist[csong+1] + "' onclick='next();'>Next Song</a>");
}
*/
/*$(document).ready(function () {
modScroll(0);							
});*/
	