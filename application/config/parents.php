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
| Global Controller Names - ( Directory Extends )
|--------------------------------------------------------------------------
| You can assign Global Controllers to Folders.
|
| Forexample 
| If router requests "index.php/rest/" match with directory
| regex (rest) then the "all" Controllers which is located in rest folder 
| will extend to Global Rest_controller .
|
*/
$parents['directory']['rest']        = 'Rest_controller';

/*
|--------------------------------------------------------------------------
| Global Controller Names - ( Controller Extends )
|--------------------------------------------------------------------------
| All global controllers located in application/parents folder.
| To create the parent controller you must define controller
| name here. 
|
| Description:
 
| If router requests "index.php/members/signup_form" match with controller
| regex (_form$) then the "all" Controllers which has subrefix "_form" will 
| extend to Global Form_controller .
|
| Warning !: A Global Controller name writing style must be like
| this "Foo_controller";
| 
*/
$parents['controller']['(_form$)']   =  'Form_controller';
$parents['controller']['(blabla_*)'] =  'Blabla_controller';

                                     
//-------------------------------------------------------------------------

/* End of file parents.php */
/* Location: .application/config/parents.php */