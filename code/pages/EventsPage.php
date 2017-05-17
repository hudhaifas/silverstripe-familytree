<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, May 8, 2017 - 1:21:48 PM
 */
class EventsPage
        extends DataObjectPage {

    public function canCreate($member = false) {
        if (!$member || !(is_a($member, 'Member')) || is_numeric($member)) {
            $member = Member::currentUserID();
        }

        return (DataObject::get($this->owner->class)->count() > 0) ? false : true;
    }

}

class EventsPage_Controller
        extends DataObjectPage_Controller {

    protected function getObjectsList() {
//        $filter = filter_input(INPUT_GET, 'f');
//        $type = filter_input(INPUT_GET, 't');
//        $date = filter_input(INPUT_GET, 'd');
//        $precision = filter_input(INPUT_GET, 'p');
//
        return null;
    }

    public function Anniversaries($title, $type) {
        $date = filter_input(INPUT_GET, 'd');

        $results = GenealogistEventsHelper::get_events_today($type, $date);

        return $results->Count() ? $this->EventsList($title, $results) : null;
    }

    public function Annuals($title, $type) {
        $date = filter_input(INPUT_GET, 'd');

        $results = GenealogistEventsHelper::get_events_this_year($type, $date);

        return $results->Count() ? $this->EventsList($title, $results) : null;
    }

    public function EventsList($title, $lis) {
        return $this
                        ->customise(array(
                            'ListTitle' => _t('Genealogist.' . $title, ''),
                            'Results' => $lis
                        ))
                        ->renderWith('Event_List');
    }

    protected function getPageLength() {
        return 24;
    }

    protected function searchObjects($list, $keywords) {
        return GenealogistSearchHelper::search_objects($list, $keywords);
    }

}
