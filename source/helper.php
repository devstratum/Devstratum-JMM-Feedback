<?php
/*######################################################
Devstratum JMM Feedback
Simple feedback form module

Version: 1.0
License: MIT License
Author: Sergey Osipov
Copyright: (c) 2020 Sergey Osipov
Website: https://devstratum.ru
Email: info@devstratum.ru
Repo: https://github.com/devstratum/Devstratum-JMM-Feedback

     _                _             _
  __| | _____   _____| |_ _ __ __ _| |_ _   _ _ __ ___
 / _` |/ _ \ \ / / __| __| '__/ _` | __| | | | '_ ` _ \
| (_| |  __/\ V /\__ \ |_| | | (_| | |_| |_| | | | | | |
 \__,_|\___| \_/ |___/\__|_|  \__,_|\__|\__,_|_| |_| |_|

########################################################*/

defined('_JEXEC') or die ('Restricted access');

class ModDvstrFeedbackHelper
{
    public static function getAjax()
    {
        $form_error = '';
        $app = JFactory::getApplication();
        $config = JFactory::getConfig();
        $mailer = JFactory::getMailer();

        // Mod params
        $mod_id = $app->input->get('mod_id', '', 'int');
        $mod = JModuleHelper::getModuleById("$mod_id");
        $mod_params = json_decode($mod->params);

        // Prepare fields
        $fields_form = $app->input->get('fields', '', 'array');
        $fields_list = $mod_params->fields_list;
        $fields_array = [];

        foreach ($fields_form as $form_key => $form_value) {
            foreach ($fields_list as $list_key => $list_value) {
                if ($form_value['name'] == $list_key) {
                    $fields_array[$list_key] = [
                        'name' => $list_value->field_name,
                        'type' => $list_value->field_type,
                        'required' => $list_value->field_required,
                        'value' => trim(htmlspecialchars(stripslashes($form_value['value'])))
                    ];
                }
            }
        }

        // Create email message
        $message = '';

        if ($mod_params->mail_header) {
            $message .= $mod_params->mail_header;
        }

        foreach ($fields_array as $item) {
            if ($item['type'] == 'email' && $item['value']) {
                if (!preg_match('/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i', $item['value'])) {
                    $form_error .= '<div class="dvstr-alert dvstr-alert-warning">' . JText::_('MOD_DVSTR_FEEDBACK_ERROR_EMAIL') . '</div>';
                }
            }

            if (!$item['value'] && $item['required']) {
                $form_error .= '<div class="dvstr-alert dvstr-alert-warning">' . $item['name'] . ' :: ' . JText::_('MOD_DVSTR_FEEDBACK_ERROR_REQUIRED') . '</div>';
            }

            if ($item['value']) {
                $message .= '<p><b>' . $item['name'] . ': </b><br/>' . $item['value'] . '</p>'. "\n";
            }
        }

        if ($mod_params->mail_footer) {
            $message .= $mod_params->mail_footer;
        }

        // Send email
        if ($form_error) {
            $response = [
                'info' => $form_error
            ];
            return $response;

        } else {
            $mailer->CharSet = 'utf-8';
            $mailer->isHTML(true);
            $mailer->setFrom($config->get('mailfrom'), $config->get('fromname'));
            $mailer->Subject = $mod_params->form_title . ' - ' . $config->get('sitename');

            if ($mod_params->form_email_from) {
                $mailer->addAddress($mod_params->form_email_from);
            } else {
                $mailer->addAddress($config->get('mailfrom'));
            }

            if ($mod_params->form_email_copy) {
                $mailer->AddCC($mod_params->form_email_copy);
            }

            if ($mod_params->form_email_admin) {
                $mailer->AddBCC($config->get('mailfrom'));
            }

            $mailer->msgHTML($message);

            if ($mailer->send()) {
                $response = [
                    'info' => '<div class="dvstr-alert dvstr-alert-success">' . $mod_params->form_success . '</div>',
                    'clear' => 1
                ];
                return $response;

            } else {
                $response = [
                    'info' => '<div class="dvstr-alert dvstr-alert-danger">' . JText::_('MOD_DVSTR_FEEDBACK_ERROR_MAILER') . ' :: ' . $mailer->ErrorInfo . '</div>'
                ];
                return $response;
            }
        }
    }
}