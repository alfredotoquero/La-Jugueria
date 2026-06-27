<?
	ini_set("session.gc_maxlifetime","7200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("002wf3f3kgdvr/983y4rhouConRem.php");
	include("002wf3f3kgdvr/983y4rhou.php");
	if($_GET["idcuenta"]>0){
		mysql_query("truncate trcuentamenutmp");
		mysql_query("insert into trcuentamenutmp select idmenu,idingrediente,cantidad,tipo,precio,1 from trcuentamenu where idcuenta = '".$_GET["idcuenta"]."'");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Table navigation with keys - Example</title>
<script src="js/jquery2.js" type="text/javascript"></script>
<script src="js/tablenavigation.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery.tableNavigation({
		table_selector: 'table.navigateable',
		row_selector: 'table.navigateable tbody tr',
		selected_class: 'selected',
		activation_selector: 'tr.activation',
		bind_key_events_to_links: false,
		focus_links_on_select: true,
		select_event: 'click',
		activate_event: 'dblclick',
		activation_element_activate_event: false,
		scroll_overlap: 20,
		cookie_name: 'last_selected_row_index',
		focus_tables: true,
		focused_table_class: 'focused',
		jump_between_tables: true,
		disabled: false,
		on_activate: null,
		on_select: null
	});
</script>
<link rel="stylesheet" type="text/css" media="screen" href="css/styles.css" />
<style type="text/css">
    table {border-collapse: collapse;}
    th, td {margin: 0; padding: 0.25em 0.5em;}
    /* This "tr.selected" style is the only rule you need for yourself. It highlights the selected table row. */
    tr.selected {background-color: red; color: white;}
    /* Not necessary but makes the links in selected rows white to... */
    tr.selected a {color: white;}
</style>
</head>
<body>

<h1>Navigate through table rows using the keyboard</h1>

<p class="subtitle">
	Version 0.6.1.
	Licensed under the <a href="#license">MIT-License</a>.
</p>

<ul id="nav">
	<li><a href="index.html">Back to information page</a></li>
</ul>


<h2 id="about">About</h2>

<p>
	This page is demonstration page of a technique using keyboard keys to navigate through table rows.
	See the <a href="index.html">information page</a> for a detailed description of the concept and implementation.<br />
	Idea by Florian Seefried &lt;<a href="&#109;&#97;&#x69;&#108;&#x74;&#x6f;&#x3a;&#x66;&#108;&#111;&#114;&#x69;&#x61;&#110;&#46;&#x73;&#101;&#x65;&#102;&#114;&#x69;&#x65;&#100;&#x40;&#104;&#x65;&#x6c;&#105;&#111;&#110;&#x77;&#101;&#x62;&#x2e;&#x64;&#x65;">&#102;&#x6c;&#x6f;&#x72;&#105;&#97;&#x6e;&#x2e;&#x73;&#x65;&#101;&#102;&#114;&#105;&#x65;&#100;&#x40;&#104;&#x65;&#108;&#x69;&#x6f;&#110;&#119;&#x65;&#x62;&#x2e;&#x64;&#x65;</a>&gt;,<br />
	implemented by Stephan Soller &lt;<a href="&#109;&#x61;&#105;&#x6c;&#116;&#x6f;&#58;&#x73;&#x74;&#x65;&#x70;&#x68;&#x61;&#110;&#46;&#x73;&#x6f;&#108;&#x6c;&#101;&#114;&#64;&#x61;&#x64;&#x64;&#x63;&#111;&#109;&#46;&#100;&#x65;">&#115;&#x74;&#101;&#x70;&#104;&#x61;&#x6e;&#x2e;&#115;&#111;&#x6c;&#x6c;&#x65;&#x72;&#64;&#x61;&#100;&#x64;&#x63;&#x6f;&#x6d;&#x2e;&#100;&#101;</a>&gt; and<br />
	extended by Roberto Rambaldi.
</p>


<h2 id="usage">Usage</h2>

<p class="legend">
	<kbd>↓</kbd> or <kbd>→</kbd> will select the next row.<br />
	<kbd>↑</kbd> or <kbd>←</kbd> will select the previous row.<br />
	<kbd>return</kbd> will activate the currently selected row.<br />
	<tt>click</tt> on a row to select it.<br />
	<tt>double click</tt> on a row to activate it.
</p>

<p>
	Have fun!
</p>

<h2 id="example">Example</h2>

<table class="navigateable">
	<thead>
		<tr>
			<th>Cell 01</th>
			<th>Cell 02</th>
			<th>Cell 03</th>
		</tr>
	</thead>
	<tbody>
		<tr class="activation">
			<td>Data 01-01</td>
			<td>Data 01-02</td>
			<td></td>
		</tr>
		<tr class="activation">
			<td>Data 02-01</td>
			<td>Data 02-02</td>
			<td></td>
		</tr>
		<tr class="activation">
			<td>Data 03-01</td>
			<td>Data 03-02</td>
			<td></td>
		</tr>
		<tr class="activation">
			<td>Data 04-01</td>
			<td>Data 04-02</td>
			<td></td>
		</tr>
		<tr class="activation">
			<td>Data 05-01</td>
			<td>Data 05-02</td>
			<td></td>
		</tr>
		<tr class="activation">
			<td>Data 06-01</td>
			<td>Data 06-02</td>
			<td></td>
		</tr>
		<tr class="activation">
			<td>Data 07-01</td>
			<td>Data 07-02</td>
			<td></td>
		</tr>
		<tr class="activation">
			<td>Data 08-01</td>
			<td>Data 08-02</td>
			<td></td>
		</tr>
	</tbody>
</table>


<h2 id="license">The MIT License</h2>

<p>
	Copyright (c) 2006
	Stephan Soller &lt;<a href="&#109;&#x61;&#105;&#x6c;&#116;&#x6f;&#58;&#x73;&#x74;&#x65;&#x70;&#x68;&#x61;&#110;&#46;&#x73;&#x6f;&#108;&#x6c;&#101;&#114;&#64;&#x61;&#x64;&#x64;&#x63;&#111;&#109;&#46;&#100;&#x65;">&#115;&#x74;&#101;&#x70;&#104;&#x61;&#x6e;&#x2e;&#115;&#111;&#x6c;&#x6c;&#x65;&#x72;&#64;&#x61;&#100;&#x64;&#x63;&#x6f;&#x6d;&#x2e;&#100;&#101;</a>&gt;,
	Florian Seefried &lt;<a href="&#109;&#97;&#x69;&#108;&#x74;&#x6f;&#x3a;&#x66;&#108;&#111;&#114;&#x69;&#x61;&#110;&#46;&#x73;&#101;&#x65;&#102;&#114;&#x69;&#x65;&#100;&#x40;&#104;&#x65;&#x6c;&#105;&#111;&#110;&#x77;&#101;&#x62;&#x2e;&#x64;&#x65;">&#102;&#x6c;&#x6f;&#x72;&#105;&#97;&#x6e;&#x2e;&#x73;&#x65;&#101;&#102;&#114;&#105;&#x65;&#100;&#x40;&#104;&#x65;&#108;&#x69;&#x6f;&#110;&#119;&#x65;&#x62;&#x2e;&#x64;&#x65;</a>&gt;.
</p>

<p>
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
</p>

<p>
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
</p>

<p>
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
</p>

</body>
</html>
