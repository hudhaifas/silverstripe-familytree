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

    /**
     * Checks if the user is an authorized member
     * @return boolean true if the user is an authorized member
     */
    public function hasPermission() {
        return GenealogistHelper::is_genealogists();
    }

    protected function getObjectsList() {
        $filter = filter_input(INPUT_GET, 'f');
        $type = filter_input(INPUT_GET, 't');
        $date = filter_input(INPUT_GET, 'd');
        $precision = filter_input(INPUT_GET, 'p');

//        if ($this->hasPermission()) {
//            return DataObject::get('PersonalEvent')
//                            ->sort('RAND()');
//        } else {
//            return DataObject::get('PersonalEvent')
//                            ->filterAny(array(
//                                'PublicFigure' => 1,
//                                'ClassName:StartsWith' => 'Clan',
//                                'ClassName:StartsWith' => 'Tribe',
//                            ))
//                            ->sort('IndexedName ASC');
//        }

//        return GenealogistEventsHelper::get_events_this_year($type, $date, $precision);
        return GenealogistEventsHelper::get_filtered_events($filter, $type, $date, $precision);

//        return null;
    }

    protected function getPageLength() {
        return 24;
    }

    protected function searchObjects($list, $keywords) {
        return GenealogistSearchHelper::search_objects($list, $keywords);
    }

}
