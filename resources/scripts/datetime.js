/* globals GiantRobot,
 |
 |------------------------------------------------------------------------------
 | DateTime Field Scripts
 |------------------------------------------------------------------------------
 |
 | A collection of scripts that support the DateTime Field.
 |
 */
GiantRobot.meta.datetime = {

    config: {
        instanceSelector: '.js-gnt-datetime',
        displaySelector: '.js-gnt-datetime__display',
        pickSelector: '.js-gnt-datetime__pick',
        inputSelector: '.js-gnt-datetime__input'
    },

    init: function ($fields) {
        "use strict";

        var _this = this;

        $fields = $fields || jQuery(this.config.instanceSelector);

        $fields.each(function () {

            var $instance = jQuery(this);
            var $display = $instance.find(_this.config.displaySelector);
            var $input = $instance.find(_this.config.inputSelector);

            $input.datetimepicker({
                format: $input.data('save-format'),
                defaultDate: new Date(),
                datepicker: $input.data('datepicker'),
                timepicker: $input.data('timepicker'),
                closeOnDateSelect: ! $input.data('timepicker'),
                onClose: function(datetime) {
                    var formatter = new DateFormatter();
                    $display.text(formatter.formatDate(datetime, $input.data('display-format')));
                }
            });

            $instance.find(_this.config.pickSelector).click(function() {
                $input.datetimepicker('show');
            });
        });
    }
};

jQuery(function () {
    "use strict";

    GiantRobot.meta.datetime.init();
});
