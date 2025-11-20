<div class="modal fade" id="quickView">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content py-4">
            <button class="close modal-close-btn" data-bs-dismiss="modal" type="button" aria-label="Close">
                <span aria-hidden="true"><i class="las la-times"></i></span>
            </button>
            <div class="modal-body">
                <div class="product-single-container">
                    <div class="row mb-30-none">
                        <div class="col-xl-5 col-md-6 mb-30">
                            <div class="product-container">
                                <img class="manage-preview" alt="image">
                            </div>
                        </div>
                        <div class="col-xl-7 col-md-6 mb-30">
                            <div class="product-details-content">
                                <h2 class="product-title product-name text-white"></h2>
                                {{-- ratings removed from quick view modal --}}
                                <hr class="short-divider">
                                <div class="price-box">
                                    <span class="product-price"></span>
                                </div>
                                <div class="product-desc">
                                    <p class="product-description">
                                    </p>
                                </div>
                                <form>
                                    <input class="product-id" name="product_id" type="hidden">
                                    <div class="product-action d-flex align-items-center flex-wrap">
                                        <div class="product-quantity">
                                            <div class="product-plus-minus">
                                                <div class="dec qtybutton">-</div>
                                                <input class="product-plus-minus-box product-qty integer-validation" name="qty" type="text" value="1">
                                                <div class="inc qtybutton">+</div>
                                            </div>
                                        </div>
                                        <button class="btn--base add-cart icon-shopping-cart addtocart" type="button" title="Add to Cart">@lang('Add to Cart')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        (function($) {
            "use strict";

            $(document).on('click', '.quick-view', function() {
                var modal = $('#quickView');
                var resource = $(this).data('resource');
                modal.find('.product-name').text(resource.name);
                modal.find('.product-price').text("{{ gs('cur_sym') }}" + parseFloat(resource.new_price).toFixed(8));
                modal.find('.product-description').text(resource.short_description);
                modal.find('.manage-preview').attr("src", $(this).data('image'));
                modal.find('.product-id').val(resource.id);
                // ratings removed from quick view modal

                modal.modal('show');
            });   

            $(document).on("keypress", ".integer-validation", (function(e) {
                var t = e.charCode ? e.charCode : e.keyCode;
                if (t != 13 && 8 != t && (t < 2534 || t > 2543) && (t < 48 || t > 57)) return !1
            }))

        })(jQuery);
    </script>
@endpush