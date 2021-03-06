
<div class="container-fluid">

	<div class="row">
		<div class="col-sm-12">

					<table id="<?=$id_table2?>" class="table table-hover table-nomargin table-striped table-bordered" 
					data-plugin="datatable-server-side" 
					data-datatable-list="<?=$datatable_list2?>"
					data-datatable-colvis="true"
					data-datatable-daterange="true"
					data-datatable-autorefresh="true"
					data-datatable-nocolvis=""
					>
						<thead>
							<tr>
								<th data-datatable-align="text-center" style="width:50px;">No.</th>
								<th data-datatable-align="text-center">RM</th>
								<th data-datatable-align="text-left">Nama Lengkap</th>
								<th data-datatable-align="text-center">No. Identitas</th>
								<th data-datatable-align="text-center">Cara Bayar</th>
								<th data-datatable-align="text-center">Jenis Daftar</th>
								<th data-datatable-align="text-center">Poliklinik</th>
								<th data-datatable-align="text-center">Dokter</th>
								<th data-datatable-align="text-center">Tanggal</th>
								<th data-datatable-align="text-center">Status</th>
								<th data-datatable-align="text-center" style="width:170px;">Action</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
			
		</div>
	</div>
</div>
<form id="penunjang_<?=$id_table2?>" action="<?=site_url($datatable_save2)?>" method="POST">
<div class="modal fade" id="addmodalpenunjang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width:80%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Data Pembayaran</h4>
      </div>
      <div class="modal-body" id="body-addmodalpenunjang">
        <center><i class='fa fa-spinner fa-spin'></i> Memuat data...</center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        <button type="submit" onclick="$('#addmodalpenunjang').modal('hide');" class="btn btn-primary">Bayar Penunjang</button>
      </div>
    </div>
  </div>
</div>
</form>
<script>
	$(document).ready(function(){
		$('#penunjang_<?=$id_table2?>').validate({
			errorElement  : 'span',
			errorClass    : 'help-block has-error',
			errorPlacement: function (error, element) {
				if (element.parents("label").length > 0) {
					element.parents("label").after(error);
					} else {
					element.after(error);
				}
			},
			highlight     : function (label) {
				$(label).closest('.form-group').removeClass('has-error has-success').addClass('has-error');
			},
			success       : function (label) {
				label.addClass('valid').closest('.form-group').removeClass('has-error has-success').addClass('has-success');
			},
			onkeyup       : function (element) {
				$(element).valid();
			},
			onfocusout    : function (element) {
				$(element).valid();
			},
			submitHandler: function(form)
			{
				var url = $(form).attr('action');
				var method = $(form).attr('method');
				var success_url = $(form).data('success');
				var datasend = $(form).serialize();
				
				$.ajax({
					url			: url,
					type		: method,
					data		: datasend,
					dataType	: 'json',
					beforeSend	: function(){
						$(form).find('button[type="submit"]').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Saving...'); //change button text
						$(form).find('button[type="submit"]').attr('disabled',true); //set button disable 
					},
					success	: function(jdata){
						$(form).find('button[type="submit"]').html('<i class="fa fa-save fa-fw"></i> Simpan'); //change button text
						$(form).find('button[type="submit"]').attr('disabled',false); //set button enable 
						if(jdata.status) //if success close modal and reload ajax table
						{
							$('#<?=$id_table?>').DataTable().ajax.reload();
							$('.modal').modal('hide');
						}
						else
						{
							alert('terjadi error');
						}
					},
					error : function(jdata){
						$(form).find('button[type="submit"]').html('<i class="fa fa-save fa-fw"></i> Simpan'); //change button text
						$(form).find('button[type="submit"]').attr('disabled',false); //set button enable 
					}
				});
			}
		});
	});

	function addmodalpenunjang(id){
		
		$.ajax({
			type: "POST",
			url: "<?=base_url()?>billing_tindakan/form_penunjang/"+id,
			success: function(data) {
				$("#addmodalpenunjang").modal('show');
				$("#body-addmodalpenunjang").html(data);
			}
		});
		return false;
	}

	function addmodalpenunjang2(id,id_billing){
		
		$.ajax({
			type: "POST",
			url: "<?=base_url()?>billing_tindakan/form_penunjang2/"+id+"/"+id_billing,
			success: function(data) {
				$("#addmodalpenunjang").modal('show');
				$("#body-addmodalpenunjang").html(data);
			}
		});
		return false;
	}

function excel(){
	var d1=$("#konsultasi_daterange1").val();
	var d2=$("#konsultasi_daterange2").val();
	var tgl1=d1.split("/");
	var tgl2=d2.split("/");
	var t1=tgl1[2]+"-"+tgl1[1]+"-"+tgl1[0];
	var t2=tgl2[2]+"-"+tgl2[1]+"-"+tgl2[0];
	var tb = ""+$("#<?=$id_table?>").dataTable().fnSettings().aaSorting;
	var tbl = tb.split(",");
	var column_order = tbl[0];
	var dir_order = tbl[1];
	
	window.open('<?=base_url()?>konsultasi/excel?tgl1='+t1+' 00:00:00&tgl2='+t2+' 23:59:59&column_order='+column_order+'&dir_order='+dir_order);
}
function pdf(){
	var d1=$("#konsultasi_daterange1").val();
	var d2=$("#konsultasi_daterange2").val();
	var tgl1=d1.split("/");
	var tgl2=d2.split("/");
	var t1=tgl1[2]+"-"+tgl1[1]+"-"+tgl1[0];
	var t2=tgl2[2]+"-"+tgl2[1]+"-"+tgl2[0];
	var tb = ""+$("#<?=$id_table?>").dataTable().fnSettings().aaSorting;
	var tbl = tb.split(",");
	var column_order = tbl[0];
	var dir_order = tbl[1];
	
	window.open('<?=base_url()?>konsultasi/pdf?tgl1='+t1+' 00:00:00&tgl2='+t2+' 23:59:59&column_order='+column_order+'&dir_order='+dir_order);
}
	table_reload();
</script>
