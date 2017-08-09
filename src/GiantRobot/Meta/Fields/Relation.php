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
     * @param mixed $value
     *
     * @return mixed
     */
    protected function sanitizeDefault($value)
    {
        return $value;
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

            foreach ($values as $id)
            {
                $user = get_userdata($id);
                $titles[$id] = $user ? $user->display_name : '';
            }
        }
        elseif ($filter = $this->options('taxonomy'))
        {
            $mode = 'term';

            foreach ($values as $id)
            {
                $term = get_term_by('term_taxonomy_id', $id);
                $titles[$id] = $term ? $term->name : '';
            }
        }
        else
        {
            $filter = $this->options('post_type', 'any');
            $mode = 'post';

            foreach ($values as $id)
            {
                $titles[$id] = get_the_title($id);
            }
        }

        return [
            'values' => $values,
            'titles' => $titles,
            'mode' => $mode,
            'filter' => is_array($filter) ? implode(',', $filter) : $filter,
            'token' => wp_create_nonce('giant|meta|relate')
        ];
    }
}
