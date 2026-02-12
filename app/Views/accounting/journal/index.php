<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <table class="dtJournal table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>No.</th>
                        <th>Journal No</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>

<link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') ?>" />
<script src="<?= base_url('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') ?>"></script>

<script>
'use strict';

$(function () {

    let dt = $('.dtJournal').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "<?= base_url('journal/datatable') ?>",
            type: "POST",
            data: d => {
                d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
            }
        },
        columns: [
            { data: null },
            { data: 'no' },
            { data: 'journal_no' },
            { data: 'date' },
            { data: 'description' },
            { data: 'status' },
            { data: 'action' }
        ],
        columnDefs: [
            { targets: 0, orderable:false, searchable:false, render:()=>'' },
            { targets: -1, orderable:false, searchable:false }
        ],
        order: [[2,'desc']]
    });

});
</script>

<?= $this->endSection() ?>
