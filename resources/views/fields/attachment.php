<?php
/**
 * Attachment Input Template
 *
 * @var string $id
 * @var string $name
 * @var string $value
 * @var string $kind
 * @var string $thumbnail
 * @var string $title
 * @var string $filename
 * @var string $filesize
 */
?>
<div class="gnt-meta-att">
    <a href="#" class = "button gnt-meta-att__add <?php echo $value ? 'hide' : '' ?>">Add</a>

    <div class="gnt-meta-att__selection <?php echo $value ? '' : 'hide' ?>" data-kind="<?php echo esc_attr($kind) ?>">
        <div class="gnt-meta-att__thumbnail">
            <?php if ($value) : ?>
                <img src="<?php echo esc_url($thumbnail) ?>">
            <?php endif ?>
        </div>
        <div class="gnt-meta-att__info">
            <strong class="js-gnt-meta-att-title"><?php echo $title ?></strong>
            <br>
            <strong>File Name:</strong>
            <span class="js-gnt-meta-att-name"><?php echo $filename ?></span>
            <br>
            <strong>File Size:</strong>
            <span class="js-gnt-meta-att-size"><?php echo $filesize ?></span>
        </div>
        <a href="#" class="gnt-meta-att__remove"></a>
    </div>

    <input type="hidden"
           name="<?php echo esc_attr($name) ?>"
           id="<?php echo esc_attr($id) ?>"
           value="<?php echo esc_attr($value) ?>">
</div>
