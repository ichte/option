<?php

namespace XT\Option\Service;


use Ichte\Db\Adapter;
use XT\Option\Model\Group;
use Zend\Db\TableGateway\TableGateway;

class Groups extends TableGateway
{
    /**
     * @param $id
     * @return array|\ArrayObject|Group
     */
    public function find($id) {
        return $this->select(['id' => $id])->current();
    }

    /**
     * @param $id
     * @return boolean
     */
    public function isExist($id) {
        /**
         * @var $dbAdapter Adapter
         */
        $dbAdapter = $this->getAdapter();
        return $dbAdapter->existrow(['id' => $id], $this->getTable());

    }
}