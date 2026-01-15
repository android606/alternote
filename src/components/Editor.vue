<template>
	<editor
		:init="editorConfig"
		:disabled="disabled"
		v-model="content"
		@update:modelValue="handleUpdate"
	/>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'
import { generateUrl } from '@nextcloud/router'

export default {
	name: 'TinyMCEEditor',
	components: {
		editor: Editor
	},
	props: {
		modelValue: {
			type: String,
			default: ''
		},
		disabled: {
			type: Boolean,
			default: false
		},
		height: {
			type: Number,
			default: 600
		}
	},
	emits: ['update:modelValue'],
	setup(props, { emit }) {
		const content = ref(props.modelValue)

		const langMapper = {
			'bg': 'bg_BG',
			'cs': 'cs_CZ',
			'fi': 'fi_FI',
			'hu': 'hu_HU',
			'nb': 'nb_NO',
			'sk': 'sk_SK',
			'th': 'th_TH',
			'ja': 'ja_JP',
			'sv': 'sv_SE',
		}

		let locale = 'en'
		if (typeof OC !== 'undefined' && OC.getLocale) {
			locale = OC.getLocale().replace('-', '_')
		}
		if (langMapper.hasOwnProperty(locale)) {
			locale = langMapper[locale]
		}

		const editorHeight = ref(props.height)

		const editorConfig = {
			menubar: false,
			language: locale,
			plugins: [
				'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
				'anchor', 'pagebreak', 'searchreplace', 'wordcount', 'visualblocks',
				'visualchars', 'code', 'fullscreen', 'insertdatetime', 'media',
				'nonbreaking', 'save', 'table', 'contextmenu', 'directionality',
				'emoticons', 'template', 'paste', 'textcolor', 'colorpicker',
				'textpattern', 'imagetools', 'codesample', 'toc', 'help'
			],
			toolbar: 'print fullscreen | undo redo | formatselect fontselect fontsizeselect | ' +
				'bold italic strikethrough subscript superscript | emoticons | ' +
				'forecolor backcolor | alignleft aligncenter alignright alignjustify | ' +
				'bullist numlist table outdent indent | link image | ' +
				'insertdatetime toc | codesample help | code',
			image_advtab: true,
			allow_html_data_urls: true,
			allow_script_urls: true,
			paste_data_images: true,
			width: '100%',
			height: editorHeight.value,
			browser_spellcheck: true,
			autoresize_min_height: editorHeight.value - 140,
			autoresize_max_height: editorHeight.value - 140,
			file_picker_types: 'file image media',
			file_picker_callback: (callback, value, meta) => {
				if (typeof OC === 'undefined' || !OC.dialogs || !OC.dialogs.filepicker) {
					console.warn('OC.dialogs.filepicker not available')
					return
				}
				if (meta.filetype === 'file') {
					OC.dialogs.filepicker('Pick a file', (file) => {
						const filePath = OC.linkToRemote('webdav') + file
						callback(filePath, { text: file })
					})
				} else if (meta.filetype === 'image') {
					OC.dialogs.filepicker('Pick an image', (file) => {
						const allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webm']
						const extension = file.split('.').pop()
						if (allowedExtensions.indexOf(extension) < 0) {
							if (OC.Notification && OC.Notification.showTemporary) {
								OC.Notification.showTemporary('File extension not allowed')
							}
							return
						}
						const filePath = OC.linkToRemote('webdav') + file
						callback(filePath, { alt: file })
					})
				}
			},
			contextmenu: 'print link image inserttable | cell row column deletetable',
			textpattern_patterns: [
				{ start: '*', end: '*', format: 'italic' },
				{ start: '**', end: '**', format: 'bold' },
				{ start: '#', format: 'h1' },
				{ start: '##', format: 'h2' },
				{ start: '###', format: 'h3' },
				{ start: '####', format: 'h4' },
				{ start: '#####', format: 'h5' },
				{ start: '######', format: 'h6' },
				{ start: '1. ', cmd: 'InsertOrderedList' },
				{ start: '* ', cmd: 'InsertUnorderedList' },
				{ start: '- ', cmd: 'InsertUnorderedList' },
			],
			content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
		}

		const handleUpdate = (value) => {
			content.value = value
			emit('update:modelValue', value)
		}

		watch(() => props.modelValue, (newVal) => {
			if (newVal !== content.value) {
				content.value = newVal
			}
		})

		onMounted(() => {
			editorHeight.value = window.innerHeight - 200
		})

		return {
			content,
			editorConfig,
			handleUpdate
		}
	}
}
</script>
