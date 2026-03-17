<?php

namespace App\Http\Controllers;

use App\Models\Submission;

class SubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::latest()->get();

        return view('submissions.index', compact('submissions'));
    }
}
