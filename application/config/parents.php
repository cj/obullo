<?php 
defined('BASE') or exit('Access Denied!'); 
/*
| -------------------------------------------------------------------------
| USER DEFINED PARENT CONTROLLERS
| -------------------------------------------------------------------------
| This file 
|
| Please see the user guide for complete details:
|
| Chapters / General Topics / Global Controllers
|
| -------------------------------------------------------------------------
| RESERVED PARENT NAMES
| -------------------------------------------------------------------------
| Global Controller is your DEFAULT parent controller. All controllers 
| should be parent of the Global Controller except the your 
| special controllers like rest controller etc ..
|
|  Reserved Name          Reserved Parent Name
|  o Global_Controller    parent::__global();
|  o Core_Controller
|
*/

/*
|--------------------------------------------------------------------------
| Global Controller Names - ( Extend Switch )
|--------------------------------------------------------------------------
| All global controllers located in application/parents folder.
| To create the parent controller you must define controller
| name here. 
| 
| Example     
|                  - extends -
|
| $parents['test.bar']  =  'Foo_controller'; 
|            |     |                   |
|            |     |                   |
|          ---   ---                 ---
| Test directory   Bar controller   parent controller  (Global Controller)
|
|
| Description:
 
| If router request "index.php/test/foo" equal to $parents['test.foo'] value
| then the "Bar" Controller which located in "Test" directory, will extend
| to Foo controller .
|
| Warning !: A Global Controller name writing style must be like
| this "Foo_controller";
| 
*/
$parents['test.bar']     =  'Foo_controller';
$parents['rest.xml']     =  'Rest_controller';
$parents['codebullo.*']  =  'Codebullo_controller';

//-------------------------------------------------------------------------

/* End of file parents.php */
/* Location: .application/config/parents.php */
?>