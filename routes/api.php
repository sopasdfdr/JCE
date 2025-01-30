<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\FormulaController;


//Funções Produtos
Route::get('/produtos', [ProdutoController::class, 'getAllProducts']);
Route::post('/adicionarProdutos', [ProdutoController::class, 'addProduct']);
Route::post('/editarProdutos/{id}', [ProdutoController::class, 'editProduct']);
Route::delete('/produtos/{id}', [ProdutoController::class, 'deleteProduct']);

//Funções Fórmulas
Route::get('/formulas', [FormulaController::class, 'getAllFormulas']);
Route::post('/adicionarFormula', [FormulaController::class, 'addFormula']);
Route::get('formulas/{id}/details', [FormulaController::class, 'show']);
Route::put('/formulas/{id}', [FormulaController::class, 'updateFormula']);
Route::delete('/formulas/{id}', [FormulaController::class, 'deleteFormula']);
Route::get('/formulas/used/{productId}', [FormulaController::class, 'getFormulasUsingProduct']);

