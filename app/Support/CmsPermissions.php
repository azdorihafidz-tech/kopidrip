<?php

namespace App\Support;

final class CmsPermissions
{
    public const SETTINGS = 'settings.manage';

    public const HOMEPAGE = 'homepage.manage';

    public const PAGES = 'pages.manage';

    public const NAVIGATION = 'navigation.manage';

    public const CATALOG = 'catalog.manage';

    public const MENU = 'menu.manage';

    public const GALLERY = 'gallery.manage';

    public const JOURNAL = 'journal.manage';

    public const CONTACT_MESSAGES = 'contact-messages.manage';

    public const FAQ = 'faq.manage';

    public const TESTIMONIALS = 'testimonials.manage';

    public const PROMOTIONS = 'promotions.manage';

    public static function allows(string $permission): bool
    {
        return auth()->user()?->can($permission) ?? false;
    }
}
