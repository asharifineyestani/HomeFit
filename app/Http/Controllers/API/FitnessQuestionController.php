<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class FitnessQuestionController extends Controller
{
    public function index(): JsonResponse
    {
        $path = database_path('data/fitness_questions.json');

        if (!file_exists($path)) {
            return response()->json([
                'message' => 'Questions file not found.'
            ], 404);
        }

        $json = file_get_contents($path);
        $questions = json_decode($json, true);

        if (!is_array($questions)) {
            return response()->json([
                'message' => 'Invalid JSON format.'
            ], 500);
        }

        return response()->json([
            'data' => $questions
        ]);
    }
}
