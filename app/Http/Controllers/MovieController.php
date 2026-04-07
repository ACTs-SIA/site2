<?php

namespace App\Http\Controllers;

use App\Models\Movie; // Correctly Capitalized
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MovieController extends Controller
{ 
    /**
     * Return all movies
     */
    public function index()
    {
        return response()->json(Movie::all(), 200);
    }

    /**
     * Show a single movie. 
     * If not found, returns 404 for Site 1's validation.
     */
    public function show($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return response()->json($movie, 200);
    }

    /**
     * Create a new movie
     */
    public function add(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:100',
            'genre' => 'required|string|max:50',
        ];

        $this->validate($request, $rules);

        $movie = Movie::create($request->all());

        return response()->json($movie, 201);
    }

    /**
     * Update movie details
     */
    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $rules = [
            'title' => 'string|max:100',
            'genre' => 'string|max:50',
        ];

        $this->validate($request, $rules);

        $movie->fill($request->all());

        if ($movie->isClean()) {
            return response()->json(['message' => 'At least one value must change'], 422);
        }

        $movie->save();
        return response()->json($movie, 200);
    }

    /**
     * Delete a movie
     */
    public function delete($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $movie->delete();
        return response()->json(['message' => 'Movie deleted successfully'], 200);
    }
}