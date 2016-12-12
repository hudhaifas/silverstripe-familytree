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
 * @version 1.0, Nov 2, 2016 - 2:45:38 PM
 */
class GenealogyPage
        extends Page {

    private static $has_many = array(
        'Roots' => 'Person'
    );
    private static $icon = "genealogist/images/genealogy.png";

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Roots', GridField::create(
                        'Roots', //
                        'Roots', //
                        $this->Roots(), //
                        GridFieldConfig_RecordEditor::create() //
        ));

        return $fields;
    }

    public function canCreate($member = false) {
        if (!$member || !(is_a($member, 'Member')) || is_numeric($member)) {
            $member = Member::currentUserID();
        }

        return (DataObject::get($this->owner->class)->count() > 0) ? false : true;
    }

}

class GenealogyPage_Controller
        extends Page_Controller {

    private static $allowed_actions = array(
        'info',
        'giveinfo',
    );
    private static $url_handlers = array(
        'person-info/$ID' => 'info',
        'giveinfo/$ID' => 'giveinfo',
    );

    public function init() {
        parent::init();

        Requirements::css("genealogist/css/jquery.jOrgChart.css");
        Requirements::css("genealogist/css/genealogy.css");
        Requirements::css("genealogist/css/genealogy-rtl.css");

        Requirements::javascript("genealogist/js/jquery.jOrgChart.js");
        Requirements::javascript("genealogist/js/jquery.dragscroll.js");
        Requirements::javascript("genealogist/js/jquery.fullscreen.js");
//        Requirements::javascript("genealogist/js/html2canvas.js");
        Requirements::javascript("genealogist/js/genealogy.js");
    }

    public function index(SS_HTTPRequest $request) {
        $id = $this->getRequest()->param('ID');
        $town = $this->getRequest()->param('town');

        if ($id) {
            $root = DataObject::get_by_id('Person', (int) $id);
        } else {
            $root = $this->Roots()->first();
        }

        $data = array(
            'Clans' => $root,
//            'Title' => $root->Name,
        );

        if ($request->isAjax()) {
            return $this
                            ->customise($data)
                            ->renderWith('TheTree');
        }

        return $data;
    }

    public function info() {
        $id = $this->getRequest()->param('ID');
        $person = GenealogistHelper::get_person($id);

        return $person->renderWith("Side_Info");
    }

    public function giveinfo() {
        $id = $this->getRequest()->param('ID');

        if ($id) {
            $person = DataObject::get_by_id('Person', (int) $id);
        } else {
            $person = $this->getClans()->first();
        }

        if ($person) {
            return $this
                            ->customise(array(
                                'Person' => $person,
                                'Title' => $person->Name
                            ))
                            ->renderWith(array('GenealogistPage_Edit', 'Page'));
        } else {
            return $this->httpError(404, 'That book could not be found!');
        }
        
        
        $id = $this->getRequest()->param('ID');
        $person = GenealogistHelper::get_person($id);

        return $person->renderWith("Side_Info");
    }

    public function getDBVersion() {
        return DB::get_conn()->getVersion();
    }

    public function getClans() {
        return GenealogistHelper::get_all_clans();
    }

    public function getTowns() {
        return Town::get();
    }

    public function getPerson($id) {
        return GenealogistHelper::get_person($id);
    }

    public function getRootClans() {
        return GenealogistHelper::get_root_clans();
    }

}