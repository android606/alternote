/**
 * Nextcloud - Alternote
 *
 * @copyright Copyright (c) 2026, Andrew Mark
 * @license GNU AGPL version 3 or any later version
 */

import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

const API_BASE = generateUrl('/apps/alternote/api/v2')

export const noteService = {
	async getAll(deleted = false, notebookId = null) {
		const params = {}
		if (deleted !== false) params.deleted = deleted
		if (notebookId) params.notebook_id = notebookId

		const response = await axios.get(`${API_BASE}/note`, { params })
		const notes = {}
		for (const key in response.data) {
			if (response.data.hasOwnProperty(key) && !isNaN(key)) {
				const note = response.data[key]
				note.mtime = note.mtime * 1000 // Convert to JS timestamp
				notes[note.id] = note
			}
		}
		return notes
	},

	async get(id) {
		const response = await axios.get(`${API_BASE}/note/${id}`)
		const note = response.data
		note.mtime = note.mtime * 1000
		return note
	},

	async create(note) {
		const response = await axios.post(`${API_BASE}/note`, {
			title: note.title,
			notebook_id: note.notebook_id || null,
			content: note.content || ''
		})
		const created = response.data
		created.mtime = created.mtime * 1000
		return created
	},

	async update(id, note) {
		const response = await axios.put(`${API_BASE}/note/${id}`, {
			title: note.title,
			content: note.content,
			deleted: note.deleted || 0,
			notebook_id: note.notebook_id || null
		})
		const updated = response.data
		updated.mtime = updated.mtime * 1000
		return updated
	},

	async delete(id) {
		await axios.delete(`${API_BASE}/note/${id}`)
	},

	async softDelete(id) {
		return this.update(id, { deleted: 1 })
	},

	async restore(id) {
		return this.update(id, { deleted: 0 })
	}
}
