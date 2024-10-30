<?php
/**
 * Plugin Name: BLVD Status
 * Plugin URI: http://www.blvdstatus.com/blog/official-blvd-status-wordpress-plugin/10/
 * Description: Simply adds your <a href="http://www.blvdstatus.com">BLVD Status</a> tracking to your blog with SSL detection and support.
 * Version: 2.0
 * Author: Patrick Bennett
 * Author URI: http://www.blvdstatus.com
*/

/**
 * Changelog
 * 5/28/2990 - V2.0 New tracking code implementation with admin page for settings
**/

/*  Copyright 2009  BLVD Status  (email : support@blvdstatus.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


class BlvdStatus {
	
	/**
	 * Outputs the tracking code
	**/
	public function getCode() {
		if(get_option('blvdstatus-code')) {
			echo get_option('blvdstatus-code');
		} else {
			return false;
		}
	}
	
	/**
	 * Allows the user to update settings within wordpress admin
	**/
	public function settings() {
		if ( current_user_can('edit_plugins') ) {
			
			//did they save
			if(isset($_POST['action']) && $_POST['action'] == 'updateblvd') {
				$_POST['blvdstatus']['code'] = stripslashes($_POST['blvdstatus']['code']);
				if($_POST['blvdstatus']['code'] != "") {
					update_option('blvdstatus-code',$_POST['blvdstatus']['code']);
					echo '<div class="updated">' . "\n"
							. '<p><strong>Your BLVD Status tracking script was added succcessfully.</strong></p>' . "\n"
						. '</div>' . "\n";					
				} else {
					echo '<div class="error">' . "\n"
							. '<p><strong>You did not paste any tracking script. Please try again.</strong></p>' . "\n"
						. '</div>' . "\n";
				}
			}
?>
			<div class="wrap">
				<h2><?php _e('BLVD Status settings') ?></h2>
                <p>Please paste your Blvd Status tracking code for this domain in the box below. Make sure you use a theme which calls the "wp_footer()" function. <strong>If you need help</strong>, please <a href="http://www.blvdstatus.com/blog/official-blvd-status-wordpress-plugin/10/" target="_blank">visit this link</a>.</p>
				<form method="post" id="blvdstatus">
				<?php wp_nonce_field('update-options'); ?>
                <table class="form-table">
					<tr valign="top">
						<td>
							<textarea name="blvdstatus[code]" id="blvdstatus-code" style="width:95%;" rows="6"><?php BlvdStatus::getCode(); ?></textarea>
						</td>
					</tr>
                </table>
				<p class="submit">
                	<input type="submit" value="save" />
                </p>
                <input type="hidden" name="action" value="updateblvd" />
                </form>
            </div>
<?php
		}
	}

	/**
	 * Adds admin link and triggers function for settings page
	 */
	public function admin_link() {
		add_options_page(__('BLVD Status'), __('BLVD Status'), 'manage_options', str_replace("\\", "/", __FILE__), array('BlvdStatus', 'settings'));
	}
}	
	
//add the script to the wordpress footer
add_action('admin_menu', array('BlvdStatus','admin_link'));
add_action('wp_footer',array('BlvdStatus','getCode'));
?>