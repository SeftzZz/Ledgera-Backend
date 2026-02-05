                        <?= $this->extend('layouts/main') ?>
                        <?= $this->section('content') ?>

                        <div class="container-xxl flex-grow-1 container-p-y">
                            <div class="card">
                                <div class="card-datatable table-responsive pt-0">
                                    <table class="dtJobVacancies table table-striped">
                                        <thead>
                                          <tr>
                                            <th></th>
                                            <th>No</th>
                                            <th>Position</th>
                                            <th>Category</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Location</th>
                                            <th>Fee</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="modal fade" id="modalAddJob" tabindex="-1" aria-hidden="true">
                                  <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">

                                      <form id="formAddJob">
                                        <?= csrf_field() ?>

                                        <div class="modal-header">
                                          <h5 class="modal-title">Add New Job</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                          <div class="row">

                                            <!-- JOB POSITION -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Job Position</label>
                                                <select
                                                    name="position[]"
                                                    id="add_job_position"
                                                    class="form-select select2"
                                                    data-placeholder="Select Job Position"
                                                    style="width:100%"
                                                    multiple
                                                    required>
                                                </select>
                                            </div>

                                            <!-- CATEGORY -->
                                            <div class="col-md-6 mb-3">
                                              <label class="form-label">Category</label>
                                              <select class="form-select" name="category" required>
                                                <option value="">-- Select Category --</option>
                                                <option value="daily_worker">Daily Worker</option>
                                                <option value="casual">Casual</option>
                                              </select>
                                            </div>

                                          </div>

                                          <div class="row">

                                            <!-- START DATE -->
                                            <div class="col-md-6 mb-3">
                                              <label class="form-label">Job Start Date</label>
                                              <input type="date" class="form-control" name="job_date_start" required>
                                            </div>

                                            <!-- END DATE -->
                                            <div class="col-md-6 mb-3">
                                              <label class="form-label">Job End Date</label>
                                              <input type="date" class="form-control" name="job_date_end" required>
                                            </div>

                                          </div>

                                          <div class="row">

                                            <!-- START TIME -->
                                            <div class="col-md-6 mb-3">
                                              <label class="form-label">Start Time</label>
                                              <input type="time" class="form-control" name="start_time" required>
                                            </div>

                                            <!-- END TIME -->
                                            <div class="col-md-6 mb-3">
                                              <label class="form-label">End Time</label>
                                              <input type="time" class="form-control" name="end_time" required>
                                            </div>

                                          </div>

                                          <div class="row">

                                            <!-- LOCATION -->
                                            <div class="col-md-6 mb-3">
                                              <label class="form-label">Location</label>
                                              <input type="text" class="form-control" name="location" required>
                                            </div>

                                            <!-- FEE -->
                                            <div class="col-md-6 mb-3">
                                              <label class="form-label">Fee</label>
                                              <input type="number" class="form-control" name="fee" required>
                                            </div>

                                          </div>

                                          <!-- DESCRIPTION -->
                                          <div class="mb-3">
                                            <label class="form-label">Job Description</label>
                                            <textarea class="form-control" name="description" rows="3"></textarea>
                                          </div>

                                          <!-- REQUIREMENT -->
                                          <div class="mb-3">
                                            <label class="form-label">Requirement Skill</label>
                                            <textarea class="form-control" name="requirement_skill" rows="3"></textarea>
                                          </div>

                                          <!-- STATUS (hidden, default open) -->
                                          <input type="hidden" name="status" value="open">

                                        </div>

                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                            Cancel
                                          </button>
                                          <button type="submit" class="btn btn-primary">
                                            Save Job
                                          </button>
                                        </div>

                                      </form>

                                    </div>
                                  </div>
                                </div>

                            </div>
                        </div>

                        <?= $this->endSection() ?>

                        <?= $this->section('scripts') ?>

                        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') ?>" />
                        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') ?>" />
                        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') ?>" />
                        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') ?>" />
                        <script src="<?= base_url('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') ?>"></script>
                        <!-- select2 -->
                        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/select2/select2.css') ?>" />
                        <script src="<?= base_url('assets/vendor/libs/select2/select2.js') ?>"></script>

                        <script>
                            'use strict';

                            $(function () {

                                let dt_table = $('.dtJobVacancies'),
                                    dt_job;

                                if (dt_table.length) {

                                    dt_job = dt_table.DataTable({
                                        processing: true,
                                        serverSide: true,
                                        responsive: true,

                                        ajax: {
                                            url: "<?= base_url('admin/job-vacancies/datatable') ?>",
                                            type: "POST",
                                            data: d => {
                                                d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
                                            }
                                        },

                                        columns: [
                                            { defaultContent: '' }, // responsive control
                                            { data: 'no' },
                                            { data: 'position' },
                                            { data: 'category' },
                                            { data: 'date' },
                                            { data: 'time' },
                                            { data: 'location' },
                                            { data: 'fee' },
                                            { data: 'status' },
                                            { data: 'action' }
                                        ],

                                        columnDefs: [
                                            {
                                                targets: 0,
                                                className: 'control',
                                                orderable: false,
                                                searchable: false
                                            },
                                            {
                                                targets: 1,
                                                orderable: false,
                                                searchable: false
                                            },
                                            {
                                                targets: -1,
                                                orderable: false,
                                                searchable: false
                                            }
                                        ],

                                        order: [[4, 'desc']], // sort by job date

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
                                                className: 'btn btn-label-primary dropdown-toggle me-2',
                                                text: '<i class="ti ti-file-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                                                buttons: [
                                                    { extend: 'print', className: 'dropdown-item', exportOptions: { columns: [1,2,3,4,5,6,7,8] }},
                                                    { extend: 'csv',   className: 'dropdown-item', exportOptions: { columns: [1,2,3,4,5,6,7,8] }},
                                                    { extend: 'excel', className: 'dropdown-item', exportOptions: { columns: [1,2,3,4,5,6,7,8] }},
                                                    { extend: 'pdf',   className: 'dropdown-item', exportOptions: { columns: [1,2,3,4,5,6,7,8] }}
                                                ]
                                            },
                                            {
                                                text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-sm-inline-block">Add New Job</span>',
                                                className: 'create-new btn btn-primary waves-effect waves-light',
                                                action: function () {
                                                    $('#modalAddJob').modal('show');
                                                }
                                            }
                                        ],

                                        responsive: {
                                            details: {
                                                display: $.fn.dataTable.Responsive.display.modal({
                                                    header: function (row) {
                                                        let data = row.data();
                                                        return 'Job Vacancy - ' + data.position;
                                                    }
                                                }),
                                                type: 'column',
                                                renderer: function (api, rowIdx, columns) {
                                                    let data = $.map(columns, function (col) {
                                                        return col.title !== ''
                                                            ? `<tr>
                                                                  <td>${col.title}:</td>
                                                                  <td>${col.data}</td>
                                                               </tr>`
                                                            : '';
                                                    }).join('');

                                                    return data
                                                        ? $('<table class="table"><tbody /></table>').append(data)
                                                        : false;
                                                }
                                            }
                                        }
                                    });

                                    $('div.head-label').html('<h5 class="card-title mb-0">Job Vacancies List</h5>');
                                }

                            });
                        </script>

                        <script>
                            'use strict';

                            $(function () {

                                // SUBMIT ADD JOB
                                $('#formAddJob').on('submit', function (e) {
                                    e.preventDefault();

                                    let form = $(this);
                                    let btn  = form.find('button[type="submit"]');

                                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

                                    $.ajax({
                                        url: "<?= base_url('admin/job-vacancies/store') ?>",
                                        type: "POST",
                                        data: form.serialize(),
                                        dataType: "json",

                                        success: function (res) {

                                            if (res.status === true) {

                                                // close modal
                                                $('#modalAddJob').modal('hide');

                                                // reset form
                                                form[0].reset();

                                                // reload datatable
                                                $('.dtJobVacancies').DataTable().ajax.reload(null, false);

                                                // toast / alert
                                                toastr.success(res.message ?? 'Job successfully added');

                                            } else {
                                                toastr.error(res.message ?? 'Failed to save job');
                                            }
                                        },

                                        error: function (xhr) {

                                            let msg = 'Server error';

                                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                                msg = xhr.responseJSON.message;
                                            }

                                            toastr.error(msg);
                                        },

                                        complete: function () {
                                            btn.prop('disabled', false).html('Save Job');
                                        }
                                    });
                                });

                            });
                        </script>

                        <script>
                            $(document).ready(function () {

                                function initJobPositionSelect2(selector, modal) {

                                    if ($(selector).hasClass('select2-hidden-accessible')) {
                                        $(selector).select2('destroy');
                                    }

                                    $(selector).select2({
                                        placeholder: 'Select Job Position',
                                        allowClear: true,
                                        closeOnSelect: false, // 🔥 penting untuk multi select
                                        dropdownParent: modal,
                                        ajax: {
                                            url: "<?= base_url('admin/job-vacancies/skills') ?>",
                                            dataType: 'json',
                                            delay: 250,
                                            data: function (params) {
                                                return { q: params.term };
                                            },
                                            processResults: function (data) {
                                                return { results: data.results };
                                            },
                                            cache: true
                                        }
                                    });
                                }

                                $('#modalAddJob').on('shown.bs.modal', function () {
                                    initJobPositionSelect2('#add_job_position', $(this));
                                });

                                $('#modalAddJob').on('hidden.bs.modal', function () {
                                    $('#add_job_position').val(null).trigger('change');
                                });

                            });
                        </script>


                        <?= $this->endSection() ?>
