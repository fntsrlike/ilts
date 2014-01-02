<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Identify extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('identify_model');
        $this->load->model('portal_model');
        $this->load->model('organization_model');
        $this->load->library('table');

        if ( false == $this->session->userdata('uid')) {
            redirect(base_url('portal/'));
        }
    }

    public function index()
    {
        $this->list_all();
    }

    public function list_all()
    {
        $users = $this->identify_model->list_all();

        $table = array();
        $this->table->set_template(array('table_open' => '<table class="table"'));
        $this->table->set_heading('Identify id', 'User', 'Organization', 'Level', 'Created Time');

        foreach ($users as $row) {
            $operator = '<button type="button" iid="'. $row->id .'"class="btn btn-danger identRemove">Link</button>';
            $table[] = array($row->id, $row->user, $row->org, $row->level, $row->created);
        }

        $data['table'] = $this->table->generate($table);;

        $this->load->view('header');
        $this->load->view('identify/list_all', $data);
        $this->load->view('footer');             
    }

    public function manage($oId)
    {
        $data['organ']  = empty($oId) ? "Null": "{$this->organization_model->read_organ($oId)->oName}";
        $data['oId']    = $oId;

        $this->load->view('header');
        $this->load->view('identify/manage', $data);
        $this->load->view('footer');             
    }

    public function organ_ident_list($oId)
    {
        $group = $this->identify_model->list_organ_identifty($oId);

        $table = array();

        $this->table->set_template(array('table_open' => '<table class="table"'));
        $this->table->set_heading('Identify id', 'User', 'Level', 'Created Time', 'Operator');

        foreach ($group as $row) {
            $u_id = $this->portal_model->read_user($row->uId);
            $u_id = empty($u_id) ? 'Null' : $u_id->uName;

            $i_id = $row->iId;
            $u_id = $u_id . " ({$row->uId})";

            $operator = '<button type="button" iid="'. $i_id .'"class="btn btn-danger identRemove">Delete</button>';
            $table[] = array($i_id, $u_id, $row->iLevel, $row->iCreateTime, $operator);
        }

        echo $data['table']  = $this->table->generate($table);
    }

    public function put_process()
    {
        $u_id   = $this->portal_model->read_user_by_name($this->input->post('username'))->uId;
        $o_id   = $this->input->post('oId');
        $level  = $this->input->post('level');

        $this->identify_model->create_identify($u_id, $o_id, $level);

        redirect('identify/manage/'.$o_id);
    }

    public function set_process()
    {
        $i_id   = $this->input->post('iid');
        $u_id   = $this->input->post('uid');
        $o_id   = $this->input->post('oid');
        $level  = $this->input->post('level');

        $this->identify_model->create_identify($i_id, $u_id, $o_id, $level);
    }

    public function del_process()
    {
        $i_id   = $this->input->post('id');

        $this->identify_model->delete_identify($i_id);
    }

}

/* End of file identify.php */
/* Location: ./application/controllers/identify.php */