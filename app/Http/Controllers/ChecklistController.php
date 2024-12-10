<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\ChecklistItem;
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

    // Detail
    public function getAllItems($checklistId)
    {
        $items = ChecklistItem::where("checklist_id", $checklistId)->get();

        return response()->json(["data" => $items]);
    }

    public function createItem(Request $request, $checklistId)
    {
        $data = $request->validate(["name" => "required|string"]);

        $item = ChecklistItem::create([
            "checklist_id" => $checklistId,
            "name" => $data["name"],
        ]);

        return response()->json([
            "message" => "Item created successfully",
            "data" => $item,
        ]);
    }

    public function getItem($checklistId, $itemId)
    {
        $item = ChecklistItem::where("checklist_id", $checklistId)
            ->where("id", $itemId)
            ->first();

        if (!$item) {
            return response()->json(["message" => "Item not found"], 404);
        }

        return response()->json(["data" => $item]);
    }

    public function updateStatus($checklistId, $itemId)
    {
        $item = ChecklistItem::where("checklist_id", $checklistId)
            ->where("id", $itemId)
            ->first();

        if (!$item) {
            return response()->json(["message" => "Item not found"], 404);
        }

        $item->update(["status" => !$item->status]);

        return response()->json([
            "message" => "Item status updated",
            "data" => $item,
        ]);
    }

    public function deleteItem($checklistId, $itemId)
    {
        $item = ChecklistItem::where("checklist_id", $checklistId)
            ->where("id", $itemId)
            ->first();

        if (!$item) {
            return response()->json(["message" => "Item not found"], 404);
        }

        $item->delete();

        return response()->json(["message" => "Item deleted successfully"]);
    }

    public function renameItem(Request $request, $checklistId, $itemId)
    {
        $request->validate(["itemName" => "required|string"]);

        $item = ChecklistItem::where("checklist_id", $checklistId)
            ->where("id", $itemId)
            ->first();

        if (!$item) {
            return response()->json(["message" => "Item not found"], 404);
        }

        $item->update(["name" => $request->itemName]);

        return response()->json([
            "message" => "Item renamed successfully",
            "data" => $item,
        ]);
    }
}
