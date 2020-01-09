@extends('master')

@section('content')
    <h1>Posts</h1>

    <div class="row" id="feed_posts">

    </div>

@stop

@section('js')
<script>

window.onload=function()
{
  loadFeeds()
}

function loadFeeds(){
    $.ajax({
        url : "/posts/getall",
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




           $("#feed_posts").empty().html(div)
        },
        error : function (err)
        {
            //$("#alert_aguarde").hide()
            //$("#alert_erro").show()
            //alert("Ocorreu um erro: "+err);
        }
    });
}

</script>
@stop
