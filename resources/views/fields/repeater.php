<?php
/**
 * Repeater Template
 *
 * @var \GiantRobot\Meta\Fields\Field[] $fields
 * @var string $layout
 * @var array  $values
 */
?>
<div class="gnt-meta-repeater gnt-meta-repeater--<?= $layout ?>" data-id="<?php echo esc_attr($id) ?>">

    <template class="gnt-meta-repeater__row-template">
        <div class="gnt-meta-repeater__row">
            <div class="gnt-meta-repeater__row-handle"></div>
            <?php foreach ($fields as $field) : ?>
                <?php
                echo $field->render('', [
                    'name' => "giant_meta[$id][][$field->id]",
                    'disabled' => 'disabled'
                ]);
                ?>
            <?php endforeach; ?>
            <div class="gnt-meta-repeater__row-actions">
                <a class="gnt-meta-repeater__row-insert"></a>
                <a class="gnt-meta-repeater__row-remove"></a>
            </div>
        </div>
    </template>

    <div class="gnt-meta-repeater__rows">
        <input type="hidden" name="giant_meta[<?= esc_attr($id) ?>]" value="">
        <?php foreach ($values as $row_id => $row_values) : ?>
            <div class="gnt-meta-repeater__row">
                <div class="gnt-meta-repeater__row-handle"></div>
                <?php foreach ($fields as $field) : ?>
                    <?php
                    echo $field->render($row_values[$field->id], [
                        'name' => "giant_meta[$id][$row_id][$field->id]"
                    ])
                    ?>
                <?php endforeach; ?>

                <div class="gnt-meta-repeater__row-actions">
                    <a class="gnt-meta-repeater__row-insert"></a>
                    <a class="gnt-meta-repeater__row-remove"></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="gnt-meta-repeater__footer">
        <a class="gnt-meta-repeater__row-append button-primary">New Row</a>
    </div>
</div>
