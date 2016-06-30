<?php
/**
 * Location Input Template
 *
 * @var string $id
 * @var string $name
 * @var string $key
 * @var int    $zoom
 * @var array  $center
 */
?>
<div class="gnt-meta-location" data-key="<?= esc_attr($key) ?>" data-zoom="<?= esc_attr($zoom) ?>">

    <input type="hidden"
           class="js-gnt-meta-location-lat"
           name="<?php echo esc_attr($name) ?>[lat]"
           value="<?php echo esc_attr($center['lat']) ?>">

    <input type="hidden"
           class="js-gnt-meta-location-lng"
           name="<?php echo esc_attr($name) ?>[lng]"
           value="<?php echo esc_attr($center['lng']) ?>">

    <div class = "gnt-meta-location__canvas js-gnt-meta-location-canvas"></div>
</div>
