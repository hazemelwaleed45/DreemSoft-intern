<?php

namespace App\Http\Controllers;

use App\Models\Programm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProgramController extends Controller
{
    public function getProgramms(Request $request)
    {
        $page = $request->get('page', 1); 
        $cacheKey = "programms_page_{$page}"; 
    
        $programms = Cache::remember($cacheKey, 3600, function () { 
            return Programm::paginate(10); 
        });
    
        return response()->json($programms);
    }

    
public function getProgrammDetails($id)
{
    $cacheKey = "programm_details_{$id}";

    $programDetails = Cache::remember($cacheKey, 3600, function () use ($id) { 
        $program = Programm::with(['chapters.sessions.quizzes.questions.answers'])->findOrFail($id);

        return [
            'program' => $program,
            'sessions' => $program->chapters->flatMap(function ($chapter) {
                return $chapter->sessions;
            }),
        ];
    });

    return response()->json($programDetails);
}
}
