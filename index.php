<?php
    require_once './CommonCrudController.php';

    $crudObj = new CommonCrudController();

    $formData = [];
    $formData['UserName']     = 'suman';
    $formData['Password']    = 'abc@123';
    $formData['status'] = '1';

     //$crudObj->create('member', $formData);
    
    //$crudObj->update('member', $formData, "id=4");

    //$crudObj->delete('member',"id=6");

   $crudObj->select('member','UserName',null,null,'order by UserName asc');
  
    $crudObj->debug($crudObj->resultDisplay());
   