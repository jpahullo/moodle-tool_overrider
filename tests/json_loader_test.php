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

namespace tool_overrider;

use tool_overrider\local\loaders\json_loader;

/**
 * Tests for json_loader class.
 *
 * @package    tool_overrider
 * @copyright  2026 onwards to Universitat Rovira i Virgili <https://www.urv.cat>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \tool_overrider\local\loaders\json_loader
 */
final class json_loader_test extends \basic_testcase {
    /** @var json_loader The loader instance for testing. */
    private json_loader $loader;

    /**
     * Set up test environment.
     */
    protected function setUp(): void {
        parent::setUp();
        $this->loader = new json_loader();
    }

    /**
     * Get fixture file path.
     *
     * @param string $filename Fixture filename
     * @return string Full path to fixture file
     */
    private function get_fixture(string $filename): string {
        return __DIR__ . '/fixtures/' . $filename;
    }

    /**
     * Test loading valid JSON file returns correct array structure.
     */
    public function test_load_valid_json(): void {
        $result = $this->loader->load($this->get_fixture('valid_config.json'));

        $this->assertIsArray($result);
        $this->assertArrayHasKey('moodle', $result);
        $this->assertArrayHasKey('auth_ldap', $result);
        $this->assertArrayHasKey('local_myplug', $result);
        $this->assertTrue($result['moodle']['debug']);
        $this->assertEquals('testdb', $result['moodle']['dbname']);
        $this->assertEquals('localhost', $result['moodle']['dbhost']);
    }

    /**
     * Test loading JSON with nested objects preserves structure.
     */
    public function test_load_nested_json(): void {
        $result = $this->loader->load($this->get_fixture('nested_config.json'));

        $this->assertIsArray($result['moodle']['behat_profiles']);
        $this->assertIsArray($result['moodle']['behat_profiles']['default']);
        $this->assertEquals('firefox', $result['moodle']['behat_profiles']['default']['browser']);
        $this->assertEquals('chrome', $result['moodle']['behat_profiles']['chrome']['browser']);
        $this->assertEquals(3306, $result['moodle']['dboptions']['dbport']);
    }

    /**
     * Test loading JSON preserves all data types correctly.
     */
    public function test_load_json_data_types(): void {
        $result = $this->loader->load($this->get_fixture('data_types.json'));

        $this->assertIsString($result['moodle']['string_value']);
        $this->assertEquals('text', $result['moodle']['string_value']);

        $this->assertIsInt($result['moodle']['int_value']);
        $this->assertEquals(42, $result['moodle']['int_value']);

        $this->assertIsFloat($result['moodle']['float_value']);
        $this->assertEqualsWithDelta(3.14, $result['moodle']['float_value'], 0.001);

        $this->assertTrue($result['moodle']['bool_true']);
        $this->assertFalse($result['moodle']['bool_false']);
        $this->assertNull($result['moodle']['null_value']);

        $this->assertIsArray($result['moodle']['array_value']);
        $this->assertEquals([1, 2, 3], $result['moodle']['array_value']);

        $this->assertEquals('', $result['moodle']['empty_string']);
    }

    /**
     * Test loading JSON with UTF-8 characters.
     */
    public function test_load_json_utf8(): void {
        $result = $this->loader->load($this->get_fixture('utf8_config.json'));

        $this->assertEquals('Test Site with UTF-8: éàüñö', $result['moodle']['sitename']);
        $this->assertEquals('Welcome message: αβγ 中文 한글', $result['moodle']['welcome']);
    }

    /**
     * Test loading empty JSON object returns array with moodle and tool_overrider sections.
     */
    public function test_load_empty_json_object(): void {
        $result = $this->loader->load($this->get_fixture('empty.json'));

        $this->assertIsArray($result);
        // Class json_loader always adds 'moodle' and 'tool_overrider' sections.
        $this->assertArrayHasKey('moodle', $result);
        $this->assertArrayHasKey('tool_overrider', $result);
    }

    /**
     * Test get_supported_extensions returns correct extensions.
     */
    public function test_get_supported_extensions(): void {
        $extensions = json_loader::get_supported_extensions();

        $this->assertIsArray($extensions);
        $this->assertContains('json', $extensions);
        $this->assertCount(1, $extensions);
    }

    /**
     * Test exception when file not found.
     */
    public function test_exception_file_not_found(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('does not exist');

        $this->loader->load('/nonexistent/file.json');
    }

    /**
     * Test exception when JSON is invalid.
     */
    public function test_exception_invalid_json(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('JSON parsing error');

        $this->loader->load($this->get_fixture('invalid_json.json'));
    }

    /**
     * Test exception when JSON is not an array/object.
     */
    public function test_exception_json_not_array(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('is not a valid configuration structure');

        $this->loader->load($this->get_fixture('not_array.json'));
    }

    /**
     * Test that loader adds tool_overrider section with configfile path.
     */
    public function test_loader_adds_configfile_path(): void {
        $result = $this->loader->load($this->get_fixture('valid_config.json'));

        $this->assertArrayHasKey('tool_overrider', $result);
        $this->assertArrayHasKey('configfile', $result['tool_overrider']);
        $this->assertStringContainsString('valid_config.json', $result['tool_overrider']['configfile']);
    }

    /**
     * Test that settings not in a section are moved to moodle section.
     */
    public function test_settings_moved_to_moodle_section(): void {
        $result = $this->loader->load($this->get_fixture('root_settings.json'));

        // Non-section settings should be moved to moodle.
        $this->assertArrayHasKey('root_setting', $result['moodle']);
        $this->assertEquals('value', $result['moodle']['root_setting']);
    }
}
