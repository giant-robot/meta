<?php
namespace GiantRobot\Meta\Fields;

/**
 * Relation
 */
class Relation extends Field
{
    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'relation';

    /**
     * Default sanitization method, used when the sanitize option is not set.
     *
     * @param array $value
     *
     * @return int|int[]|null
     */
    protected function sanitizeDefault($value)
    {
        // Input should always be an array.
        // If not, somebody's been monkeying around with it.
        if (! is_array($value))
        {
            return null;
        }

        $sanitizeOne = function ($value) {

            if (is_numeric($value) && $value > 0 && intval($value) == $value)
            {
                return intval($value);
            }

            return null;
        };

        if ($this->options('multi'))
        {
            $sanitized = array_filter(array_map($sanitizeOne, $value));
        }
        else
        {
            $sanitized = $sanitizeOne(array_shift($value));
        }

        return $sanitized;
    }

    /**
     * Gather field-specific view data.
     *
     * @param mixed $value
     *
     * @return array
     */
    protected function viewData($value = null)
    {
        $values = $value;
        $choices = array();
        $titles = array();

        if (! $value)
        {
            $values = array();
        }
        elseif (! is_array($values))
        {
            $values = array($value);
        }

        if ($filter = $this->options('user_role'))
        {
            $mode = 'user';
            $users = get_users([
                'include' => $values ?: [0],
                'number' => -1,
                'orderby' => 'include',
            ]);

            /* @var \WP_User[] $users */
            foreach ($users as $user)
            {
                $choices[] = $user->ID;
                $titles[$user->ID] = "$user->display_name ($user->user_email)";
            }
        }
        elseif ($filter = $this->options('taxonomy'))
        {
            $mode = 'term';
            $terms = get_terms([
                'term_taxonomy_id' => $values ?: [0],
                // TODO: Sort results in the same order they're passed to term_taxonomy_id.
            ]);

            /* @var \WP_Term[] $terms */
            foreach ($terms as $term)
            {
                $choices[] = $term->term_taxonomy_id;
                $titles[$term->term_taxonomy_id] = $term->name;
            }
        }
        else
        {
            $filter = $this->options('post_type', 'any');
            $mode = 'post';
            $posts = get_posts([
                'post_type' => $filter,
                'include' => $values ?: [0],
                'numberposts' => -1,
                'orderby' => 'post__in',
            ]);

            /* @var \WP_Post[] $posts */
            foreach ($posts as $post)
            {
                $choices[] = $post->ID;
                $titles[$post->ID] = $post->post_title;
            }
        }

        return [
            'values' => $choices,
            'titles' => $titles,
            'mode' => $mode,
            'filter' => is_array($filter) ? implode(',', $filter) : $filter,
            'token' => wp_create_nonce('giant|meta|relate'),
            'multi' => $this->options('multi', false),
        ];
    }
}
