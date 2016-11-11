<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Nov 11, 2016 - 8:32:02 AM
 */
class AnnualFilter
        extends DateFilter {

    protected function applyOneDate(DataQuery $query) {
        $this->model = $query->applyRelation($this->relation);

        $yearClause = 'YEAR(' . $this->getDbName() . ')';

        $date = $this->getDate();

        return $query->where(array(
                    $yearClause => $date['y'],
        ));
    }

}