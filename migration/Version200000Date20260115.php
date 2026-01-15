<?php
/**
 * Nextcloud - Alternote
 *
 * @copyright Copyright (c) 2026, Andrew Mark
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

namespace OCA\Alternote\Migration;

use OCP\DB\ISchemaWrapper;
use OCP\IDBConnection;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version200000Date20260115 extends SimpleMigrationStep {

	/**
	 * @param IOutput $output
	 * @param \Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 */
	public function changeSchema(IOutput $output, \Closure $schemaClosure, array $options) {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		if (!$schema->hasTable('alternote_notes')) {
			$table = $schema->createTable('alternote_notes');
			$table->addColumn('id', 'integer', [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 4,
			]);
			$table->addColumn('guid', 'string', [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('uid', 'string', [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('name', 'string', [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('notebook', 'integer', [
				'notnull' => true,
				'length' => 4,
				'default' => 0,
			]);
			$table->addColumn('mtime', 'integer', [
				'notnull' => true,
				'length' => 4,
			]);
			$table->addColumn('deleted', 'integer', [
				'notnull' => true,
				'length' => 1,
				'default' => 0,
			]);
			$table->addColumn('note', 'text', [
				'notnull' => true,
			]);
			$table->setPrimaryKey(['id']);
			$table->addIndex(['uid'], 'alternote_notes_uid_idx');
			$table->addIndex(['notebook'], 'alternote_notes_notebook_idx');
			$table->addIndex(['deleted'], 'alternote_notes_deleted_idx');
		}

		if (!$schema->hasTable('alternote_parts')) {
			$table = $schema->createTable('alternote_parts');
			$table->addColumn('pid', 'integer', [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 4,
			]);
			$table->addColumn('id', 'integer', [
				'notnull' => true,
				'length' => 4,
			]);
			$table->addColumn('note', 'text', [
				'notnull' => true,
			]);
			$table->setPrimaryKey(['pid']);
			$table->addIndex(['id'], 'alternote_parts_id_idx');
		}

		if (!$schema->hasTable('alternote_groups')) {
			$table = $schema->createTable('alternote_groups');
			$table->addColumn('id', 'integer', [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 4,
			]);
			$table->addColumn('guid', 'string', [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('parent_id', 'integer', [
				'notnull' => false,
				'length' => 4,
				'default' => 0,
			]);
			$table->addColumn('uid', 'string', [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('name', 'string', [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('color', 'string', [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('deleted', 'integer', [
				'notnull' => true,
				'length' => 1,
				'default' => 0,
			]);
			$table->setPrimaryKey(['id']);
			$table->addIndex(['uid'], 'alternote_groups_uid_idx');
			$table->addIndex(['parent_id'], 'alternote_groups_parent_idx');
			$table->addIndex(['deleted'], 'alternote_groups_deleted_idx');
		}

		return $schema;
	}
}
