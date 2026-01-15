<template>
	<ul id="grouplist">
		<li class="group">
			<a v-if="!showAddNotebook" class="name" role="button" @click="showAddNotebook = true">
				+ {{ t('alternote', 'New notebook') }}
			</a>
			<div v-else class="add_group_container">
				<input
					v-model="newNotebookName"
					type="text"
					id="new_group_name"
					:placeholder="t('alternote', 'Enter notebook name')"
					@keyup.enter="handleCreateNotebook"
					@keyup.esc="cancelAddNotebook"
				/>
				<div class="button" @click="handleCreateNotebook">
					<i class="fa fa-check"></i>
				</div>
				<div class="button" @click="cancelAddNotebook">
					<i class="fa fa-times"></i>
				</div>
			</div>
		</li>
		<li
			class="group"
			:class="{ active: selectedNotebook === 'all' }"
			@click="$emit('select-notebook', 'all')"
		>
			<a class="name" role="button">{{ t('alternote', 'All') }}</a>
			<span class="utils">
				<span class="action numnotes">{{ noteCount }}</span>
			</span>
		</li>
		<li
			class="group"
			:class="{ active: selectedNotebook === null }"
			@click="$emit('select-notebook', null)"
		>
			<a class="name">{{ t('alternote', 'Not grouped') }}</a>
			<span class="utils">
				<span class="action numnotes">{{ ungroupedCount }}</span>
			</span>
		</li>
		<li
			v-for="notebook in notebooks"
			:key="notebook.id"
			:id="`group-${notebook.id}`"
			class="group"
			:class="{ active: selectedNotebook === notebook.id }"
			@click="$emit('select-notebook', notebook.id)"
		>
			<a class="name" role="button">{{ notebook.name }}</a>
			<span class="utils">
				<span class="action numnotes">{{ notebook.note_count || 0 }}</span>
			</span>
		</li>
		<li
			class="nav-trashbin"
			:class="{ active: showDeleted }"
			@click="$emit('toggle-deleted')"
		>
			<a class="nav-icon-trashbin svg">
				{{ t('alternote', 'Deleted notes') }}
			</a>
			<span class="utils">
				<span class="action numnotes">{{ deletedCount }}</span>
			</span>
		</li>
	</ul>
</template>

<script>
import { ref, computed } from 'vue'
import { translate as t } from '@nextcloud/l10n'

export default {
	name: 'NotebookSidebar',
	props: {
		notebooks: {
			type: Array,
			default: () => []
		},
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
	emits: ['select-notebook', 'toggle-deleted', 'create-notebook'],
	setup(props, { emit }) {
		const showAddNotebook = ref(false)
		const newNotebookName = ref('')

		const noteCount = computed(() => {
			return Object.keys(props.notes).filter(key => {
				const note = props.notes[key]
				return note && note.id && note.deleted === 0
			}).length
		})

		const ungroupedCount = computed(() => {
			return Object.values(props.notes).filter(note => {
				return note && note.id && note.notebook === null && note.deleted === 0
			}).length
		})

		const deletedCount = computed(() => {
			return Object.values(props.notes).filter(note => {
				return note && note.id && note.deleted === 1
			}).length
		})

		const handleCreateNotebook = () => {
			if (newNotebookName.value.trim()) {
				emit('create-notebook', newNotebookName.value.trim())
				newNotebookName.value = ''
				showAddNotebook.value = false
			}
		}

		const cancelAddNotebook = () => {
			newNotebookName.value = ''
			showAddNotebook.value = false
		}

		return {
			showAddNotebook,
			newNotebookName,
			noteCount,
			ungroupedCount,
			deletedCount,
			handleCreateNotebook,
			cancelAddNotebook,
			t
		}
	}
}
</script>
