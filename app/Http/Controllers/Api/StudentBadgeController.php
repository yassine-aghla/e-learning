<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentBadgeController extends Controller
{
    public function index($studentId)
    {
        $student = User::findOrFail($studentId);
        $badges = $student->badges()->get();

        return response()->json($badges);
    }
}