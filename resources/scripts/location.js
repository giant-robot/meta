/* globals GiantRobot, google,
 |
 |------------------------------------------------------------------------------
 | Location Field Scripts
 |------------------------------------------------------------------------------
 |
 | A collection of scripts that support the Location Field.
 |
 */
GiantRobot.meta.location = {

    config: {
        instanceSelector: '.gnt-meta-location',
        latInputSelector: '.js-gnt-meta-location-lat',
        lngInputSelector: '.js-gnt-meta-location-lng',
        canvasSelector: '.js-gnt-meta-location-canvas'
    },

    init: function ($fields) {
        "use strict";

        var _this = this;

        $fields = $fields || jQuery(this.config.instanceSelector);

        $fields.each(function() {
            var $instance = jQuery(this);

            _this.renderMap($instance);
        });
    },

    /**
     * Render callback for Google Maps.
     *
     * Gets executed when API download completes.
     */
    renderMap: function($instance) {

        var $canvas = $instance.find(this.config.canvasSelector);
        var $latInput = $instance.find(this.config.latInputSelector);
        var $lngInput = $instance.find(this.config.lngInputSelector);

        var lat = $latInput.val() ? parseFloat($latInput.val()) : 0;
        var lng = $lngInput.val() ? parseFloat($lngInput.val()) : 0;
        var latLng = new google.maps.LatLng(lat, lng);
        var zoom = $instance.data('zoom') ? $instance.data('zoom') : 12;

        // Create a map.
        var map = new google.maps.Map($canvas.get(0), {
            zoom: zoom,
            center: latLng,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                position: google.maps.ControlPosition.BOTTOM_CENTER
            },
            scrollwheel: false
        });

        // Draw a marker and lat/lng input fields when it is moved.
        var marker = new google.maps.Marker({
            map: map,
            position: latLng,
            draggable: true
        });

        google.maps.event.addListener(marker, 'dragend', function() {

            map.setCenter(marker.getPosition());
            $latInput.val(marker.getPosition().lat());
            $lngInput.val(marker.getPosition().lng());
        });

        // Setup a location search input.
        var $searchInput = jQuery('<input type="text">');

        // Disable "enter" to avoid accidental page submissions.
        $searchInput.keypress(function (event) {
            if (event.which == '13')
            {
                return false;
            }
        });

        // Add the input element to the map controls.
        map.controls[google.maps.ControlPosition.TOP_LEFT].push($searchInput.get(0));

        // Create a new Autocomplete instance from the search input.
        // Then bind the Autocomplete instance to the map and add a listener
        // that updates the map marker position and the lat/lng input fields
        // when a suggestion is selected.
        var autocomplete = new google.maps.places.Autocomplete($searchInput.get(0));

        autocomplete.bindTo('bounds', map);

        autocomplete.addListener('place_changed', function() {

            var place = autocomplete.getPlace();

            // Bail if the place does not carry geometry information.
            if (! place.geometry)
            {
                alert("The location you searched for could not be found.");
                return false;
            }

            // Center the map.
            if (place.geometry.viewport)
            {
                map.fitBounds(place.geometry.viewport);
            }
            else
            {
                map.setCenter(place.geometry.location);
                map.setZoom(17); // Why 17? Because it looks good.
            }

            // Update marker position and lat/lng fields.
            marker.setPosition(place.geometry.location);
            $latInput.val(place.geometry.location.lat());
            $lngInput.val(place.geometry.location.lng());
        });
    }
};

jQuery(function ($) {
    "use strict";

    var $fields = $(GiantRobot.meta.location.config.instanceSelector);
    var gmapsLoaded = typeof google === 'object' && google.hasOwnProperty('maps') && google.maps.hasOwnProperty('places');
    var apiKey = $fields.data('key');

    if ($fields.length && ! gmapsLoaded)
    {
        jQuery.getScript('//maps.googleapis.com/maps/api/js?key='+apiKey+'&libraries=places&callback=GiantRobot.meta.location.init');
    }
});
