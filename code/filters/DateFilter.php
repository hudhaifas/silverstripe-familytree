<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Nov 11, 2016 - 8:32:54 AM
 */
abstract class DateFilter
        extends PartialMatchFilter {

    protected function getDate() {
        $date = new DateTime($this->getValue());

        return array(
            'd' => $date->format('d'),
            'm' => $date->format('m'),
            'y' => $date->format('Y')
        );
    }

    protected function applyOne(\DataQuery $query) {
        return $this->applyOneDate($query);
    }

    abstract protected function applyOneDate(DataQuery $query);
}