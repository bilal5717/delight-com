<script type="application/ld+json">
<?php
$appURL = url('/');

// Pages
$pages = \App\Models\Page::orderBy('lft', 'ASC')->get();

// Country code
$countryCode = session('country_code', '');

// Base breadcrumbs
$itemsListElementBreadcrumbs = [
    [
        '@type' => 'ListItem',
        'position' => 1,
        'name' => 'Home',
        'item' => $appURL,
    ],
    [
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'Register',
        'item' => $appURL . '/register',
    ],
    [
        '@type' => 'ListItem',
        'position' => 3,
        'name' => 'Login',
        'item' => $appURL . '/login',
    ],
    [
        '@type' => 'ListItem',
        'position' => 4,
        'name' => 'Create',
        'item' => $appURL . '/create',
    ],
    [
        '@type' => 'ListItem',
        'position' => 5,
        'name' => 'Search',
        'item' => $appURL . '/search?d=' . $countryCode,
    ],
    [
        '@type' => 'ListItem',
        'position' => 6,
        'name' => 'Contact',
        'item' => $appURL . '/contact',
    ],
    [
        '@type' => 'ListItem',
        'position' => 7,
        'name' => 'Sitemap',
        'item' => $appURL . '/sitemap',
    ],
    [
        '@type' => 'ListItem',
        'position' => 8,
        'name' => 'Password Reset',
        'item' => $appURL . '/password/reset',
    ],
    [
        '@type' => 'ListItem',
        'position' => 9,
        'name' => 'Pricing',
        'item' => $appURL . '/pricing',
    ],
    [
        '@type' => 'ListItem',
        'position' => 10,
        'name' => 'Logout',
        'item' => $appURL . '/logout',
    ],
];

// Add dynamic pages
foreach ($pages as $key => $page) {
    $url = \App\Helpers\UrlGen::page($page, $countryCode);
    $itemsListElementBreadcrumbs[] = [
        '@type' => 'ListItem',
        'position' => $key + 11,
        'name' => $page->name,
        'item' => $url,
    ];
}

// Add categories (flat collection expected)
$itemListElement = [];
if (isset($categories) && $categories instanceof \Illuminate\Support\Collection) {
    foreach ($categories as $key => $category) {
        if (isset($category->name)) {
            $item = [
                '@type' => 'ProductCategory',
                'name' => $category->name,
                'url'  => \App\Helpers\UrlGen::category($category),
            ];
            $itemListElement[] = [
                '@type'    => 'ListItem',
                'position' => $key + 1,
                'item'     => $item,
            ];
        }
    }
}

// Structured data
$structuredData = [
    '@context'    => 'http://schema.org',
    '@type'       => config('settings.app.app_name') . ' - ' . config('settings.app.slogan'),
    'name'        => getMetaTag('title', $pageTitle),
    'url'         => $appURL . '/' . $pageUrl,
    'description' => strip_tags(getMetaTag('description', $pageTitle)),
    'breadcrumb'  => [
        '@type' => 'BreadcrumbList',
        'itemListElement' => $itemsListElementBreadcrumbs,
    ],
    'itemListElement' => $itemListElement,
];

// Convert to JSON
echo json_encode($structuredData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
</script>