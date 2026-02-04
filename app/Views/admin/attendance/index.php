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
                </div>
            <?= $this->endSection() ?>