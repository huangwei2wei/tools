<html><head><title>拾色器</title><style> .cc { width:10;height:8; }</style><script launguage="JavaScript"> function colorOver(theTD) { previewColor(theTD.style.backgroundColor); setTextField(theTD.style.backgroundColor); } function colorClick(theTD) { setTextField(theTD.style.backgroundColor); returnColor(theTD.style.backgroundColor); } function setTextField(ColorString) { document.getElementById("ColorText").value = ColorString.toUpperCase(); } function returnColor(ColorString) { window.returnValue = ColorString; window.close(); } function userInput(theinput) { previewColor(theinput.value); } function previewColor(theColor) { try { PreviewDiv.style.backgroundColor = theColor; } catch (e) { } }</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head><body style="background-color:d4d0c8; margin: 2 2 2 2;"><form name="_ctl0" method="post" action="colorpicker.aspx" id="_ctl0">
<input type="hidden" name="__VIEWSTATE" value="dDwxMTczNTE5OTM3Ozs+Yq/ghNVv9/4COMiKbljpl8+grVw=" />
<table cellpadding=0 cellspacing=0 border=0><tr><td colspan=3><table cellpadding=0 cellspacing=1 style="background-color:ffffff;" border=0 ><tr>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FFFFFF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FFFFCC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FFFF99;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FFFF66;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FFFF33;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FFFF00;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FFCCFF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FFCCCC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FFCC99;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FFCC66;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FFCC33;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FFCC00;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF99FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF99CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF9999;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF9966;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF9933;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF9900;" class=cc></td>
</tr>
<tr>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CCFFFF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CCFFCC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CCFF99;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CCFF66;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CCFF33;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CCFF00;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CCCCFF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CCCCCC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CCCC99;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CCCC66;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CCCC33;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CCCC00;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC99FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC99CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC9999;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC9966;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC9933;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC9900;" class=cc></td>
</tr>
<tr>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:99FFFF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:99FFCC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:99FF99;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:99FF66;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:99FF33;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:99FF00;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:99CCFF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:99CCCC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:99CC99;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:99CC66;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:99CC33;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:99CC00;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:9999FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:9999CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:999999;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:999966;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:999933;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:999900;" class=cc></td>
</tr>
<tr>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:66FFFF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:66FFCC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:66FF99;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:66FF66;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:66FF33;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:66FF00;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:66CCFF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:66CCCC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:66CC99;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:66CC66;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:66CC33;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:66CC00;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:6699FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:6699CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:669999;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:669966;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:669933;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:669900;" class=cc></td>
</tr>
<tr>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:33FFFF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:33FFCC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:33FF99;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:33FF66;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:33FF33;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:33FF00;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:33CCFF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:33CCCC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:33CC99;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:33CC66;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:33CC33;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:33CC00;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:3399FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:3399CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:339999;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:339966;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:339933;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:339900;" class=cc></td>
</tr>
<tr>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:00FFFF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:00FFCC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:00FF99;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:00FF66;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:00FF33;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:00FF00;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:00CCFF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:00CCCC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:00CC99;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:00CC66;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:00CC33;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:00CC00;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:0099FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:0099CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:009999;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:009966;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:009933;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:009900;" class=cc></td>
</tr>
<tr>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF66FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF66CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF6699;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF6666;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF6633;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF6600;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF33FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF33CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF3399;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF3366;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF3333;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF3300;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF00FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF00CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF0099;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF0066;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF0033;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:FF0000;" class=cc></td>
</tr>
<tr>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC66FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC66CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC6699;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC6666;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC6633;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC6600;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC33FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC33CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC3399;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC3366;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC3333;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC3300;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC00FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC00CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC0099;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC0066;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC0033;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:CC0000;" class=cc></td>
</tr>
<tr>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:9966FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:9966CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:996699;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:996666;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:996633;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:996600;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:9933FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:9933CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:993399;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:993366;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:993333;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:993300;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:9900FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:9900CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:990099;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:990066;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:990033;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:990000;" class=cc></td>
</tr>
<tr>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:6666FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:6666CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:666699;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:666666;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:666633;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:666600;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:6633FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:6633CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:663399;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:663366;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:663333;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:663300;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:6600FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:6600CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:660099;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:660066;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:660033;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:660000;" class=cc></td>
</tr>
<tr>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:3366FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:3366CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:336699;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:336666;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:336633;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:336600;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:3333FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:3333CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:333399;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:333366;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:333333;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:333300;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:3300FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:3300CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:330099;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:330066;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:330033;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:330000;" class=cc></td>
</tr>
<tr>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:0066FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:0066CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:006699;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:006666;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:006633;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:006600;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:0033FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:0033CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:003399;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:003366;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:003333;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:003300;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:0000FF;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:0000CC;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:000099;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:000066;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:000033;" class=cc></td>
	<td onMouseOver="colorOver(this);" onClick="colorClick(this);" style="background-color:000000;" class=cc></td>
</tr>
</table></td></tr><tr><td><input type="text" name="ColorText" id="ColorText" style="width:60;height:22;" onKeyUp="userInput(this);"></td><td align=center><div id="PreviewDiv" style="width:50;height:20;border: 1 solid black; background-color: #ffffff;">&nbsp;</div></td><td align=right><input type="button" value="确定" onClick="returnColor(ColorText.value);" id="ColorButton"  style="width:80;"></td></tr></table></form></body></html>