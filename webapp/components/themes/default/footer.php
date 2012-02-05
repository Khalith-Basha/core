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

$pages = __get( 'pages' );
?>

<br style="clear: both" />

<?php osc_show_widgets('footer'); ?>
<footer>
	<ul>
		<li><a href="<?php echo osc_contact_url(); ?>"><?php _e( 'Contact', 'modern' ); ?></a></li>
		<?php foreach( $pages as $page ): ?>
			<li><a href="<?php echo osc_static_page_url( $page ); ?>"><?php echo $page['s_title']; ?></a></li>
		<?php endforeach; ?>
		<li><?php _e('This website is proudly using an <a title="OpenSourceClassifieds project" href="http://www.opensourceclassifieds.org/">open source classifieds</a> software.', 'modern'); ?></li>
	</ul>
</footer>

<?php osc_run_hook('footer'); ?>

</div>

</body>
</html>
