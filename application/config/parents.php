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
| $parents['test.bar']  =  'Foo_Controller'; 
|            |     |                   |
|            |     |                   |
|          ---   ---                 ---
| Test directory   Bar controller   parent controller
|
|
| Description: 
| If router request equal $parent config value then
| "Test directory / Bar controller" will extend
| to Foo Controller (ersin).
|
| 
*/
$parents['test.foo']    =  'Foo_controller';
$parents['test.bar']    =  'Foo_controller';

//-------------------------------------------------------------------------

/* End of file parents.php */
/* Location: .application/config/parents.php */

?>