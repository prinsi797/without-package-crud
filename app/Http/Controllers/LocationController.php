<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function index()
    {
        return view('location.index');
    }

    public function search(Request $request)
    {
        $apiKey = 'pk.25c93e51205acf9625540a226c7b0601';
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        $url = "https://api.locationiq.com/v1/autocomplete.php?key={$apiKey}&q=" . urlencode($query) . "&limit=5";

        $response = Http::get($url);

        if ($response->successful()) {
            $results = $response->json();
            return response()->json($results);
        }

        return response()->json(['error' => 'Unable to fetch locations'], 500);
    }
}
