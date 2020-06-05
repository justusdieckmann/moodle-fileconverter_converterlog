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

namespace fileconverter_converterlog;

defined('MOODLE_INTERNAL') || die();

use \core_files\conversion;
use core_files\converter_interface;

class converter implements converter_interface {

    public static function are_requirements_met() {
        return true;
    }

    public function start_document_conversion(conversion $conversion) {
        global $CFG;
        $record = $conversion->to_record();
        $record->filename = $conversion->get_sourcefile()->get_filename();
        $record->filecontent = $conversion->get_sourcefile()->get_content();
        file_put_contents($CFG->dataroot . '/converter.log', "[" . date("H:i:s") . "] " . json_encode($record) . "\n", FILE_APPEND);
        $conversion->set('status', conversion::STATUS_FAILED);
        return $this;
    }

    public function poll_conversion_status(conversion $conversion) {
        return $this;
    }

    public static function supports($from, $to) {
        return true;
    }

    public function get_supported_conversions() {
        return "txt";
    }
}
