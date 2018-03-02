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

        if ($this->options('multi'))
        {
            $sanitized = array_filter(array_map(function ($value) {
                return intval($value) ?: null;
            }, $value));
        }
        else
        {
            $sanitized = intval(array_shift($value)) ?: null;
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

            foreach ($values as $key => $id)
            {
                $user = get_userdata($id);

                if (! $user)
                {
                    unset($values[$key]);
                }
                else
                {
                    $titles[$id] = "$user->display_name ($user->user_email)";
                }
            }
        }
        elseif ($filter = $this->options('taxonomy'))
        {
            $mode = 'term';

            foreach ($values as $key => $id)
            {
                $term = get_term_by('term_taxonomy_id', $id);

                if (! $term)
                {
                    unset($values[$key]);
                }
                else
                {
                    $titles[$id] = $term->name;
                }
            }
        }
        else
        {
            $filter = $this->options('post_type', 'any');
            $mode = 'post';

            foreach ($values as $key => $id)
            {
                $post = get_post($id);

                if (! $post)
                {
                    unset($values[$key]);
                }
                else
                {
                    $titles[$id] = $post->post_title;
                }
            }
        }

        return [
            'values' => array_values($values),
            'titles' => $titles,
            'mode' => $mode,
            'filter' => is_array($filter) ? implode(',', $filter) : $filter,
            'token' => wp_create_nonce('giant|meta|relate'),
            'multi' => $this->options('multi', false),
        ];
    }
}
