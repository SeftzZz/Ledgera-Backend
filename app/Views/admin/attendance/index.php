                        <?= $this->extend('layouts/main') ?>
                        <?= $this->section('content') ?>

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
                                                <th>Hotel</th>
                                                <th>Job</th>
                                                <th>Check In</th>
                                                <th>Check Out</th>
                                                <th>Duration</th>
                                                <th>10 Min Count</th>
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
                                            { data: null },      // responsive control
                                            { data: 'no' },
                                            { data: 'date' },
                                            { data: 'worker' },
                                            { data: 'hotel' },
                                            { data: 'job' },
                                            { data: 'checkin' },
                                            { data: 'checkout' },
                                            { data: 'duration' },
                                            { data: 'ten_minutes' },
                                            { data: 'status' },
                                            { data: 'action' }
                                        ],

                                        columnDefs: [
                                            { targets: 0, className: 'control', orderable: false, searchable: false },
                                            { targets: 1, orderable: false, searchable: false },
                                            {
                                                targets: 10,
                                                render: function (data) {
                                                    let badge = data === 'Complete' ? 'success' : 'warning';
                                                    return `<span class="badge bg-${badge}">${data}</span>`;
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
                                        `<span class="badge bg-${d.status === 'Complete' ? 'success' : 'warning'}">${d.status}</span>`
                                    );

                                    $('#detail_checkin_photo').attr('src', d.checkin_photo ? "<?= base_url() ?>/" + d.checkin_photo : '');
                                    $('#detail_checkout_photo').attr('src', d.checkout_photo ? "<?= base_url() ?>/" + d.checkout_photo : '');

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
                        <?= $this->endSection() ?>