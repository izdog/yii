<section class="user">
    <div class="container">
        <div class="row">
            <div class="user-content">
                <div class="user-button" style="display: flex; align-items: center; justify-content: flex-start">
                    <h1 id="user-name"></h1>
                </div>
                <p id="user-email"></p>
            </div>

        </div>
        <div class="row">
            <div class="success">

            </div>
            <div class="error">

            </div>
            <form><input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"></form>
            <h4>Tasks</h4>
            <table id="tasks-content">
                <thead>
                <tr>

                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="row">
            <form action="" class="col s6">
                <h4>Add a task</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="title" type="text" class="validate">
                        <label for="title">Title</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="description" class="materialize-textarea"></textarea>
                        <label for="description">Description</label>
                    </div>
                </div>
                <button class="btn waves-effect waves-light green" id="submit-task" type="submit" name="action" style="margin-bottom: 100px">Submit
                </button>
            </form>
        </div>
    </div>
</section>
<?php
$this->registerJs( <<< EOT_JS
    $(document).ready(function(){
         var url = window.location.href;
         var id = url.split(/[? ]+/).pop(); 
         id = id.split(/[= ]+/).pop();
  
//GET CONTENT 
        $.get('tasks?id='+ id, function(data){
        
            var tbody = '';
            var thead = '';
            var userButton = '';
            var tasks = data.tasks;
            var user = data.user;
            
            for(key in tasks[0]){
                 if(key != 'user_id'){
                       thead += '<th>'+ fstLetterToUpper(key) + '</th>';
                  }
               }
             for(var i = 0; i < tasks.length; i++){
                   tbody += '<tr id="task' + tasks[i]['id'] + '">';

                   for(key in tasks[i]){
                       if(key != 'user_id'){
                        tbody += '<td>' + tasks[i][key] + '</td>';
                       }
                   }

                   tbody += '<td><a class="waves-effect waves-light btn blue edit-task" href="/tasks/edit?id=' + tasks[i]['id'] + '">edit</a></td>';
                   tbody += '<td><button class="waves-effect waves-light btn red delete-task" value="' + tasks[i]['id'] + '">delete</button></td>';
                   tbody += '</tr>';
               }

               userButton += '<a style="margin-left: 10px; margin-right: 10px;" class="waves-effect waves-light btn blue edit-user" href="/users/edit?id=' + user.id + '">edit</a>';
               userButton += '<button class="waves-effect waves-light btn red delete-user" value="' + user.id + '">delete</button>';

               $('.user-button').append(userButton);
               $('#tasks-content tbody').append(tbody);
               $('#tasks-content thead tr').append(thead);
               $('#user-name').append(user.name);
               $('#user-email').append(user.email);
        });
//END GET CONTENT

//ADD TASK
        $('#submit-task').on('click', function(e){
            e.preventDefault();
            var user_id = id;
            var title = $('#title').val();
            var description = $('#description').val();
            var responses = '';
            var tbody = '';
            
            $.ajax({
                method: 'POST',
                url: '/tasks/store',
                data: {user_id: user_id, title: title, description: description},
                
                success: function(data){
                    console.log(data);
                    tbody += '<tr id="task' + data.id + '">';
                    tbody += '<td>' + data.id  + '</td>';
                    tbody += '<td>' + data.title  + '</td>';
                    tbody += '<td>' + data.description  + '</td>';
                    tbody += '<td>' + data.status  + '</td>';
                    tbody += '<td>' + data.created_at  + '</td>';
                    tbody += '<td><a class="waves-effect waves-light btn blue edit-task" href="/tasks/edit?id=' + data.id + '">edit</a></td>';
                    tbody += '<td><button class="waves-effect waves-light btn red delete-task" value="' + data.id + '">delete</button></td>';
                    tbody += '</tr>';

                    responses = '<div class="card-panel green darken-1 responses"><span class="white-text">Task has been created</span></div>';

                    $('#title').val('');
                    $('#description').val('');
                    $('#tasks-content tbody').append(tbody);
                    $('.success').append(responses).trigger('contentChanged');
                }
            });
        });
//END ADD TASK

//DELETE TASK
         $('#tasks-content').on('click', '.delete-task', function(){
                var task_id = $(this).val();
                var responses = '';
                $.ajax({
                    url:'/tasks/delete?id='+task_id,
                    method: 'delete',
                    success: function(data){
                        console.log(data);
                        $('#task'+task_id).remove();
                        responses += '\<div class="card-panel green darken-1 responses"><span class="white-text">'+data+'</span></div>';
                        $('.success').append(responses).trigger('contentChanged');
                    }
                })
        });
//END DELETE TASK

//DELETE USER
        $('.user-button').on('click','.delete-user', function(){
        console.log('trigger');
               $.ajax({
                method: 'delete',
                url:'delete?id='+id,
                success: function(){
                    window.location.replace('/users'); 
                }
               });
        });

//END DELETE USER
    });
EOT_JS
);