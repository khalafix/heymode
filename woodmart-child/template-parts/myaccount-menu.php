<?php if ( ! is_user_logged_in() ) return; ?>

<nav class="myaccount-nav">
    <ul>
        <?php foreach ( myaccount_menu_items() as $key => $item ) : ?>
            <li class="<?php echo myaccount_is_active( $key ) ? 'is-active' : ''; ?>">
                <a href="<?php echo esc_url( $item['url'] ); ?>">
                    <span class="icon icon-<?php echo esc_attr( $item['icon'] ); ?>"></span>
                    <span class="text"><?php echo esc_html( $item['label'] ); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
