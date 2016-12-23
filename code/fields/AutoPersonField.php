<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Dec 22, 2016 - 9:57:02 PM
 */
class AutoPersonField
        extends AutoCompleteField {

    /**
     * @var array
     */
    private static $allowed_actions = array(
        'Suggest'
    );

    /**
     * Handle a request for an Autocomplete list.
     *
     * @param SS_HTTPRequest $request The request to handle.
     *
     * @return string A JSON list of items for Autocomplete.
     */
    public function Suggest(SS_HTTPRequest $request) {
        // Find class to search within
        $sourceClass = $this->determineSourceClass();

        if (!$sourceClass) {
            return;
        }

        // Find field to search within
        $sourceFields = $this->getSourceFields();

        // input
        $q = $request->getVar('term');
        $limit = $this->getLimit();

        $filters = array();

//        foreach (preg_split('/[\s,]+/', $q) as $keyword) {
        foreach ($sourceFields as $sourceField) {
            $filters["{$sourceField}:PartialMatch"] = $q;
        }
//        }
        // Generate query
        $query = DataList::create($sourceClass)
                ->filterAny($filters)
                ->sort($this->sourceSort)
                ->limit($limit);

        if ($this->sourceFilter) {
            $query = $query->where($this->sourceFilter);
        }

        // generate items from result
        $items = array();

        foreach ($query as $record) {
            $items[$record->{$this->storedField}] = array(
                'label' => $record->{$this->labelField},
                'value' => $record->{$this->displayField},
                'stored' => $record->{$this->storedField}
            );
        }

        $items = array_values($items);

        // the response body
        return json_encode($items);
    }

}
