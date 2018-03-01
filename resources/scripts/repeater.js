/* globals GiantRobot, wp, frame,
 |
 |------------------------------------------------------------------------------
 | Repeater Field Scripts
 |------------------------------------------------------------------------------
 |
 | A collection of scripts that support the Repeater Field.
 |
 */
GiantRobot.meta.repeater = {

    config: {
        instanceSelector: '.gnt-meta-repeater',
        rowContainerSelector: '.gnt-meta-repeater__rows',
        rowSelector: '.gnt-meta-repeater__row',
        rowTemplateSelector: '.gnt-meta-repeater__row-template',
        rowHandleSelector: '.gnt-meta-repeater__row-handle',
        appendBtnSelector: '.gnt-meta-repeater__row-append',
        insertBtnSelector: '.gnt-meta-repeater__row-insert',
        removeBtnSelector: '.gnt-meta-repeater__row-remove',
        mediaRepeaterClass: 'gnt-meta-repeater--media'
    },

    init: function ($fields) {
        "use strict";

        var _this = this;

        $fields = $fields || jQuery(this.config.instanceSelector);

        $fields.each(function () {

            var $instance = jQuery(this);

            // Bind actions to the Append button.
            $instance.find(_this.config.appendBtnSelector).on('click', function(e){

                e.preventDefault();

                var $row = _this.getRow($instance);

                $instance.find(_this.config.rowContainerSelector).append($row);
            });

            // Bind actions to the Insert button.
            $instance.on('click', _this.config.insertBtnSelector, function(e){

                e.preventDefault();

                var $row = _this.getRow($instance);

                $row.insertBefore(jQuery(this).closest(_this.config.rowSelector));
            });

            // Bind actions to the Remove button.
            $instance.on('click', _this.config.removeBtnSelector, function(e){

                e.preventDefault();

                jQuery(this).closest(_this.config.rowSelector).remove();
            });

            // Make rows sortable.
            $instance.find(_this.config.rowContainerSelector).sortable({
                handle: _this.config.rowHandleSelector
            });
        });
    },

    /**
     * Clone prototype.
     */
    getRow: function($instance) {

        var template = $instance.find(this.config.rowTemplateSelector).html();
        var $row = jQuery(template);
        var id = this.rand();

        // Process sub-fields.
        $row.find('.giant-field').each(function() {

            var $this  = jQuery(this);
            var $input = $this.find(':input');

            // Some fields have templates and those templates have input fields.
            // We need to work on them as well.
            var $templateTag = $this.find('script[type="text/html"]');
            var $template = jQuery($templateTag.text());
            var $templateInput = $template.find(':input');

            // Refactor field input names so that all fields are
            // submitted in the name of the repeater.
            $input.add($templateInput).each(function () {
                var $in = jQuery(this);
                var name = $in.attr('name');

                if (name)
                {
                    var newname = name.replace('[' + $instance.data('id') + '][]', '[' + $instance.data('id') + '][' + id + ']');
                    $in.attr('name', newname);
                }
            });

            // Replace the template!
            $templateTag.text(jQuery('<div>').append($template).html());

            // Attach field-specific JS.
            var fieldType = jQuery(this).data('type');

            if (GiantRobot.meta[fieldType])
            {
                GiantRobot.meta[fieldType].init(jQuery(this));
            }
        });

        return $row;
    },

    /**
     * Create a random string.
     */
    rand: function(length) {

        length = length || 8;

        var text  = '';
        var chars = "abcdefghijklmnopqrstuvwxyz0123456789";

        for (var i=0; i < length; i++)
        {
            text += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        return text;
    }
};

jQuery(function () {
    "use strict";

    GiantRobot.meta.repeater.init();
});
