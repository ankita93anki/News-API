<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preference;
use App\Models\Article;
class PreferenceController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->preferences);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categories' => 'nullable|array',
            'sources' => 'nullable|array',
            'authors' => 'nullable|array',
        ]);

        $preference = Preference::updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return response()->json($preference, 200);
    }

    public function feed(Request $request)
    {
        $preferences = $request->user()->preferences;

        $query = Article::query();

        if (!empty($preferences->categories)) {
            $query->whereIn('category', $preferences->categories);
        }

        return response()->json($query->paginate(10));
    }

}