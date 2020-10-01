<table class="content-block" cellspacing="0" border="1px">
    <?php $cnt = 1;
    $cntChecked = 0;
    foreach ($task as $keyT => $valT)
    {
    if ($task[$keyT]['status']==1 || $task[$keyT]['deadline_flag'] == 1)
    {$cntChecked++;}
    }
    $cntChecked = count($task) - $cntChecked;

    foreach ($task as $keyT => $valT): ?>

        <tr class="list-element <?= $task[$keyT]['deadline_flag'] == 1 ? 'expired' : ''; ?>">
            <td class="checkbox-container"><input type="checkbox" id="doneTask<?= $task[$keyT]['id'] ?>" name="status" <?= $task[$keyT]['status'] == 1 ? 'checked' : '' ?> disabled/></td>
            <td class="text-container"><span id="taskName<?= $task[$keyT]['id'] ?>"><?=$task[$keyT]['name']?></span></td>
            <td class="buttons-container">
                <?php if ($task[$keyT]['status'] == 0):?>
                    <?php
                    if ($cnt > 1 && $task[$keyT]['deadline_flag'] == 0) {
                        $up = '<div class="glyphicon glyphicon-arrow-up" onclick="task_up(' . $task[$keyT]['id'] . ',' . $task[$keyT]['project_id'] . ')"></div>';
                        echo $up;
                    }
                    if (($cnt < $cntChecked) && $task[$keyT]['status'] == 0) {
                        $down = '<div class="glyphicon glyphicon-arrow-down" onclick="task_down(' . $task[$keyT]['id'] . ',' . $task[$keyT]['project_id']  . ')"></div>';
                        echo $down;
                    }
                    ?>
                <?php endif; ?>
            </td>
            <td class="buttons-container">
                <i class="glyphicon glyphicon-pencil" <?= $task[$keyT]['deadline_flag'] == 0 ? 'data-toggle="modal" data-target="#editTaskModal" onclick="editTask(' . $task[$keyT]['id'] . ');"' : ''; ?> ></i>
                <i class="glyphicon glyphicon-trash" onclick="<?= $task[$keyT]['deadline_flag'] == 0 ? 'removeTask(' . $task[$keyT]['id'] . ')' : '' ?>"></i>
            </td>
        </tr>



    <?php $cnt++ ?>
    <?php endforeach; ?>
</table>

