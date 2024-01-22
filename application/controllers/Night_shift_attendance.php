<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Night_shift_attendance extends CI_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function __construct()
    {
        parent::__construct();
        $this->load->model('NightShiftAttendanceModel');
    }

	public function index()
	{
		$nightAttendance = new NightShiftAttendanceModel;
		// $rows = $nightAttendance->get_night_shift_schedule();
		$rows = $nightAttendance->get_night_shift_schedule();
		$insertion_data = [];
		$single_person_data = [];
		foreach($rows as $row){
			// query employee attendance records
			$emp_night_data =	$nightAttendance->check_employee_attendance_records($row['bio_id']);
			// echo "<pre>"; print_r($emp_night_data);
			//check if selected employee record didn't found in tbl_night_attendance
			if(empty($emp_night_data)){
				// echo $row['bio_id'].' '.$row['schedule_start'].''.$row['schedule_end'].'<br>';
				// query to fetch employee records from checkinout table
				// $ref_id = '';
				$allCheckinoutEmpData =	$nightAttendance->get_all_employee_records_from_checkinout($row['bio_id'],$row['schedule_start'],$row['schedule_end']);
				// echo "<pre>"; print_r($allCheckinoutEmpData);
				// $empRecords = count($allCheckinoutEmpData);
				foreach($allCheckinoutEmpData as $key => $value){
						if($key == 0){
							$prevIndexDate = date('Y-m-d', strtotime($value['CheckDate'] . ' -1 day'));
						}else{
							$prevIndexDate = $allCheckinoutEmpData[$key-1]['CheckDate'];
						}
					// if($key < $empRecords-1){
							if($prevIndexDate < $value['CheckDate']){
								if($value['CheckTime'] < '12:00:00' && $value['CheckDate']>$row['schedule_start']){
									$previousDay = date('Y-m-d', strtotime($value['CheckDate'] . ' -1 day'));
									// query to fetch checkout record
									$single_person_data['ref_id'] = $value['ref_id'];
									$single_person_data['schedule_id'] = $row['id'];
									$single_person_data['fullname'] = $row['fullname'];
									$single_person_data['bio_id'] = $value['userid'];
									$single_person_data['attendance_date'] = $previousDay;
									$single_person_data['check_time'] = $allCheckinoutEmpData[$key]['CheckDate'].' '.$value['CheckTime'];
									$insertion_data[] = $single_person_data;
								}
								if($value['CheckTime'] > '12:00:00' ){
									$single_person_data['ref_id'] = $value['ref_id'];
									$single_person_data['schedule_id'] = $row['id'];
									$single_person_data['fullname'] = $row['fullname'];
									$single_person_data['bio_id'] = $value['userid'];
									$single_person_data['attendance_date'] = $allCheckinoutEmpData[$key]['CheckDate'];
									$single_person_data['check_time'] = $allCheckinoutEmpData[$key]['CheckDate'].' '.$value['CheckTime'];
									$insertion_data[] = $single_person_data;
								}
							}

							if($prevIndexDate == $value['CheckDate']){
								if($value['CheckTime'] < '12:00:00' && $value['CheckDate']>$row['schedule_start']){
									// query to fetch checkout record
									$empCheckoutData =	$nightAttendance->get_checkout_data($row['bio_id'],$value['CheckDate'],$row['schedule_end']);
									$previousDay = date('Y-m-d', strtotime($value['CheckDate'] . ' -1 day'));
									$single_person_data['ref_id'] = $value['ref_id'];
									$single_person_data['schedule_id'] = $row['id'];
									$single_person_data['fullname'] = $row['fullname'];
									$single_person_data['bio_id'] = $value['userid'];
									$single_person_data['attendance_date'] = $previousDay;
									$single_person_data['check_time'] = $allCheckinoutEmpData[$key]['CheckDate'].' '.$empCheckoutData[0]['CheckTime'];
									$insertion_data[] = $single_person_data;
								}
								if($value['CheckTime'] > '12:00:00'){
									$single_person_data['ref_id'] = $value['ref_id'];
									$single_person_data['schedule_id'] = $row['id'];
									$single_person_data['fullname'] = $row['fullname'];
									$single_person_data['bio_id'] = $value['userid'];
									$single_person_data['attendance_date'] = $allCheckinoutEmpData[$key]['CheckDate'];
									$single_person_data['check_time'] = $allCheckinoutEmpData[$key]['CheckDate'].' '.$value['CheckTime'];
									$insertion_data[] = $single_person_data;
								}
							}
					// }
				}
			}else{
				// query to fetch employee records within limit from checkinout table
				$inlimitCheckinoutEmpData =	$nightAttendance->get_inlimit_employee_records_from_checkinout($emp_night_data[0]['bio_id'],$emp_night_data[0]['attendance_date'],$emp_night_data[0]['check_time'],$row['schedule_start'],$row['schedule_end']);
				echo "<pre>"; print_r($inlimitCheckinoutEmpData);
				// $empRecords = count($inlimitCheckinoutEmpData); exit;
					foreach($inlimitCheckinoutEmpData as $key => $value){
						if($key == 0){
							$prevIndexDate = date('Y-m-d', strtotime($value['CheckDate'] . ' -1 day'));
						}else{
							$prevIndexDate = $inlimitCheckinoutEmpData[$key-1]['CheckDate'];
						}
							if($prevIndexDate < $value['CheckDate']){
								if($value['CheckTime'] < '12:00:00' && $value['CheckDate']>$row['schedule_start']){
									$previousDay = date('Y-m-d', strtotime($value['CheckDate'] . ' -1 day'));
									// query to fetch checkout record
									$single_person_data['ref_id'] = $value['ref_id'];
									$single_person_data['schedule_id'] = $row['id'];
									$single_person_data['fullname'] = $row['fullname'];
									$single_person_data['bio_id'] = $value['userid'];
									$single_person_data['attendance_date'] = $previousDay;
									$single_person_data['check_time'] = $inlimitCheckinoutEmpData[$key]['CheckDate'].' '.$value['CheckTime'];
									$insertion_data[] = $single_person_data;
								}
								if($value['CheckTime'] > '12:00:00' ){
									$single_person_data['ref_id'] = $value['ref_id'];
									$single_person_data['schedule_id'] = $row['id'];
									$single_person_data['fullname'] = $row['fullname'];
									$single_person_data['bio_id'] = $value['userid'];
									$single_person_data['attendance_date'] = $inlimitCheckinoutEmpData[$key]['CheckDate'];
									$single_person_data['check_time'] = $inlimitCheckinoutEmpData[$key]['CheckDate'].' '.$value['CheckTime'];
									$insertion_data[] = $single_person_data;
								}
							}

							if($prevIndexDate == $value['CheckDate']){
								if($value['CheckTime'] < '12:00:00' && $value['CheckDate']>$row['schedule_start']){
									// query to fetch checkout record
									$empCheckoutData =	$nightAttendance->get_checkout_data($row['bio_id'],$value['CheckDate'],$row['schedule_end']);
									$previousDay = date('Y-m-d', strtotime($value['CheckDate'] . ' -1 day'));
									$single_person_data['ref_id'] = $value['ref_id'];
									$single_person_data['schedule_id'] = $row['id'];
									$single_person_data['fullname'] = $row['fullname'];
									$single_person_data['bio_id'] = $value['userid'];
									$single_person_data['attendance_date'] = $previousDay;
									$single_person_data['check_time'] = $inlimitCheckinoutEmpData[$key]['CheckDate'].' '.$empCheckoutData[0]['CheckTime'];
									$insertion_data[] = $single_person_data;
								}
								if($value['CheckTime'] > '12:00:00'){
									$single_person_data['ref_id'] = $value['ref_id'];
									$single_person_data['schedule_id'] = $row['id'];
									$single_person_data['fullname'] = $row['fullname'];
									$single_person_data['bio_id'] = $value['userid'];
									$single_person_data['attendance_date'] = $inlimitCheckinoutEmpData[$key]['CheckDate'];
									$single_person_data['check_time'] = $inlimitCheckinoutEmpData[$key]['CheckDate'].' '.$value['CheckTime'];
									$insertion_data[] = $single_person_data;
								}
							}

				}
			}
		}
		// $uniqueRefIds = array();
		// $finalizedArray = array();

		foreach ($insertion_data as $data) {
			// echo "<pre>"; print_r($data); exit;

			$ref_id_records =  $nightAttendance->check_ref_id($data['ref_id']);
			if(empty($ref_id_records)){
				$this->db->insert('tbl_night_attendance',$data);
			}

			// $refId = $data['ref_id'];
			// if (!in_array($refId, $uniqueRefIds)) {
			// 	$uniqueRefIds[] = $refId;
			// 	$finalizedArray[] = $data;
			// }
		}
		// echo "<pre>"; print_r($finalizedArray); exit;
		// $nightAttendance->insert_night_attendance($insertion_data);
		exit;
	}
}

