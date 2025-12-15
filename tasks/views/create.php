<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Task Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Task Title');
        echo form_input('task_title', $task_title, ["placeholder" => "Enter Task Title"]);
        echo form_label('Task Description');
        echo form_textarea('task_description', $task_description, ["placeholder" => "Enter Task Description"]);
        echo '<div>';
        echo form_label('Complete');
        echo form_checkbox('complete', 1, $complete);
        echo '</div>';
        echo '<div class="text-center">';
        echo anchor($cancel_url, 'Cancel', ['class' => 'button alt']);
        echo form_submit('submit', 'Submit');
        echo form_close();
        echo '</div>';
        ?>
    </div>
</div>