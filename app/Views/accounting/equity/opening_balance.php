<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Opening Balance</h5>
            <button class="btn btn-primary" id="btnSave">Save</button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="openingTable">
                    <thead class="table-light">
                        <tr>
                            <th>Account Code</th>
                            <th>Account Name</th>
                            <th width="200">Debit</th>
                            <th width="200">Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($accounts as $acc): ?>
                        <tr data-id="<?= $acc['id'] ?>">
                            <td><?= esc($acc['account_code']) ?></td>
                            <td><?= esc($acc['account_name']) ?></td>
                            <td>
                                <input type="number" class="form-control debit text-end" value="0">
                            </td>
                            <td>
                                <input type="number" class="form-control credit text-end" value="0">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="2" class="text-end">TOTAL</td>
                            <td id="totalDebit">0</td>
                            <td id="totalCredit">0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-3 text-end">
                <span id="balanceStatus" class="badge bg-success">Balanced</span>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function calculateTotal() {
    let totalDebit = 0;
    let totalCredit = 0;

    $('.debit').each(function() {
        totalDebit += parseFloat($(this).val()) || 0;
    });

    $('.credit').each(function() {
        totalCredit += parseFloat($(this).val()) || 0;
    });

    $('#totalDebit').text(totalDebit.toLocaleString());
    $('#totalCredit').text(totalCredit.toLocaleString());

    if (totalDebit === totalCredit) {
        $('#balanceStatus')
            .removeClass('bg-danger')
            .addClass('bg-success')
            .text('Balanced');
    } else {
        $('#balanceStatus')
            .removeClass('bg-success')
            .addClass('bg-danger')
            .text('Not Balanced');
    }
}

$(document).on('keyup change', '.debit, .credit', function() {
    calculateTotal();
});

$('#btnSave').click(function() {

    let rows = [];

    $('#openingTable tbody tr').each(function() {

        let coa_id = $(this).data('id');
        let debit = $(this).find('.debit').val();
        let credit = $(this).find('.credit').val();

        rows.push({
            coa_id: coa_id,
            debit: debit,
            credit: credit
        });

    });

    $.ajax({
        url: "<?= base_url('equity/opening-balance/save') ?>",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(rows),
        success: function(res) {
            Swal.fire('Success', res.message, 'success');
        }
    });
});
</script>
<?= $this->endSection() ?>
