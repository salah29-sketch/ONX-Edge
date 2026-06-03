<?php

namespace App\Console\Commands;

use App\Models\Content\PortfolioItem;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature   = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml';

    public function handle(): void
    {
        $sitemap = Sitemap::create();

        // صفحات ثابتة
        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('weekly'));
        $sitemap->add(Url::create('/portfolio')->setPriority(0.9)->setChangeFrequency('weekly'));
        $sitemap->add(Url::create('/services')->setPriority(0.8)->setChangeFrequency('monthly'));
        $sitemap->add(Url::create('/booking')->setPriority(0.7)->setChangeFrequency('monthly'));

        // أعمال Portfolio
        PortfolioItem::where('is_active', true)
            ->orderByDesc('id')
            ->each(function ($item) use ($sitemap) {
                $sitemap->add(
                    Url::create('/portfolio')
                        ->setPriority(0.6)
                        ->setChangeFrequency('monthly')
                        ->setLastModificationDate($item->updated_at)
                );
            });

        $sitemap->writeToFile(base_path('../public_html/sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
