						<?= $this->extend('layouts/main') ?>
                        <?= $this->section('content') ?>
                            <div class="container-xxl flex-grow-1 container-p-y">
                                <div class="card">
                                    <div class="card-datatable table-responsive pt-0">
						                <table class="dtHotel table">
						                    <thead>
						                      	<tr>
							                        <th></th>
											        <th>Data Id</th>
											        <th>Name</th>
											        <th>Email</th>
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
							$(function () {
								  'use strict';

								  var dt_basic_table = $('.dtHotel');

								  if (dt_basic_table.length) {
								    var dt_basic = dt_basic_table.DataTable({
								      ajax: assetsPath + '/json/tablebasic-datatable.json',

								      columns: [
								        { data: '' },
								        { data: 'id' },
								        { data: 'full_name' },
								        { data: 'email' },
								        { data: null }
								      ],

								      columnDefs: [
								        // Responsive (+)
								        {
								          className: 'control',
								          orderable: false,
								          searchable: false,
								          responsivePriority: 2,
								          targets: 0,
								          render: function () {
								            return '';
								          }
								        },

								        // Hidden ID
								        {
								          targets: 1,
								          visible: false,
								          searchable: false
								        },

								        // Avatar + Name
								        {
								          targets: 2,
								          responsivePriority: 1,
								          render: function (data, type, full) {
								            var name = full.full_name;
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

								        // Email
								        {
								          targets: 3,
								          responsivePriority: 3
								        },

								        // Actions
								        {
								          targets: -1,
								          title: 'Actions',
								          orderable: false,
								          searchable: false,
								          render: function (data, type, full, meta) {
								            return (
								              '<div class="d-inline-block">' +
								              '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="text-primary ti ti-dots-vertical"></i></a>' +
								              '<ul class="dropdown-menu dropdown-menu-end m-0">' +
								              '<li><a href="javascript:;" class="dropdown-item">Details</a></li>' +
								              '<li><a href="javascript:;" class="dropdown-item">Archive</a></li>' +
								              '<div class="dropdown-divider"></div>' +
								              '<li><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a></li>' +
								              '</ul>' +
								              '</div>' +
								              '<a href="javascript:;" class="btn btn-sm btn-icon item-edit"><i class="text-primary ti ti-pencil"></i></a>'
								            );
								          }
								        }
								      ],

								      order: [[1, 'desc']],

								      dom:
								        '<"card-header"<"head-label text-center"><"dt-action-buttons text-end"B>>' +
								        '<"d-flex justify-content-between row"<"col-md-6"l><"col-md-6"f>>' +
								        't' +
								        '<"d-flex justify-content-between row"<"col-md-6"i><"col-md-6"p>>',

								      buttons: [
								        {
								          extend: 'collection',
								          className: 'btn btn-label-primary dropdown-toggle me-2',
								          text: '<i class="icon-base ti tabler-show me-1"></i>Export',
								          buttons: ['print', 'csv', 'excel', 'pdf', 'copy']
								        },
								        {
								          text: '<i class="icon-base ti tabler-plus me-1"></i> Add New Record',
								          className: 'btn btn-primary'
								        }
								      ],

								      responsive: {
								        details: {
								          type: 'column',
								          display: $.fn.dataTable.Responsive.display.modal({
								            header: function (row) {
								              return 'Details of ' + row.data().full_name;
								            }
								          }),
								          renderer: function (api, rowIdx, columns) {
								            var data = $.map(columns, function (col) {
								              return col.title !== ''
								                ? `<tr><td>${col.title}</td><td>${col.data}</td></tr>`
								                : '';
								            }).join('');
								            return data ? $('<table class="table"/><tbody />').append(data) : false;
								          }
								        }
								      }
								    });

								    $('div.head-label').html('<h5 class="card-title mb-0">Data User</h5>');
								  }
								});


						</script>

		                

                        <?= $this->endSection() ?>
