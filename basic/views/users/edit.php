<section class="user">
    <div class="container">
        <div class="row">
            <h1>Edit User</h1>
            <h3 id="username"></h3>
            <div class="success">

            </div>
            <div class="error">

            </div>
            <form action="" class="col s12">
                <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>">
                <div class="row">
                    <div class="input-field col s6">
                        <input id="name" type="text" class="validate">
                        <label for="name">Name</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="email" type="email" class="validate">
                        <label for="email">E-mail</label>
                    </div>
                </div>
                <div class="submit-buttons" style="display: flex; justify-content: space-between">
                    <button class="btn waves-effect waves-light green" id="edit-user" type="submit" name="action">Submit
                </div>
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
        console.log('id = '+id); 
//        GET CONTENT
        $.get('getuser?id='+id, function(data){
        
            console.log(data);
            $('.submit-buttons').append('<button class="waves-effect waves-light btn red delete-user" value="' + data.id + '">delete user</button>');
            $('#name').val(data.name).siblings('label').addClass('active');
            $('#email').val(data.email).siblings('label').addClass('active');        
        });
//GET CONTENT END


//EDIT USER 
       $('#edit-user').on('click', function(e){
            e.preventDefault();
            $('.error').empty();
            var name = $('#name').val();
            var email = $('#email').val();
            var responses ='';
            
            $.ajax({
            
                url: 'update?id='+id,
                method: 'PUT',
                data: {name: name, email:email},
                success: function(data){
                    if(!data.errors){
                        responses += '<div class="card-panel green darken-1 responses"><span class="white-text">User has been modified</span></div>';
                        $('.success').append(responses).trigger('contentChanged');                        
                    } else {
                        console.log(data.errors);
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
            });
       });
    });
//END EDIT USER

//DELETE USER
    $('.submit-buttons').on('click', '.delete-user', function(){
            var id = $(this).val();
            var responses = '';
            console.log(id);
            $.ajax({
                url: 'delete?id='+id,
                type: 'delete',
                success: function(data){
                    window.location.replace('/users');
                }         
            });
    });
//END DELETE USER
EOT_JS
);
