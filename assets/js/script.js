/**
 * ShopEasy - Master Interactive JavaScript Bundle
 * Powers real-time search, cart & wishlist AJAX, countdown timers, and modals.
 */

document.addEventListener('DOMContentLoaded', () => {
    initHeaderSearch();
    initHeroSlider();
    initCountdownTimer();
    initMobileDrawer();
    initCartQuantityControls();
    initGlobalToastListener();
    initThemeSwitcher();
});

/* ==========================================================================
   Global Toast Notification Function
   ========================================================================== */
function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast-item ${type}`;

    let icon = 'info-circle';
    if (type === 'success') icon = 'check-circle';
    if (type === 'error') icon = 'exclamation-circle';

    toast.innerHTML = `
        <i class="fas fa-${icon}"></i>
        <span>${message}</span>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(40px)';
        setTimeout(() => toast.remove(), 400);
    }, 3500);
}

/* ==========================================================================
   Live Auto-Suggest Search Bar
   ========================================================================== */
function initHeaderSearch() {
    const searchInput = document.getElementById('globalSearchInput');
    const categorySelect = document.getElementById('searchCategorySelect');
    const clearBtn = document.getElementById('clearSearchBtn');
    const dropdown = document.getElementById('searchSuggestionsBox');
    const list = document.getElementById('suggestionsList');
    const footer = document.getElementById('suggestionsFooter');
    const viewAllLink = document.getElementById('viewAllResultsLink');
    const loading = document.getElementById('suggestionsLoading');

    if (!searchInput || !dropdown) return;

    let debounceTimer;

    const performSearch = () => {
        const query = searchInput.value.trim();
        const cat = categorySelect ? categorySelect.value : '';

        if (query.length > 0 && clearBtn) {
            clearBtn.style.display = 'block';
        } else if (clearBtn) {
            clearBtn.style.display = 'none';
        }

        if (query.length < 2) {
            dropdown.style.display = 'none';
            return;
        }

        if (loading) loading.style.display = 'block';
        dropdown.style.display = 'block';

        fetch(`ajax/live_search.php?q=${encodeURIComponent(query)}&category=${encodeURIComponent(cat)}`)
            .then(res => res.json())
            .then(data => {
                if (loading) loading.style.display = 'none';
                if (data.results && data.results.length > 0) {
                    list.innerHTML = data.results.map(item => `
                        <div class="suggestion-item-row" onclick="window.location.href='${item.url}'">
                            <img src="${item.image}" alt="${item.name}" class="suggestion-thumb" onerror="this.src='assets/images/placeholder.svg'">
                            <div class="suggestion-info">
                                <div class="suggestion-title">${item.name}</div>
                                <div class="suggestion-cat">${item.category} &bull; <span style="color: #10b981;">${item.in_stock ? 'In Stock' : 'Out of Stock'}</span></div>
                            </div>
                            <div class="suggestion-price">${item.price}</div>
                        </div>
                    `).join('');
                    if (footer && viewAllLink) {
                        viewAllLink.href = `products.php?search=${encodeURIComponent(query)}`;
                        footer.style.display = 'block';
                    }
                } else {
                    list.innerHTML = `<div style="padding: 16px; text-align: center; color: #94a3b8; font-size: 13px;">No products found matching "${query}"</div>`;
                    if (footer) footer.style.display = 'none';
                }
            })
            .catch(() => {
                if (loading) loading.style.display = 'none';
                dropdown.style.display = 'none';
            });
    };

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(performSearch, 220);
    });

    if (categorySelect) {
        categorySelect.addEventListener('change', () => {
            if (searchInput.value.trim().length >= 2) performSearch();
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.style.display = 'none';
            dropdown.style.display = 'none';
            searchInput.focus();
        });
    }

    // Close on click outside
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
}

/* ==========================================================================
   Hero Banner Carousel
   ========================================================================== */
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.querySelector('.slider-arrow-prev');
    const nextBtn = document.querySelector('.slider-arrow-next');

    if (!slides.length) return;

    let current = 0;
    let autoTimer;

    const goToSlide = (index) => {
        slides.forEach((s, i) => s.classList.toggle('active', i === index));
        dots.forEach((d, i) => d.classList.toggle('active', i === index));
        current = index;
    };

    const nextSlide = () => goToSlide((current + 1) % slides.length);
    const prevSlide = () => goToSlide((current - 1 + slides.length) % slides.length);

    if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); resetTimer(); });
    if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); resetTimer(); });

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => { goToSlide(idx); resetTimer(); });
    });

    const startTimer = () => { autoTimer = setInterval(nextSlide, 5000); };
    const resetTimer = () => { clearInterval(autoTimer); startTimer(); };

    startTimer();
}

/* ==========================================================================
   Deal of the Day Live Countdown Timer
   ========================================================================== */
function initCountdownTimer() {
    const hoursEl = document.getElementById('dealHours');
    const minsEl = document.getElementById('dealMins');
    const secsEl = document.getElementById('dealSecs');

    if (!hoursEl || !minsEl || !secsEl) return;

    // Set countdown to end of current day (midnight)
    const updateCountdown = () => {
        const now = new Date();
        const midnight = new Date();
        midnight.setHours(23, 59, 59, 999);

        const diff = midnight - now;
        if (diff <= 0) return;

        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        hoursEl.textContent = String(hours).padStart(2, '0');
        minsEl.textContent = String(minutes).padStart(2, '0');
        secsEl.textContent = String(seconds).padStart(2, '0');
    };

    updateCountdown();
    setInterval(updateCountdown, 1000);
}

/* ==========================================================================
   Mobile Off-Canvas Drawer Menu
   ========================================================================== */
function initMobileDrawer() {
    const menuBtn = document.getElementById('mobileMenuBtn');
    const drawer = document.getElementById('mobileDrawer');
    const overlay = document.getElementById('mobileDrawerOverlay');
    const closeBtn = document.getElementById('drawerCloseBtn');

    if (!menuBtn || !drawer || !overlay) return;

    const openDrawer = () => {
        drawer.style.transform = 'translateX(0)';
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    };

    const closeDrawer = () => {
        drawer.style.transform = 'translateX(-100%)';
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    };

    menuBtn.addEventListener('click', openDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    overlay.addEventListener('click', closeDrawer);
}

/* ==========================================================================
   AJAX Add to Cart
   ========================================================================== */
function quickAddToCart(productId, quantity = 1, buttonElement = null) {
    if (buttonElement) {
        const originalText = buttonElement.innerHTML;
        buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        buttonElement.disabled = true;

        fetch('ajax/add_to_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        })
        .then(res => res.json())
        .then(data => {
            buttonElement.innerHTML = originalText;
            buttonElement.disabled = false;

            if (data.success) {
                updateHeaderBadges(data.cart_count, null);
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Could not add product', 'error');
            }
        })
        .catch(() => {
            buttonElement.innerHTML = originalText;
            buttonElement.disabled = false;
            showToast('Network error while adding to cart.', 'error');
        });
    } else {
        fetch('ajax/add_to_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateHeaderBadges(data.cart_count, null);
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        });
    }
}

/* ==========================================================================
   AJAX Wishlist Toggle
   ========================================================================== */
function toggleWishlist(productId, btnElement = null) {
    fetch('ajax/add_to_wishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.require_login) {
            showToast('Please login to save items to your wishlist.', 'info');
            setTimeout(() => window.location.href = 'login.php', 1200);
            return;
        }

        if (data.success) {
            updateHeaderBadges(null, data.wishlist_count);
            if (btnElement) {
                btnElement.classList.toggle('active', data.in_wishlist);
            }
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => {
        showToast('Network error while updating wishlist.', 'error');
    });
}

/* ==========================================================================
   Header Counter Badges Updater
   ========================================================================== */
function updateHeaderBadges(cartCount, wishlistCount) {
    if (cartCount !== null && cartCount !== undefined) {
        const topCart = document.getElementById('headerCartBadge');
        const bottomCart = document.getElementById('bottomCartBadge');
        if (topCart) topCart.textContent = cartCount;
        if (bottomCart) bottomCart.textContent = cartCount;
    }
    if (wishlistCount !== null && wishlistCount !== undefined) {
        const topWish = document.getElementById('headerWishlistBadge');
        const bottomWish = document.getElementById('bottomWishlistBadge');
        if (topWish) topWish.textContent = wishlistCount;
        if (bottomWish) bottomWish.textContent = wishlistCount;
    }
}

/* ==========================================================================
   Quick View Modal
   ========================================================================== */
function openQuickView(productId) {
    const modal = document.getElementById('quickViewModal');
    const content = document.getElementById('quickViewContent');
    const closeBtn = document.getElementById('quickViewCloseBtn');

    if (!modal || !content) return;

    content.innerHTML = '<div style="padding: 40px; text-align: center;"><i class="fas fa-spinner fa-spin fa-2x"></i><p style="margin-top: 10px;">Loading product details...</p></div>';
    modal.style.display = 'flex';

    fetch(`ajax/quick_view.php?id=${productId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                content.innerHTML = `<div style="padding: 30px; text-align: center; color: #ef4444;">${data.message}</div>`;
                return;
            }
            const p = data.product;
            content.innerHTML = `
                <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 24px; padding: 24px;">
                    <div style="background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; padding: 20px;">
                        <img src="${p.image}" alt="${p.name}" style="max-height: 260px; object-fit: contain;">
                    </div>
                    <div>
                        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #2563eb;">${p.category_name}</span>
                        <h2 style="font-size: 20px; font-weight: 700; margin: 6px 0 10px; color: #0f172a;">${p.name}</h2>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                            <span class="star-badge-green"><i class="fas fa-star"></i> ${p.rating}</span>
                            <span style="font-size: 12px; color: #64748b;">(${p.reviews_count} reviews)</span>
                            <span style="font-size: 12px; font-weight: 600; color: ${p.in_stock ? '#10b981' : '#ef4444'};">
                                ${p.in_stock ? '&bull; In Stock (' + p.stock_quantity + ')' : '&bull; Out of Stock'}
                            </span>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 10px; margin-bottom: 14px;">
                            <span style="font-size: 24px; font-weight: 800; color: #0f172a;">$${p.effective_price}</span>
                            ${p.sale_price ? `<span style="font-size: 14px; text-decoration: line-through; color: #94a3b8;">$${p.price}</span>` : ''}
                            ${p.discount_pct > 0 ? `<span style="font-size: 12px; font-weight: 700; color: #10b981;">${p.discount_pct}% OFF</span>` : ''}
                        </div>
                        <p style="font-size: 13px; color: #475569; line-height: 1.5; margin-bottom: 18px;">${p.short_description}</p>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn btn-primary" onclick="quickAddToCart(${p.id}, 1, this); closeQuickView();" ${!p.in_stock ? 'disabled' : ''}>
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                            <a href="${p.url}" class="btn btn-outline">View Full Details</a>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(() => {
            content.innerHTML = '<div style="padding: 30px; text-align: center; color: #ef4444;">Error loading product details.</div>';
        });

    if (closeBtn) closeBtn.onclick = closeQuickView;
    modal.onclick = (e) => { if (e.target === modal) closeQuickView(); };
}

function closeQuickView() {
    const modal = document.getElementById('quickViewModal');
    if (modal) modal.style.display = 'none';
}

/* ==========================================================================
   Cart Quantity & AJAX Controls
   ========================================================================== */
function initCartQuantityControls() {
    // Quantity changes
    document.addEventListener('click', (e) => {
        if (e.target.matches('.cart-qty-plus') || e.target.closest('.cart-qty-plus')) {
            const btn = e.target.closest('.cart-qty-plus');
            const pid = btn.dataset.productId;
            const input = document.getElementById(`cartQty_${pid}`);
            if (input) {
                const newQty = parseInt(input.value) + 1;
                updateCartQuantityAjax(pid, newQty);
            }
        }
        if (e.target.matches('.cart-qty-minus') || e.target.closest('.cart-qty-minus')) {
            const btn = e.target.closest('.cart-qty-minus');
            const pid = btn.dataset.productId;
            const input = document.getElementById(`cartQty_${pid}`);
            if (input) {
                const newQty = Math.max(0, parseInt(input.value) - 1);
                updateCartQuantityAjax(pid, newQty);
            }
        }
    });
}

function updateCartQuantityAjax(productId, quantity) {
    fetch('ajax/update_cart_quantity.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: quantity })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            updateHeaderBadges(data.cart_count, null);
            // If on cart page, reload or update DOM elements
            if (window.location.pathname.includes('cart.php')) {
                window.location.reload();
            } else {
                showToast(data.message, 'success');
            }
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Error updating cart.', 'error'));
}

function removeCartItemAjax(productId) {
    fetch('ajax/remove_from_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            updateHeaderBadges(data.cart_count, null);
            if (window.location.pathname.includes('cart.php')) {
                window.location.reload();
            } else {
                showToast('Item removed from cart', 'info');
            }
        }
    });
}

function saveForLaterAjax(productId) {
    fetch('ajax/add_to_wishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.require_login) {
            showToast('Please sign in to save items for later.', 'info');
            setTimeout(() => window.location.href = 'login.php', 1200);
            return;
        }
        if (data.success) {
            updateHeaderBadges(null, data.wishlist_count);
            showToast('Item saved to your wishlist!', 'success');
            removeCartItemAjax(productId);
        } else {
            showToast(data.message || 'Could not save item.', 'error');
        }
    })
    .catch(() => showToast('Error saving item for later.', 'error'));
}

function clearCartAjax() {
    if (!confirm('Are you sure you want to clear your cart?')) return;
    fetch('ajax/clear_cart.php', { method: 'POST' })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateHeaderBadges(0, null);
                window.location.reload();
            }
        })
        .catch(() => showToast('Error clearing cart.', 'error'));
}

/* ==========================================================================
   Coupon Applicator AJAX
   ========================================================================== */
function applyCouponAjax() {
    const input = document.getElementById('couponCodeInput');
    if (!input) return;
    const code = input.value.trim();
    if (!code) {
        showToast('Please enter a valid coupon code.', 'error');
        return;
    }

    fetch('ajax/apply_coupon.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ coupon_code: code })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Error applying coupon.', 'error'));
}

function removeCouponAjax() {
    fetch('ajax/remove_coupon.php', { method: 'POST' })
        .then(res => res.json())
        .then(() => window.location.reload());
}

/* ==========================================================================
   Pincode Delivery Simulator
   ========================================================================== */
function checkDeliveryPincode() {
    const pin = document.getElementById('deliveryPincodeInput');
    const msg = document.getElementById('pincodeResultMsg');
    if (!pin || !msg) return;

    const val = pin.value.trim();
    if (val.length < 5) {
        msg.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-times-circle"></i> Please enter a valid 6-digit Pincode.</span>';
        return;
    }

    const today = new Date();
    const deliveryDate = new Date(today);
    deliveryDate.setDate(today.getDate() + 2);
    const dateStr = deliveryDate.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });

    msg.className = 'pincode-result-msg success';
    msg.innerHTML = `<i class="fas fa-check-circle"></i> Delivery available to <strong>${val}</strong> by <strong>${dateStr}</strong> &bull; Free Delivery.`;
}

function openPincodeModal() {
    const modal = document.getElementById('pincodeModal');
    const input = document.getElementById('modalPincodeInput');
    const saved = localStorage.getItem('shopeasy_pincode') || '400001';
    if (input) input.value = saved;
    if (modal) {
        modal.style.display = 'flex';
        if (input) input.focus();
    }
}

function closePincodeModal() {
    const modal = document.getElementById('pincodeModal');
    if (modal) modal.style.display = 'none';
}

function savePincodeModal() {
    const input = document.getElementById('modalPincodeInput');
    if (!input) return;
    const pin = input.value.trim();
    if (pin.length >= 5) {
        localStorage.setItem('shopeasy_pincode', pin);
        const text = document.getElementById('headerPincodeText');
        if (text) text.textContent = `Pincode ${pin}`;
        const deliveryInput = document.getElementById('deliveryPincodeInput');
        if (deliveryInput) deliveryInput.value = pin;
        closePincodeModal();
        showToast(`Delivery location set to ${pin}!`, 'success');
    } else {
        showToast('Please enter a valid 5 or 6 digit pincode.', 'error');
    }
}

// Restore saved pincode on page load
document.addEventListener('DOMContentLoaded', () => {
    const savedPin = localStorage.getItem('shopeasy_pincode');
    if (savedPin) {
        const text = document.getElementById('headerPincodeText');
        if (text) text.textContent = `Pincode ${savedPin}`;
        const deliveryInput = document.getElementById('deliveryPincodeInput');
        if (deliveryInput) deliveryInput.value = savedPin;
    }
    
    // Also close pincode modal when clicking backdrop
    const pModal = document.getElementById('pincodeModal');
    if (pModal) {
        pModal.addEventListener('click', (e) => {
            if (e.target === pModal) closePincodeModal();
        });
    }
});

function initGlobalToastListener() {}

/* ==========================================================================
   Theme Mode Manager (Dark / Light)
   ========================================================================== */
function getActiveTheme() {
    const saved = localStorage.getItem('shopeasy_theme');
    if (saved === 'dark' || saved === 'light') return saved;
    return (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
}

function setThemeMode(theme) {
    if (theme !== 'dark' && theme !== 'light') return;
    localStorage.setItem('shopeasy_theme', theme);
    document.documentElement.setAttribute('data-theme', theme);
    if (document.body) {
        document.body.classList.toggle('theme-dark', theme === 'dark');
        document.body.classList.toggle('theme-light', theme === 'light');
    }
    updateThemeUI(theme);
}

function toggleThemeMode() {
    const current = document.documentElement.getAttribute('data-theme') || getActiveTheme();
    const next = current === 'dark' ? 'light' : 'dark';
    setThemeMode(next);
    showToast(`Switched to ${next === 'dark' ? 'Dark' : 'Light'} Mode`, 'info');
}

function updateThemeUI(theme) {
    const isDark = theme === 'dark';

    // Segmented buttons
    document.querySelectorAll('.theme-btn-light').forEach(btn => {
        btn.classList.toggle('active', !isDark);
    });
    document.querySelectorAll('.theme-btn-dark').forEach(btn => {
        btn.classList.toggle('active', isDark);
    });

    // Toggle icons and labels
    document.querySelectorAll('.theme-toggle-icon').forEach(icon => {
        icon.className = isDark ? 'fas fa-sun theme-toggle-icon' : 'fas fa-moon theme-toggle-icon';
        icon.style.color = isDark ? '#fbbf24' : '';
    });
    document.querySelectorAll('#mainThemeLabel').forEach(label => {
        label.textContent = isDark ? 'Light' : 'Dark';
    });
}

function initThemeSwitcher() {
    const initialTheme = document.documentElement.getAttribute('data-theme') || getActiveTheme();
    setThemeMode(initialTheme);

    // Listen for OS theme change if user hasn't explicitly set a preference
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('shopeasy_theme')) {
                setThemeMode(e.matches ? 'dark' : 'light');
            }
        });
    }
}


