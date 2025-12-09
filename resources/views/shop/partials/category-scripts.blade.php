{{-- resources/views/shop/partials/category-scripts.blade.php --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to Cart with AJAX
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const originalContent = button.innerHTML;
            
            // Check if user is authenticated
            @guest
                showToast('Please sign in to add items to cart', 'info');
                setTimeout(() => {
                    window.location.href = '{{ route("login") }}';
                }, 1500);
                return;
            @endguest
            
            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = '⏳';
            
            // Make AJAX request
            fetch(`/cart/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ quantity: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.innerHTML = '✓';
                    updateCartCount();
                    showToast(`${productName} added to cart!`, 'success');
                    
                    setTimeout(() => {
                        if (typeof openCartSidebar === 'function') {
                            openCartSidebar();
                        }
                    }, 500);
                    
                    setTimeout(() => {
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    }, 2000);
                } else {
                    showToast(data.message || 'Failed to add product', 'error');
                    button.innerHTML = originalContent;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
                button.innerHTML = originalContent;
                button.disabled = false;
            });
        });
    });

    // Wishlist AJAX
    @auth
    const wishlistForms = document.querySelectorAll('.wishlist-form');
    wishlistForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button');
            const productId = this.dataset.productId;
            const url = this.action;
            const methodInput = this.querySelector('input[name="_method"]');
            const method = methodInput ? 'DELETE' : 'POST';
            const token = this.querySelector('input[name="_token"]').value;

            btn.disabled = true;
            const originalText = btn.textContent;
            btn.textContent = '⏳';

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ _method: method })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    if(method === 'POST') {
                        btn.textContent = '❤️';
                        btn.classList.remove('bg-pink-100', 'text-pink-500', 'hover:bg-pink-200');
                        btn.classList.add('bg-red-500', 'text-white');
                        if(!methodInput) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = '_method';
                            input.value = 'DELETE';
                            this.appendChild(input);
                        }
                        this.action = `/wishlist/${productId}`;
                    } else {
                        btn.textContent = '🤍';
                        btn.classList.remove('bg-red-500', 'text-white');
                        btn.classList.add('bg-pink-100', 'text-pink-500', 'hover:bg-pink-200');
                        if(methodInput) methodInput.remove();
                        this.action = `/wishlist/${productId}`;
                    }
                    showToast(data.message || 'Wishlist updated!', 'success');
                } else {
                    btn.textContent = originalText;
                    showToast(data.message || 'Something went wrong!', 'error');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                btn.textContent = originalText;
                showToast('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                btn.disabled = false;
            });
        });
    });
    @endauth

    // Update cart count
    function updateCartCount() {
        fetch('/cart/count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const badges = document.querySelectorAll('.cart-count-badge, #cartItemCount');
            badges.forEach(badge => {
                if (badge) {
                    badge.textContent = data.count || 0;
                    if (data.count > 0) {
                        badge.classList.remove('hidden');
                    }
                }
            });
        })
        .catch(error => console.error('Error:', error));
    }

    // Toast notifications
    function showToast(message, type = 'success') {
        const existingToasts = document.querySelectorAll('.toast-notification');
        existingToasts.forEach(toast => toast.remove());
        
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500'
        };
        
        const icons = {
            success: '✓',
            error: '✗',
            info: 'ℹ'
        };
        
        const toast = document.createElement('div');
        toast.className = `toast-notification fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center gap-3`;
        toast.innerHTML = `
            <span class="text-2xl font-bold">${icons[type]}</span>
            <span class="font-semibold">${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});
</script>