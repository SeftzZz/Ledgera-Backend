						<?= $this->extend('layouts/main') ?>
                        <?= $this->section('content') ?>
                            <div class="container-xxl flex-grow-1 container-p-y">
                                <div class="card">
                                    <div class="card-datatable table-responsive pt-0">
						                <table class="dtHotel table">
						                    <thead>
						                      	<tr>
							                        <th>No.</th>
							                        <th>hotel name</th>
							                        <th>Address</th>
							                        <th>latitude</th>
							                        <th>longitude</th>
							                        <th>website</th>
							                        <th>Action</th>
						                      	</tr>
						                    </thead>
						                    <tbody></tbody>
						                </table>
					                </div>
                                </div>
                            </div>
                        <?= $this->endSection() ?>

                        <?= $this->section('scripts') ?>
                        <!-- DataTables -->
				        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') ?>" />
				        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') ?>" />
				        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') ?>" />
				        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') ?>" />
		                <script src="<?= base_url('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') ?>"></script>

		                <script>
							$(function() {
							  	'use strict';
							  	var dt_tableHotel = $('.dtHotel');

								if (dt_tableHotel.length) {
								    var dt_hotel = dt_tableHotel.DataTable({
								      	processing: true,
								        serverSide: true,
								        responsive: true,
								        ajax: {
								            url: "<?= base_url('admin/hotels/datatable') ?>",
								            type: "POST",
								            data: d => {
								                d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>'
								            }
								        },
								      
								      	order: [[2, 'desc']],
								      	dom:
								        	'<"card-header"<"head-label text-center"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
								      	buttons: [
									        {
									          extend: 'collection',
									          className: 'btn btn-label-primary dropdown-toggle me-2',
									          text: '<i class="icon-base ti tabler-show me-1"></i>Export',
									          buttons: [
									            {
									              extend: 'print',
									              text: '<i class="icon-base ti tabler-printer me-1" ></i>Print',
									              className: 'dropdown-item',
									              exportOptions: { columns: [3, 4, 5, 6, 7] }
									            },
									            {
									              extend: 'csv',
									              text: '<i class="icon-base ti tabler-file me-1" ></i>Csv',
									              className: 'dropdown-item',
									              exportOptions: { columns: [3, 4, 5, 6, 7] }
									            },
									            {
									              extend: 'excel',
									              text: 'Excel',
									              className: 'dropdown-item',
									              exportOptions: { columns: [3, 4, 5, 6, 7] }
									            },
									            {
									              extend: 'pdf',
									              text: '<i class="icon-base ti tabler-file-text me-1"></i>Pdf',
									              className: 'dropdown-item',
									              exportOptions: { columns: [3, 4, 5, 6, 7] }
									            },
									            {
									              extend: 'copy',
									              text: '<i class="icon-base ti tabler-copy me-1" ></i>Copy',
									              className: 'dropdown-item',
									              exportOptions: { columns: [3, 4, 5, 6, 7] }
									            }
									          ]
									        },
									        {
									          text: '<i class="icon-base ti tabler-plus me-1"></i> <span class="d-none d-lg-inline-block">Add New Record</span>',
									          className: 'create-new btn btn-primary'
									        }
								      	]
								    });
								    $('div.head-label').html('<h5 class="card-title mb-0">DataTable with Buttons</h5>');
								}
							});
						</script>

		                

                        <?= $this->endSection() ?>
