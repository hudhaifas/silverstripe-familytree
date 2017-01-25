<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Jan 21, 2017 - 4:07:38 PM
 */
class FiguresPage
        extends DataObjectPage {

    public function canCreate($member = false) {
        if (!$member || !(is_a($member, 'Member')) || is_numeric($member)) {
            $member = Member::currentUserID();
        }

        return (DataObject::get($this->owner->class)->count() > 0) ? false : true;
    }

}

class FiguresPage_Controller
        extends DataObjectPage_Controller {

    protected function getObjectsList() {
        return DataObject::get('Person', "`PublicFigure` = 1 OR `ClassName` = 'Clan'");
//        return DataObject::get('Person');
    }

    protected function getPageLength() {
        return 18;
    }

    protected function searchObjects($list, $keywords) {
//        die('Filterd');
        return $list->filter(array(
                    'IndexedName:PartialMatch' => $keywords
        ));
    }

}