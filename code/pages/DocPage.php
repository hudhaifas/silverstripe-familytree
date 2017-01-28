<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Jan 22, 2017 - 9:18:56 PM
 */
class DocPage
        extends DataObjectPage {

    public function canCreate($member = false) {
        if (!$member || !(is_a($member, 'Member')) || is_numeric($member)) {
            $member = Member::currentUserID();
        }

        return (DataObject::get($this->owner->class)->count() > 0) ? false : true;
    }

}

class DocPage_Controller
        extends DataObjectPage_Controller {

    protected function getObjectsList() {
        return DataObject::get('DocumentFile', "`IsPrivate` = 0");
    }

    protected function getPageLength() {
        return 24;
    }

    protected function searchObjects($list, $keywords) {
        return $list->filterAny(array(
                    'Title:PartialMatch' => $keywords,
                    'Description:PartialMatch' => $keywords,
                    'Texts:PartialMatch' => $keywords,
        ));
    }

    protected function getFiltersList() {
        $lists = array(
            array(
                'Title' => _t('Genealogist.PEOPLE', 'People'),
                'Items' => $this->getPeople()
            ),
            array(
                'Title' => _t('Genealogist.TAGS', 'Tags'),
                'Items' => $this->getTags()
            )
        );
        return new ArrayList($lists);
//        return null;
    }

    private function getPeople() {
        $people = array();
        foreach ($this->getObjectsList() as $doc) {
            foreach ($doc->People() as $person) {
                $people[] = array(
                    'Title' => $person->getTitle(),
                    'Link' => $person->getObjectLink(),
                );
            }
        }

        $result = new ArrayList($people);
        $result->removeDuplicates();

        return $result;
    }

    private function getTags() {
        $tags = array();
        foreach ($this->getObjectsList() as $doc) {
            foreach ($doc->Tags() as $tag) {
                $tags[] = array(
                    'Title' => $tag->getTitle(),
                );
            }
        }

        $result = new ArrayList($tags);
        $result->removeDuplicates();

        return $result;
    }

}
