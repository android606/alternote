/**
 * Nextcloud - Alternote
 * Utility filters/functions
 *
 * @copyright Copyright (c) 2026, Andrew Mark
 * @license GNU AGPL version 3 or any later version
 */

export const capitalize = (str) => {
	if (!str) return ''
	return str.charAt(0).toUpperCase() + str.slice(1)
}

export const stripHtml = (html) => {
	if (!html) return ''
	const tmp = document.createElement('DIV')
	tmp.innerHTML = html
	return tmp.textContent || tmp.innerText || ''
}

export const htmlToPlaintext = (html) => {
	return stripHtml(html)
}

export const objectKeysLength = (obj) => {
	if (!obj) return 0
	return Object.keys(obj).length
}
