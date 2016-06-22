<?php
/**
 * Field Template
 *
 * @var string $id
 * @var string $type
 * @var mixed  $value
 * @var string $label
 * @var string $description
 */
?>
<div class="giant-field" data-type="<?php echo esc_attr($type) ?>" data-id="<?php echo esc_attr($id) ?>">
    <?php if (isset($label)) : ?>
        <div class="giant-field__label">
            <label for="<?php echo esc_attr($id) ?>"><?php echo $label ?></label>
        </div>
    <?php endif ?>
    <?php if (isset($description)) : ?>
        <small class="giant-field__description"><?php echo $description ?></small>
    <?php endif ?>
    <div class="giant-field__input">
        <?php include "$type.php" ?>
    </div>
</div>
