@extends('master')

@section('content')
    <h1 style="text-align:center">Posts</h1>

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <label>Posts de </label>
            <select id="select_user" class="form-group">
                <option selected>Todos</option>
                @foreach($users as $user)
                <option value="<?=$user->apiId != '' ? $user->apiId : -1?>"><?=$user->name?></option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row" id="feed_posts">

    </div>

@stop

@section('js')
<script>

window.onload=function()
{
  loadFeeds(0)
}

function loadFeeds(id){
    $.ajax({
        url : "/users/"+id+"/posts",
        cache:false,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $("#token").val()
        },
        beforeSend:function()
        {
            $("#feed_posts").empty().html('Aguarde...')
        },
        success : function (data)
        {
            var div = '';

            for (var i = 0; i< data.length; i++)
            {
                if(data[i].title != "") {
                    div += '<div class="card col-sm-12 col-md-12 col-lg-12 col-xl-12">'
                    div += '    <div class="card-body">'
                    div += '        <h4 class="card-title">'+data[i].title+'</h4>'
                    div += '        <h6 class="card-subtitle mb-2 text-muted">Postado por '+data[i].usuario+'</h6>'
                    div += '        <p class="card-text">'+data[i].body+'</p>'
                    div += '    </div>'
                    div += '    <hr/>'
                    div += '</div>'
                }

            }

            if(data.length == 0){
                div += '<p style="text-align:center"><b>Nada para mostrar</b></p>'
            }



           $("#feed_posts").empty().html(div)
        },
        error : function (err)
        {
            //$("#alert_aguarde").hide()
            //$("#alert_erro").show()
            //alert("Ocorreu um erro: "+err);
        }
    });



    $('#select_user').on('change', function (e) {

        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        loadFeeds(valueSelected)


    });
}

</script>
@stop
