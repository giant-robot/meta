<?php
/**
 * Gallery Template
 *
 * @var string $id
 * @var string $name
 * @var array  $values
 */
?>
<div class="gnt-meta-gallery">

    <template class="gnt-meta-gallery__item-template">
        <div class="gnt-meta-gallery__item">
            <div class="gnt-meta-gallery__item-handle"></div>
            <div class="gnt-meta-gallery__item-thumb"></div>
            <input type="hidden" name="giant_meta[<?php echo esc_attr($id) ?>][]" value="">
            <a class="gnt-meta-gallery__item-remove"></a>
        </div>
    </template>

    <div class="gnt-meta-gallery__items">
        <?php foreach ($values as $index => $item_id) : ?>
            <div class="gnt-meta-gallery__item">
                <div class="gnt-meta-gallery__item-handle"></div>
                <div class="gnt-meta-gallery__item-thumb">
                    <?php echo wp_get_attachment_image($item_id) ?>
                </div>
                <input type="hidden"
                       name="giant_meta[<?php echo esc_attr($id) ?>][<?php echo esc_attr($index) ?>]"
                       value="<?php echo esc_attr($item_id) ?>">
                <a class="gnt-meta-gallery__item-remove"></a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="gnt-meta-gallery__footer">
        <a class="gnt-meta-gallery__item-append button-primary">Add Media</a>
    </div>
</div>
