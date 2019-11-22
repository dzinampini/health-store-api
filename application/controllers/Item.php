<?php require APPPATH . 'libraries/REST_Controller.php';

class Item extends REST_Controller {
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }

	public function index_get($id = 0){
        if(!empty($id)){
            $data = $this->db->get_where("medical_institutions", ['id' => $id])->row_array();
        }
        else{
            $data = $this->db->get("medical_institutions")->result();
        }

        $this->response($data, REST_Controller::HTTP_OK);

        if(!empty($data))
            $this->response("No such records ", REST_Controller::HTTP_OK);       
	}

    public function patients_get($id = 0){
        if(!empty($id)){
            $data = $this->db->get_where("medical_institutions", ['id' => $id])->row_array();
        }
        else{
            $data = $this->db->get("medical_institutions")->result();
        }

        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function index_post(){
        $input = $this->input->post();
        $this->db->insert('medical_institutions',$input);
        $this->response(['inserted'], REST_Controller::HTTP_OK);
    } 

    public function index_put($id){
        $input = $this->put();
        $this->db->update('medical_institutions', $input, array('id'=>$id));
        $this->response(['updated'], REST_Controller::HTTP_OK);
    }

    public function index_delete($id){
        $this->db->delete('medical_institutions', array('id'=>$id));
        $this->response(['deleted'], REST_Controller::HTTP_OK);
    }
}