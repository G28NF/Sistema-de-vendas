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
        $produto = $this->request->getPost(['codigo', 'nome', 'preco', 'estoque', 'descricao']);

        foreach($produto as $campo)
        {
            if(empty($campo)) 
            {
                return redirect()->back()->with('erro', 'Preencha todos os campos.')->withInput();
            }
        }

        $verificaProduto = $this->ProdutoModel
        ->where('codigo', $produto['codigo'])
        ->where('nome', $produto['nome'])
        ->first();

        if($verificaProduto ['codigo'] || $verificaProduto ['nome'])
        {
            return redirect()->back()->with('erro', 'Preencha todos os campos com os dados do novo produto.');
        }
        
        $this->ProdutoModel->insert([
            'codigo' => $produto['codigo'],
            'nome' => $produto ['nome'],
            'preco' => $produto['preco'],
            'estoque_' => $produto['estoque'],
            'descricao' => $produto['descricao'] 
        ]);

        return redirect()->back()->with('sucesso', 'Produto cadastrado com sucesso.');
    }

    public function editar()
    {
        $produtoAntigo = $this->request->getPost(['id']);

        $verificaProduto = $this->ProdutoModel
            ->where('id', $produtoAntigo['id'])
            ->first();

        if(!$verificaProduto)
        {
           return redirect()->back()->with('erro', 'Produto não encontrado.');
        }

        $produtoNovo = $this->request->getPost(['novo_codigo', 'novo_nome', 'novo_preco', 'novo_descricao']);

        $this->ProdutoModel->update($verificaProduto['id'], [
            'codigo' => $produtoNovo['novo_codigo'],
            'nome' => $produtoNovo['novo_nome'],
            'preco' => $produtoNovo['novo_preco'],
            'descricao' => $produtoNovo['novo_descricao']
        ]);

        return redirect()->back()->with('sucesso', 'Produto atualizado com sucesso.');
    }

    public function deletar()
    {
        $produto = $this->request->getPost('id');

        if(empty($produto)) 
        {
            return redirect()->back()->with('erro', 'Selecione um produto.');
        }

        $verificaProduto = $this->ProdutoModel->where('id', $produto)->first();

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

    public function estoqueAtualizar()
    {
        $estoque       = $this->request->getPost('estoque');
        $idProduto     = $this->request->getPost('produto_id'); 

        if (!empty($idProduto) && !empty($estoque)) {
            $this->ProdutoModel->update($idProduto, ['estoque' => $estoque]);
            return redirect()->back()->with('sucesso', 'Estoque atualizado com sucesso.');
        }

        $codigoProduto = $this->request->getPost('codigo');
        $nomeProduto   = $this->request->getPost('nome');

        if ((empty($codigoProduto) && empty($nomeProduto))) {
            return redirect()->back()->with('erro', 'Preencha todos os campos.');
        }

        $produtos = $this->ProdutoModel
            ->like('nome', $nomeProduto)
            ->orWhere('codigo', $codigoProduto)
            ->findAll();

        if (empty($produtos)) {
            return redirect()->back()->with('erro', 'Produto não encontrado.');
        }

        return view('produtos/selecao_produto', ['produtos' => $produtos, 'estoque' => $estoque]);
    }
}