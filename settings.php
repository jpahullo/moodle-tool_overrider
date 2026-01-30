<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings for tool_overrider.
 *
 * @package    tool_overrider
 * @author     Jordi Pujol Ahulló <jordi.pujol@urv.cat>
 * @copyright  2026 onwards to Universitat Rovira i Virgili <https://www.urv.cat>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('tool_overrider', get_string('pluginname', 'tool_overrider'));

    // Get tool_overrider metadata from $CFG if available.
    $configfile = '';
    $loader = '';
    $overriddensettings = '';

    if (isset($CFG->forced_plugin_settings['tool_overrider'])) {
        $metadata = $CFG->forced_plugin_settings['tool_overrider'];

        if (isset($metadata['configfile'])) {
            $configfile = $metadata['configfile'];
        }

        if (isset($metadata['loader'])) {
            $loader = $metadata['loader'];
        }
    }

    // Build human-readable list of overridden settings.
    if (!empty($configfile) && !empty($loader)) {
        try {
            // Load settings on-demand only when viewing this settings page.
            // This avoids performance overhead on every Moodle request.

            // Instantiate the loader class.
            $loaderinstance = new $loader();
            $alloverrides = $loaderinstance->load($configfile);

            // Use config_formatter to format the output.
            $overriddensettings = \tool_overrider\local\config_formatter::format($alloverrides);
        } catch (Exception $e) {
            $overriddensettings = "Error loading configuration: " . $e->getMessage();
        }
    }

    // Configuration file (read-only).
    $settings->add(new admin_setting_description(
        'tool_overrider/configfile',
        get_string('configfile', 'tool_overrider'),
        get_string('configfile_desc', 'tool_overrider', $configfile ?: get_string('no_config', 'tool_overrider'))
    ));

    // Loader used (read-only).
    $settings->add(new admin_setting_description(
        'tool_overrider/loader',
        get_string('loader', 'tool_overrider'),
        get_string('loader_desc', 'tool_overrider', $loader ?: get_string('no_config', 'tool_overrider'))
    ));

    // Overridden settings (read-only).
    if (!empty($overriddensettings)) {
        $settings->add(new admin_setting_description(
            'tool_overrider/overridden_settings',
            get_string('overridden_settings', 'tool_overrider'),
            get_string('overridden_settings_desc', 'tool_overrider', s($overriddensettings))
        ));
    }

    $ADMIN->add('tools', $settings);
}
