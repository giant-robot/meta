/* globals GiantRobot, ajaxurl
 |
 |------------------------------------------------------------------------------
 | Relation Field Scripts
 |------------------------------------------------------------------------------
 |
 | A collection of scripts that support the Relation Field.
 |
 */
GiantRobot.meta.relation = {

    config: {
        instanceSelector: '.gnt-meta-relation'
    },

    init: function ($fields) {
        "use strict";
        
        var $instances = $fields ? $fields.find(this.config.instanceSelector) : jQuery(this.config.instanceSelector);

        $instances.each(function () {

            var $instance = jQuery(this);

            $instance.find('select').selectize({
                valueField: 'id',
                labelField: 'title',
                searchField: 'title',
                create: false,
                preload: 'focus',
                render: {
                    option: function(item, escape) {
                        return '<div>' + escape(item.title) + '</div>';
                    }
                },
                load: function(query, callback) {

                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            s: query,
                            action: 'giant_meta_find_related',
                            mode: $instance.data('mode'),
                            filter: $instance.data('filter'),
                            _wpnonce: $instance.data('token')
                        },
                        error: function() {
                            callback();
                        },
                        success: function(res) {
                            callback(res.data);
                        }
                    });
                }
            });
        });
    }
};

jQuery(function () {
    "use strict";

    GiantRobot.meta.relation.init();
});
