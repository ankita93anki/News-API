<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
class ArticleController extends Controller
{
        public function index()
    {
        return response()->json(Article::paginate(10));
    }

   public function search(Request $request)
    {
        try {
            // Initialize the query
            $query = Article::query();

            // Check if the 'keyword' parameter is provided
            if ($request->filled('keyword')) {
                $query->where('title', 'like', '%' . $request->input('keyword') . '%');
            }

            // Check if the 'category' parameter is provided
            if ($request->filled('category')) {
                $query->where('category', $request->input('category'));
            }

            // Execute the query and paginate the results
            $articles = $query->paginate(10);

            // Return the paginated results
            return response()->json($articles, 200);
        } catch (\Exception $e) {
            // Log the error and return an error response
            Log::error('Error in search method: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while searching for articles',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}