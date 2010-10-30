<?php 
defined('BASE') or exit('Access Denied!');

/*
| -------------------------------------------------------------------------
| USER DEFINED GLOBAL CONTROLLERS (GC)
| -------------------------------------------------------------------------
| Please see the user guide for complete details:
|
| Chapters / General Topics / Global Controllers
|
*/

/*
|--------------------------------------------------------------------------
| Global Controller Names - ( Directory Extends )
|--------------------------------------------------------------------------
| You can assign Global Controllers to Folders.
|
| Description:
|
| If there is any regex match like "index.php/rest/" with directory
| then the "all" Controllers which is located in rest folder 
| will extend to Global Rest_controller .
|
*/
$parents['directory']['rest']       = 'Rest_controller';

/*
|--------------------------------------------------------------------------
| Global Controller Names - ( Controller Extends )
|--------------------------------------------------------------------------
| All global controllers located in application/parents folder.
| To create the parent controller you must define controller
| name here. 
|
| Warning : A Global Controller name writing style must be like
| this "Foo_controller";
|
| Description:
|
| If router requests "index.php/members/signup_form" match with regex (_form$)
| extend rules then the "all" Controllers which has suffix "_form" will 
| extend to Global Form_controller .
|
| Examples:
|
|  
|   $parents['controller']['(_form$)']   =  'Form_controller';
|                |                                  |
|   the controller name suffix which is "_foo" will extend to Global "Form_controller"
|    
|   
|   $parents['controller']['(bar_*)']    =  'Bar_controller';
|               |                                   |
|   the controller name prefix which is "bar_" will extend to Global "Bar_controller"
| 
*/
$parents['controller']['(_form$)']   =  'Form_controller';
$parents['controller']['(bar_*)']    =  'Bar_controller';



/* End of file parents.php */
/* Location: .application/config/parents.php */