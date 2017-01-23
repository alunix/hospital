

<div class="container-fluid">

	<div class="row">

		<div class="col-sm-12">

			<div class="box box-bordered box-color">

				<div class="box-title">

					<h3>

						<i class="fa fa-table"></i>

						Daftar <?=$title?>

					</h3>

					<div class="actions">

						<button data-datatable-action="add" data-datatable-idtable="<?=$id_table?>" class="btn btn-mini">

							<i class="fa fa-plus"></i> Tambah

						</button>

						<button data-datatable-action="bulk" data-datatable-idtable="<?=$id_table?>" class="btn btn-mini">

							<i class="fa fa-trash"></i> Hapus

						</button>

					</div>

				</div>

				<div class="box-content nopadding">

					<table id="<?=$id_table?>" class="table table-hover table-nomargin table-striped table-bordered" 

					data-plugin="datatable-server-side" 

					data-datatable-list="<?=$datatable_list?>"

					data-datatable-edit="<?=$datatable_edit?>"

					data-datatable-delete="<?=$datatable_delete?>"

					data-datatable-colvis="true"

					data-datatable-multiselect="true"

					data-datatable-nocolvis=""

					>

						<thead>

							<tr>

								<th class="with-checkbox" data-datatable-align="text-center">

									<input type="checkbox" name="check_all" data-datatable-checkall="true">

								</th>

								<th data-datatable-align="text-center" style="width:50px;">No.</th>

								<th data-datatable-align="text-left">Nama</th>

								<th data-datatable-align="text-left">No_Rekening</th>

								<th data-datatable-align="text-left">Parent_id</th>

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

