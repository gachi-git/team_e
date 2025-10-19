<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        return University::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('name_kana', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->get(['id', 'name', 'name_kana', 'type']);
    }
}