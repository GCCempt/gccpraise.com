<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>OpenSong Viewer</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />	
    <script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery.transposer.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="js/functions.js"></script>
    <script type="text/javascript">
		var s = localStorage.getItem("maxfont");
		
		function changeMaxFont()
		{
			var strValidChars = "0123456789";
			var strChar;
			var blnResult = true;
			var strString = document.getElementById('maxfontsize').value;
			if (strString.length == 0) return false;
			
			//  test strString consists of valid characters listed above
			for (i = 0; i < strString.length && blnResult == true; i++)
			{
				strChar = strString.charAt(i);
				if (strValidChars.indexOf(strChar) == -1)
			 	{
			 		blnResult = false;
			 	}
			}
			if (blnResult)
			{
				localStorage.setItem("maxfont", strString);
			}
			else
			{
				alert("Please enter a valid font size (should be a number)");
			}
		}
		$(document).ready(function () 
		{
			if (s)
			{
			document.getElementById('maxfontsize').value = s;		
			}
		});
	</script>
</head>
<body>
<div id="header"><img src="images/header.gif" />
<div id="menu"><a href="about.php">About</a> <a href="settings.php">Settings</a> <a href="preview_index.php">Index</a> <a href="preview_set.php">Sets</a></div>
</div>
<div id="container">
<p><strong>Settings</strong></p>
Max Font Size: <input type="text" name="maxfontsize" id="maxfontsize" value="" />
<input type="button" value="Save" onclick="changeMaxFont();"
</div>

</body>
</html>