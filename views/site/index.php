<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = 'RubyGarage Test TODO';
?>
    <div class="site-index">

        <div class="jumbotron">
            <p>Simple TODO Lists from RubyGarage</p>
        </div>

        <div class="body-content">
            <div class="form-group" id="projects">
                <div id="projects">
                    <div id="display_msg"></div>
                    <div id="result_form">
                        <?php foreach ($projects as $keyP => $valP): ?>
                        <div class="project-block" id="projectBlock<?= $projects[$keyP]['id'] ?>">
                            <div class="main-panel same-block">
                                <span class="glyphicon glyphicon-th-list btn-lg"></span>
                                <span id="projectName<?= $projects[$keyP]['id'] ?>"><?= $projects[$keyP]['name']?></span>
                                <span class="glyphicon glyphicon-pencil right-buttons" data-toggle="modal" data-target="#editModal" onclick="editProject(<?= $projects[$keyP]['id'];?>);"></span>
                                <span  class="glyphicon glyphicon-trash right-buttons" onclick="removeProject(<?= $projects[$keyP]['id'];?>)"></span>
                            </div>

                        <div class="add-panel same-block">
                            <div class="glyphicon glyphicon-plus btn-lg"></div>
                            <form class="needs-validation" method="post" id="create_task<?= $projects[$keyP]['id']?>" action="" novalidate>
                                <div class="input-group">
                                    <input type="text" pattern="[a-zA-Z0-9_.*]+" class="form-control"  id="task<?= $projects[$keyP]['id']?>" name="task_name" placeholder="NAME" required/>
                                    <div class="invalid-tooltip">
                                        Task name not valid!
                                    </div>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-success add-task" onclick="createTask(<?= $projects[$keyP]['id'] ?>)">Add Task</button>
                                    </span>
                                </div>
                            </form>
                            <div class="alert alert-danger error" id="taskMsgContainer<?= $projects[$keyP]['id']?>" ></div>
                        </div>
                                <div id="taskContainer<?= $projects[$keyP]['id']?>">
                                    <table class="content-block" cellspacing="0" border="1px">
                                        <?php $cnt = 1;
                                        $cntChecked = 0;

                                        foreach ($projects[$keyP]['tasks'] as $keyT => $valT)
                                        {
                                            if ($projects[$keyP]['tasks'][$keyT]['status'] == 1 || $projects[$keyP]['tasks'][$keyT]['deadline_flag'] == 1)
                                            {$cntChecked++;}
                                        }
                                        $cntChecked = count($projects[$keyP]['tasks']) - $cntChecked;
                                        foreach ($projects[$keyP]['tasks'] as $keyT => $valT): ?>
                                            <tr class="list-element <?= $projects[$keyP]['tasks'][$keyT]['deadline_flag'] == 1 ? 'expired' : ''; ?>" >
                                                <td class="checkbox-container"><input type="checkbox" id="doneTask<?= $projects[$keyP]['tasks'][$keyT]['id'] ?>" name="status" <?= $projects[$keyP]['tasks'][$keyT]['status'] == 1 ? 'checked' : '' ?> disabled/></td>
                                                <td class="text-container"><span id="taskName<?= $projects[$keyP]['tasks'][$keyT]['id'] ?>"><?=$projects[$keyP]['tasks'][$keyT]['name']?></span></td>
                                                <td class="buttons-container">
                                                    <?php if ($projects[$keyP]['tasks'][$keyT]['status'] == 0):?>
                                                        <?php
                                                            if ($cnt > 1 && $projects[$keyP]['tasks'][$keyT]['deadline_flag'] == 0) {
                                                                $up = '<div class="glyphicon glyphicon-arrow-up" onclick="task_up(' . $projects[$keyP]['tasks'][$keyT]['id'] . ',' . $projects[$keyP]['id'] . ')"></div>';
                                                                echo $up;
                                                            }
                                                            if (($cnt < $cntChecked) && $projects[$keyP]['tasks'][$keyT]['status'] == 0) {
                                                                $down = '<div class="glyphicon glyphicon-arrow-down" onclick="task_down(' . $projects[$keyP]['tasks'][$keyT]['id'] . ',' . $projects[$keyP]['id'] . ')"></div>';
                                                                echo $down;
                                                            }
                                                        ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="buttons-container">
                                                    <i class="glyphicon glyphicon-pencil"  id="edit<?= $projects[$keyP]['tasks'][$keyT]['id'];?>" data-toggle="modal" data-target="#editTaskModal" onclick="editTask(<?= $projects[$keyP]['tasks'][$keyT]['id'];?>);"></i>
                                                    <i class="glyphicon glyphicon-trash" onclick="removeTask(<?= $projects[$keyP]['tasks'][$keyT]['id'];?>)"></i>
                                                </td>
                                            </tr>
                                            <?php $cnt++ ?>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="same-block main-button-block">
                        <a class="btn btn-primary create-project" data-toggle="modal" data-target="#exampleModal">+ Create Project</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal create project--> <!-- The Modal -->
        <div class="modal fade" id="exampleModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Modal Heading</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form class="needs-validation" method="post" id="create_form" action="" novalidate>
                            <input type="text" pattern="[a-zA-Z0-9]+" class="form-control" name="project_name" placeholder="NAME"  required/><br>
                            <div class="invalid-tooltip">
                                Project name not valid!
                            </div>
                        </form>
                        <br>
                        <div class="alert alert-danger error" role="alert" hidden="false"></div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="create_project" class="btn btn-primary">Create Project</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- modal create project end -->
        <!-- modal edit project -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" method="post" id="edit_form" action="" novalidate>
                            <input type="text" pattern="[a-zA-Z0-9]+" class="form-control" name="name" placeholder="NAME" required/><br>
                            <div class="invalid-tooltip">
                                Project name not valid!
                            </div>
                        </form>
                        <br>
                        <div class="alert alert-danger error" role="alert" hidden="false"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="save_project" class="btn btn-primary">Save Project</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal edit project end-->
        <!-- modal edit task-->
        <div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" method="post" id="editTaskForm" action="" novalidate>
                            <input type="text" pattern="[a-zA-Z0-9._*]+" class="form-control" name="name" placeholder="NAME" required/><br>
                            <div class="invalid-tooltip">
                                Task name not valid!
                            </div>
                            <input type="text" class="form-control" name="deadline" placeholder="DEADLINE" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}"required>
                            <div class="invalid-tooltip">
                                Task deadline not valid!
                            </div>
                            <input type="checkbox" id="status" name="status"/> <span>Done</span><br>
                        </form>
                        <br>
                        <div class="alert alert-danger error" role="alert" hidden="false"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="save_task" class="btn btn-primary">Save Project</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal edit  task end-->
    </div>



<script>
    $(document).ready(function() {
        $("#create_project").click(
            function() {
                createProject('create_form');

                return false;
            }
        );

        $('.site-index form').on('submit', function (e) {
            e.preventDefault();
        });

        $('.modal').on('hidden.bs.modal', function (e) {
            $(this).find('.error').hide();
            $(this).find('input').val('');
        });


    });

    function createProject(projectFormId) {
        let valid = validateForm(projectFormId);
        if (valid) {
            $.ajax({
                url: '/create-project',
                type: "POST",
                dataType: "html",
                data: $("#" + projectFormId).serialize(),
                success: function (response) {
                    let modalId = 'exampleModal';
                    let formId = 'result_form';
                    let msgBlock = 'display_msg';
                    result = $.parseJSON(response);
                    msgAjax(msgBlock, result, modalId, true)

                    if (!result.error) {
                        let newProject = '<div class="project-block" id="projectBlock' + result.project.id + '">' +
                            '<div class="main-panel same-block">\n' +
                            '<span class="glyphicon glyphicon-th-list btn-lg"></span>' +
                            '<span id="projectName+' + result.project.id + '">' + result.project.name + '</span>\n' +
                            '<i class="glyphicon glyphicon-pencil right-buttons" data-toggle="modal" data-target="#editModal" onclick="editProject(' + result.project.id + ');"></i>\n' +
                            '<i class="glyphicon glyphicon-trash right-buttons" onclick="removeProject(' + result.project.id + ')"></i>\n' +
                            '</div>\n' +
                            '<div class="add-panel same-block">' +
                            '<div class="glyphicon glyphicon-plus btn-lg"></div>' +
                            '<form class="needs-validation" method="post" id="create_task' + result.project.id + '" action="" novalidate>'+
                            '<div class="input-group">'+
                            '<input type="text" pattern="[a-zA-Z0-9_.*]+" class="form-control" name="task_name" placeholder="NAME" required/>'+
                            '<div class="invalid-tooltip">'+
                            'Task name not valid!'+
                            '</div>'+
                            '<span class="input-group-btn">'+
                            '<button type="button" class="btn btn-success add-task" onclick="createTask(' + result.project.id + ')">Add Task</button>'+
                            '</span>'+
                            '</div>'+
                            '</form>'+
                            '</div>' +
                            '<div id="taskMsgContainer' + result.project.id + '"></div>\n' +
                            '<div id="taskContainer' + result.project.id + '">' +
                            '</div>' +
                            '</div>';
                        $('#' + formId).prepend(newProject);
                        $('#create_task' + result.project.id).on('submit', function (e) {
                            e.preventDefault();
                        });
                    }
                },
                error: function (response) {
                    let msgBlock = 'display_msg';

                    $('#' + msgBlock).html('Ajax Error!');
                }
            });
        }
    }

    function editProject(id) {
        $.ajax({
            url:     '/edit-project/get/' + id,
            type:     "POST",
            dataType: "html",
            success: function(response) {
                let modalId = 'editModal';
                let editFormId = 'edit_form';
                let formId = 'result_form';
                let msgBlock = 'display_msg';

                result = $.parseJSON(response);
                msgAjax(msgBlock, result, modalId);

                for (let val in result.project)
                {
                    $('#'+ editFormId + ' input[name="' + val + '"]').val(result.project[val]);
                }

                $('#save_project').unbind('click');
                $('#save_project').click({id: result.project.id, editFormId: editFormId}, function (e) {
                    saveProject(e.data.id, e.data.editFormId);
                })
            },
            error: function(response) {
                let msgBlock = 'display_msg';

                $('#' + msgBlock).html('Ajax Error!');
            }
        });
    }

    function saveProject(id, projectFormId) {
        let editFormId = 'edit_form';
        let valid = validateForm(editFormId);
        if (valid) {
            $.ajax({
                url: '/edit-project/set/' + id,
                type: "POST",
                dataType: "html",
                data: $("#" + projectFormId).serialize(),
                success: function (response) {
                    let modalId = 'editModal';
                    let formId = 'result_form';
                    let msgBlock = 'display_msg';

                    result = $.parseJSON(response);
                    msgAjax(msgBlock, result, modalId, true);

                    $('#projectName' + id).html('Name: ' + result.project.name);
                },
                error: function (response) {
                    let msgBlock = 'display_msg';

                    $('#' + msgBlock).html('Ajax Error!');
                }
            });
        }
    }

    function removeProject(id) {
        $.ajax({
            url:     '/remove-project/' + id,
            type:     "POST",
            dataType: "html",
            success: function(response) {
                let modalId = 'editModal';
                let msgBlock = 'display_msg';

                result = $.parseJSON(response);
                msgAjax(msgBlock, result, modalId, true);

                $('#projectBlock' + id).remove();
            },
            error: function(response) {
                let msgBlock = 'display_msg';

                $('#' + msgBlock).html('Ajax Error!');
            }
        });
    }

    function createTask(id) {
        let taskFormId = 'create_task';
        let valid = validateForm(taskFormId + id);
        if (valid) {
            $.ajax({
                url: '/create-task/' + id,
                type: "POST",
                dataType: "html",
                data: $("#" + taskFormId + id).serialize(),
                success: function (response) {
                    let formId = 'taskContainer';
                    let msgBlock = 'taskMsgContainer' + id;

                    result = $.parseJSON(response);
                    msgAjax(msgBlock, result)
                    if (!result.error)
                    {
                        refreshTask(id);
                    }
                },
                error: function (response) {
                    let msgBlock = 'taskMsgContainer' + id;

                    $('#' + msgBlock).html('Ajax Error!');
                }
            });
        }
    }

    function editTask(id) {
        $.ajax({
            url: '/edit-task/get/' + id,
            type: "POST",
            dataType: "html",
            success: function (response) {
                let modalId = 'editTaskModal';
                let editFormId = 'editTaskForm';
                let msgBlock = 'taskMsgContainer';
                let status = 0;

                $('#status').click(function () {
                    if ($(this).prop("checked") == true) {
                        status = 1;
                    } else if ($(this).prop("checked") == false) {
                        status = 0;
                    }
                });
                result = $.parseJSON(response);
                if (!result.error) {
                    for (let val in result.task) {
                        if (val == 'status') {
                            if (result.task[val] == 1) {
                                $('#' + editFormId + ' input[name="' + val + '"]').prop("checked", true);
                            } else {
                                $('#' + editFormId + ' input[name="' + val + '"]').prop("checked", false);
                            }
                        } else {
                            if (val == 'deadline') {
                                $('#' + editFormId + ' input[name="' + val + '"]').val(result.task[val]);
                            } else {
                                $('#' + editFormId + ' input[name="' + val + '"]').val(result.task[val]);
                            }
                        }
                    }

                    $('#save_task').unbind('click');
                    $('#save_task').click({
                        id: result.task.id,
                        project_id: result.task.project_id,
                        editFormId: editFormId
                    }, function (e) {
                        saveTask(e.data.id, e.data.project_id, e.data.editFormId);
                    })
                }
                msgAjax(msgBlock, result, modalId);
            },
            error: function (response) {
                let msgBlock = 'taskMsgContainer';

                $('#' + msgBlock).html('Ajax Error!');
            }
        });
    }

    function saveTask(id,project_id, projectFormId) {
        let editFormId = 'editTaskForm';
        let valid = validateForm(editFormId);
        if (valid) {
            $.ajax({
                url: '/edit-task/set/' + id,
                type: "POST",
                dataType: "html",
                data: $("#" + projectFormId).serialize(),
                success: function (response) {
                    let modalId = 'editTaskModal';
                    let msgBlock = 'taskMsgContainer';
                    let status = false;

                    result = $.parseJSON(response);
                    msgAjax(msgBlock, result, modalId, true);
                    if (result.task.expired == true)
                    {
                        let task = document.getElementById('taskName' + id);
                        console.log(task);
                        task.classList.add('expired');
                        $('#status').attr("disabled", true);
                        $('#edit' + id).remove();
                        refreshTask(project_id);
                    } else {
                        refreshTask(project_id);
                    }

                },
                error: function (response) {
                    let msgBlock = 'taskMsgContainer';

                    $('#' + msgBlock).html('Ajax Error!');
                }
            });
        }
    }

    function removeTask(id) {
        $.ajax({
            url:     '/remove-task/' + id,
            type:     "POST",
            dataType: "html",
            success: function(response) {

                let msgBlock = 'taskMsgContainer';

                result = $.parseJSON(response);
                msgAjax(msgBlock, result);
                refreshTask(result.projectId);
            },
            error: function(response) {
                let msgBlock = 'taskMsgContainer';

                $('#' + msgBlock).html('Ajax Error!');
            }
        });
    }

    function task_up(id, project_id) {
        $.ajax({
            url:     '/task-up/' + project_id + '/' + id,
            type:     "POST",
            dataType: "html",
            success: function(response) {

                let msgBlock = 'taskMsgContainer';

                result = $.parseJSON(response);
                refreshTask(project_id);
            },
            error: function(response) {
                let msgBlock = 'taskMsgContainer';

                $('#' + msgBlock).html('Ajax Error!');
            }
        });
    }

    function task_down(id,project_id) {
        $.ajax({
            url:     '/task-down/' + project_id + '/' + id,
            type:     "POST",
            dataType: "html",
            success: function(response) {

                let msgBlock = 'taskMsgContainer';

                result = $.parseJSON(response);
                refreshTask(project_id);
            },
            error: function(response) {
                let msgBlock = 'taskMsgContainer';

                $('#' + msgBlock).html('Ajax Error!');
            }
        });
    }

    function refreshTask(id) {
        $.ajax({
            url:  '/refresh-task/' + id,
            type:     "POST",
            dataType: "html",
            success: function(response) {
               $('#taskContainer' + id).html(response);
            },
            error: function(response) {
                let msgBlock = 'taskMsgContainer';

                $('#' + msgBlock).html('Ajax Error!');
            }
    });
    }

    function msgAjax(msgBlock, result, modalId = '', close = false) {
        if (result.error == true)
        {
            if (result.errorMsg.length >= 1)
            {
                let msg = '';
                for(let i = 0; i < result.errorMsg.length; i++)
                {
                    msg += result.errorMsg[i] + '<br>';
                }

                $('#' + msgBlock).show();
                $('#' + msgBlock).html(msg);
                if (modalId != '')
                {
                    $('#' + modalId + ' .error').show();
                    $('#' + modalId + ' .error').html(msg);
                }
            }
        } else {
            if (close)
            {
                $('#' + modalId).modal('hide');
            }

        }

    }

    function validateForm(formId) {
        let form = document.getElementById(formId);
        form.classList.add('was-validated');

        return form.checkValidity();
    }
    function deadline() {

    }
</script>