<?php

namespace App\Controllers;

class Usuario extends BaseController
{
    //============================================================================
    # INICIAR LOGIN
	public function login()
	{
		return view('includes/head') . view('login/login');
	}

    //============================================================================
    # LOGOUT
    public function logout()
    {
		session()->destroy();
		return redirect()->to('usuario/login');
    }

    //============================================================================
    #SESSÃO DE LOGIN
    public function recebeDadosLogin()
    {
        //recebe dados do formulário
        $this->usuario = $this->request->getPost()['usuario'];
        $this->senha = $this->request->getPost()['senha'];
        $this->rm = $this->request->getPost()['rm'];
    }

    public function verificarLogin()
    {
        $this->recebeDadosLogin();

        //consulta sql personalizada
        $db      = \Config\Database::connect();
        $builder = $db->table('usuario');
        $builder->select('ID, Nome, Email, Senha, Nivel, Ativo');
        $builder->where('Email', $this->usuario);
        $builder->where('Senha', md5($this->senha));
        $builder->where('RM', $this->rm);
        $builder->where('Ativo', 1);
        $query = $builder->get()->getResultArray();

        //verifica como que está a estrutura do select
        // var_dump($builder->getCompiledSelect());

        if ($query == false) {
            return redirect()->to('usuario/login?error'); 
        } else {
            echo '<pre>';

            session()->set([
                'id' => $query[0]['ID'],
                'usuario' => $query[0]['Nome'],
                'email' => $query[0]['Email'],
                'nivel' => $query[0]['Nivel'],
                'ativo' => $query[0]['Ativo'],
            ]);
            return redirect()->to('../'); 
        }
        // print_r(session()->get());
    }

    //============================================================================
    # VERIFICAÇÃO DE NÍVEL DE USUÁRIO
    public function consultaNivel()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('usuario');
        $builder->select('ID, Nome, Nivel, Ativo');
        $builder->where('ID', session()->id);
        $query = $builder->get()->getResultArray();

        session()->set([
            'id' => $query[0]['ID'],
            'usuario' => $query[0]['Nome'],
            'nivel' => $query[0]['Nivel'],
            'ativo' => $query[0]['Ativo'],
        ]);

        if (session()->ativo != 1)
        {
            session()->destroy();
        }
    }

    public function nivel()
    {
        $this->consultaNivel();

        

        //usuario = 1
        // if (session()->nivel == 1) 
        // {
        // } 

        //moderador = 2
        // if (session()->nivel == 2) 
        // {
        //     echo "Publicações | Usuários";
        // }

        //administrador = 3
        if (session()->nivel == 3) 
        {
            //habilitar caso necessite criar um banner home (lembrando que deve ter apenas um banner)
            // echo '<a href="administrador/banner_home" class="btn btn-primary my-3">Banner principal</a>'; 
            echo '<a href="administrador/">Banner notícias</a>'; 
            echo '<a href="administrador/categoria" class="btn btn-primary my-3 mx-3">Cria categoria</a>'; 
            echo '<a href="administrador/index" class="btn btn-primary my-3">Usuários registrados</a>'; 
        }
    }

    //============================================================================
    #CADASTRO DE USUÁRIO
    public function registraUsuario()
    {
        return view('includes/head') . view('login/registre-se');
    }

    public function recebeDadosCadastro()
    {
         //recebe dados do formulário
         $this->usuario = $this->request->getPost()['usuario'];
         $this->sobrenome = $this->request->getPost()['sobrenome'];
         $this->email = $this->request->getPost()['email'];
         $this->rm = $this->request->getPost()['rm'];
         $this->dtnascimento = $this->request->getPost()['dtnascimento'];
         $this->senha = $this->request->getPost()['senha'];
    }

    public function cadastroUsuario()
    {
        $this->recebeDadosCadastro();

        $db      = \Config\Database::connect();
        $builder = $db->table('rm');
        $builder->select('RM');
        $builder->where('Ativo', 0);
        $query = $builder->get()->getResultArray();

        if ($query == true) 
        {
            return redirect()->to('usuario/registraUsuario?UsuarioInativo=s');
            exit;
        }

        $builder = $db->table('rm');
        $builder->select('Email');
        $builder->where('Email', $this->email);
        $builder->where('Ativo', 1);
        $query = $builder->get()->getResultArray();

        if ($query == false)
        {
            return redirect()->to('usuario/registraUsuario?Email-Invalido=s');
            exit; 
        } 

        $builder = $db->table('rm');
        $builder->select('Email');
        $builder->where('Email', $this->email);
        $builder->where('Ativo', 1);
        $query = $builder->get()->getResultArray();

        if ($query == false)
        {
            return redirect()->to('usuario/registraUsuario?RM-Invalido=s');
            exit;
        }

        $builder = $db->table('usuario');
        $builder->select('RM');
        $builder->where('RM', $this->rm);
        $query = $builder->get()->getResultArray();

        if ($query == true)
        {
            return redirect()->to('usuario/registraUsuario?RM-JaRegistrado=s'); 
            exit;
        } else {
            $db = new \App\Models\UsuarioModel();

            $this->primaryKey = 'id';

            $data = [
                'Nome' => $this->usuario,
                'Sobrenome' => $this->sobrenome,
                'DataNascimento' => $this->dtnascimento,
                'Email' => $this->email,
                'Senha' => md5($this->senha),
                'Foto' => '',
                'RM' => $this->rm,
                'Nivel' => 1,
                'Ativo' => 1,
            ];	

            $db->save($data);
            return view('includes/head') . view('login/sucesso'); 
        }
    }

    //============================================================================
    
    public function esqueceuSenha()
    {
        return view('login/esqueceu-senha');
    }
}
