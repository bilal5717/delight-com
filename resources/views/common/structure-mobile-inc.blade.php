<script type="application/ld+json">
    <?php
    $appURL = url('/');
    $pages = \App\Models\Page::orderBy('lft', 'ASC')->get();
    $countryCode = '';
    if (session()->has('country_code')) {
        $countryCode = session('country_code');
    }
    $itemsListElement = [
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
            'item' => $appURL . '/search?d='. $countryCode,
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
    foreach ($pages as $key=>$page) {
        $url = \App\Helpers\UrlGen::page($page, $countryCode);
        $itemListElement = [];
        $itemListElement['@type'] = 'ListItem';
        $itemListElement['position'] = $key+11;
        $itemListElement['name'] = $page->name;
        $itemListElement['item'] = $url;

        $itemsListElement [] = $itemListElement;

        if ($page->slug == 'micro-device') {
            $pageTitle = $page->name;
            $pageUrl = $url;
        }
    }

    $structuredData = [
        '@context' => 'http://schema.org',
        '@type' => config('settings.app.app_name').' - '.config('settings.app.slogan'),
        'name2' => getMetaTag('title', $pageTitle),
        'url' => $appURL . '/' . $pageUrl,
        'description' => strip_tags(getMetaTag('description', $pageTitle)),
        'breadcrumb' => [
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemsListElement
        ]
    ];

    // Convert to JSON
    $json_ld = json_encode($structuredData, JSON_PRETTY_PRINT);

    // Output the JSON-LD
    echo $json_ld;

    ?>
</script>