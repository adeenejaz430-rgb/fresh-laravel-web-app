// // Checkout JavaScript - public/js/checkout.js

// document.addEventListener('DOMContentLoaded', function() {
//     // Initialize Stripe
//     const stripeKey = document.querySelector('meta[name="stripe-key"]');
//     if (!stripeKey) {
//         console.error('Stripe key not found in meta tags');
//         return;
//     }

//     const stripe = Stripe(stripeKey.content);
//     const elements = stripe.elements();
    
//     const cardElement = elements.create('card', {
//         style: {
//             base: {
//                 fontSize: '16px',
//                 color: '#424770',
//                 fontFamily: '"Inter", "Helvetica Neue", Helvetica, sans-serif',
//                 fontSmoothing: 'antialiased',
//                 '::placeholder': { color: '#aab7c4' }
//             },
//             invalid: { color: '#9e2146', iconColor: '#fa755a' }
//         },
//         hidePostalCode: true
//     });

//     // State management
//     let currentStep = 1;
//     let cartItems = [];
//     let cardComplete = false;
//     let isProcessing = false;
//     let currentUserId = null;

//     // Get user ID from meta tag if authenticated
//     const userIdMeta = document.querySelector('meta[name="user-id"]');
//     if (userIdMeta) {
//         currentUserId = parseInt(userIdMeta.content);
//     }

//     // Load cart from localStorage
//     function loadCart() {
//         const savedCart = localStorage.getItem('cart');
//         if (savedCart) {
//             try {
//                 cartItems = JSON.parse(savedCart);
//                 if (!Array.isArray(cartItems) || cartItems.length === 0) {
//                     redirectToHome();
//                     return;
//                 }
//                 renderCart();
//                 updateTotals();
//             } catch (error) {
//                 console.error('Error parsing cart:', error);
//                 redirectToHome();
//             }
//         } else {
//             redirectToHome();
//         }
//     }

//     function redirectToHome() {
//         window.location.href = '/';
//     }

//     // Render cart items in sidebar
//     function renderCart() {
//         const cartContainer = document.getElementById('cart-items');
//         if (!cartContainer) return;

//         cartContainer.innerHTML = '';

//         cartItems.forEach(item => {
//             const itemDiv = document.createElement('div');
//             itemDiv.className = 'flex py-3 border-b last:border-0';
            
//             const imageSrc = item.image || (item.images && item.images[0]) || '/images/placeholder.jpg';
            
//             itemDiv.innerHTML = `
//                 <div class="w-16 h-16 rounded-md overflow-hidden flex-shrink-0 bg-gray-100">
//                     <img src="${imageSrc}" 
//                          alt="${escapeHtml(item.name)}" 
//                          class="w-full h-full object-cover"
//                          onerror="this.src='/images/placeholder.jpg'">
//                 </div>
//                 <div class="ml-4 flex-1">
//                     <h4 class="font-medium text-gray-900 text-sm">${escapeHtml(item.name)}</h4>
//                     <div class="flex justify-between mt-1">
//                         <span class="text-sm text-gray-500">Qty: ${item.quantity}</span>
//                         <span class="font-medium text-sm">$${(item.price * item.quantity).toFixed(2)}</span>
//                     </div>
//                 </div>
//             `;
//             cartContainer.appendChild(itemDiv);
//         });
//     }

//     // Escape HTML to prevent XSS
//     function escapeHtml(text) {
//         const map = {
//             '&': '&amp;',
//             '<': '&lt;',
//             '>': '&gt;',
//             '"': '&quot;',
//             "'": '&#039;'
//         };
//         return text.replace(/[&<>"']/g, m => map[m]);
//     }

//     // Update price totals
//     function updateTotals() {
//         const subtotal = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
//         const total = subtotal;

//         const elements = {
//             'subtotal-sidebar': subtotal,
//             'total-sidebar': total,
//             'subtotal-payment': subtotal,
//             'total-payment': total
//         };

//         Object.entries(elements).forEach(([id, value]) => {
//             const element = document.getElementById(id);
//             if (element) {
//                 element.textContent = `$${value.toFixed(2)}`;
//             }
//         });

//         const itemsCountEl = document.getElementById('items-count-payment');
//         if (itemsCountEl) {
//             itemsCountEl.textContent = cartItems.length;
//         }
//     }

//     // Show/hide error message
//     function showError(message) {
//         const errorDiv = document.getElementById('error-message');
//         const errorText = document.getElementById('error-text');
        
//         if (errorDiv && errorText) {
//             errorText.textContent = message;
//             errorDiv.classList.remove('hidden');
            
//             // Auto-hide after 7 seconds
//             setTimeout(() => {
//                 errorDiv.classList.add('hidden');
//             }, 7000);

//             // Scroll to error
//             errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
//         }
//     }

//     // Hide error message
//     function hideError() {
//         const errorDiv = document.getElementById('error-message');
//         if (errorDiv) {
//             errorDiv.classList.add('hidden');
//         }
//     }

//     // Show success message
//     function showSuccess() {
//         const successDiv = document.getElementById('success-message');
//         if (successDiv) {
//             successDiv.classList.remove('hidden');
//         }
//     }

//     // Validate step 1 fields
//     function validateStep1() {
//         const fields = {
//             name: document.getElementById('name'),
//             email: document.getElementById('email'),
//             address: document.getElementById('address'),
//             city: document.getElementById('city'),
//             state: document.getElementById('state'),
//             zip: document.getElementById('zip')
//         };

//         // Check if all fields exist
//         for (const [key, field] of Object.entries(fields)) {
//             if (!field) {
//                 showError(`Form field ${key} not found`);
//                 return false;
//             }
//         }

//         const name = fields.name.value.trim();
//         const email = fields.email.value.trim();
//         const address = fields.address.value.trim();
//         const city = fields.city.value.trim();
//         const state = fields.state.value.trim();
//         const zip = fields.zip.value.trim();

//         if (!name) {
//             showError('Please enter your full name');
//             fields.name.focus();
//             return false;
//         }

//         if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
//             showError('Please enter a valid email address');
//             fields.email.focus();
//             return false;
//         }

//         if (!address || !city || !state || !zip) {
//             showError('Please fill in all address fields');
//             return false;
//         }

//         return true;
//     }

//     // Move to step 2 (Payment)
//     function goToStep2() {
//         currentStep = 2;
        
//         const step1 = document.getElementById('step-1');
//         const step2 = document.getElementById('step-2');
//         const backBtn = document.getElementById('back-btn');
//         const step1Icon = document.getElementById('step-1-icon');
//         const step2Icon = document.getElementById('step-2-icon');
//         const progressBar = document.getElementById('progress-bar');
//         const btnText = document.getElementById('btn-text');

//         if (step1) step1.classList.add('hidden');
//         if (step2) step2.classList.remove('hidden');
//         if (backBtn) backBtn.classList.remove('hidden');
        
//         // Update step indicators
//         if (step1Icon) {
//             step1Icon.className = 'w-10 h-10 rounded-full flex items-center justify-center bg-green-500 text-white transition-colors';
//             step1Icon.innerHTML = '<i class="fas fa-check"></i>';
//         }
        
//         if (step2Icon) {
//             step2Icon.className = 'w-10 h-10 rounded-full flex items-center justify-center bg-blue-600 text-white transition-colors';
//         }
        
//         // Update progress bar
//         if (progressBar) {
//             progressBar.style.width = '100%';
//         }
        
//         // Update button text
//         const total = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
//         if (btnText) {
//             btnText.textContent = `Pay $${total.toFixed(2)}`;
//         }
        
//         // Mount card element if not already mounted
//         if (!cardElement._parent) {
//             const cardElementContainer = document.getElementById('card-element');
//             if (cardElementContainer) {
//                 cardElement.mount('#card-element');
                
//                 cardElement.on('change', function(event) {
//                     cardComplete = event.complete;
//                     if (event.error) {
//                         showError(event.error.message);
//                     } else {
//                         hideError();
//                     }
//                 });
//             }
//         }

//         // Scroll to top
//         window.scrollTo({ top: 0, behavior: 'smooth' });
//     }

//     // Go back to step 1
//     function goToStep1() {
//         currentStep = 1;
        
//         const step1 = document.getElementById('step-1');
//         const step2 = document.getElementById('step-2');
//         const backBtn = document.getElementById('back-btn');
//         const step1Icon = document.getElementById('step-1-icon');
//         const step2Icon = document.getElementById('step-2-icon');
//         const progressBar = document.getElementById('progress-bar');
//         const btnText = document.getElementById('btn-text');

//         if (step1) step1.classList.remove('hidden');
//         if (step2) step2.classList.add('hidden');
//         if (backBtn) backBtn.classList.add('hidden');
        
//         // Update step indicators
//         if (step1Icon) {
//             step1Icon.className = 'w-10 h-10 rounded-full flex items-center justify-center bg-blue-600 text-white transition-colors';
//             step1Icon.innerHTML = '<i class="fas fa-user"></i>';
//         }
        
//         if (step2Icon) {
//             step2Icon.className = 'w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 text-gray-500 transition-colors';
//         }
        
//         // Update progress bar
//         if (progressBar) {
//             progressBar.style.width = '0%';
//         }
        
//         // Update button text
//         if (btnText) {
//             btnText.textContent = 'Continue';
//         }

//         hideError();
//         window.scrollTo({ top: 0, behavior: 'smooth' });
//     }

//     // Process payment
//     async function processPayment() {
//         if (isProcessing) return;
        
//         if (!cardComplete) {
//             showError('Please enter valid card details');
//             return;
//         }

//         isProcessing = true;
//         const submitBtn = document.getElementById('submit-btn');
        
//         if (submitBtn) {
//             submitBtn.disabled = true;
//             submitBtn.innerHTML = `
//                 <svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
//                     <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
//                     <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
//                 </svg>
//                 Processing...
//             `;
//         }

//         try {
//             // Get form data
//             const formData = {
//                 name: document.getElementById('name').value.trim(),
//                 email: document.getElementById('email').value.trim().toLowerCase(),
//                 address: document.getElementById('address').value.trim(),
//                 city: document.getElementById('city').value.trim(),
//                 state: document.getElementById('state').value.trim(),
//                 zip: document.getElementById('zip').value.trim(),
//                 country: document.getElementById('country').value
//             };

//             // Create payment intent
//             const response = await fetch('/api/create-payment-intent', {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
//                     'Accept': 'application/json'
//                 },
//                 body: JSON.stringify({
//                     items: cartItems.map(item => ({
//                         id: item.id,
//                         name: item.name,
//                         price: item.price,
//                         quantity: item.quantity
//                     })),
//                     customer: {
//                         name: formData.name,
//                         email: formData.email,
//                         address: {
//                             line1: formData.address,
//                             city: formData.city,
//                             state: formData.state,
//                             postal_code: formData.zip,
//                             country: formData.country
//                         }
//                     },
//                     user_id: currentUserId
//                 })
//             });

//             if (!response.ok) {
//                 const errorData = await response.json();
//                 throw new Error(errorData.error || 'Failed to create payment intent');
//             }

//             const { clientSecret, paymentIntentId } = await response.json();

//             // Confirm card payment
//             const { error: stripeError, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
//                 payment_method: {
//                     card: cardElement,
//                     billing_details: {
//                         name: formData.name,
//                         email: formData.email,
//                         address: {
//                             line1: formData.address,
//                             city: formData.city,
//                             state: formData.state,
//                             postal_code: formData.zip,
//                             country: formData.country
//                         }
//                     }
//                 }
//             });

//             if (stripeError) {
//                 throw new Error(stripeError.message);
//             }

//             if (paymentIntent.status === 'succeeded') {
//                 showSuccess();
                
//                 // Clear cart
//                 localStorage.removeItem('cart');
                
//                 // Save order details for success page
//                 const total = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
//                 sessionStorage.setItem('lastOrder', JSON.stringify({
//                     orderNumber: paymentIntent.id,
//                     date: new Date().toISOString(),
//                     total: total.toFixed(2),
//                     email: formData.email
//                 }));
                
//                 // Redirect to success page
//                 setTimeout(() => {
//                     window.location.href = '/checkout/success';
//                 }, 1500);
//             }

//         } catch (error) {
//             console.error('Payment error:', error);
//             showError(error.message || 'Payment failed. Please try again.');
            
//             if (submitBtn) {
//                 submitBtn.disabled = false;
//                 const total = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
//                 submitBtn.innerHTML = `
//                     <span id="btn-text">Pay $${total.toFixed(2)}</span>
//                     <i class="fas fa-chevron-right ml-1"></i>
//                 `;
//             }
//         } finally {
//             isProcessing = false;
//         }
//     }

//     // Form submit handler
//     const checkoutForm = document.getElementById('checkout-form');
//     if (checkoutForm) {
//         checkoutForm.addEventListener('submit', function(e) {
//             e.preventDefault();
            
//             if (currentStep === 1) {
//                 if (validateStep1()) {
//                     goToStep2();
//                 }
//             } else if (currentStep === 2) {
//                 processPayment();
//             }
//         });
//     }

//     // Back button handler
//     const backBtn = document.getElementById('back-btn');
//     if (backBtn) {
//         backBtn.addEventListener('click', function() {
//             goToStep1();
//         });
//     }

//     // Initialize
//     loadCart();
// });
// public/js/checkout.js

// public/js/checkout.js

document.addEventListener('DOMContentLoaded', () => {
    const stripeKeyMeta = document.querySelector('meta[name="stripe-key"]');
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');

    if (!stripeKeyMeta || !stripeKeyMeta.content) {
        console.error('Stripe publishable key not found in <meta name="stripe-key">');
        return;
    }

    const stripe   = Stripe(stripeKeyMeta.content);
    const elements = stripe.elements();

    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                fontFamily: '"Inter", "Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                '::placeholder': { color: '#aab7c4' }
            },
            invalid: { color: '#9e2146', iconColor: '#fa755a' }
        },
        hidePostalCode: true
    });

    let cardMounted  = false;
    let currentStep  = 1;
    let isProcessing = false;

    const form        = document.getElementById('checkout-form');
    const step1       = document.getElementById('step-1');
    const step2       = document.getElementById('step-2');
    const step1Icon   = document.getElementById('step-1-icon');
    const step2Icon   = document.getElementById('step-2-icon');
    const progressBar = document.getElementById('progress-bar');
    const backBtn     = document.getElementById('back-btn');
    const submitBtn   = document.getElementById('submit-btn');
    const btnText     = document.getElementById('btn-text');
    const errorBox    = document.getElementById('error-message');
    const errorText   = document.getElementById('error-text');
    const successBox  = document.getElementById('success-message');

    // user id (optional)
    let currentUserId = null;
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    if (userIdMeta && userIdMeta.content) {
        currentUserId = parseInt(userIdMeta.content);
    }

    // Build items array from blade cart (window.checkoutItems)
    const items = (window.checkoutItems || []).map(item => ({
        id: item.id,
        name: item.name,
        price: item.price,
        quantity: item.qty
    }));

    // ===== helpers =====
    function showError(message) {
        if (!errorBox || !errorText) return;
        errorText.textContent = message;
        errorBox.classList.remove('hidden');
        errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function hideError() {
        if (!errorBox) return;
        errorBox.classList.add('hidden');
    }

    function showSuccess() {
        if (!successBox) return;
        successBox.classList.remove('hidden');
        successBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function getTotalFromDom() {
        const totalEl = document.getElementById('total-payment');
        if (!totalEl) return 0;
        const text    = totalEl.textContent || '';
        const numeric = parseFloat(text.replace(/[^0-9.]/g, '')) || 0;
        return numeric;
    }

    // ===== step logic =====
    function validateStep1() {
        const name    = document.getElementById('name');
        const email   = document.getElementById('email');
        const address = document.getElementById('address');
        const city    = document.getElementById('city');
        const state   = document.getElementById('state');
        const zip     = document.getElementById('zip');

        if (!name || !email || !address || !city || !state || !zip) {
            showError('Some form fields are missing in the template.');
            return false;
        }

        if (!name.value.trim()) {
            showError('Please enter your full name.');
            name.focus();
            return false;
        }

        const emailValue = email.value.trim();
        if (!emailValue || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
            showError('Please enter a valid email address.');
            email.focus();
            return false;
        }

        if (!address.value.trim() || !city.value.trim() || !state.value.trim() || !zip.value.trim()) {
            showError('Please fill in all address fields.');
            return false;
        }

        if (!items.length) {
            showError('Your cart is empty.');
            return false;
        }

        const total = getTotalFromDom();
        if (!total || total <= 0) {
            showError('Your total amount is 0.');
            return false;
        }

        return true;
    }

    function goToStep2() {
        currentStep = 2;
        hideError();

        if (step1) step1.classList.add('hidden');
        if (step2) step2.classList.remove('hidden');
        if (backBtn) backBtn.classList.remove('hidden');

        if (step1Icon) {
            step1Icon.className = 'w-10 h-10 rounded-full flex items-center justify-center bg-green-500 text-white transition-colors';
            step1Icon.innerHTML = '<i class="fas fa-check"></i>';
        }

        if (step2Icon) {
            step2Icon.className = 'w-10 h-10 rounded-full flex items-center justify-center bg-blue-600 text-white transition-colors';
        }

        if (progressBar) {
            progressBar.style.width = '100%';
        }

        const total = getTotalFromDom();
        if (btnText) {
            btnText.textContent = total > 0 ? `Pay $${total.toFixed(2)}` : 'Pay now';
        }

        if (!cardMounted) {
            const cardContainer = document.getElementById('card-element');
            if (cardContainer) {
                cardElement.mount('#card-element');
                cardMounted = true;

                cardElement.on('change', event => {
                    if (event.error) {
                        showError(event.error.message);
                    } else {
                        hideError();
                    }
                });
            }
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function goToStep1() {
        currentStep = 1;
        hideError();

        if (step2) step2.classList.add('hidden');
        if (step1) step1.classList.remove('hidden');
        if (backBtn) backBtn.classList.add('hidden');

        if (step1Icon) {
            step1Icon.className = 'w-10 h-10 rounded-full flex items-center justify-center bg-blue-600 text-white transition-colors';
            step1Icon.innerHTML = '<i class="fas fa-user"></i>';
        }

        if (step2Icon) {
            step2Icon.className = 'w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 text-gray-500 transition-colors';
        }

        if (progressBar) {
            progressBar.style.width = '0%';
        }

        if (btnText) {
            btnText.textContent = 'Continue';
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    if (backBtn) {
        backBtn.addEventListener('click', e => {
            e.preventDefault();
            goToStep1();
        });
    }

    // ===== payment =====
    async function processPayment() {
        if (isProcessing) return;

        hideError();

        const total = getTotalFromDom();
        if (!total || total <= 0) {
            showError('Your total amount is 0.');
            return;
        }

        if (!cardMounted) {
            showError('Card element is not ready yet.');
            return;
        }

        isProcessing = true;

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            `;
        }

        try {
            const name    = document.getElementById('name').value.trim();
            const email   = document.getElementById('email').value.trim();
            const address = document.getElementById('address').value.trim();
            const city    = document.getElementById('city').value.trim();
            const state   = document.getElementById('state').value.trim();
            const zip     = document.getElementById('zip').value.trim();
            const country = document.getElementById('country').value;

            const response = await fetch('/create-payment-intent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    ...(csrfTokenMeta ? { 'X-CSRF-TOKEN': csrfTokenMeta.content } : {})
                },
                body: JSON.stringify({
                    items,
                    customer: {
                        name,
                        email,
                        address: {
                            line1: address,
                            city,
                            state,
                            postal_code: zip,
                            country
                        }
                    },
                    user_id: currentUserId
                })
            });

            if (!response.ok) {
                let msg = 'Failed to create payment intent.';
                try {
                    const data = await response.json();
                    if (data && data.error) msg = data.error;
                } catch (_) {}
                throw new Error(msg);
            }

            const data = await response.json();
            const clientSecret = data.clientSecret || data.client_secret;

            if (!clientSecret) {
                throw new Error('No client secret returned from server.');
            }

            const { error: stripeError, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name,
                        email,
                        address: {
                            line1: address,
                            city,
                            state,
                            postal_code: zip,
                            country
                        }
                    }
                }
            });

            if (stripeError) {
                throw new Error(stripeError.message);
            }

            if (paymentIntent && paymentIntent.status === 'succeeded') {
                // Show success banner
                showSuccess();

                // Save minimal order info for success page
                const totalAmount = (paymentIntent.amount / 100).toFixed(2);
                const lastOrder   = {
                    orderNumber: paymentIntent.id,
                    date: new Date().toISOString(),
                    total: totalAmount,
                    email: email
                };
                sessionStorage.setItem('lastOrder', JSON.stringify(lastOrder));

                // NEW: tell backend to create the order in DB (no webhooks)
                try {
                    await fetch('/sync-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            ...(csrfTokenMeta ? { 'X-CSRF-TOKEN': csrfTokenMeta.content } : {})
                        },
                        body: JSON.stringify({
                            payment_intent_id: paymentIntent.id
                        })
                    });
                } catch (syncErr) {
                    console.error('Failed to sync order:', syncErr);
                    // We still redirect; order creation error will be in logs
                }

                // Redirect to success page
                setTimeout(() => {
                    window.location.href = '/checkout/success';
                }, 1500);
            } else {
                throw new Error('Payment was not completed.');
            }
        } catch (err) {
            console.error(err);
            showError(err.message || 'Payment failed. Please try again.');

            if (submitBtn) {
                const totalNow = getTotalFromDom();
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <span id="btn-text">${ totalNow > 0 ? 'Pay $' + totalNow.toFixed(2) : 'Pay now' }</span>
                    <i class="fas fa-chevron-right ml-1"></i>
                `;
            }
        } finally {
            isProcessing = false;
        }
    }

    // ===== form submit =====
    if (form) {
        form.addEventListener('submit', e => {
            e.preventDefault();

            if (currentStep === 1) {
                if (validateStep1()) {
                    goToStep2();
                }
            } else if (currentStep === 2) {
                processPayment();
            }
        });
    }
});
