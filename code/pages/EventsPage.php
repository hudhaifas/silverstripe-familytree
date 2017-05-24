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

    public function init() {
        parent::init();

        Requirements::css("genealogist/css/events.css");

        if ($this->isRTL()) {
            
        }
    }

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
