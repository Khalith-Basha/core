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
class CAdminTool extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		switch ($this->action) 
		{
		case 'images':
			$this->doView('tools/images.php');
			break;

		case 'images_post':
			$preferences = Preference::newInstance()->toArray();
			$wat = new Watermark();
			$aResources = ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' )->getAllResources();
			foreach ($aResources as $resource) 
			{
				osc_run_hook('regenerate_image', $resource);
				$path = osc_content_path() . 'uploads/';
				// comprobar que no haya original
				$img_original = $path . $resource['pk_i_id'] . "_original*";
				$aImages = glob($img_original);
				// there is original image
				if (count($aImages) == 1) 
				{
					$image_tmp = $aImages[0];
				}
				else
				{
					$img_normal = $path . $resource['pk_i_id'] . ".*";
					$aImages = glob($img_normal);
					if (count($aImages) == 1) 
					{
						$image_tmp = $aImages[0];
					}
					else
					{
						$img_thumbnail = $path . $resource['pk_i_id'] . "_thumbnail*";
						$aImages = glob($img_thumbnail);
						$image_tmp = $aImages[0];
					}
				}
				// extension
				preg_match('/\.(.*)$/', $image_tmp, $matches);
				if (isset($matches[1])) 
				{
					$extension = $matches[1];
					// Create normal size
					$path_normal = $path = osc_content_path() . 'uploads/' . $resource['pk_i_id'] . '.jpg';
					$size = explode('x', osc_normal_dimensions());
					ImageResizer::fromFile($image_tmp)->resizeTo($size[0], $size[1])->saveToFile($path);
					if (osc_is_watermark_text()) 
					{
						$wat->doWatermarkText($path, osc_watermark_text_color(), osc_watermark_text(), 'image/jpeg');
					}
					elseif (osc_is_watermark_image()) 
					{
						$wat->doWatermarkImage($path, 'image/jpeg');
					}
					// Create preview
					$path = osc_content_path() . 'uploads/' . $resource['pk_i_id'] . '_preview.jpg';
					$size = explode('x', osc_preview_dimensions());
					ImageResizer::fromFile($path_normal)->resizeTo($size[0], $size[1])->saveToFile($path);
					// Create thumbnail
					$path = osc_content_path() . 'uploads/' . $resource['pk_i_id'] . '_thumbnail.jpg';
					$size = explode('x', osc_thumbnail_dimensions());
					ImageResizer::fromFile($path_normal)->resizeTo($size[0], $size[1])->saveToFile($path);
					// update resource info
					ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' )->update(array('s_path' => 'components/uploads/', 's_name' => osc_genRandomPassword(), 's_extension' => 'jpg', 's_content_type' => 'image/jpeg'), array('pk_i_id' => $resource['pk_i_id']));
					osc_run_hook('regenerated_image', ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' )->findByPrimaryKey($resource['pk_i_id']));
					// si extension es direfente a jpg, eliminar las imagenes con $extension si hay
					if ($extension != 'jpg') 
					{
						$files_to_remove = osc_content_path() . 'uploads/' . $resource['pk_i_id'] . "*" . $extension;
						$fs = glob($files_to_remove);
						if (is_array($fs)) 
						{
							array_map("unlink", $fs);
						}
					}
					// ....
					
				}
				else
				{
					// no es imagen o imagen sin extesión
					
				}
			}
			osc_add_flash_ok_message(_m('Re-generation complete'), 'admin');
			$this->redirectTo(osc_admin_base_url(true) . '?page=tool&action=images');
			break;
		}
	}
}

