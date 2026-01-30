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
 * Interface for configuration file loaders.
 *
 * @package    tool_overrider
 * @author     Jordi Pujol Ahulló <jordi.pujol@urv.cat>
 * @copyright  2026 onwards to Universitat Rovira i Virgili <https://www.urv.cat>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_overrider\local;

/**
 * Interface for configuration file loaders.
 *
 * Each configuration format (INI, JSON, YAML, etc.) should implement
 * this interface to provide a consistent way to load settings.
 */
interface config_loader {
    /**
     * Load configuration from a file.
     *
     * @param string $filepath Path to the configuration file
     * @return array Configuration settings organized by sections
     * @throws \Exception If the file cannot be loaded or parsed
     */
    public function load(string $filepath): array;

    /**
     * Get the file extensions supported by this loader.
     *
     * @return array List of supported file extensions (without dots)
     */
    public static function get_supported_extensions(): array;
}
