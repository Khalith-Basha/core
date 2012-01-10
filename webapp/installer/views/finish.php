<h2 class="target">Congratulations!</h2>
<p class="space-left-10">OpenSourceClassifieds has been installed. Were you expecting more steps? Sorry to disappoint.</p>
<p class="space-left-10">An e-mail with the password for administration has sent to: <?php echo $data['s_email'] ?></p>
<div style="clear:both;"></div>
<div class="form-table finish">
    <table>
        <tbody>
            <tr>
                <th><label>Username</label></th>
                <td>
                    <div class="s_name">
                        <span style="float:left;" ><?php echo $data['admin_user']; ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <th><label>Password</label></th>
                <td>
                    <div class="s_passwd">
                        <span style="float: left;"><?php echo $data['password']; ?></span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<p class="margin20">
    <a target="_blank" href="<?php echo get_absolute_url() ?>/administration/index.php" class="button">Finish and go to the administration panel</a>
</p>

