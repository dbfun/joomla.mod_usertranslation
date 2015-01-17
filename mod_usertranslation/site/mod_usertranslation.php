<?php
// no direct access

// сброс переводов
// UPDATE `description` SET `translation` = null, `ip` = null, `checked` = null

defined('_JEXEC') or die('Restricted access');
global $mainframe;

// получаем параметры модуля
$dbtype = $params->get('dbtype', ''); // какую БД использовать - текущую (Джумлы) или указанную в настройках
$db_name = $params->get('db', ''); // название БД
$user = $params->get('user', ''); // имя пользователя БД
$password = $params->get('password', ''); // пароль пользователя
$table = $params->get('table', ''); // таблица перевода
$tbl_dest = $params->get('tbl_dest', ''); // колонка с тектом перевода
$tbl_source = $params->get('tbl_source', ''); // колонка с тектом перевода
$tbl_ip = $params->get('tbl_ip', ''); // колонка с тектом перевода
$tbl_primary_key = $params->get('tbl_primary_key', ''); // колонка с тектом перевода
$tbl_check = $params->get('tbl_check', ''); // колонка с тектом перевода
$tbl_user_id = $params->get('tbl_user_id', ''); // колонка с user_id
$ajax = JRequest::getVar('ajax') == 'ajax'; // AJAX-запрос?

if ($ajax) // если это был аякс-запрос, сохраняем данные
	{
	$primary_key = addslashes(JRequest::getVar('primary_key'));
	$translation = addslashes(JRequest::getVar('translation'));
	$ip = addslashes($_SERVER['REMOTE_ADDR']);
	$user_id = JFactory::getUser()->id;
	$update_query = 'UPDATE '.$table.' SET `'.$tbl_user_id.'` = '.(empty($user_id) ? 'null' : $user_id).', `'.$tbl_dest.'` = \''.$translation.'\', `'.$tbl_ip.'` = \''.$ip.'\' WHERE `'.$tbl_primary_key.'` = \''.$primary_key.'\' AND `'.$tbl_dest.'` IS NULL';
	}

// запрос на извлечение
$query = 'SELECT * FROM `'.$table.'` '
	.'WHERE `'.$tbl_check.'`=0 AND `'.$tbl_dest.'` IS NULL ORDER BY RAND() LIMIT 1';
	
// подключаемся к БД
if ($dbtype==0) // к указанной в настройках
	{
	$db = new mysqli('localhost', $user, $password, $db_name);
	$db->query('SET NAMES \'utf8\'');
	if ($ajax) $db->query($update_query);
	$result = $db->query($query);
	$row = $result->fetch_assoc();
	}
else
	{ // к той, с которой работает Joomla
	$db = &JFactory::getDBO();
	if ($ajax) 
		{
		$db->setQuery($update_query);
		$db->query();
		}
	$db->setQuery($query);
	$row = $db->loadAssoc();
	}
	
function make_view($primary_key, $translation)
	{
	$result  =  '<script src="//translate.google.com/translate_a/element.js?cb=googleSectionalElementInit&ug=section&hl=ru"></script>'
				.'<div id="ajax_translate">'
				.'<div class="usertranslation_source_text">'.$translation.'</div>'
				.'<div class="goog-trans-section" lang="en">'
				.'	<div class="goog-trans-control"></div>'
				.'	<p contenteditable="" id="p_translation" onfocus="enable_submit()" onclick="enable_submit()">'.$translation.'</p>'
				.'</div>'
				.'<input id="save_translation" type="submit" value="Сохранить перевод" disabled="disabled" onclick="ajax_save_translate(\''.$primary_key
				.'\', strip_tags(document.getElementById(\'p_translation\').innerHTML))" />'
				.'</div>';
	return $result;
	}
	
if (JRequest::getVar('ajax') == 'ajax') // если это был аякс-запрос, отдаем новые данные
	{
	$th = array('Спасибо за перевод!', 'Спасибо, с Вашей помощью мы улучшим качество перевода!', 'Спасибо! Если не устали, переведете еще?',
	'Огромное спасибо за помощь в переводе!', 'Спасибо за интерес и содействие переводом!', 'Спасибо, Вы очень любезны!', 'Спасибо, Вы нам очень помогаете!');
	$mainframe->close(json_encode(array('nt'=>make_view($row[$tbl_primary_key], $row[$tbl_source]), 'th'=>$th[rand(0,count($th)-1)])));
	}

require(JModuleHelper::getLayoutPath('mod_usertranslation'));
?>