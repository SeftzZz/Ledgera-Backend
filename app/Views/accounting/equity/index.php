						<?= $this->extend('layouts/main') ?>
                        <?= $this->section('content') ?>
	                        <?php 
								$sessionCompanyId = session()->get('company_id');
								$isSuperAdmin = $sessionCompanyId == 0;
							?>
                            <div class="container-xxl flex-grow-1 container-p-y">
                                <div class="card">
                                    <div class="card-datatable table-responsive pt-0">
						                <table class="table table-bordered">
										    <thead>
										        <tr>
										            <th>Kode</th>
										            <th>Nama Akun</th>
										            <th>Saldo Awal</th>
										            <th>Status</th>
										            <th>Action</th>
										        </tr>
										    </thead>
										    <tbody>
										        <?php foreach($equities as $row): ?>
										        <tr>
										            <td><?= esc($row['account_code']) ?></td>
										            <td><?= esc($row['account_name']) ?></td>
										            <td>
										                <?= number_format($row['opening_balance'] ?? 0, 0, ',', '.') ?>
										            </td>
										            <td>
										                <?= $row['is_active'] ? 
										                    '<span class="badge bg-success">Active</span>' : 
										                    '<span class="badge bg-danger">Inactive</span>' ?>
										            </td>
										            <td>
										                <button class="btn btn-sm btn-primary">Edit</button>
										            </td>
										        </tr>
										        <?php endforeach ?>
										    </tbody>
										</table>
					                </div>

					                <!-- add modal form -->
					                <div class="modal fade" id="modalAddEquity">
										  <div class="modal-dialog modal-lg">
										    <div class="modal-content">
										      <form id="formAddEquity">

										        <div class="modal-header">
										          <h5 class="modal-title">Tambah Akun Ekuitas</h5>
										        </div>

										        <div class="modal-body">

										          <div class="row">
										            <div class="col-md-6 mb-3">
										              <label>Kode Akun</label>
										              <input type="text" name="account_code" class="form-control" required>
										            </div>

										            <div class="col-md-6 mb-3">
										              <label>Nama Akun</label>
										              <input type="text" name="account_name" class="form-control" required>
										            </div>
										          </div>

										          <div class="mb-3">
										            <label>Parent</label>
										            <select name="parent_id" class="form-control">
										              <option value="">-- None --</option>
										            </select>
										          </div>

										          <div class="mb-3">
										            <label>Saldo Awal</label>
										            <input type="number" name="opening_balance" class="form-control" value="0">
										          </div>

										          <div class="mb-3">
										            <label>Status</label>
										            <select name="is_active" class="form-control">
										              <option value="1">Active</option>
										              <option value="0">Inactive</option>
										            </select>
										          </div>

										        </div>

										        <div class="modal-footer">
										          <button type="submit" class="btn btn-primary">Simpan</button>
										        </div>

										      </form>
										    </div>
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
		                <!-- select2 -->
		                <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/select2/select2.css') ?>" />
		                <script src="<?= base_url('assets/vendor/libs/select2/select2.js') ?>"></script>

		                
                        <?= $this->endSection() ?>