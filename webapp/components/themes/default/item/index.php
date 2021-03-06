<?php
$itemUrls = $classLoader->getClassInstance( 'Url_Item' );
$userUrls = $classLoader->getClassInstance( 'Url_User' );
$resourceUrls = $classLoader->getClassInstance( 'Url_Resource' );
$commentForm = $classLoader->getClassInstance( 'Form_Comment' );
$contactForm = $classLoader->getClassInstance( 'Form_Contact' );
echo $view->render( 'header' );
?>
<script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('fancybox/jquery.fancybox-1.3.4.js'); ?>"></script>
<link href="<?php echo osc_current_web_theme_js_url('fancybox/jquery.fancybox-1.3.4.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/static/scripts/item-details.js"></script>
        
        <?php if( osc_item_is_expired( $item ) ): ?>
        <meta name="robots" content="noindex, nofollow" />
        <meta name="googlebot" content="noindex, nofollow" />
        <?php else: ?>
        <meta name="robots" content="index, follow" />
        <meta name="googlebot" content="index, follow" />
        <?php endif; ?>
        <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js'); ?>"></script>
	    <div class="content item">
                <div id="item_head">
                    <div class="inner">
                        <h1><?php if( osc_price_enabled_at_items() ): ?><span class="price"><?php echo osc_item_formated_price( $item ); ?></span> <?php endif; ?><strong><?php echo osc_item_title( $item ); ?></strong></h1>
                        <p id="report">
                            <strong><?php _e('Mark as', 'modern'); ?></strong>
                            <span>
                                <a id="item_spam" href="<?php echo $itemUrls->create( 'mark-spam', $item['pk_i_id'] ); ?>" rel="nofollow"><?php _e('spam', 'modern'); ?></a>
                                <a id="item_bad_category" href="<?php echo $itemUrls->osc_item_link_bad_category( $item ); ?>" rel="nofollow"><?php _e('misclassified', 'modern'); ?></a>
                                <a id="item_repeated" href="<?php echo $itemUrls->osc_item_link_repeated( $item ); ?>" rel="nofollow"><?php _e('duplicated', 'modern'); ?></a>
                                <a id="item_expired" href="<?php echo $itemUrls->osc_item_link_expired( $item ); ?>" rel="nofollow"><?php _e('expired', 'modern'); ?></a>
                                <a id="item_offensive" href="<?php echo $itemUrls->osc_item_link_offensive( $item ); ?>" rel="nofollow"><?php _e('offensive', 'modern'); ?></a>
                            </span>
                        </p>
                    </div>
                </div>
                <div id="main">
                    <div id="type_dates">
                        <strong><?php echo osc_item_category( $item ); ?></strong>
                        <em class="publish"><?php if (osc_item_pub_date( $item ) != '') echo __('Published date', 'modern') . ': ' . osc_format_date( osc_item_pub_date( $item )); ?></em>
                        <em class="update"><?php if (osc_item_mod_date( $item ) != '') echo __('Modified date', 'modern') . ': ' . osc_format_date( osc_item_mod_date( $item )); ?></em>
                    </div>
                    <ul id="item_location">
                        <?php if (osc_item_country( $item ) != "") { ?><li><?php _e("Country", 'modern'); ?>: <strong><?php echo osc_item_country( $item ); ?></strong></li><?php } ?>
                        <?php if (osc_item_region( $item ) != "") { ?><li><?php _e("Region", 'modern'); ?>: <strong><?php echo osc_item_region( $item ); ?></strong></li><?php } ?>
                        <?php if (osc_item_city( $item ) != "") { ?><li><?php _e("City", 'modern'); ?>: <strong><?php echo osc_item_city( $item ); ?></strong></li><?php } ?>
                        <?php if (osc_item_city_area( $item ) != "") { ?><li><?php _e("City area", 'modern'); ?>: <strong><?php echo osc_item_city_area( $item ); ?></strong></li><?php } ?>
                        <?php if (osc_item_address( $item ) != "") { ?><li><?php _e("Address", 'modern'); ?>: <strong><?php echo osc_item_address( $item ); ?></strong></li><?php } ?>
                    </ul>
                    <div id="description">
                        <p><?php echo osc_item_description( $item ); ?></p>
			<div id="custom_fields">
				<?php if( 0 < count( $metafields ) ): ?>
                                <br/>
				<div class="meta_list">
					<?php foreach( $metafields as $metafield ): var_dump($metafield);?>
                                        <?php if (osc_item_meta_value( $item ) != '')  { ?>
                                            <div class="meta">
                                                <strong><?php echo osc_item_meta_name( $item ); ?>:</strong> <?php echo osc_item_meta_value( $item ); ?>
                                            </div>
                                        <?php } ?>
                                    <?php  endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php osc_run_hook( 'item_detail', $item ); ?>
                        <p class="contact_button">
                            <?php if (!osc_item_is_expired( $item )): ?>
                            <?php if (!((osc_logged_user_id() == osc_item_user_id( $item )) && osc_logged_user_id() != 0)) { ?>
                                <?php if (osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact())  { ?>
                                    <strong><a href="#contact"><?php _e('Contact seller', 'modern'); ?></a></strong>
                                <?php } ?>
                            <?php } ?>
                            <?php endif; ?>
                            <strong class="share"><a href="<?php echo $itemUrls->osc_item_send_friend_url( $item ); ?>" rel="nofollow"><?php _e('Share', 'modern'); ?></a></strong>
                        </p>
                        <?php osc_run_hook('location'); ?>
                    </div>
                    <div id="useful_info">
                        <h2><?php _e('Useful information', 'modern'); ?></h2>
                        <ul>
                            <li><?php _e('Avoid scams by acting locally or paying with PayPal', 'modern'); ?></li>
                            <li><?php _e('Never pay with Western Union, Moneygram or other anonymous payment services', 'modern'); ?></li>
                            <li><?php _e('Don\'t buy or sell outside of your country. Don\'t accept cashier cheques from outside your country', 'modern'); ?></li>
                            <li><?php _e('This site is never involved in any transaction, and does not handle payments, shipping, guarantee transactions, provide escrow services, or offer "buyer protection" or "seller certification"', 'modern'); ?></li>
                        </ul>
                    </div>
                    <?php if (osc_comments_enabled())  { ?>
                        <?php if (osc_reg_user_post_comments() && osc_is_web_user_logged_in() || !osc_reg_user_post_comments())  { ?>
                        <div id="comments">
                            <h2><?php _e('Comments', 'modern'); ?></h2>
                            <ul id="comment_error_list"></ul>
                            <?php if( 0 < count( $comments ) ): ?>
				<div class="comments_list">
					<?php foreach( $comments as $comment ): ?>
                                        <div class="comment">
                                            <h3><strong><?php echo osc_comment_title( $comment ); ?></strong> <em><?php _e("by", 'modern'); ?> <?php echo osc_comment_author_name( $comment ); ?>:</em></h3>
                                            <p><?php echo osc_comment_body( $comment ); ?> </p>
                                            <?php if (osc_comment_user_id( $comment ) && (osc_comment_user_id( $comment ) == osc_logged_user_id()))  { ?>
                                            <p>
                                                <a rel="nofollow" href="<?php echo osc_delete_comment_url( $item, $comment ); ?>" title="<?php _e('Delete your comment', 'modern'); ?>"><?php _e('Delete', 'modern'); ?></a>
                                            </p>
                                            <?php } ?>
                                        </div>
                                    <?php endforeach; ?>
				    <div class="pagination">
					<?php foreach( $commentsPagination->getPages() as $page ): ?>
						<a class="<?php echo $page['selected'] ? 'searchPaginationSelected' : 'searchPaginationNonSelected'; ?>" href="<?php echo $page['url']; ?>"><?php echo $page['number']; ?></a>
					<?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <form action="<?php echo $urlFactory->getBaseUrl(true); ?>" method="post" name="comment_form" id="comment_form">
                                <fieldset>
                                    <h3><?php _e('Leave your comment (spam and offensive messages will be removed)', 'modern'); ?></h3>
                                    <input type="hidden" name="action" value="add_comment" />
                                    <input type="hidden" name="page" value="item" />
                                    <input type="hidden" name="id" value="<?php echo osc_item_id( $item ); ?>" />
                                    <?php if( osc_is_web_user_logged_in() ): ?>
                                        <input type="hidden" name="authorName" value="<?php echo osc_logged_user_name(); ?>" />
                                        <input type="hidden" name="authorEmail" value="<?php echo osc_logged_user_email(); ?>" />
                                    <?php else: ?>
                                        <label for="authorName"><?php _e('Your name', 'modern'); ?>:</label> <?php $commentForm->author_input_text(); ?><br />
                                        <label for="authorEmail"><?php _e('Your e-mail', 'modern'); ?>:</label> <?php $commentForm->email_input_text(); ?><br />
                                    <?php endif; ?>
                                    <label for="title"><?php _e('Title', 'modern'); ?>:</label><?php	$commentForm->title_input_text(); ?><br />
                                    <label for="body"><?php _e('Comment', 'modern'); ?>:</label><?php $commentForm->body_input_textarea(); ?><br />
                                    <button type="submit"><?php _e('Send', 'modern'); ?></button>
                                </fieldset>
                            </form>
                        </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div id="sidebar">
                    <?php if (osc_images_enabled_at_items())  { ?>
                        <?php if( 0 < count( $resources ) )  { ?>
			<div id="photos">
				<?php foreach( $resources as $resource ): ?>
                            <a href="<?php echo $resourceUrls->osc_resource_url( $resource ); ?>" rel="image_group">
                                <?php if ( !isset( $firstResource ) )  { $firstResource = false;  ?>
                                    <img src="<?php echo $resourceUrls->osc_resource_url( $resource ); ?>" width="315" alt="" title=""/>
                                <?php } else { ?>
                                    <img src="<?php echo $resourceUrls->osc_resource_thumbnail_url( $resource ); ?>" width="75" alt="" title=""/>
                                <?php } ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php } ?>
                    <?php } ?>
                    <div id="contact">
                        <h2><?php _e("Contact publisher", 'modern'); ?></h2>
                        <?php if (osc_item_is_expired( $item )) { ?>
                            <p>
                                <?php _e('The item is expired. You cannot contact the publisher.', 'modern'); ?>
                            </p>
                        <?php } else if ((osc_logged_user_id() == osc_item_user_id( $item )) && osc_logged_user_id() != 0)  { ?>
                            <p>
                                <?php _e("It's your own item, you cannot contact the publisher.", 'modern'); ?>
                            </p>
                        <?php } else if (osc_reg_user_can_contact() && !osc_is_web_user_logged_in())  { ?>
                            <p>
                                <?php _e("You must login or register a new free account in order to contact the advertiser", 'modern'); ?>
                            </p>
                            <p class="contact_button">
                                <strong><a href="<?php echo $userUrls->osc_user_login_url(); ?>"><?php _e('Login', 'modern'); ?></a></strong>
                                <strong><a href="<?php echo $userUrls->osc_register_account_url(); ?>"><?php _e('Register for a free account', 'modern'); ?></a></strong>
                            </p>
                        <?php } else { ?>
                            <?php if (osc_item_user_id( $item ) != null)  { ?>
                                <p class="name"><?php _e('Name', 'modern') ?>: <a href="<?php echo $userUrls->getPublicProfileUrl( $user ); ?>" ><?php echo osc_item_contact_name( $item ); ?></a></p>
                            <?php } else { ?>
                                <p class="name"><?php _e('Name', 'modern') ?>: <?php echo osc_item_contact_name( $item ); ?></p>
                            <?php } ?>
                            <?php if (osc_item_show_email( $item ) ) { ?>
                                <p class="email"><?php _e('E-mail', 'modern'); ?>: <?php echo osc_item_contact_email( $item ); ?></p>
                            <?php } ?>
                            <?php if (osc_user_phone( $user ) != '') { ?>
                                <p class="phone"><?php _e("Tel", 'modern'); ?>.: <?php echo osc_user_phone( $user ); ?></p>
                            <?php } ?>
                            <ul id="error_list"></ul>
                            <form action="<?php echo $urlFactory->getBaseUrl(true); ?>" method="post" name="contact_form" id="contact_form">
                                <fieldset>
                                    <label for="yourName"><?php _e('Your name', 'modern'); ?>:</label> <?php $contactForm->your_name(); ?>
                                    <label for="yourEmail"><?php _e('Your e-mail address', 'modern'); ?>:</label> <?php $contactForm->your_email(); ?>
                                    <label for="phoneNumber"><?php _e('Phone number', 'modern'); ?> (<?php _e('optional', 'modern'); ?>):</label> <?php $contactForm->your_phone_number(); ?>
                                    <label for="message"><?php _e('Message', 'modern'); ?>:</label> <?php $contactForm->your_message(); ?>
                                    <input type="hidden" name="action" value="contact" />
                                    <input type="hidden" name="page" value="item" />
                                    <input type="hidden" name="id" value="<?php echo osc_item_id( $item ); ?>" />
                                    <?php if (osc_recaptcha_public_key())  { ?>
                                    <script type="text/javascript">
                                        var RecaptchaOptions = {
                                            theme : 'custom',
                                            custom_theme_widget: 'recaptcha_widget'
                                        };
                                    </script>
                                    <style type="text/css"> div#recaptcha_widget, div#recaptcha_image > img { width:280px; } </style>
                                    <div id="recaptcha_widget">
                                        <div id="recaptcha_image"><img /></div>
                                        <span class="recaptcha_only_if_image"><?php _e('Enter the words above', 'modern'); ?>:</span>
                                        <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
                                        <div><a href="javascript:Recaptcha.showhelp()"><?php _e('Help', 'modern'); ?></a></div>
                                    </div>
                                    <?php } ?>
                                    <?php osc_show_recaptcha(); ?>
                                    <button type="submit"><?php _e('Send', 'modern'); ?></button>
                                </fieldset>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

