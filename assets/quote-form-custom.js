/* ============================================================
   PDS QUOTE FORM — CUSTOM JS  (v4 — finish chips + remove buttons)
   Enqueued via functions.php (Hello Elementor child theme)
   ============================================================ */
(function () {
    'use strict';

    if (window.__pdsQuoteFormInit) { return; }
    window.__pdsQuoteFormInit = true;

    var PRODUCT_GROUPS = [
        { name: 'expanders_products',      label: 'Expanders' },
        { name: 'cabins_pods_products',    label: 'Cabins & Pods' },
        { name: 'class_1a_products',       label: 'DIY Class 1A' },
        { name: 'half_expanders_products', label: 'Half Expanders' },
        { name: 'accessories_products',    label: 'Accessories' }
    ];

    var RADIO_DESCRIPTIONS = {
        placement_location: {
            'Backyard / residential block': 'Standard suburban block access and council rules apply.',
            'Rural or semi-rural property': 'Larger clearance, may need longer haulage or a permit check.',
            'Vacant land': 'No existing structures — we\'ll confirm site access with you.',
            'Other': 'Tell us a bit more in the notes field below.'
        },
        installation_preference: {
            'Full installation': 'We deliver, site and connect your unit ready to use.',
            'Delivery only': 'We drop it on-site — you handle siting and connections.'
        }
    };

    function init() {
        var form = document.querySelector('.fluent_form_5, .frm-fluent-form');
        if (!form) { return; }

        buildTabsAndCards(form);
        setupRadioCards(form);
        var sidebar = buildSidebar(form);
        updateSidebar(form, sidebar);
        form.addEventListener('change', function () { updateSidebar(form, sidebar); });
        trackStepProgress(form);
        applyPrefill(form);

        /* Sidebar click delegation: remove a finish chip or uncheck an accessory */
        form.addEventListener('click', function (e) {
            var removeBtn = e.target.closest('[data-remove-finish]');
            if (removeBtn && window.pdsPrefill && window.pdsPrefill.finishes) {
                delete window.pdsPrefill.finishes[removeBtn.dataset.removeFinish];
                updateSidebar(form, sidebar);
                return;
            }

            var uncheckBtn = e.target.closest('[data-uncheck-product]');
            if (uncheckBtn) {
                var li = uncheckBtn.closest('li');
                var value = li ? li.dataset.uncheckValue : null;
                if (value) {
                    var input = form.querySelector('.ff-el-image-holder input[type="checkbox"][value="' + CSS.escape(value) + '"]');
                    if (input) {
                        input.checked = false;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            }
        });
    }

    /* ---------- 1. Build tabs + rich cards from pdsProductData ---------- */
    function getWrapper(name) {
        var input = formQuerySelector(name);
        if (!input) { return null; }
        return input.closest('.ff-el-group') || input.closest('[data-name]') || input.parentElement.parentElement;
    }

    function formQuerySelector(name) {
        return document.querySelector('[name="' + name + '[]"]') || document.querySelector('[name="' + name + '"]');
    }

    function buildTabsAndCards(form) {
        if (form.querySelector('.pds-tab-nav')) { return; }

        var wrappers = PRODUCT_GROUPS.map(function (g) {
            var el = getWrapper(g.name);
            if (el) { el.classList.add('pds-tab-panel'); }
            return { label: g.label, name: g.name, el: el };
        }).filter(function (g) { return g.el; });

        if (!wrappers.length) { return; }

        var nav = document.createElement('div');
        nav.className = 'pds-tab-nav';
        nav.setAttribute('role', 'tablist');

        wrappers.forEach(function (g, i) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'pds-tab-btn' + (i === 0 ? ' is-active' : '');
            btn.textContent = g.label;
            btn.setAttribute('role', 'tab');
            btn.setAttribute('aria-selected', i === 0 ? 'true' : 'false');
            btn.addEventListener('click', function () {
                wrappers.forEach(function (w) { w.el.classList.remove('is-active'); });
                nav.querySelectorAll('.pds-tab-btn').forEach(function (b) {
                    b.classList.remove('is-active');
                    b.setAttribute('aria-selected', 'false');
                });
                g.el.classList.add('is-active');
                btn.classList.add('is-active');
                btn.setAttribute('aria-selected', 'true');
            });
            nav.appendChild(btn);
            if (i === 0) { g.el.classList.add('is-active'); }
        });

        wrappers[0].el.parentNode.insertBefore(nav, wrappers[0].el);
        markTabsWithSelections(form, nav, wrappers);
        form.addEventListener('change', function () { markTabsWithSelections(form, nav, wrappers); });

        if (typeof window.pdsProductData === 'undefined') { return; }
        buildRichCards(form);
    }

    function markTabsWithSelections(form, nav, wrappers) {
        var buttons = Array.prototype.slice.call(nav.querySelectorAll('.pds-tab-btn'));
        wrappers.forEach(function (w, i) {
            var hasChecked = w.el.querySelector('input:checked');
            if (buttons[i]) { buttons[i].classList.toggle('pds-tab-has-selection', !!hasChecked); }
        });
    }

    function buildRichCards(form) {
        form.querySelectorAll('.ff-el-form-check.ff-el-image-holder:not(.pds-rich-card)').forEach(function (wrapper) {
            var input = wrapper.querySelector('input[type="checkbox"]');
            if (!input) { return; }

            var p = window.pdsProductData[input.value];
            if (!p) { return; }

            wrapper.classList.add('pds-rich-card');

            var specsHtml = (p.specs || []).map(function (s) {
                return '<div class="spec-cell"><span class="sv">' + (s.value || '—') + '</span><span class="sk">' + s.label + '</span></div>';
            }).join('');

            var priceHtml = p.price
                ? '<div class="card-price"><span class="from-lbl">From</span><span class="amount">$' + Number(p.price).toLocaleString() + '</span></div>'
                : '';

            wrapper.innerHTML =
                '<div class="card-img">' +
                    (p.image ? '<img src="' + p.image + '" alt="' + escapeHtml(p.title) + '" loading="lazy">' : '') +
                    (p.badge ? '<span class="card-badge">' + escapeHtml(p.badge) + '</span>' : '') +
                '</div>' +
                '<div class="card-body">' +
                    '<h3>' + escapeHtml(p.title) + '</h3>' +
                    (p.desc ? '<p class="tagline">' + escapeHtml(p.desc) + '</p>' : '') +
                    (specsHtml ? '<div class="card-specs">' + specsHtml + '</div>' : '') +
                '</div>' +
                '<div class="card-footer">' +
                    priceHtml +
                    '<span class="pds-checkmark"><svg viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"></path></svg></span>' +
                '</div>';

            wrapper.insertBefore(input, wrapper.firstChild);
            input.style.position = 'absolute';
            input.style.opacity = '0';
            input.style.pointerEvents = 'none';

            wrapper.style.cursor = 'pointer';
            wrapper.addEventListener('click', function (e) {
                if (e.target.closest('a')) { return; }
                input.checked = !input.checked;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
            input.addEventListener('change', function () {
                wrapper.classList.toggle('is-checked', input.checked);
            });
        });
    }

    /* ---------- 2. Radio option cards + descriptions ---------- */
    function setupRadioCards(form) {
        Object.keys(RADIO_DESCRIPTIONS).forEach(function (fieldName) {
            var firstInput = form.querySelector('input[name="' + fieldName + '"]');
            if (!firstInput) { return; }
            var content = firstInput.closest('.ff-el-input--content');
            if (!content || content.dataset.pdsCardsDone) { return; }
            content.classList.add('pds-radio-cards');
            content.dataset.pdsCardsDone = '1';

            content.querySelectorAll('.ff-el-form-check').forEach(function (check) {
                var input = check.querySelector('input[type="radio"]');
                var textSpan = check.querySelector('label > span');
                if (!input || !textSpan) { return; }
                var desc = RADIO_DESCRIPTIONS[fieldName][input.value];
                if (desc && !check.querySelector('.pds-opt-desc')) {
                    var descEl = document.createElement('span');
                    descEl.className = 'pds-opt-desc';
                    descEl.textContent = desc;
                    textSpan.parentNode.appendChild(descEl);
                }
            });
        });
    }

    /* ---------- 3. Sidebar scaffold ---------- */
    function buildSidebar(form) {
        if (document.querySelector('.pds-selection-sidebar')) {
            return document.querySelector('.pds-selection-sidebar');
        }

        var wrapper = document.createElement('div');
        wrapper.className = 'pds-selection-sidebar';
        wrapper.innerHTML =
            '<h4>Your selection</h4>' +
            '<p class="pds-sidebar-sub">Updates live as you choose products</p>' +
            '<div class="pds-sidebar-section pds-sidebar-section-products">' +
                '<span class="pds-sidebar-section-title">Products selected</span>' +
                '<ul class="pds-sidebar-items"></ul>' +
                '<div class="pds-sidebar-empty">No products selected yet</div>' +
                '<div class="pds-sidebar-total" style="display:none;">' +
                    '<span class="lbl">Estimated total</span>' +
                    '<span class="val">$0</span>' +
                '</div>' +
                '<p class="pds-sidebar-note">Estimate only, based on list pricing. Your final quote may vary with site conditions and options chosen.</p>' +
            '</div>' +
            '<div class="pds-sidebar-section pds-sidebar-section-prefs">' +
                '<span class="pds-sidebar-section-title">Your preferences</span>' +
                '<div class="pds-sidebar-details"></div>' +
                '<div class="pds-sidebar-empty-prefs">Nothing selected yet</div>' +
            '</div>';

        var totalWrapEl = wrapper.querySelector('.pds-sidebar-total');
        if (totalWrapEl) { totalWrapEl.setAttribute('aria-live', 'polite'); }

        var formWrapper = form.closest('.fluentform') || form.parentElement;
        var host = formWrapper.parentElement || form.parentElement;

        if (host) {
            var content = host.querySelector('.pds-form-main-content');
            if (!content) {
                content = document.createElement('div');
                content.className = 'pds-form-main-content';
                while (host.firstChild) {
                    content.appendChild(host.firstChild);
                }
                host.appendChild(content);
            }
            if (!host.classList.contains('pds-quote-layout')) {
                host.classList.add('pds-quote-layout');
            }
            host.insertBefore(wrapper, host.firstChild);
        } else {
            formWrapper.insertAdjacentElement('afterend', wrapper);
        }
        return wrapper;
    }

    /* ---------- 4. Update sidebar on change (includes prefill product) ---------- */
    function updateSidebar(form, sidebar) {
        var list = sidebar.querySelector('.pds-sidebar-items');
        var empty = sidebar.querySelector('.pds-sidebar-empty');
        var totalWrap = sidebar.querySelector('.pds-sidebar-total');
        var totalVal = sidebar.querySelector('.val');
        var details = sidebar.querySelector('.pds-sidebar-details');

        list.innerHTML = '';
        var sum = 0;
        var count = 0;
        var data = window.pdsProductData || {};

        var pf = window.pdsPrefill;
        if (pf && pf.model) {
            var pfPrice = pf.price ? Number(pf.price) : 0;
            sum += pfPrice;
            count++;
            var pfLabel = pf.model + (pf.layout ? ' — ' + pf.layout : '');

            var finishChips = Object.keys(pf.finishes || {}).map(function (key) {
                var raw = pf.finishes[key] || '';
                var parts = raw.split('|');
                var name = parts[0];
                var image = parts[1];
                var label = parts[2] || key;
                if (!name) { return ''; }
                var dotStyle = image
                    ? ' style="background-image:url(\'' + image.replace(/'/g, "%27") + '\');background-size:cover;background-position:center center;"'
                    : '';
                return '<span class="pds-finish-chip" data-finish-key="' + escapeHtml(key) + '">' +
                '<span class="pds-finish-dot"' + dotStyle + '></span>' +
                '<span class="pds-finish-text">' +
                    '<span class="pds-finish-label">' + escapeHtml(label) + '</span>' +
                    '<span class="pds-finish-value">' + escapeHtml(name) + '</span>' +
                '</span>' +
                '<button type="button" class="pds-chip-remove" data-remove-finish="' + escapeHtml(key) + '" aria-label="Remove">×</button>' +
                '</span>';
            }).join('');
            var finishesHtml = finishChips ? '<div class="pds-item-finishes">' + finishChips + '</div>' : '';

            var pfLi = document.createElement('li');
            pfLi.className = 'pds-sidebar-product-item';
            var editHtml = pf.returnUrl
                ? '<a href="' + escapeHtml(pf.returnUrl) + '" class="pds-item-edit">← Edit selections on the product page</a>'
                : '';
            pfLi.innerHTML =
                '<div class="pds-item-top">' +
                    '<span class="pds-item-name">' + escapeHtml(pfLabel) + '</span>' +
                    (pfPrice ? '<span class="pds-item-price">$' + pfPrice.toLocaleString('en-AU') + '</span>' : '') +
                '</div>' +
                finishesHtml +
                editHtml;
            list.appendChild(pfLi);
        }

        form.querySelectorAll('.ff-el-image-holder input[type="checkbox"]:checked').forEach(function (input) {
            var p = data[input.value];
            var label = p ? p.title : (input.getAttribute('aria-label') || input.value);
            var price = p && p.price ? Number(p.price) : 0;
            sum += price;
            count++;
            var li = document.createElement('li');
            li.dataset.uncheckValue = input.value;
            li.innerHTML =
                '<span class="pds-item-name">' + escapeHtml(label) + '</span>' +
                '<span class="pds-item-right">' +
                    (price ? '<span class="pds-item-price">$' + price.toLocaleString('en-AU') + '</span>' : '') +
                    '<button type="button" class="pds-chip-remove" data-uncheck-product aria-label="Remove">×</button>' +
                '</span>';
            list.appendChild(li);
        });

        empty.style.display = count ? 'none' : 'block';
        totalWrap.style.display = count ? 'flex' : 'none';
        totalVal.textContent = '$' + sum.toLocaleString('en-AU');

        var emptyPrefs = sidebar.querySelector('.pds-sidebar-empty-prefs');
        var detailRows = [];
        var placement = form.querySelector('input[name="placement_location"]:checked');
        var install = form.querySelector('input[name="installation_preference"]:checked');
        var intendedUse = Array.prototype.slice.call(form.querySelectorAll('input[name="intended_use[]"]:checked'))
            .map(function (i) { return i.value; });

        if (placement) { detailRows.push(['Site', placement.value]); }
        if (install) { detailRows.push(['Preference', install.value]); }
        if (intendedUse.length) { detailRows.push(['Use', intendedUse.join(', ')]); }

        if (detailRows.length) {
            details.classList.add('pds-has-content');
            details.innerHTML = detailRows.map(function (row) {
                return '<div class="pds-detail-row"><span>' + escapeHtml(row[0]) + '</span><span>' + escapeHtml(row[1]) + '</span></div>';
            }).join('');
            if (emptyPrefs) { emptyPrefs.style.display = 'none'; }
        } else {
            details.classList.remove('pds-has-content');
            details.innerHTML = '';
            if (emptyPrefs) { emptyPrefs.style.display = 'block'; }
        }
    }

    /* ---------- 5. Step progress (completed checkmarks) ---------- */
    function trackStepProgress(form) {
        var titles = form.querySelector('.ff-step-titles');
        if (!titles) { return; }

        function refresh() {
            var items = Array.prototype.slice.call(titles.children);
            var activeIndex = items.findIndex(function (li) { return li.classList.contains('ff_active'); });
            items.forEach(function (li, i) {
                li.classList.toggle('pds-step-complete', activeIndex > -1 && i < activeIndex);
            });
        }

        refresh();
        var observer = new MutationObserver(refresh);
        Array.prototype.slice.call(titles.children).forEach(function (li) {
            observer.observe(li, { attributes: true, attributeFilter: ['class'] });
        });
    }

    /* ---------- 6. Prefill from URL params (single definition) ---------- */
    function applyPrefill(form) {
        if (!window.pdsPrefill) { return; }
        var pf = window.pdsPrefill;

        setHiddenField(form, 'prefill_model', pf.model);
        setHiddenField(form, 'prefill_layout', pf.layout);
        setHiddenField(form, 'prefill_price', pf.price);
        setHiddenField(form, 'prefill_shire_approval', pf.shireApproval);
        setHiddenField(form, 'prefill_finishes', JSON.stringify(pf.finishes || {}));

        form.querySelectorAll('.ff_list_buttons input[type="checkbox"], .ff_list_buttons input[type="radio"]').forEach(function (el) {
            el.removeAttribute('required');
        });

        hideNonAccessoryTabs(form);
    }

    function hideNonAccessoryTabs(form) {
        var nav = form.querySelector('.pds-tab-nav');
        if (!nav) { return; }

        var buttons = Array.prototype.slice.call(nav.querySelectorAll('.pds-tab-btn'));
        var panels = Array.prototype.slice.call(form.querySelectorAll('.pds-tab-panel'));

        buttons.forEach(function (btn, i) {
            var isAccessories = btn.textContent.trim() === 'Accessories';
            btn.style.display = isAccessories ? '' : 'none';
            if (panels[i]) { panels[i].classList.toggle('is-active', isAccessories); }
            if (isAccessories) { btn.classList.add('is-active'); btn.setAttribute('aria-selected', 'true'); }
            else { btn.classList.remove('is-active'); btn.setAttribute('aria-selected', 'false'); }
        });
    }

    function setHiddenField(form, name, value) {
        var input = form.querySelector('[name="' + name + '"]');
        if (input) {
            input.value = value || '';
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.textContent = str == null ? '' : str;
        return div.innerHTML;
    }

    /* ---------- boot: poll for form since Fluent Forms renders async ---------- */
    var attempts = 0;
    var poll = setInterval(function () {
        attempts++;
        var found = document.querySelector('.fluent_form_5, .frm-fluent-form');
        if (found || attempts > 40) {
            clearInterval(poll);
            if (found) { init(); }
        }
    }, 250);
})();