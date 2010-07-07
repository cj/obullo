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
| $parents['test']  =  array('bar'      =>      'Foo_controller');
|            |                |                          |
|            |                |                          |
|          ---               ---                        ---
| Test directory        Bar controller  (extends to)   Global Controller
|
|
| Description:
 
| If router request equal to "index.php/test/bar"
| then the "Bar" Controller will extend
| to Global Foo controller .
|
| Warning !: A Global Controller name writing style must be like
| this "Foo_controller";
| 
*/
$parents['test']          =  array('bar'  => 'Foo_controller');

/*
|--------------------------------------------------------------------------
| Global Controller Names - ( Directory Extends )
|--------------------------------------------------------------------------
| You can assign Global Controllers to Folders.
| Forexample You have a Folder called /Dummy
| when you use the '*' symbol , all of the controllers of
| /Dummy folder will extend to Dummy Global Controller.
|
*/
$parents['directoryname'] = array(
                                     '*'    => 'Dummy_controller',  // default
                                   );
                                     
//-------------------------------------------------------------------------

/* End of file parents.php */
/* Location: .application/config/parents.php */
?>