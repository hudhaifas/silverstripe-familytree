<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 27, 2017 - 4:36:00 PM
 */
class EventsTask {

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
            switch ($level) {
                case 'all':
                case 'stats':
                case 'dates':
                    $this->indexEvents($person);
                    break;
            }

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
