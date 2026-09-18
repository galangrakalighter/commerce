<?php

namespace App\Http\Controllers;

use App\Models\ArticleGenerateKeywordPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArticleGenerateKeywordPlanController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $plan = ArticleGenerateKeywordPlan::query()->create($this->validatedData($request));

        return response()->json([
            'message' => 'Keyword planning berhasil ditambahkan.',
            'plan' => $plan,
        ], 201);
    }

    public function update(Request $request, ArticleGenerateKeywordPlan $articleGenerateKeywordPlan): JsonResponse
    {
        $articleGenerateKeywordPlan->update($this->validatedData($request, $articleGenerateKeywordPlan));

        return response()->json([
            'message' => 'Keyword planning berhasil diperbarui.',
            'plan' => $articleGenerateKeywordPlan->fresh(),
        ]);
    }

    public function destroy(ArticleGenerateKeywordPlan $articleGenerateKeywordPlan): JsonResponse
    {
        $articleGenerateKeywordPlan->delete();

        return response()->json([
            'message' => 'Keyword planning berhasil dihapus.',
        ]);
    }

    private function validatedData(Request $request, ?ArticleGenerateKeywordPlan $plan = null): array
    {
        $request->merge([
            'keyword' => trim((string) $request->input('keyword')),
            'article_prompt' => trim((string) $request->input('article_prompt')),
            'image_prompt' => trim((string) $request->input('image_prompt')),
        ]);

        return $request->validate([
            'planned_date' => [
                'required',
                'date_format:Y-m-d',
                Rule::unique('article_generate_keyword_plans', 'planned_date')->ignore($plan?->id),
            ],
            'keyword' => ['required', 'string', 'max:255'],
            'article_prompt' => ['required', 'string'],
            'image_prompt' => ['required', 'string'],
        ]);
    }
}
