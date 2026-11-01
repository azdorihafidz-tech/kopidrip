<?php

namespace App\Http\Middleware;

use App\Models\NavigationItem;
use App\Models\WebsiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareWebsiteSettings
{
    public function handle(Request $request, Closure $next): Response
    {
        View::share('websiteSettings', WebsiteSetting::query()->first() ?? new WebsiteSetting(['site_is_demo' => true]));
        View::share('navigationItems', NavigationItem::publicItems());

        return $next($request);
    }
}
