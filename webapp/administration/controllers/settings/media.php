<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2012 OpenSourceClassifieds
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
class CAdminSettings extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->doView('settings/media.php');
	}
	
	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$iUpdated = 0;
		$maxSizeKb = Params::getParam('maxSizeKb');
		$allowedExt = Params::getParam('allowedExt');
		$dimThumbnail = Params::getParam('dimThumbnail');
		$dimPreview = Params::getParam('dimPreview');
		$dimNormal = Params::getParam('dimNormal');
		$keepOriginalImage = Params::getParam('keep_original_image');
		$use_imagick = Params::getParam('use_imagick');
		$type_watermark = Params::getParam('watermark_type');
		$watermark_color = Params::getParam('watermark_text_color');
		$watermark_text = Params::getParam('watermark_text');
		$watermark_image = Params::getParam('watermark_image');
		switch ($type_watermark) 
		{
		case 'none':
			$iUpdated+= Preference::newInstance()->update(array('s_value' => ''), array('s_name' => 'watermark_text_color'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => ''), array('s_name' => 'watermark_text'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => ''), array('s_name' => 'watermark_image'));
			break;

		case 'text':
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $watermark_color), array('s_name' => 'watermark_text_color'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $watermark_text), array('s_name' => 'watermark_text'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => ''), array('s_name' => 'watermark_image'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => Params::getParam('watermark_text_place')), array('s_name' => 'watermark_place'));
			break;

		case 'image':
			// upload image & move to path
			if ($_FILES['watermark_image']['error'] == UPLOAD_ERR_OK) 
			{
				$tmpName = $_FILES['watermark_image']['tmp_name'];
				$path = osc_content_path() . 'uploads/watermark.png';
				if (move_uploaded_file($tmpName, $path)) 
				{
					$iUpdated+= Preference::newInstance()->update(array('s_value' => $path), array('s_name' => 'watermark_image'));
				}
				else
				{
					$iUpdated+= Preference::newInstance()->update(array('s_value' => ''), array('s_name' => 'watermark_image'));
				}
			}
			$iUpdated+= Preference::newInstance()->update(array('s_value' => ''), array('s_name' => 'watermark_text_color'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => ''), array('s_name' => 'watermark_text'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => Params::getParam('watermark_image_place')), array('s_name' => 'watermark_place'));
			break;

		default:
			break;
		}
		// format parameters
		$maxSizeKb = strip_tags($maxSizeKb);
		$allowedExt = strip_tags($allowedExt);
		$dimThumbnail = strip_tags($dimThumbnail);
		$dimPreview = strip_tags($dimPreview);
		$dimNormal = strip_tags($dimNormal);
		$keepOriginalImage = ($keepOriginalImage != '' ? true : false);
		$use_imagick = ($use_imagick != '' ? true : false);
		if (!extension_loaded('imagick')) 
		{
			$use_imagick = false;
		}
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $maxSizeKb), array('s_name' => 'maxSizeKb'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $allowedExt), array('s_name' => 'allowedExt'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $dimThumbnail), array('s_name' => 'dimThumbnail'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $dimPreview), array('s_name' => 'dimPreview'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $dimNormal), array('s_name' => 'dimNormal'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $keepOriginalImage), array('s_name' => 'keep_original_image'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $use_imagick), array('s_name' => 'use_imagick'));
		if ($iUpdated > 0) 
		{
			$this->getSession()->addFlashMessage( _m('Media config has been updated'), 'admin' );
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=media');
	}
}

