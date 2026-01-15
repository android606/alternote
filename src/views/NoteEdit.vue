<template>
	<div id="ownnote" class="edit-note-container">
		<div class="controls">
			<span id="newfile" class="indent">
				<span v-if="hasPermission(note, 'update')">
					{{ t('alternote', 'Name') }}:
					<input
						v-model="note.title"
						type="text"
						class="fileinput"
						:placeholder="t('alternote', 'Untitled note')"
					/>
					<select v-model="selectedNotebookId">
						<option :value="null">{{ t('alternote', 'Not grouped') }}</option>
						<option v-for="nb in localNotebooks" :key="nb.id" :value="nb.id">
							{{ nb.name }}
						</option>
						<option value="_new">{{ t('alternote', 'New notebook') }}</option>
					</select>
					<input
						v-if="selectedNotebookId === '_new'"
						v-model="newNotebookName"
						type="text"
						:placeholder="t('alternote', 'Notebook name')"
					/>
					<div class="button" @click="saveNote">
						{{ t('alternote', 'Save') }}
					</div>
				</span>
				<span v-else style="padding-right: 10px;">
					<b>{{ note.title }}</b>
					{{ t('alternote', 'No edit permission') }}
				</span>
				<div id="canceledit" class="button" @click="goBack">
					{{ t('alternote', 'Back') }}
				</div>
				<div class="pull-right autosave" :class="{ shown: autoSaved }">
					{{ t('alternote', 'Saved!') }}
				</div>
			</span>
		</div>
		<TinyMCEEditor
			v-model="note.content"
			:disabled="!hasPermission(note, 'update')"
			:height="editorHeight"
		/>
	</div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { noteService } from '../services/noteService'
import { notebookService } from '../services/notebookService'
import { translate as t } from '@nextcloud/l10n'
import { showSuccess, showError } from '@nextcloud/dialogs'
import TinyMCEEditor from '../components/Editor.vue'

export default {
	name: 'NoteEdit',
	components: {
		TinyMCEEditor
	},
	props: {
		id: {
			type: String,
			default: null
		},
		notes: {
			type: Object,
			default: () => ({})
		},
		notebooks: {
			type: Array,
			default: () => []
		}
	},
	setup(props) {
		const route = useRoute()
		const router = useRouter()
		const note = ref({
			id: null,
			title: '',
			content: '',
			notebook_id: null,
			owner: { uid: OC.getCurrentUser().uid },
			permissions: OC.PERMISSION_ALL
		})
		const selectedNotebookId = ref(null)
		const newNotebookName = ref('')
		const autoSaved = ref(false)
		const editorHeight = ref(600)
		const autoSaveTimer = ref(null)

		const localNotebooks = computed(() => {
			return props.notebooks.filter(nb => nb.deleted === 0)
		})

		const loadNote = async () => {
			const noteId = props.id || route.params.id
			if (noteId && noteId !== 'new') {
				try {
					const loadedNote = await noteService.get(noteId)
					note.value = loadedNote
					selectedNotebookId.value = loadedNote.notebook || null
				} catch (error) {
					showError(t('alternote', 'Failed to load note'))
					console.error(error)
				}
			}
		}

		const saveNote = async () => {
			if (!note.value.title.trim()) return

			try {
				let notebookId = selectedNotebookId.value
				if (notebookId === '_new' && newNotebookName.value.trim()) {
					const newNotebook = await notebookService.create({
						name: newNotebookName.value.trim(),
						color: '',
						parent_id: 0
					})
					notebookId = newNotebook.id
					newNotebookName.value = ''
				}

				if (note.value.id) {
					await noteService.update(note.value.id, {
						title: note.value.title,
						content: note.value.content,
						notebook_id: notebookId
					})
				} else {
					const created = await noteService.create({
						title: note.value.title,
						content: note.value.content,
						notebook_id: notebookId
					})
					note.value.id = created.id
					router.replace(`/note/edit/${created.id}`)
				}

				autoSaved.value = true
				setTimeout(() => {
					autoSaved.value = false
				}, 2500)
			} catch (error) {
				showError(t('alternote', 'Failed to save note'))
				console.error(error)
			}
		}

		const goBack = () => {
			router.push('/')
		}

		const hasPermission = (note, perm) => {
			if (!note.owner || !note.owner.uid) return false
			if (note.owner.uid === OC.currentUser) return true
			const permission = `PERMISSION_${perm.toUpperCase()}`
			return note.permissions & OC[permission]
		}

		// Auto-save functionality
		watch([() => note.value.title, () => note.value.content], () => {
			if (!note.value.id || !hasPermission(note.value, 'update')) return
			if (!note.value.title.trim()) return

			if (autoSaveTimer.value) {
				clearTimeout(autoSaveTimer.value)
			}

			autoSaveTimer.value = setTimeout(() => {
				if (route.params.id) {
					saveNote()
				}
			}, 10000)
		})

		onMounted(() => {
			loadNote()
			editorHeight.value = window.innerHeight - 200
		})

		return {
			note,
			selectedNotebookId,
			newNotebookName,
			autoSaved,
			editorHeight,
			localNotebooks,
			saveNote,
			goBack,
			hasPermission,
			t
		}
	}
}
</script>
