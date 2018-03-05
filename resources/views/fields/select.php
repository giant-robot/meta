<?php
/**
 * Select Input Template
 *
 * @var string $id
 * @var string $name
 * @var array  $choices
 * @var array  $values
 * @var bool   $multi
 */
?>
<div class="gnt-meta-select">
    <select id="<?php echo esc_attr($id) ?>" name="<?php echo esc_attr("{$name}[]") ?>" <?php echo $multi ? 'multiple' : '' ?>>
    <?php foreach ($choices as $choice_val => $choice_title) : ?>
        <option value="<?php echo esc_attr($choice_val) ?>" <?php selected(true, in_array($choice_val, $values)) ?>>
            <?php echo esc_html($choice_title) ?>
        </option>
    <?php endforeach ?>
    </select>
</div>
