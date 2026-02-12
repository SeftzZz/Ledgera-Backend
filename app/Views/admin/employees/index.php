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
                                            <h3 class="card-title">Daftar Pegawai</h3>
                                        </div>
                                        <div class="card-body pad table-responsive">
                                            <a href="<?= base_url('admin/employees/create') ?>" class="btn btn-primary mb-3">
                                               <i class="fas fa-plus"></i> Tambah Pegawai
                                            </a>

                                            <table id="employeesTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">No</th>
                                                        <th width="7%">Foto</th>
                                                        <th>NIP/NIK</th>
                                                        <th>Nama</th>
                                                        <th>Email</th>
                                                        <th>HP</th>
                                                        <th>Jabatan</th>
                                                        <th>Dept.</th>
                                                        <th>Status</th>
                                                        <th width="15%">Aksi</th>
                                                    </tr>
                                                </thead>
                                            </table>
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
                <!-- DataTables -->
                <link rel="stylesheet" href="<?= base_url('assets/themes/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
                <script src="<?= base_url('assets/themes/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
                <script src="<?= base_url('assets/themes/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>

                <script>
                    $(function () {
                        let table = $('#employeesTable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "<?= base_url('admin/employees/datatable') ?>",
                                type: "POST"
                            },
                            order: [[3, 'asc']],
                            columnDefs: [
                                { orderable: false, targets: [0,1,5] },
                                { className: 'text-center', targets: [0,4,5] }
                            ]
                        });

                        $('#employeesTable').on('click', '.delete', function () {
                            let id = $(this).data('id');

                            Swal.fire({
                                title: 'Yakin hapus?',
                                text: 'Data akan dihapus',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, hapus!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.post("<?= base_url('admin/employees/delete') ?>", {
                                        id: id,
                                        <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                                    }, function () {
                                        table.ajax.reload();
                                        Swal.fire('Sukses', 'Data berhasil dihapus', 'success');
                                    });
                                }
                            });
                        });

                        $('#employeesTable').on('click', '.edit', function () {
                            window.location.href = "<?= base_url('admin/employees/edit') ?>/" + $(this).data('id');
                        });
                    });
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
