<section class="task">
    <div class="container">
        <div class="row">
            <h1>Tasks table</h1>
            <div class="success">

            </div>
            <div class="error">

            </div>
            <form><input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"></form>
            <table>
                <thead>

                </thead>
                <tbody id="tasks-contents">
                </tbody>
            </table>
        </div>
        <div class="row">
            <form action="" class="col s12">
                <h3>Create task</h3>
                <div class="row">
                    <div class="input-field col s6">
                        <input id="title" type="text" class="validate">
                        <label for="title">Title</label>
                    </div>
                    <div class="input-field col s6">
                        <select name="user" id="users">
                            <option value="" disabled selected>Choose your user</option>
                        </select>
                        <label for="user">User</label>
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

        
//GET CONTENT 
        $.get('tasks/getdata', function(data){
   
            var options = '';
            var tasksWithUser = data.tasksWithUser;
            var tasksWithoutUser = data.tasksWithoutUser;
            var users = data.users;
            
            
            for(var i = 0; i < users.length; i++){
                options += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
            }
            
            var content = createTableTask(tasksWithUser, true);
            var contentWithoutUser = createTableTask(tasksWithoutUser, false, true);
            
            $('thead').append(content.thead);
            $('#users').append(options).trigger('contentChanged');
            $('#tasks-contents').append(contentWithoutUser.tbody);
            $('#tasks-contents').append(content.tbody);
            
        });
//END GET CONTENT

//DELETE TASK
        $('#tasks-contents').on('click', '.delete-task', function(){
            var task_id = $(this).val();
            var responses = '';
            $.ajax({
                url:'tasks/delete?id='+task_id,
                method: 'delete',
                success: function(data){

                    $('#task'+task_id).remove();
                    responses += '\<div class="card-panel green darken-1 responses"><span class="white-text">'+data+'</span></div>';
                    $('.success').append(responses).trigger('contentChanged');                
                }
            })
        });
//END DELETE TASK

//ADD TASK
        $('#submit-task').on('click', function(e){
               e.preventDefault();
               $('.error').empty();
                var title = $('#title').val();
                var description = $('#description').val();
                var user_id = $('#users').val();
                var username = $('.select-dropdown').val();
                var responses = '';
                
               $.ajax({
                    method: 'POST',
                    url: 'tasks/store',
                    data: {title: title, description: description, user_id: user_id},
                    success: function(data){

                        if(!data.errors){
 
                            var content = createTableTask(data, false, false);
                            if(username === 'Choose your user'){
                                username = 'Not associated with a user'
                            };

                        
                            $('#tasks-contents').append(content.tbody);
                            $('#tasks-contents tr:last-child td:nth-child(6)').append(username);
                            responses += '<div class="card-panel green darken-1 responses"><span class="white-text">Tasks has been added</span></div>';
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
//END ADD TASK
    })
EOT_JS
);
