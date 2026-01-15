<?php
/**
 * Nextcloud - NextNote
 *
 *
 * @copyright Copyright (c) 2017, Sander Brand (brantje@gmail.com)
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Alternote\AppInfo;

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> PageController->index()
 */
return [
	'routes' => [

		['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],

		// V2 API
		['name' => 'note_api#preflighted_cors', 'url' => '/api/v2/{path}', 'verb' => 'OPTIONS', 'requirements' => ['path' => '.+']],

		// Notes
		['name' => 'note_api#index', 'url' => '/api/v2/note', 'verb' => 'GET'],
		['name' => 'note_api#create', 'url' => '/api/v2/note', 'verb' => 'POST'],
		['name' => 'note_api#get', 'url' => '/api/v2/note/{id}', 'verb' => 'GET'],
		['name' => 'note_api#update', 'url' => '/api/v2/note/{id}', 'verb' => 'PUT'],
		['name' => 'note_api#delete', 'url' => '/api/v2/note/{id}', 'verb' => 'DELETE'],

		// Notebooks
		['name' => 'notebook_api#index', 'url' => '/api/v2/notebook', 'verb' => 'GET'],
		['name' => 'notebook_api#create', 'url' => '/api/v2/notebook', 'verb' => 'POST'],
		['name' => 'notebook_api#get', 'url' => '/api/v2/notebook/{id}', 'verb' => 'GET'],
		['name' => 'notebook_api#update', 'url' => '/api/v2/notebook/{id}', 'verb' => 'PUT'],
		['name' => 'notebook_api#delete', 'url' => '/api/v2/notebook/{id}', 'verb' => 'DELETE'],

		// Translations
		['name' => 'translation#getLanguageStrings', 'url' => '/api/v2/language', 'verb' => 'GET'],
		// Settings
		['name' => 'settings#saveAdminSetting', 'url' => '/api/v2/settings', 'verb' => 'POST'],
		['name' => 'settings#saveUserSetting', 'url' => '/api/v2/settings-user', 'verb' => 'POST'],
		['name' => 'settings#getSettings', 'url' => '/api/v2/settings', 'verb' => 'GET'],

		// Sharing
		['name' => 'share_api#getshares', 'url' => '/api/v2/sharing/shares', 'verb' => 'GET'],
		['name' => 'share_api#share', 'url' => '/api/v2/sharing/shares', 'verb' => 'POST'],
		['name' => 'share_api#unshare', 'url' => '/api/v2/sharing/shares/{itemSource}', 'verb' => 'DELETE', 'requirements' => ['itemSource' => '.+']],
		['name' => 'share_api#setpermissions', 'url' => '/api/v2/sharing/shares/{itemSource}/permissions', 'verb' => 'PUT', 'requirements' => ['itemSource' => '.+']]
	]
]
