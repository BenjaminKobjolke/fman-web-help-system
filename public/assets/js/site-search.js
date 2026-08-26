// Site-wide search over help articles and keybindings. The index is a single
// generated file (data/search.json) fetched on first focus, not on page load —
// most visits never touch the search box, and the file is browser-cached across
// pages once it has been pulled.
(function () {
    'use strict';

    var wrap = document.querySelector('[data-site-search]');
    if (!wrap) {
        return;
    }

    var input = wrap.querySelector('#site-search');
    var panel = wrap.querySelector('#site-search-results');
    var base = document.body.getAttribute('data-base') || '/';
    var labels = {
        empty: panel.getAttribute('data-empty') || 'No results.',
        help: panel.getAttribute('data-group-help') || 'Help',
        shortcut: panel.getAttribute('data-group-shortcuts') || 'Shortcuts'
    };

    var MAX_RESULTS = 12;
    // Fuse scores 0 (exact) to 1 (nothing in common). Anything past this is noise.
    var SCORE_CUTOFF = 0.3;
    var fuse = null;
    var loading = null;
    var activeIndex = -1;
    var links = [];

    function load() {
        if (loading) {
            return loading;
        }
        loading = fetch(base + 'data/search.json')
            .then(function (r) {
                if (!r.ok) {
                    throw new Error('search index ' + r.status);
                }
                return r.json();
            })
            .then(function (records) {
                fuse = new Fuse(records, {
                    keys: [
                        { name: 'title', weight: 3 },
                        { name: 'subtitle', weight: 2 },
                        { name: 'text', weight: 1 }
                    ],
                    // Tighter than the keybindings-page filter on purpose. That one
                    // searches short command names only; this one also searches whole
                    // articles, where a loose threshold lets a short query match the
                    // letters of almost any sentence in order ("icons" hitting
                    // "extends selection").
                    threshold: 0.25,
                    ignoreLocation: true,
                    minMatchCharLength: 2,
                    includeScore: true
                });
            });

        return loading;
    }

    function close() {
        panel.hidden = true;
        panel.innerHTML = '';
        input.setAttribute('aria-expanded', 'false');
        activeIndex = -1;
        links = [];
    }

    function highlight(index) {
        links.forEach(function (a, i) {
            a.classList.toggle('is-active', i === index);
        });
        if (index >= 0 && links[index]) {
            links[index].scrollIntoView({ block: 'nearest' });
        }
        activeIndex = index;
    }

    function render(results) {
        panel.innerHTML = '';
        links = [];
        activeIndex = -1;

        if (results.length === 0) {
            var none = document.createElement('p');
            none.className = 'search-empty';
            none.textContent = labels.empty;
            panel.appendChild(none);
            panel.hidden = false;
            input.setAttribute('aria-expanded', 'true');
            return;
        }

        // Help first, shortcuts second — a prose page answers "how does X work?",
        // which is what someone typing into a help site usually means.
        ['help', 'shortcut'].forEach(function (type) {
            var group = results.filter(function (r) {
                return r.type === type;
            });
            if (group.length === 0) {
                return;
            }

            var heading = document.createElement('p');
            heading.className = 'search-group';
            heading.textContent = labels[type];
            panel.appendChild(heading);

            group.forEach(function (record) {
                var a = document.createElement('a');
                a.className = 'search-hit search-hit-' + type;
                a.href = base + record.url;
                a.setAttribute('role', 'option');

                var title = document.createElement('span');
                title.className = 'search-hit-title';
                title.textContent = record.title;
                a.appendChild(title);

                if (record.subtitle) {
                    var sub = document.createElement(type === 'shortcut' ? 'kbd' : 'span');
                    sub.className = 'search-hit-sub';
                    sub.textContent = record.subtitle;
                    a.appendChild(sub);
                }

                panel.appendChild(a);
                links.push(a);
            });
        });

        panel.hidden = false;
        input.setAttribute('aria-expanded', 'true');
    }

    function search() {
        var query = (input.value || '').trim();
        if (query.length < 2) {
            close();
            return;
        }
        load().then(function () {
            if (!fuse || (input.value || '').trim() !== query) {
                return;
            }
            var hits = fuse.search(query).filter(function (r) {
                return r.score <= SCORE_CUTOFF;
            });
            render(hits.slice(0, MAX_RESULTS).map(function (r) {
                return r.item;
            }));
        }).catch(function () {
            close();
        });
    }

    var debounce = null;
    input.addEventListener('input', function () {
        window.clearTimeout(debounce);
        debounce = window.setTimeout(search, 120);
    });

    input.addEventListener('focus', function () {
        load();
        if ((input.value || '').trim().length >= 2) {
            search();
        }
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            close();
            return;
        }
        if (panel.hidden || links.length === 0) {
            return;
        }
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            highlight((activeIndex + 1) % links.length);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            highlight((activeIndex - 1 + links.length) % links.length);
        } else if (e.key === 'Enter' && activeIndex >= 0) {
            e.preventDefault();
            links[activeIndex].click();
        }
    });

    document.addEventListener('click', function (e) {
        if (!wrap.contains(e.target)) {
            close();
        }
    });

    // "/" focuses the search from anywhere, unless the visitor is already typing.
    document.addEventListener('keydown', function (e) {
        if (e.key !== '/' || e.ctrlKey || e.metaKey || e.altKey) {
            return;
        }
        var tag = (e.target.tagName || '').toLowerCase();
        if (tag === 'input' || tag === 'textarea' || e.target.isContentEditable) {
            return;
        }
        e.preventDefault();
        input.focus();
    });
})();
