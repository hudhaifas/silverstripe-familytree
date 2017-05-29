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
 * @version 1.0, Jan 8, 2017 - 7:51:02 AM
 */
class StatsTask
        extends BuildTask {

    protected $title = 'Indexing people statistics';
    protected $description = "
            Indexing all people statistics
            
            Parameters:
                - level: all - to index all records, otherwise index only non-indexed records.
            ";
    protected $enabled = true;

    public function run($request) {
        $startTime = microtime(true);

        $level = $request->getVar('level');

        if ($level == 'all') {
            $people = Person::get()->limit(120)->sort('ID');
            $count = $people->count();
        } else if ($level == 'reset') {
            $this->reset();
            return;
        } else {
            $people = Person::get()->where('StatsID = 0')->sort('ID');
            $count = $people->count();
        }

        foreach ($people as $index => $person) {
            $this->printProgress($index, $count);

            $this->indexStats($person);
            $this->indexName($person);

            $person->write();
        }

        $taskTime = gmdate("H:i:s", microtime(true) - $startTime);
        $this->println('');
        $this->println("Task is completed in $taskTime");
    }

    private function indexName($person) {
        $person->IndexedName = $person->toIndexName();
    }

    private function indexStats($person) {
        if ($person->Stats()->exists() || $person->StatsID) {
            $stats = $person->Stats();
        } else {
            $stats = new PersonalStats();
        }

        $stats->Sons = GenealogistHelper::count_sons($person);
        $stats->Daughters = GenealogistHelper::count_daughters($person);
        $stats->Males = GenealogistHelper::count_males($person);
        $stats->Females = GenealogistHelper::count_females($person);
        $stats->Total = GenealogistHelper::count_descendants($person);
        $stats->LiveSons = GenealogistHelper::count_sons($person, 1);
        $stats->LiveDaughters = GenealogistHelper::count_daughters($person, 1);
        $stats->LiveMales = GenealogistHelper::count_males($person, 1);
        $stats->LiveFemales = GenealogistHelper::count_females($person, 1);
        $stats->LiveTotal = GenealogistHelper::count_descendants($person, 1);

        $ancestors = GenealogistHelper::get_ancestors_ids($person);
        $person->IndexedAncestors = '|' . implode("|", $ancestors) . '|';

        $stats->PersonID = $person->ID;
        $stats->write();

        $person->StatsID = $stats->ID;
    }

    private function reset() {
        $people = Person::get();
        $this->println('Deleting: ' . $people->Count() . ' person records...');
        foreach ($people as $person) {
            $person->StatsID = 0;
            $person->write();
        }

        $stats = PersonalStats::get();
        $this->println('Deleting: ' . $stats->Count() . ' stats records...');

        foreach ($stats as $stat) {
            $stat->delete();
        }
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
