<?php
 
// Set flag that this is a parent file
define( '_JEXEC', 1 );
define('JPATH_BASE', preg_replace('|\Smodules\Smod_.*?\Sajax.php|i', '', __FILE__));
define( 'DS', DIRECTORY_SEPARATOR );
 
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
 
$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
 
JPluginHelper::importPlugin('system');
jimport( 'joomla.application.module.helper' );
preg_match('|\Smodules\Smod_(.*?)\Sajax.php|si', __FILE__, $buff);
$module = JModuleHelper::getModule( $buff[1], '' );
// Загрузили фреймворк

header("Content-type: text/plain; charset=utf-8", true, 200);
header("Cache-Control: no-store, no-cache, must-revalidate");

$result = JModuleHelper::renderModule( $module, array('style' => 'none') );
$mainframe->close($result);
?>