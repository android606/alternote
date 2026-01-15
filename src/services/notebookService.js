/**
 * Nextcloud - Alternote
 *
 * @copyright Copyright (c) 2026, Andrew Mark
 * @license GNU AGPL version 3 or any later version
 */

import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

const API_BASE = generateUrl('/apps/alternote/api/v2')

export const notebookService = {
	async getAll(deleted = false) {
		const params = {}
		if (deleted !== false) params.deleted = deleted

		const response = await axios.get(`${API_BASE}/notebook`, { params })
		return Array.isArray(response.data) ? response.data : Object.values(response.data)
	},

	async get(id) {
		const response = await axios.get(`${API_BASE}/notebook/${id}`)
		return response.data
	},

	async create(notebook) {
		const response = await axios.post(`${API_BASE}/notebook`, {
			name: notebook.name,
			color: notebook.color || '',
			parent_id: notebook.parent_id || 0
		})
		return response.data
	},

	async update(id, notebook) {
		const response = await axios.put(`${API_BASE}/notebook/${id}`, {
			name: notebook.name,
			color: notebook.color || '',
			parent_id: notebook.parent_id || 0
		})
		return response.data
	},

	async delete(id) {
		await axios.delete(`${API_BASE}/notebook/${id}`)
	}
}
