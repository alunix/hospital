<?php 
$string = '
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="box">
				<div class="box-title">
					<h3>
						<i class="fa fa-table"></i>
						Daftar <?=$title?>
					</h3>
					<div class="actions">
						<button data-datatable-action="reload" data-datatable-idtable="<?=$id_table?>" class="btn btn-mini content-refresh">
							<i class="fa fa-refresh"></i> Refresh
						</button>
						<button data-datatable-action="add" data-datatable-idtable="<?=$id_table?>" class="btn btn-mini">
							<i class="fa fa-plus"></i> Tambah
						</button>
						<button data-datatable-action="bulk" data-datatable-idtable="<?=$id_table?>" class="btn btn-mini">
							<i class="fa fa-trash"></i> Hapus
						</button>
						<div class="btn-group">
							<a href="#" data-toggle="dropdown" class="btn dropdown-toggle btn--icon">
								<i class="fa fa-download"></i> Export
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a data-download="excel" data-download-url="'.$c_url.'/excel"><i class="fa fa-file-excel-o fa-fw"></i> Excel (.xlsx)</a>
								</li>
								<li>
									<a data-download="pdf" data-download-url="'.$c_url.'/pdf"><i class="fa fa-file-pdf-o fa-fw"></i>Pdf (.pdf)</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="box-content nopadding">
					<table id="<?=$id_table?>" class="table table-hover table-nomargin table-striped table-bordered" 
					data-plugin="datatable-server-side" 
					data-datatable-list="<?=$datatable_list?>"
					data-datatable-edit="<?=$datatable_edit?>"
					data-datatable-delete="<?=$datatable_delete?>"
					data-datatable-colvis="true"
					data-datatable-nocolvis="-2,-3,-4"
					>
						<thead>
							<tr>
								<th class="with-checkbox" data-datatable-align="text-center">
									<input type="checkbox" name="check_all" data-datatable-checkall="true">
								</th>
								<th data-datatable-align="text-center" style="width:50px;">No.</th>';
								foreach ($non_pk as $row) {
									$string .= "\n\t\t\t\t\t\t\t\t";
									$string .= '<th data-datatable-align="text-left">'.ucwords($row['column_name']).'</th>';
								}								
$string .='
								<th data-datatable-align="text-center" style="width:100px;">Action</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $load_form ?>
';


$hasil_view_list = createFile($string, $target."views/" . $v_list_file);

?>