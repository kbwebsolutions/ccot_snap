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
 * Test ccot requirements manager
 * @author    Guy Thomas <osdev@blackboard.com>
 * @copyright Copyright (c) 2016 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use theme_ccot\ccot_page_requirements_manager;

/**
 * Class theme_ccot_ccot_page_requirements_manager_test
 * @author    Guy Thomas <osdev@blackboard.com>
 * @copyright Copyright (c) 2016 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_ccot_ccot_page_requirements_manager_test extends \advanced_testcase {

    /**
     * Test clean theme does not black list M.core_completion.init.
     */
    public function test_js_init_call_clean() {
        global $CFG, $PAGE;

        $this->resetAfterTest();

        $CFG->theme = 'clean';
        $PAGE->initialise_theme_and_output();

        $PAGE->requires->js_init_call('M.core_completion.init');

        $endcode = $PAGE->requires->get_end_code();
        $this->assertContains('M.core_completion.init', $endcode);
    }

    /**
     * Test ccot theme black lists M.core_completion.init and excludes the code.
     */
    public function test_js_init_call_ccot() {
        global $CFG, $PAGE;

        $this->resetAfterTest();

        $CFG->theme = 'ccot';
        $PAGE->initialise_theme_and_output();

        $PAGE->requires->js_init_call('M.core_completion.init');

        $endcode = $PAGE->requires->get_end_code();
        $this->assertNotContains('M.core_completion.init', $endcode);
    }

    /**
     * Integration test - Test clean theme does not use ccot page requirements manager.
     */
    public function test_clean_theme_regular_requirements_manager() {
        global $CFG, $PAGE;

        $this->resetAfterTest();

        $CFG->theme = 'clean';

        $PAGE->initialise_theme_and_output();
        $this->assertInstanceOf('page_requirements_manager', $PAGE->requires);
    }

    /**
     * Integration test - Test ccot theme uses ccot page requirements manager.
     */
    public function test_ccot_theme_ccot_requirements_manager() {
        global $CFG, $PAGE;

        $this->resetAfterTest();

        $CFG->theme = 'ccot';

        $PAGE->initialise_theme_and_output();
        // @codingStandardsIgnoreLine
        $this->assertInstanceOf(ccot_page_requirements_manager::class, $PAGE->requires);
    }
}
