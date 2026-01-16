<?php
namespace OCA\Alternote\AppInfo;
return [
	'routes' => [
		['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
		['name' => 'note_api#preflighted_cors', 'url' => '/api/v2/{path}', 'verb' => 'OPTIONS', 'requirements' => ['path' => '.+']],
		['name' => 'note_api#index', 'url' => '/api/v2/note', 'verb' => 'GET'],
		['name' => 'note_api#create', 'url' => '/api/v2/note', 'verb' => 'POST'],
		['name' => 'note_api#get', 'url' => '/api/v2/note/{id}', 'verb' => 'GET'],
		['name' => 'note_api#update', 'url' => '/api/v2/note/{id}', 'verb' => 'PUT'],
		['name' => 'note_api#delete', 'url' => '/api/v2/note/{id}', 'verb' => 'DELETE'],
		['name' => 'notebook_api#index', 'url' => '/api/v2/notebook', 'verb' => 'GET'],
		['name' => 'notebook_api#create', 'url' => '/api/v2/notebook', 'verb' => 'POST'],
		['name' => 'notebook_api#get', 'url' => '/api/v2/notebook/{id}', 'verb' => 'GET'],
		['name' => 'notebook_api#update', 'url' => '/api/v2/notebook/{id}', 'verb' => 'PUT'],
		['name' => 'notebook_api#delete', 'url' => '/api/v2/notebook/{id}', 'verb' => 'DELETE'],
		['name' => 'translation#getLanguageStrings', 'url' => '/api/v2/language', 'verb' => 'GET'],
		['name' => 'settings#saveAdminSetting', 'url' => '/api/v2/settings', 'verb' => 'POST'],
		['name' => 'settings#saveUserSetting', 'url' => '/api/v2/settings-user', 'verb' => 'POST'],
		['name' => 'settings#getSettings', 'url' => '/api/v2/settings', 'verb' => 'GET'],
		['name' => 'share_api#getshares', 'url' => '/api/v2/sharing/shares', 'verb' => 'GET'],
		['name' => 'share_api#share', 'url' => '/api/v2/sharing/shares', 'verb' => 'POST'],
		['name' => 'share_api#unshare', 'url' => '/api/v2/sharing/shares/{itemSource}', 'verb' => 'DELETE', 'requirements' => ['itemSource' => '.+']],
		['name' => 'share_api#setpermissions', 'url' => '/api/v2/sharing/shares/{itemSource}/permissions', 'verb' => 'PUT', 'requirements' => ['itemSource' => '.+']]
	]
];
