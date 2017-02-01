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
//        $lists = array(
//            array(
//                'Title' => _t('Genealogist.PEOPLE', 'People'),
//                'Items' => $this->getPeople()
//            ),
//            array(
//                'Title' => _t('Genealogist.TAGS', 'Tags'),
//                'Items' => $this->getTags()
//            )
//        );
//        return new ArrayList($lists);
        return null;
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
