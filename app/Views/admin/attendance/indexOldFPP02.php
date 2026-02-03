<!DOCTYPE html>
<html lang="id">
	<head>
	    <meta charset="UTF-8">
	    <title>Attendance</title>
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= base_url('assets/themes/plugins/fontawesome-free/css/all.min.css') ?>">
	    <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('assets/themes/dist/css/themesstyle.css') ?>">

	    <style>
	        body { background:#f4f6f9 }
	        video {
	            width:100%;
	            border-radius:12px;
	            transform: scaleX(-1);
	        }
	        .btn-lg { font-size:18px }
	    </style>
	</head>

	<body class="hold-transition layout-top-nav">
	<div class="wrapper">

	<div class="content-wrapper">
	<section class="content pt-3">
	<div class="container">

	    <!-- HEADER -->
	    <div class="card card-outline card-primary text-center">
	        <div class="card-body">
	            <h3 id="clock">--:--:--</h3>
	            <p class="mb-0">
	                <i class="fas fa-map-marker-alt"></i>
	                <strong>Kantor Paledang</strong>
	            </p>
	            <small class="text-muted">Jl. Raya Paledang No.12</small>
	        </div>
	    </div>

	    <!-- CAMERA -->
	    <div class="card">
	        <div class="card-body text-center">
	            <video id="video" autoplay playsinline></video>
	            <canvas id="canvas" hidden></canvas>
	        </div>
	    </div>

	    <!-- GPS STATUS -->
	    <div class="card">
	        <div class="card-body">
	            <p class="mb-1">📍 Lat: <span id="lat">-</span></p>
	            <p class="mb-1">📍 Lng: <span id="lng">-</span></p>
	            <p class="mb-1">🎯 Accuracy: <span id="accuracy">-</span> m</p>
	            <p class="mb-1">📏 Distance: <span id="distance">-</span> m</p>

	            <div id="gpsStatus" class="alert alert-warning text-center mt-2">
	                Mengambil lokasi...
	            </div>
	        </div>
	    </div>

	    <!-- ACTION BUTTON -->
	    <div class="row text-center">
	        <div class="col-6">
	            <button id="btnIn" class="btn btn-success btn-lg btn-block" disabled>
	                CHECK IN
	            </button>
	        </div>
	        <div class="col-6">
	            <button id="btnOut" class="btn btn-danger btn-lg btn-block" disabled>
	                CHECK OUT
	            </button>
	        </div>
	    </div>

	    <!-- MESSAGE -->
	    <div id="messageBox" class="alert alert-danger mt-3 d-none"></div>

	</div>
	</section>
	</div>

	</div>

	<!-- ======================= SCRIPT ======================= -->
	<script>
	/* ================= CLOCK ================= */
	setInterval(() => {
	    document.getElementById('clock').innerHTML =
	        new Date().toLocaleTimeString('id-ID');
	}, 1000);

	/* ================= CAMERA ================= */
	const video  = document.getElementById('video');
	const canvas = document.getElementById('canvas');

	navigator.mediaDevices.getUserMedia({
	    video: { facingMode: "user" }
	}).then(stream => {
	    video.srcObject = stream;
	}).catch(() => {
	    showError('Kamera tidak dapat diakses');
	});

	/* ================= GPS CONFIG ================= */
	const OFFICE_LAT = -6.595038;
	const OFFICE_LNG = 106.816635;
	const MAX_RADIUS = 100; // meter
	const MAX_ACCURACY = 50;

	const latEl = document.getElementById('lat');
	const lngEl = document.getElementById('lng');
	const accEl = document.getElementById('accuracy');
	const distEl = document.getElementById('distance');
	const gpsStatus = document.getElementById('gpsStatus');
	const btnIn  = document.getElementById('btnIn');
	const btnOut = document.getElementById('btnOut');

	navigator.geolocation.watchPosition(successGPS, errorGPS, {
	    enableHighAccuracy: true,
	    timeout: 10000,
	    maximumAge: 0
	});

	/* ================= GPS SUCCESS ================= */
	function successGPS(pos) {
	    const lat = pos.coords.latitude;
	    const lng = pos.coords.longitude;
	    const acc = pos.coords.accuracy;

	    latEl.innerText = lat;
	    lngEl.innerText = lng;
	    accEl.innerText = acc.toFixed(1);

	    const distance = getDistance(lat, lng, OFFICE_LAT, OFFICE_LNG);
	    distEl.innerText = distance.toFixed(1);

	    if (acc > MAX_ACCURACY) {
	        setStatus('Akurasi GPS buruk', 'danger');
	        disableBtn();
	        return;
	    }

	    if (distance > MAX_RADIUS) {
	        setStatus('Diluar area absensi', 'danger');
	        disableBtn();
	        return;
	    }

	    setStatus('GPS OK - Siap absen', 'success');
	    btnIn.disabled  = false;
	    btnOut.disabled = false;
	}

	/* ================= GPS ERROR ================= */
	function errorGPS() {
	    setStatus('GPS tidak aktif', 'danger');
	    disableBtn();
	}

	/* ================= HELPER ================= */
	function setStatus(msg, type) {
	    gpsStatus.className = 'alert alert-' + type + ' text-center mt-2';
	    gpsStatus.innerText = msg;
	}

	function disableBtn() {
	    btnIn.disabled = true;
	    btnOut.disabled = true;
	}

	function showError(msg) {
	    const box = document.getElementById('messageBox');
	    box.innerText = msg;
	    box.classList.remove('d-none');
	}

	/* ================= DISTANCE ================= */
	function getDistance(lat1, lon1, lat2, lon2) {
	    const R = 6371000;
	    const dLat = (lat2-lat1) * Math.PI/180;
	    const dLon = (lon2-lon1) * Math.PI/180;
	    const a =
	        Math.sin(dLat/2) * Math.sin(dLat/2) +
	        Math.cos(lat1*Math.PI/180) *
	        Math.cos(lat2*Math.PI/180) *
	        Math.sin(dLon/2) * Math.sin(dLon/2);
	    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	}

	/* ================= CAPTURE ================= */
	function captureImage() {
	    canvas.width  = video.videoWidth;
	    canvas.height = video.videoHeight;
	    canvas.getContext('2d')
	          .drawImage(video, 0, 0);
	    return canvas.toDataURL('image/png');
	}

	/* ================= ACTION ================= */
	btnIn.onclick = () => submitAttendance('in');
	btnOut.onclick = () => submitAttendance('out');

	function submitAttendance(type) {
	    const image = captureImage();

	    alert('Attendance ' + type.toUpperCase() + ' siap dikirim ke server');
	    // next: fetch API CI4
	}
	</script>

	</body>
</html>
