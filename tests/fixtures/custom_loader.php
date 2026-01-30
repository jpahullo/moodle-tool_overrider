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
 * Custom loader for testing.
 *
 * @package    tool_overrider
 * @copyright  2026 onwards to Universitat Rovira i Virgili (https://www.urv.cat)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace custom\test;

use tool_overrider\local\config_loader;

/**
 * Custom loader for testing.
 */
class custom_loader implements config_loader {
    /**
     * Load configuration.
     *
     * @param string $filepath Path to file
     * @return array Configuration
     */
    public function load(string $filepath): array {
        return [
            'moodle' => [
                'custom_loaded' => true,
                'custom_value' => 'from_custom_loader',
            ],
        ];
    }

    /**
     * Get supported extensions.
     *
     * @return array Extensions
     */
    public static function get_supported_extensions(): array {
        return ['custom'];
    }
}
