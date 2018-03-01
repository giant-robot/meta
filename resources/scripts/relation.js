/* globals GiantRobot, ajaxurl,
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
        instanceSelector: '.gnt-meta-relation',
        searchFieldSelector: '.gnt-meta-relation__search',
        suggestionsContainerSelector: '.gnt-meta-relation__suggestions-container',
        suggestionsContainerActiveClass: 'is-active',
        suggestionsSelector: '.gnt-meta-relation__suggestions',
        suggestionsLoadingClass: 'is-loading',
        suggestionClass: 'gnt-meta-relation__suggestion',
        suggestionsMoreSelector: '.gnt-meta-relation__more',
        suggestionsEmptySelector: '.gnt-meta-relation__empty',
        selectionsSelector: '.gnt-meta-relation__selections',
        selectionSelector: '.gnt-meta-relation__selection',
        selectionTemplateSelector: '.js-gnt-meta-rel-selection-tpl',
        selectionInputSelector: '.js-gnt-meta-rel-selection-input',
        selectionTitleSelector: '.js-gnt-meta-rel-selection-name',
        selectionRemoveSelector: '.gnt-meta-relation__remove',
        itemsPerPage: 5
    },

    init: function ($fields) {
        "use strict";

        var _this = this;
        var $instances = $fields ? $fields.find(this.config.instanceSelector) : jQuery(this.config.instanceSelector);

        $instances.each(function () {

            var $instance = jQuery(this);

            _this.bind($instance);
        });
    },

    /**
     * Bind actions to events.
     *
     * @param $instance
     */
    bind: function ($instance) {

        var _this = this;

        var $search = $instance.find(this.config.searchFieldSelector);
        var $suggestionsContainer = $instance.find(this.config.suggestionsContainerSelector);
        var $selections = $instance.find(this.config.selectionSelector);

        // Open the search module and get suggestions.
        $search.on('focus', function () {
            $suggestionsContainer.addClass(_this.config.suggestionsContainerActiveClass);
            _this.getSuggestions($instance);
        });

        // Get suggestions on user search.
        $search.on('input', function () {
            _this.getSuggestions($instance);
        });

        // Close the search module.
        $(window).on('click', function (event) {
            var $target = $(event.target);

            if (! $target.hasClass(_this.config.suggestionClass)
                && ! $target.is(_this.config.searchFieldSelector)
                && $suggestionsContainer.hasClass(_this.config.suggestionsContainerActiveClass))
            {
                $suggestionsContainer.removeClass(_this.config.suggestionsContainerActiveClass)
            }
        });

        // Remove stored selections.
        $selections.each(function () {
            var $selection = jQuery(this);
            $selection.find(_this.config.selectionRemoveSelector).on('click', function () {
                $selection.remove();
            });
        });
    },

    /**
     * Get suggestions from the server and show them to the user.
     *
     * @param $instance
     */
    getSuggestions: function ($instance) {

        var _this = this;
        var $searchField = $instance.find(this.config.searchFieldSelector);
        var $suggestionsContainer = $instance.find(this.config.suggestionsContainerSelector);
        var $suggestions = $instance.find(this.config.suggestionsSelector);
        var $moreSuggestionsMessage = $instance.find(this.config.suggestionsMoreSelector);
        var $noSuggestionsMessage = $instance.find(this.config.suggestionsEmptySelector);
        var $selections = $instance.find(this.config.selectionsSelector);
        var $selectionTpl = $instance.find(this.config.selectionTemplateSelector);

        $suggestions.addClass(this.config.suggestionsLoadingClass);
        $moreSuggestionsMessage.hide();
        $noSuggestionsMessage.hide();

        jQuery.ajax({
            url: ajaxurl,
            dataType: 'json',
            delay: 300,
            data: {
                action: 'giant_meta_relation_suggestions',
                mode: $instance.data('mode'),
                filter: $instance.data('filter'),
                s: $searchField.val(),
                limit: _this.config.itemsPerPage,
                _wpnonce: $instance.data('token')
            },
            success: function (response) {

                // Generate a list of suggestions.
                $suggestions.html('');

                jQuery.each(response.data.items, function (key, item) {
                    var $suggestion = jQuery('<div>').text(item.title).addClass(_this.config.suggestionClass);

                    $suggestion.on('click', function () {
                        var $selection = jQuery($selectionTpl.text());

                        $selection.find(_this.config.selectionInputSelector).val(item.id);
                        $selection.find(_this.config.selectionTitleSelector).text(item.title);
                        $selection.find(_this.config.selectionRemoveSelector).on('click', function () {
                            $selection.remove();
                        });

                        if (! $instance.data('multi'))
                        {
                            $selections.find(_this.config.selectionSelector).remove();
                        }

                        $selections.append($selection);
                        $suggestionsContainer.removeClass(_this.config.suggestionsContainerActiveClass);
                    });

                    $suggestions.append($suggestion);
                });

                // Show empty/incomplete list messages.
                if (response.data.count === 0)
                {
                    $noSuggestionsMessage.show();
                }
                if (response.data.count > _this.config.itemsPerPage)
                {
                    $moreSuggestionsMessage.show();
                }
            },
            complete: function () {
                $suggestions.removeClass(_this.config.suggestionsLoadingClass);
            }
        });
    }
};

jQuery(function () {
    "use strict";

    GiantRobot.meta.relation.init();
});
