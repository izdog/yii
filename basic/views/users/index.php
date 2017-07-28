
<section class="task">
    <div class="container">
        <div class="row">
            <h1>User table</h1>
            <div class="success">

            </div>
            <div class="error">

            </div>
            <table>
                <thead>
                <tr>
                    <th>id</th>
                    <th>User name</th>
                    <th>email</th>
                </tr>
                </thead>
                <tbody id="user-contents">
                </tbody>
            </table>
        </div>
        <div class="row">
            <?php
            use yii\bootstrap\ActiveForm;

            $form = ActiveForm::begin([
                'action' => '',
                'id' => 'users-form',
                'options' => ['class' => ['col', 's12']],
                'enableAjaxValidation' =>  false,
            ]) ?>

<!--                <form action="" class="col s12">-->
                <h3>Add user</h3>
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
                <button class="btn waves-effect waves-light green" id="submit-user" type="submit" name="action" style="margin-bottom: 100px">Submit
                </button>
            <?php ActiveForm::end() ?>
<!--                </form>-->
        </div>
    </div>
</section>

<?php
$this->registerJs( <<< EOT_JS

//GET CONTENT 
    $.get('users/test', function(data){

                var table = '';
                var options = '';

                // ADD USERS IN TBODY

                for(var i = 0; i < data.length; i++){

                    table += '<tr id="user'+data[i]['id']+'">';

                    for(key in data[i]){
                        table += '<td>' + data[i][key] + '</td>';
                    }

                    table += '<td><a class="waves-effect waves-light btn" href="users/view?id='+data[i]['id']+'">tasks</a></td>';
                    table += '<td><a class="waves-effect waves-light btn blue edit-user" href="users/edit?id='+data[i]['id']+'">edit</a></td>';
                    table += '<td><button class="waves-effect waves-light btn red delete-user" value="'+data[i]['id']+'">delete</button></td>';
                    table += '</tr>';

                }

                $('#user-contents').append(table);

    });
//      END GET CONTENT
            
//       ADD USER    
    $('#submit-user').on('click', function(e){
        e.preventDefault();


        var csrf = $('input[name="_csrf"]').attr('value');
        var name = $('#name').val();
        var email = $('#email').val();
        var responses = '';
  
        $.ajax({

            method: 'POST',
            url: 'users/store',
            data: {name: name, email: email},
            
            success: function(data){

                 if(!data.errors){

                    var table = '<tr id="user'+data.id+'">';
                        var option = '<option value="'+data.id+'">'+data.name+'</option>';

                        table += '<td>'+ data.id+'</td>';
                        table += '<td>'+ data.name+'</td>';
                        table += '<td>'+ data.email+'</td>';
                        table += '<td><a class="waves-effect waves-light btn" href="users/view?id=' + data.id + '">tasks</a></td>';
                        table += '<td><a class="waves-effect waves-light btn blue edit-user" href="users/edit?id=' + data.id + '">edit</a></td>';
                        table += '<td><button class="waves-effect waves-light btn red delete-user" value="' + data.id + '">delete</button></td>';
                        table += '</tr>';

                        responses = '\<div class="card-panel green darken-1 responses"><span class="white-text">'+data.name.toUpperCase()+'\ has been created</span></div>';

                        $('#name').val('');
                        $('#email').val('');
                        $('tbody').append(table);
                        $('#user').append(option).trigger('contentChanged');
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
            },

            error: function(data){
            }
        
        });
        return false;
    });
//    END ADD USER

//DELETE USER
    $('#user-contents').on('click', '.delete-user', function(){
            var id = $(this).val();
            var responses = '';

            $.ajax({
                url: 'users/delete?id='+id,
                type: 'delete',
                success: function(data){
                    $('#user'+id).remove();
                    responses += '\<div class="card-panel green darken-1 responses"><span class="white-text">'+data+'</span></div>';
                    $('.success').append(responses).trigger('contentChanged');
                }
            
            
            });
    });
//END DELETE USER

EOT_JS
);
