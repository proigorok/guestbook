<?php
namespace Records\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class RecordsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($field, $order)
    {
        $this->field = $field;
        $this->order = $order;
        $resultSet = $this->tableGateway->select(function (Select $select) {
        $select->columns(array('date', 'name', 'email', 'homepage', 'text', 'image', 'file'));
        $select->order($this->field.' '.$this->order);        
        });
        $resultSet->buffer();
        $resultSet->next();

        return $resultSet;
    }

    public function saveRecord(Records $records)
    {
        $data = array(
            'name' => $records->name,
            'email'  => $records->email,
            'homepage'  => $records->homepage,
            'text'  => $records->text,
            'image'  => $records->image['tmp_name'],
            'file'  => $records->file['tmp_name'],
            'ip'  => $records->ip,
            'browser'  => $records->browser,
        );
        $this->tableGateway->insert($data);        
    }
}