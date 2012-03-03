<form id="target_form" name="target_form" action="#" method="POST">
<input type="hidden" name="step" value="4" />
    <h2 class="target">Information needed</h2>
    <div class="form-table">
        <h2 class="title">Admin user</h2>
        <table class="admin-user">
            <tbody>
                <tr>
                    <th><label>Username</label></th>
                    <td>
                        <input size="25" id="admin_user" name="s_name" type="text" value="admin"/>
                    </td>
                    <td><span id="admin-user-error" class="error" style="display:none;">Admin user is required</span></td>
                </tr>
                <tr>
                    <th><label>Password</label></th>
                    <td>
                        <input size="25" class="password_test" name="s_passwd" type="text" value=""/>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="admin-user">
            A password will be automatically generated for you if you leave this blank.
            <img src="<?php echo osc_get_absolute_url() ?>installer/data/images/question.png" class="question-skip vtip" title="You can modify username and password if you like, only need change the inputs value." alt=""/>
        </div>
        <h2 class="title">Contact information</h2>
        <table class="contact-info">
            <tbody>
                <tr>
                    <th><label for="webtitle">Web title</label></th>
                    <td><input type="text" id="webtitle" name="webtitle" size="25"/></td>
                    <td></td>
                </tr>
                <tr>
                    <th><label for="email">Contact e-mail</label></th>
                    <td><input type="text" id="email" name="email" size="25"/></td>
                    <td><span id="email-error" class="error" style="display:none;">Put your e-mail here</span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
    <p class="margin20">
        <input type="submit" class="button" value="Next" />
    </p>
    <div class="clear"></div>
<input type="hidden" id="skip-location-h" name="skip-location-h" value="1"/>
<input id="skip-location" name="skip-location" type="hidden" value="1" />
</form>
<div id="lightbox" style="display:none;">
    <div class="center">
        <img src="<?php echo osc_get_absolute_url(); ?>installer/data/images/loading.gif" alt="" title=""/>
    </div>
</div>

