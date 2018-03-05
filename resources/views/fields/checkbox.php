<?php
/**
 * Radio Input Template
 *
 * @var string $id
 * @var string $name
 * @var array  $choices
 * @var array  $values
 * @var string $layout
 */
?>
<div class="gnt-meta-checkbox">
    <input type="hidden" name="<?php echo esc_attr($name) ?>" value="" />
    <?php foreach ($choices as $choice_val => $choice_title) : ?>
        <label class="gnt-meta-checkbox__choice gnt-meta-checkbox__choice--<?php echo esc_attr($layout) ?>">
            <input type="checkbox"
                   name="<?php echo esc_attr($name) ?>[]"
                   value="<?php echo esc_attr($choice_val) ?>"
                <?php checked(true, in_array($choice_val, $values)) ?>
            ><?php echo $choice_title ?>
        </label>
    <?php endforeach ?>
</div>
