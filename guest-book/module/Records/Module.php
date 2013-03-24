<?php
namespace Records;

use Records\Model\Records;
use Records\Model\RecordsTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Records\Model\RecordsTable' =>  function($sm) {
                    $tableGateway = $sm->get('RecordsTableGateway');
                    $table = new RecordsTable($tableGateway);
                    return $table;
                },
                'RecordsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Records());
                    return new TableGateway('records', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}