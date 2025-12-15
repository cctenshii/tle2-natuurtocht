<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    //

    public function showQuiz(int $id)
    {
        $tableData = DB::table('quiz')
            ->where('card_id', '=', $id)
            ->get()
            ->first();
        return view("quiz")->with('data', $tableData)->with('idCard', $id);
    }


}
