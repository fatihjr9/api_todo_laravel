<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $data = Checklist::latest()->get();
            return response()->json([
                "data" => $data,
            ]);
        } else {
            return response()->json(
                [
                    "message" => "Unauthorized",
                ],
                401
            );
        }
    }
    public function addChecklist(Request $request)
    {
        if (Auth::check()) {
            $data = $request->validate([
                "name" => ["required", "string", "max:255"],
            ]);
            Checklist::create($data);
            return response()->json([
                "message" => "Checklist Added",
            ]);
        } else {
            return response()->json(
                [
                    "message" => "Failed added checklist",
                ],
                401
            );
        }
    }

    public function rmChecklist($id)
    {
        if (Auth::check()) {
            $checklist = Checklist::find($id);

            if ($checklist) {
                $checklist->delete();

                return response()->json([
                    "message" => "Checklist deleted successfully",
                ]);
            } else {
                return response()->json(
                    [
                        "message" => "Checklist not found",
                    ],
                    404
                );
            }
        } else {
            return response()->json(
                [
                    "message" => "Unauthorized",
                ],
                401
            );
        }
    }
}
