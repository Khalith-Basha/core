<form action="index.php" method="POST">
    <input type="hidden" name="step" value="3" />
    <h2 class="target">Database information</h2>
    <div class="form-table">
        <table>
            <tbody>
                <tr>
                    <th align="left"><label for="dbhost">Host</label></th>
                    <td><input type="text" id="dbhost" name="dbhost" value="localhost" size="25" /></td>
                    <td class="small">Server name or IP where the database engine resides</td>
                </tr>
                <tr>
                    <th align="left"><label for="dbname">Database name</label></th>
                    <td><input type="text" id="dbname" name="dbname" value="osc_db" size="25" /></td>
                    <td class="small">The name of the database you want to run OpenSourceClassifieds in</td>
                </tr>
                <tr>
                    <th align="left"><label for="username">User Name</label></th>
                    <td><input type="text" id="username" name="username" size="25" /></td>
                    <td class="small">Your MySQL username</td>
                </tr>
                <tr>
                    <th align="left"><label for="password">Password</label></th>
                    <td><input type="password" id="password" name="password" value="" size="25" /></td>
                    <td class="small">Your MySQL password</td>
                </tr>
                <tr>
                    <th align="left"><label for="tableprefix">Table prefix</label></th>
		    <td><input type="text" id="tableprefix" name="tableprefix" value="<?php
echo DEFAULT_TABLE_PREFIX; ?>" size="25" /></td>
                    <td class="small">If you want to run multiple OpenSourceClassifieds installations in a single database, change this</td>
                </tr>
            </tbody>
        </table>
        <div id="advanced_install" class="shrink">
            <div class="text">
                <span>Advanced</span>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#advanced_install').click(function() {
                    $('#more-options').toggle();
                    if( $('#advanced_install').attr('class') == 'shrink' ) {
                        $('#advanced_install').removeClass('shrink');
                        $('#advanced_install').addClass('expanded');
                    } else {
                        $('#advanced_install').addClass('shrink');
                        $('#advanced_install').removeClass('expanded');
                    }
                });
            });
        </script>
        <div style="clear:both;"></div>
        <table id="more-options" style="display:none;">
            <tbody>
                <tr>
                    <th></th>
                    <td><input type="checkbox" id="createdb" name="createdb" onclick="db_admin();" value="1" /><label for="createdb">Create DB</label></td>
                    <td class="small">Check here if the database is not created and you want to create it now</td>
                </tr>
                <tr id="admin_username_row">
                    <th align="left"><label for="admin_username">DB admin username</label></th>
                    <td><input type="text" id="admin_username" name="admin_username" size="25" disabled/></td>
                    <td></td>
                </tr>
                <tr id="admin_password_row">
                    <th align="left"><label for="admin_password">DB admin password</label></th>
                    <td><input type="password" id="admin_password" name="admin_password" value="" size="25" disabled/></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
    <p class="margin20">
        <input type="submit" class="button" name="submit" value="Next"/>
    </p>
    <div class="clear"></div>
</form>

