<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ProdutoModel;

class ControllProduto extends baseController
{
    protected ProdutoModel $ProdutoModel;

    public function __construct()
    {
        $this->ProdutoModel = new ProdutoModel();
    }

    public function cadastrar()
    {
        $produto = $this->request->getPost(['codigo_produto', 'nome_produto', 'preco_produto']);

        foreach($produto as $campo)
        {
            if(empty($campo)) 
            {
                return redirect()->back()->with('erro', 'Preencha todos os campos.')->withInput();
            }
        }

        $verificaProduto = $this->ProdutoModel
        ->where('codigo_produto', $produto['codigo_produto'])
        ->where('nome_produto', $produto['nome_produto'])
        ->first();

        if($verificaProduto ['codigo_produto'] || $verificaProduto ['nome_produto'])
        {
            return redirect()->back()->with('erro', 'Preencha todos os campos com os dados do novo produto.');
        }
        
        $this->ProdutoModel->insert([
            'codigo_produto' => $produto['codigo_produto'],
            'nome_produto' => $produto ['nome_produto'],
            'preco_produto' => $produto['preco_produto']
        ]);

        return redirect()->back()->with('sucesso', 'Produto cadastrado com sucesso.');
    }

    public function editar()
    {
        $produtoAntigo = $this->request->getPost(['codigo_produto', 'nome_produto', 'preco_produto']);

        foreach($produtoAntigo as $campo)
        {
            if(empty($campo)) 
            {
                return redirect()->back()->with('erro', 'Preencha todos os campos.');
            }
        }

        $verificaProduto = $this->ProdutoModel
        ->where('codigo_produto', $produtoAntigo['codigo_produto'])
        ->where('nome_produto', $produtoAntigo['nome_produto'])
        ->first();

        if(!$verificaProduto)
        {
           return redirect()->back()->with('erro', 'Produto não encontrado.');
        }

        $produtoNovo = $this->request->getPost(['novo_codigo_produto', 'novo_nome_produto', 'novo_preco_produto']);

        foreach($produtoNovo as $campo)
        {
            if(empty($campo)) 
            {
                return redirect()->back()->with('erro', 'Preencha todos os campos.');
            }
        }

        $this->ProdutoModel->update($verificaProduto['id'], [
            'codigo_produto' => $produtoNovo['novo_codigo_produto'],
            'nome_produto' => $produtoNovo['novo_nome_produto'],
            'preco_produto' => $produtoNovo['novo_preco_produto']
        ]);

        return redirect()->back()->with('sucesso', 'Produto atualizado com sucesso.');
    }

    public function deletar()
    {
        $produto = $this->request->getPost('codigo_produto');

        if(empty($produto)) 
        {
            return redirect()->back()->with('erro', 'Preencha o código do produto.');
        }

        $verificaProduto = $this->ProdutoModel->where('codigo_produto', $produto)->first();

        if(!$verificaProduto)
        {
            return redirect()->back()->with('erro', 'Produto não encontrado.');
        }

        $this->ProdutoModel->delete($verificaProduto['id']);

        return redirect()->back()->with('sucesso', 'Produto deletado com sucesso.');
    }

    public function listar()
    {
        $this->ProdutoModel->FindALL();
        return view('produto/listar', ['produtos' => $produtos]);
    }
}