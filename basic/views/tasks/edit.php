<section class="user">
    <div class="container">
        <div class="row">
            <h1>Edit Task</h1>
            <div class="success">

            </div>
            <div class="error">

            </div>
        </div>
        <div class="row">
            <form action="" class="col s12">
                <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>">
                <div class="row">
                    <div class="input-field col s6">
                        <input id="title" type="text" class="validate">
                        <label for="title">Title</label>
                    </div>
                    <div class="input-field col s6">
                        <select name="user" id="users">
                            {{--<option value="" disabled selected>Choose your user</option>--}}
                        </select>
                        <label for="user">User</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <textarea id="description" class="materialize-textarea"></textarea>
                        <label for="description">Description</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="status" type="text" class="validate">
                        <label for="status">Status</label>
                    </div>
                </div>
                <div class="submit-buttons" style="display: flex; justify-content: space-between">
                    <button class="btn waves-effect waves-light green" id="edit-task" type="submit" name="action">Submit
                </div>
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
        $.get('gettask?id='+id, function(data){

            var task = data.task;
            var users = data.users;
            var options = '';

            
            $('.submit-buttons').append('<button class="waves-effect waves-light btn red delete-task" value="' + task.id + '">delete task</button>');
            $('#title').val(task.title).siblings('label').addClass('active');
            $('#description').val(task.description).siblings('label').addClass('active');
            $('#status').val(task.status).siblings('label').addClass('active');

            if(task['user_id'] === null ){
                options += '<option value="" disabled selected>Choose your user</option>';
            }

            for(var i = 0; i < users.length; i++ ){
                if(users[i]['id'] === task['user_id']){
                    options += '<option value="' + users[i]['id'] + '" selected>' + users[i]['name'] + '</option>';
                } else {
                    options += '<option value="' + users[i]['id'] + '">' + users[i]['name'] + '</option>'
                }
            }

            $('#users').append(options).trigger('contentChanged');
        })
//END GET CONTENT

//DELETE TASK
        $('.submit-buttons').on('click', '.delete-task', function(){
                var task_id = $(this).val();
                $.ajax({
                    url:'delete?id='+task_id,
                    method: 'delete',
                    success: function(data){

                        window.location.replace('/tasks');                   
                    }
                })
        });
//END DELETE TASK

//UPDATE TASK

        $('#edit-task').on('click', function(e){
               e.preventDefault();
               $('.error').empty();
                var title = $('#title').val();
                var description = $('#description').val();
                var user_id = $('#users').val();
                var status = $('#status').val();
                var responses = '';
                
               $.ajax({
                    method: 'put',
                    url: 'update?id='+id,
                    data: {title: title, description: description, user_id: user_id, status: status},
                    success: function(data){

                        if(!data.errors){
                        responses += '<div class="card-panel green darken-1 responses"><span class="white-text">User has been modified</span></div>';
                        $('.success').append(responses).trigger('contentChanged');                        
                        } else {

                            responses += '\<div class="card-panel red darken-1 responses">';
                            for(key in data.errors){
                                data.errors[key].forEach(function(el){
                                    responses += '<p><span class="white-text">'+el+'</span></p>';
                                });
                            }
                            responses += '\</div>';
                            $('.error').append(responses);
                        } 
                    }
                    
               })
       });
//END UPTDATE TASK
    })
EOT_JS
);
