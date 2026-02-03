						<?= $this->extend('layouts/main') ?>
                        <?= $this->section('content') ?>
                            <div class="container-xxl flex-grow-1 container-p-y">
                                <div class="card">
                                    <div class="card-datatable table-responsive pt-0">
						                <table class="dtHotel table">
						                    <thead>
						                      	<tr>
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
						    /**
						     * DataTables Basic (Hotels)
						     */

						    'use strict';

						    $(function () {
						        let dt_basic_table = $('.dtHotel'),
						            dt_basic;

						        if (dt_basic_table.length) {
						            dt_basic = dt_basic_table.DataTable({
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
						                    	// Hotel name
									          targets: 1,
									          responsivePriority: 1,
									          render: function (data, type, full) {
									            var name = full.hotel_name;
									            var initials = name.match(/\b\w/g) || [];
									            initials = ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();

									            return `
									              <div class="d-flex align-items-center">
									                <div class="avatar me-2">
									                  <span class="avatar-initial rounded-circle bg-label-primary">${initials}</span>
									                </div>
									                <div class="d-flex flex-column">
									                  <span class="fw-medium">${name}</span>
									                </div>
									              </div>
									            `;
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

						                order: [[1, 'asc']],

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
						                                exportOptions: { columns: [1, 2, 3, 4, 5] }
						                            },
						                            {
						                                extend: 'csv',
						                                text: '<i class="ti ti-file-text me-1"></i>Csv',
						                                className: 'dropdown-item',
						                                exportOptions: { columns: [1, 2, 3, 4, 5] }
						                            },
						                            {
						                                extend: 'excel',
						                                text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
						                                className: 'dropdown-item',
						                                exportOptions: { columns: [1, 2, 3, 4, 5] }
						                            },
						                            {
						                                extend: 'pdf',
						                                text: '<i class="ti ti-file-description me-1"></i>Pdf',
						                                className: 'dropdown-item',
						                                exportOptions: { columns: [1, 2, 3, 4, 5] }
						                            },
						                            {
						                                extend: 'copy',
						                                text: '<i class="ti ti-copy me-1"></i>Copy',
						                                className: 'dropdown-item',
						                                exportOptions: { columns: [1, 2, 3, 4, 5] }
						                            }
						                        ]
						                    },
						                    {
						                        text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Hotel</span>',
						                        className: 'create-new btn btn-primary waves-effect waves-light'
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

						            $('div.head-label').html('<h5 class="card-title mb-0">Hotels Data</h5>');
						        }
						    });
						</script>


                        <?= $this->endSection() ?>