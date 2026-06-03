<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Content\PortfolioItem;
use App\Models\Service\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $categories = Cache::remember('portfolio_categories', 300, fn () =>
            Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug', 'icon'])
        );

        // ── Hero
        $heroItem = PortfolioItem::where('is_active', true)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('media_type', 'image')->whereNotNull('image_path');
                })->orWhere(function ($q2) {
                    $q2->where('media_type', 'youtube')->whereNotNull('youtube_video_id');
                });
            })
            ->inRandomOrder()
            ->first();

        // ── Featured
        $featuredItems = Cache::remember('portfolio_featured', 300, fn () =>
            PortfolioItem::where('is_active', true)
                ->where('is_featured', true)
                ->where('is_reel', false)
                ->with('categoryRelation:id,name,slug')
                ->orderBy('sort_order')
                ->limit(3)
                ->get()
        );
        if ($featuredItems->isEmpty()) {
            $featuredItems = Cache::remember('portfolio_featured_fallback', 300, fn () =>
                PortfolioItem::where('is_active', true)
                    ->where('is_reel', false)
                    ->with('categoryRelation:id,name,slug')
                    ->orderByDesc('id')
                    ->limit(3)
                    ->get()
            );
        }

        // ── Reels
        $reelItems = Cache::remember('portfolio_reels', 300, fn () =>
            PortfolioItem::where('is_active', true)
                ->where('is_reel', true)
                ->with('categoryRelation:id,name,slug,icon')
                ->orderBy('sort_order')
                ->get()
        );

        // ── All items
        $items = Cache::remember('portfolio_items', 300, fn () =>
            PortfolioItem::where('is_active', true)
                ->where('is_reel', false)
                ->with('categoryRelation:id,name,slug')
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get()
        );

        return view('front.portfolio', [
            'items'         => $items,
            'featuredItems' => $featuredItems,
            'heroItem'      => $heroItem,
            'reelItems'     => $reelItems,
            'categories'    => $categories,
        ]);
    }
}
