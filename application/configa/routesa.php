<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// $route['attendance_list/(:any)/(:any)'] = 'Api/AttendanceList/index/$1/$2';

$route['login'] = 'Api/LoginController/index';
$route['user_list'] = 'Api/UsersList/index';

$route['sites_list'] = 'Api/SitesList/index';

$route['designations_list'] = 'Api/DesignationsList/index';

$route['shift_list'] = 'Api/GetShifts/index';
$route['attendance_list'] = 'Api/AttendanceList/index';
$route['add_user'] = 'Api/AddUser/index';

$route['startdate_leavestatus'] = 'Api/LeaveStatusForStartDate/index';

$route['check_leave_start_date'] = 'Api/CheckLeaveStartDate/index';

$route['check_leave_end_date'] = 'Api/CheckLeaveEndDate/index';

$route['enddate_leavestatus'] = 'Api/LeaveStatusForEndDate/index';

$route['add_schedule'] = 'Api/AddSchedule/index';

$route['schedule_list'] = 'Api/ScheduleList/index';

$route['start_schedule_blocked_dates'] = 'Api/StartScheduleBlockedDates/index';

$route['add_shift'] = 'Api/AddShift/index';

$route['add_leave'] = 'Api/AddLeave/index';

$route['update_leave'] = 'Api/UpdateLeave/index';

$route['delete_leave'] = 'Api/DeleteLeave/index';

$route['approve_leave'] = 'Api/ApproveLeave/index';

$route['disapprove_leave'] = 'Api/DisapproveLeave/index';

$route['leaves_list'] = 'Api/LeavesList/index';

$route['monthly_summary'] = 'Api/MonthlySummary/index';

$route['site_employees/(:any)'] = 'Api/SiteEmployees/index/$1';

$route['site_roles/(:any)'] = 'Api/SiteRoles/index/$1';

$route['site_role_employees/(:any)/(:any)'] = 'Api/SiteRoleEmployees/index/$1/$2';

$route['employees_list_for_filters'] = 'Api/EmployeesListForFilters/index';

$route['api/(:any)']['OPTIONS'] = 'api/$1';


// $route['mobileApi/filtered_site_inventory/(:any)/(:any)'] = 'mobileApi/MobileApiFilteredSiteInventoryController/index/$1/$2';

