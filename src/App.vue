<template>
	<div id="alternote-app">
		<div id="app-navigation" v-show="sidebarShown">
			<NotebookSidebar
				:notebooks="notebooks"
				:notes="notes"
				:selected-notebook="selectedNotebook"
				:show-deleted="showDeleted"
				@select-notebook="handleSelectNotebook"
				@toggle-deleted="showDeleted = !showDeleted"
				@create-notebook="createNotebook"
			/>
		</div>
		<div id="app-content">
			<NoteList
				:notes="notes"
				:notebooks="notebooks"
				:selected-notebook="selectedNotebook"
				:show-deleted="showDeleted"
				@refresh="loadData"
			/>
			<router-view />
		</div>
	</div>
</template>

<script>
import { ref, onMounted, provide } from 'vue'
import { noteService } from './services/noteService'
import { notebookService } from './services/notebookService'
import NotebookSidebar from './components/NotebookSidebar.vue'
import NoteList from './views/NoteList.vue'

export default {
	name: 'App',
	components: {
		NotebookSidebar,
		NoteList
	},
	setup() {
		const notes = ref({})
		const notebooks = ref([])
		const sidebarShown = ref(true)
		const selectedNotebook = ref('all')
		const showDeleted = ref(false)

		const loadData = async () => {
			try {
				notes.value = await noteService.getAll(showDeleted.value, selectedNotebook.value === 'all' ? null : selectedNotebook.value)
				notebooks.value = await notebookService.getAll()
			} catch (error) {
				console.error('Failed to load data:', error)
			}
		}

		const handleSelectNotebook = (notebookId) => {
			selectedNotebook.value = notebookId
			loadData()
		}

		const createNotebook = async (name) => {
			try {
				await notebookService.create({ name, color: '', parent_id: 0 })
				await loadData()
			} catch (error) {
				console.error('Failed to create notebook:', error)
			}
		}

		// Provide data to child components
		provide('notes', notes)
		provide('notebooks', notebooks)
		provide('loadData', loadData)

		onMounted(() => {
			loadData()
		})

		return {
			notes,
			notebooks,
			sidebarShown,
			selectedNotebook,
			showDeleted,
			loadData,
			createNotebook,
			handleSelectNotebook
		}
	}
}
</script>
