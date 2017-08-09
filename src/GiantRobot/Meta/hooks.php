<?php
add_action('wp_ajax_giant_meta_find_related', function () {

    check_admin_referer('giant|meta|relate');

    $s = isset($_POST['s']) ? wp_unslash($_POST['s']) : '';
    $mode = isset($_POST['mode']) ? $_POST['mode'] : '';
    $filter = isset($_POST['filter']) ? $_POST['filter'] : '';
    $results = array();

    if ($mode === 'post')
    {
        $postType = empty($filter) ? 'any' : (is_array($filter) ? $filter : explode(',', $filter));

        $args = array(
            'post_type' => $postType,
            'post_status' => 'any',
            'posts_per_page' => 50,
        );

        if ($s)
        {
            $args['s'] = $s;
        }

        $results = array_map(function (\WP_Post $post) {
            return (object) [
                'id' => $post->ID,
                'title' => $post->post_title,
            ];
        }, get_posts($args));
    }
    elseif ($mode === 'user')
    {
        $args = array(
            'number' => 50,
        );

        if ($filter && $filter !== 'any')
        {
            $args['role__in'] = is_array($filter) ? $filter : explode(',', $filter);
        }

        if ($s)
        {
            $args['search'] = $s;
        }

        $results = array_map(function (\WP_User $user) {
            return (object) [
                'id' => $user->ID,
                'title' => $user->display_name,
            ];
        }, get_users($args));
    }
    elseif ($mode === 'term')
    {
        $args = array(
            'number' => 50,
        );

        if ($filter && $filter !== 'any')
        {
            $args['taxonomy'] = is_array($filter) ? $filter : explode(',', $filter);
        }

        if ($s)
        {
            $args['name__like'] = $s;
        }

        $results = array_map(function (\WP_Term $term) {
            return (object) [
                'id' => $term->term_taxonomy_id,
                'title' => $term->name,
            ];
        }, get_terms($args));
    }
    else
    {
        wp_send_json_error();
    }



    wp_send_json_success($results);
});
