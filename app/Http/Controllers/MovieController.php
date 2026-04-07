<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

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
     * Return a specific movie
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
        // Validation - if this fails, it automatically returns a 422 JSON response
        $this->validate($request, [
            'title' => 'required|string|max:100',
            'genre' => 'required|string|max:50',
        ]);

        // Explicitly create using input to ensure data is caught
        $movie = Movie::create([
            'title' => $request->input('title'),
            'genre' => $request->input('genre'),
        ]);

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

        $this->validate($request, [
            'title' => 'string|max:100',
            'genre' => 'string|max:50',
        ]);

        // Capture only the allowed fields from the request
        $data = $request->only(['title', 'genre']);
        
        $movie->fill($data);

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