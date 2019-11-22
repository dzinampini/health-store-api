<?php class DonorsModel extends CI_model{
	public function donors($id){
		$this->db->select('*');
	    if($id != 'all') $this->db->where('id', $id);
	    $this->db->order_by('surname', 'ASC');  
	    $this->db->from('donors');    
	    $q=$this->db->get();

	    if($q->num_rows() > 0){
	      foreach($q->result() as $row){
	        $data[]=$row;
	      }
	      return $data;
	    }
	}

	public function addDonor($data){
	    $this->db->insert('donors', $data);
	}

	public function updateDonor($data){
		$this->db->where('id', $data['id']);
	    $this->db->update('donors', $data);
	}

	public function deleteDonor($id){
		$this->db->where('id', $id);
	    $this->db->delete('donors');
	}

	public function feesDonations($id, $donation, $donor){
		$this->db->select('*');
	    if($id != 'all') $this->db->where('id', $id);
	    if($donation != 'all') $this->db->where('donation', $id);
	    if($donor != 'all') $this->db->where('donor', $id);
	    $this->db->order_by('dt', 'ASC');  
	    $this->db->from('fees_donations');    
	    $q=$this->db->get();

	    if($q->num_rows() > 0){
	      foreach($q->result() as $row){
	        $data[]=$row;
	      }
	      return $data; 
	    }
	}

	public function organDonations($id, $donation, $donor){
		$this->db->select('*');
	    if($id != 'all') $this->db->where('id', $id);
	    if($donation != 'all') $this->db->where('donation', $id);
	    if($donor != 'all') $this->db->where('donor', $id);
	    $this->db->order_by('dt', 'ASC');  
	    $this->db->from('organ_funding');    
	    $q=$this->db->get();

	    if($q->num_rows() > 0){
	      foreach($q->result() as $row){
	        $data[]=$row;
	      }
	      return $data; 
	    }
	}
}