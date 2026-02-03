            <?= $this->extend('layouts/admin') ?>
            <?= $this->section('content') ?>
                
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1>Managemen Pegawai</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Managemen Pegawai</li>
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
                                <div class="col-md-12">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">Tambah Pegawai</h3>
                                        </div>
                                        <form method="post" action="<?= base_url('admin/employees/store') ?>" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="employee_code">NIP/NIK</label>
                                                            <input name="employee_code" id="employee_code" class="form-control" value="<?= old('employee_code') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label for="prefix_title">Gelar Depan</label>
                                                        <input name="prefix_title" id="prefix_title" class="form-control" value="<?= old('prefix_title') ?>">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="full_name">Nama Lengkap*</label>
                                                            <input name="full_name" id="full_name" class="form-control" value="<?= old('full_name') ?>">
                                                            <small class="text-danger"><?= session('errors.full_name') ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="suffix_title">Gelar Belakang</label>
                                                            <input name="suffix_title" id="suffix_title" class="form-control" value="<?= old('suffix_title') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="nick_name">Nama Panggil</label>
                                                            <input name="nick_name" id="nick_name" class="form-control" value="<?= old('nick_name') ?>">
                                                            <small class="text-danger"><?= session('errors.nick_name') ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="gender">Jenis Kelamin*</label>
                                                            <select name="gender" id="gender" class="form-control">
                                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                                <option value="Laki-Laki" <?= old('gender') == 'Laki-Laki' ? 'selected' : '' ?>>
                                                                    Laki-Laki
                                                                </option>
                                                                <option value="Perempuan" <?= old('gender') == 'Perempuan' ? 'selected' : '' ?>>
                                                                    Perempuan
                                                                </option>
                                                            </select>
                                                            <small class="text-danger"><?= session('errors.gender') ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="religion">Agama*</label>
                                                            <select name="religion" id="religion" class="form-control">
                                                                <option value="">--Pilih Agama--</option>
                                                                <option value="Islam" <?= old('religion') == 'Islam' ? 'selected' : '' ?>>
                                                                    Islam
                                                                </option>
                                                                <option value="Katolik" <?= old('religion') == 'Katolik' ? 'selected' : '' ?>>
                                                                    Katolik
                                                                </option>
                                                                <option value="Protestan" <?= old('religion') == 'Protestan' ? 'selected' : '' ?>>
                                                                    Protestan
                                                                </option>
                                                                <option value="Hindu" <?= old('religion') == 'Hindu' ? 'selected' : '' ?>>
                                                                    Hindu
                                                                </option>
                                                                <option value="Buddha" <?= old('religion') == 'Buddha' ? 'selected' : '' ?>>
                                                                    Buddha
                                                                </option>
                                                                <option value="Khonghucu" <?= old('religion') == 'Khonghucu' ? 'selected' : '' ?>>
                                                                    Khonghucu
                                                                </option>
                                                            </select>
                                                            <small class="text-danger"><?= session('errors.religion') ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="idcard_no">No. KTP</label>
                                                            <input name="idcard_no" id="idcard_no" class="form-control" value="<?= old('idcard_no') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="nationality">Kewarganegaraan</label>
                                                            <input name="nationality" id="nationality" class="form-control" value="<?= old('nationality') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="place_birth">Tempat Lahir</label>
                                                            <input name="place_birth" id="place_birth" class="form-control" value="<?= old('place_birth') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="date_birth">Tanggal Lahir*</label>
                                                            <div class="input-group date" id="tgllahir" data-target-input="nearest">
                                                                <input type="text" name="date_birth" id="date_birth" class="form-control datetimepicker-input" value="<?= old('date_birth') ?>" data-target="#tgllahir" data-toggle="datetimepicker"/>
                                                                <div class="input-group-append" data-target="#tgllahir" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                            <small class="text-danger"><?= session('errors.date_birth') ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="address">Alamat</label>
                                                            <textarea name="address" id="address" class="form-control" rows="3"><?= old('address') ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="city">Kota</label>
                                                            <input name="city" id="city" class="form-control" value="<?= old('city') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="zip_code">Kodepos</label>
                                                            <input name="zip_code" id="zip_code" class="form-control" value="<?= old('zip_code') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="email">Email*</label>
                                                            <input name="email" id="email" class="form-control" value="<?= old('email') ?>">
                                                            <small class="text-danger"><?= session('errors.email') ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="hp">No. HP*</label>
                                                            <input name="hp" id="hp" class="form-control" value="<?= old('hp') ?>">
                                                            <small style="color:red"><?= session('errors.hp') ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="marital">Status Menikah</label>
                                                            <input name="marital" id="marital" class="form-control" value="<?= old('marital_id') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="pendidikan">Pendidikan Terakhir</label>
                                                            <input name="pendidikan" id="pendidikan" class="form-control" value="<?= old('education_id') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="jabatan">Jabatan</label>
                                                            <input name="jabatan" id="jabatan" class="form-control" value="<?= old('position') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="dept">Departemen</label>
                                                            <select name="dept" id="dept" class="form-control">
                                                                <option value="">-- Pilih Departemen --</option>
                                                                <?php foreach ($departments as $dept): ?>
                                                                    <option value="<?= $dept['id'] ?>">
                                                                        <?= esc($dept['name']) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="join_date">Tgl. Bergabung</label>
                                                            <div class="input-group date" id="tglgabung" data-target-input="nearest">
                                                                <input type="text" name="join_date" id="join_date" class="form-control datetimepicker-input" value="<?= old('join_date') ?>" data-target="#tglgabung" data-toggle="datetimepicker"/>
                                                                <div class="input-group-append" data-target="#tglgabung" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="status">Status Pegawai</label>
                                                            <select name="status" id="status" class="form-control">
                                                                <option value="">-- Pilih Status --</option>
                                                                <option value="Aktif" <?= old('status') == 'Aktif' ? 'selected' : '' ?>>
                                                                    Aktif
                                                                </option>
                                                                <option value="Nonaktif" <?= old('status') == 'Nonaktif' ? 'selected' : '' ?>>
                                                                    Nonaktif
                                                                </option>
                                                            </select>
                                                            <small class="text-danger"><?= session('errors.status') ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Foto Employee</label>
                                                    <div id="drop-area" class="drop-area" style="border:2px dashed #ccc;padding:20px;text-align:center;cursor:pointer;">

                                                        <p>Drag & Drop Foto atau Klik</p>
                                                        <img id="preview" src="<?= base_url('assets/themes/dist/img/user-default.jpg') ?>" style="max-width:100%;max-height:300px;">
                                                    </div>

                                                    <input type="file" id="fileInput" name="picture" accept="image/*" hidden>
                                                    <input type="hidden" name="picture" id="picture" value="<?= old('picture') ?>">
                                                    <small style="color:red"><?= session('errors.picture') ?></small>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button class="btn btn-warning">Simpan</button>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href="<?= base_url('admin/employees') ?>" class="btn btn-secondary">&nbsp;&nbsp;&nbsp;Batal&nbsp;&nbsp;&nbsp;</a>
                                            </div>
                                        </form>

                                        <div class="modal fade" id="cropModal">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Crop Foto</h5>
                                                        <button class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img id="cropImage" style="max-width:100%;">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button id="cropBtn" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- ./row -->
                        </div>
                    </section>
                    <!-- /.main content -->
                </div>
            <?= $this->endSection() ?>

            <?= $this->section('scripts') ?>
                <!-- InputMask -->
                <script src="<?= base_url('assets/themes/plugins/moment/moment.min.js') ?>"></script>
                <script src="<?= base_url('assets/themes/plugins/inputmask/jquery.inputmask.min.js') ?>"></script>
                <!-- date-range-picker -->
                <link rel="stylesheet" href="<?= base_url('assets/themes/plugins/daterangepicker/daterangepicker.css') ?>">
                <script src="<?= base_url('assets/themes/plugins/daterangepicker/daterangepicker.js') ?>"></script>
                <!-- Tempusdominus Bootstrap 4 -->
                <link rel="stylesheet" href="<?= base_url('assets/themes/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
                <script src="<?= base_url('assets/themes/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css"/>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

                
                <script>
                    $(function () {
                        //Date picker
                        $('#tgllahir').datetimepicker({
                            format: 'YYYY-MM-DD'
                        });
                        $('#tglgabung').datetimepicker({
                            format: 'YYYY-MM-DD'
                        });
                    })
                </script>

                <script>
                    let cropper;
                    const dropArea   = document.getElementById('drop-area');
                    const fileInput  = document.getElementById('fileInput');
                    const cropImage  = document.getElementById('cropImage');

                    /* ===============================
                     * CLICK → FILE PICKER
                     * =============================== */
                    dropArea.addEventListener('click', () => fileInput.click());

                    fileInput.addEventListener('change', function () {
                        if (this.files && this.files[0]) {
                            handleFile(this.files[0]);
                        }
                    });

                    /* ===============================
                     * DRAG & DROP HANDLER
                     * =============================== */
                    ['dragenter', 'dragover'].forEach(event => {
                        dropArea.addEventListener(event, e => {
                            e.preventDefault();
                            e.stopPropagation();
                            dropArea.classList.add('dragover');
                        });
                    });

                    ['dragleave', 'drop'].forEach(event => {
                        dropArea.addEventListener(event, e => {
                            e.preventDefault();
                            e.stopPropagation();
                            dropArea.classList.remove('dragover');
                        });
                    });

                    dropArea.addEventListener('drop', e => {
                        const files = e.dataTransfer.files;
                        if (files.length) {
                            handleFile(files[0]);
                        }
                    });

                    /* ===============================
                     * HANDLE FILE → CROP MODAL
                     * =============================== */
                    function handleFile(file) {

                        if (!file.type.startsWith('image/')) {
                            Swal.fire('Error', 'File harus berupa gambar', 'error');
                            return;
                        }

                        const reader = new FileReader();

                        reader.onload = () => {
                            cropImage.src = reader.result;
                            $('#cropModal').modal('show');

                            $('#cropModal').on('shown.bs.modal', function () {

                                if (cropper) {
                                    cropper.destroy();
                                }

                                cropper = new Cropper(cropImage, {
                                    aspectRatio: 1,
                                    viewMode: 1,
                                    autoCropArea: 1,
                                    responsive: true
                                });
                            });
                        };

                        reader.readAsDataURL(file);
                    }

                    /* ===============================
                     * CROP & UPLOAD
                     * =============================== */
                    document.getElementById('cropBtn').addEventListener('click', function () {

                        if (!cropper) return;

                        const canvas = cropper.getCroppedCanvas({
                            width: 160,
                            height: 160
                        });

                        const imageData = canvas.toDataURL('image/png');

                        $.post("<?= base_url('admin/employees/upload-photo-temp') ?>", {
                            image: imageData,
                            <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                        }, function (res) {
                            if (res.status) {
                                $('#preview').attr('src', imageData);
                                $('#picture').val(res.file);
                                Swal.fire('Sukses', 'Foto berhasil disiapkan', 'success');
                            } else {
                                Swal.fire('Gagal', res.message, 'error');
                            }

                            cropper.destroy();
                            cropper = null;
                            $('#cropModal').modal('hide');
                        }, 'json');
                    });
                </script>

                <?php if (session()->getFlashdata('error')): ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: '<?= session()->getFlashdata('error') ?>',
                            confirmButtonColor: "#007bff",
                            confirmButtonText: "Tutup"
                        });
                    </script>
                <?php endif; ?>
            <?= $this->endSection() ?>
