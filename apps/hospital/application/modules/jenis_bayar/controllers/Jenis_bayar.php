<?php
/**
	* CodeIgniter Core Model
	*
	* @package         CodeIgniter
	* @subpackage      Controller
	* @category        Jenis_bayar Controller
	* @author          Amir Mufid
	* @version         1.1
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_bayar extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		$this->load->model('Jenis_bayar_model');
		$config['table'] = 'ms_jenis_bayar';
		$config['column_order'] = array(null,'id','nama','no_kartu','bank','aktif',null);
		$config['column_search'] = array('id','nama','no_kartu','bank','aktif');
		$config['column_excel'] = array('nama','no_kartu','bank','aktif');
		$config['column_pdf'] = array('nama','no_kartu','bank','aktif');
		$config['order'] = array('id' => 'asc');
		$this->Jenis_bayar_model->initialize($config);
    }

    public function index()
    {
		$data['title'] = 'Jenis_bayar';
		$data['id_table'] = 'jenis_bayar';
		$data['datatable_list'] = 'jenis_bayar/ajax_list';
		$data['datatable_edit'] = 'jenis_bayar/ajax_edit';
		$data['datatable_delete'] = 'jenis_bayar/ajax_delete';
		$data['datatable_save'] = 'jenis_bayar/ajax_save';
		$data['load_form'] = $this->load_form($data);
		$this->template->display('jenis_bayar',$data);
    }

    public function load_form($data)
	{
		return $this->load->view('form_jenis_bayar',$data,true);
	}

    public function ajax_list()
	{	
		$list = $this->Jenis_bayar_model->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $row) {
			$no++;
			$fields = array();
			$fields[] = $row->id;
			$fields[] = $no;
			
			 $fields[] = $row->nama;
			 $fields[] = $row->no_kartu;
			 $fields[] = $row->bank;
			 $fields[] = $row->aktif;
			$fields[] = $row->id;
			
			$data[] = $fields;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Jenis_bayar_model->count_all(),
			"recordsFiltered" => $this->Jenis_bayar_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_edit($id=0)
	{
		$data_object = $this->Jenis_bayar_model->get_by_id($id);
		if($data_object)
		{
			$list_fields = array(
			 'id',
			 'nama',
			 'no_kartu',
			 'bank',
			 'aktif',
			);
			
			$fields = $this->Jenis_bayar_model->list_fields($list_fields);
			$data = (array) $data_object;
			
			foreach($fields as $meta){
				$data_new['name'] = $meta->name;
				$data_new['value'] = $data[$meta->name];
				$data_array[] = $data_new;
			}
			
			$result['status'] = 0;
			$result['data_array'] = $data_array;
			$result['data_object'] = $data_object;
			$response['response'] = $result;
		}
		else
		{
			$result['status'] = 99;
			$response['response'] = $result;
		}
		echo json_encode($response);
	}

	public function ajax_save()
	{
		$post = $this->input->post(NULL,TRUE);
		$data = array(
			 'nama' => $post['nama'],
			 'no_kartu' => $post['no_kartu'],
			 'bank' => $post['bank'],
			 'aktif' => $post['aktif']
		);
			
		if(empty($post['id']))
		{
			$result = $this->Jenis_bayar_model->insert($data);
		}
		else
		{
			$result = $this->Jenis_bayar_model->update(array('id' => $post['id']), $data);
		}
		
		echo json_encode(array("status" => true));
		
	}
  

	public function ajax_delete()
	{
		$post = $this->input->post(NULL,TRUE);
		$id = $post['id'];
		if(!is_array($id)){
			$id[] = $id;
		}
		$this->Jenis_bayar_model->delete($id);
		echo json_encode(array("status" => TRUE));
	}
  
	public function excel()
	{
		$this->load->library("Excel");

		$query = $this->Jenis_bayar_model->data_excel("jenis_bayar");
		$this->excel->export($query);
	}
	
	public function pdf()
	{
		$this->load->library("Chtml2pdf");
		$this->load->library("Header_file");
		
		$query = $this->Jenis_bayar_model->data_pdf();
		$data['header'] = $this->header_file->pdf('100%');
		$data['query'] = $query;
		$content = $this->load->view('pdf_jenis_bayar',$data,true);
		$this->chtml2pdf->cetak("P","A4",$content,"Jenis_bayar","I"); 
	}
}
?>
