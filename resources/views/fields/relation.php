<?php
/**
 * Relation Input Template
 *
 * @var string $id
 * @var string $name
 * @var array  $values
 * @var array  $titles
 * @var string $mode
 * @var string $filter
 * @var string $token
 * @var string $types
 * @var bool   $multi
 */
$template = '
    <li class="gnt-meta-relation__selection">
        <input type="hidden" name="' . esc_attr("{$name}[]") . '" value="%s" class="js-gnt-meta-rel-selection-input" />
        <span class="gnt-meta-relation__remove"></span>
        <span class="js-gnt-meta-rel-selection-name">%s</span>
    </li>';
?>
<div class="gnt-meta-relation" data-token="<?php echo esc_attr($token) ?>" data-mode="<?php echo esc_attr($mode) ?>" data-filter="<?php echo esc_attr($filter) ?>" data-multi="<?php echo $multi ? 'true' : 'false' ?>">
    <input type="hidden" name="<?php echo esc_attr($name) ?>" value="" />
    <ul class="gnt-meta-relation__selections">
        <script type="text/html" class="js-gnt-meta-rel-selection-tpl">
            <?php printf($template, '', ''); ?>
        </script>
        <?php
        foreach ($values as $value)
        {
            printf($template, esc_attr($value), esc_html($titles[$value]));
        }
        ?>
    </ul>
    <input type="text"
           id="<?php echo esc_attr($id) ?>"
           class="gnt-meta-relation__search"
           placeholder="<?php echo esc_attr(__('Click to search...', 'giant-meta')) ?>"
           autocomplete="off"/>
    <div class="gnt-meta-relation__suggestions-container">
        <div class="gnt-meta-relation__suggestions-container-inner">
            <div class="gnt-meta-relation__suggestions">
            </div>
            <div class="gnt-meta-relation__empty">
                <?php echo esc_html(__('There are no results. Try another search.', 'giant-meta')) ?>
            </div>
            <div class="gnt-meta-relation__more">
                <?php echo esc_html(__('There are more results. Try refining your search.', 'giant-meta')) ?>
            </div>
        </div>
    </div>
</div>
