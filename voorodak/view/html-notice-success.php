<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
?>
<div class="voorodak__wrapper-messages-success">
    <svg width="20" hidden="20" data-slot="icon" aria-hidden="true" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>
    <span class="flex-1">
        <?php esc_html_e($message); ?>
    </span>
</div>
