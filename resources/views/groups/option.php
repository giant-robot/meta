<?php
/**
 * Options Group Template
 *
 * @var string $id
 * @var string $token
 * @var string $fields
 */
?>
<div id="<?php echo esc_attr($id) ?>" class="giant-fields">
    <input type="hidden" name="_giant_meta_tokens[<?php echo esc_attr($id) ?>]" value="<?php echo esc_attr($token) ?>">
    <?php echo $fields ?>
</div>
