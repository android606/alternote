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

spl_autoload_register(function ($class) {
	if (strpos($class, 'OCA\\Alternote\\') === 0) {
		$class = str_replace('OCA\\Alternote\\', '', $class);
		$file = __DIR__ . '/../../' . strtolower(str_replace('\\', '/', $class)) . '.php';
		if (file_exists($file)) {
			require_once $file;
		}
	}
});


