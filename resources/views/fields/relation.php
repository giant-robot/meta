<?php
/**
 * Relation Input Template
 *
 * @var string $id
 * @var string $name
 * @var array  $values
 */
?>
<div class="gnt-meta-relation" data-token="<?php echo wp_create_nonce('giant|find|posts') ?>">
    <input type="hidden" name="<?php echo esc_attr($name) ?>" value="" />
    <select id="<?php echo esc_attr($id) ?>" name="<?php echo esc_attr($name) ?>">
        <?php foreach ($values as $id) : ?>
            <option value="<?php echo esc_attr($id) ?>" selected>
                <?php echo get_the_title($id) ?>
            </option>
        <?php endforeach ?>
    </select>
</div>
