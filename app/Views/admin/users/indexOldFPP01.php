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
									                        	<input type="text" class="form-control" name="hotel_user" id="edit_hotel_user" required>
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
										                              placeholder="812 345 6789" required />
										                        </div>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_role_user">Role</label>
											            		<input type="text" class="form-control" name="role_user" id="edit_role_user" required>
									                        </div>
									                    </div>
									                    <div class="row">
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_status_user">Status</label>
									                        	<input type="text" class="form-control" name="status_user" id="edit_status_user" required>
									                        </div>
									                        <div class="col-md-6 mb-3">
									                            <label class="form-label" for="edit_pass_user">Password</label>
											            		<input type="text" class="form-control" name="pass_user" id="edit_pass_user" required>
									                        </div>
									                    </div>
									                    <div class="mb-3">
														    <label class="form-label" for="edit_logo">Photo</label>
														    <div class="mb-2">
														        <img id="preview_foto" src="" class="img-thumbnail" style="max-height:120px">
														    </div>
														    <input type="file" class="form-control" name="logo" id="edit_foto" accept="image/*">
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
							            $('#edit_hotel_user').val(res.data.hotel_id);
							            $('#edit_role_user').val(res.data.role);
							            $('#edit_hp_user').val(res.data.phone);
							            $('#edit_status_user').val(res.data.is_active);
							            if (res.data.logo) {
										  	$('#preview_foto')
										    .attr('src', "<?= base_url() ?>" + res.data.photo)
										    .show();
										} else {
										  	$('#preview_foto')
										    .removeAttr('src')
										    .hide();
										}
										$('#edit_foto').val('');

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