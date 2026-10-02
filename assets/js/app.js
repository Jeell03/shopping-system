/**
 * ShopEasy - app.js (Enhancement Layer)
 * Adds supplementary features on top of script.js.
 * Does NOT duplicate or override anything in script.js.
 */

document.addEventListener('DOMContentLoaded', () => {
    initAddToCartButtons();
    initWishlistButtons();
    updateCartBadge();
});

/* ==========================================================================
   Cart Count Badge (reads existing cart session)
   ========================================================================== */
function updateCartBadge() {
    // Cart count is already rendered server-side in header — nothing extra needed.
    // This is a placeholder for future AJAX badge refresh after actions.
}

/* ==========================================================================
   Add-To-Cart (delegates to existing ajax/add_to_cart.php)
   ========================================================================== */
function initAddToCartButtons() {
    document.body.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-action="add-to-cart"]');
        if (!btn) return;
        e.preventDefault();

        const productId = btn.dataset.id;
        if (!productId) return;

        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        fetch('ajax/add_to_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: parseInt(productId), quantity: 1 })
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            if (data.success) {
                showToast(data.message || 'Added to cart!', 'success');
                if (typeof updateHeaderBadges === 'function') {
                    updateHeaderBadges(data.cart_count, null);
                } else {
                    document.querySelectorAll('.cart-count-badge, #bottomCartBadge').forEach(el => {
                        el.textContent = data.cart_count;
                    });
                }
            } else {
                showToast(data.message || 'Could not add to cart.', 'error');
            }
        })
        .catch(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            showToast('Network error. Please try again.', 'error');
        });
    });
}

/* ==========================================================================
   Wishlist Toggle (delegates to existing ajax/add_to_wishlist.php)
   ========================================================================== */
function initWishlistButtons() {
    document.body.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-action="toggle-wishlist"]');
        if (!btn) return;
        e.preventDefault();

        const productId = btn.dataset.id;
        if (!productId) return;

        fetch('ajax/add_to_wishlist.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: parseInt(productId) })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('far');
                    icon.classList.toggle('fas');
                }
                if (typeof updateHeaderBadges === 'function') {
                    updateHeaderBadges(null, data.wishlist_count);
                }
                showToast(data.message || 'Wishlist updated!', 'success');
            } else {
                showToast(data.message || 'Login required.', 'error');
            }
        })
        .catch(() => showToast('Network error. Please try again.', 'error'));
    });
}
