<?php

class DataObjectPage
        extends Page {
    
}

class DataObjectPage_Controller
        extends Page_Controller {

    public function index(SS_HTTPRequest $request) {
        $results = $this->getObjectsList();

        if ($keywords = $request->getVar('Keywords')) {
            $results = $this->filterObjects($results, $keywords);
        }

        $paginated = PaginatedList::create(
                        $results, $request
                )->setPageLength($this->getPageLength())
                ->setPaginationGetVar('s');

        $data = array(
            'Results' => $paginated
        );

        if ($request->isAjax()) {
            return $this->customise($data)
                            ->renderWith('ObjectSearchResults');
        }

        return $data;
    }

    protected function getObjectsList() {
        return DataObject::get();
    }

    protected function filterObjects($list, $keywords) {
        return $list->filter(array(
                    'Title:PartialMatch' => $keywords
        ));
    }

    protected function getPageLength() {
        return 15;
    }

    public function ObjectSearchForm() {
        $form = Form::create(
                        $this, 'ObjectSearchForm', FieldList::create(
                                TextField::create('Keywords')
                                        ->setAttribute('placeholder', 'Keywords')
                                        ->addExtraClass('form-control')
                        ), FieldList::create(
                                FormAction::create('doObjectSearch', 'Search')
                                        ->addExtraClass('btn-lg btn-fullcolor')
                        )
        );

        $form->setFormMethod('GET')
                ->setFormAction($this->Link())
                ->disableSecurityToken()
                ->loadDataFrom($this->request->getVars());

        return $form;
    }

}
