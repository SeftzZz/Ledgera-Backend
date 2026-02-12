                        <?= $this->extend('layouts/main') ?>
                        <?= $this->section('content') ?>
                        <style>
                            .star-rating {
                                display: inline-flex;
                                flex-direction: row-reverse;
                                font-size: 1.5rem;
                            }

                            .star-rating input {
                                display: none;
                            }

                            .star-rating label {
                                color: #ddd;
                                cursor: pointer;
                                transition: color 0.2s;
                            }

                            .star-rating input:checked ~ label,
                            .star-rating label:hover,
                            .star-rating label:hover ~ label {
                                color: #f5b301;
                            }

                        </style>
                        <div class="container-xxl flex-grow-1 container-p-y">
                            <div class="card">
                                <div class="card-datatable table-responsive pt-0">
                                    <table class="dtAttendance table table-striped">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>No.</th>
                                                <th>Date</th>
                                                <th>Worker</th>
                                                <!-- <th>Hotel</th> -->
                                                <th>Job</th>
                                                <th>Check In</th>
                                                <th>Check Out</th>
                                                <th>Duration (Hours)</th>
                                                <th>10 Min Count</th>
                                                <th>Rate</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- detail attendance modal -->
                        <div class="modal fade" id="modalAttendanceDetail" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Attendance Detail</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div id="attendanceDetailContent">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>Date</th>
                                                        <td id="detail_date"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Worker</th>
                                                        <td id="detail_worker"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Hotel</th>
                                                        <td id="detail_hotel"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Job</th>
                                                        <td id="detail_job"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Check In</th>
                                                        <td id="detail_checkin"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Check Out</th>
                                                        <td id="detail_checkout"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Duration</th>
                                                        <td id="detail_duration"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td id="detail_status"></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <h6>Check In Photo</h6>
                                                    <img id="detail_checkin_photo" class="img-fluid rounded">
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Check Out Photo</h6>
                                                    <img id="detail_checkout_photo" class="img-fluid rounded">
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <h6>Location</h6>
                                                <div id="mapAttendanceDetail" style="height:300px" class="rounded"></div>
                                            </div>
                                        </div>

                                        <?php if (in_array(session('user_role'), ['hotel_hr', 'admin'])): ?>
                                        <hr>

                                        <div id="workerRatingFormWrapper">
                                            <h6 class="mb-3">Worker Rating</h6>

                                            <form id="formWorkerRating">
                                                <?= csrf_field() ?>

                                                <input type="hidden" name="user_id" id="wr_user_id">
                                                <input type="hidden" name="job_id" id="wr_job_id">
                                                <input type="hidden" name="date" id="wr_date">

                                                <div class="row g-4">
                                                    <?php
                                                    $ratings = [
                                                        'punctuality' => 'Punctuality',
                                                        'apperance'   => 'Appearance',
                                                        'knowledge'   => 'Knowledge',
                                                        'durability'  => 'Durability',
                                                        'ethics'      => 'Ethics'
                                                    ];
                                                    foreach ($ratings as $name => $label):
                                                    ?>
                                                    <div class="col-md-6">
                                                        <label class="form-label"><?= $label ?></label>
                                                        <div class="star-rating">
                                                            <?php for ($i=5; $i>=1; $i--): ?>
                                                                <input type="radio" name="<?= $name ?>" value="<?= $i ?>" id="<?= $name ?>_<?= $i ?>">
                                                                <label for="<?= $name ?>_<?= $i ?>">★</label>
                                                            <?php endfor ?>
                                                        </div>
                                                    </div>
                                                    <?php endforeach ?>

                                                    <div class="col-md-12">
                                                        <label class="form-label">Comments</label>
                                                        <textarea name="comments" class="form-control" rows="3"></textarea>
                                                    </div>
                                                </div>

                                                <div class="mt-4 text-end">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="ti ti-star"></i> Submit Rating
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <?php endif ?>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
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

                        <!-- Leaflet + OpenStreetMap -->
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

                        <script>
                            'use strict';
                            $(function () {

                                let dt_tableAttendance = $('.dtAttendance'),
                                    dt_attendance;

                                if (dt_tableAttendance.length) {

                                    dt_attendance = dt_tableAttendance.DataTable({
                                        processing: true,
                                        serverSide: true,
                                        responsive: true,

                                        ajax: {
                                            url: "<?= base_url('admin/attendance/datatable') ?>",
                                            type: "POST",
                                            data: d => {
                                                d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
                                            }
                                        },

                                        columns: [
                                            { defaultContent: '' },
                                            { data: 'no' },
                                            { data: 'date' },
                                            { data: 'worker' },
                                            // { data: 'hotel' },
                                            { data: 'job' },
                                            { data: 'checkin' },
                                            { data: 'checkout' },
                                            { data: 'duration' },
                                            { data: 'ten_minutes' },
                                            { data: 'rate' },
                                            { data: 'status' },
                                            { data: 'action' }
                                        ],

                                        columnDefs: [
                                            { targets: 0, className: 'control', orderable: false, searchable: false },
                                            { targets: 1, orderable: false, searchable: false },
                                            {
                                                targets: 9,
                                                render: function (data) {
                                                    return data !== '-' ? 'Rp ' + data : '-';
                                                }
                                            },
                                            {
                                                targets: 10,
                                                render: function (data) {
                                                    let badge = data === 'Complete' ? 'success' : 'warning';
                                                    return `<span class="badge bg-label-${badge}">${data}</span>`;
                                                }
                                            },
                                            { targets: -1, orderable: false, searchable: false }
                                        ],

                                        order: [[2, 'desc']],

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
                                                    {
                                                        extend: 'print',
                                                        className: 'dropdown-item',
                                                        exportOptions: { columns: [1,2,3,4,5,6,7,8,9] }
                                                    },
                                                    {
                                                        extend: 'csv',
                                                        className: 'dropdown-item',
                                                        exportOptions: { columns: [1,2,3,4,5,6,7,8,9] }
                                                    },
                                                    {
                                                        extend: 'excel',
                                                        className: 'dropdown-item',
                                                        exportOptions: { columns: [1,2,3,4,5,6,7,8,9] }
                                                    },
                                                    {
                                                        extend: 'pdf',
                                                        className: 'dropdown-item',
                                                        exportOptions: { columns: [1,2,3,4,5,6,7,8,9] }
                                                    }
                                                ]
                                            }
                                        ],

                                        responsive: {
                                            details: {
                                                display: $.fn.dataTable.Responsive.display.modal({
                                                    header: function (row) {
                                                        let data = row.data();
                                                        return 'Attendance Detail - ' + data.worker;
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

                                    $('div.head-label').html('<h5 class="card-title mb-0">Attendance List</h5>');
                                }

                            });
                        </script>

                        <script>
                            let mapDetail, markerDetail;

                            $(document).on('click', '.btn-detail', function () {
                                const user = $(this).data('user');
                                const job  = $(this).data('job');
                                const date = $(this).data('date');

                                $.post("<?= base_url('admin/attendance/detail') ?>", {
                                    user_id: user,
                                    job_id: job,
                                    date: date,
                                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                                }, function (res) {
                                    if (!res.status) return;

                                    const d = res.data;

                                    $('#detail_date').text(d.date);
                                    $('#detail_worker').text(d.worker);
                                    $('#detail_hotel').text(d.hotel);
                                    $('#detail_job').text(d.job);
                                    $('#detail_checkin').text(d.checkin_time ?? '-');
                                    $('#detail_checkout').text(d.checkout_time ?? '-');
                                    $('#detail_duration').text(d.duration);
                                    $('#detail_status').html(
                                        `<span class="badge bg-label-${d.status === 'Complete' ? 'success' : 'warning'}">${d.status}</span>`
                                    );

                                    $('#detail_checkin_photo').attr('src', d.checkin_photo ? "<?= base_url() ?>/" + d.checkin_photo : '');
                                    $('#detail_checkout_photo').attr('src', d.checkout_photo ? "<?= base_url() ?>/" + d.checkout_photo : '');
                                    // set hidden value
                                    $('#wr_user_id').val(d.user_id);
                                    $('#wr_job_id').val(d.job_id);
                                    $('#wr_date').val(d.date);

                                    $('#modalAttendanceDetail').modal('show');

                                    setTimeout(() => {
                                        if (mapDetail) mapDetail.remove();

                                        mapDetail = L.map('mapAttendanceDetail').setView([d.latitude, d.longitude], 16);

                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            attribution: '&copy; OpenStreetMap'
                                        }).addTo(mapDetail);

                                        markerDetail = L.marker([d.latitude, d.longitude]).addTo(mapDetail);
                                        mapDetail.invalidateSize();
                                    }, 300);

                                }, 'json');
                            });
                        </script>

                        <script>
                            $(document).on('submit', '#formWorkerRating', function (e) {
                                e.preventDefault();

                                const form = $(this);

                                $.post(
                                    "<?= base_url('admin/attendance/rate') ?>",
                                    form.serialize(),
                                    function (res) {
                                        if (!res.status) {
                                            alert(res.message);
                                            return;
                                        }

                                        alert(res.message);

                                        // lock form setelah submit
                                        $('#formWorkerRating input, #formWorkerRating textarea, #formWorkerRating button')
                                            .prop('disabled', true);

                                        $('#formWorkerRating button[type=submit]')
                                            .text('Already Rated');
                                    },
                                    'json'
                                );
                            });
                        </script>

                        <?= $this->endSection() ?>