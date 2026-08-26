// Fuzzy filter over the server-rendered keybinding rows. The rendered DOM is the
// single data source — no separate JSON fetch, so nothing can drift out of sync.
(function () {
    'use strict';

    var rows = Array.prototype.slice.call(document.querySelectorAll('[data-kb-row]'));
    if (rows.length === 0) {
        return;
    }

    var items = rows.map(function (el) {
        return {
            el: el,
            platform: el.getAttribute('data-platform') || '',
            keys: el.getAttribute('data-keys') || '',
            name: el.getAttribute('data-name') || '',
            command: el.getAttribute('data-command') || ''
        };
    });

    var fuse = new Fuse(items, {
        keys: ['name', 'command', 'keys'],
        threshold: 0.4,
        ignoreLocation: true,
        minMatchCharLength: 2
    });

    var searchInput = document.getElementById('kb-search');
    var filters = document.getElementById('kb-filters');
    var emptyMsg = document.getElementById('kb-empty');
    var sections = Array.prototype.slice.call(document.querySelectorAll('.kb-section'));

    var activePlatform = 'all';

    function apply() {
        var query = (searchInput.value || '').trim();
        var matched = null; // null = no search, show everything
        if (query.length > 0) {
            matched = new Set();
            fuse.search(query).forEach(function (r) {
                matched.add(r.item.el);
            });
        }

        var anyVisible = false;
        items.forEach(function (it) {
            // Base ('all') bindings apply on every OS, so an OS filter shows base + that OS.
            // 'viewer' is its own context (base excluded); 'all' shows everything.
            var platformOk;
            if (activePlatform === 'all') {
                platformOk = true;
            } else if (activePlatform === 'viewer') {
                platformOk = it.platform === 'viewer';
            } else {
                platformOk = it.platform === activePlatform || it.platform === 'all';
            }
            var searchOk = matched === null || matched.has(it.el);
            var visible = platformOk && searchOk;
            it.el.hidden = !visible;
            if (visible) {
                anyVisible = true;
            }
        });

        // Hide a section whose rows are all filtered out.
        sections.forEach(function (section) {
            var visibleRow = section.querySelector('[data-kb-row]:not([hidden])');
            section.hidden = visibleRow === null;
        });

        emptyMsg.hidden = anyVisible;
    }

    if (searchInput) {
        searchInput.addEventListener('input', apply);
    }

    if (filters) {
        filters.addEventListener('click', function (e) {
            var btn = e.target.closest('.filter');
            if (!btn) {
                return;
            }
            activePlatform = btn.getAttribute('data-filter') || 'all';
            filters.querySelectorAll('.filter').forEach(function (b) {
                b.classList.toggle('is-active', b === btn);
            });
            apply();
        });
    }

    function selectFilter(platform) {
        if (!platform || !filters) {
            return false;
        }
        var preset = filters.querySelector('.filter[data-filter="' + platform + '"]');
        if (!preset) {
            return false;
        }
        preset.click();

        return true;
    }

    // Arriving from the site-wide search with #kb-... in the URL: show that row's
    // platform, or the OS guess below would hide the very row we linked to.
    var target = window.location.hash.length > 1
        ? document.getElementById(window.location.hash.slice(1))
        : null;

    if (target) {
        selectFilter(target.getAttribute('data-platform'));
        target.classList.add('is-targeted');
        // After the filter has reflowed, and after the browser's own jump to the
        // anchor — which happens on load and would otherwise land on stale offsets.
        window.requestAnimationFrame(function () {
            target.scrollIntoView({ block: 'center' });
        });
    } else {
        // Otherwise default the platform filter to the visitor's OS.
        var ua = (navigator.platform || '').toLowerCase();
        selectFilter(
            ua.indexOf('win') !== -1 ? 'windows'
                : ua.indexOf('mac') !== -1 ? 'mac'
                    : ua.indexOf('linux') !== -1 ? 'linux' : ''
        );
    }
})();
