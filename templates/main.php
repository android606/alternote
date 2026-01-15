<?php
/**
 * Nextcloud - Alternote
 *
 * @copyright Copyright (c) 2026, Andrew Mark
 * @license GNU AGPL version 3 or any later version
 */

\OCP\Util::addStyle('core', 'icons');
\OCP\Util::addStyle('files_trashbin', 'trash');
\OCP\Util::addScript('alternote', 'js/main');
\OCP\Util::addStyle('alternote', 'css/main');
?>
<input type="hidden" name="nextNonce" id="nextNonce" value="<?php p($_['nonce']) ?>" />
<div id="alternote-app"></div>
<script nonce="<?php p($_['nonce']) ?>">
	window.shareMode = <?php p($_['shareMode']) ?>;
	window.app_config = <?php print_unescaped(json_encode($_['config'])) ?>;
</script>
