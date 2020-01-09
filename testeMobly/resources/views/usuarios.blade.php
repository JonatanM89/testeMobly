@extends('master')

@section('content')
    <h1>Usuários</h1>
    <input name="_token" id="token" type="hidden" value="{{ csrf_token() }}"/>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <button class="btn btn-secondary" onclick="importarUsuarios()" style="float:right">Importar da API</button>
            <button class="btn btn-primary" onclick="editarAddUser(0)" style="float:right">Adicionar</button>
        </div>
    </div>
    <br/>
     <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div id="alert_aguarde" style="display: none" class="alert alert-warning" role="alert">
                Aguarde, importando usuários
            </div>

            <div id="alert_erro" style="display: none" class="alert alert-danger" role="alert">
                Ocorreu um erro
            </div>
        </div>
    </div>
    <div class="row">
        <div id="tabela_usuarios" class="col-sm-12 col-md-12 col-lg-12 col-xl-12">

        </div>
    </div>


    <div id="modal_editar" class="modal fade" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h3 id="title_modal" class="modal-title">Editar</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" value="0" id="modal_id" />
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                      <label>Nome</label>
                      <input type="text" class="form-control" id="modal_name" placeholder="Nome" value="">
                    </div>
                    <div class="form-group col-sm-12 col-md-8 col-lg-8 col-xl-8">
                      <label>Email</label>
                      <input type="email" class="form-control" id="modal_email" placeholder="Email" value="">
                    </div>
                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                      <label>Nome de usuário</label>
                      <input type="text" class="form-control" id="modal_username" placeholder="Username" value="">
                    </div>
                </div>
                <div id="alert_erro_modal" style="display: none" class="alert alert-danger" role="alert">
                </div>
                <div id="alert_aguarde_modal" style="display: none" class="alert alert-warning" role="alert">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
              <button onclick="saveUser()" type="button" class="btn btn-primary">Salvar</button>
            </div>
          </div>
        </div>
      </div>
@stop

@section('js')
<script>

window.onload=function()
{
  carregarUsuarios()
}

function editarAddUser(id){

    clearModal()

    if(id == 0){
        $("#title_modal").text("Adicionar")
        $("#modal_editar").modal()
    }
    else{
        $("#title_modal").text("Editar")
        editUser(id)
    }

}

function saveUser(){
    validar = true;
    campos  = '';

    if( $("#modal_name").val() == ""){
        validar = false;
        campos  = 'Preencha o nome / ';
    }

    if( $("#modal_email").val() == ""){
        validar = false;
        campos  += 'Preencha o email / ';
    } else {
        if ( !validacaoEmail( $("#modal_email").val() ) ) {
            validar = false;
            campos  += 'Email inválido / ';
        }
    }

    if( $("#modal_username").val() == ""){
        validar = false;
        campos  += 'Preencha o nome de usuário';
    }

    if( !validar){
        $("#alert_erro_modal").empty().html("<p>"+campos+"</p>")
        $("#alert_erro_modal").show()
    } else {

        $("#alert_erro_modal").empty()
        $("#alert_erro_modal").hide()

        var formData = new FormData();
        formData.append('modal_id', $("#modal_id").val())
        formData.append('modal_name', $("#modal_name").val())
        formData.append('modal_email', $("#modal_email").val())
        formData.append('modal_username', $("#modal_username").val())

        $.ajax({
            url : "/users/save",
            cache:false,
            data: formData,
            processData: false,
            contentType: false,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN':  $("#token").val()
            },
            beforeSend:function()
            {
                $("#alert_aguarde_modal").empty().html("<p>Salvando</p>")
                $("#alert_aguarde_modal").show()
            },
            success : function (data)
            {
                clearModal()
                $("#modal_editar").modal("toggle")
                carregarUsuarios()
            },
            error : function (err)
            {
                $("#alert_erro_modal").empty().html("<p>Ocorreu um erro ao salvar!</p>")
                $("#alert_erro_modal").show()
                $("#alert_aguarde_modal").hide()
            }
        });

    }
}

function apagar_usuario(id)
{
    var r = confirm("Deseja realmente excluir este usuário? Esta ação será irreverssível!");
    if (r == true) {
        $.ajax({
            url : "/users/delete/"+id,
            cache:false,
            type: 'delete',
            headers: {
                'X-CSRF-TOKEN': $("#token").val()
            },
            beforeSend:function()
            {

            },
            success : function (data)
            {
                carregarUsuarios()
            },
            error : function (err)
            {
                $("#alert_erro").empty().html('<p>Ocorreu um erro ao tentar excluir o usuário!</p>');
                $("#alert_erro").show();
            }
            });
    }

}

function validacaoEmail(field) {
    usuario = field.substring(0, field.indexOf("@"));
    dominio = field.substring(field.indexOf("@")+ 1, field.length);
    if ((usuario.length >=1) &&
        (dominio.length >=3) &&
        (usuario.search("@")==-1) &&
        (dominio.search("@")==-1) &&
        (usuario.search(" ")==-1) &&
        (dominio.search(" ")==-1) &&
        (dominio.search(".")!=-1) &&
        (dominio.indexOf(".") >=1)&&
        (dominio.lastIndexOf(".") < dominio.length - 1))
    {
        return true;
    }
    else{
        return false;
    }
}

function clearModal(){
    $("#modal_id").val("0");
    $("#modal_name").val("");
    $("#modal_email").val("");
    $("#modal_username").val("");
    $("#alert_aguarde_modal").hide()
    $("#alert_erro_modal").hide()
}

function editUser(id){
    $("#modal_editar").modal()

    $.ajax({
        url : "/users/"+id,
        cache:false,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $("#token").val()
        },
        beforeSend:function()
        {

        },
        success : function (data)
        {
            $("#modal_id").val(data.id);
            $("#modal_name").val(data.name);
            $("#modal_email").val(data.email);
            $("#modal_username").val(data.username);
        },
        error : function (err)
        {
            $("#alert_aguarde").hide()
            $("#alert_erro").show()
        }
    });
}

function carregarUsuarios(){
    $.ajax({
        url : "/users/getall",
        cache:false,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $("#token").val()
        },
        beforeSend:function()
        {
            $("#tabela_usuarios").empty().html('Aguarde...')
        },
        success : function (data)
        {
            var div = '<table class="table">'
            div    += '   <thead>';
            div    += '     <tr>';
            div    += '       <th scope="col">#</th>';
            div    += '       <th scope="col">Nome</th>';
            div    += '       <th scope="col">Email</th>';
            div    += '       <th scope="col">Opções</th>';
            div    += '     </tr>';
            div    += '   </thead>';
            div    += '   <tbody>';

            for (var i = 0; i< data.length; i++)
            {
                div    += ' <tr>';
                div    += '     <th scope="row">'+data[i].id+'</th>';
                div    += '     <td>'+data[i].name+'</td>';
                div    += '     <td>'+data[i].email+'</td>';
                div    += '     <td>';
                div    += '         <button onclick="editarAddUser('+data[i].id+')" class="btn btn-sm btn-primary">Editar</button>';
                div    += '         <button onclick="apagar_usuario('+data[i].id+')" class="btn btn-sm btn-danger">Excluir</button>';
                div    += '         <a class="btn btn-sm btn-success">Posts</button>';
                div    += '     </td>';
                div    += ' </tr>';
            }

            div    += '   </tbody>';
            div    += '</table>';


           $("#tabela_usuarios").empty().html(div)
        },
        error : function (err)
        {
            $("#alert_aguarde").hide()
            $("#alert_erro").show()
            //alert("Ocorreu um erro: "+err);
        }
    });
}


function importarUsuarios(){

    $.ajax({
        url : "/importar_user_api",
        cache:false,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $("#token").val()
        },
        beforeSend:function()
        {
            $("#alert_aguarde").show()
        },
        success : function (data)
        {
            if(data == "ok"){
                $("#alert_aguarde").hide()
                carregarUsuarios()
            }

        },
        error : function (err)
        {
            $("#alert_aguarde").hide()
            $("#alert_erro").empty().html('<p>Ocorreu um erro ao tentar importar usuários da API</p>');
            $("#alert_erro").show()
            //alert("Ocorreu um erro: "+err);
        }
    });
}

</script>
@stop
