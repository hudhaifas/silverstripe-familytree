<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Dec 17, 2016 - 10:29:00 PM
 */
class IndexingTask
        extends BuildTask {

    protected $title = 'Indexing people full names';
    protected $description = "
            Indexing all people full name for searching purposes
            
            Parameters:
                - level: all - to index all records, otherwise index only non-indexed records.
            ";
    protected $enabled = true;

    public function run($request) {
        $level = $request->getVar('level');

        if ($level == 'all') {
            $people = Person::get();
        } else {
            $people = Person::get()->where('IndexedName IS NULL');
        }

        echo $people->count() . ' records has been indexed.';

        foreach ($people as $person) {
            $person->IndexedName = $person->getFullName();
            $person->write();
        }
    }

}
