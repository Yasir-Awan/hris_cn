<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userinfo_Controller extends CI_Controller {

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
		$db = 'E:\att2000.mdb';
		//phpinfo(); exit;
		if(!file_exists($db)){
echo "yasir";
		}
		// $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
		$db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$db; Uid=; Pwd=;");

		$bioUser = $this->db->select('*')->order_by('bio_ref_id', 'desc')->limit(1)->get('user_bio')->result_array();

		if(empty($bioUser)){
			$sql = "SELECT * FROM USERINFO";
		}
		if(!empty($bioUser)){
			$ref_id = $bioUser[0]['bio_ref_id'];
			$sql = "SELECT * FROM USERINFO where USERID > $ref_id ORDER BY id ASC";
		}
		$result = $db->query($sql)->fetchAll();

		foreach ($result as $row) {
			$import_data = array(
				'user_name' => $row['Name'],
				'bio_ref_id' => $row['USERID'],
			);
			$this->db->insert('user_bio', $import_data);
			// your code here.
		}
	}
}
