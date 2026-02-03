						<?= $this->extend('layouts/main') ?>
                        <?= $this->section('content') ?>
                            <div class="container-xxl flex-grow-1 container-p-y">
                                <div class="card">
                                    <div class="card-datatable table-responsive pt-0">
						                <table class="dtHotel table">
						                    <thead>
						                      	<tr>
							                        <!-- <th>No.</th> -->
							                        <th></th>
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

								        columns: [
									        { data: null },          // responsive control
									        { data: 'hotel_name' },
									        { data: 'location' },
									        { data: 'latitude' },
									        { data: 'longitude' },
									        { data: 'website' },
									        { data: 'action' }           // actions
									    ],

								        columnDefs: [
									        // Responsive control
									        {
									          className: 'control',
									          orderable: false,
									          searchable: false,
									          targets: 0,
									          render: () => ''
									        },

									        {
									          targets: 1,
									          responsivePriority: 1,
									          render: function (data) {
									            return `
									              <div class="d-flex align-items-center">
									                <span class="fw-medium">${data}</span>
									              </div>
									            `;
									          }
									        }
									    ],
								      
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
									              exportOptions: { columns: [3, 4, 5, 6] }
									            },
									            {
									              extend: 'csv',
									              text: '<i class="icon-base ti tabler-file me-1" ></i>Csv',
									              className: 'dropdown-item',
									              exportOptions: { columns: [3, 4, 5, 6] }
									            },
									            {
									              extend: 'excel',
									              text: 'Excel',
									              className: 'dropdown-item',
									              exportOptions: { columns: [3, 4, 5, 6] }
									            },
									            {
									              extend: 'pdf',
									              text: '<i class="icon-base ti tabler-file-text me-1"></i>Pdf',
									              className: 'dropdown-item',
									              exportOptions: { columns: [3, 4, 5, 6] }
									            },
									            {
									              extend: 'copy',
									              text: '<i class="icon-base ti tabler-copy me-1" ></i>Copy',
									              className: 'dropdown-item',
									              exportOptions: { columns: [3, 4, 5, 6] }
									            }
									          ]
									        },
									        {
									          text: '<i class="icon-base ti tabler-plus me-1"></i> <span class="d-none d-lg-inline-block">Add New Record</span>',
									          className: 'create-new btn btn-primary'
									        }
								      	],

								      	responsive: {
								        	details: {
								          		display: $.fn.dataTable.Responsive.display.modal({
								            		header: row =>
								              		`Details of ${row.data().hotel_name}`
								          		}),
								          		renderer: function (api, rowIdx, columns) {
								            		const data = $.map(columns, col =>
									              		col.title
										                ? `<tr>
										                     <td>${col.title}:</td>
										                     <td>${col.data}</td>
										                   </tr>`
										                : ''
								            		).join('');
								            		return data
										            ? $('<table class="table"><tbody/></table>').append(data)
										            : false;
								          		}
								        	}
								      	}
								    });
								    $('div.head-label').html('<h5 class="card-title mb-0">DataTable with Buttons</h5>');
								}
							});
						</script>

		                

                        <?= $this->endSection() ?>
