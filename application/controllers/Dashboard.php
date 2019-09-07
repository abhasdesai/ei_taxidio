<?php
class Dashboard extends Front_Controller {
	public function index() {
		$data['webpagename']     = 'home';
		$data['main']            = 'index';
		$data['preschool']       = $this->Cms_fm->getPreSchool();
		$data['juniorschool']    = $this->Cms_fm->getJuniorSchool();
		$data['middleschool']    = $this->Cms_fm->getMiddleSchool();
		$data['seniorchool']     = $this->Cms_fm->getSeniorSchool();
		$data['sports']          = $this->Cms_fm->getSports();
		$data['extraCurriculum'] = $this->Cms_fm->extraCurriculum();
		$data['creativility']    = $this->Cms_fm->getCreativity();
		$data['facilities']      = $this->Cms_fm->getFacilities();
		$data['events']          = $this->Cms_fm->getEvents();
		//$data['news']=$this->Cms_fm->getNews();
		//$data['career']=$this->Cms_fm->getCareer();
		//$data['video']=$this->Cms_fm->getVideos();
		$data['banners']       = $this->Cms_fm->getBanners(1);
		$data['upcomingevent'] = $this->Cms_fm->getUpComingevent();
		$data['philosophies']  = $this->Cms_fm->getPhilosophies(4);
		$data['download']      = $this->Cms_fm->getDownloads();
		$this->load->vars($data);
		$this->load->view('templates/homemaster');
	}

	function architecture_campus_infrastructure($var = '') {

		if ($var == '') {
			$var                 = 'architecture-campus-infrastructure';
			$data['webpagename'] = 'about_methodology';
			$data['main']        = 'category_content';
			$data['action']      = 'architecture-campus-infrastructure';
			$data['content']     = $this->Cms_fm->getContents($var);
			if ($data['content']) {
				$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
				if ($data['maincat']['parentcat']) {
					$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
					if ($data['subcat']['parentcat']) {
						$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
					}
				}
				$data['subcategory'] = $this->Cms_fm->getSubCategories($data['content']['id']);
				$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
				$data['error']       = array();
			} else {
				$data['error']       = 1;
				$data['subcategory'] = array();
				$data['banners']     = array();
			}
			$this->load->vars($data);
			$this->load->view('templates/category_content');
		} else {

			$data['webpagename'] = 'architecture';
			$data['main']        = 'architecture';
			$data['content']     = $this->Cms_fm->getAboutus($var);
			if ($data['content']) {
				$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
				if ($data['maincat']['parentcat']) {

					$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
					if ($data['maincat']) {
						$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
					}
				}
				$data['images']  = $this->Cms_fm->getAboutusImage($data['content']['id']);
				$data['banners'] = $this->Cms_fm->getBanners($data['content']['id']);
				// $data['subcategory']=$this->Cms_fm->getSameCategories($data['content']['parentcat']);
				$data['action'] = 'architecture-campus-infrastructure';
				$data['error']  = array();
			} else {
				$data['error']   = 1;
				$data['images']  = array();
				$data['banners'] = array();
			}
			$this->load->vars($data);
			$this->load->view('templates/gallery_content');
		}

	}

	function events($var) {
		$data['webpagename'] = 'architecture';
		$data['main']        = 'architecture';
		$data['content']     = $this->Cms_fm->getAboutus($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {

				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['maincat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['images']  = $this->Cms_fm->getAboutusImage($data['content']['id']);
			$data['banners'] = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']   = array();
		} else {
			$data['error']   = 1;
			$data['images']  = array();
			$data['banners'] = array();
		}

		$this->load->vars($data);
		$this->load->view('templates/gallery_content');

	}

	function creativity($var) {
		$data['webpagename'] = 'architecture';
		$data['main']        = 'architecture';
		$data['content']     = $this->Cms_fm->getAboutus($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {

				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['maincat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['images']  = $this->Cms_fm->getAboutusImage($data['content']['id']);
			$data['banners'] = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']   = array();
		} else {
			$data['error']   = 1;
			$data['images']  = array();
			$data['banners'] = array();
		}

		$this->load->vars($data);
		$this->load->view('templates/gallery_content');

	}

	function facilities($var) {
		$data['webpagename'] = 'architecture';
		$data['main']        = 'architecture';
		$data['content']     = $this->Cms_fm->getAboutus($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {

				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['maincat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['images']  = $this->Cms_fm->getAboutusImage($data['content']['id']);
			$data['banners'] = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']   = array();
		} else {
			$data['error']   = 1;
			$data['images']  = array();
			$data['banners'] = array();
		}

		$this->load->vars($data);
		$this->load->view('templates/gallery_content');

	}

	function extra_curriculum($var) {
		$data['webpagename'] = 'architecture';
		$data['main']        = 'architecture';
		$data['content']     = $this->Cms_fm->getAboutus($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {

				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['maincat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['images']  = $this->Cms_fm->getAboutusImage($data['content']['id']);
			$data['banners'] = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']   = array();
		} else {
			$data['error']   = 1;
			$data['images']  = array();
			$data['banners'] = array();
		}

		$this->load->vars($data);
		$this->load->view('templates/gallery_content');

	}

	function beyond_academics($var) {
		$data['webpagename'] = 'architecture';
		$data['main']        = 'architecture';
		$data['content']     = $this->Cms_fm->getAboutus($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {
				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['subcat']['parentcat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['images']  = $this->Cms_fm->getAboutusImage($data['content']['id']);
			$data['banners'] = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']   = array();
		} else {
			$data['error']   = 1;
			$data['images']  = array();
			$data['banners'] = array();
		}

		$this->load->vars($data);
		$this->load->view('templates/gallery_content');

	}

	function aboutus($var) {
		$data['webpagename'] = 'aboutus';
		$data['main']        = 'content';
		$data['content']     = $this->Cms_fm->getAboutusOblyContent($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {
				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['subcat']['parentcat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['images']  = $this->Cms_fm->getAboutusImage($data['content']['id']);
			$data['banners'] = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']   = array();
		} else {
			$data['error']   = 1;
			$data['images']  = array();
			$data['banners'] = array();
		}

		$this->load->vars($data);
		$this->load->view('templates/gallery_content');

	}

	function academics($var) {
		$data['webpagename'] = 'architecture';
		$data['main']        = 'architecture';
		$data['content']     = $this->Cms_fm->getAboutus($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {
				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['subcat']['parentcat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['images']  = $this->Cms_fm->getAboutusImage($data['content']['id']);
			$data['banners'] = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']   = array();
		} else {
			$data['error']   = 1;
			$data['images']  = array();
			$data['banners'] = array();
		}

		$this->load->vars($data);
		$this->load->view('templates/gallery_content');
	}

	function methodology($var) {
		$data['webpagename'] = 'school';
		$data['main']        = 'school';
		$data['title']       = $this->Cms_fm->getTitle($var);
		$data['content']     = $this->Cms_fm->getCategories($var);
		if ($data['title']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['title']['parentcat']);
			if ($data['maincat']['parentcat']) {
				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);

				if ($data['subcat']['parentcat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
					//print_r($data['subsubcat']);
					die;
				}
			}
			$data['images']  = $this->Cms_fm->getAboutusImage($data['title']['id']);
			$data['banners'] = $this->Cms_fm->getBanners($data['title']['id']);
			$data['error']   = array();
		} else {
			$data['error']   = 1;
			$data['images']  = array();
			$data['banners'] = array();
		}

		$this->load->vars($data);
		$this->load->view('templates/school');
	}

	function details($var) {
		$data['webpagename'] = 'schooldetails';
		$data['main']        = 'schooldetails';
		$data['content']     = $this->Cms_fm->getCategoriesdetails($var);
		$data['images']      = $this->Cms_fm->getCategoriesImage($data['content']['id']);
		$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
		$this->load->vars($data);
		$this->load->view('templates/gallery_content');
	}

	function pre_schools($var) {
		$data['webpagename'] = 'architecture';
		$data['main']        = 'architecture';
		$data['content']     = $this->Cms_fm->getAboutus($var);
		$data['maincat']     = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
		if ($data['maincat']['parentcat']) {
			$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
			if ($data['subcat']['parentcat']) {
				$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
			}
		}
		$data['images']  = $this->Cms_fm->getAboutusImage($data['content']['id']);
		$data['banners'] = $this->Cms_fm->getBanners($data['content']['id']);
		$this->load->vars($data);
		$this->load->view('templates/gallery_content');
	}

	function contactus() {
		$data['webpagename'] = 'contactus';
		$data['main']        = 'contactus';
		$data['content']     = $this->Cms_fm->getContentFromId(71);
		$data['banners']     = $this->Cms_fm->getBanners(71);
		$this->load->vars($data);
		$this->load->view('templates/contactus');
	}

	function sendMail() {
		$this->load->library('form_validation');
		if ($this->input->post('btnsubmit')) {
			$this->form_validation->set_error_delimiters('<p style="color:red;margin-top:45px;">', '</p>');
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('phone', 'Phone', 'required|min_length[10]|max_length[12]');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('message1', 'Message', 'required');
			$this->form_validation->set_rules('fullname', 'Full Name', 'required');
			$this->form_validation->set_rules('dob', 'Date Of Birth', 'required');
			$this->form_validation->set_rules('sought', 'Admission Sought In', 'required');
			if ($this->form_validation->run() == false) {
				$data['webpagename'] = 'contactus';
				$data['main']        = 'contactus';
				$data['content']     = $this->Cms_fm->getContentFromId(71);
				$data['banners']     = $this->Cms_fm->getBanners(71);
				$this->load->vars($data);
				$this->load->view('templates/contactus');
			} else {
				$this->Cms_fm->sendMail();
				$this->Cms_fm->sendMail_sender();
				$this->session->set_flashdata('success', 'Thanks for inquiry. We will get back to you soon.');
				redirect('thank-you');
			}
		} else {
			redirect('best-icse-school-vadodara-contact-us');
		}
	}

	function thankyou() {

		$data['webpagename'] = 'contactus';
		$data['main']        = 'thankyou';
		$data['content']     = $this->Cms_fm->getContentFromId(71);
		$data['banners']     = $this->Cms_fm->getBanners(71);
		$this->load->vars($data);
		$this->load->view('templates/contactus');

		/*
	$u=$_SERVER['HTTP_REFERER'];
	if(isset($u) && $u!=='')
	{
	$lastmethod=substr($u, strrpos($u, '/') + 1);
	if($lastmethod!='best-icse-school-vadodara-contact-us')
	{
	redirect(site_url());
	}
	$data['webpagename']='contactus';
	$data['main']='thankyou';
	$data['content']=$this->Cms_fm->getContentFromId(71);
	$data['banners']=$this->Cms_fm->getBanners(71);
	$this->load->vars($data);
	$this->load->view('templates/contactus');
	}
	else
	{
	redirect(site_url());
	}

	 */
	}

	function about_us() {
		$var                 = 'aboutus';
		$data['webpagename'] = 'aboutus';
		$data['main']        = 'aboutus';
		$data['content']     = $this->Cms_fm->getAboutus($var);
		$data['why']         = $this->Cms_fm->getAboutus('why-nalanda');
		$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function sendEmail() {
		if ($this->input->post('btnsubmit')) {
			$this->Cms_fm->sendEmail();
			$this->Cms_fm->sendMail_sender();
			$this->session->set_flashdata('success', 'Thanks for inquiry.');
			redirect('about_us');
		}

	}

	function about_beyond_academics($var) {
		$data['webpagename'] = 'aboutbeyond';
		$data['main']        = 'aboutbeyond';
		$data['content']     = $this->Cms_fm->getAboutus($var);
		$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
		$this->load->vars($data);
		$this->load->view('templates/gallery_content');
	}

	function academics_methodology($var) {
		$data['webpagename'] = 'category_content';
		$data['main']        = 'category_content';
		$data['content']     = $this->Cms_fm->getContents($var);
		$data['subcategory'] = $this->Cms_fm->getSubCategories($data['content']['id']);
		$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
		$this->load->vars($data);
		$this->load->view('templates/category_content');
	}

	function about_beyond_academics_events($var) {
		$data['webpagename'] = 'category_content';
		$data['var']         = $var;
		$data['main']        = 'category_content';
		$data['action']      = 'beyond-academics-events';
		$data['content']     = $this->Cms_fm->getContents($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {
				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['subcat']['parentcat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['subcategory'] = $this->Cms_fm->getSubCategories($data['content']['id']);
			$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']       = array();
		} else {
			$data['error']       = 1;
			$data['subcategory'] = array();
			$data['banners']     = array();
		}
		$this->load->vars($data);
		$this->load->view('templates/category_content');
	}

	function beyond_academics_events($var) {
		$data['webpagename'] = 'category_content';
		$data['main']        = 'category_content';
		$data['action']      = 'events';
		$data['content']     = $this->Cms_fm->getContents($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {
				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['subcat']['parentcat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['subcategory'] = $this->Cms_fm->getSubCategories($data['content']['id']);
			$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']       = array();
		} else {
			$data['error']       = 1;
			$data['subcategory'] = array();
			$data['banners']     = array();
		}

		$this->load->vars($data);
		$this->load->view('templates/category_content');
	}

	function beyond_academics_creativity($var) {
		$data['webpagename'] = 'category_content';
		$data['main']        = 'category_content';
		$data['action']      = 'creativity';
		$data['content']     = $this->Cms_fm->getContents($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {
				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['subcat']['parentcat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['subcategory'] = $this->Cms_fm->getSubCategories($data['content']['id']);
			$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']       = array();
		} else {
			$data['error']       = 1;
			$data['subcategory'] = array();
			$data['banners']     = array();
		}
		$this->load->vars($data);
		$this->load->view('templates/category_content');
	}

	function beyond_academics_facilities($var) {
		$data['webpagename'] = 'category_content';
		$data['main']        = 'category_content';
		$data['action']      = 'facilities';
		$data['content']     = $this->Cms_fm->getContents($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {
				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['subcat']['parentcat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['subcategory'] = $this->Cms_fm->getSubCategories($data['content']['id']);
			$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']       = array();
		} else {
			$data['error']       = 1;
			$data['subcategory'] = array();
			$data['banners']     = array();
		}

		$this->load->vars($data);
		$this->load->view('templates/category_content');
	}

	function about_methodology($var) {
		$data['webpagename'] = 'about_methodology';
		$data['main']        = 'category_content';
		$data['action']      = 'methodology';
		$data['content']     = $this->Cms_fm->getContents($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {
				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['subcat']['parentcat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['subcategory'] = $this->Cms_fm->getSubCategories($data['content']['id']);
			$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']       = array();
		} else {
			$data['error']       = 1;
			$data['subcategory'] = array();
			$data['banners']     = array();
		}
		$this->load->vars($data);
		$this->load->view('templates/category_content');
	}

	function about_preschool($var) {
		$data['webpagename'] = 'about_preschool';
		$data['main']        = 'category_content';
		$data['action']      = 'pre-schools';
		$data['content']     = $this->Cms_fm->getContents($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {
				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['subcat']['parentcat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['subcategory'] = $this->Cms_fm->getSubCategories($data['content']['id']);
			$data['images']      = $this->Cms_fm->getAboutusImage($data['content']['id']);
			$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']       = array();
		} else {
			$data['error']       = 1;
			$data['subcategory'] = array();
			$data['banners']     = array();
			$data['images']      = array();
		}
		$this->load->vars($data);
		$this->load->view('templates/category_content');
	}

	function beyond_academics_extra_curriculum($var) {
		$data['webpagename'] = 'about_methodology';
		$data['main']        = 'category_content';
		$data['action']      = 'extra-curriculum';
		$data['content']     = $this->Cms_fm->getContents($var);
		if ($data['content']) {
			$data['maincat'] = $this->Cms_fm->getSecondCategory($data['content']['parentcat']);
			if ($data['maincat']['parentcat']) {
				$data['subcat'] = $this->Cms_fm->getThirdCategory($data['maincat']['parentcat']);
				if ($data['subcat']['parentcat']) {
					$data['subsubcat'] = $this->Cms_fm->getFourthCategory($data['subcat']['parentcat']);
				}
			}
			$data['subcategory'] = $this->Cms_fm->getSubCategories($data['content']['id']);
			$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
			$data['error']       = array();
		} else {
			$data['error']       = 1;
			$data['subcategory'] = array();
			$data['banners']     = array();

		}

		$this->load->vars($data);
		$this->load->view('templates/category_content');
	}

	function searchpages() {
		if (isset($_GET['term'])) {
			$q = strtolower($_GET['term']);
			$this->Cms_fm->get_search($q);
		}
	}

	function getMethod() {
		$data = $this->Cms_fm->getMethod();
		echo $data['url'];
	}

	function search($param = '') {
		$data['webpagename'] = 'search';
		$data['main']        = 'search';
		if (isset($param)) {
			$data['param'] = $param;
		} else {
			$data['param'] = '';
		}
		$data['banners'] = $this->Cms_fm->getBanners(81);
		$this->load->vars($data);
		$this->load->view('templates/gallery_content');
	}

	function search_ajax() {
		$start_row = $this->uri->segment(2);
		$param     = $_POST['param'];
		if (trim($start_row) == '') {
			$start_row = 0;
		}
		$limit = 8;
		$this->load->library('pagination');
		$config["base_url"]       = base_url()."search_ajax/";
		$config["total_rows"]     = $this->Cms_fm->countPages($param);
		$config["full_tag_open"]  = "<ul>";
		$config["full_tag_close"] = "</ul>";
		$config["num_tag_open"]   = "<li class='numtag'>";
		$config["num_tag_close"]  = "</li>";
		$config["cur_tag_open"]   = "<li class='curtag'><b>";
		$config["cur_tag_close"]  = "</b></span></li>";
		$config["prev_link"]      = 'Prev';
		$config["next_link"]      = 'Next';
		$config["prev_tag_open"]  = "<li class='prevtag  nicdark_btn nicdark_bg_orange medium nicdark_shadow nicdark_radius white nicdark_press white'>";
		$config["prev_tag_close"] = "</li>";
		$config["next_tag_open"]  = "<li class='nexttag  nicdark_btn nicdark_bg_green medium nicdark_shadow nicdark_radius white nicdark_press white'>";
		$config["next_tag_close"] = "</li>";
		$config["first_link"]     = "<li style='float:left'>&lsaquo; First";
		$config["first_link"]     = "</li>";
		$config["last_link"]      = "<li>Last &rsaquo;";
		$config["last_link"]      = "</li>";
		$config["per_page"]       = $limit;
		$this->pagination->initialize($config);
		$data['keyword']    = $param;
		$data["search"]     = $this->Cms_fm->getPages($param, $config["per_page"], $start_row);
		$data["pagination"] = $this->pagination->create_links();
		$_html              = $this->load->view('loadSearch', $data, TRUE);
		echo $_html;
	}

	function footerSendEmail() {
		if ($this->input->is_ajax_request()) {
			if ($_POST['fname'] == '' || $_POST['femail'] == '' || $_POST['fmessage'] == '') {
				echo "1";
				die;
			} else {
				$this->Cms_fm->sendFooterEmail();
				$this->Cms_fm->sendMail_sender();
			}
		}
	}

	function admissions() {
		$var                 = 'admissions';
		$data['webpagename'] = 'admission';
		$data['main']        = 'admission_content';
		$data['content']     = $this->Cms_fm->getAboutusOblyContent($var);
		$data['images']      = $this->Cms_fm->getAboutusImage($data['content']['id']);
		$data['banners']     = $this->Cms_fm->getBanners($data['content']['id']);
		$this->load->vars($data);
		$this->load->view('templates/gallery_content');
	}

	function campus_tour() {
		$data['webpagename'] = 'campus_tour';
		$data['main']        = 'campus_tour';
		$data['banners']     = $this->Cms_fm->getBanners(83);
		$this->load->vars($data);
		$this->load->view('templates/campus_tour');
	}

	function announcement() {
		$data['webpagename'] = 'announcement';
		$data['main']        = 'announcement';
		$data['events']      = $this->Cms_fm->getAllUpComingEvents();
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function infographic() {
		if ($this->uri->segment(1) == 'infographic') {
			redirect('what-we-learn-becomes-a-part-of-who-we-are');
		}
		$data['webpagename'] = 'infographics';
		$data['main']        = 'infographics';
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function careers() {
		$data['webpagename'] = 'careers';
		$data['main']        = 'careers';
		$data['content']     = $this->Cms_fm->getCareerContent(87);
		$data['banners']     = $this->Cms_fm->getBanners(87);
		$data['openings']    = $this->Cms_fm->getOpenings();
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function sendCareerMail() {
		$this->load->library('form_validation');
		if ($this->input->post('btnsubmit')) {
			$this->form_validation->set_error_delimiters('<p style="color:red;margin-top:45px;">', '</p>');
			$this->form_validation->set_rules('name', 'Full Name', 'required|max_length[100]');
			$this->form_validation->set_rules('phone', 'Phone', 'required|min_length[10]|max_length[12]');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('postapply', 'Post Apply', 'required');
			if (empty($_FILES['resume']['name'])) {
				$this->form_validation->set_rules('resume', 'Resume', 'required');
			}

			if ($this->form_validation->run() == false) {
				$data['webpagename'] = 'careers';
				$data['main']        = 'careers';
				$this->load->vars($data);
				$this->load->view('templates/aboutus');

			} else {

				$this->Cms_fm->sendCareerMail();
				$this->Cms_fm->sendCareerMail_sender();
				$this->session->set_flashdata('success', 'Your career inquiry has been sent.');
				redirect('careers');

			}
		} else {
			$data['webpagename'] = 'careers';
			$data['main']        = 'careers';
			$this->load->vars($data);
			$this->load->view('templates/aboutus');
		}
	}

	function blog($blog) {
		$data['webpagename'] = 'blog';
		$data['main']        = 'blog';
		$data['content']     = array('meta_title' => "Physical Activity & Child's Mental Development",'meta_description'=>"Physical benefits are not the only reason why children should exercise on a consistent basis. Many studies show that there is a positive correlation between the child's physical activity and their mental development.");
		
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function educate_to_inspire() {
		$data['webpagename'] = 'blog';
		$data['main']        = 'educate_to_inspire';
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function values_and_ethics_importance() {
		$data['webpagename'] = 'blog';
		$data['main']        = 'values_and_ethics_importance';
		$data['content']     = array('meta_title' => "Importance Of Values And Ethics In Education",'meta_description'=>"A value is an enduring belief that a specific mode of conduct or end-state of existence is personally or socially preferable to an opposite or converse mode of conduct or end-state of existence");
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function hygiene_habits() {
		$data['webpagename'] = 'blog';
		$data['main']        = 'hygiene_habits';
		$data['content']     = array('meta_title' => "9 Hygiene Habits That Children Should Develop At School",'meta_description'=>"Remind students of the importance of good hygiene every day. Explain to them that, although germs may not be visible to the eyes, they are still found on their hands, in air particles & can make them very sick");
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function clay_modelling() {
		$data['webpagename'] = 'blog';
		$data['main']        = 'clay_modelling';
		$data['content']     = array('meta_title' => "Clay Modeling: Giving Shape To Creative and Colourful Visions",'meta_description'=>"People around them play a vital role Parents, teachers, classmates, friends shape their persona & psyche. A child's environment shapes them & they start to adapt a specific style of thinking & doing things.");
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function good_manners_are_assets() {
		$data['webpagename'] = 'blog';
		$data['main']        = 'good_manners_are_assets';
		$data['content']     = array('meta_title' => "Good Manners Are Assets That Never Go Out Of Style",'meta_description'=>"Teach children to be honest and courteous and behave responsibly when they are out alone with their friends. If you don’t allow cursing, unkind harsh words at home, there is high chance that they won't do it outside either");
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function importance_of_guidance_and_counselling() {
		$data['webpagename'] = 'blog';
		$data['main']        = 'importance_of_guidance_and_counselling';
		$data['content']     = array('meta_title' => "Importance Of Guidance & Counselling In A Student's Life with Nalanda School",'meta_description'=>"Guidance & counselling are important for children, & schools have a huge role in bringing out the best in children. Through counselling, children are given advice on how to manage and deal with emotional conflict and personal problems");
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function tips_on_how_to_engage_students_in_learning_activities() {
		$data['webpagename'] = 'blog';
		$data['content']     = array('meta_title' => "5 Golden Tips On How To Engage Students in Learning Activities",'meta_description'=>"Children are easily distracted by little things, it's important to draw their attention using different techniques. Find out what the students are passionate about & then effectively use these interests as natural motivators");
		$data['main']        = 'tips_on_how_to_engage_students_in_learning_activities';
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function online_payments()
	{
		$var                 = 'Online Payments';
		$data['webpagename'] = 'online_payments';
		$data['main']='online_payments';
		$data['banners']     = array();
		$this->load->vars($data);
		$this->load->view('templates/gallery_content');
	}

	function  effective_ways_to_make_learning_a_fun_activity() {
		$data['webpagename'] = 'blog';
		$data['content']     = array('meta_title' => "4 Effective Ways To Make Learning A Fun Activity",'meta_description'=>"It's actually hard to concentrate when your brain is constantly responding to a certain amount of stress. Therefore, it is essential that learning is made fun for them.");
		$data['main']        = 'effective_ways_to_make_learning_a_fun_activity';
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function sports_and_character_development()
	{
		$data['webpagename'] = 'blog';
		$data['content']     = array('meta_title' => "Sports & Character Development",'meta_description'=>"Playing sports is essential not just for your body, but it is also beneficial for your intellect and emotions. Therefore, it's important to participate in a team sport, since it can develop good character.");
		$data['main']        = 'sports_and_character_development';
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}
	
	function tips_to_breeze()
	{
		$data['webpagename'] = 'blog';
		$data['content']     = array('meta_title' => "Tips To Breeze Through Your Study Schedules",'meta_description'=>"");
		$data['main']        = 'tips-to-breeze';
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}
	function revolutionise_the_education_sector()
	{
		$data['webpagename'] = 'blog';
		$data['content']     = array('meta_title' => "4 Trends That Will Revolutionise The Education Sector",'meta_description'=>"");
		$data['main']        = 'revolutionise-the-education-sector';
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	}

	function reignite_the_love_for_learning()
	{
		$data['webpagename'] = 'blog';
		$data['content']     = array('meta_title' => "Reignite the Love for Learning and Education in A Fun Manner",'meta_description'=>"");
		$data['main']        = 'reignite_the_love_for_learning';
		$this->load->vars($data);
		$this->load->view('templates/aboutus');
	
	}
}
?>
