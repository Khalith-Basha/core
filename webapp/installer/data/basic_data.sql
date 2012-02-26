INSERT INTO /*TABLE_PREFIX*/t_currency (pk_c_code, s_name, s_description, b_enabled) VALUES
    ('GBP', 'United Kingdom pound', 'Pound £', true),
    ('USD', 'United States dollar', 'Dollar US$', true),
    ('EUR', 'European Union euro', 'Euro €', true);

INSERT INTO /*TABLE_PREFIX*/t_preference VALUES
    ('osclass', 'version', 233, 'INTEGER')
    ,('osclass', 'theme', 'default', 'STRING')
    ,('osclass', 'admin_language', 'en_US', 'STRING')
    ,('osclass', 'language', 'en_US', 'STRING')
    ,('osclass', 'pageDesc', '', 'STRING')
    ,('osclass', 'maxSizeKb', 2048, 'INTEGER')
    ,('osclass', 'allowedExt', 'png,gif,jpg', 'STRING')
    ,('osclass', 'dimThumbnail', '240x200', 'STRING')
    ,('osclass', 'dimPreview', '480x340', 'STRING')
    ,('osclass', 'dimNormal', '640x480', 'STRING')
    ,('osclass', 'keep_original_image', '1', 'BOOLEAN')
    ,('osclass', 'dateFormat', 'F j, Y', 'STRING')
    ,('osclass', 'timeFormat', 'g:i a', 'STRING')
    ,('osclass', 'timezone', 'Europe/Madrid', 'STRING')
    ,('osclass', 'weekStart', '0', 'STRING')
    ,('osclass', 'moderate_comments', '0', 'INTEGER')
    ,('osclass', 'moderate_items', '0', 'INTEGER')
    ,('osclass', 'reg_user_post', '1', 'BOOLEAN')
    ,('osclass', 'num_rss_items', '50', 'INTEGER')
    ,('osclass', 'active_plugins', '', 'STRING')
    ,('osclass', 'installed_plugins', '', 'STRING')
    ,('osclass', 'notify_new_item', '1', 'BOOLEAN')
    ,('osclass', 'notify_new_user', '1', 'BOOLEAN')
    ,('osclass', 'auto_cron', '1', 'BOOLEAN')
    ,('osclass', 'item_attachment', '0', 'BOOLEAN')
    ,('osclass', 'contact_attachment', '0', 'BOOLEAN')
    ,('osclass', 'notify_contact_item', '1', 'BOOLEAN')
    ,('osclass', 'notify_contact_friends', '1', 'BOOLEAN')
    ,('osclass', 'notify_new_comment', '1', 'BOOLEAN')
    ,('osclass', 'notify_new_comment_user', '0', 'BOOLEAN')
    ,('osclass', 'enabled_recaptcha_items', '0', 'BOOLEAN')
    ,('osclass', 'logged_user_item_validation', '1', 'BOOLEAN')
    ,('osclass', 'items_wait_time', '0', 'INTEGER')
    ,('osclass', 'enabled_user_validation', '1', 'BOOLEAN')
    ,('osclass', 'enabled_user_registration', '1', 'BOOLEAN')
    ,('osclass', 'enabled_users','1', 'BOOLEAN')
    ,('osclass', 'enabled_comments', '1', 'BOOLEAN')
    ,('osclass', 'mailserver_host', 'localhost', 'STRING')
    ,('osclass', 'mailserver_port', '', 'INTEGER')
    ,('osclass', 'mailserver_username', '', 'STRING')
    ,('osclass', 'mailserver_password', '', 'STRING')
    ,('osclass', 'mailserver_type', 'custom', 'STRING')
    ,('osclass', 'mailserver_auth', '', 'BOOLEAN')
    ,('osclass', 'mailserver_pop', '', 'BOOLEAN')
    ,('osclass', 'mailserver_ssl', '', 'STRING')
    ,('osclass', 'currency', 'USD','STRING')
    ,('osclass', 'rewriteEnabled', '0', 'BOOLEAN')
    ,('osclass', 'mod_rewrite_loaded', '0', 'BOOLEAN')
    ,('osclass', 'rewrite_rules', '', 'STRING')
    ,('osclass', 'enableField#f_price@items', '1', 'BOOLEAN')
    ,('osclass', 'enableField#images@items', '1', 'BOOLEAN')
    ,('osclass', 'numImages@items', '4', 'INTEGER')
    ,('osclass', 'maxLatestItems@home', '10', 'INTEGER')
    ,('osclass', 'defaultResultsPerPage@search', '10', 'INTEGER')
    ,('osclass', 'maxResultsPerPage@search', '50', 'INTEGER')
    ,('osclass', 'defaultShowAs@search', 'list', 'STRING')
    ,('osclass', 'defaultOrderField@search', 'pub_date', 'STRING')
    ,('osclass', 'defaultOrderType@search', 'desc', 'STRING')
    ,('osclass', 'admin_theme', 'default', 'STRING')
    ,('osclass', 'akismetKey', '', 'STRING')
    ,('osclass', 'recaptchaPrivKey', '', 'STRING')
    ,('osclass', 'recaptchaPubKey', '', 'STRING')
    ,('osclass', 'comments_per_page', '10', 'INTEGER')
    ,('osclass', 'save_latest_searches', '0', 'BOOLEAN')
    ,('osclass', 'purge_latest_searches', '1000', 'STRING')
    ,('osclass', 'selectable_parent_categories', '0', 'BOOLEAN')
    ,('osclass', 'reg_user_post_comments', '0', 'BOOLEAN')
    ,('osclass', 'reg_user_can_contact', '0', 'BOOLEAN')
    ,('osclass', 'watermark_text', '', 'STRING')
    ,('osclass', 'watermark_text_color', '', 'STRING')
    ,('osclass', 'watermark_place', 'centre', 'STRING')
    ,('osclass', 'watermark_image', '', 'STRING'),
	( 'osclass', 'item_num_days_old', 1, 'STRING' );

