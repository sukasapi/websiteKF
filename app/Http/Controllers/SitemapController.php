<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('home'))->setPriority(1.0))
            ->add(Url::create(route('about')))
            ->add(Url::create(route('services')))
            ->add(Url::create(route('portfolio.index')))
            ->add(Url::create(route('blog.index')))
            ->add(Url::create(route('contact.index')));

        foreach (Project::all() as $project) {
            $sitemap->add(Url::create(route('portfolio.show', $project)));
        }

        foreach (Post::published()->get() as $post) {
            $sitemap->add(
                Url::create(route('blog.show', $post))
                    ->setLastModificationDate($post->updated_at)
            );
        }

        return $sitemap->toResponse(request());
    }
}
