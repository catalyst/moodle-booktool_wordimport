<?php
// This file is part of Moodle - http://moodle.org/
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Import Microsoft Word file - menu configuration.
 *
 * @package    booktool_wordimport
 * @copyright  2016 Eoin Campbell
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Adds module specific settings to the settings block
 *
 * @param settings_navigation $settings The settings navigation object
 * @param navigation_node $node The node to add module settings to
 */
function booktool_wordimport_extend_settings_navigation(settings_navigation $settings, navigation_node $node) {
    global $PAGE;

    if (empty($PAGE->cm) or $PAGE->cm->modname !== 'book') {
        return;
    }

    $params = $PAGE->url->params();
    if (empty($params['id']) and empty($params['cmid'])) {
        return;
    }

    if (empty($PAGE->cm->context)) {
        $PAGE->cm->context = get_context_module::instance($PAGE->cm->instance);
    }

    if (!(has_capability('booktool/wordimport:import', $PAGE->cm->context) and
        has_capability('mod/book:edit', $PAGE->cm->context))) {
        return;
    }

    // Configure Import link, and pass in the current chapter in case the insert should happen here rather than at the end.
    $url1 = new moodle_url('/mod/book/tool/wordimport/index.php',
            array('id' => $PAGE->cm->id, 'chapterid' => $params['chapterid']));
    $node->add(get_string('importchapters', 'booktool_wordimport'), $url1, navigation_node::TYPE_SETTING, null, null,
            new pix_icon('f/document', '', 'moodle', array('class' => 'iconsmall', 'title' => '')));

    // Configure Export links for book and current chapter.
    $url2 = new moodle_url('/mod/book/tool/wordimport/index.php', array('id' => $PAGE->cm->id, 'action' => 'export'));
    $node->add(get_string('exportbook', 'booktool_wordimport'), $url2, navigation_node::TYPE_SETTING,
            null, null, new pix_icon('f/document', '', 'moodle', array('class' => 'iconsmall', 'title' => '')));

    $url3 = new moodle_url('/mod/book/tool/wordimport/index.php',
            array('id' => $PAGE->cm->id, 'chapterid' => $params['chapterid'], 'action' => 'export'));
    $node->add(get_string('exportchapter', 'booktool_wordimport'), $url3, navigation_node::TYPE_SETTING,
            null, null, new pix_icon('f/document', '', 'moodle', array('class' => 'iconsmall', 'title' => '')));
}