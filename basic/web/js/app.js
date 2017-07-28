function fstLetterToUpper(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

$('.success').on('contentChanged', function(){
    setTimeout(function(){
        $('.responses').fadeOut().remove();}, 3000);
});

$('#users').on('contentChanged', function(){
    $(this).material_select();
});

function createTableTask(array, thead, noname){
    var content = {thead: '', tbody: ''};

    if(Array.isArray(array)){

        if(thead === true){
            for(key in array[0]){
                if(key != 'user_id'){
                    content.thead += '<th>'+ fstLetterToUpper(key) + '</th>';
                }
            }
        }

        for(var i = 0; i < array.length; i++){
            content.tbody += '<tr id="task' + array[i]['id'] + '">';

            for(key in array[i]){
                if(key != 'user_id'){
                    content.tbody += '<td>' + array[i][key] + '</td>';
                }
            }

            if(noname === true){
                content.tbody += '<td>Not associated with a user</td>'
            }


            content.tbody += '<td><a class="waves-effect waves-light btn blue edit-task" href="/tasks/edit?id=' + array[i]['id'] + '">edit</a></td>';
            content.tbody += '<td><button class="waves-effect waves-light btn red delete-task" value="' + array[i]['id'] + '">delete</button></td>';
            content.tbody += '</tr>';
        }

        return content;

    }else if(typeof(array) === 'object' ) {

        content.tbody += '<tr id="task' + array['id'] + '">';

        for(key in array){

            if(key != 'user_id'){
                content.tbody += '<td>'+ array[key] +'</td>';
            }
        }
        content.tbody += '<td></td>';
        content.tbody += '<td><a class="waves-effect waves-light btn blue edit-task" href="/tasks/edit?id=' + array['id'] + '">edit</a></td>';
        content.tbody += '<td><button class="waves-effect waves-light btn red delete-task" value="' + array['id'] + '">delete</button></td>';
        content.tbody += '</tr>';

        return content;

    } else {
        console.log('Array must be an object');
    }

}
