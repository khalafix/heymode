<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
?>
<div class="voorodak__wrapper-messages-error">
    <svg width="20" hidden="20" data-slot="icon" aria-hidden="true" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>
    <span class="flex-1">
        <?php esc_html_e($message); ?>
    </span>
</div>

