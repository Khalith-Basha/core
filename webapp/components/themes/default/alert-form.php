<?php
$alertForm = $classLoader->getClassInstance( 'Form_Alert' );
?>
<div class="alert_form">
    <h3>
        <strong><?php _e('Subscribe to this search', 'modern'); ?></strong>
    </h3>
    <form action="<?php echo $urlFactory->getBaseUrl(true); ?>" method="post" name="sub_alert" id="sub_alert">
        <fieldset>
            <?php $alertForm->page_hidden(); ?>
            <?php $alertForm->alert_hidden(); ?>
            <?php if (osc_is_web_user_logged_in()) { ?>
                <?php $alertForm->user_id_hidden(); ?>
                <?php $alertForm->email_hidden(); ?>
            <?php } else { ?>
                <?php $alertForm->user_id_hidden(); ?>
                <?php $alertForm->email_text(); ?>
            <?php } ?>
            <button type="submit" class="sub_button" ><?php _e('Subscribe now', 'modern'); ?>!</button>
        </fieldset>
    </form>
</div>
