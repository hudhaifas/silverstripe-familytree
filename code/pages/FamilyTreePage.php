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
class FamilyTreePage
        extends Page {
    
}

class FamilyTreePage_Controller
        extends Page_Controller {

    private static $allowed_actions = array(
        'info',
        'town',
    );
    private static $url_handlers = array(
        'person-info/$ID' => 'info',
        'town/$action' => 'town',
        '$ID/$town' => 'index',
    );

    public function init() {
        parent::init();

        Requirements::css("familytree/css/jquery.jOrgChart.css");
        Requirements::css("familytree/css/familytree.css");
        Requirements::javascript("familytree/js/jquery.jOrgChart.js");
        Requirements::javascript("familytree/js/dragscroll.js");
        Requirements::javascript("familytree/js/script.js");
    }

    public function index(SS_HTTPRequest $request) {
        $id = $this->getRequest()->param('ID');
        $town = $this->getRequest()->param('town');

        if ($id) {
            $root = DataObject::get_by_id('Person', (int) $id);
        } else {
            $root = $this->getRootClans();
        }

        $data = array(
            'Clans' => $root,
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
        $person = DataObject::get_by_id('Person', (int) $id);

        return $person->renderWith("Side_Info");
    }

    public function getDBVersion() {
        return DB::get_conn()->getVersion();
    }

    public function getClans() {
        return Clan::get();
    }

    public function getTowns() {
        return Town::get();
    }

    public function getPerson($id) {
        return DataObject::get_by_id('Person', (int) $id);
    }

    public function getRootClans() {
        return Clan::get()->filter(array('FatherID' => 0));
    }

}