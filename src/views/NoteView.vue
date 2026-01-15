<template>
	<div id="ownnote" class="view-note-container">
		<div id="controls">
			<span class="indent">
				<div id="canceledit" class="button" @click="goBack">
					{{ t('alternote', 'Back') }}
				</div>
			</span>
		</div>
		<div class="listingBlank"></div>
		<h1><b>{{ note.title }}</b></h1>
		<div class="noteContent" v-html="note.content"></div>
	</div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { noteService } from '../services/noteService'
import { translate as t } from '@nextcloud/l10n'
import { showError } from '@nextcloud/dialogs'

export default {
	name: 'NoteView',
	props: {
		id: {
			type: String,
			required: true
		}
	},
	setup(props) {
		const route = useRoute()
		const router = useRouter()
		const note = ref({
			title: '',
			content: ''
		})

		const loadNote = async () => {
			const noteId = props.id || route.params.id
			try {
				note.value = await noteService.get(noteId)
			} catch (error) {
				showError(t('alternote', 'Failed to load note'))
				console.error(error)
			}
		}

		const goBack = () => {
			router.push('/')
		}

		onMounted(() => {
			loadNote()
		})

		return {
			note,
			goBack,
			t
		}
	}
}
</script>
