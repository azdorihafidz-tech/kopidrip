<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Services\PageContentSanitizer;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(): View
    {
        return view('pages.promotions.index', [
            'promotions' => Promotion::available()->paginate(9),
        ]);
    }

    public function show(string $slug, PageContentSanitizer $sanitizer): View
    {
        $promotion = Promotion::available()->where('slug', $slug)->firstOrFail();

        return view('pages.promotions.show', [
            'promotion' => $promotion,
            'safeDescription' => $sanitizer->sanitize($promotion->description),
        ]);
    }
}
