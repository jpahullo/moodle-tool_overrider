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
 * Configuration formatter for displaying settings in human-readable format.
 *
 * @package   tool_overrider
 * @author    Jordi Pujol Ahulló <jordi.pujol@urv.cat>
 * @copyright 2026 onwards to Universitat Rovira i Virgili <https://www.urv.cat>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_overrider\local;

/**
 * Formats configuration data for display.
 */
class config_formatter {
    /**
     * Format configuration data as a human-readable string.
     *
     * @param array $data Configuration data to format
     * @return string Formatted configuration string (JSON format)
     */
    public static function format(array $data): string {
        // Remove tool_overrider metadata before formatting.
        $filtered = [];
        foreach ($data as $component => $values) {
            if ($component !== 'tool_overrider') {
                if (is_array($values)) {
                    ksort($values);
                }
                $filtered[$component] = $values;
            }
        }

        // Sort sections alphabetically, but ensure 'moodle' is always first.
        ksort($filtered);
        if (isset($filtered['moodle'])) {
            $moodle = $filtered['moodle'];
            unset($filtered['moodle']);
            $filtered = ['moodle' => $moodle] + $filtered;
        }

        // Use json_encode with pretty print for clean, consistent output.
        return json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
