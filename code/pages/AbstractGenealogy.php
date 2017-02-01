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
 * @version 1.0, Dec 28, 2016 - 4:31:43 PM
 */
class AbstractGenealogy
        extends Page {

    private static $group_code = 'genealogists';
    private static $group_title = 'Genealogists';
    private static $group_permission = 'CMS_ACCESS_CMSMain';
    private static $co_group_code = 'co-genealogists';
    private static $co_group_title = 'Co-Genealogists';

    public function canCreate($member = false) {
        return false;
    }

    protected function onBeforeWrite() {
        parent::onBeforeWrite();
        $this->getUserGroup();
        $this->getCoUserGroup();
    }

    /**
     * Returns/Creates the genealogists group to assign CMS access.
     *
     * @return Group Librarians group
     */
    protected function getUserGroup() {
        $code = $this->config()->group_code;

        $group = Group::get()->filter('Code', $code)->first();

        if (!$group) {
            $group = new Group();
            $group->Title = $this->config()->group_title;
            $group->Code = $code;

            $group->write();

            $permission = new Permission();
            $permission->Code = $this->config()->group_permission;

            $group->Permissions()->add($permission);
        }

        return $group;
    }

    /**
     * Returns/Creates the genealogists group to assign CMS access.
     *
     * @return Group Librarians group
     */
    protected function getCoUserGroup() {
        $code = $this->config()->co_group_code;

        $group = Group::get()->filter('Code', $code)->first();

        if (!$group) {
            $group = new Group();
            $group->Title = $this->config()->co_group_title;
            $group->Code = $code;

            $group->write();
        }

        return $group;
    }

}

class AbstractGenealogy_Controller
        extends Page_Controller {

    /// Utils ///
    public function getDBVersion() {
        return DB::get_conn()->getVersion();
    }

    public function getClans() {
        return GenealogistHelper::get_all_clans();
    }

    public function getPerson($id) {
        return GenealogistHelper::get_person($id);
    }

    public function getRootClans() {
        return GenealogistHelper::get_root_clans();
    }

    /**
     * Checks if the user is an authorized member
     * @return boolean true if the user is an authorized member
     */
    public function hasPermission() {
        return GenealogistHelper::is_genealogists();
    }

}
