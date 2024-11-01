<?php

namespace App\Services;

use App\Models\Studio\Studio;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Sitemap;

class SitemapService
{
    public static function build(): void
    {
        if(config('app.env') === 'production'){
            Log::info('Sitemap build start');
    
            $path = public_path('sitemap.xml');
    
            $studios_slug = Studio::where('is_complete', true)->where('is_published', true)->pluck('slug')->toArray();
    
            $urls = [];
            if($studios_slug){
                foreach ($studios_slug as $slug) {
                    $urls[] = route('studio.show', $slug);
                }
            }
    
            Sitemap::create()
                ->add(config('app.url'))
                ->add(route('login'))
                ->add(route('register.studio.starter.step_1'))
                ->add(route('privacy'))
                ->add(route('tos'))
                // ->add(route('studio.index'))
                ->add($urls)
                ->writeToFile($path);
            
            echo('Sitemap creata!');
    
            Log::info('Sitemap build end');
        }
    }
}