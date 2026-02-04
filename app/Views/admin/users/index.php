						<?= $this->extend('layouts/main') ?>
                        <?= $this->section('content') ?>
                            <div class="container-xxl flex-grow-1 container-p-y">
                                <div class="card">
                                    <div class="card-datatable table-responsive pt-0">
						                <table class="dtUser table">
						                    <thead>
						                      	<tr>
							                        <th></th>
							                        <th>No.</th>
							                        <th>Hotel Name</th>
							                        <th>Address</th>
							                        <th>Latitude</th>
							                        <th>Longitude</th>
							                        <th>Website</th>
							                        <th>Action</th>
						                      	</tr>
						                    </thead>
						                    <tbody></tbody>
						                </table>
					                </div>

					                <!-- add modal form -->
					                <div class="modal fade" id="modalAddHotel" tabindex="-1" aria-hidden="true">
									    <div class="modal-dialog modal-lg modal-dialog-centered">
									        <div class="modal-content">
									            <form id="formAddHotel" enctype="multipart/form-data">
									                <div class="modal-header">
									                    <h5 class="modal-title">Add New Hotel</h5>
									                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
									                </div>

									                <div class="modal-body">
									                	<div class="row">
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label">Hotel Name</label>
									                        	<input type="text" class="form-control" name="hotel_name" required>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label">Founded</label>
									                        	<input type="text" class="form-control" name="founded" required>
									                        </div>
									                    </div>
									                    <div class="row">
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label">Total Employees</label>
									                        	<input type="text" class="form-control" name="tot_employees" required>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label">Address</label>
									                        	<input type="text" class="form-control" name="location" id="add_location" required>
									                        </div>
									                    </div>
									                    <div class="row">
									                        <div class="col-md-6 mb-3">
									                            <input type="hidden" name="latitude" id="add_latitude">
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <input type="hidden" name="longitude" id="add_longitude">
									                        </div>
									                    </div>
									                    <!-- Search Map insert hotel -->
									                    <div class="mb-2">
									                        <input type="text" id="searchAddLocation" class="form-control" placeholder="Search location...">
									                        <div id="searchAddResult" class="list-group position-absolute w-100" style="z-index:1055;"></div>
									                    </div>
									                    <!-- Map insert hotel-->
									                    <div id="mapAddHotel" style="height:300px" class="mb-3 rounded"></div>
									                    <div class="mb-3">
									                        <label class="form-label">Website</label>
									                        <input type="text" class="form-control" name="website">
									                    </div>
									                    <div class="mb-3">
									                        <label class="form-label">Description</label>
									                        <textarea class="form-control" name="desc" rows="3"></textarea>
									                    </div>
									                    <!-- LOGO -->
									                    <div class="mb-3">
									                        <label class="form-label">Logo</label>
									                        <input type="file" class="form-control" name="logo" accept="image/*">
									                    </div>

									                </div>

									                <div class="modal-footer">
									                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
									                    <button type="submit" class="btn btn-primary">Save</button>
									                </div>
									            </form>
									        </div>
									    </div>
									</div>

					                <!-- edit modal form -->
					                <div class="modal fade" id="modalEditHotel" tabindex="-1" aria-hidden="true">
										<div class="modal-dialog modal-lg modal-dialog-centered">
										    <div class="modal-content">
											    <form id="formEditHotel" enctype="multipart/form-data">
											        <div class="modal-header">
											          	<h5 class="modal-title">Edit Hotel</h5>
											          	<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
											        </div>

											        <div class="modal-body">
											          	<input type="hidden" name="id" id="edit_id">
											          	<div class="row">
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_hotel_name">Hotel Name</label>
											            		<input type="text" class="form-control" name="hotel_name" id="edit_hotel_name" required>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_founded">Founded</label>
									                        	<input type="text" class="form-control" name="founded" id="edit_founded" required>
									                        </div>
									                    </div>
									                    <div class="row">
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_tot_employees">Total Employees</label>
									                        	<input type="text" class="form-control" name="tot_employees" id="edit_tot_employees" required>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_location">Address</label>
											            		<input type="text" class="form-control" name="location" id="edit_location" required>
									                        </div>
									                    </div>
											        	<div class="mb-3 position-relative">
														    <label class="form-label" for="searchLocation">Search Location</label>
														    <input type="text" id="searchLocation" class="form-control" placeholder="Type the place or address..." autocomplete="off"
														    >
														    <div id="searchResult" class="list-group position-absolute w-100" style="z-index: 1055;"></div>
														</div>

											        	<div class="mb-3">
														    <label class="form-label">Select on Map</label>
														    <div id="mapEditHotel" style="height: 350px; border-radius: 8px;"></div>
														</div>

											        	<div class="mb-3">
											            	<label class="form-label" for="edit_website">Website</label>
											            	<input type="text" class="form-control" name="website" id="edit_website">
											          	</div>

											          	<div class="mb-3">
									                        <label class="form-label" for="edit_desc">Description</label>
									                        <textarea class="form-control" name="desc" id="edit_desc" rows="3"></textarea>
									                    </div>

									                    <div class="mb-3">
														    <label class="form-label" for="edit_logo">Logo</label>
														    <div class="mb-2">
														        <img id="preview_logo" src="" class="img-thumbnail" style="max-height:120px">
														    </div>
														    <input type="file" class="form-control" name="logo" id="edit_logo" accept="image/*">
														    <small class="text-muted">
														        Leave blank if you don't want to change
														    </small>
														</div>
											        </div>

											        <div class="modal-footer">
											        	<input type="hidden" class="form-control" name="latitude" id="edit_latitude">
											        	<input type="hidden" class="form-control" name="longitude" id="edit_longitude">

											          	<button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
											          	<button type="submit" class="btn btn-primary">Save</button>
											        </div>
											    </form>
										    </div>
										</div>
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
						    // DataTables Users
						    'use strict';
						    $(function () {
						        let dt_tableHotel = $('.dtHotel'), dt_hotel;
						        if (dt_tableHotel.length) {
						        	dt_hotel = dt_tableHotel.DataTable({
						        		processing: true,
					                	serverSide: true,
					                	responsive: true,
						                ajax: {
						                    url: "<?= base_url('admin/hotels/datatable') ?>",
						                    type: "POST",
						                    data: d => {
						                        d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
						                    }
						                },
						                columns: [
						                    { data: null },          // responsive control
						                    { data: 'no_urut' },
						                    { data: 'hotel_name' },
						                    { data: 'location' },
						                    { data: 'latitude' },
						                    { data: 'longitude' },
						                    { data: 'website' },
						                    { data: 'action' }       // actions (HTML from backend)
						                ],
						                columnDefs: [
						                    {
						                        // Responsive control
						                        className: 'control',
						                        orderable: false,
						                        searchable: false,
						                        responsivePriority: 2,
						                        targets: 0,
						                        render: function () {
						                            return '';
						                        }
						                    },
						                    {
									          	targets: 1,
									          	orderable: false,
									          	searchable: false
									        },
					                    	{
					                    		// Hotel name
								          		targets: 2,
								          		responsivePriority: 1,
								          		render: function (data, type, full) {
									            	var $user_img = full['logo'],
									              	$name = full['hotel_name'];
									            	if ($user_img) {
									              		// For Avatar image
									             		var $output = '<img src="' + "../" + $user_img + '" class="rounded-circle">';
									            	} else {
									              		// For Avatar badge
									              		var stateNum = Math.floor(Math.random() * 6);
									              		var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
									              		var $state = states[stateNum],
									                	$name = full['hotel_name'],
									                	$initials = $name.match(/\b\w/g) || [];
									              		$initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
									              		$output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
									            	}
									            	
									            	// Creates full output for row
									            	var $row_output =
									              		'<div class="d-flex justify-content-start align-items-center user-name">' +
									              		'<div class="avatar-wrapper">' +
									              		'<div class="avatar me-2">' +
									              		$output +
									              		'</div>' +
									              		'</div>' +
									              		'<div class="d-flex flex-column">' +
									              		'<span class="emp_name text-truncate">' +
									              		$name +
									              		'</span>' +
									              		'</div>' +
									              		'</div>';
									            	return $row_output;
									          	}
								        	},
						                    {
						                        // Actions
						                        targets: -1,
						                        title: 'Actions',
						                        orderable: false,
						                        searchable: false
						                    }
					                	],
					                	order: [[2, 'asc']],
					                	dom:
					                    	'<"card-header flex-column flex-md-row"' +
					                        '<"head-label text-center">' +
					                        '<"dt-action-buttons text-end pt-3 pt-md-0"B>' +
					                    	'>' +
					                    	'<"row"' +
					                        '<"col-sm-12 col-md-6"l>' +
					                        '<"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>' +
					                    	'>' +
					                    	't' +
					                    	'<"row"' +
					                        '<"col-sm-12 col-md-6"i>' +
					                        '<"col-sm-12 col-md-6"p>' +
					                    	'>',
					                    displayLength: 10,
						                lengthMenu: [10, 25, 50, 100],
						                buttons: [
						                    {
						                        extend: 'collection',
						                        className: 'btn btn-label-primary dropdown-toggle me-2 waves-effect waves-light',
						                        text: '<i class="ti ti-file-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
						                        buttons: [
						                            {
						                                extend: 'print',
						                                text: '<i class="ti ti-printer me-1"></i>Print',
						                                className: 'dropdown-item',
						                                exportOptions: { columns: [1,2,3,4,5,6] }
						                            },
						                            {
						                                extend: 'csv',
						                                text: '<i class="ti ti-file-text me-1"></i>Csv',
						                                className: 'dropdown-item',
						                                exportOptions: { columns: [1,2,3,4,5,6] }
						                            },
						                            {
						                                extend: 'excel',
						                                text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
						                                className: 'dropdown-item',
						                                exportOptions: { columns: [1,2,3,4,5,6] }
						                            },
						                            {
						                                extend: 'pdf',
						                                text: '<i class="ti ti-file-description me-1"></i>Pdf',
						                                className: 'dropdown-item',
						                                exportOptions: { columns: [1,2,3,4,5,6] }
						                            }
						                        ]
						                    },
						                    {
						                        text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-sm-inline-block">Add New Hotel</span>',
						                        className: 'create-new btn btn-primary waves-effect waves-light',
						                        action: function () {
										            $('#modalAddHotel').modal('show');
										        }
						                    }
						                ],
						                responsive: {
						                    details: {
						                        display: $.fn.dataTable.Responsive.display.modal({
						                            header: function (row) {
						                                let data = row.data();
						                                return 'Details of ' + data.hotel_name;
						                            }
						                        }),
						                        type: 'column',
						                        renderer: function (api, rowIdx, columns) {
						                            let data = $.map(columns, function (col) {
						                                return col.title !== ''
						                                    ? '<tr>' +
						                                          '<td>' + col.title + ':</td>' +
						                                          '<td>' + col.data + '</td>' +
						                                      '</tr>'
						                                    : '';
						                            }).join('');

						                            return data
						                                ? $('<table class="table"><tbody /></table>').append(data)
						                                : false;
						                        }
						                    }
						                }
						        	});
									$('div.head-label').html('<h5 class="card-title mb-0">Users List</h5>');
						        }
						    });

						</script>
                        <?= $this->endSection() ?>