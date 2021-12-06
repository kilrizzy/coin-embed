<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:title" content="@yield('metaTitle', config('app.name'))" />
<meta property="og:description" content="@yield('metaDescription','CoinEmbed is a service to create embeddable widgets for easy cryptocurrency payments')" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:site_name" content="{{ config('app.name') }}" />
<meta property="og:image" content="@yield('metaImage',asset('/img/media-card.png'))" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="@kilrizzy" />
<meta name="twitter:title" content="@yield('metaTitle',config('app.name'))" />
<meta name="twitter:description" content="@yield('metaDescription','CoinEmbed is a service to create embeddable widgets for easy cryptocurrency payments')" />
<meta name="twitter:image" content="@yield('metaImage',asset('/img/media-card.png'))" />
<meta name="theme-color" content="#1c64f2">