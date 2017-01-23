<?php
/**
	* CodeIgniter Core Model
	*
	* @package         CodeIgniter
	* @subpackage      Controller
	* @category        Billing Controller
	* @author          Amir Mufid
	* @version         1.1
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_konsultasi extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		$this->load->model('Billing_model');
		$config['table'] = 'trs_billing';
		$config['column_order'] = array(
			null,
			'rm',
			'ms_pasien.nama_lengkap',
			'ms_pasien.no_identitas',
			'ms_cara_bayar.nama',
			'ms_poliklinik.nama',
			'trs_billing.id_ms_penunjang',
			'trs_billing.nominal',
			'trs_billing.add_time',
			'trs_billing.status',
			null
		);
		
			
		$config['column_search'] = array(
			'ms_pasien.rm',
			'ms_pasien.nama_lengkap',
			'ms_pasien.tipe_identitas',
			'ms_pasien.no_identitas',
			'ms_cara_bayar.nama',
			'ms_poliklinik.nama',
			'trs_billing.status',
			'trs_billing.nominal',
			'trs_billing.add_time'
		);
		$config['column_excel'] = array(
			'ms_pasien.rm',
			'ms_pasien.nama_lengkap',
			'ms_pasien.tipe_identitas',
			'ms_pasien.no_identitas',
			'ms_cara_bayar.nama',
			'ms_poliklinik.nama',
			'trs_billing.status',
			'trs_billing.nominal',
			'trs_billing.add_time'
		);
		$config['column_pdf'] = array(
			'ms_pasien.rm',
			'ms_pasien.nama_lengkap',
			'ms_pasien.tipe_identitas',
			'ms_pasien.no_identitas',
			'ms_cara_bayar.nama',
			'ms_poliklinik.nama',
			'trs_billing.status',
			'trs_billing.nominal',
			'trs_billing.add_time'
		);
		$config['order'] = array('trs_billing.status' => 'ASC','trs_billing.last_update' => 'Desc');
		$this->Billing_model->initialize($config);
    }

    public function index()
    {
		$p = $this->input->get('p');
		$data['title'] = 'Billing Tindakan';
		$data['id_table'] = 'billing_konsultasi';
		$data['datatable_list'] = 'billing_konsultasi/ajax_list/'.$p;
		$data['datatable_edit'] = 'billing_konsultasi/ajax_edit';
		$data['datatable_delete'] = 'billing_konsultasi/ajax_delete';
		$data['datatable_save'] = 'billing_konsultasi/ajax_save';
		$data['cara_bayar'] = $this->db->get('ms_cara_bayar')->result_array();
		$data['poliklinik'] = $this->db->get('ms_poliklinik')->result_array();
		$data['penunjang'] = $this->db->select('id,nama')
		->get('ms_penunjang')->result_array();
		$data['load_form'] = $this->load_form($data);
		
		$this->template->display('billing',$data);
    }
	
	
    public function load_form($data)
	{
		$data['id_table'] = 'billing_konsultasi';
		$data['jenis_bayar'] = $this->db->get('ms_jenis_bayar')->result_array();
		return $this->load->view('form_billing',$data,true);
	}
	
	public function pembayaran(){
		$p = $this->input->get('p');
		
		$post = $this->input->post(NULL,TRUE);
			$billing = $this->db->select('
			trs_billing.id,
			trs_billing.id_appointment,
			ms_komponen_registrasi.nama as nama_komponen,
			trs_billing.nominal as total_bayar
		')
		->where('trs_billing.id',element('id',$post))
		->join('ms_komponen_registrasi','ms_komponen_registrasi.id = trs_billing.id_komponen','inner')
		->get('trs_billing')->row_array();
		if(!empty($billing))
		{
			$billing = array_filter($billing);
		}
		echo json_encode($billing);
	}
	
	public function save_pembayaran(){
		$post = $this->input->post(NULL,TRUE);
		$update['id_jenis_bayar'] = element('id_jenis_bayar',$post);
		$update['total_bayar'] = rupiah_to_number(element('total_bayar',$post));
		$update['keterangan'] = element('keterangan',$post);
		$update = array_string_to_null($update);
		
		$this->db->where('id',element('id',$post))->update('trs_billing',$update);
		
		echo json_encode(array("status" => true));
	}

    public function ajax_list($id)
	{	
		$list = $this->Billing_model->get_datatables($id);
		
		$data = array();
		$total = 0;
		$no = $_POST['start'];
		foreach ($list as $row) {
			$no++;
			$fields = array();
			$fields[] = $no;
			
			$fields[] = $row->rm;
			$fields[] = $row->nama_lengkap;
			$fields[] = $row->tipe_identitas.' '.$row->no_identitas;
			$fields[] = $row->cara_bayar;
			$fields[] = $row->poliklinik;
			if($row->id_komponen == 5){
			$fields[] = get_field(get_field($row->id_penunjang,'trs_penunjang','id_ms_penunjang'),'ms_penunjang');
			}else{
			$fields[] = get_field($row->id_komponen,'ms_komponen_registrasi');
			}
			$fields[] = rupiah($row->nominal);
			$fields[] = convert_tgl($row->add_time,'d M Y H:i',1);
			$fields[] = ($row->status == 1)?'<i class="label label-success">Sudah Bayar</i>':'<i id="label_'.$row->id.'" class="label label-danger">Belum Bayar</i>';
			$fields[] = ($row->status == 1)?
				'<a href="'.site_url('billing_konsultasi/kwitansi/'.$row->id_pasien.'/'.$row->id).'" target="_BLANK" class="btn btn-default btn-block" rel="tooltip" title="Ubah data"><i class="fa fa-print"></i> Kwitansi</a>'
				:
				'<button type="button" onclick="modal_pembayaran('.$row->id.')" type="button" class="btn btn-default btn-block" rel="tooltip" title="Cetak Kartu"><i class="fa fa-money"></i> Bayar</button>'
			;
			
			$data[] = $fields;
			if($row->status == 1){
				$total += $row->nominal;
			}
		}
			$fields = array();
			$fields[] = '';
			
			$fields[] = '';
			$fields[] = '';
			$fields[] = '';
			$fields[] = '';
			$fields[] = '';
			$fields[] = '<center><b>Total</b></center>';
			$fields[] = '<b>'.rupiah($total).'</b>';
			$fields[] = '';
			$fields[] = '';
			$fields[] = '';
			
			$data[] = $fields;

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Billing_model->count_all($id),
			"recordsFiltered" => $this->Billing_model->count_filtered($id),
			"data" => $data,
		);

		echo json_encode($output);
	}
	
	public function ajax_save()
	{
		$post = $this->input->post(NULL,TRUE);
		$data = array(
			 'id_pasien' => $post['id_pasien'],
			 'id_komponen' => $post['id_komponen'],
			 'no_tagihan' => $post['no_tagihan'],
			 'nominal' => $post['nominal'],
			 'total_bayar' => $post['total_bayar'],
			 'sisa' => $post['sisa'],	
			'last_user'=>$this->session->userdata('username')
		);
			
		if(empty($post['id']))
		{
			$data['add_time'] = date('Y-m-d H:i:s');
			$result = $this->Billing_model->insert($data);
		}
		else
		{
			$result = $this->Billing_model->update(array('id' => $post['id']), $data);
		}
		
		echo json_encode(array("status" => true));
		
	}
  
	public function excel()
	{
		$this->load->library("Excel");

		$query = $this->Billing_model->data_excel("billing");
		$this->excel->export($query);
	}
	
	public function pdf($p)
	{
		$get = $this->input->get(NULL,TRUE);
		$this->load->library("Chtml2pdf");
		$this->load->library("Header_file");
		$w="";
		$x="";
		$data['header'] = $this->header_file->pdf('100%');
		if(!empty($get['cara_bayar'])){
			$w.=" AND id_cara_bayar='".element('cara_bayar',$get)."'";
		}
		
		if(!empty($get['id_poliklinik'])){
			$w.=" AND id_poliklinik='".element('id_poliklinik',$get)."'";
		}
		
		if(!empty($get['id_ms_penunjang'])){
			$w.=" AND id_ms_penunjang='".element('id_ms_penunjang',$get)."'";
		}
		
		$column_order = array(
			null,
			'rm',
			'ms_pasien.nama_lengkap',
			'ms_pasien.no_identitas',
			'ms_cara_bayar.nama',
			'ms_poliklinik.nama',
			'trs_billing.id_ms_penunjang',
			'trs_billing.nominal',
			'trs_billing.add_time',
			'trs_billing.status',
			null
		);
		
		if(!empty($get['column_order']) || !empty($column_order[$get['column_order']])){
			$x = "ORDER BY ".$column_order[$get['column_order']]." ".$get['dir_order'];
		}else{
			$x = "ORDER BY
				`trs_billing`.`status` ASC,
				`ms_pasien`.`rm` DESC";
		}
		$sql = "
			SELECT
				`trs_billing`.`id`,
				`ms_pasien`.`id` AS `id_pasien`,
				`ms_pasien`.`rm`,
				`ms_pasien`.`nama_lengkap`,
				`ms_pasien`.`tipe_identitas`,
				`ms_pasien`.`no_identitas`,
				`ms_cara_bayar`.`nama` AS `cara_bayar`,
				`ms_poliklinik`.`nama` AS `poliklinik`,
				`trs_billing`.`status` AS `status`,
				`trs_billing`.`nominal` AS `nominal`,
				`ms_komponen_registrasi`.`nama` AS `komponen`,
				trs_billing.id_komponen,
				trs_billing.id_penunjang,
				`trs_billing`.`add_time` AS `add_time`
			FROM
				`trs_billing`
			INNER JOIN `ms_komponen_registrasi` ON `ms_komponen_registrasi`.`id` = `trs_billing`.`id_komponen`
			INNER JOIN `trs_appointment` ON `trs_appointment`.`id` = `trs_billing`.`id_appointment`
			INNER JOIN `trs_status` ON `trs_appointment`.`id` = `trs_status`.`id_appointment`
			INNER JOIN `ms_pasien` ON `ms_pasien`.`id` = `trs_appointment`.`id_pasien`
			INNER JOIN `ms_cara_bayar` ON `ms_cara_bayar`.`id` = `trs_appointment`.`id_cara_bayar`
			INNER JOIN `ms_poliklinik` ON `ms_poliklinik`.`id` = `trs_appointment`.`id_poliklinik`
			WHERE
				DATE(trs_billing.add_time) >= '".element('tgl1',$get)."'
			AND DATE(trs_billing.add_time) <= '".element('tgl2',$get)."'
			AND `trs_status`.`status` = 'billing'
			AND `trs_billing`.`id_komponen` IN (4,5)
			AND `trs_billing`.`total_bayar` > 0
			$w
			$x
			";
		
		$data['query'] = $this->db->query($sql)->result_array();
		$content = $this->load->view('pdf_billing',$data,true);
		$this->chtml2pdf->cetak("P","A4",$content,"Billing","I"); 
	}
	
	public function kwitansi($id_pasien,$id_billing) {
        $this->load->library('fpdf');
        $this->load->library('currency');
		
		$data['identitas']	= $this->db->get('ms_identitas')->row_array();
        $data['pasien'] 	= $this->db->get_where('ms_pasien',array('id'=>$id_pasien))->row_array();
        $data['payment'] 	= $payment = $this->db->get_where('trs_billing',array('id'=>$id_billing))->row_array();
		$data['penanggung_jawab'] = "Nurlaela Sari";
		$data['nip'] = "1976006292007012006";
        //var_dump($data);
        $this->load->view('kwitansi', $data);
    }
}
?>
