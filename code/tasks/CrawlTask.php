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

        $this->println('');
        $this->println('Task is completed');
    }

    private function indexName($person) {
        $person->IndexedName = $person->toIndexName();
        $this->println('Indexing the name of: ' . $person->IndexedName);
    }

    private function indexDates($person) {
        if ($person->Stats()->exists() || $person->StatsID) {
            $stats = $person->Stats();
            $this->println('Updating the dates of: ' . $person->Name);
            echo '.';
        } else {
            $stats = new PersonStats();
            $this->println('Indexing the dates of: ' . $person->Name);
            echo '.';
        }

        $stats->MinYear = GenealogistCrawlHelper::calculate_min_person_year($person);
        $stats->MaxYear = GenealogistCrawlHelper::calculate_max_person_year($person, $stats->MinYear);
        $stats->PersonID = $person->ID;
        $stats->write();

        $person->StatsID = $stats->ID;
    }

    private function indexStats($person) {
        if ($person->Stats()->exists() || $person->StatsID) {
            $stats = $person->Stats();
            $this->println('Updating the stats of: ' . $person->Name);
            echo '.';
        } else {
            $stats = new PersonStats();
            $this->println('Indexing the stats of: ' . $person->Name);
            echo '.';
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

        $stats = PersonStats::get();
        $this->println('Deleting: ' . $stats->Count() . ' stats records...');

        foreach ($stats as $stat) {
            $stat->delete();
        }
    }

    function println($string_message = '') {
        return isset($_SERVER['SERVER_PROTOCOL']) ? print "$string_message<br />" . PHP_EOL :
                print $string_message . PHP_EOL;
    }

}
