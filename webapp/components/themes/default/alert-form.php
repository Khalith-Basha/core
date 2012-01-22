<?php
$alertForm = ClassLoader::getInstance()->getClassInstance( 'Form_Alert' );
?>
<script type="text/javascript">
$(document).ready(function(){
    $(".sub_button").click(function(){
        $.post('<?php
echo osc_base_url(true); ?>', {email:$("#alert_email").val(), userid:$("#alert_userId").val(), alert:$("#alert").val(), page:"ajax", action:"alerts"}, 
            function(data){
                if(data==1) { alert('<?php
_e('You have sucessfully subscribed to the alert', 'modern'); ?>'); }
                else if(data==-1) { alert('<?php
_e('Invalid email address', 'modern'); ?>'); }
                else { alert('<?php
_e('There was a problem with the alert', 'modern'); ?>');
                };
        });
        return false;
    });

    var sQuery = '<?php
echo $alertForm->default_email_text(); ?>' ;

    if($('input[name=alert_email]').val() == sQuery) {
        $('input[name=alert_email]').css('color', 'gray');
    }
    $('input[name=alert_email]').click(function(){
        if($('input[name=alert_email]').val() == sQuery) {
            $('input[name=alert_email]').val('');
            $('input[name=alert_email]').css('color', '');
        }
    });
    $('input[name=alert_email]').blur(function(){
        if($('input[name=alert_email]').val() == '') {
            $('input[name=alert_email]').val(sQuery);
            $('input[name=alert_email]').css('color', 'gray');
        }
    });
    $('input[name=alert_email]').keypress(function(){
        $('input[name=alert_email]').css('background','');
    })
});
</script>

<div class="alert_form">
    <h3>
        <strong><?php
_e('Subscribe to this search', 'modern'); ?></strong>
    </h3>
    <form action="<?php
echo osc_base_url(true); ?>" method="post" name="sub_alert" id="sub_alert">
        <fieldset>
            <?php
$alertForm->page_hidden(); ?>
            <?php
$alertForm->alert_hidden(); ?>

            <?php
if (osc_is_web_user_logged_in()) 
{ ?>
                <?php
	$alertForm->user_id_hidden(); ?>
                <?php
	$alertForm->email_hidden(); ?>

            <?php
}
else
{ ?>
                <?php
	$alertForm->user_id_hidden(); ?>
                <?php
	$alertForm->email_text(); ?>

            <?php
}; ?>
            <button type="submit" class="sub_button" ><?php
_e('Subscribe now', 'modern'); ?>!</button>
        </fieldset>
    </form>
</div>
