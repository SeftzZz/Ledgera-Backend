            <?= $this->extend('layouts/admin') ?>
            <?= $this->section('content') ?>
                <style>
                    .camera-preview {
                        height: 240px;
                        background: #000;
                        border-radius: 8px;
                        overflow: hidden;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .camera-preview video {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        transform: scaleX(-1);
                    }
                </style>
                
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1>Absensi</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Absensi</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- /.content header -->

                    <!-- Main content -->
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4 offset-md-4">
                                    <div class="card card-primary card-outline">
                                        <div class="card-body">
                                            <div class="row mb-2">
                                                <div class="col-12">
                                                    <h5 class="text-center">
                                                        <span class="ml-2" id="date">&nbsp;</span>
                                                    </h5>
                                                    <h2 class="text-center">
                                                        <span id="clock" class="text-primary" style="font-weight: bold;">&nbsp;</span>
                                                    </h2>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-12">
                                                    <div class="camera-preview">
                                                        <video id="video" autoplay playsinline></video>
                                                        <canvas id="canvas" hidden></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <button id="btnIn" class="btn btn-success btn-block btn-xs">
                                                        <i class="fas fa-sign-in-alt"></i><br>MASUK
                                                    </button>
                                                </div>
                                                <div class="col-6">
                                                    <button id="btnOut" class="btn btn-danger btn-block btn-xs">
                                                        <i class="fas fa-sign-out-alt"></i><br>PULANG
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <div id="map" style="height:250px;border-radius:12px;"></div>

                                                    <p class="mb-1">
                                                        Lat: <span id="lat">-</span> | Lng: <span id="lng">-</span>
                                                    </p>
                                                    <p class="mb-1">
                                                        Accuracy: <span id="accuracy">-</span> m |
                                                        Distance: <span id="distance">-</span> m
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- /.main content -->
                </div>
            <?= $this->endSection() ?>

            <?= $this->section('scripts') ?>
                <!-- Leaflet -->
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <!-- Toastr -->
                <link rel="stylesheet" href="<?= base_url('assets/themes/plugins/toastr/toastr.min.css') ?>">
                <script src="<?= base_url('assets/themes/plugins/toastr/toastr.min.js') ?>"></script>

                <script>
                    /* ================= CLOCK ================= */
                    function updateDateTime() {
                        const now = new Date();
                        document.getElementById('clock').innerHTML =
                            now.toLocaleTimeString('id-ID') + ' WIB';
                        document.getElementById('date').innerHTML =
                            now.toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });
                    }
                    setInterval(updateDateTime, 1000);
                    updateDateTime();

                    /* ================= CAMERA ================= */
                    const video  = document.getElementById('video');
                    const canvas = document.getElementById('canvas');

                    navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
                    .then(stream => video.srcObject = stream)
                    .catch(() => {
                        toastr.error('Kamera tidak dapat diakses', 'Kamera');
                    });

                    /* ================= CONFIG ================= */
                    const lastAttendance = <?= json_encode($lastAttendance ?? null) ?>;
                    const USER_DEPT_ID = <?= (int) session()->get('employee_dept') ?>;
                    const IS_FREE_LOCATION = USER_DEPT_ID === 3;

                    function isMobileDevice() {
                        return /Android|iPhone|iPad|iPod|Opera Mini|IEMobile/i.test(navigator.userAgent);
                    }

                    const DEVICE_TYPE = isMobileDevice() ? 'mobile' : 'desktop';

                    let MAX_ACCURACY = DEVICE_TYPE === 'mobile' ? 80 : 200;
                    let CONFIDENT_DISTANCE = DEVICE_TYPE === 'mobile' ? 15 : 30;
                    let IDEAL_ACCURACY = DEVICE_TYPE === 'mobile' ? 50 : 200;

                    const OFFICE_LAT = -6.6011188;
                    const OFFICE_LNG = 106.7941239;
                    const MAX_RADIUS = 65;

                    const latEl  = document.getElementById('lat');
                    const lngEl  = document.getElementById('lng');
                    const accEl  = document.getElementById('accuracy');
                    const distEl = document.getElementById('distance');
                    const btnIn  = document.getElementById('btnIn');
                    const btnOut = document.getElementById('btnOut');

                    /* ================= TOASTR CONFIG ================= */
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 5000
                    };

                    let lastStatus = '';

                    function setStatus(msg, type) {
                        if (msg === lastStatus) return;
                        lastStatus = msg;
                        toastr.clear();

                        if (type === 'success') toastr.success(msg, 'GPS');
                        if (type === 'warning') toastr.warning(msg, 'GPS');
                        if (type === 'danger')  toastr.error(msg, 'GPS');
                    }

                    /* ================= MAP ================= */
                    let map = L.map('map').setView([OFFICE_LAT, OFFICE_LNG], 17);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
                    .addTo(map);

                    L.marker([OFFICE_LAT, OFFICE_LNG])
                    .addTo(map).bindPopup('Lokasi Kantor').openPopup();

                    L.circle([OFFICE_LAT, OFFICE_LNG], {
                        radius: MAX_RADIUS,
                        color: 'green',
                        fillOpacity: 0.15
                    }).addTo(map);

                    let userMarker;

                    /* ================= GPS ================= */
                    toastr.warning('Mengambil lokasi GPS...', 'GPS', {
                        timeOut: 0,
                        extendedTimeOut: 0,
                        tapToDismiss: false
                    });

                    console.log('Device:', DEVICE_TYPE);
                    console.log('Max Accuracy:', MAX_ACCURACY);

                    navigator.geolocation.watchPosition(successGPS, errorGPS, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    });

                    function successGPS(pos) {
                        const latitude  = pos.coords.latitude;
                        const longitude = pos.coords.longitude;
                        const accuracy  = pos.coords.accuracy;

                        // ===============================
                        // ANTI FAKE GPS (TELEPORT)
                        // ===============================
                        if (isFakeMovement(latitude, longitude)) {
                            setStatus('Fake GPS terdeteksi', 'danger');
                            disableBtn();
                            return;
                        }

                        // ===============================
                        // HITUNG JARAK
                        // ===============================
                        const distance = getDistance(
                            latitude, longitude,
                            OFFICE_LAT, OFFICE_LNG
                        );

                        // ===============================
                        // UPDATE UI
                        // ===============================
                        latEl.innerText  = latitude.toFixed(6);
                        lngEl.innerText  = longitude.toFixed(6);
                        accEl.innerText  = accuracy.toFixed(1);
                        distEl.innerText = distance.toFixed(1);

                        // ===============================
                        // MAP UPDATE
                        // ===============================
                        if (!userMarker) {
                            userMarker = L.marker([latitude, longitude])
                                .addTo(map)
                                .bindPopup('Posisi Anda');
                        } else {
                            userMarker.setLatLng([latitude, longitude]);
                        }

                        map.setView([latitude, longitude]);
                        
                        // ===============================
                        // VALIDASI GPS
                        // ===============================
                        // ================== TANPA RADIUS ==================
                        if (IS_FREE_LOCATION) {
                            // ACCURACY TERLALU BURUK
                            // if (accuracy > MAX_ACCURACY) {
                            //     setStatus(`Akurasi GPS ${DEVICE_TYPE} buruk, pindah ke area terbuka`, 'warning');
                            //     disableBtn();
                            //     return;
                            // }

                            setStatus(`GPS ${DEVICE_TYPE} OK`, 'success');
                            if (lastAttendance) {
                                if (lastAttendance.check_type === 'Masuk') {
                                    btnIn.disabled = true;
                                    btnOut.disabled = false;
                                }

                                if (lastAttendance.check_type === 'Pulang') {
                                    btnIn.disabled  = true;
                                    btnOut.disabled = true;
                                }
                            }
                            return;
                        }

                        // ================== DENGAN RADIUS ==================
                        // ACCURACY TERLALU BURUK
                        if (accuracy > MAX_ACCURACY) {
                            setStatus(`Akurasi GPS ${DEVICE_TYPE} buruk , pindah ke area terbuka`, 'danger');
                            disableBtn();
                            return;
                        }

                        // ACCURACY SEDANG
                        if (accuracy > IDEAL_ACCURACY) {
                            setStatus('Akurasi GPS ${DEVICE_TYPE} kurang stabil, mohon tunggu...', 'warning');
                            disableBtn();
                            return;
                        }

                        if (distance > MAX_RADIUS) {
                            setStatus('Anda diluar lokasi kantor', 'danger');
                            disableBtn();
                            return;
                        }

                        if (accuracy < 10 && distance > 30) {
                            setStatus('GPS tidak wajar', 'danger');
                            disableBtn();
                            return;
                        }
                        
                        // ===============================
                        // GPS VALID
                        // ===============================
                        setStatus(`GPS ${DEVICE_TYPE} OK`, 'success');
                        if (lastAttendance) {
                            if (lastAttendance.check_type === 'Masuk') {
                                btnIn.disabled = true;
                                btnOut.disabled = false;
                            }

                            if (lastAttendance.check_type === 'Pulang') {
                                btnIn.disabled  = true;
                                btnOut.disabled = true;
                            }
                        }

                        // CONFIDENCE ZONE
                        if (distance <= CONFIDENT_DISTANCE) {
                            setStatus(`GPS ${DEVICE_TYPE} OK`, 'success');
                            if (lastAttendance) {
                                if (lastAttendance.check_type === 'Masuk') {
                                    btnIn.disabled = true;
                                    btnOut.disabled = false;
                                }

                                if (lastAttendance.check_type === 'Pulang') {
                                    btnIn.disabled  = true;
                                    btnOut.disabled = true;
                                }
                            }
                            return;
                        }
                    }

                    function errorGPS() {
                        setStatus('GPS tidak aktif', 'danger');
                        disableBtn();
                    }

                    /* ================= HELPER ================= */
                    function disableBtn() {
                        btnIn.disabled = true;
                        btnOut.disabled = true;
                    }

                    function getDistance(lat1, lon1, lat2, lon2) {
                        const R = 6371000;
                        const dLat = (lat2-lat1) * Math.PI/180;
                        const dLon = (lon2-lon1) * Math.PI/180;
                        const a =
                            Math.sin(dLat/2)**2 +
                            Math.cos(lat1*Math.PI/180) *
                            Math.cos(lat2*Math.PI/180) *
                            Math.sin(dLon/2)**2;
                        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                    }

                    /* ================= CAPTURE ================= */
                    function captureImage() {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        canvas.getContext('2d').drawImage(video, 0, 0);
                        return canvas.toDataURL('image/png');
                    }

                    function getDeviceId() {
                        let deviceId = localStorage.getItem('device_id');

                        if (!deviceId) {
                            const raw = [
                                navigator.userAgent,
                                navigator.language,
                                screen.width + 'x' + screen.height,
                                Intl.DateTimeFormat().resolvedOptions().timeZone,
                                navigator.platform
                            ].join('|');

                            deviceId = btoa(raw); // simple fingerprint
                            localStorage.setItem('device_id', deviceId);
                        }

                        return deviceId;
                    }

                    function submitAttendance(type) {
                        const payload = {
                            type: type,
                            lat: latEl.innerText,
                            lng: lngEl.innerText,
                            accuracy: accEl.innerText,
                            distance: distEl.innerText,
                            is_fake_gps: 0,
                            photo: captureImage(),
                            device_id: getDeviceId()
                        };

                        fetch('<?= base_url('admin/attendance/submit') ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(payload)
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.status) {
                                Swal.fire('Sukses', res.message, 'success');
                            } else {
                                Swal.fire('Gagal', res.message, 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Server error', 'error');
                        });
                    }

                    btnIn.onclick  = () => submitAttendance('Masuk');
                    btnOut.onclick = () => submitAttendance('Pulang');
                </script>

                <script>
                    /* ===============================
                     * ANTI FAKE GPS - TELEPORT
                     * =============================== */

                    let lastLat = null;
                    let lastLng = null;
                    let lastTime = null;

                    function isFakeMovement(lat, lng) {
                        const now = Date.now();

                        if (lastLat !== null && lastLng !== null) {
                            const distance = getDistance(lastLat, lastLng, lat, lng); // meter
                            const timeDiff = (now - lastTime) / 1000; // detik

                            // TELEPORT DETECTION
                            if (timeDiff < 3 && distance > 100) {
                                console.warn('FAKE GPS DETECTED', distance, timeDiff);
                                return true;
                            }
                        }

                        lastLat = lat;
                        lastLng = lng;
                        lastTime = now;

                        return false;
                    }
                </script>

                <?php if (session()->getFlashdata('success')): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: '<?= session()->getFlashdata('success') ?>',
                            confirmButtonText: "Tutup"
                        });
                    </script>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '<?= session()->getFlashdata('error') ?>',
                            confirmButtonText: "Tutup"
                        });
                    </script>
                <?php endif; ?>

                <?php if (session()->getFlashdata('warning')): ?>
                    <script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: '<?= session()->getFlashdata('warning') ?>',
                            confirmButtonText: "Tutup"
                        });
                    </script>
                <?php endif; ?>
            <?= $this->endSection() ?>
