<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Identify_model extends CI_Model {

    public function list_all()
    {
        $this->load->model('user_model');
        $this->load->model('organization_model');

        $this->db->order_by('iId','asc');
        $result = $this->db->get('identify_tag')->result();
        $user_obj_arr = array();

        foreach ($result as $row) {
            $user = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
            $isOrgan = $this->organization_model->read_organ($row->oId);
            if(empty($isOrgan)) {
                $organ = 'Null';
            }
            else {
                $organ = $this->organization_model->read_organ($row->oId)->oName;
            }

            $isUser = $this->user_model->read_user($row->uId);
            $username = empty($isUser) ? 'Null' : $this->user_model->read_user($row->uId)->uName;

            $user->id      = $row->iId;
            $user->user    = '<a href="' . base_url("user/user_info/{$username}") . '">' . $username . " ({$row->uId})" . "</a>";
            $user->org     = '<a href="' . base_url("identify/manage/{$row->oId}") . '">' . $organ . " ({$row->oId})" . '</a>';

            $user->level   = $row->iLevel;
            $user->status  = $row->iStatus;
            $user->created = $row->iCreateTime;

            $user_obj_arr[] = $user;
        }

        return $user_obj_arr;

    }

    public function create_identify($uId, $oId, $level, $status = '0')
    {
        $insertArr['uId']       = $uId;
        $insertArr['oId']       = $oId;
        $insertArr['iLevel']    = $level;
        $insertArr['iStatus']   = $status;

        $this->db->insert('identify_tag', $insertArr);
    }

    public function read_identify_by_uId($uId)
    {

        $this->db->order_by('iId','asc');
        $this->db->where('uId = ', $uId);
        $result = $this->db->get('identify_tag')->result();

        return $result;

    }

    public function update_identify($iId, $uId, $oId, $level, $status = '0')
    {
        $updateArr['uId']       = $uId;
        $updateArr['oId']       = $oId;
        $updateArr['iLevel']    = $level;
        $updateArr['iStatus']   = $status;

        $this->db->where('iId', $iId);
        $this->db->update('identify_tag', $updateArr);
    }

    public function delete_identify($iId)
    {
        $this->db->where('iId', $iId);
        $this->db->delete('identify_tag');
    }

    public function list_organ_identifty($oId)
    {
        $this->db->where('oId', $oId);
        return $result = $this->db->get('identify_tag')->result();
    }
}

/* End of file identify_model.php */
/* Location: ./application/models/identify_model.php */