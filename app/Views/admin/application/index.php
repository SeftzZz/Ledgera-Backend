                        <?= $this->extend('layouts/main') ?>
                        <?= $this->section('content') ?>

                        <div class="container-xxl flex-grow-1 container-p-y">
                            <div class="card">
                                <div class="card-datatable table-responsive pt-0">
                                    <table class="dtJobApplication table table-striped">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>No.</th>
                                                <th>Mitra</th>
                                                <th>Email</th>
                                                <th>Job</th>
                                                <th>Fee</th>
                                                <th>Status</th>
                                                <th>Applied At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Mitra Detail Modal -->
                        <div class="modal fade" id="modalWorkerDetail" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header position-relative">

                                        <!-- Title (kiri) -->
                                        <h5 class="modal-title mb-0">Mitra Detail</h5>

                                        <!-- ACTION BUTTONS (TENGAH) -->
                                        <div 
                                            id="workerActionButtons"
                                            class="position-absolute top-50 start-50 translate-middle d-flex gap-2 d-none">

                                            <button 
                                                type="button"
                                                class="btn btn-sm btn-success btn-accept-worker"
                                                data-status="accepted">
                                                <i class="ti ti-check"></i> Accept
                                            </button>

                                            <button 
                                                type="button"
                                                class="btn btn-sm btn-danger btn-reject-worker"
                                                data-status="rejected">
                                                <i class="ti ti-x"></i> Reject
                                            </button>
                                        </div>

                                        <!-- CLOSE (kanan) -->
                                        <button 
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                        </button>

                                    </div>


                                    <div class="modal-body">

                                        <div class="row mb-4">

                                          <!-- FOTO PROFIL (COL 4) -->
                                          <div class="col-md-4 text-center">
                                            <img
                                              id="wd_photo"
                                              src="<?= base_url('assets/img/avatars/default.png') ?>"
                                              class="img-fluid rounded mb-2"
                                              style="max-height: 200px; object-fit: cover;"
                                              alt="Worker Photo">

                                            <div class="fw-semibold mt-2" id="wd_name_preview">-</div>
                                            <small class="text-muted">Worker Photo</small>
                                          </div>

                                          <!-- BASIC INFORMATION (COL 8) -->
                                          <div class="col-md-8">
                                            <table class="table table-sm table-bordered mb-0">
                                              <tr>
                                                <th width="35%">Name</th>
                                                <td id="wd_name">-</td>
                                              </tr>
                                              <tr>
                                                <th>Email</th>
                                                <td id="wd_email">-</td>
                                              </tr>
                                              <tr>
                                                <th>Phone</th>
                                                <td id="wd_phone">-</td>
                                              </tr>
                                              <tr>
                                                <th>Gender</th>
                                                <td id="wd_gender">-</td>
                                              </tr>
                                              <tr>
                                                <th>Birth Date</th>
                                                <td id="wd_birth">-</td>
                                              </tr>
                                              <tr>
                                                <th>Address</th>
                                                <td id="wd_address">-</td>
                                              </tr>
                                              <tr>
                                                <th>Bio</th>
                                                <td id="wd_bio">-</td>
                                              </tr>
                                            </table>
                                          </div>

                                        </div>

                                        <!-- EDUCATION -->
                                        <h6 class="mb-2">Educations</h6>
                                        <table class="table table-striped mb-4">
                                            <thead>
                                                <tr>
                                                    <th>Level</th>
                                                    <th>Title</th>
                                                    <th>Institute</th>
                                                    <th>Period</th>
                                                </tr>
                                            </thead>
                                            <tbody id="wd_educations"></tbody>
                                        </table>

                                        <!-- EXPERIENCE -->
                                        <h6 class="mb-2">Experiences</h6>
                                        <table class="table table-striped mb-4">
                                            <thead>
                                                <tr>
                                                    <th>Company</th>
                                                    <th>Job Title</th>
                                                    <th>Department</th>
                                                    <th>Period</th>
                                                </tr>
                                            </thead>
                                            <tbody id="wd_experiences"></tbody>
                                        </table>

                                        <!-- SKILLS -->
                                        <h6 class="mb-2">Skills</h6>
                                        <div id="wd_skills" class="d-flex flex-wrap gap-2 mb-4"></div>

                                        <!-- DOCUMENT -->
                                        <h6 class="mb-2">Documents</h6>
                                        <div id="wd_documents" class="row g-3 mb-4"></div>

                                        <!-- LINKS -->
                                        <h6 class="mb-2">Links</h6>
                                        <table class="table table-striped mb-4">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Url</th>
                                                </tr>
                                            </thead>
                                            <tbody id="wd_links"></tbody>
                                        </table>

                                        <!-- RATINGS -->
                                        <h6 class="mt-4 mb-2">Ratings</h6>
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr><th>Punctuality</th><td id="wd_punctuality"></td></tr>
                                                <tr><th>Appearance</th><td id="wd_appearance"></td></tr>
                                                <tr><th>Knowledge</th><td id="wd_knowledge"></td></tr>
                                                <tr><th>Durability</th><td id="wd_durability"></td></tr>
                                                <tr><th>Ethics</th><td id="wd_ethics"></td></tr>
                                            </tbody>
                                        </table>

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
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

                        <script>
                            'use strict';

                            $(function () {

                                let dt_table = $('.dtJobApplication'),
                                    dt_job;

                                if (dt_table.length) {

                                    dt_job = dt_table.DataTable({
                                        processing: true,
                                        serverSide: true,
                                        responsive: true,

                                        ajax: {
                                            url: "<?= base_url('admin/application/datatable') ?>",
                                            type: "POST",
                                            data: d => {
                                                d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
                                            }
                                        },

                                        columns: [
                                            { defaultContent: '' }, // ✅ responsive control (AMAN MOBILE)
                                            { data: 'no' },
                                            { data: 'worker' },
                                            { data: 'email' },
                                            { data: 'job' },
                                            { data: 'fee' },
                                            { data: 'status' },
                                            { data: 'applied' },
                                            { data: 'action' }
                                        ],

                                        columnDefs: [
                                            {
                                                targets: 0,
                                                className: 'control',   // ✅ IKON RESPONSIVE SAMA
                                                orderable: false,
                                                searchable: false
                                            },
                                            {
                                                targets: 1,
                                                orderable: false,
                                                searchable: false
                                            },
                                            { 
                                                data: 'status' 
                                            },
                                            {
                                                targets: -1,
                                                orderable: false,
                                                searchable: false
                                            }
                                        ],

                                        order: [[7, 'desc']],

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
                                                    { extend: 'print', className: 'dropdown-item', exportOptions: { columns: [1,2,3,4,5,6,7] }},
                                                    { extend: 'csv',   className: 'dropdown-item', exportOptions: { columns: [1,2,3,4,5,6,7] }},
                                                    { extend: 'excel', className: 'dropdown-item', exportOptions: { columns: [1,2,3,4,5,6,7] }},
                                                    { extend: 'pdf',   className: 'dropdown-item', exportOptions: { columns: [1,2,3,4,5,6,7] }}
                                                ]
                                            }
                                        ],

                                        responsive: {
                                            details: {
                                                display: $.fn.dataTable.Responsive.display.modal({
                                                    header: function (row) {
                                                        let data = row.data();
                                                        return 'Job Application - ' + data.worker;
                                                    }
                                                }),
                                                type: 'column', // ✅ sama dengan Attendance
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

                                    $('div.head-label').html('<h5 class="card-title mb-0">Job Application List</h5>');
                                }

                            });
                        </script>

                        <script>
                            let currentApplicationId = null;

                            $(document).on('click', '.btn-worker-detail', function () {

                                currentApplicationId = $(this).data('application');

                                // reset action buttons
                                $('#workerActionButtons').addClass('d-none');
                                $('.btn-accept-worker, .btn-reject-worker').prop('disabled', false);

                                $.get(
                                    "<?= base_url('admin/application/worker') ?>/" + currentApplicationId,
                                    function (res) {

                                        if (!res || !res.status) return;

                                        /* ===============================
                                           STATUS APPLICATION
                                        =============================== */
                                        const status = res.application?.status ?? null;

                                        console.log('APPLICATION STATUS:', status);

                                        if (status === 'pending') {
                                            $('#workerActionButtons').removeClass('d-none');
                                        }

                                        /* ===============================
                                           BASIC PROFILE
                                        =============================== */
                                        const u = res.user ?? {};

                                        $('#wd_name').text(u.name ?? '-');
                                        $('#wd_email').text(u.email ?? '-');
                                        $('#wd_phone').text(u.phone ?? '-');
                                        $('#wd_gender').text(u.gender ?? '-');
                                        $('#wd_birth').text(u.birth_date ?? '-');
                                        $('#wd_address').text(u.address ?? '-');
                                        $('#wd_bio').text(u.bio ?? '-');

                                        // preview name bawah foto
                                        $('#wd_name_preview').text(u.name ?? '-');

                                        // FOTO PROFIL
                                        if (u.photo) {
                                          $('#wd_photo').attr('src', `<?= base_url() ?>/${u.photo}`);
                                        } else {
                                          $('#wd_photo').attr('src', `<?= base_url('assets/img/avatars/default.png') ?>`);
                                        }

                                        /* ===============================
                                           DOCUMENTS
                                        =============================== */
                                        $('#wd_documents').html(
                                          (res.documents ?? []).length
                                            ? res.documents.map(d => {

                                                const fullUrl = `<?= base_url() ?>/${d.file_path}`;
                                                const fileName = d.file_path.split('/').pop(); // ambil nama file saja

                                                const shortName =
                                                  fileName.length > 20
                                                    ? fileName.substring(0, 20) + '...'
                                                    : fileName;

                                                return `
                                                  <div class="col-md-3">
                                                    <a href="${fullUrl}" target="_blank" title="${fileName}">
                                                      ${shortName}
                                                    </a>
                                                    <small class="d-block text-center mt-1">
                                                      ${(d.type || '').toUpperCase()}
                                                    </small>
                                                  </div>
                                                `;
                                              }).join('')
                                            : '<div class="text-muted">No documents</div>'
                                        );

                                        /* ===============================
                                           EDUCATIONS
                                        =============================== */
                                        $('#wd_educations').html(
                                            (res.educations ?? []).length
                                                ? res.educations.map(e => `
                                                    <tr>
                                                        <td>${e.level}</td>
                                                        <td>${e.title}</td>
                                                        <td>${e.instituted_name}</td>
                                                        <td>${e.start_date} - ${e.is_current ? 'Now' : e.end_date}</td>
                                                    </tr>
                                                `).join('')
                                                : '<tr><td colspan="4" class="text-center text-muted">No education data</td></tr>'
                                        );

                                        /* ===============================
                                           EXPERIENCES
                                        =============================== */
                                        $('#wd_experiences').html(
                                            (res.experiences ?? []).length
                                                ? res.experiences.map(ex => `
                                                    <tr>
                                                        <td>${ex.company_name}</td>
                                                        <td>${ex.job_title}</td>
                                                        <td>${ex.department}</td>
                                                        <td>${ex.start_date} - ${ex.is_current ? 'Now' : ex.end_date}</td>
                                                    </tr>
                                                `).join('')
                                                : '<tr><td colspan="4" class="text-center text-muted">No experience</td></tr>'
                                        );

                                        /* ===============================
                                           SKILLS
                                        =============================== */
                                        $('#wd_skills').html(
                                            (res.skills ?? []).length
                                                ? res.skills.map(s =>
                                                    `<span class="badge bg-primary me-1">${s.name}</span>`
                                                  ).join('')
                                                : '<span class="text-muted">No skills</span>'
                                        );

                                        /* ===============================
                                           LINKS
                                        =============================== */
                                        $('#wd_links').html(
                                            (res.links ?? []).length
                                                ? res.links.map(l => `
                                                    <tr>
                                                        <td>${l.name}</td>
                                                        <td><a href="${l.url}" target="_blank">${l.url}</a></td>
                                                    </tr>
                                                `).join('')
                                                : '<span class="text-muted">No links</span>'
                                        );

                                        /* ===============================
                                           RATINGS
                                        =============================== */
                                        if (res.rating) {
                                            $('#wd_punctuality').text(res.rating.punctuality ?? '-');
                                            $('#wd_appearance').text(res.rating.apperance ?? '-');
                                            $('#wd_knowledge').text(res.rating.knowledge ?? '-');
                                            $('#wd_durability').text(res.rating.durability ?? '-');
                                            $('#wd_ethics').text(res.rating.ethics ?? '-');
                                        } else {
                                            $('#wd_punctuality, #wd_appearance, #wd_knowledge, #wd_durability, #wd_ethics')
                                                .text('-');
                                        }

                                        $('#modalWorkerDetail').modal('show');
                                    },
                                    'json'
                                );
                            });
                        </script>

                        <script>
                            $(document).on('click', '.btn-accept-worker, .btn-reject-worker', function () {
                                const status = $(this).data('status');

                                if (!currentApplicationId) return;

                                if (!confirm(`Are you sure want to ${status}?`)) return;

                                $.post("<?= base_url('admin/application/update-status') ?>", {
                                    application_id: currentApplicationId,
                                    status: status,
                                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                                }, function (res) {

                                    if (!res.status) {
                                        alert(res.message || 'Failed');
                                        return;
                                    }

                                    $('#modalWorkerDetail').modal('hide');
                                    $('.dtJobApplication').DataTable().ajax.reload(null, false);

                                }, 'json');
                            });

                        </script>
                        <?= $this->endSection() ?>
