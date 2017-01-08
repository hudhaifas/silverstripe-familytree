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
        $level = $request->getVar('level');

        if ($level == 'all') {
            $people = Person::get();
        } else {
            $people = Person::get()->where('StatsID = 0');
//            $people = Person::get()->where('IndexedName IS NULL');
        }

        echo $people->count() . ' records has been indexed.';

        foreach ($people as $person) {
            $this->indexStats($person);

            $person->write();
        }
    }

    private function indexName($person) {
        $person->IndexedName = $person->getFullName();
    }

    private function indexStats($person) {
        if ($person->Stats()->exists()) {
            $stats = $person->Stats();
        } else {
            $stats = new PersonStats();
            $stats->PersonID = $person->ID;
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
        $stats->write();

        $person->StatsID = $stats->ID;
    }

}