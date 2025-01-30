<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formula; // Import the Produto model
use App\Models\Formulacoes; // Import the Produto model
use DB;

class FormulaController extends Controller
{
     // Method to get all formulas
    public function getAllFormulas()
    {
        // Fetch all products from the 'produtos' table
        $formulas = Formula::all();
        // Return the products as a JSON response
        return response()->json($formulas);
    }

    // Method to add a new formula
    public function addFormula(Request $request)
{
    // Validate the incoming data
    $validatedData = $request->validate([
        'nome' => 'required|string|max:255',
        'codigo' => 'required|string|max:255',
        'products' => 'required|array|min:1', // Ensure there are products in the array
        'products.*.prd_id' => 'required|integer|exists:produtos,prd_id', // Ensure the product exists in the products table
        'products.*.quantity' => 'required|numeric|min:1',
        'products.*.priority' => 'required|integer|min:1',
    ]);

    // Calculate the total quantity (fml_qtde) and number of products (fml_nrprodutos)
    $totalQuantity = array_sum(array_column($validatedData['products'], 'quantity'));
    $numberOfProducts = count($validatedData['products']);

    // Get the current timestamp for fml_datacriacao and fml_dataalteracao
    $currentTimestamp = now()->timestamp; // This gives the current time in seconds

    // Create a new formula in the 'formulas' table
    $formula = Formula::create([
        'fml_nome' => $validatedData['nome'],
        'fml_codigo' => $validatedData['codigo'],
        'fml_qtde' => $totalQuantity, // Total quantity of all products
        'fml_nrprodutos' => $numberOfProducts, // Number of products
        'fml_datacriacao' => $currentTimestamp, // Creation date
        'fml_dataalteracao' => $currentTimestamp, // Last updated date
    ]);

    // Loop through each selected product and save it in the 'formulacoes' table
    foreach ($validatedData['products'] as $productData) {
        Formulacoes::create([
            'fml_id' => $formula->fml_id, // The ID of the newly created formula
            'prd_id' => $productData['prd_id'], // The product ID
            'fmc_qtde' => $productData['quantity'], // Quantity (Kg)
            'fmc_prioridade' => $productData['priority'], // Priority
        ]);
    }

    // Return the created formula and its associated products as a JSON response
    return response()->json($formula, 201);
}

    public function show($id)
    {
        $formula = Formula::with(['formulacoes.produto'])->findOrFail($id); // Eager load formulacoes and associated produto
        return response()->json($formula);
    }



    public function deleteFormula($id)
    {
        // Find the formula by ID
        $formula = Formula::find($id);

        if (!$formula) {
            return response()->json(['message' => 'Formula not found'], 404); // Formula not found
        }

        // Delete related entries in the 'formulacoes' table
        $formula->formulacoes()->delete(); // This deletes all related formulacoes

        // Delete the formula itself
        $formula->delete();

        // Return a success response
        return response()->json(['message' => 'Formula deleted successfully'], 200);
    }

    public function updateFormula(Request $request, $id)
{
    // Validate the incoming data
    $validatedData = $request->validate([
        'fml_nome' => 'required|string|max:255',
        'fml_codigo' => 'required|string|max:255',
        'formulacoes' => 'required|array|min:1',
        'formulacoes.*.produto.prd_id' => 'required|integer|exists:produtos,prd_id',
        'formulacoes.*.produto.quantity' => 'required|numeric|min:1',
        'formulacoes.*.produto.priority' => 'nullable|integer|min:1',
    ]);

    // Begin a transaction
    DB::beginTransaction();

    try {
        // Find the existing formula
        $formula = Formula::findOrFail($id);

        // Update formula details if they have changed
        if ($formula->fml_nome !== $validatedData['fml_nome'] || $formula->fml_codigo !== $validatedData['fml_codigo']) {
            $formula->fml_nome = $validatedData['fml_nome'];
            $formula->fml_codigo = $validatedData['fml_codigo'];
        }

        // Get existing product IDs in the formula
        $existingProducts = Formulacoes::where('fml_id', $id)->pluck('prd_id')->toArray();
        
        // Prepare new product data
        $totalQuantity = 0;

        foreach ($validatedData['formulacoes'] as $formulacao) {
            $product = $formulacao['produto'];
            $prd_id = $product['prd_id'];
            $quantity = $product['quantity'];
            $priority = $product['priority']; // Priority is optional
        
            $totalQuantity += $quantity;
        
            // Check if product already exists in formula
            $existingProduct = Formulacoes::where('fml_id', $id)
                ->where('prd_id', $prd_id)
                ->first();
        
            if ($existingProduct) {
                // Product exists, update quantity and priority
                $existingProduct->update([
                    'fmc_qtde' => $quantity,
                    'fmc_prioridade' => $priority,
                ]);
            } else {
                // Product does not exist, create new product
                Formulacoes::create([
                    'fml_id' => $id,
                    'prd_id' => $prd_id,
                    'fmc_qtde' => $quantity,
                    'fmc_prioridade' => $priority,
                ]);
            }
        }
        
        // Delete products that are no longer in the validated data
        $updatedProductIds = array_map(function($formulacao) {
            return $formulacao['produto']['prd_id'];
        }, $validatedData['formulacoes']);
        Formulacoes::where('fml_id', $id)
            ->whereNotIn('prd_id', $updatedProductIds)
            ->delete();
        

        // Update formula total quantity and number of products
        $formula->fml_qtde = $totalQuantity;
        $formula->fml_nrprodutos = count($validatedData['formulacoes']);
        $formula->fml_dataalteracao = now()->timestamp; // Update last modified timestamp

        // Save changes
        $formula->save();

        // Commit the transaction
        DB::commit();

        return response()->json([
            'message' => 'Formula updated successfully',
            'formula' => $formula
        ], 200);
    } catch (\Exception $e) {
        // Rollback the transaction if anything goes wrong
        DB::rollBack();

        return response()->json([
            'message' => 'Failed to update formula',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getFormulasUsingProduct($productId)
{
    // Find formulas that use the product
    $formulas = Formula::whereHas('formulacoes', function ($query) use ($productId) {
        $query->where('prd_id', $productId);
    })->get();

    return response()->json($formulas);
}

    


    
}
