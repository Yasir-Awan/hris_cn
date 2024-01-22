<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
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
	public function index()
	{
				// $db = 'C:\Program Files\ZKTeco\att2000.mdb';
				$db = '//172.19.10.46/ZKTeco/att2000.mdb';
				if(!file_exists($db)){
				echo "file not exist in the location";
				}

				$con = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$db; Uid=; Pwd=;");
				//$db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$dbName; Uid=; Pwd=");
				$checkinout = $this->db->select('*')->order_by('id', 'desc')->limit(1)->get('tbl_checkinouts')->result_array();

				if(empty($checkinout)){
					$sql = 'SELECT TOP 200 * FROM CHECKINOUT WHERE CHECKTIME > #8/31/2023 11:58:35 PM# ORDER BY CHECKTIME ASC;';
						// -- CHECKTIME > #8/31/2023 11:58:35 PM# ORDER BY CHECKTIME ASC;';
					#  # where CHECKTIME > 7/31/2023 11:53:40 PM
				}
				else{
					$lastDate = $checkinout[0]['CheckDate'];
					$lastTime = $checkinout[0]['CheckTime'];
					$lastRefId = $checkinout[0]['ref_id'];
					// Set the timezone to the one you need
					date_default_timezone_set('Asia/Karachi');

					// Create a new DateTime object with the date and time
					$date_time = new DateTime($lastDate.' '.$lastTime);

					// Format the date and time in the desired format
					$formatted_date_time = $date_time->format('m/d/Y h:i:s A');

					$timestamp = $formatted_date_time;
					$date = DateTime::createFromFormat('n/j/Y h:i:s A', $timestamp);
					$hour = ltrim($date->format('h'), '0');
					$formatted_date = $date->format("n/j/Y $hour:i:s A");
					echo $formatted_date;
					$sql = 'SELECT TOP 150 * FROM CHECKINOUT WHERE id > '.$lastRefId.' ORDER BY id ASC;';
				}
					// echo $sql; exit;
				$result = $con->query($sql)->fetchAll();

				// echo "<pre>"; print_r($result); exit;

				// $import_data = array();
				foreach($result as $key => $val) {
					// $import_data[$key]['userid'] = $val['USERID'];
					// $import_data[$key]['ref_id'] = $val['id'];

					$explodedTimeStamp = explode(" ",$val['CHECKTIME']);
					$checkDate = $explodedTimeStamp[0];
					$checkTime = $explodedTimeStamp[1];

					$this->db->query("INSERT IGNORE INTO tbl_checkinouts (ref_id, userid, CheckDate, CheckTime) VALUES ({$val['id']}, {$val['USERID']}, '{$checkDate}', '{$checkTime}');");

					// $import_data[$key]['ref_id'] = $val['id'];
					// $import_data[$key]['CheckDate'] = $checkDate;
					// $import_data[$key]['CheckTime'] = $checkTime;

					// $import_data[$key]['CheckType'] = $val['CHECKTYPE'];
					// $import_data[$key]['sensorid'] = $val['SENSORID'];
					// $import_data[$key]['sn'] = $val['sn'];
				}


				// echo "<pre>"; print_r($import_data);
				// exit;

				// $this->db->insert_batch('tbl_checkinouts', $import_data);

				$con = null;
	}
}

