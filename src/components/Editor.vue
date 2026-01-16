<template>
	<div :id="editorId" :style="{ height: editorHeight + 'px', width: '100%' }"></div>
</template>

<script>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'
import Quill from 'quill'
import 'quill/dist/quill.snow.css'

export default {
	name: 'QuillEditor',
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
		const editorHeight = ref(props.height)
		const editorId = ref('quill-editor-' + Math.random().toString(36).substr(2, 9))
		let quillInstance = null

		const editorConfig = {
			theme: 'snow',
			modules: {
				toolbar: [
					[{ 'header': [1, 2, 3, 4, 5, 6, false] }],
					['bold', 'italic', 'underline', 'strike'],
					[{ 'script': 'sub'}, { 'script': 'super' }],
					[{ 'color': [] }, { 'background': [] }],
					[{ 'align': [] }],
					['blockquote', 'code-block'],
					[{ 'list': 'ordered'}, { 'list': 'bullet' }],
					['link', 'image'],
					['clean']
				]
			},
			placeholder: 'Start writing...',
			readOnly: props.disabled
		}

		const handleUpdate = () => {
			if (quillInstance) {
				const newContent = quillInstance.root.innerHTML
				if (newContent !== content.value) {
					content.value = newContent
					emit('update:modelValue', newContent)
				}
			}
		}

		onMounted(() => {
			editorHeight.value = props.height || window.innerHeight - 200
			
			const editorElement = document.getElementById(editorId.value)
			if (editorElement) {
				quillInstance = new Quill(editorElement, editorConfig)
				
				if (props.modelValue) {
					quillInstance.root.innerHTML = props.modelValue
					content.value = props.modelValue
					
					setTimeout(() => {
						const qlEditor = quillInstance.root
						Array.from(qlEditor.querySelectorAll("*")).forEach(el => {
							if (el.style.textAlign === "center") el.style.textAlign = "left"
							if (el.style.maxWidth && parseFloat(el.style.maxWidth) < qlEditor.offsetWidth * 0.9) el.style.maxWidth = "100%"
							if (el.style.width && parseFloat(el.style.width) < qlEditor.offsetWidth * 0.9) el.style.width = "100%"
						})
					}, 100)
				}
				
				quillInstance.on("text-change", handleUpdate)
				
				if (props.disabled) {
					quillInstance.enable(false)
				}
			}
		})

		watch(() => props.modelValue, (newVal) => {
			if (quillInstance && newVal !== content.value) {
				quillInstance.root.innerHTML = newVal || ''
				content.value = newVal || ''
			}
		})

		watch(() => props.disabled, (newVal) => {
			if (quillInstance) {
				quillInstance.enable(!newVal)
			}
		})

		watch(() => props.height, (newVal) => {
			editorHeight.value = newVal
		})

		onBeforeUnmount(() => {
			if (quillInstance) {
				quillInstance.off('text-change', handleUpdate)
				quillInstance = null
			}
		})

		return {
			content,
			editorHeight,
			editorId
		}
	}
}
</script>

<style scoped>
[id^="quill-editor"] {
	width: 100%;
	display: block;
}
</style>

<style>
.ql-container {
	font-family: Helvetica, Arial, sans-serif;
	font-size: 14px;
	width: 100% !important;
}

.ql-editor {
	min-height: 400px;
	width: 100% !important;
	max-width: 100% !important;
}
</style>

		onMounted(() => {
			editorHeight.value = props.height || window.innerHeight - 200
			
			const editorElement = document.getElementById(editorId.value)
			if (editorElement) {
				quillInstance = new Quill(editorElement, editorConfig)
				
				if (props.modelValue) {
					quillInstance.root.innerHTML = props.modelValue
					content.value = props.modelValue
					
					// Remove inline styles that constrain width
					setTimeout(() => {
						const qlEditor = quillInstance.root
						Array.from(qlEditor.querySelectorAll('*')).forEach(el => {
							if (el.style.textAlign === 'center') {
								el.style.textAlign = 'left'
							}
							if (el.style.maxWidth && parseFloat(el.style.maxWidth) < qlEditor.offsetWidth) {
								el.style.maxWidth = '100%'
							}
							if (el.style.width && parseFloat(el.style.width) < qlEditor.offsetWidth * 0.8) {
								el.style.width = '100%'
							}
						})
					}, 100)
				}
				
				quillInstance.on('text-change', handleUpdate)
				
				if (props.disabled) {
					quillInstance.enable(false)
				}
			}
		})
