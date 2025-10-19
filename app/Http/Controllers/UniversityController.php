<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', '');
        
        return University::query()
            ->when($type, function ($q) use ($type) {
                $q->where('type', $type);
            })
            ->when($query, function ($q) use ($query) {
                $q->where(function($subQuery) use ($query) {
                    $subQuery->where('name', 'like', "%{$query}%")
                             ->orWhere('name_kana', 'like', "%{$query}%");
                });
            })
            ->orderBy('name')
            ->get(['id', 'name', 'name_kana', 'type']);
    }
}