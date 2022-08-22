var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/storage/public/icon-72x72.png',
    '/storage/public/icon-96x96.png',
    '/storage/public/icon-128x128.png',
    '/storage/public/icon-144x144.png',
    '/storage/public/icon-152x152.png',
    '/storage/public/icon-192x192.png',
    '/storage/public/icon-384x384.png',
    '/storage/public/icon-512x512.png',
    '/storage/public/splash-640x1136.jpg',
    '/storage/public/splash-750x1334.jpg',
    '/storage/public/splash-828x1792.jpg',
    '/storage/public/splash-1125x2436.jpg',
    '/storage/public/splash-1242x2208.jpg',
    '/storage/public/splash-1242x2688.jpg',
    '/storage/public/splash-1536x2048.jpg',
    '/storage/public/splash-1668x2224.jpg',
    '/storage/public/splash-1668x2388.jpg',
    '/storage/public/splash-2048x2732.jpg',
    '/css/app.css',
    'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js',
    '/backendv2/plugins/bootstrap/js/bootstrap.min.js',
    '/backendv2/plugins/perfectscroll/perfect-scrollbar.min.js',
    '/backendv2/js/main.min.js',
    '/backendv2/js/blazy.min.js',
    'https://unpkg.com/feather-icons',
    'https://unpkg.com/@popperjs/core@2',
    'https://cdn.jsdelivr.net/npm/sweetalert2@10',
    '/backendv2/js/custom.js',
    'https://rawgit.com/sitepoint-editors/jsqrcode/master/src/qr_packed.js',
    '/js/qrCodeScanner.js'
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