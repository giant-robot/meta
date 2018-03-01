<?php
/**
 * Register an AJAX action that fetches post/user/term suggestions for the
 * Relation field.
 *
 * This action is not available to "non-privileged" users.
 */
add_action('wp_ajax_giant_meta_relation_suggestions', function () {

    check_admin_referer('giant|meta|relate');

    $mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
    $search = isset($_REQUEST['s']) ? wp_unslash($_REQUEST['s']) : '';
    $filter = isset($_REQUEST['filter']) ? explode(',', $_REQUEST['filter']) : [];
    $offset = isset($_REQUEST['offset']) ? intval($_REQUEST['offset']) : 0;
    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 100;
    $items = array();
    $count = 0;

    if ($mode === 'post')
    {
        // Save a million seconds in query times by hooking this callback to the
        // post_search filter. That will have WP only searching in post titles.
        // Remove right after the query is performed to avoid screwing up the
        // search logic of the rest of the system.
        $searchOnlyPostTitles = function ($whereClauseSql, WP_Query $query) {

            global $wpdb;
            $terms = isset($query->query_vars['search_terms']) ? $query->query_vars['search_terms'] : array();
            $parts = array();

            foreach ($terms as $term)
            {
                $parts[] = $wpdb->prepare(
                    "$wpdb->posts.post_title LIKE %s",
                    '%' . $wpdb->esc_like($term) . '%'
                );
            }

            if ($parts)
            {
                $whereClauseSql = ' AND (' . implode(' OR ', $parts) . ') ';
            }

            return $whereClauseSql;
        };

        add_filter('posts_search', $searchOnlyPostTitles, 10, 2);

        $postQuery = new WP_Query([
            'post_type' => $filter ?: 'any',
            's' => $search,
            'post_status' => 'any',
            'offset' => $offset,
            'posts_per_page' => $limit,
        ]);

        // Remembered to remove the filter in time! Yay!
        remove_filter('posts_search', $searchOnlyPostTitles, 10);

        $items = array_map(function (WP_Post $post) {
            return array(
                'id' => $post->ID,
                'title' => $post->post_title,
            );
        }, $postQuery->posts);

        $count = $postQuery->found_posts;
    }
    elseif ($mode === 'user')
    {
        $userQuery = new WP_User_Query([
            'role__in' => $filter === ['any'] ? [] : $filter,
            'search' => "*$search*",
            'search_columns' => ['display_name', 'user_email'],
            'offset' => $offset,
            'number' => $limit,
        ]);

        $items = array_map(function (WP_User $user) {
            return array(
                'id' => $user->ID,
                'title' => "$user->display_name ($user->user_email)",
            );
        }, $userQuery->get_results());

        $count = $userQuery->get_total();
    }
    elseif ($mode === 'term')
    {
        $termQuery = new WP_Term_Query([
            'taxonomy' => $filter === array('any') ? array() : $filter,
            'name__like' => $search,
            'offset' => $offset,
            'number' => $limit,
        ]);

        // Yep! WP_Term_Query does NOT return the total amount of terms found.
        $termCountQuery = new WP_Term_Query([
            'taxonomy' => $filter === array('any') ? array() : $filter,
            'name__like' => $search,
            'fields' => 'count',
            'count' => true,
        ]);

        $items = array_map(function (WP_Term $term) {
            return array(
                'id' => $term->term_taxonomy_id,
                'title' => $term->name,
            );
        }, $termQuery->terms);

        // Isn't this the most counter-intuitive way to do this?
        $count = $termCountQuery->get_terms();
    }
    else
    {
        wp_send_json_error();
    }

    wp_send_json_success([
        'items' => $items,
        'count' => $count,
    ]);
});
