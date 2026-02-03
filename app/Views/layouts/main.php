<!doctype html>
<html
    lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
    dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-no-customizer">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <title><?= $title ?> | Hey! Work</title>

        <meta name="description" content="hey work connects hospitality professionals with trusted hotels for flexible daily and casual job opportunities." />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon/favicon.png') ?>" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
            rel="stylesheet" />

        <!-- Icons -->
        <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/fontawesome.css') ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/tabler-icons.css') ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/flag-icons.css') ?>" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/vendor/css/rtl/core.css') ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/vendor/css/rtl/theme-default.css') ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/css/demo.css') ?>" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/node-waves/node-waves.css') ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/typeahead-js/typeahead.css') ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/sweetalert2/sweetalert2.css') ?>" />
       
        <!-- Helpers -->
        <script src="<?= base_url('assets/vendor/js/helpers.js') ?>"></script>
        <script src="<?= base_url('assets/js/config.js') ?>"></script>
    </head>

    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Menu -->
                <?= $this->include('layouts/sidebar') ?>
                <!-- / Menu -->

                <!-- Layout page -->
                <div class="layout-page">
                    <!-- Navbar -->
                    <?= $this->include('layouts/navbar') ?>
                    <!-- / Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <?= $this->renderSection('content') ?>
                        <!-- / Content -->

                        <!-- Footer -->
                        <?= $this->include('layouts/footer') ?>
                        <!-- / Footer -->

                        <div class="content-backdrop fade"></div>
                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>

            <!-- Drag Target Area To SlideIn Menu On Small Screens -->
            <div class="drag-target"></div>
        </div>
        <!-- / Layout wrapper -->

        <!-- Core JS -->
        <script src="<?= base_url('assets/vendor/libs/jquery/jquery.js') ?>"></script>
        <script src="<?= base_url('assets/vendor/libs/popper/popper.js') ?>"></script>
        <script src="<?= base_url('assets/vendor/js/bootstrap.js') ?>"></script>
        <script src="<?= base_url('assets/vendor/libs/node-waves/node-waves.js') ?>"></script>
        <script src="<?= base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') ?>"></script>
        <script src="<?= base_url('assets/vendor/libs/hammer/hammer.js') ?>"></script>
        <script src="<?= base_url('assets/vendor/libs/i18n/i18n.js') ?>"></script>
        <script src="<?= base_url('assets/vendor/libs/typeahead-js/typeahead.js') ?>"></script>
        <script src="<?= base_url('assets/vendor/js/menu.js') ?>"></script>
        <!-- endbuild -->

        <!-- Vendors JS -->
        <?= $this->renderSection('scripts') ?>
        <script src="<?= base_url('assets/vendor/libs/sweetalert2/sweetalert2new.js') ?>"></script>

        
        <!-- Main JS -->
        <script src="<?= base_url('assets/js/main.js') ?>"></script>

        <!-- Page JS -->
    </body>
</html>