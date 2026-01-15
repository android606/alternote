<template>
	<div id="ownnote">
		<div class="view-container">
			<div class="view-left">
				<div id="controls">
					<div id="new" class="button indent" @click="newNote">
						{{ t('alternote', 'New') }}
					</div>
				</div>
				<table class="listingSort">
					<thead>
						<tr>
							<th class="notename filesort notesort">
								<div
									class="pointer sorttitle"
									@click="toggleSort('name')"
								>
									{{ t('alternote', 'Name') }}
								</div>
								<div
									v-if="sortField === 'name'"
									class="sortarrow"
									:class="{ sortup: sortReverse, sortdown: !sortReverse }"
								></div>
							</th>
							<th class="actions"></th>
							<th class="info modified notesort">
								<span
									class="pointer"
									@click="toggleSort('mtime')"
								>
									{{ t('alternote', 'Modified') }}
								</span>
								<div
									v-if="sortField === 'mtime'"
									class="sortarrow"
									:class="{ sortup: sortReverse, sortdown: !sortReverse }"
								></div>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr
							v-for="note in filteredNotes"
							:key="note.id"
							class="listing"
							@click="editNote(note)"
						>
							<td :title="note.title" class="file pointer">
								<div class="caption note-title">{{ note.title }}</div>
								<div class="caption">
									<small>{{ getPlainContent(note.content_plain) }}</small>
								</div>
							</td>
							<td class="actions" v-if="!showDeleted">
								<div v-if="hasPermission(note, 'delete')" class="buttons delete-note" @click.stop="deleteNote(note)">
									<span class="pointer buttons delete"></span>
								</div>
								<div v-if="hasPermission(note, 'update')" class="buttons edit-note" @click.stop="editNote(note)">
									<span class="pointer buttons edit"></span>
								</div>
							</td>
							<td class="actions" v-else>
								<div v-if="hasPermission(note, 'delete')" class="buttons delete pointer" @click.stop="deleteNote(note)">
									<span class="delete-note"></span>
								</div>
								<div v-if="hasPermission(note, 'delete')" class="buttons pointer restore-note" @click.stop="restoreNote(note)">
								</div>
							</td>
							<td class="info">
								<div class="modified">
									<span>{{ formatDate(note.mtime) }}</span>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="view-right">
				<router-view />
			</div>
		</div>
	</div>
</template>

<script>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { noteService } from '../services/noteService'
import { translate as t } from '@nextcloud/l10n'
import { showSuccess, showError } from '@nextcloud/dialogs'

export default {
	name: 'NoteList',
	props: {
		notes: {
			type: Object,
			default: () => ({})
		},
		selectedNotebook: {
			type: [String, Number, null],
			default: 'all'
		},
		showDeleted: {
			type: Boolean,
			default: false
		}
	},
	emits: ['refresh'],
	setup(props, { emit }) {
		const router = useRouter()
		const sortField = ref('name')
		const sortReverse = ref(true)

		const filteredNotes = computed(() => {
			let noteList = Object.values(props.notes).filter(note => {
				if (!note || !note.id) return false
				if (props.showDeleted) {
					return note.deleted === 1
				}
				if (note.deleted === 1) return false
				if (props.selectedNotebook === 'all') return true
				if (props.selectedNotebook === null) return note.notebook === null
				return note.notebook === props.selectedNotebook
			})

			noteList.sort((a, b) => {
				let aVal = a[sortField.value]
				let bVal = b[sortField.value]
				if (sortField.value === 'mtime') {
					aVal = aVal || 0
					bVal = bVal || 0
				} else {
					aVal = String(aVal || '').toLowerCase()
					bVal = String(bVal || '').toLowerCase()
				}
				if (aVal < bVal) return sortReverse.value ? 1 : -1
				if (aVal > bVal) return sortReverse.value ? -1 : 1
				return 0
			})

			return noteList
		})

		const toggleSort = (field) => {
			if (sortField.value === field) {
				sortReverse.value = !sortReverse.value
			} else {
				sortField.value = field
				sortReverse.value = false
			}
		}

		const editNote = (note) => {
			if (note.deleted === 1) return
			router.push(`/note/edit/${note.id}`)
		}

		const newNote = () => {
			router.push('/note/new')
		}

		const deleteNote = async (note) => {
			try {
				if (note.deleted === 0) {
					await noteService.softDelete(note.id)
					showSuccess(t('alternote', 'Note deleted'))
				} else {
					await noteService.delete(note.id)
					showSuccess(t('alternote', 'Note permanently deleted'))
				}
				emit('refresh')
			} catch (error) {
				showError(t('alternote', 'Failed to delete note'))
				console.error(error)
			}
		}

		const restoreNote = async (note) => {
			try {
				await noteService.restore(note.id)
				showSuccess(t('alternote', 'Note restored'))
				emit('refresh')
			} catch (error) {
				showError(t('alternote', 'Failed to restore note'))
				console.error(error)
			}
		}

		const hasPermission = (note, perm) => {
			if (!note.owner || !note.owner.uid) return false
			if (note.owner.uid === OC.currentUser) return true
			const permission = `PERMISSION_${perm.toUpperCase()}`
			return note.permissions & OC[permission]
		}

		const getPlainContent = (content) => {
			if (!content) return ''
			return content.substring(0, 500)
		}

		const formatDate = (timestamp) => {
			if (!timestamp) return ''
			const seconds = Math.floor((Date.now() - timestamp) / 1000)
			if (seconds < 60) return t('alternote', 'just now')
			if (seconds < 3600) return t('alternote', '{minutes} minutes ago', { minutes: Math.floor(seconds / 60) })
			if (seconds < 86400) return t('alternote', '{hours} hours ago', { hours: Math.floor(seconds / 3600) })
			if (seconds < 604800) return t('alternote', '{days} days ago', { days: Math.floor(seconds / 86400) })
			const date = new Date(timestamp)
			return date.toLocaleDateString(OC.getLocale())
		}

		return {
			sortField,
			sortReverse,
			filteredNotes,
			toggleSort,
			editNote,
			newNote,
			deleteNote,
			restoreNote,
			hasPermission,
			getPlainContent,
			formatDate,
			t
		}
	}
}
</script>
