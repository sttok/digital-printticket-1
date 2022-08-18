var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/images/icons/icon-72x72.png',
    '/images/icons/icon-96x96.png',
    '/images/icons/icon-128x128.png',
    '/images/icons/icon-144x144.png',
    '/images/icons/icon-152x152.png',
    '/images/icons/icon-192x192.png',
    '/images/icons/icon-384x384.png',
    '/images/icons/icon-512x512.png',
    '/images/icons/splash-640x1136.jpg',
    '/images/icons/splash-750x1334.jpg',
    '/images/icons/splash-828x1792.jpg',
    '/images/icons/splash-1125x2436.jpg',
    '/images/icons/splash-1242x2208.jpg',
    '/images/icons/splash-1242x2688.jpg',
    '/images/icons/splash-1536x2048.jpg',
    '/images/icons/splash-1668x2224.jpg',
    '/images/icons/splash-1668x2388.jpg',
    '/images/icons/splash-2048x2732.jpg',
    '/css/app.css',
    '/backendv2/plugins/jquery/jquery-3.4.1.min.js',
    '/backendv2/plugins/bootstrap/js/bootstrap.min.js',
    '/backendv2/plugins/perfectscroll/perfect-scrollbar.min.js',
    '/backendv2/js/main.min.js',
    '/backendv2/js/blazy.min.js',
    'https://unpkg.com/feather-icons',
    'https://unpkg.com/@popperjs/core@2',
    'https://cdn.jsdelivr.net/npm/sweetalert2@10'
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
        .then(cache => {
            return cache.addAll(filesToCache);
        })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                .filter(cacheName => (cacheName.startsWith("pwa-")))
                .filter(cacheName => (cacheName !== staticCacheName))
                .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
        .then(response => {
            return response || fetch(event.request);
        })
        .catch(() => {
            return caches.match('offline');
        })
    )
});