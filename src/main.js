/**
 * Nextcloud - Alternote
 *
 * @copyright Copyright (c) 2026, Andrew Mark
 * @license GNU AGPL version 3 or any later version
 */

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './css/main.css'

const app = createApp(App)
app.use(router)
app.mount('#alternote-app')
