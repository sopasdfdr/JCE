<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto; // Import the Produto model

class ProdutoController extends Controller
{
    // Method to get all products
    public function getAllProducts()
    {
        // Fetch all products from the 'produtos' table
        $products = Produto::all();
        // Return the products as a JSON response
        return response()->json($products);
    }

    // Method to add a new product
    public function addProduct(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
            'tipo' => 'required|integer', //tipo is an integer
        ]);
        
        // Create a new product and save it to the database
        $product = Produto::create([
            'prd_nome' => $validatedData['nome'],
            'prd_codigo' => $validatedData['codigo'],
            'prd_tipo' => $validatedData['tipo'],
        ]);

        $product->save();

        // Return the created product as a JSON response
        return response()->json($product, 201);
    }

    public function editProduct(Request $request, $id)
    {
        // Validate the request
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
            'tipo' => 'required|integer|in:0,1,2', // Ensure tipo is one of the expected values
        ]);

        // Find the product by ID
        $produto = Produto::where('prd_id', $id)->first();

        if (!$produto) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Update product details
        $produto->prd_nome = $validatedData['nome'];
        $produto->prd_codigo = $validatedData['codigo'];
        $produto->prd_tipo = $validatedData['tipo'];

        // Save the changes
        $produto->save();

        return response()->json(['message' => 'Product updated successfully', 'product' => $produto], 200);
    }

    public function deleteProduct($id)
    {
        $produto = Produto::where('prd_id', $id)->first();

        if (!$produto) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $produto->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }


}
