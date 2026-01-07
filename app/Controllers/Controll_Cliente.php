<?php

namespace App\Controllers;
use App\Models\ClienteModel;
use App\Controllers\BaseController;

class ClienteControll extends BaseController{

    private ClienteModel $ModelCliente;

    public function __construct()
    {
        $this->ModelCliente = new ClienteModel;
    }

    public function criar()
    {
        $create = $this->request->getPost(
        'nome', 
        'nascimento', 
        'cpf',
        'rg',
        'endereco',
        'email',
        'telefone',
        'telefone2'
        );

        $create['senha'] = password_hash($this->request->getPost('senha'),PASSWORD_DEFAULT);

        if($this->ModelCliente->insert($create) === false)
        {
            return redirect()->back()->with('erro', 'Faltou campo a ser preenchido')->withInput();
        }

        session()->set([
        'id'    => $usuario['id'],
        'email' => $usuario['email'],
        'nome'  => $usuario['nome'],
        'cpf'   => $usuario['cpf'],
        'login' => true
        ]);

        return redirect()->back();
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');
        
        $usuario = $this->ModelCliente->where('email', $email)->first();

        if(!$usuario)
        {
            return redirect()->back()->with('erro', 'Usuario não existe.')->withInput();
        }

        if(!password_verify($senha, $usuario['senha']))
        {
            return redirect()->back()->with('erro', 'Senha incorreta.')->withInput();
        }

        session()->set([
            'id' => $usuario['id'],
            'email' => $usuario['email'],
            'nome'  => $usuario['nome'],
            'cpf'   => $usuario['cpf'],
            'rg'   => $usuario['rg'],
            'login' => true
        ]);

        return redirect()->back();
    }

    public function editar()
    {
        $email = $this->request->getPost('email');
        $senhaAtual = $this->request->getPost('senha');
        $usuario = $this->ModelCliente
            ->where('email', $email) 
            ->first();

        if(!$usuario){
            return redirect()->back()->with('erro', 'Usuário não encontrado.')->withInput();
        } else if(!$usuario === null) {
            return redirect()->back()->with('erro', 'Digite o email do usuario.')->withInput();
        }

        $senhaNova = $this->request->getPost('senhaNova');
        $senhaNova = password_hash($senhaNova, PASSWORD_DEFAULT);

        if(empty($senhaNova)) {
            return redirect()->back()->with('erro', 'Digite a nova senha.')->withInput();
        }

        $this->ModelCliente->update($usuario['id'], ['senha' => $senhaNova]);

        session()->set([
            'id'    => $usuario['id'],
            'email' => $usuario['email'],
            'nome'  => $usuario['nome'],
            'cpf'   => $usuario['cpf'],
            'rg'    => $usuario['rg'],
            'login' => true
        ]);

        return redirect()->back();
    }

    public function delete()
    {
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');
        
        $cliente = $this->ModelCliente
            ->where('email', $email)
            ->where('senha', $senha)
            ->first();

        if (!$cliente)
        {
            return redirect()->back()->with('erro', 'Os dados não correspondem.')->withInput();
        }

        $this->ModelCliente->where('email', $cliente)->delete();

        return redirect()->back();
    }

    public function listar()
    {
        $this->ClienteModel->select('id', 'nome', 'email', 'telefone', 'telefone2')->FindALL();
        return view('cliente/listar', ['clientes' => $clientes]);
    }
}