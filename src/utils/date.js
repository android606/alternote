/**
 * Nextcloud - Alternote
 * Date formatting utilities
 *
 * @copyright Copyright (c) 2026, Andrew Mark
 * @license GNU AGPL version 3 or any later version
 */

export const formatTimeAgo = (timestamp) => {
	if (!timestamp) return ''
	const seconds = Math.floor((Date.now() - timestamp) / 1000)
	
	if (seconds < 60) return 'just now'
	if (seconds < 3600) return `${Math.floor(seconds / 60)} minutes ago`
	if (seconds < 86400) return `${Math.floor(seconds / 3600)} hours ago`
	if (seconds < 604800) return `${Math.floor(seconds / 86400)} days ago`
	
	const date = new Date(timestamp)
	return date.toLocaleDateString(OC.getLocale())
}

export const formatDate = (timestamp) => {
	if (!timestamp) return ''
	const date = new Date(timestamp)
	return date.toLocaleDateString(OC.getLocale())
}

export const formatDateTime = (timestamp) => {
	if (!timestamp) return ''
	const date = new Date(timestamp)
	return date.toLocaleString(OC.getLocale())
}
