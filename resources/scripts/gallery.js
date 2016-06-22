/* globals GiantRobot, wp, frame,
 |
 |------------------------------------------------------------------------------
 | Gallery Field Scripts
 |------------------------------------------------------------------------------
 |
 | A collection of scripts that support the Gallery Field.
 |
 */
GiantRobot.meta.gallery = {

    config: {
        instanceSelector: '.gnt-meta-gallery',
        rowContainerSelector: '.gnt-meta-gallery__items',
        rowSelector: '.gnt-meta-gallery__item',
        rowThumbSelector: '.gnt-meta-gallery__item-thumb',
        rowTemplateSelector: '.gnt-meta-gallery__item-template',
        appendBtnSelector: '.gnt-meta-gallery__item-append',
        removeBtnSelector: '.gnt-meta-gallery__item-remove'
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

                // Use the wp.media library to define the settings for the Media
                // Uploader.
                //
                // We're opting to use the 'post' frame which is a template
                // defined in WordPress core and we are initializing the file frame
                // with the 'insert' state.
                //
                // We're also not allowing the user to select more than one item.
                var frame = wp.media.frames.frame = wp.media({
                    title: 'Select or Upload Media',
                    button: {
                        text: 'Add Selected'
                    },
                    multiple: true
                });

                // Setup an event handler for what to do when an image has been
                // selected.
                //
                // Since we're using the 'view' state when initializing
                // the frame, we need to make sure that the handler is attached
                // to the insert event.
                frame.on('select', function () {

                    // Get selected attachments.
                    var files = frame.state().get('selection').toJSON();
                    var $rows = [];

                    // Generate rows.
                    jQuery.each(files, function(i, file) {

                        var thumbSrc = file.hasOwnProperty('sizes') ? file.sizes.thumbnail.url : file.icon;
                        var $row = _this.getRow($instance);

                        $row.find(':input').val(file.id);
                        $row.find(_this.config.rowThumbSelector).append('<img src="'+thumbSrc+'">' );

                        $rows.push($row);
                    });

                    $instance.find(_this.config.rowContainerSelector).append($rows);
                });

                // Finally, open the media uploader.
                frame.open();
            });

            // Bind actions to the Remove button.
            $instance.on('click', _this.config.removeBtnSelector, function(e){

                e.preventDefault();

                jQuery(this).closest(_this.config.rowSelector).remove();
            });

            // Make items sortable.
            $instance.find(_this.config.rowContainerSelector).sortable();
        });
    },

    /**
     * Clone prototype.
     */
    getRow: function($instance) {

        var template = $instance.find(this.config.rowTemplateSelector).html();
        return jQuery(template);
    }
};

jQuery(function () {
    "use strict";

    GiantRobot.meta.gallery.init();
});
