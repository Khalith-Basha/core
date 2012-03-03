
INSERT INTO
	/*TABLE_PREFIX*/acl_role ( id, name, description )
VALUES
	( 1, 'Administrator', 'Full administration privileges' ),
	( 2, 'User', 'Regular user' );

INSERT INTO
	/*TABLE_PREFIX*/acl_permission ( id, object, privilege )
VALUES
	( 1, '*', '*' );

INSERT INTO
	/*TABLE_PREFIX*/t_currency ( pk_c_code, s_name, s_description, b_enabled )
VALUES
	('GBP', 'United Kingdom pound', 'Pound £', true),
	('USD', 'United States dollar', 'Dollar US$', true),
	('EUR', 'European Union euro', 'Euro €', true);

INSERT INTO
	/*TABLE_PREFIX*/t_preference
VALUES
	('osc', 'theme', 'default', 'STRING'),
	('osc', 'admin_language', 'en_US', 'STRING'),
	('osc', 'language', 'en_US', 'STRING'),
	('osc', 'pageDesc', '', 'STRING'),
	('osc', 'maxSizeKb', 2048, 'INTEGER'),
	('osc', 'allowedExt', 'png,gif,jpg', 'STRING'),
	('osc', 'dimThumbnail', '240x200', 'STRING'),
	('osc', 'dimPreview', '480x340', 'STRING'),
	('osc', 'dimNormal', '640x480', 'STRING'),
	('osc', 'keep_original_image', '1', 'BOOLEAN'),
	('osc', 'dateFormat', 'F j, Y', 'STRING'),
	('osc', 'timeFormat', 'g:i a', 'STRING'),
	('osc', 'timezone', 'Europe/Madrid', 'STRING'),
	('osc', 'weekStart', '0', 'STRING'),
	('osc', 'moderate_comments', '0', 'INTEGER'),
	('osc', 'moderate_items', '0', 'INTEGER'),
	('osc', 'reg_user_post', '1', 'BOOLEAN'),
	('osc', 'num_rss_items', '50', 'INTEGER'),
	('osc', 'active_plugins', '', 'STRING'),
	('osc', 'installed_plugins', '', 'STRING'),
	('osc', 'notify_new_item', '1', 'BOOLEAN'),
	('osc', 'notify_new_user', '1', 'BOOLEAN'),
	('osc', 'auto_cron', '1', 'BOOLEAN'),
	('osc', 'item_attachment', '0', 'BOOLEAN'),
	('osc', 'contact_attachment', '0', 'BOOLEAN'),
	('osc', 'notify_contact_item', '1', 'BOOLEAN'),
	('osc', 'notify_contact_friends', '1', 'BOOLEAN'),
	('osc', 'notify_new_comment', '1', 'BOOLEAN'),
	('osc', 'notify_new_comment_user', '0', 'BOOLEAN'),
	('osc', 'enabled_recaptcha_items', '0', 'BOOLEAN'),
	('osc', 'logged_user_item_validation', '1', 'BOOLEAN'),
	('osc', 'items_wait_time', '0', 'INTEGER'),
	('osc', 'enabled_user_validation', '1', 'BOOLEAN'),
	('osc', 'enabled_user_registration', '1', 'BOOLEAN'),
	('osc', 'enabled_users','1', 'BOOLEAN'),
	('osc', 'enabled_comments', '1', 'BOOLEAN'),
	('osc', 'mailserver_host', 'localhost', 'STRING'),
	('osc', 'mailserver_port', '', 'INTEGER'),
	('osc', 'mailserver_username', '', 'STRING'),
	('osc', 'mailserver_password', '', 'STRING'),
	('osc', 'mailserver_type', 'custom', 'STRING'),
	('osc', 'mailserver_auth', '', 'BOOLEAN'),
	('osc', 'mailserver_pop', '', 'BOOLEAN'),
	('osc', 'mailserver_ssl', '', 'STRING'),
	('osc', 'currency', 'USD','STRING'),
	('osc', 'rewriteEnabled', '0', 'BOOLEAN'),
	('osc', 'mod_rewrite_loaded', '0', 'BOOLEAN'),
	('osc', 'rewrite_rules', '', 'STRING'),
	('osc', 'enableField#f_price@items', '1', 'BOOLEAN'),
	('osc', 'enableField#images@items', '1', 'BOOLEAN'),
	('osc', 'numImages@items', '4', 'INTEGER'),
	('osc', 'maxLatestItems@home', '10', 'INTEGER'),
	('osc', 'defaultResultsPerPage@search', '10', 'INTEGER'),
	('osc', 'maxResultsPerPage@search', '50', 'INTEGER'),
	('osc', 'defaultShowAs@search', 'list', 'STRING'),
	('osc', 'defaultOrderField@search', 'pub_date', 'STRING'),
	('osc', 'defaultOrderType@search', 'desc', 'STRING'),
	('osc', 'admin_theme', 'default', 'STRING'),
	('osc', 'akismetKey', '', 'STRING'),
	('osc', 'recaptchaPrivKey', '', 'STRING'),
	('osc', 'recaptchaPubKey', '', 'STRING'),
	('osc', 'comments_per_page', '10', 'INTEGER'),
	('osc', 'save_latest_searches', '0', 'BOOLEAN'),
	('osc', 'purge_latest_searches', '1000', 'STRING'),
	('osc', 'selectable_parent_categories', '0', 'BOOLEAN'),
	('osc', 'reg_user_post_comments', '0', 'BOOLEAN'),
	('osc', 'reg_user_can_contact', '0', 'BOOLEAN'),
	('osc', 'watermark_text', '', 'STRING'),
	('osc', 'watermark_text_color', '', 'STRING'),
	('osc', 'watermark_place', 'centre', 'STRING'),
	('osc', 'watermark_image', '', 'STRING'),
	( 'osc', 'item_num_days_old', 1, 'STRING' );

