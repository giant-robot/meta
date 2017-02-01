<?php
/**
 * DateTime Input Template
 *
 * @var string $id
 * @var string $name
 * @var string $value
 * @var string $save_format
 * @var string $display_format
 * @var string $show_datepicker
 * @var string $show_timepicker
 */
?>
<div class="gnt-meta-datetime js-gnt-datetime" style="position: relative;">

    <span class="js-gnt-datetime__display">
        <?php echo esc_html(date($display_format, strtotime($value))) ?>
    </span>
    <i class="dashicons dashicons-calendar js-gnt-datetime__pick"></i>

    <input type="text"
           class="js-gnt-datetime__input"
           name="<?php echo esc_attr($name) ?>"
           id="<?php echo esc_attr($id) ?>"
           value="<?php echo esc_attr($value) ?>"
           data-datepicker="<?php echo esc_attr($show_datepicker ? 'true' : 'false') ?>"
           data-timepicker="<?php echo esc_attr($show_timepicker ? 'true' : 'false') ?>"
           data-save-format="<?php echo esc_attr($save_format) ?>"
           data-display-format="<?php echo esc_attr($display_format) ?>"
           style="visibility: hidden;">
</div>
