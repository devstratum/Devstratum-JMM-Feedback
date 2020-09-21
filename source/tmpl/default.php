<?php
/*######################################################
Devstratum JMM Feedback
Simple feedback form module

Version: 1.0
License: MIT License
Author: Sergey Osipov
Copyright: © 2020 Sergey Osipov
Website: https://devstratum.ru
Email: info@devstartum.ru
Repo: https://github.com/devstratum/Devstratum-JMM-Feedback

     _                _             _
  __| | _____   _____| |_ _ __ __ _| |_ _   _ _ __ ___
 / _` |/ _ \ \ / / __| __| '__/ _` | __| | | | '_ ` _ \
| (_| |  __/\ V /\__ \ |_| | | (_| | |_| |_| | | | | | |
 \__,_|\___| \_/ |___/\__|_|  \__,_|\__|\__,_|_| |_| |_|

########################################################*/

defined('_JEXEC') or die ('Restricted access');

$fields_list = $params->get('fields_list');

$phone_mask = false;

?>

<div class="dvstr-feedback dvstr-feedback-<?php echo $params->get('form_theme'); ?>" id="dvstr_feedback_<?php echo $module->id; ?>" data-form-id="<?php echo $module->id; ?>">
    <div class="dvstr-feedback__form">
        <form>
            <?php if ($params->get('form_title')): ?>
                <div class="dvstr-feedback__title">
                    <span><?php echo $params->get('form_title'); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($params->get('form_header')): ?>
                <div class="dvstr-feedback__header">
                    <?php echo $params->get('form_header'); ?>
                </div>
            <?php endif; ?>

            <div class="dvstr-feedback__fields">
                <?php $ii = 0; foreach ($fields_list as $key => $item): ?>
                    <div class="dvstr-feedback__field <?php if ($item->field_required && $item->field_type != 'textarea') echo 'required'; ?>">

                        <?php
                        // check placeholder
                        $placeholder = '';
                        if ($params->get('form_placeholder')) {
                            $placeholder = 'placeholder="' . $item->field_name . '"';
                        }

                        // check required
                        $required = '';
                        if ($item->field_required) {
                            $required = ' required';
                        }
                        ?>

                        <?php if ($params->get('form_label')): ?>
                            <div class="dvstr-feedback__label"><?php echo $item->field_name; ?></div>
                        <?php endif; ?>

                        <?php
                            switch ($item->field_type) {
                                case 'text':
                                    echo '<input type="text" name="' . $key . '" class="dvstr-field dvstr-input-text' . $required . '" ' . $placeholder . ' />';
                                    break;
                                case 'phone':
                                    echo '<input type="text" name="' . $key . '" class="dvstr-field dvstr-input-phone' . $required . '" ' . $placeholder . ' />';
                                    $phone_mask = true;
                                    break;
                                case 'email':
                                    echo '<input type="text" name="' . $key . '" class="dvstr-field dvstr-input-email' . $required . '" ' . $placeholder . ' />';
                                    break;
                                case 'textarea':
                                    echo '<textarea name="' . $key . '" class="dvstr-field dvstr-textarea' . $required . '" ' . $placeholder . ' ></textarea>';
                                    break;
                            }
                        ?>
                    </div>
                <?php $ii++; endforeach; ?>
            </div>

            <?php if ($params->get('form_footer')): ?>
                <div class="dvstr-feedback__footer">
                    <?php echo $params->get('form_footer'); ?>
                </div>
            <?php endif; ?>

            <div class="dvstr-feedback__system">
                <div class="dvstr-feedback__alert"></div>
            </div>

            <div class="dvstr-feedback__submit">
                <div class="submit-button" data-form-id="<?php echo $module->id; ?>">
                    <span><?php echo $params->get('form_submit'); ?></span>
                    <div class="dvstr-feedback__progress">
                        <div class="dvstr-progress-bar">
                            <div class="dvstr-progress-bar__bubbles">
                                <div class="dvstr-progress-bar__bubble">
                                    <div class="dvstr-progress-bar__circle"></div>
                                </div>
                                <div class="dvstr-progress-bar__bubble">
                                    <div class="dvstr-progress-bar__circle"></div>
                                </div>
                                <div class="dvstr-progress-bar__bubble">
                                    <div class="dvstr-progress-bar__circle"></div>
                                </div>
                                <div class="dvstr-progress-bar__bubble">
                                    <div class="dvstr-progress-bar__circle"></div>
                                </div>
                                <div class="dvstr-progress-bar__bubble">
                                    <div class="dvstr-progress-bar__circle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($params->get('form_privacy')): ?>
                <div class="dvstr-feedback__privacy">
                    <div class="privacy-checkbox checked" data-form-id="<?php echo $module->id; ?>"></div>
                    <?php if ($params->get('form_privacy_url')): ?>
                        <a class="privacy-link" href="<?php echo $params->get('form_privacy_url'); ?>" target="_blank"><?php echo $params->get('form_privacy_text'); ?></a>
                    <?php else: ?>
                        <div class="privacy-link"><?php echo $params->get('form_privacy_text'); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <input type="hidden" name="dvstr_feedback" value="<?php echo $module->id; ?>">
        </form>
        <?php if ($phone_mask): ?>
            <script>
                // Mask Phone
                jQuery(document).ready(function($) {
                    var feedback = $('#dvstr_feedback_<?php echo $module->id; ?>');
                    feedback.find('.dvstr-input-phone').mask('<?php echo $params->get('form_mask_phone'); ?>', {placeholder: '<?php echo $params->get('form_placeholder_phone'); ?>'});
                });
            </script>
        <?php endif; ?>
    </div>
</div>