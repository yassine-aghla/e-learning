<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function store(Request $request)
    {
        
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'image_url' => 'required|url',
        //     'type' => 'required|in:student,mentor',
        //     'conditions' => 'nullable|array'
        // ]);

        $badge = Badge::create($request->all());
        // dd($badge);

        return response()->json($badge, 201);
    }

    public function update(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);

        // $request->validate([
        //     'name' => 'sometimes|string|max:255',
        //     'description' => 'sometimes|string',
        //     'image_url' => 'sometimes|url',
        //     'type' => 'sometimes|in:student,mentor',
        //     'conditions' => 'nullable|array'
        // ]);

        $badge->update($request->all());

        return response()->json($badge);
    }

    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();

        return response()->json(null, 204);
    }
}