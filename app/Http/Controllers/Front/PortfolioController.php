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
        $categories = Cache::remember('portfolio_categories', 300, function () {
            return Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug', 'icon']);
        });

        // ── Hero
        $heroItem = PortfolioItem::where('is_active', true)
            ->where('media_type', 'image')
            ->whereNotNull('image_path')
            ->inRandomOrder()
            ->first();
        $heroImages = PortfolioItem::where('is_active', true)
            ->where('media_type', 'image')
            ->whereNotNull('image_path')
            ->inRandomOrder()
            ->limit(6)
            ->pluck('image_path');

        // ── Featured
        $featuredItems = Cache::remember('portfolio_featured', 300, function () {
            return PortfolioItem::where('is_active', true)
                ->where('is_featured', true)
                ->where('is_reel', false)
                ->with('categoryRelation:id,name,slug')
                ->select('id', 'title', 'caption', 'category_id', 'media_type', 'image_path', 'youtube_video_id', 'is_featured', 'sort_order')
                ->orderBy('sort_order')
                ->limit(3)
                ->get();
        });
        if ($featuredItems->isEmpty()) {
            $featuredItems = Cache::remember('portfolio_featured_fallback', 300, function () {
                return PortfolioItem::where('is_active', true)
                    ->where('is_reel', false)
                    ->with('categoryRelation:id,name,slug')
                    ->select('id', 'title', 'caption', 'category_id', 'media_type', 'image_path', 'youtube_video_id', 'is_featured', 'sort_order')
                    ->orderByDesc('id')
                    ->limit(3)
                    ->get();
            });
        }

        // ── Reels
        $reelItems = Cache::remember('portfolio_reels', 300, function () {
            return PortfolioItem::where('is_active', true)
                ->where('is_reel', true)
                ->with('categoryRelation:id,name,slug,icon')
                ->select('id', 'title', 'caption', 'category_id', 'media_type', 'image_path', 'youtube_video_id', 'reel_source', 'reel_url', 'video_path', 'sort_order')
                ->orderBy('sort_order')
                ->get();
        });

        // ── All items
        $items = Cache::remember('portfolio_items', 300, function () {
            return PortfolioItem::where('is_active', true)
                ->where('is_reel', false)
                ->with('categoryRelation:id,name,slug')
                ->select('id', 'title', 'caption', 'category_id', 'media_type', 'image_path', 'youtube_video_id', 'is_featured', 'sort_order')
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get();
        });

        return view('front.portfolio', [
            'items'         => $items,
            'featuredItems' => $featuredItems,
            'heroItem'      => $heroItem,
            'heroImages'    => $heroImages,
            'reelItems'     => $reelItems,
            'categories'    => $categories,
        ]);
    }
}