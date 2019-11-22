<?php require APPPATH . 'libraries/REST_Controller.php';

class Fanvil extends REST_Controller {
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }

    public function index_get($id = 0){
        if(!empty($id)){
            $data = $this->db->get_where("suppliers", ['id' => $id])->row_array();
        }
        else{
            $data = $this->db->get("suppliers")->result();
        }

        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function index_post(){
        $input = $this->input->post();
        $this->db->insert('suppliers',$input);
        $this->response(['successful'], REST_Controller::HTTP_OK);
    } 

    public function index_put($id){
        $input = $this->put();
        $this->db->update('suppliers', $input, array('id'=>$id));
        $this->response(['successful'], REST_Controller::HTTP_OK);
    }

    public function index_delete($id){
        $this->db->delete('suppliers', array('id'=>$id));
        $this->response(['successful'], REST_Controller::HTTP_OK);
    }
}