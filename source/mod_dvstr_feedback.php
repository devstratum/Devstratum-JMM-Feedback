<?php
/*######################################################
Devstratum JMM Feedback
Simple feedback form module

Version: 1.0
License: MIT License
Author: Sergey Osipov
Copyright: © 2020 Sergey Osipov
Website: https://devstratum.ru
Email: info@devstratum.ru
Repo: https://github.com/devstratum/Devstratum-JMM-Feedback

     _                _             _
  __| | _____   _____| |_ _ __ __ _| |_ _   _ _ __ ___
 / _` |/ _ \ \ / / __| __| '__/ _` | __| | | | '_ ` _ \
| (_| |  __/\ V /\__ \ |_| | | (_| | |_| |_| | | | | | |
 \__,_|\___| \_/ |___/\__|_|  \__,_|\__|\__,_|_| |_| |_|

########################################################*/

defined('_JEXEC') or die('Restricted access');

JLoader::register('ModDvstrFeedbackHelper', __DIR__ . '/helper.php');

$doc = JFactory::getDocument();
$doc->addStyleSheet('modules/mod_dvstr_feedback/assets/mod_dvstr_feedback.css');
$doc->addScript('modules/mod_dvstr_feedback/assets/maskedinput.min.js');
$doc->addScript('modules/mod_dvstr_feedback/assets/mod_dvstr_feedback.js');

require JModuleHelper::getLayoutPath('mod_dvstr_feedback', $params->get('layout', 'default'));

?>