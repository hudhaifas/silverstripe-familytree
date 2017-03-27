<?php

/*
 * MIT License
 *
 * Copyright (c) 2016 Hudhaifa Shatnawi
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 27, 2017 - 4:36:00 PM
 */
class EventsTask
        extends BuildTask {

    protected $title = 'Crawling people events';
    protected $description = "
            Indexing all people events and Calculating the estimated birth and death dates
            
            Parameters:
                - src: all - index all records, otherwise index only non-indexed records.
                       'ID' - index a specific person
                - level: all - index all records, otherwise index only non-indexed records.
                         reset - delete all statistics records
                         names - index names only
                         stats - index statistics only
                         dates - index dates only
            ";
    protected $enabled = true;

    public function run($request) {
        $level = $request->getVar('level');
        $source = $request->getVar('src');
        $count = 1;

        if ($source == 'all') {
            $people = Person::get();
            $count = $people->count();
        } else if (is_numeric($source)) {
            $people = DataObject::get_by_id('Person', (int) $source);
        } else {
            $people = Person::get()->where('StatsID = 0');
            $count = $people->count();
        }

        $this->println($count . ' record(s) to be indexed.');

        if ($level == 'reset') {
            $this->reset($people);
            return;
        }

        foreach ($people as $person) {
            $this->indexEvents($person);
            $person->write();
        }

        $this->println('');
        $this->println('Task is completed');
    }

    private function indexEvents($person) {
//        if ($person->Stats()->exists() || $person->StatsID) {
//            $stats = $person->Stats();
        $this->println('Updating the dates of: ' . $person->Name);
//            echo '.';
//        } else {
//            $stats = new PersonStats();
////            $this->println('Indexing the dates of: ' . $person->Name);
//            echo '.';
//        }

        GenealogistEventsHelper::create_all_events($person);
    }

    function println($string_message = '') {
        return isset($_SERVER['SERVER_PROTOCOL']) ? print "$string_message<br />" . PHP_EOL :
                print $string_message . PHP_EOL;
    }

}
