<?php
add_action('wp_ajax_giant_find_posts', function () {

    check_ajax_referer('giant|find|posts');

    $post_types = get_post_types(array('public' => true), 'objects');
    unset($post_types['attachment']);

    $s = wp_unslash($_POST['ps']);
    $args = array(
        'post_type'      => array_keys($post_types),
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
