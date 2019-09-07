<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class User_wm extends CI_Model {

	function getUserDetails() {
		$Q = $this->db->query('select id,name,email,dob,phone,gender,userimage,passport,country_id,socialimage from tbl_front_users where id="' . $_POST['userid'] . '"');
		$row = $Q->row_array();
		$data = array(
			'id' => $row['id'],
			'username' => $row['name'],
			'useremail' => $row['email'],
			'dob' => $row['dob'],
			'phone' => $row['phone'] ? $row['phone'] : '',
			'gender' => $row['gender'] ? $row['gender'] : '',
			'userimage' => $row['userimage'] ? $row['userimage'] : '',
			'socialimage' => $row['socialimage'] ? $row['socialimage'] : '',
			'passport' => $row['passport'] ? $row['passport'] : '',
			'country_id' => $row['country_id'] ? $row['country_id'] : 0,
		);
		return $data;
	}

	function getCountries() {
		$data = array();
		$Q = $this->db->query('select id,name from tbl_worlds_countries order by name asc');
		return $Q->result_array();
	}

	/*Code For User's Tags - start*/

	function getTags() {
		$data = array();
		$this->db->select('id,tag_name');
		$this->db->order_by('sortorder', 'ASC');
		$Q = $this->db->get('tbl_tag_master');
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	function getAllUserTags() {
		$tags = $this->getTags();
		//echo "<pre>";print_r($tags);die;

		$Q = $this->db->query('select tag_id from tbl_user_tags where user_id=' . $_POST['userid']);
		if ($Q->num_rows() > 0) {
			$userTags = $Q->result_array();
			//echo "<pre>";print_r($userTags);die;
			foreach ($tags as $t) {
				if (count($userTags) > 0) {
					for ($i = 0; $i < count($userTags); $i++) {
						if ($t['id'] == $userTags[$i]['tag_id']) {
							$t['flag'] = 1;
							break;
						} else {
							$t['flag'] = 0;
						}
					}
				} else {
					$t['flag'] = 0;
				}

				$result[] = $t;
			}

			if ($result && count($result) > 0) {
				return $result;
			}

		} else {
			foreach ($tags as $tag) {
				$tag['flag'] = 0;
				$result[] = $tag;
			}
			if ($result && count($result) > 0) {
				return $result;
			}
		}
		return array();
	}

	/*Code For User's tag - end*/

	function signupUser() {
		$device_id = deviceUpdate($_POST['device_udid'], $_POST['device_version'], $_POST['device_type']);

		$datetime = date('Y-m-d H:i:s');
		$data = array(
			'name' => ucwords($_POST['username']),
			'email' => $_POST['useremail'],
			'password' => $this->hash($_POST['userpassword']),
			'isactive' => 1,
			'created' => $datetime,
			'last_login' => $datetime,
			'userimage' => '',
			'phone' => '',
			'gender' => 0,
			'facebookid' => '',
			'googleid' => '',
			'logintype' => 1, //now not important
			'country_id' => 0,
			'dob' => '0000-00-00',
			'isemail' => 0,
			'device_id' => $device_id,
			'last_login' => $datetime,
			'isloggedin' => 1,
			'socialimage' => '',
		);

		$this->db->insert('tbl_front_users', $data);
		$userid = $this->db->insert_id();
		deviceLogin($userid, $device_id);

		$userArray = array(
			'userid' => $userid,
			'username' => ucwords($_POST['username']),
			'useremail' => $_POST['useremail'],
			'userimage' => '',
			'issocial' => 0,
			'askforemail' => 0,
		);

		$data['taxidio'] = $this->getSettings();

		$this->load->library('email');
		$this->load->library('MY_Email_Other');

		$_SESSION['name'] = $userArray['username'];
		$message = $this->load->view('register_template', $data, true);
		$from = "noreply@taxidio.com"; //'ei.muniruddin.malek@gmail.com'
		$to = $userArray['useremail'];
		$subject = 'Welcome to Taxidio’s World of Travel.';
		$this->email->from($from, 'Taxidio');
		$this->email->subject($subject);
		$this->email->to($to);
		$this->email->message($message);
		$this->email->send();
		unset($_SESSION['name']);
		return $userArray;
	}

	function googleLogin() {
		$Q = $this->db->query('select id,name,email,last_login,userimage,isemail from tbl_front_users where googleid="' . $_POST['googleid'] . '"');

		$device_id = deviceUpdate($_POST['device_udid'], $_POST['device_version'], $_POST['device_type']);

		$datetime = date('Y-m-d H:i:s');
		if ($Q->num_rows() > 0) {
			$returnData = $Q->row_array();

			$data = array(
				'socialimage' => $_POST['socialimage'],
				'device_id' => $device_id,
				'last_login' => $datetime,
			);

			$this->db->where('id', $returnData['id']);
			$this->db->update('tbl_front_users', $data);

			deviceLogin($returnData['id'], $device_id);

			$userArray = array(
				'userid' => $returnData['id'],
				'username' => ucwords($returnData['name']),
				'useremail' => $returnData['email'],
				'socialimage' => $_POST['socialimage'],
				'issocial' => 1,
				'askforemail' => $returnData['isemail'],
			);

		} else {

			$qrcheck = $this->db->query('select id from tbl_front_users where email="' . $_POST['useremail'] . '" limit 1');
			if ($qrcheck->num_rows() > 0) {
				$uid = $qrcheck->row_array();
				$updatedata = array(
					'isactive' => 1,
					'googleid' => $_POST['googleid'],
					'socialimage' => $_POST['socialimage'],
					'gender' => $_POST['usergender'],
					'isemail' => 0,
					'isloggedin' => 1,
				);
				$this->db->where('id', $uid['id']);
				$this->db->update('tbl_front_users', $updatedata);
				$userid = $uid['id'];
			} else {
				$insertdata = array(
					'name' => $_POST['username'],
					'email' => $_POST['useremail'],
					'logintype' => 2, // not important
					'isactive' => 1,
					'created' => $datetime,
					'password' => '',
					'googleid' => $_POST['googleid'],
					'facebookid' => '',
					'socialimage' => $_POST['socialimage'],
					'phone' => '',
					'gender' => $_POST['usergender'],
					'country_id' => 0,
					'dob' => '',
					'isemail' => 0,
					'device_id' => $device_id,
					'last_login' => $datetime,
					'isloggedin' => 1,
				);
				$this->db->insert('tbl_front_users', $insertdata);
				$userid = $this->db->insert_id();

			}

			deviceLogin($userid, $device_id);
			$userArray = array(
				'userid' => $userid,
				'username' => ucwords($_POST['username']),
				'useremail' => $_POST['useremail'],
				'socialimage' => $_POST['socialimage'],
				'issocial' => 1,
				'askforemail' => 0,
			);
		}
		return $userArray;
	}

	function facebookLogin() {
		$Q = $this->db->query('select id,name,email,last_login,userimage,isemail from tbl_front_users where facebookid="' . $_POST['facebookid'] . '"');

		$device_id = deviceUpdate($_POST['device_udid'], $_POST['device_version'], $_POST['device_type']);

		$datetime = date('Y-m-d H:i:s');
		if ($Q->num_rows() > 0) {
			$returnData = $Q->row_array();

			$socialimage = "";
			if (isset($_POST['socialimage']) && $_POST['socialimage'] != '') {
				$socialimage = $_POST['socialimage'];
			}

			$data = array(
				'socialimage' => $socialimage,
				'device_id' => $device_id,
				'last_login' => $datetime,
			);
			$this->db->where('id', $returnData['id']);
			$this->db->update('tbl_front_users', $data);

			deviceLogin($returnData['id'], $device_id);

			$userArray = array(
				'userid' => $returnData['id'],
				'username' => ucwords($returnData['name']),
				'useremail' => $returnData['email'],
				'socialimage' => $_POST['socialimage'],
				'issocial' => 1,
				'askforemail' => $returnData['isemail'],
			);
		} else {

			if (isset($_POST['useremail']) && $_POST['useremail'] != '') {
				$uemail = $_POST['useremail'];
				$isemail = 0;
			} else {
				$uemail = $_POST['facebookid'] . '@facebook.com';
				$isemail = 1;
			}

			$isupdate = 0;
			if ($isemail == 0) {
				$qrcheck = $this->db->query('select id from tbl_front_users where email="' . $_POST['useremail'] . '" limit 1');
				if ($qrcheck->num_rows() > 0) {
					$uid = $qrcheck->row_array();
					$isupdate = 1;
				}

			}

			$socialimage = '';
			if (isset($_POST['socialimage']) && $_POST['socialimage'] != '') {
				$socialimage = $_POST['socialimage'];
			}

			if ($isupdate == 0) {

				$insertdata = array(
					'name' => ucwords($_POST['username']),
					'email' => $uemail,
					'logintype' => 3,
					'isactive' => 1,
					'created' => $datetime,
					'password' => '',
					'googleid' => '',
					'facebookid' => $_POST['facebookid'],
					'socialimage' => $socialimage,
					'phone' => '',
					'gender' => $_POST['usergender'],
					'country_id' => 0,
					'dob' => '',
					'isemail' => $isemail,
					'device_id' => $device_id,
					'last_login' => $datetime,
					'isloggedin' => 1,
				);
				$this->db->insert('tbl_front_users', $insertdata);
				$userid = $this->db->insert_id();
			} else {

				$updatedata = array(
					'logintype' => 3,
					'isactive' => 1,
					'facebookid' => $_POST['facebookid'],
					'socialimage' => $socialimage,
					'isloggedin' => 1,
				);
				$this->db->where('id', $uid['id']);
				$this->db->update('tbl_front_users', $updatedata);
				$userid = $uid['id'];
			}

			deviceLogin($userid, $device_id);

			$userArray = array(
				'userid' => $userid,
				'username' => ucwords($_POST['username']),
				'useremail' => $uemail,
				'socialimage' => $socialimage,
				'issocial' => 1,
				'askforemail' => $isemail,
			);
		}
		return $userArray;
	}

	function signinUser() {
		$Q = $this->db->query('select * from tbl_front_users where email="' . $_POST['useremail'] . '" and password="' . $this->hash($_POST['userpassword']) . '" limit 1');
		if ($Q->num_rows() > 0) {
			$device_id = deviceUpdate($_POST['device_udid'], $_POST['device_version'], $_POST['device_type']);
			$data = $Q->row_array();
			$datetime = date('Y-m-d H:i:s');
			$this->db->where('id', $data['id']);
			$this->db->update('tbl_front_users', array('last_login' => $datetime, 'device_id' => $device_id));

			deviceLogin($data['id'], $device_id);

			$sessionArray = array(
				'userid' => $data['id'],
				'username' => $data['name'],
				'useremail' => $data['email'],
				'userimage' => $data['userimage'],
				'issocial' => 0,
				'askforemail' => 0,
			);

			return $sessionArray;
		}
		return false;
	}

	public function hash($string) {
		return hash('sha512', $string . config_item('encryption_key'));
	}

	function forgotPassword() {
		$Q = $this->db->query('select id from tbl_front_users where email="' . $_POST['useremail'] . '"');
		if ($Q->num_rows() > 0) {
			$this->load->library('email');
			$this->load->library('MY_Email_Other');

			$uniq = uniqid();
			$data = $Q->row_array();
			$udata = array(
				'user_id' => $data['id'],
				'expire' => strtotime("+12 hour"),
				'token' => $uniq,
			);

			$this->db->where('user_id', $data['id']);
			$this->db->delete('tbl_tokens');
			$this->db->insert('tbl_tokens', $udata);
			$data['url'] = site_url('reset-password') . '/' . md5($data['id']) . '/' . md5($uniq);
			$message = $this->load->view('forgotpasspassword_template', $data, true);

			$from = 'noreply@taxidio.com'; //'ei.muniruddin.malek@gmail.com';
			$to = $_POST['useremail'];
			$subject = 'Password Reset Help';
			$this->email->from($from, 'Taxidio');
			$this->email->subject($subject);
			//$this->email->reply_to($from);
			$this->email->to($to);
			$this->email->message($message);
			$this->email->send();
			//echo "sd".$message;die;
			return true;
		} else {
			return false;
		}
	}

	function check_current_password() {
		$Q = $this->db->query('select id from tbl_front_users where id="' . $_POST['userid'] . '" and password="' . $this->hash($_POST['currentpassword']) . '" limit 1');
		if ($Q->num_rows() < 1) {
			$this->form_validation->set_message('check_current_password', 'Please provide correct current Password.');
			return FALSE;
		}
		return TRUE;
	}

	function changepassword() {
		$this->db->where('id', $_POST['userid']);
		$this->db->update('tbl_front_users', array('password' => $this->hash($_POST['newpassword'])));
		$udata = selectcolbycondition('name,email', 'tbl_front_users', 'id=' . $_POST['userid']);
		//$this->session->userdata('name')=

		$this->load->library('email');
		$this->load->library('MY_Email_Other');

		$_SESSION['name'] = $udata[0]['name'];
		$message = $this->load->view('myaccount/changepasspassword_template', $data = "", true);

		$from = "noreply@taxidio.com"; //'ei.muniruddin.malek@gmail.com';
		$to = $udata[0]['email'];
		$subject = 'Password Changed';
		$this->email->from($from, 'Taxidio');
		$this->email->subject($subject);
		$this->email->to($to);
		$this->email->message($message);
		$this->email->send();
		unset($_SESSION['name']);
	}

	function getSettings() {
		$Q = $this->db->query('select * from tbl_settings where id=1');
		return $Q->row_array();
	}

	function editUser() {
		/*echo json_decode($_POST['user_tags']);
			$tags = json_decode($_POST['user_tags']);

			foreach($tags as $k=>$t)
			{
				print_r($t);
			}
		*/

		$dob = implode("-", array_reverse(explode("/", $_POST['dob'])));
		if (isset($_POST['issocial']) && $_POST['issocial'] != 1) {
			$data = array(
				'name' => ucwords($_POST['username']),
				'email' => $_POST['useremail'],
				'passport' => $_POST['passport'],
				'country_id' => $_POST['country_id'],
				'dob' => $dob,
				'phone' => $_POST['phone'],
				'gender' => $_POST['gender'],
			);
			if (isset($_FILES['userimage']) && !empty($_FILES['userimage']['name'])) {
				$image = $this->img_save_to_file_profile();
				$data['userimage'] = $image;
			}
		} else {
			$data = array(
				'passport' => $_POST['passport'],
				'country_id' => $_POST['country_id'],
				'dob' => $dob,
				'phone' => $_POST['phone'],
				'gender' => $_POST['gender'],
			);
		}

		$this->db->where('id', $_POST['userid']);
		$this->db->update('tbl_front_users', $data);

		//Remove the tags of the user
		$this->db->where('user_id', $_POST['userid']);
		$this->db->delete('tbl_user_tags');

		//Add the recent tags of the user
		$tags = json_decode($_POST['user_tags']);
		foreach ($tags as $k => $tag) {
			if ($tag->id != '' && $tag->flag != '') {
				if ($tag->flag == '1') {
					$this->db->insert('tbl_user_tags', array('user_id' => $_POST['userid'], 'tag_id' => $tag->id));
				}
			}
		}

		/*for($i=0;$i<count($_POST['tag_id']);$i++)
			{
				$tagdata=array(
						'user_id'=>$user_id,
						'tag_id'=>$_POST['tag_id'][$i]
					);
				$this->db->insert('tbl_user_tags',$tagdata);
		*/

		$user = getrowbycondition("*", "tbl_front_users", "id=" . $_POST['userid']);
		$sessionArray = array(
			'userid' => $user['id'],
			'username' => $user['name'],
			'useremail' => $user['email'],
			'issocial' => 0,
			'askforemail' => 0,
		);

		if (isset($_POST['issocial']) && $_POST['issocial'] != 1) {
			$sessionArray['userimage'] = $user['userimage'];
		} else {
			$sessionArray['socialimage'] = $user['socialimage'];
		}
		return $sessionArray;
	}

	function updateEmail() {
		$data = array(
			'email' => $_POST['useremail'],
			'isemail' => 0,
		);
		$this->db->where('id', $_POST['userid']);
		$this->db->update('tbl_front_users', $data);

		$user = getrowbycondition("*", "tbl_front_users", "id=" . $_POST['userid']);
		$sessionArray = array(
			'userid' => $user['id'],
			'username' => $user['name'],
			'useremail' => $user['email'],
			'socialimage' => $user['socialimage'],
			'issocial' => 0,
			'askforemail' => 0,
		);

		return $sessionArray;
	}

	function check_email($email) {
		$Q = $this->db->query('select id from tbl_front_users where email="' . $email . '" and id!="' . $_POST['userid'] . '"');
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_email', 'That Email already exists.');
			return FALSE;
		}
		return TRUE;
	}

	/*Image Upload*/

	function img_save_to_file_profile() {
		//print_r($_FILES['userimage']);die;

		$config['upload_path'] = './userfiles/userimages/medium/';
		$config['allowed_types'] = '*';
		$config['max_size'] = '';
		$config['remove_spaces'] = true;
		$config['overwrite'] = false;
		$config['encrypt_name'] = false;
		$config['max_width'] = '';
		$config['max_height'] = '';
		$config['file_name'] = time() . '.jpg';
		$this->load->library('upload');
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('userimage')) {
			return "";
		} else {
			$image = $this->upload->data();

			$config['image_library'] = 'gd2';
			$config['source_image'] = './userfiles/userimages/medium/' . $image['file_name'];
			$config['new_image'] = './userfiles/userimages/small/';
			$config['maintain_ratio'] = TRUE;
			$config['overwrite'] = false;
			$config['width'] = 150;
			$config['height'] = 150;
			$config['master_dim'] = 'width';
			$config['file_name'] = time();
			$this->load->library('image_lib', $config); //load library
			$this->image_lib->resize(); //do whatever specified in config

			$this->removeImage(); //remove old image

			return $image['file_name'];
		}

	}

	function removeImage() {
		$Q = $this->db->query('select userimage from tbl_front_users where id="' . $_POST['userid'] . '" limit 1');

		$imagedata = $Q->row_array();
		if ($imagedata['userimage'] != '') {
			if (file_exists(FCPATH . 'userfiles/userimages/' . $imagedata['userimage'])) {
				unlink(FCPATH . 'userfiles/userimages/' . $imagedata['userimage']);
			}

			if (file_exists(FCPATH . 'userfiles/userimages/medium/' . $imagedata['userimage'])) {
				unlink(FCPATH . 'userfiles/userimages/medium/' . $imagedata['userimage']);
			}

			if (file_exists(FCPATH . 'userfiles/userimages/small/' . $imagedata['userimage'])) {
				unlink(FCPATH . 'userfiles/userimages/small/' . $imagedata['userimage']);
			}
		}
	}

	//Code For SOS CRUD - start

	public function getAllSOSOfUser() {
		$this->db->select('id,name,relation,phone,country_code,email');
		$this->db->from('tbl_sos');
		$this->db->where('user_id', $this->input->post('userid'));
		$Q = $this->db->get();
		if ($Q->num_rows() > 0) {
			return $Q->result_array();
		}

		return array();
	}

	public function addSOS() {
		if (isset($_POST['sosname']) && $_POST['sosname'] != '') {
			$data = array(
				'user_id' => $_POST['userid'],
				'name' => $_POST['sosname'],
				'relation' => $_POST['sosrelation'],
				'phone' => $_POST['sosphno'],
				'country_code' => $_POST['soscountrycode'],
				'email' => $_POST['sosemail'],
			);

			return $this->db->insert('tbl_sos', $data);
		} else {
			return FALSE;
		}
	}

	public function getSOSById($sosId, $userId) {
		$this->db->select('id,name,relation,phone,country_code,email');
		$this->db->from('tbl_sos');
		$this->db->where('id', $sosId);
		$this->db->where('user_id', $userId);
		$Q = $this->db->get();
		if ($Q->num_rows() > 0) {
			return $Q->result_array();
		}

		return array();
	}

	public function editSOS($sosId, $userId) {
		if (isset($_POST['sosname']) && $_POST['sosname'] != '') {
			$data = array(
				'name' => $_POST['sosname'],
				'relation' => $_POST['sosrelation'],
				'phone' => $_POST['sosphno'],
				'country_code' => $_POST['soscountrycode'],
				'email' => $_POST['sosemail'],
			);

			$this->db->set($data);
			$this->db->where('id', $sosId);
			$this->db->where('user_id', $userId);
			return $this->db->update('tbl_sos');
		} else {
			return FALSE;
		}
	}

	public function deleteSOS($sosId, $userId) {
		$this->db->where('id', $sosId);
		$this->db->where('user_id', $userId);
		$this->db->delete('tbl_sos', array('id' => $sosId, 'user_id' => $userId));
	}

	function countrySelector() {
		$countryArray = array(
			'AD' => array('name' => 'ANDORRA', 'code' => '376'),
			'AE' => array('name' => 'UNITED ARAB EMIRATES', 'code' => '971'),
			'AF' => array('name' => 'AFGHANISTAN', 'code' => '93'),
			'AG' => array('name' => 'ANTIGUA AND BARBUDA', 'code' => '1268'),
			'AI' => array('name' => 'ANGUILLA', 'code' => '1264'),
			'AL' => array('name' => 'ALBANIA', 'code' => '355'),
			'AM' => array('name' => 'ARMENIA', 'code' => '374'),
			'AN' => array('name' => 'NETHERLANDS ANTILLES', 'code' => '599'),
			'AO' => array('name' => 'ANGOLA', 'code' => '244'),
			'AQ' => array('name' => 'ANTARCTICA', 'code' => '672'),
			'AR' => array('name' => 'ARGENTINA', 'code' => '54'),
			'AS' => array('name' => 'AMERICAN SAMOA', 'code' => '1684'),
			'AT' => array('name' => 'AUSTRIA', 'code' => '43'),
			'AU' => array('name' => 'AUSTRALIA', 'code' => '61'),
			'AW' => array('name' => 'ARUBA', 'code' => '297'),
			'AZ' => array('name' => 'AZERBAIJAN', 'code' => '994'),
			'BA' => array('name' => 'BOSNIA AND HERZEGOVINA', 'code' => '387'),
			'BB' => array('name' => 'BARBADOS', 'code' => '1246'),
			'BD' => array('name' => 'BANGLADESH', 'code' => '880'),
			'BE' => array('name' => 'BELGIUM', 'code' => '32'),
			'BF' => array('name' => 'BURKINA FASO', 'code' => '226'),
			'BG' => array('name' => 'BULGARIA', 'code' => '359'),
			'BH' => array('name' => 'BAHRAIN', 'code' => '973'),
			'BI' => array('name' => 'BURUNDI', 'code' => '257'),
			'BJ' => array('name' => 'BENIN', 'code' => '229'),
			'BL' => array('name' => 'SAINT BARTHELEMY', 'code' => '590'),
			'BM' => array('name' => 'BERMUDA', 'code' => '1441'),
			'BN' => array('name' => 'BRUNEI DARUSSALAM', 'code' => '673'),
			'BO' => array('name' => 'BOLIVIA', 'code' => '591'),
			'BR' => array('name' => 'BRAZIL', 'code' => '55'),
			'BS' => array('name' => 'BAHAMAS', 'code' => '1242'),
			'BT' => array('name' => 'BHUTAN', 'code' => '975'),
			'BW' => array('name' => 'BOTSWANA', 'code' => '267'),
			'BY' => array('name' => 'BELARUS', 'code' => '375'),
			'BZ' => array('name' => 'BELIZE', 'code' => '501'),
			'CA' => array('name' => 'CANADA', 'code' => '1'),
			'CC' => array('name' => 'COCOS (KEELING) ISLANDS', 'code' => '61'),
			'CD' => array('name' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'code' => '243'),
			'CF' => array('name' => 'CENTRAL AFRICAN REPUBLIC', 'code' => '236'),
			'CG' => array('name' => 'CONGO', 'code' => '242'),
			'CH' => array('name' => 'SWITZERLAND', 'code' => '41'),
			'CI' => array('name' => 'COTE D IVOIRE', 'code' => '225'),
			'CK' => array('name' => 'COOK ISLANDS', 'code' => '682'),
			'CL' => array('name' => 'CHILE', 'code' => '56'),
			'CM' => array('name' => 'CAMEROON', 'code' => '237'),
			'CN' => array('name' => 'CHINA', 'code' => '86'),
			'CO' => array('name' => 'COLOMBIA', 'code' => '57'),
			'CR' => array('name' => 'COSTA RICA', 'code' => '506'),
			'CU' => array('name' => 'CUBA', 'code' => '53'),
			'CV' => array('name' => 'CAPE VERDE', 'code' => '238'),
			'CX' => array('name' => 'CHRISTMAS ISLAND', 'code' => '61'),
			'CY' => array('name' => 'CYPRUS', 'code' => '357'),
			'CZ' => array('name' => 'CZECH REPUBLIC', 'code' => '420'),
			'DE' => array('name' => 'GERMANY', 'code' => '49'),
			'DJ' => array('name' => 'DJIBOUTI', 'code' => '253'),
			'DK' => array('name' => 'DENMARK', 'code' => '45'),
			'DM' => array('name' => 'DOMINICA', 'code' => '1767'),
			'DO' => array('name' => 'DOMINICAN REPUBLIC', 'code' => '1809'),
			'DZ' => array('name' => 'ALGERIA', 'code' => '213'),
			'EC' => array('name' => 'ECUADOR', 'code' => '593'),
			'EE' => array('name' => 'ESTONIA', 'code' => '372'),
			'EG' => array('name' => 'EGYPT', 'code' => '20'),
			'ER' => array('name' => 'ERITREA', 'code' => '291'),
			'ES' => array('name' => 'SPAIN', 'code' => '34'),
			'ET' => array('name' => 'ETHIOPIA', 'code' => '251'),
			'FI' => array('name' => 'FINLAND', 'code' => '358'),
			'FJ' => array('name' => 'FIJI', 'code' => '679'),
			'FK' => array('name' => 'FALKLAND ISLANDS (MALVINAS)', 'code' => '500'),
			'FM' => array('name' => 'MICRONESIA, FEDERATED STATES OF', 'code' => '691'),
			'FO' => array('name' => 'FAROE ISLANDS', 'code' => '298'),
			'FR' => array('name' => 'FRANCE', 'code' => '33'),
			'GA' => array('name' => 'GABON', 'code' => '241'),
			'GB' => array('name' => 'UNITED KINGDOM', 'code' => '44'),
			'GD' => array('name' => 'GRENADA', 'code' => '1473'),
			'GE' => array('name' => 'GEORGIA', 'code' => '995'),
			'GH' => array('name' => 'GHANA', 'code' => '233'),
			'GI' => array('name' => 'GIBRALTAR', 'code' => '350'),
			'GL' => array('name' => 'GREENLAND', 'code' => '299'),
			'GM' => array('name' => 'GAMBIA', 'code' => '220'),
			'GN' => array('name' => 'GUINEA', 'code' => '224'),
			'GQ' => array('name' => 'EQUATORIAL GUINEA', 'code' => '240'),
			'GR' => array('name' => 'GREECE', 'code' => '30'),
			'GT' => array('name' => 'GUATEMALA', 'code' => '502'),
			'GU' => array('name' => 'GUAM', 'code' => '1671'),
			'GW' => array('name' => 'GUINEA-BISSAU', 'code' => '245'),
			'GY' => array('name' => 'GUYANA', 'code' => '592'),
			'HK' => array('name' => 'HONG KONG', 'code' => '852'),
			'HN' => array('name' => 'HONDURAS', 'code' => '504'),
			'HR' => array('name' => 'CROATIA', 'code' => '385'),
			'HT' => array('name' => 'HAITI', 'code' => '509'),
			'HU' => array('name' => 'HUNGARY', 'code' => '36'),
			'ID' => array('name' => 'INDONESIA', 'code' => '62'),
			'IE' => array('name' => 'IRELAND', 'code' => '353'),
			'IL' => array('name' => 'ISRAEL', 'code' => '972'),
			'IM' => array('name' => 'ISLE OF MAN', 'code' => '44'),
			'IN' => array('name' => 'INDIA', 'code' => '91'),
			'IQ' => array('name' => 'IRAQ', 'code' => '964'),
			'IR' => array('name' => 'IRAN, ISLAMIC REPUBLIC OF', 'code' => '98'),
			'IS' => array('name' => 'ICELAND', 'code' => '354'),
			'IT' => array('name' => 'ITALY', 'code' => '39'),
			'JM' => array('name' => 'JAMAICA', 'code' => '1876'),
			'JO' => array('name' => 'JORDAN', 'code' => '962'),
			'JP' => array('name' => 'JAPAN', 'code' => '81'),
			'KE' => array('name' => 'KENYA', 'code' => '254'),
			'KG' => array('name' => 'KYRGYZSTAN', 'code' => '996'),
			'KH' => array('name' => 'CAMBODIA', 'code' => '855'),
			'KI' => array('name' => 'KIRIBATI', 'code' => '686'),
			'KM' => array('name' => 'COMOROS', 'code' => '269'),
			'KN' => array('name' => 'SAINT KITTS AND NEVIS', 'code' => '1869'),
			'KP' => array('name' => 'KOREA DEMOCRATIC PEOPLES REPUBLIC OF', 'code' => '850'),
			'KR' => array('name' => 'KOREA REPUBLIC OF', 'code' => '82'),
			'KW' => array('name' => 'KUWAIT', 'code' => '965'),
			'KY' => array('name' => 'CAYMAN ISLANDS', 'code' => '1345'),
			'KZ' => array('name' => 'KAZAKSTAN', 'code' => '7'),
			'LA' => array('name' => 'LAO PEOPLES DEMOCRATIC REPUBLIC', 'code' => '856'),
			'LB' => array('name' => 'LEBANON', 'code' => '961'),
			'LC' => array('name' => 'SAINT LUCIA', 'code' => '1758'),
			'LI' => array('name' => 'LIECHTENSTEIN', 'code' => '423'),
			'LK' => array('name' => 'SRI LANKA', 'code' => '94'),
			'LR' => array('name' => 'LIBERIA', 'code' => '231'),
			'LS' => array('name' => 'LESOTHO', 'code' => '266'),
			'LT' => array('name' => 'LITHUANIA', 'code' => '370'),
			'LU' => array('name' => 'LUXEMBOURG', 'code' => '352'),
			'LV' => array('name' => 'LATVIA', 'code' => '371'),
			'LY' => array('name' => 'LIBYAN ARAB JAMAHIRIYA', 'code' => '218'),
			'MA' => array('name' => 'MOROCCO', 'code' => '212'),
			'MC' => array('name' => 'MONACO', 'code' => '377'),
			'MD' => array('name' => 'MOLDOVA, REPUBLIC OF', 'code' => '373'),
			'ME' => array('name' => 'MONTENEGRO', 'code' => '382'),
			'MF' => array('name' => 'SAINT MARTIN', 'code' => '1599'),
			'MG' => array('name' => 'MADAGASCAR', 'code' => '261'),
			'MH' => array('name' => 'MARSHALL ISLANDS', 'code' => '692'),
			'MK' => array('name' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'code' => '389'),
			'ML' => array('name' => 'MALI', 'code' => '223'),
			'MM' => array('name' => 'MYANMAR', 'code' => '95'),
			'MN' => array('name' => 'MONGOLIA', 'code' => '976'),
			'MO' => array('name' => 'MACAU', 'code' => '853'),
			'MP' => array('name' => 'NORTHERN MARIANA ISLANDS', 'code' => '1670'),
			'MR' => array('name' => 'MAURITANIA', 'code' => '222'),
			'MS' => array('name' => 'MONTSERRAT', 'code' => '1664'),
			'MT' => array('name' => 'MALTA', 'code' => '356'),
			'MU' => array('name' => 'MAURITIUS', 'code' => '230'),
			'MV' => array('name' => 'MALDIVES', 'code' => '960'),
			'MW' => array('name' => 'MALAWI', 'code' => '265'),
			'MX' => array('name' => 'MEXICO', 'code' => '52'),
			'MY' => array('name' => 'MALAYSIA', 'code' => '60'),
			'MZ' => array('name' => 'MOZAMBIQUE', 'code' => '258'),
			'NA' => array('name' => 'NAMIBIA', 'code' => '264'),
			'NC' => array('name' => 'NEW CALEDONIA', 'code' => '687'),
			'NE' => array('name' => 'NIGER', 'code' => '227'),
			'NG' => array('name' => 'NIGERIA', 'code' => '234'),
			'NI' => array('name' => 'NICARAGUA', 'code' => '505'),
			'NL' => array('name' => 'NETHERLANDS', 'code' => '31'),
			'NO' => array('name' => 'NORWAY', 'code' => '47'),
			'NP' => array('name' => 'NEPAL', 'code' => '977'),
			'NR' => array('name' => 'NAURU', 'code' => '674'),
			'NU' => array('name' => 'NIUE', 'code' => '683'),
			'NZ' => array('name' => 'NEW ZEALAND', 'code' => '64'),
			'OM' => array('name' => 'OMAN', 'code' => '968'),
			'PA' => array('name' => 'PANAMA', 'code' => '507'),
			'PE' => array('name' => 'PERU', 'code' => '51'),
			'PF' => array('name' => 'FRENCH POLYNESIA', 'code' => '689'),
			'PG' => array('name' => 'PAPUA NEW GUINEA', 'code' => '675'),
			'PH' => array('name' => 'PHILIPPINES', 'code' => '63'),
			'PK' => array('name' => 'PAKISTAN', 'code' => '92'),
			'PL' => array('name' => 'POLAND', 'code' => '48'),
			'PM' => array('name' => 'SAINT PIERRE AND MIQUELON', 'code' => '508'),
			'PN' => array('name' => 'PITCAIRN', 'code' => '870'),
			'PR' => array('name' => 'PUERTO RICO', 'code' => '1'),
			'PT' => array('name' => 'PORTUGAL', 'code' => '351'),
			'PW' => array('name' => 'PALAU', 'code' => '680'),
			'PY' => array('name' => 'PARAGUAY', 'code' => '595'),
			'QA' => array('name' => 'QATAR', 'code' => '974'),
			'RO' => array('name' => 'ROMANIA', 'code' => '40'),
			'RS' => array('name' => 'SERBIA', 'code' => '381'),
			'RU' => array('name' => 'RUSSIAN FEDERATION', 'code' => '7'),
			'RW' => array('name' => 'RWANDA', 'code' => '250'),
			'SA' => array('name' => 'SAUDI ARABIA', 'code' => '966'),
			'SB' => array('name' => 'SOLOMON ISLANDS', 'code' => '677'),
			'SC' => array('name' => 'SEYCHELLES', 'code' => '248'),
			'SD' => array('name' => 'SUDAN', 'code' => '249'),
			'SE' => array('name' => 'SWEDEN', 'code' => '46'),
			'SG' => array('name' => 'SINGAPORE', 'code' => '65'),
			'SH' => array('name' => 'SAINT HELENA', 'code' => '290'),
			'SI' => array('name' => 'SLOVENIA', 'code' => '386'),
			'SK' => array('name' => 'SLOVAKIA', 'code' => '421'),
			'SL' => array('name' => 'SIERRA LEONE', 'code' => '232'),
			'SM' => array('name' => 'SAN MARINO', 'code' => '378'),
			'SN' => array('name' => 'SENEGAL', 'code' => '221'),
			'SO' => array('name' => 'SOMALIA', 'code' => '252'),
			'SR' => array('name' => 'SURINAME', 'code' => '597'),
			'ST' => array('name' => 'SAO TOME AND PRINCIPE', 'code' => '239'),
			'SV' => array('name' => 'EL SALVADOR', 'code' => '503'),
			'SY' => array('name' => 'SYRIAN ARAB REPUBLIC', 'code' => '963'),
			'SZ' => array('name' => 'SWAZILAND', 'code' => '268'),
			'TC' => array('name' => 'TURKS AND CAICOS ISLANDS', 'code' => '1649'),
			'TD' => array('name' => 'CHAD', 'code' => '235'),
			'TG' => array('name' => 'TOGO', 'code' => '228'),
			'TH' => array('name' => 'THAILAND', 'code' => '66'),
			'TJ' => array('name' => 'TAJIKISTAN', 'code' => '992'),
			'TK' => array('name' => 'TOKELAU', 'code' => '690'),
			'TL' => array('name' => 'TIMOR-LESTE', 'code' => '670'),
			'TM' => array('name' => 'TURKMENISTAN', 'code' => '993'),
			'TN' => array('name' => 'TUNISIA', 'code' => '216'),
			'TO' => array('name' => 'TONGA', 'code' => '676'),
			'TR' => array('name' => 'TURKEY', 'code' => '90'),
			'TT' => array('name' => 'TRINIDAD AND TOBAGO', 'code' => '1868'),
			'TV' => array('name' => 'TUVALU', 'code' => '688'),
			'TW' => array('name' => 'TAIWAN, PROVINCE OF CHINA', 'code' => '886'),
			'TZ' => array('name' => 'TANZANIA, UNITED REPUBLIC OF', 'code' => '255'),
			'UA' => array('name' => 'UKRAINE', 'code' => '380'),
			'UG' => array('name' => 'UGANDA', 'code' => '256'),
			'US' => array('name' => 'UNITED STATES', 'code' => '1'),
			'UY' => array('name' => 'URUGUAY', 'code' => '598'),
			'UZ' => array('name' => 'UZBEKISTAN', 'code' => '998'),
			'VA' => array('name' => 'HOLY SEE (VATICAN CITY STATE)', 'code' => '39'),
			'VC' => array('name' => 'SAINT VINCENT AND THE GRENADINES', 'code' => '1784'),
			'VE' => array('name' => 'VENEZUELA', 'code' => '58'),
			'VG' => array('name' => 'VIRGIN ISLANDS, BRITISH', 'code' => '1284'),
			'VI' => array('name' => 'VIRGIN ISLANDS, U.S.', 'code' => '1340'),
			'VN' => array('name' => 'VIET NAM', 'code' => '84'),
			'VU' => array('name' => 'VANUATU', 'code' => '678'),
			'WF' => array('name' => 'WALLIS AND FUTUNA', 'code' => '681'),
			'WS' => array('name' => 'SAMOA', 'code' => '685'),
			'XK' => array('name' => 'KOSOVO', 'code' => '381'),
			'YE' => array('name' => 'YEMEN', 'code' => '967'),
			'YT' => array('name' => 'MAYOTTE', 'code' => '262'),
			'ZA' => array('name' => 'SOUTH AFRICA', 'code' => '27'),
			'ZM' => array('name' => 'ZAMBIA', 'code' => '260'),
			'ZW' => array('name' => 'ZIMBABWE', 'code' => '263'),
		);

		return array_values($countryArray); // or echo $output; to print directly
	}

	//Code For SOS CRUD - end
}

/* End of file User_wm.php */
/* Location: ./application/models/webservices_models/User_wm.php */
