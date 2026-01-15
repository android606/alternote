/**
 * Nextcloud - Alternote
 *
 * @copyright Copyright (c) 2026, Andrew Mark
 * @license GNU AGPL version 3 or any later version
 */

import { createRouter, createWebHashHistory } from 'vue-router'
import NoteEdit from './views/NoteEdit.vue'
import NoteView from './views/NoteView.vue'

const routes = [
	{
		path: '/',
		name: 'Home',
		component: { template: '<div></div>' }
	},
	{
		path: '/note/edit/:id?',
		name: 'NoteEdit',
		component: NoteEdit,
		props: true
	},
	{
		path: '/note/view/:id',
		name: 'NoteView',
		component: NoteView,
		props: true
	}
]

const router = createRouter({
	history: createWebHashHistory(),
	routes
})

export default router
