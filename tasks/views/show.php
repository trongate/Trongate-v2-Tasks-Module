<h1><?= $headline ?></h1>
<?= flashdata() ?>
<div class="card">
    <div class="card-heading">
        Task Details
    </div>
    <div class="card-body">
        <div class="text-right mb-3">
            <?= anchor($back_url, 'Back', array('class' => 'button alt')) ?>
            <?= anchor(BASE_URL.'tasks/create/'.$update_id, 'Edit', array('class' => 'button')) ?>
            <?= anchor('tasks/delete_conf/'.$update_id, 'Delete',  array('class' => 'button danger')) ?>
        </div>
        
        <div class="detail-grid">
            <div class="detail-row">
                <div class="detail-label">Task Title</div>
                <div class="detail-value"><?= out($task_title) ?></div>
            </div>
            <div class="detail-block">
                <div class="detail-label">Task Description</div>
                <div class="detail-content"><?= nl2br(out($task_description)) ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <?= out($complete_formatted) ?>
                </div>
            </div>
        </div>
    </div>
</div>