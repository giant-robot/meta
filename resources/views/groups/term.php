<?php
/**
 * Term Group Template
 *
 * @var string $id
 * @var string $token
 * @var string $fields
 */
?>
<tr class="form-field term-<?php echo esc_attr($id) ?>-wrap">
    <th>
        <label><?php echo esc_html(apply_filters('giant_meta_group_title', '')) ?></label>
    </th>
    <td class="giant-fields">
        <input type="hidden" name="_giant_meta_tokens[<?php echo esc_attr($id) ?>]" value="<?php echo esc_attr($token) ?>">
        <?php echo $fields ?>
    </td>
</tr>
