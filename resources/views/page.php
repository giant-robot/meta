<?php
/**
 * @var string $id
 * @var string $title
 * @var string $description
 */
?>
<div class="wrap">

    <h2><?= esc_html($title) ?></h2>

    <?= apply_filters('the_content', $description) ?>

    <form id="poststuff" method="post">
        <?php do_meta_boxes($id, 'normal', $id); ?>
        <?php submit_button(); ?>
    </form>

</div>
