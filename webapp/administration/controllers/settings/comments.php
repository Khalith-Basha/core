<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2011 OpenSourceClassifieds
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
class CAdminSettings extends AdminSecBaseModel
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->doView('settings/comments.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$iUpdated = 0;
		$enabledComments = Params::getParam('enabled_comments');
		$enabledComments = (($enabledComments != '') ? true : false);
		$moderateComments = Params::getParam('moderate_comments');
		$moderateComments = (($moderateComments != '') ? true : false);
		$numModerateComments = Params::getParam('num_moderate_comments');
		$commentsPerPage = Params::getParam('comments_per_page');
		$notifyNewComment = Params::getParam('notify_new_comment');
		$notifyNewComment = (($notifyNewComment != '') ? true : false);
		$notifyNewCommentUser = Params::getParam('notify_new_comment_user');
		$notifyNewCommentUser = (($notifyNewCommentUser != '') ? true : false);
		$regUserPostComments = Params::getParam('reg_user_post_comments');
		$regUserPostComments = (($regUserPostComments != '') ? true : false);
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $enabledComments), array('s_name' => 'enabled_comments'));
		if ($moderateComments) 
		{
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $numModerateComments), array('s_name' => 'moderate_comments'));
		}
		else
		{
			$iUpdated+= Preference::newInstance()->update(array('s_value' => '-1'), array('s_name' => 'moderate_comments'));
		}
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $notifyNewComment), array('s_name' => 'notify_new_comment'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $notifyNewCommentUser), array('s_name' => 'notify_new_comment_user'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $commentsPerPage), array('s_name' => 'comments_per_page'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $regUserPostComments), array('s_name' => 'reg_user_post_comments'));
		if ($iUpdated > 0) 
		{
			osc_add_flash_ok_message(_m('Comments\' settings have been updated'), 'admin');
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=comments');
	}

	function doView($file) 
	{
		osc_current_admin_theme_path($file);
		Session::newInstance()->_clearVariables();
	}
}
