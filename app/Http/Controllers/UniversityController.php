<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class UniversityController extends Controller
{
    /**
     * GET /api/universities?q=九州&limit=20
     * 大学タグ（tags.kind='university'）を検索して返す（オートコンプリート用）。
     * 返却: [{ id, label }]
     */
    public function search(Request $request)
    {
        $q     = trim((string) $request->query('q', ''));
        $limit = (int) $request->query('limit', 20);
        $limit = max(1, min($limit, 50)); // 1..50

        $query = Tag::query()
            ->where('kind', 'university');

        if ($q !== '') {
            // 正規化キー（全半角・空白差異に強い）でもマッチ
            $norm = $this->normalize($q);

            $query->where(function ($qq) use ($q, $norm) {
                $qq->where('label', 'like', '%' . $q . '%');
                if ($norm !== '') {
                    $qq->orWhere('key', 'like', '%' . $norm . '%');
                }
            });
        } else {
            // 何も入力されていないときは上位から返す（必要なければ削ってOK）
            $query->orderBy('label');
        }

        $rows = $query
            ->orderBy('label')
            ->limit($limit)
            ->get([
                'id',      // ← フロントは row.id を期待
                'label',
            ]);

        return response()->json($rows);
    }

    /** 入力文字列を tags.key と同じ規則で正規化（全半角/空白除去/小文字化） */
    private function normalize(string $s): string
    {
        $s = trim($s);
        if ($s === '') return '';
        $s = mb_convert_kana($s, 'asKV', 'UTF-8');            // 全半角/カナ統一
        $s = preg_replace('/[\s　,、，．。]+/u', '', $s) ?? ''; // 空白・読点など除去
        $s = mb_strtolower($s, 'UTF-8');
        return $s;
    }
}
