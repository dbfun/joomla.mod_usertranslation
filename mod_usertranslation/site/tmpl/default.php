<?php // no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'usertranslation.css', 'modules/mod_usertranslation/'); // ���������� CSS
if ($params->get('enable_jquery') == 1)
	{
	if ($params->get('jquery_source') == 1) 
	JHTML::_('script', 'jquery-1.7.1.min.js', 'modules/mod_usertranslation/'); // ���������� JS � ������������ �������
		else JHTML::_('script', 'jquery.min.js', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/'); // ���������� JS ����� Google code
	}
JHTML::_('script', 'usertranslation.js', 'modules/mod_usertranslation/'); // ���������� JS

?>
<div class="usertranslation<?php echo $params->get( 'moduleclass_sfx' ) ?>"> 
<?php
echo make_view($row[$tbl_primary_key], $row[$tbl_source]);
//echo $query;

?>
<div id="s_response"></div>
</div>