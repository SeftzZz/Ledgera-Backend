			<?= $this->extend('layouts/admin') ?>
			<?= $this->section('content') ?>
				<div class="container mt-3">
				    <div class="card shadow-sm">
				        <div class="card-body text-center">

				            <h4 class="mb-2">Absensi Karyawan</h4>
				            <p class="text-muted" id="clock">--:--:--</p>

				            <!-- STATUS -->
				            <div id="status" class="alert alert-info">
				                Mengambil lokasi...
				            </div>

				            <!-- VIDEO -->
				            <video id="video" class="w-100 rounded mb-2" autoplay playsinline></video>
				            <canvas id="canvas" hidden></canvas>

				            <!-- PREVIEW -->
				            <img id="preview" class="img-fluid rounded mb-2" style="display:none;">

				            <!-- BUTTON -->
				            <button id="btnAttendance" class="btn btn-primary btn-block mt-2" disabled>
				                Check In
				            </button>

				        </div>
				    </div>
				</div>
			<?= $this->endSection() ?>

			<?= $this->section('scripts') ?>
				<script>
					let latitude, longitude, accuracy;
					let imageData = null;

					/* ===============================
					 * LIVE CLOCK
					 * =============================== */
					setInterval(() => {
					    document.getElementById('clock').innerText =
					        new Date().toLocaleTimeString();
					}, 1000);

					/* ===============================
					 * CAMERA
					 * =============================== */
					navigator.mediaDevices.getUserMedia({
					    video: { facingMode: "user" }
					}).then(stream => {
					    document.getElementById('video').srcObject = stream;
					});

					/* ===============================
					 * GPS
					 * =============================== */
					navigator.geolocation.getCurrentPosition(
					    pos => {
					        latitude  = pos.coords.latitude;
					        longitude = pos.coords.longitude;
					        accuracy  = pos.coords.accuracy;

					        document.getElementById('status').className = 'alert alert-success';
					        document.getElementById('status').innerText =
					            `Lokasi OK (Akurasi ±${Math.round(accuracy)}m)`;

					        document.getElementById('btnAttendance').disabled = false;
					    },
					    err => {
					        document.getElementById('status').className = 'alert alert-danger';
					        document.getElementById('status').innerText =
					            'GPS gagal. Aktifkan lokasi.';
					    },
					    { enableHighAccuracy: true }
					);

					/* ===============================
					 * ATTENDANCE CLICK
					 * =============================== */
					document.getElementById('btnAttendance').onclick = () => {

					    const video  = document.getElementById('video');
					    const canvas = document.getElementById('canvas');

					    canvas.width  = video.videoWidth;
					    canvas.height = video.videoHeight;
					    canvas.getContext('2d').drawImage(video, 0, 0);

					    imageData = canvas.toDataURL('image/jpeg');

					    submitAttendance();
					};

					/* ===============================
					 * SUBMIT
					 * =============================== */
					function submitAttendance() {

					    const deviceId = btoa(navigator.userAgent + screen.width + screen.height);

					    $.post("<?= base_url('attendance/store') ?>", {
					        latitude,
					        longitude,
					        accuracy,
					        selfie: imageData,
					        device_id: deviceId,
					        <?= csrf_token() ?>: "<?= csrf_hash() ?>"
					    }, res => {

					        if (res.status) {
					            Swal.fire('Berhasil', 'Absensi tercatat', 'success');
					            document.getElementById('preview').src = imageData;
					            document.getElementById('preview').style.display = 'block';
					        } else {
					            Swal.fire('Gagal', res.message, 'error');
					        }

					    }, 'json');
					}
					</script>

			<?= $this->endSection() ?>