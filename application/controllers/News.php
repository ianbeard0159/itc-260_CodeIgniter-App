<?php
class News extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->model('news_model');
                $this->load->helper('url_helper');
                $this->config->set_item('banner', "Big Al's Big News");
        }

        public function index()
        {
                $this->config->set_item('title', 'Big News');
                $this->config->set_item('currentPage', 'news');
                $data['news'] = $this->news_model->get_news();
                $data['title'] = 'News archive';

                $this->load->view('themes/bootswatch/header', $data);
                $this->load->view('news/index', $data);
                $this->load->view('themes/bootswatch/footer');
        }

        public function view($slug = NULL)
        {
                // Remove Dashes
                $title_slug = str_replace("-", " ", $slug);
                // Convert to upper Case
                $title_slug = ucwords($title_slug);
                // Set as title
                $this->config->set_item('title', 'Big News - ' . $title_slug);
                $this->config->set_item('currentPage', 'news/view');

                $data['news_item'] = $this->news_model->get_news($slug);

                if (empty($data['news_item']))
                {
                        show_404();
                }

                $data['title'] = $data['news_item']['title'];

                $this->load->view('themes/bootswatch/header', $data);
                $this->load->view('news/view', $data);
                $this->load->view('themes/bootswatch/footer');
        }
        public function create()
                {
                $this->config->set_item('title', 'Create News');
                $this->config->set_item('currentPage', 'news/create');

                $this->load->helper('form');
                $this->load->library('form_validation');

                $data['title'] = 'Create a news item';

                $this->form_validation->set_rules('title', 'Title', 'required');
                $this->form_validation->set_rules('text', 'Text', 'required');

                if ($this->form_validation->run() === FALSE)
                {
                        // Redirect to news/create
                        $this->load->view('themes/bootswatch/header', $data);
                        $this->load->view('news/create');
                        $this->load->view('themes/bootswatch/footer');

                }
                else
                {
                        feedback('News sucessfully updated', 'notice');
                        $this->news_model->set_news();
                        // Redirect to news
                        redirect('news', 'refresh');
                }
        }
}