<?php

/**
 * Private Profiles plugin for Elgg 1.8+1.9
 * @package private_profiles
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author iionly
 * @website https://github.com/iionly
 *
 */

elgg_register_event_handler('init', 'system', 'private_profiles_init');

function private_profiles_init() {

	elgg_register_plugin_hook_handler('route', 'profile', 'private_profiles_router');
}

function private_profiles_router($hook, $type, $result) {

	// Admins are allowed to visit all profiles
	if (elgg_is_admin_logged_in()) {
		return $result;
	}

	$page = $result['segments'];
	$page = array_pad($page, 4, "");

	if (isset($page[0])) {

		$username = $page[0];
		$user = get_user_by_username($username);
		
		if (($logged_in_user_guid = elgg_get_logged_in_user_guid()) && ($logged_in_user_guid == $user->getGUID())) {
			return $result;
		}
	}
	
	// either no one logged in or no valid profile username or logged in user is trying to view another user's profile
	forward(REFERER);
	return false;
}
