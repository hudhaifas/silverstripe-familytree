<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, May 25, 2017 - 11:25:46 AM
 */
class TwonsPage
        extends DataObjectPage {

    private static $group_code = 'towns';
    private static $group_title = 'Towns';
    private static $icon = "genealogist/images/icn-town.png";


    public function canCreate($member = false) {
        if (!$member || !(is_a($member, 'Member')) || is_numeric($member)) {
            $member = Member::currentUserID();
        }

        return (DataObject::get($this->owner->class)->count() > 0) ? false : true;
    }

}

class TwonsPage_Controller
        extends DataObjectPage_Controller {

    public function init() {
        parent::init();

//        Requirements::css("genealogist/css/towns.css");

        if ($this->isRTL()) {
            
        }
    }

    protected function getObjectsList() {
        return DataObject::get('Town')
                        ->filterByCallback(function($record) {
                            return $record->canView();
                        })->sort('RAND()');
    }

    protected function getPageLength() {
        return 24;
    }

    protected function searchObjects($list, $keywords) {
        return $list->filterAny(array(
                    'DefaultName:PartialMatch' => $keywords,
                    'TownID' => $keywords,
                    'Latitude' => $keywords,
                    'Longitude' => $keywords,
        ));
    }

}
