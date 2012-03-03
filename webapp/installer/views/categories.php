<?php if( !empty( $error ) ): ?>
    <h2 class="target">Error</h2>
    <p class="bottom space-left-10">
        <?php echo $error; ?>
    </p>
<?php endif; ?>
<form id="category_form" action="index.php?step=5" method="POST">
    <input type="hidden" name="password" value="<?php
echo $password; ?>"/>
    <h2 class="target">Categories</h2>
    <div class="form-table">
        <?php
if (Params::getParam('error_location') == 1) 
{ ?>
        <script type="text/javascript">
            setTimeout (function(){
                $('.error-location').fadeOut('slow');
            }, 2500);
        </script>
        <div class="error-location">
            The location selected could not been installed
        </div>
        <?php
} ?>
        <div class="select-categories">
            &nbsp;
            <div class="right">
                <a href="#" onclick="check_all('category_form', true); return false;">Check all</a>
                ·
                <a href="#" onclick="check_all('category_form', false); return false;">Uncheck all</a>
            </div>
            <div class="left">
                <h3>Select your classified categories <span style="font-size:11px;">or</span> <a href="index.php?step=5">Skip</a><img src="<?php
echo osc_get_absolute_url() ?>installer/data/images/question.png" class="question-skip vtip" title="You can add/remove categories after the installation, using the admin dashboard." alt=""/></h3>
            </div>
        </div>
        <table class="list-categories">
            <tr>
                <?php
for ($j = 0; $j < $numCols; $j++) 
{ ?>
                        <td>
                            <?php
	for ($i = $catsPerCol * $j; $i < $catsPerCol * ($j + 1); $i++) 
	{ ?>
                            <?php
		if (isset($categories[$i]) && is_array($categories[$i])) 
		{ ?>
                            <div class="cat-title">
                                <label for="category-<?php
			echo $categories[$i]['pk_i_id'] ?>">
                                    <input id="category-<?php
			echo $categories[$i]['pk_i_id'] ?>" class="left" type="checkbox" name="categories[]" value="<?php
			echo $categories[$i]['pk_i_id'] ?>" onclick="javascript:check_cat('<?php
			echo $categories[$i]['pk_i_id'] ?>', this.checked);"/>
                                    <span><?php
			echo $categories[$i]['s_name'] ?></span>
                                </label>
                            </div>
                            <div id="cat<?php
			echo $categories[$i]['pk_i_id']; ?>" class="sub-cat-title">
                                <?php
			foreach ($categories[$i]['categories'] as $sc) 
			{ ?>
                                <div id="category" class="space">
                                    <label for="category-<?php
				echo $sc['pk_i_id'] ?>" class="space">
                                        <input id="category-<?php
				echo $sc['pk_i_id'] ?>" type="checkbox" name="categories[]" value="<?php
				echo $sc['pk_i_id'] ?>" onclick="javascript:check('category-<?php
				echo $categories[$i]['pk_i_id'] ?>')"/>
                                        <?php
				echo $sc['s_name']; ?>
                                    </label>
                                </div>
                                <?php
			} ?>
                            </div>
                            <?php
		} ?>
                        <?php
	} ?>
                        </td>
                <?php
} ?>
            </tr>
        </table>
    </div>
    <div class="clear"></div>
    <p class="margin20">
        <input type="submit" class="button" name="submit" value="Next"/>
    </p>
    <div class="clear"></div>
</form>

