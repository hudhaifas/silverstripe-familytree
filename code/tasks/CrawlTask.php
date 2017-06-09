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
 * @version 1.0, Dec 17, 2016 - 10:29:00 PM
 */
class CrawlTask
        extends BuildTask {

    protected $title = 'Crawling people statistics';
    protected $description = "
            Indexing all people statistics and Calculating the estimated birth and death dates
            
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
        $startTime = microtime(true);

        $level = $request->getVar('level');
        $source = $request->getVar('src');
        $count = 1;

        if ($source == 'all') {
            $people = Person::get()->sort('ID');
            $count = $people->count();
        } else if (is_numeric($source)) {
            $people = DataObject::get_by_id('Person', (int) $source);
            $count = 1;
        } else {
            $people = Person::get()->where('StatsID = 0')->sort('ID');
            $count = $people->count();
        }

        if ($level == 'reset') {
            $this->reset($people);
            return;
        }

        foreach ($people as $index => $person) {
            $this->printProgress($index, $count);

            switch ($level) {
                case 'all':
                    $this->indexStats($person);
                    $this->indexName($person);
                    break;

                case 'names':
                    $this->indexName($person);
                    break;

                case 'stats':
                    $this->indexStats($person);
                    break;

                case 'dates':
                    $this->indexDates($person);
                    break;
            }

            $person->write();
        }

        $taskTime = gmdate("H:i:s", microtime(true) - $startTime);
        $this->println('');
        $this->println("Task is completed in $taskTime");
    }

    private function indexName($person) {
        $person->IndexedName = $person->toIndexName();
    }

    private function indexDates($person) {
        if ($person->Stats()->exists() || $person->StatsID) {
            $stats = $person->Stats();
        } else {
            $stats = new PersonalStats();
        }

        $minYear = GenealogistCrawlHelper::calculate_min_person_year($person);
        $stats->MinYear = $minYear;
        $person->BirthYear = $minYear;
        if ($person->IsDead) {
            $stats->MaxYear = GenealogistCrawlHelper::calculate_max_person_year($person, $stats->MinYear);
        }

        $stats->PersonID = $person->ID;
        $stats->write();

        $person->StatsID = $stats->ID;
    }

    private function indexStats($person) {
        if ($person->Stats()->exists() || $person->StatsID) {
            $stats = $person->Stats();
        } else {
            $stats = new PersonalStats();
        }

        $stats->Sons = GenealogistCountersHelper::count_sons($person);
        $stats->Daughters = GenealogistCountersHelper::count_daughters($person);
        $stats->Males = GenealogistCountersHelper::count_males($person);
        $stats->Females = GenealogistCountersHelper::count_females($person);
        $stats->Total = GenealogistCountersHelper::count_descendants($person);
        $stats->LiveSons = GenealogistCountersHelper::count_sons($person, 1);
        $stats->LiveDaughters = GenealogistCountersHelper::count_daughters($person, 1);
        $stats->LiveMales = GenealogistCountersHelper::count_males($person, 1);
        $stats->LiveFemales = GenealogistCountersHelper::count_females($person, 1);
        $stats->LiveTotal = GenealogistCountersHelper::count_descendants($person, 1);

        $ancestors = GenealogistHelper::get_ancestors_ids($person);
        $person->IndexedAncestors = '|' . implode("|", $ancestors) . '|';

        $stats->PersonID = $person->ID;
        $stats->write();

        $person->StatsID = $stats->ID;
    }

    private function reset($people) {
        $this->println('Deleting...');
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
