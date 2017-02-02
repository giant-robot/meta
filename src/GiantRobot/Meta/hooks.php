<?php
add_action('wp_ajax_giant_find_posts', function () {

    check_admin_referer('giant|find|posts');

    $s = wp_unslash($_POST['ps']);
    $post_type = $_POST['filter'] ? explode(',', $_POST['filter']) : 'any';

    $args = array(
        'post_type'      => $post_type,
        'post_status'    => 'any',
        'posts_per_page' => 50,
    );

    if ('' !== $s)
    {
        $args['s'] = $s;
    }

    $posts = get_posts($args);
    
    wp_send_json_success($posts);
});
