<?php
/**
 * User Group Template
 *
 * @var string $id
 * @var string $token
 * @var string $fields
 */
?>
<h2>
    <?php echo esc_html(apply_filters('giant_meta_group_title', '')) ?>
</h2>

<table class="form-table">
    <tbody>
        <tr>
            <th>
                <?php echo esc_html(apply_filters('giant_meta_group_title', '')) ?>
            </th>
            <td class="giant-fields giant-fields--boxed">
                <input type="hidden" name="_giant_meta_tokens[<?php echo esc_attr($id) ?>]" value="<?php echo esc_attr($token) ?>">
                <?php echo $fields ?>
            </td>
        </tr>
    </tbody>
</table>
