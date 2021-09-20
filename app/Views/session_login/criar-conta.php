<!DOCTYPE html>
<html lang="pt_br">
<head>
    <?php require_once('../includes/head.php'); ?>
    <link rel="stylesheet" type="text/css" href="/FORUM/app/css/style-criar-conta.css">
    <title>Sessão de login - Criar Conta</title>
</head>
<body>

<div class="box">
        <div class="box-form">
            <div class="content-form container py-5 px-4">
                <h1>RESGISTRE-SE</h1>
                <form action="../../Controller/CriarConta.php" method="post">
                    <div class="row">
                        <div class="form-group col-md-6 my-4">
                            <input type="text" class="form-control" name="nome" placeholder="NOME" title="Digite seu nome aqui!" required  id="nome">
                        </div>
                        <div class="form-group col-md-6 my-4">
                            <input type="text" class="form-control" name="sobrenome" placeholder="SOBRENOME" title="Digite seu sobrenome aqui!" required id="sobrenome">
                        </div>
                        <div class="form-group col-md-6 my-4">
                            <input type="text" class="form-control" name="email" placeholder="EMAIL" title="Digite seu e-mail aqui!" required id="email">
                        </div>
                        <div class="form-group col-md-6 my-4">
                            <input type="text" class="form-control" name="rm" placeholder="RM" title="Digite seu RM aqui!" required id="codigo">
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="dtnascimento">Data de nascimento</label>
                            <input type="date" class="form-control" name="dtnascimento" title="Digite sua data de nascimento aqui!" required id="dtnascimento">
                        </div>
                        <div class="form-group col-md-6 my-4">
                            <input type="password" class="form-control" name="senha" placeholder="SENHA" title="Digite sua senha aqui!" required id="senha">
                        </div>

                        <div class="text-center">
                            <!-- VERIFICAÇÃO SE O NOME DE USUÁRIO OU SENHA ESTÃO CORRETOS PARA CADASTRO OU SE O RM JÁ ESTÁ CADSATRO NO DB -->
                            <?php 
                                if (isset($_GET['error'])) { echo '<b style="color: red;">RM OU NOME DE USUÁRIO NÃO COINCIDE EM NOSSO BANCO DE DADOS!</b>'; }
                                if (isset($_GET['error2'])) { echo '<b style="color: red;">ESSE RM JÁ ESTÁ VINCULADO A UMA CONTA EXISTENTE, CERTIFIQUE-SE DE QUE DIGITOU O RM CORRETO OU SE JÁ TEM UMA CONTA DE USUÁRIO REGISTRADO!</b>'; } 
                             ?>
                        </div>

                        <div class="form-group col-md-12 mt-4 text-center">
                            <input type="submit" class="button-submit py-3 my-3" id="submit" title="Clique aqui para cirar sua conta!" value="CRIAR"><br>
                            <a href="login.php" class="button-voltar py-3">RETORNAR A SESSÃO DE LOGIN</a>
                        </div>
                    </div>
                    <!-- fim row -->
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>