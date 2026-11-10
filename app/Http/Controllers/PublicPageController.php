<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\HomepageSection;
use App\Models\JournalPost;
use App\Models\MenuCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Promotion;
use App\Models\Testimonial;
use App\Models\WebsiteSetting;
use App\Services\PageContentSanitizer;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'homeSections' => HomepageSection::visible()->get()->keyBy('section_key'),
            'featuredProducts' => Product::published()->with(['images', 'primaryImage', 'category'])->where('featured', true)->orderBy('name')->limit(3)->get(),
            'journalPosts' => JournalPost::published()->latest('published_at')->limit(3)->get(),
            'galleryItems' => GalleryItem::published()->orderBy('sort_order')->limit(4)->get(),
            'featuredPromotion' => Promotion::available()->first(),
            'testimonials' => Testimonial::published()->limit(3)->get(),
            'faqs' => Faq::published()->limit(5)->get(),
        ]);
    }

    public function about(): View
    {
        return $this->renderCmsPage('about');
    }

    public function coffee(): View
    {
        return $this->renderCmsPage('coffee', ['products' => Product::published()->with(['images', 'primaryImage', 'category'])->orderBy('name')->limit(3)->get()]);
    }

    public function products(): View
    {
        return view('pages.products.index', [
            'products' => Product::published()->with(['images', 'primaryImage', 'category'])->orderBy('name')->paginate(12),
            'categories' => ProductCategory::active()->get(),
        ]);
    }

    public function product(string $slug): View
    {
        $product = Product::published()->with(['images', 'primaryImage', 'variants' => fn ($query) => $query->where('is_active', true), 'category'])
            ->where('slug', $slug)->firstOrFail();

        return view('pages.products.show', compact('product'));
    }

    public function productCategory(string $slug): View
    {
        $category = ProductCategory::active()->where('slug', $slug)->firstOrFail();

        return view('pages.products.index', [
            'category' => $category,
            'categories' => ProductCategory::active()->get(),
            'products' => $category->products()->published()->with(['images', 'primaryImage', 'category'])->orderBy('name')->paginate(12),
        ]);
    }

    public function menu(): View
    {
        $categories = MenuCategory::query()
            ->where('is_active', true)
            ->with(['items' => fn ($query) => $query->where('is_available', true)])
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (MenuCategory $category) => $category->items->isNotEmpty());

        return view('pages.menu', compact('categories'));
    }

    public function gallery(): View
    {
        return view('pages.gallery', [
            'galleryItems' => GalleryItem::published()->orderBy('sort_order')->paginate(12),
        ]);
    }

    public function faq(): View
    {
        return view('pages.faq', ['faqs' => Faq::published()->paginate(20)]);
    }

    public function journal(): View
    {
        return view('pages.journal.index', [
            'posts' => JournalPost::published()->latest('published_at')->paginate(9),
        ]);
    }

    public function journalPost(string $slug): View
    {
        $post = JournalPost::published()->where('slug', $slug)->firstOrFail();

        return view('pages.journal.show', [
            'post' => $post,
            'safeContent' => app(PageContentSanitizer::class)->sanitize($post->content),
        ]);
    }

    public function location(): View
    {
        return $this->renderCmsPage('location', [], 'pages.location');
    }

    public function contact(): View
    {
        return $this->renderCmsPage('contact', [], 'pages.contact');
    }

    public function cmsPage(string $slug, PageContentSanitizer $sanitizer): View
    {
        $page = CmsPage::published()->where('slug', $slug)->firstOrFail();

        return view('pages.cms-page', [
            'cmsPage' => $page,
            'safeContent' => $sanitizer->sanitize($page->content),
        ]);
    }

    public function sitemap(): Response
    {
        if (WebsiteSetting::query()->value('site_is_demo') ?? true) {
            return response()->view('seo.sitemap', ['urls' => collect()])
                ->header('Content-Type', 'application/xml; charset=UTF-8');
        }

        $urls = collect([
            route('home'),
            route('products.index'),
            route('menu'),
            route('gallery'),
            route('faq'),
            route('promotions.index'),
            route('journal.index'),
        ])->map(fn (string $url) => ['url' => $url, 'last_modified' => null]);

        $pageUrls = CmsPage::published()->get(['slug', 'updated_at'])->map(fn (CmsPage $page) => [
            'url' => route(in_array($page->slug, ['about', 'coffee', 'contact', 'location'], true) ? $page->slug : 'cms-pages.show', in_array($page->slug, ['about', 'coffee', 'contact', 'location'], true) ? [] : $page->slug),
            'last_modified' => $page->updated_at,
        ]);

        $productUrls = Product::published()->where('is_demo', false)->get(['slug', 'updated_at'])->map(fn (Product $product) => [
            'url' => route('products.show', $product->slug),
            'last_modified' => $product->updated_at,
        ]);

        $journalUrls = JournalPost::published()->where('is_demo', false)->get(['slug', 'updated_at'])->map(fn (JournalPost $post) => [
            'url' => route('journal.show', $post->slug),
            'last_modified' => $post->updated_at,
        ]);

        $promotionUrls = Promotion::available()->get(['slug', 'updated_at'])->map(fn (Promotion $promotion) => [
            'url' => route('promotions.show', $promotion->slug),
            'last_modified' => $promotion->updated_at,
        ]);

        return response()->view('seo.sitemap', [
            'urls' => $urls->concat($pageUrls)->concat($productUrls)->concat($journalUrls)->concat($promotionUrls),
        ])->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    private function renderCmsPage(string $slug, array $data = [], string $view = 'pages.cms-page'): View
    {
        $page = CmsPage::published()->where('slug', $slug)->firstOrFail();

        return view($view, [
            ...$data,
            'cmsPage' => $page,
            'safeContent' => app(PageContentSanitizer::class)->sanitize($page->content),
        ]);
    }
}
