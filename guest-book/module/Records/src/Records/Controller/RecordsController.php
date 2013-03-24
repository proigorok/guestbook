<?php
namespace Records\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Records\Model\Records;
use Records\Form\RecordsForm;
use Zend\Paginator;

class RecordsController extends AbstractActionController
{
    protected $recordsTable;
    public function indexAction()
    {
        $sortForm = new \Records\Form\SortingForm();        
        $request = $this->getRequest();
        
        if ($request->isGet()){
            $sortData = $request->getQuery()->toArray();
            $sortForm->setData($sortData);
            $sortForm->setValidationGroup(\Zend\Form\FormInterface::VALIDATE_ALL);
            if($sortForm->isValid()) {
                $sortForm->getData($sortData);
                $field = $sortData['field'];
                $order = $sortData['order'];
            }
            else {
            $sortForm->getData($sortData);
            $field = 'date';
            $order = 'desc';
            }
        }
        $query = $this->getRecordsTable()->fetchAll($field, $order);
        $paginator = new Paginator\Paginator(new Paginator\Adapter\Iterator($query));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page', 1));
        $paginator->setItemCountPerPage(25);

        $vm = new ViewModel(array('records' => $paginator));

        $form = new RecordsForm();

        if ($request->isPost()) {
            $records = new Records();            
            $form->setInputFilter($records->getInputFilter());
            $data = array_merge_recursive(
                $request->getPost()->toArray(),          
                $request->getFiles()->toArray()
            );
            $form->setData($data);

            if ($form->isValid()) {
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setDestination(PUBLIC_PATH.'/files');
                $records->checkImage($data['image']);
                $records->exchangeArray($form->getData());
                $this->getRecordsTable()->saveRecord($records);
                
                return $this->redirect()->refresh();
            }
        }
        $vm->setVariables(array('form' => $form, 'sortForm' => $sortForm));
                
        return $vm;
    }
    public function getRecordsTable()
    {
        if (!$this->recordsTable) {
            $sm = $this->getServiceLocator();
            $this->recordsTable = $sm->get('Records\Model\RecordsTable');
        }
        return $this->recordsTable;
    }
}