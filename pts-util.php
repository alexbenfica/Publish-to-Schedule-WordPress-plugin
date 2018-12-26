<?php


# return the actual version of this plugin
function pts_get_version() {
    $plugin_data = get_plugin_data(__FILE__);
    $plugin_version = $plugin_data['Version'];
    return $plugin_version;
}

