<?php require APPPATH . 'libraries/REST_Controller.php';

class Fanvil extends REST_Controller {

    var $table_name = 'suppliers'; 

    public function __construct() {
       parent::__construct();
       $this->load->database();
    }

    public function index_get($id = 0){
        // the id in parameters is a uri segment 
        if(!empty($id)){
            $data = $this->db->get_where($this->table_name, ['id' => $id])->row_array();
        }
        else{
            $data = $this->db->get($this->table_name)->result();
        }

        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function index_post(){
        $input = $this->input->post();
        // alternatively 
        // 'supplier'=>$this->input->post('parameter_name');
        $this->db->insert($this->table_name,$input); //sent as form data in postman 
        $this->response(['successful'], REST_Controller::HTTP_OK);
    } 

    public function index_put($id){
        // the id in parameters is a uri segment 
        $input = $this->put(); // sent as raw data in post man 
        // $input = $this->input->post();
        $this->db->update($this->table_name, $input, array('id'=>$id));
        $this->response(['successful'], REST_Controller::HTTP_OK);
    }

    public function index_delete($id){
        // the id in parameters is a uri segment 
        $this->db->delete($this->table_name, array('id'=>$id));
        $this->response(['successful'], REST_Controller::HTTP_OK);
    }
}