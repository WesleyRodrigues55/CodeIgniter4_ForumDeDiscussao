<?php
use App\Controllers\ValidaSessao;
$validar = new ValidaSessao();
$validar->validaSessaoPerfil($usuario['ID']);

if ($usuario['Nivel'] == 1) {
    $usuario['Nivel'] = 'Usuário';
}
else if ($usuario['Nivel'] == 2) {
    $usuario['Nivel'] = 'Moderador';
} else {
    $usuario['Nivel'] = 'Administrador';
}
?>


<br><br><br><br>
<div class="container my-5">
	<div class="card-perfil-publico row">
		<div class="text-center">
			<h2>Alterar dados de perfil</h2>
		</div>
		<div class="col-md-12 col-lg-3">
			<div class="box-info-perfil">
				<img src="/FORUM_CODEIGNITER/assets/img/usuarios/<?php echo $usuario['Foto']; ?>">
				<br><br>
				<p><b><?php echo $usuario['Nivel'] . ':</b> ' .$usuario['Nome'] . ' ' . $usuario['Sobrenome'] ?></p>
			</div>
		</div>

		<div class="col-md-12 col-lg-3">
			<div class="box-dados-perfil">
				<?php
					echo '<p>Escolha uma nova foto de perfil</p>';
					helper('form');
					echo form_open_multipart('Usuario/alterarImagemUsuario');
						echo form_input('id', $usuario['ID'], 'class="d-none" readonly');
						echo form_upload('image[]', '', 'class="form-control"');
						echo form_submit('mysubmit', 'Alterar foto', 'class="botao-alterar-perfil my-3"');
					echo form_close();
					?>
			</div>
		</div>

		<div class="col-md-12 col-lg-3">
			<div class="box-dados-perfil">
				<?php
				echo '<p>Alterar senha:</p>';
					helper('form');
					echo form_open('/FORUM_CODEIGNITER/public/Usuario/alterarSenhaUsuario');
						echo form_input('id', $usuario['ID'], 'class="d-none" readonly');
						echo form_input('senha', '', 'class="form-control w-100 my-1" placeholder="Digite a nova senha"');
						echo form_input('senha2', '', 'class="form-control w-100 my-1" placeholder="Confirme a senha"');
						echo form_submit('mysubmit', 'Alterar senha', 'class="botao-alterar-perfil my-3"');
					echo form_close();
					?>
			</div>
		</div>

		<div class="col-md-12 col-lg-3">
			<div class="box-dados-perfil">
				<?php
				echo '<p>Alterar dados pessoais:</p>';
				helper('form');
				echo form_open('/FORUM_CODEIGNITER/public/Usuario/alterarDadosUsuario');
					echo form_input('id', $usuario['ID'], 'class="d-none" readonly');
					echo form_input('nome', $usuario['Nome'], 'class="form-control w-100 my-1"');
					echo form_input('sobrenome', $usuario['Sobrenome'], 'class="form-control w-100 my-1"');
					echo form_input('data', $usuario['DataNascimento'], 'class="form-control w-100 my-1"', 'date');

					echo form_submit('mysubmit', 'Alterar dados', 'class="botao-alterar-perfil my-3"');
				echo form_close();
				?>
			</div>
		</div>
		<div class="text-center">
			<a href="">Caso tenha dúvidas dos dados a serem alterados, peça ajuda ao suporte</a>
		</div>
	</div>
</div>
<!-- end container -->
<br><br><br><br><br><br><br><br>

<!-- MODAL ALERTA DE PRIVACIDADE -->
<div class="modal fade" id="AlertaPrivacidade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Política de privacidade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="resultadoPrivacidade">
                    
                </div>
                <p>Termos de politica de privacidade</p>
                
            </div>
            <div class="modal-footer">
                <a href="/FORUM_CODEIGNITER/public/Usuario/logout" class="btn btn-danger">Discordo</a>
                <div class="resultadoA"></div>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function(){
        function verificar_privacidade()
        {
            $.ajax({
                url:"/FORUM_CODEIGNITER/public/ValidaSessao/verificarPrivacidade/" + <?php echo json_encode(session()->id) ?> + "/" + <?php echo json_encode(session()->privacidade) ?>,
                method: 'POST',
                dataType: 'json'
                }).done(function(alertaPrivacidade){
                    console.log(alertaPrivacidade);
                    // var box_comment = document.querySelector('.resultadoPrivacidade');
                    // while(box_comment.firstChild){
                    //     box_comment.firstChild.remove();
                    // }
                    for (var i = 0; i < alertaPrivacidade.length; i++) {

                        if (<?php echo json_encode(session()->has('id')) ?> == false) {
                            
                        } else {
                            if (<?php echo json_encode(session()->privacidade) ?> == 0) {
                                $('#AlertaPrivacidade').modal('show');
                            } else {
                                $('#AlertaPrivacidade').modal('hide');
                            }
                        }
                        $('.resultadoA').prepend(
                            '<form method="post" id="formPrivacidade">' +
                                '<input type="hidden" id="idusuario" name="idusuario" value="'+ alertaPrivacidade[i].ID +'">' +
                                '<button type="submit" form="formPrivacidade" class="btn btn-primary">Concordo</button>' +
                            '</form>');
                    }   
                    $("#formPrivacidade").submit(function(e) {
                        e.preventDefault();    
                        var formData = new FormData(this);

                        $.ajax({
                            url: '/FORUM_CODEIGNITER/public/Usuario/privacidadeConfirmar',
                            type: 'POST',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false
                            
                        }).done(function(){
                            
                            $('#idusuario');
                            alert('Você concordou com os dados de privacidade, curta o conteúdo com boas práticas!')
                            $('#AlertaPrivacidade').modal('hide');
                        });
                    });   
            });
        }
        verificar_privacidade();   
    });
</script>
