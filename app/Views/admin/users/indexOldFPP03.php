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
							                        <th>Name</th>
							                        <th>Hotel</th>
							                        <th>Role</th>
							                        <th>Email</th>
							                        <th>Hp</th>
							                        <th>Status</th>
							                        <th>Action</th>
						                      	</tr>
						                    </thead>
						                    <tbody></tbody>
						                </table>
					                </div>

					                <!-- add modal form -->
					                <div class="modal fade" id="modalAddUser" tabindex="-1" aria-hidden="true">
										<div class="modal-dialog modal-lg modal-dialog-centered">
										    <div class="modal-content">
											    <form id="formAddUser" enctype="multipart/form-data">
											        <div class="modal-header">
											          	<h5 class="modal-title">Add User</h5>
											          	<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
											        </div>
											        <div class="modal-body">
											          	<div class="row">
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label">Name</label>
											            		<input type="text" class="form-control" name="name_user" required>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label">Hotel</label>
									                        	<select
																    name="hotel_id"
																    id="add_hotel_user"
																    class="form-select select2"
																    data-placeholder="Select Hotel"
																    style="width:100%">
																    <option value=""></option>
																    <?php foreach ($hotels as $hotel): ?>
																        <option value="<?= $hotel['id'] ?>">
																            <?= esc($hotel['hotel_name']) ?>
																        </option>
																    <?php endforeach; ?>
																</select>
									                        </div>
									                    </div>
									                    <div class="row">
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label">Email</label>
											            		<input type="email" class="form-control" name="email_user" required>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label">Hp.</label>
									                            <div class="input-group">
										                            <span class="input-group-text">+62</span>
										                            <input
										                              type="text"
										                              name="hp_user"
										                              class="form-control phone-number-mask"
										                              placeholder="812 3456 7890" required />
										                        </div>
									                        </div>
									                    </div>
									                    <div class="row">
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label">Role</label>
									                            <select name="role_user" class="form-control required">
									                            	<option value="">Select Role</option>
								                                    <option value="admin">Admin HW</option>
								                                    <option value="worker">Mitra</option>
								                                    <option value="hotel_hr">User HR</option>
								                                    <option value="hotel_fo">User FO</option>
								                                    <option value="hotel_hk">User HK</option>
								                                    <option value="hotel_fnb_service">User FnBS</option>
								                                    <option value="hotel_fnb_production">User FnBP</option>
								                                </select>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label">Status</label>
									                            <select name="status_user" class="form-control required">
									                            	<option value="">Select Status</option>
								                                    <option value="active">Active</option>
								                                    <option value="inactive">Inactive</option>
								                                </select>
									                        </div>
									                    </div>
									                    <div class="row">
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label">Password</label>
											            		<input type="text" class="form-control" name="pass_user" required>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                        	<label class="form-label">Photo</label>
														    	<input type="file" class="form-control" name="foto_user" accept="image/*">
									                        </div>
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
					                <div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
										<div class="modal-dialog modal-lg modal-dialog-centered">
										    <div class="modal-content">
											    <form id="formEditUser" enctype="multipart/form-data">
											        <div class="modal-header">
											          	<h5 class="modal-title">Edit User</h5>
											          	<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
											        </div>

											        <div class="modal-body">
											          	<input type="hidden" name="id" id="edit_id">
											          	<div class="row">
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_name_user">Name</label>
											            		<input type="text" class="form-control" name="name_user" id="edit_name_user" required>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_hotel_user">Hotel</label>
									                        	<select
															        name="hotel_user"
															        id="edit_hotel_user"
															        class="form-select select2"
															        data-placeholder="Select Hotel"
															        style="width:100%">
															        <option value=""></option>
															        <?php foreach ($hotels as $hotel): ?>
															            <option value="<?= $hotel['id'] ?>">
															                <?= esc($hotel['hotel_name']) ?>
															            </option>
															        <?php endforeach; ?>
															    </select>
									                        </div>
									                    </div>
									                    <div class="row">
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_hp_user">Hp.</label>
									                            <div class="input-group">
										                            <span class="input-group-text">+62</span>
										                            <input
										                              type="text"
										                              name="hp_user"
										                              id="edit_hp_user"
										                              class="form-control phone-number-mask"
										                              placeholder="812 3456 7890" required />
										                        </div>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_role_user">Role</label>
									                            <select name="role_user" id="edit_role_user" class="form-control required">
								                                    <option value="admin">Admin HW</option>
								                                    <option value="worker">Mitra</option>
								                                    <option value="hotel_hr">User HR</option>
								                                    <option value="hotel_fo">User FO</option>
								                                    <option value="hotel_hk">User HK</option>
								                                    <option value="hotel_fnb_service">User FnBS</option>
								                                    <option value="hotel_fnb_production">User FnBP</option>
								                                </select>
									                        </div>
									                    </div>
									                    <div class="row">
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_status_user">Status</label>
									                            <select name="status_user" id="edit_status_user" class="form-control required">
								                                    <option value="active">Active</option>
								                                    <option value="inactive">Inactive</option>
								                                </select>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_pass_user">Password</label>
											            		<input type="text" class="form-control" name="pass_user" id="edit_pass_user">
											            		<small class="text-muted">
															        Leave blank if you don't want to change
															    </small>
									                        </div>
									                    </div>
									                    <div class="mb-3">
														    <label class="form-label" for="edit_foto">Photo</label>
														    <div class="mb-2">
														        <img id="preview_foto" src="" class="img-thumbnail" style="max-height:120px">
														    </div>
														    <input type="file" class="form-control" name="foto" id="edit_foto" accept="image/*">
														    <small class="text-muted">
														        Leave blank if you don't want to change
														    </small>
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
		                <!-- select2 -->
		                <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/select2/select2.css') ?>" />
		                <script src="<?= base_url('assets/vendor/libs/select2/select2.js') ?>"></script>

		                <script>
						    // DataTables Users
						    'use strict';
						    $(function () {
						        let dt_tableUser = $('.dtUser'), dt_user;
						        if (dt_tableUser.length) {
						        	dt_user = dt_tableUser.DataTable({
						        		processing: true,
					                	serverSide: true,
					                	responsive: true,
						                ajax: {
						                    url: "<?= base_url('admin/users/datatable') ?>",
						                    type: "POST",
						                    data: d => {
						                        d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
						                    }
						                },
						                columns: [
						                    { data: null },          // responsive control
						                    { data: 'no_urut' },
						                    { data: 'name_user' },
						                    { data: 'hotel_name' },
						                    { data: 'role_user' },
						                    { data: 'email_user' },
						                    { data: 'hp_user' },
						                    { data: 'status_user' },
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
					                    		// Name User
								          		targets: 2,
								          		responsivePriority: 1,
								          		render: function (data, type, full) {
									            	var $user_img = full['photo_user'],
									              	$name = full['name_user'];
									            	if ($user_img) {
									              		// For Avatar image
									             		var $output = '<img src="' + "../" + $user_img + '" class="rounded-circle">';
									            	} else {
									              		// For Avatar badge
									              		var stateNum = Math.floor(Math.random() * 6);
									              		var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
									              		var $state = states[stateNum],
									                	$name = full['name_user'],
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
						                                exportOptions: { columns: [1,2,3,4,5,6,7] }
						                            },
						                            {
						                                extend: 'csv',
						                                text: '<i class="ti ti-file-text me-1"></i>Csv',
						                                className: 'dropdown-item',
						                                exportOptions: { columns: [1,2,3,4,5,6,7] }
						                            },
						                            {
						                                extend: 'excel',
						                                text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
						                                className: 'dropdown-item',
						                                exportOptions: { columns: [1,2,3,4,5,6,7] }
						                            },
						                            {
						                                extend: 'pdf',
						                                text: '<i class="ti ti-file-description me-1"></i>Pdf',
						                                className: 'dropdown-item',
						                                exportOptions: { columns: [1,2,3,4,5,6,7] }
						                            }
						                        ]
						                    },
						                    {
						                        text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-sm-inline-block">Add New User</span>',
						                        className: 'create-new btn btn-primary waves-effect waves-light',
						                        action: function () {
										            $('#modalAddUser').modal('show');
										        }
						                    }
						                ],
						                responsive: {
						                    details: {
						                        display: $.fn.dataTable.Responsive.display.modal({
						                            header: function (row) {
						                                let data = row.data();
						                                return 'Details of ' + data.name_user;
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

							// init select2
							$(document).ready(function () {
							    function initHotelSelect2(selector, modal) {
							        if ($(selector).hasClass('select2-hidden-accessible')) {
							            $(selector).select2('destroy');
							        }

							        $(selector).select2({
							            placeholder: 'Select Hotel',
							            allowClear: true,
							            dropdownParent: modal
							        });
							    }

							    $('#modalEditUser').on('shown.bs.modal', function () {
							        initHotelSelect2('#edit_hotel_user', $('#modalEditUser'));
							    });

							    $('#modalAddUser').on('shown.bs.modal', function () {
							        initHotelSelect2('#add_hotel_user', $('#modalAddUser'));
							    });
							});

							// Auto Set Hotel by role
							$(document).ready(function () {
							    const sessionRole  = "<?= session()->get('user_role') ?>";
							    const sessionHotel = "<?= session()->get('hotel_id') ?>";
							    function lockHotelSelect() {
							        // set value
							        $('#add_hotel_user')
							            .val(sessionHotel)
							            .trigger('change');

							        // lock interaction (tanpa disabled)
							        $('#add_hotel_user').on('select2:opening select2:selecting', function (e) {
							            e.preventDefault();
							        });
							    }
							    function unlockHotelSelect() {
							        $('#add_hotel_user').off('select2:opening select2:selecting');
							        $('#add_hotel_user').val(null).trigger('change');
							    }
							    // role change handler
							    $('select[name="role_user"]').on('change', function () {
							        const role = $(this).val();
							        if (sessionRole === 'hotel_hr') {
							            lockHotelSelect();
							        } else {
							            // optional: role tertentu auto hotel
							            if (role.startsWith('hotel_')) {
							                unlockHotelSelect();
							            } else {
							                unlockHotelSelect();
							            }
							        }
							    });

							    // saat modal add dibuka
							    $('#modalAddUser').on('shown.bs.modal', function () {
							        if (sessionRole === 'hotel_hr') {
							            lockHotelSelect();
							        }
							    });

							});

							// Submit form insert data
							$('#formAddUser').on('submit', function (e) {
							    e.preventDefault();
							    let formData = new FormData(this);
							    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
							    Swal.fire({
							        title: 'Add new hotel?',
							        icon: 'question',
							        showCancelButton: true,
							        reverseButtons: true,
							        confirmButtonText: 'Yes, save',
							        cancelButtonText: 'No'
							    }).then(result => {
							        if (result.isConfirmed) {
							            $.ajax({
							                url: "<?= base_url('admin/users/store') ?>",
							                type: "POST",
							                data: formData,
							                processData: false,
							                contentType: false,
							                dataType: 'json',
							                success(res) {
							                    if (res.status) {
							                        Swal.fire({
							                            icon: 'success',
							                            title: 'Saved',
							                            text: res.message,
							                            timer: 1500,
							                            showConfirmButton: false
							                        });

							                        $('#modalAddUser').modal('hide');
							                        $('.dtUser').DataTable().ajax.reload(null, false);
							                    } else {
							                        Swal.fire('Failed', res.message, 'error');
							                    }
							                }
							            });
							        }
							    });
							});

							// Edit form
							$(document).on('click', '.btn-edit', function () {
							    const id = $(this).data('id');
							    $('#edit_foto').on('change', function () {
								    const file = this.files[0];
								    if (file) {
								        $('#preview_foto').attr('src', URL.createObjectURL(file));
								    }
								});
							    $.post("<?= base_url('admin/users/get') ?>", {
							        id: id,
							        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
							    }, function (res) {
							        if (res.status) {
							            $('#edit_id').val(res.data.id);
									    $('#edit_name_user').val(res.data.name);
									    $('#edit_role_user').val(res.data.role);
									    $('#edit_hp_user').val(res.data.phone);
									    $('#edit_status_user').val(res.data.is_active);
									    $('#edit_hotel_user')
									        .val(res.data.hotel_id)
									        .trigger('change');
									    if (res.data.photo) {
									        $('#preview_foto')
									            .attr('src', "<?= base_url() ?>" + res.data.photo)
									            .show();
									    } else {
									        $('#preview_foto').hide();
									    }
									    $('#modalEditUser').modal('show');
							        } else {
							            Swal.fire('Gagal', res.message, 'error');
							        }
							    }, 'json');
							});

							$('#formEditUser').on('submit', function (e) {
							    e.preventDefault();
							    let formData = new FormData(this);
							    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
							    Swal.fire({
							        title: 'Are you sure?',
							        icon: 'question',
							        showCancelButton: true,
							        showDenyButton: false,
							        confirmButtonText: 'Yes, update',
							        cancelButtonText: 'No',
							        reverseButtons: true
							    }).then((result) => {
							        if (result.isConfirmed) {
							            $.ajax({
									        url: "<?= base_url('admin/users/update') ?>",
									        type: "POST",
									        data: formData,
									        processData: false,
									        contentType: false,
									        dataType: 'json',
									        success: function (res) {
									            if (res.status) {
									                Swal.fire({
									                    icon: 'success',
									                    title: 'Succeed',
									                    text: res.message,
									                    timer: 1500,
									                    showConfirmButton: false
									                });

									                $('#modalEditUser').modal('hide');
									                $('.dtUser').DataTable().ajax.reload(null, false);
									            } else {
									                Swal.fire('Failed', res.message, 'error');
									            }
									        },
									        error: function () {
									            Swal.fire('Error', 'Server error', 'error');
									        }
									    });
							        }
							    });
							});

							// Delete Soft
							$(document).on('click', '.btn-delete', function () {
							    const id = $(this).data('id');
							    Swal.fire({
							        title: 'Are you sure!!!',
							        text: 'Data will be deleted',
							        icon: 'warning',
							        showCancelButton: true,
							        confirmButtonText: 'Yes, delete it!',
							        cancelButtonText: 'Cancel',
							        reverseButtons: true
							    }).then((result) => {
							        if (result.isConfirmed) {
							            $.ajax({
							                url: "<?= base_url('admin/users/delete') ?>",
							                type: "POST",
							                dataType: "json",
							                data: {
							                    id: id,
							                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
							                },
							                success: function (res) {
							                    if (res.status) {
							                        Swal.fire({
							                            icon: 'success',
							                            title: 'Success',
							                            text: res.message,
							                            timer: 1500,
							                            showConfirmButton: false
							                        });

							                        $('.dtUser').DataTable().ajax.reload(null, false);
							                    } else {
							                        Swal.fire('Gagal', res.message, 'error');
							                    }
							                },
							                error: function () {
							                    Swal.fire('Error', 'Terjadi kesalahan server', 'error');
							                }
							            });
							        }
							    });
							});
						</script>
                        <?= $this->endSection() ?>