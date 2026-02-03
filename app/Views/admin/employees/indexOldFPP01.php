<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="content-wrapper p-3">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Employee List</h3>
            <a href="<?= base_url('admin/employees/create') ?>"
               class="btn btn-primary btn-sm float-right">Add</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($employees as $e): ?>
                    <tr>
                        <td><?= esc($e['employee_code']) ?></td>
                        <td><?= esc($e['full_name']) ?></td>
                        <td><?= esc($e['department']) ?></td>
                        <td><?= esc($e['position']) ?></td>
                        <td><?= esc($e['status']) ?></td>
                        <td>
                            <?php if (service('rbac')->hasPermission('employee.edit')): ?>
                            	<a href="<?= base_url('admin/employees/edit/'.$e['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <?php endif ?>
                            <?php if (service('rbac')->hasPermission('employee.delete')): ?>
                            <a href="<?= base_url('admin/employees/delete/'.$e['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
