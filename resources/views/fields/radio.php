<?php
/**
 * Radio Input Template
 *
 * @var string $id
 * @var string $name
 * @var array  $choices
 * @var mixed  $value
 * @var string $layout
 */
?>
<div class="gnt-meta-radio">
    <input type="hidden" name="<?php echo esc_attr($name) ?>" value="" />
<?php foreach ($choices as $choice_val => $choice_title) : ?>
    <label class="gnt-meta-radio__choice gnt-meta-radio__choice--<?php echo esc_attr($layout) ?>">
        <input type="radio"
               name="<?php echo esc_attr($name) ?>"
               value="<?php echo esc_attr($choice_val) ?>"
               <?php checked($choice_val, $value) ?>
        ><?php echo $choice_title ?>
    </label>
<?php endforeach ?>

</div>
