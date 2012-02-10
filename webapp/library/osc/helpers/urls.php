<?php

/**
 *  Create automatically the contact url
 *
 * @return string
 */
function osc_contact_url() 
{
	if (osc_rewrite_enabled()) 
	{
		$path = osc_base_url() . '/contact';
	}
	else
	{
		$path = osc_base_url(true) . '?page=contact';
	}
	return $path;
}
function osc_item_url()
{
	$classLoader = ClassLoader::getInstance();
	$items = $classLoader->getClassInstance( 'View_Html' )->_get('items');
	if( count( $items ) > 0 )
	{
		$itemUrl = $classLoader->getClassInstance( 'Url_Item' )->getDetailsUrl( $items[0] );
		return $itemUrl;
	}

	return null;
}


/**
 * Create automatically the url to post an item in a category
 *
 * @return string
 */
function osc_item_post_url_in_category() 
{
	if (osc_category_id() > 0) 
	{
		if (osc_rewrite_enabled()) 
		{
			$path = osc_base_url() . '/item/new/' . osc_category_id();
		}
		else
		{
			$path = sprintf(osc_base_url(true) . '?page=item&action=add&catId=%d', osc_category_id());
		}
	}
	else
	{
		$path = osc_item_post_url();
	}
	return $path;
}
/**
 *  Create automatically the url to post an item
 *
 * @return string
 */
function osc_item_post_url() 
{
	if (osc_rewrite_enabled()) 
	{
		$path = osc_base_url() . '/item/new';
	}
	else
	{
		$path = sprintf(osc_base_url(true) . '?page=item&action=add');
	}
	return $path;
}
/**
 * Create automatically the url of a category
 *
 * @param string $pattern
 * @return string the url
 */
function osc_search_category_url($pattern = '') 
{
	$category = osc_category();
	$path = '';
	if (osc_rewrite_enabled()) 
	{
		if ($category != '') 
		{
			$categoryModel = ClassLoader::getInstance()->getClassInstance( 'Model_Category' );
			$category = $categoryModel->hierarchy($category['pk_i_id']);
			$sanitized_category = "";
			for ($i = count($category); $i > 0; $i--) 
			{
				$sanitized_category.= $category[$i - 1]['s_slug'] . '/';
			}
			$path = osc_base_url() . '/' . $sanitized_category;
		}
		if ($pattern != '') 
		{
			if ($path == '') 
			{
				$path = osc_base_url() . '/search/' . $pattern;
			}
			else
			{
				$path.= '/search/' . $pattern;
			}
		}
	}
	else
	{
		$path = sprintf(osc_base_url(true) . '?page=search&sCategory=%d', $category['pk_i_id']);
	}
	return $path;
}
/**
 * Create automatically the url of the users' dashboard
 *
 * @return string
 */
function osc_user_dashboard_url() 
{
	if (osc_rewrite_enabled()) 
	{
		$path = osc_base_url() . '/user/dashboard';
	}
	else
	{
		$path = osc_base_url(true) . '?page=user&action=dashboard';
	}
	return $path;
}
/**
 * Create automatically the logout url
 *
 * @return string
 */
function osc_user_logout_url() 
{
	if (osc_rewrite_enabled()) 
	{
		$path = osc_base_url() . '/user/logout';
	}
	else
	{
		$path = osc_base_url(true) . '?page=user&action=logout';
	}
	return $path;
}
/**
 * Create automatically the login url
 *
 * @return string
 */
function osc_user_login_url() 
{
	if (osc_rewrite_enabled()) 
	{
		$path = osc_base_url() . '/user/login';
	}
	else
	{
		$path = osc_base_url(true) . '?page=user&action=login';
	}
	return $path;
}
/**
 * Create automatically the url to register an account
 *
 * @return string
 */
function osc_register_account_url() 
{
	if (osc_rewrite_enabled()) 
	{
		$path = osc_base_url() . '/user/register';
	}
	else
	{
		$path = osc_base_url(true) . '?page=user&action=register';
	}
	return $path;
}
/**
 * Create automatically the url to activate an account
 *
 * @param int $id
 * @param string $code
 * @return string
 */
function osc_user_activate_url($id, $code) 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/user/activate/' . $id . '/' . $code;
	}
	else
	{
		return osc_base_url(true) . '?page=user&action=validate&id=' . $id . '&code=' . $code;
	}
}
/**
 * Create automatically the url of the item's comments page
 *
 * @param mixed $page
 * @param string $locale
 * @return string
 */
function osc_item_comments_url($page = 'all', $locale = '') 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_item_url($locale) . "?comments-page=" . $page;
	}
	else
	{
		return osc_item_url($locale) . "&comments-page=" . $page;
	}
}
/**
 * Create automatically the url of the item's comments page
 *
 * @param string $locale
 * @return string
 */
function osc_comment_url($locale = '') 
{
	return osc_item_url($locale) . "?comment=" . osc_comment_id();
}
/**
 * Create automatically the url of the item details page
 *
 * @param string $locale
 * @return string
 */
function osc_premium_url($locale = '') 
{
	if (osc_rewrite_enabled()) 
	{
		$sanitized_title = osc_sanitizeString(osc_premium_title());
		$sanitized_category = '';
		$cat = ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->hierarchy(osc_premium_category_id());
		for ($i = (count($cat)); $i > 0; $i--) 
		{
			$sanitized_category.= $cat[$i - 1]['s_slug'] . '/';
		}
		if ($locale != '') 
		{
			$path = osc_base_url() . sprintf('%s_%s%s_%d', $locale, $sanitized_category, $sanitized_title, osc_premium_id());
		}
		else
		{
			$path = osc_base_url() . sprintf('%s%s_%d', $sanitized_category, $sanitized_title, osc_premium_id());
		}
	}
	else
	{
		//$path = osc_base_url(true) . sprintf('?page=item&id=%d', osc_item_id()) ;
		$path = osc_item_url_ns(osc_premium_id(), $locale);
	}
	return $path;
}
/**
 * Create the no friendly url of the item using the id of the item
 *
 * @param int $id the primary key of the item
 * @param $locale
 * @return string
 */
function osc_item_url_ns($id, $locale = '') 
{
	$path = osc_base_url(true) . '?page=item&id=' . $id;
	if ($locale != '') 
	{
		$path.= "&lang=" . $locale;
	}
	return $path;
}

/**
 * Gets current user alerts' url
 *
 * @return string
 */
function osc_user_alerts_url() 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/user/alerts';
	}
	else
	{
		return osc_base_url(true) . '?page=user&action=alerts';
	}
}
/**
 * Gets current user alert unsubscribe url
 *
 * @param string $email
 * @param string $secret
 * @return string
 */
function osc_user_unsubscribe_alert_url($email = '', $secret = '') 
{
	if ($secret == '') 
	{
		$secret = osc_alert_secret();
	}
	if ($email == '') 
	{
		$email = osc_user_email();
	}
	return osc_base_url(true) . '?page=user&action=unsub_alert&email=' . urlencode($email) . '&secret=' . $secret;
}
/**
 * Gets user alert activate url
 *
 * @param string $secret
 * @param string $email
 * @return string
 */
function osc_user_activate_alert_url($secret, $email) 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/user/activate_alert/' . $secret . '/' . urlencode($email);
	}
	else
	{
		return osc_base_url(true) . '?page=user&action=activate_alert&email=' . urlencode($email) . '&secret=' . $secret;
	}
}
/**
 * Gets current user url
 *
 * @return string
 */
function osc_user_profile_url() 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/user/profile';
	}
	else
	{
		return osc_base_url(true) . '?page=user&action=profile';
	}
}
/**
 * Gets current user alert activate url
 *
 * @param int $page
 * @return string
 */
function osc_user_list_items_url($page = '') 
{
	if (osc_rewrite_enabled()) 
	{
		if ($page == '') 
		{
			return osc_base_url() . '/user/items';
		}
		else
		{
			return osc_base_url() . '/user/items?iPage=' . $page;
		}
	}
	else
	{
		if ($page == '') 
		{
			return osc_base_url(true) . '?page=user&action=items';
		}
		else
		{
			return osc_base_url(true) . '?page=user&action=items&iPage=' . $page;
		}
	}
}
/**
 * Gets url to change email
 *
 * @return string
 */
function osc_change_user_email_url() 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/user/change_email';
	}
	else
	{
		return osc_base_url(true) . '?page=user&action=change_email';
	}
}
/**
 * Gets confirmation url of change email
 *
 * @param int $userId
 * @param string $code
 * @return string
 */
function osc_change_user_email_confirm_url($userId, $code) 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/user/change_email_confirm/' . $userId . '/' . $code;
	}
	else
	{
		return osc_base_url(true) . '?page=user&action=change_email_confirm&userId=' . $userId . '&code=' . $code;
	}
}
/**
 * Gets url for changing password
 *
 * @return string
 */
function osc_change_user_password_url() 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/user/change_password';
	}
	else
	{
		return osc_base_url(true) . '?page=user&action=change_password';
	}
}
/**
 * Gets url for recovering password
 *
 * @return string
 */
function osc_recover_user_password_url() 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/user/recover';
	}
	else
	{
		return osc_base_url(true) . '?page=user&action=recover';
	}
}
/**
 * Gets url for confirm the forgot password process
 *
 * @param int $userId
 * @param string $code
 * @return string
 */
function osc_forgot_user_password_confirm_url($userId, $code) 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/user/forgot/' . $userId . '/' . $code;
	}
	else
	{
		return osc_base_url(true) . '?page=user&action=forgot&userId=' . $userId . '&code=' . $code;
	}
}
/**
 * Gets url for changing website language (for users)
 *
 * @param string $locale
 * @return string
 */
function osc_change_language_url($locale) 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/language/' . $locale;
	}
	else
	{
		return osc_base_url(true) . '?page=language&locale=' . $locale;
	}
}

/**
 * Gets url for editing an item
 *
 * @param string $secret
 * @param int $id
 * @return string
 */
function osc_item_edit_url($secret = '', $id = '') 
{
	if ($id == '') $id = osc_item_id();
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/item/edit/' . $id . '/' . $secret;
	}
	else
	{
		return osc_base_url(true) . '?page=item&action=item_edit&id=' . $id . ($secret != '' ? '&secret=' . $secret : '');
	}
}
/**
 * Gets url for delete an item
 *
 * @param string $secret
 * @param int $id
 * @return string
 */
function osc_item_delete_url($secret = '', $id = '') 
{
	if ($id == '') $id = osc_item_id();
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/item/delete/' . $id . '/' . $secret;
	}
	else
	{
		return osc_base_url(true) . '?page=item&action=item_delete&id=' . $id . ($secret != '' ? '&secret=' . $secret : '');
	}
}
/**
 * Gets url for activate an item
 *
 * @param string $secret
 * @param int $id
 * @return string
 */
function osc_item_activate_url($secret = '', $id = '') 
{
	if ($id == '') $id = osc_item_id();
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/item/activate/' . $id . '/' . $secret;
	}
	else
	{
		return osc_base_url(true) . '?page=item&action=activate&id=' . $id . ($secret != '' ? '&secret=' . $secret : '');
	}
}
/**
 * Gets url for deleting a resource of an item
 *
 * @param int $id of the resource
 * @param int $item
 * @param string $code
 * @param string $secret
 * @return string
 */
function osc_item_resource_delete_url($id, $item, $code, $secret = '') 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/item/resource/delete/' . $id . '/' . $item . '/' . $code . ($secret != '' ? '/' . $secret : '');
	}
	else
	{
		return osc_base_url(true) . '?page=item&action=deleteResource&id=' . $id . '&item=' . $item . '&code=' . $code . ($secret != '' ? '&secret=' . $secret : '');
	}
}
/**
 * Gets url of send a friend (current item)
 *
 * @return string
 */
function osc_item_send_friend_url() 
{
	if (osc_rewrite_enabled()) 
	{
		return osc_base_url() . '/item/send-friend/' . osc_item_id();
	}
	else
	{
		return osc_base_url(true) . "?page=item&action=send_friend&id=" . osc_item_id();
	}
}
/**
 * Gets the root url for your installation
 *
 * @param boolean $with_index true if index.php in the url is needed
 * @return string
 */
function osc_base_url( $withIndex = false ) 
{
	$generalConfig = ClassLoader::getInstance()->getClassInstance( 'Config' )->getConfig( 'general' );
	$path = $generalConfig['webUrl'];
	if( $withIndex )
		$path .= '/index.php';
	return $path;
}

