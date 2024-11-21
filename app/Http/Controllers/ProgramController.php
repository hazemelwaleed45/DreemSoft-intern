<?php

namespace App\Http\Controllers;

use App\Models\Programm;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function getProgramms(Request $request)
    {
        $programms = Programm::paginate(10); // Paginate results by 10 programs per page
        return response()->json($programms);
    }

    public function getProgrammDetails($id)
    {
        $program = Programm::with(['chapters.sessions.quizzes.questions.answers'])->findOrFail($id);
        return response()->json([
            'program' => $program,
            'sessions' => $program->sessions,
        ]);
    }
}
