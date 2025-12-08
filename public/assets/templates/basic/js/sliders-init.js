/**
 * Centralized slider & page JS initializations.
 * Goal: remove inline <script> blocks and enable stricter CSP.
 */
(function(){
	// Swiper-based sliders
	if (typeof Swiper !== 'undefined') {
		if (window.SLIDER_BANNER) {
			new Swiper('.banner-slider', {
				slidesPerView: 1,
				spaceBetween: 0,
				effect: 'fade',
				loop: true,
				pagination: { el: '.swiper-pagination', clickable: true },
				autoplay: { speed: 1000, delay: 3000 },
				speed: 1000
			});
		}
		if (window.SLIDER_BRAND) {
			new Swiper('.brand-slider-two', {
				slidesPerView: 6,
				spaceBetween: 16,
				loop: true,
				autoplay: { speed: 1000, delay: 3000 },
				speed: 800,
				breakpoints: {1400:{slidesPerView:8},1200:{slidesPerView:7},992:{slidesPerView:6},768:{slidesPerView:5},576:{slidesPerView:4},0:{slidesPerView:3}}
			});
			new Swiper('.brand-slider', {
				slidesPerView: 6,
				spaceBetween: 16,
				loop: true,
				autoplay: { speed: 1000, delay: 3000 },
				speed: 800,
				breakpoints: {1400:{slidesPerView:8},1200:{slidesPerView:7},992:{slidesPerView:6},768:{slidesPerView:5},576:{slidesPerView:4},0:{slidesPerView:3}}
			});
		}
		if (window.SLIDER_TESTIMONIAL) {
			new Swiper('.client-slider', {
				slidesPerView: 1,
				spaceBetween: 30,
				loop: true,
				autoplay: { speed: 1000, delay: 3000 },
				speed: 1000
			});
		}
		if (window.SLIDER_INFO) {
			new Swiper('.info-slider', {
				slidesPerView: 3,
				spaceBetween: 0,
				loop: true,
				autoplay: { speed: 1000, delay: 3000 },
				speed: 1000,
				breakpoints: {991:{slidesPerView:2},767:{slidesPerView:1},575:{slidesPerView:1}}
			});
		}
		if (window.SLIDER_PRODUCT_SINGLE) {
			new Swiper('.product-single-slider', {
				slidesPerView: 4,
				spaceBetween: 10,
				loop: false,
				pagination: { el: '.swiper-pagination', clickable: true },
				speed: 1000,
				breakpoints: {991:{slidesPerView:4},767:{slidesPerView:4},575:{slidesPerView:3}}
			});
		}
		if (window.SLIDER_PRODUCT_RELATED) {
			new Swiper('.product-slider-two', {
				slidesPerView: 4,
				spaceBetween: 30,
				loop: false,
				pagination: { el: '.swiper-pagination', clickable: true },
				autoplay: { speed: 1000, delay: 3000 },
				speed: 1000,
				breakpoints: {991:{slidesPerView:2},767:{slidesPerView:1},575:{slidesPerView:1}}
			});
		}
		if (window.SLIDER_HOME_PRODUCTS) {
			new Swiper('.product-slider', {
				slidesPerView: 3,
				spaceBetween: 30,
				loop: false,
				autoplay: { speed: 1000, delay: 3000 },
				speed: 1000,
				breakpoints: {991:{slidesPerView:2},767:{slidesPerView:2},575:{slidesPerView:1}}
			});
		}
		if (window.SLIDER_HOME_WIDGET) {
			new Swiper('.widget-slider', {
				slidesPerView: 1,
				spaceBetween: 0,
				loop: true,
				autoplay: { speed: 1000, delay: 3000 },
				speed: 1000
			});
		}
	}

	// Product details helpers (xzoom, load more ratings, add to cart)
	if (window.PRODUCT_DETAILS_HELPERS) {
		var cfg = window.PRODUCT_DETAILS_HELPERS;
		var pid = cfg.productId;
		var csrf = cfg.csrf;

		// xzoom init (requires jQuery)
		if (typeof jQuery !== 'undefined' && jQuery.fn && jQuery.fn.xzoom) {
			try {
				jQuery('.xzoom, .xzoom5').xzoom({ tint:'#333', Xoffset:15 });
			} catch(e) {}
		}

		var loadMoreBtn = document.getElementById('loadMoreBtn');
		if (loadMoreBtn && cfg.loadMoreUrl) {
			var counter = 5;
			loadMoreBtn.addEventListener('click', function(){
				var url = cfg.loadMoreUrl + '?count=' + counter + '&id=' + encodeURIComponent(pid);
				fetch(url, { headers: { 'Accept':'application/json' }})
					.then(r => r.json())
					.then(response => {
						if (response.ratings && response.ratings.length < 5) {
							loadMoreBtn.remove();
						}
						if (response.html) {
							var list = document.querySelector('.comment-list');
							if (list) list.insertAdjacentHTML('beforeend', response.html);
						}
						counter += 5;
					});
			});
		}

		var addCartBtn = document.querySelector('.productAddtocart');
		if (addCartBtn && cfg.addToCartUrl) {
			addCartBtn.addEventListener('click', function(){
				var qtyInput = document.getElementById('product-qty');
				var qty = qtyInput ? qtyInput.value : 1;
				fetch(cfg.addToCartUrl, {
					method: 'POST',
					headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
					body: JSON.stringify({product_id: pid, qty: qty})
				}).then(r=>r.json()).then(response => {
					if(response.success){
						if (typeof notify === 'function') notify('success', response.success);
						// Redirect to checkout page immediately
						if (cfg.cartUrl) {
							window.location.href = cfg.cartUrl;
						}
					}else{
						if (typeof notify === 'function') notify('error', response.error);
					}
				}).catch(error => {
					console.error('Add to cart error:', error);
					if (typeof notify === 'function') notify('error', 'Failed to add product. Please try again.');
				});
			});
		}
	}

	// Subscribe form (home page)
	if (window.SUBSCRIBE_ENDPOINT) {
		var subForm = document.querySelector('.subscribe-form');
		if (subForm) {
			subForm.addEventListener('submit', function(e){
				e.preventDefault();
				var emailInput = document.getElementById('subscriber');
				if(!emailInput || !emailInput.value){
					if (typeof notify === 'function') notify('error', 'The email field is required');
					return;
				}
				fetch(window.SUBSCRIBE_ENDPOINT.url, {
					method: 'POST',
					headers: {'Content-Type':'application/json','X-CSRF-TOKEN':window.SUBSCRIBE_ENDPOINT.csrf,'Accept':'application/json'},
					body: JSON.stringify({email: emailInput.value})
				}).then(r=>r.json()).then(response => {
					if(response.success){
						if (typeof notify === 'function') notify('success', response.success);
						emailInput.value='';
					}else{
						if (typeof notify === 'function') notify('error', response.error);
					}
				});
			});
		}
	}
})();
