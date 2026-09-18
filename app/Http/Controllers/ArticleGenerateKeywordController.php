<?php

namespace App\Http\Controllers;

use App\Models\ArticleGenerateKeyword;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleGenerateKeywordController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->merge([
            'keyword' => trim((string) $request->input('keyword')),
            'article_prompt' => trim((string) $request->input('article_prompt')),
            'image_prompt' => trim((string) $request->input('image_prompt')),
        ]);

        $validated = $request->validate([
            'keyword' => ['required', 'string', 'max:255'],
            'article_prompt' => ['required', 'string'],
            'image_prompt' => ['required', 'string'],
        ]);

        if (ArticleGenerateKeyword::query()->exists()) {
            return response()->json([
                'message' => 'Kata kunci sudah tersedia. Silakan edit atau hapus data yang ada.',
            ], 409);
        }

        try {
            $keyword = ArticleGenerateKeyword::query()->create([
                'keyword' => trim($validated['keyword']),
                'article_prompt' => $validated['article_prompt'],
                'image_prompt' => $validated['image_prompt'],
                'singleton' => 1,
            ]);
        } catch (QueryException) {
            return response()->json([
                'message' => 'Kata kunci sudah tersedia. Silakan edit atau hapus data yang ada.',
            ], 409);
        }

        return response()->json([
            'message' => 'Kata kunci berhasil ditambahkan.',
            'keyword' => $keyword,
        ], 201);
    }

    public function update(Request $request, ArticleGenerateKeyword $articleGenerateKeyword): JsonResponse
    {
        $request->merge([
            'keyword' => trim((string) $request->input('keyword')),
            'article_prompt' => trim((string) $request->input('article_prompt')),
            'image_prompt' => trim((string) $request->input('image_prompt')),
        ]);

        $validated = $request->validate([
            'keyword' => ['required', 'string', 'max:255'],
            'article_prompt' => ['required', 'string'],
            'image_prompt' => ['required', 'string'],
        ]);

        $articleGenerateKeyword->update([
            'keyword' => trim($validated['keyword']),
            'article_prompt' => $validated['article_prompt'],
            'image_prompt' => $validated['image_prompt'],
        ]);

        return response()->json([
            'message' => 'Kata kunci berhasil diperbarui.',
            'keyword' => $articleGenerateKeyword->fresh(),
        ]);
    }

    public function destroy(ArticleGenerateKeyword $articleGenerateKeyword): JsonResponse
    {
        $articleGenerateKeyword->delete();

        return response()->json([
            'message' => 'Kata kunci berhasil dihapus.',
        ]);
    }
}
