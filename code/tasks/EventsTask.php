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
            Indexing all people events
            
            Parameters:
                - src: all - index all records, otherwise index only non-indexed records.
                       'ID' - index a specific person
            ";
    protected $enabled = true;

    public function run($request) {
        $startTime = microtime(true);

        $GLOBALS['task_caller'] = true;

        $source = $request->getVar('src');
        $count = 1;

        i18n::set_locale('ar_JO');

        if ($source == 'all') {
            $people = Person::get()->sort('ID');
            $count = $people->count();
        } else if (is_numeric($source)) {
            $people = DataObject::get_by_id('Person', (int) $source);
            $count = 1;
        }

        if (!$people) {
            $this->println('No records to be indexed.');
            return;
        }

        foreach ($people as $index => $person) {
            $this->printProgress($index, $count);

            $this->indexEvents($person);
            $person->write();
        }

        $GLOBALS['task_caller'] = false;

        $taskTime = gmdate("H:i:s", microtime(true) - $startTime);
        $this->println('');
        $this->println("Task is completed in $taskTime");
    }

    private function indexEvents($person) {
        GenealogistEventsHelper::create_all_events($person);
    }

    function println($string_message = '') {
        return isset($_SERVER['SERVER_PROTOCOL']) ? print "$string_message<br />" . PHP_EOL :
                print $string_message . PHP_EOL;
    }

    function printProgress($index, $total) {
        $bs = chr(8);
        $backspaces = '';

        $digitsCount = 0;
        if ($index > 0) {
            $digitsCount = strlen((string) $index);
            $digitsCount += strlen((string) $total);
            $digitsCount += 1;
        }
        for ($i = 0; $i < $digitsCount; $i++) {
            $backspaces .= $bs;
        }

        echo "{$backspaces}" . ($index + 1) . "/{$total}";
    }

}
