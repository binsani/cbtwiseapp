// This worker deliberately caches only the small, stable PWA shell.  HTML and
// Livewire responses must always come from the network so candidates never see
// an old exam screen after a deployment.
const CACHE_NAME = 'cbtwise-static-v2';
const ASSETS = [
    '/manifest.json',
    '/favicon.png',
    '/icons/icon-192x192.png',
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(ASSETS))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys => Promise.all(
            keys.map(key => {
                if (key !== CACHE_NAME) {
                    return caches.delete(key);
                }
            })
        )).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', event => {
    // Never cache application pages or Livewire requests.  A stale HTML page
    // can reference a different Livewire component version and freeze an exam.
    if (event.request.method !== 'GET') {
        return;
    }

    const url = new URL(event.request.url);
    if (ASSETS.includes(url.pathname)) {
        event.respondWith(
            fetch(event.request)
                .then(response => {
                    const copy = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(event.request, copy));
                    return response;
                })
                .catch(() => caches.match(event.request))
        );
    }
});
