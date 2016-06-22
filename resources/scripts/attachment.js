/* globals GiantRobot, wp, frame,
 |
 |------------------------------------------------------------------------------
 | Attachment Field Scripts
 |------------------------------------------------------------------------------
 |
 | A collection of scripts that support the Attachment Field.
 |
 */
GiantRobot.meta.attachment = {

    config: {
        fieldSelector: '.gnt-meta-att',
        addButtonSelector: '.gnt-meta-att__add',
        removeButtonSelector: '.gnt-meta-att__remove',
        selectionSelector: '.gnt-meta-att__selection',
        thumbnailContainerSelector: '.gnt-meta-att__thumbnail',
        fileTitleSelector: '.js-gnt-meta-att-title',
        fileNameSelector: '.js-gnt-meta-att-name',
        fileSizeSelector: '.js-gnt-meta-att-size'
    },

    init: function ($fields) {
        "use strict";

        var _this = this;
        $fields = $fields || jQuery(_this.config.fieldSelector);

        $fields.each(function () {

            var $field = jQuery(this);
            var $input = $field.find('input[type="hidden"]:enabled');
            var $addBtn = $field.find(_this.config.addButtonSelector);
            var $removeBtn = $field.find(_this.config.removeButtonSelector);
            var $selection = $field.find(_this.config.selectionSelector);
            var $thumbContainer = $field.find(_this.config.thumbnailContainerSelector);
            var $fileTitle = $field.find(_this.config.fileTitleSelector);
            var $fileName = $field.find(_this.config.fileNameSelector);
            var $fileSize = $field.find(_this.config.fileSizeSelector);

            // Bind attachment insert on add button click.
            $addBtn.off('click').on('click', function () {

                // Use the wp.media library to define the settings for the Media
                // Uploader.
                //
                // We're opting to use the 'post' frame which is a template
                // defined in WordPress core and we are initializing the file frame
                // with the 'insert' state.
                //
                // We're also not allowing the user to select more than one item.
                var frame = wp.media.frames.frame = wp.media({
                    title: 'Select or Upload Attachment',
                    button: {
                        text: 'Add Selected'
                    },
                    multiple: false
                });

                // Setup an event handler for what to do when an image has been
                // selected.
                //
                // Since we're using the 'view' state when initializing
                // the frame, we need to make sure that the handler is attached
                // to the insert event.
                frame.on('select', function () {

                    // Get attachment details.
                    var file = frame.state().get('selection').first().toJSON();

                    // Send the attachment's thumbnail URL to the input field.
                    var src = file.hasOwnProperty('sizes') ? file.sizes.thumbnail.url : file.icon;

                    $thumbContainer.append('<img src="'+src+'">' );
                    $fileTitle.text(file.title);
                    $fileName.text(file.filename);
                    $fileSize.text(file.filesizeHumanReadable);
                    $selection.attr('data-kind', file.type);

                    // Send the attachment id to the hidden input
                    $input.val(file.id);

                    // Hide the add image link
                    $addBtn.addClass('hide');

                    // Show the remove image link
                    $selection.removeClass('hide');
                });

                // Finally, open the media uploader.
                frame.open();

                return false;
            });

            // Bind attachment removal on remove button click.
            $removeBtn.off('click').on('click', function () {

                // Clear attachment info.
                $thumbContainer.empty();
                $fileTitle.empty();
                $fileName.empty();
                $fileSize.empty();
                $input.val('');

                // Hide the selection container.
                $selection.addClass('hide');

                // Show the add image button.
                $addBtn.removeClass('hide');

                return false;
            });
        });
    }
};

jQuery(function () {
    "use strict";

    GiantRobot.meta.attachment.init();
});
