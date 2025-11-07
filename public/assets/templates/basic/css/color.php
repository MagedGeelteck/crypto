<?php

header("Content-Type:text/css");
$color1 = $_GET['color']; // Change your Color Here

function checkhexcolor($color1){
    return preg_match('/^#[a-f0-9]{6}$/i', $color1);
}

if (isset($_GET['color']) AND $_GET['color'] != '') {
    $color1 = "#" . $_GET['color'];
}

if (!$color1 OR !checkhexcolor($color1)) {
    $color1 = "#336699";
}

?>

.custom-btn, .custom-btn:hover, .checkbox-wrapper .checkbox-item label a, .forgot-password a, .navbar-toggler span, .footer-bottom-area .copyright-area p a, .nav-tabs .nav-item .nav-link.active, .product-details-content .product-desc a, .product-single-tab .nav-tabs .nav-item .nav-link.active, .product-desc-content ul li i, .product-reviews-content .comment-box .ratings-container i, .contact-info-icon i, .blog-content .title a:hover, .category-content li:hover, .starrr a {
  color: <?= $color1 ?>;
}

.text--base{
  color: <?= $color1 ?> !important;
} 

.sidebar-home .widget, .side-menu-wrapper, .widget-box{
  box-shadow: 0px 0px 7px 0 <?= $color1 ?>70;
}

.scrollToTop, .pagination .page-item.active .page-link, .pagination .page-item:hover .page-link, .badge-circle, footer-bottom-area::after, .side-menu-title, .widget-range-title, .pagination .page-item.disabled span {
  background: <?= $color1 ?>;
}

.custom-table thead tr, *::-webkit-scrollbar-button, *::-webkit-scrollbar-thumb, .slider-next, .slider-prev, .tab-menu .nav-item.active, .btn--base, .submit-btn, .surface .coin, ::selection, .cart-dropdown .btn-remove, .header-bottom-area .navbar-collapse .main-menu li .sub-menu li::before, .tip-hot, .footer-social li:hover, .footer-ribon, .info-icon , .widget .ui-slider-range, .widget .ui-state-default, .account-tab .nav-tabs .nav-item .nav-link, .product-default .product-label.label-sale, .account-area .account-close, .blog-social-area .blog-social li:hover, .tag-item-wrapper .tag-item:hover, .input-group-text, .footer-bottom-area::after, .custom-check-group input:checked + label::before {
  background-color: <?= $color1 ?>;
}

.bg--base {
  background-color: <?= $color1 ?> !important;
}

.product-single-filter .config-size-list li.active a {
  border: 1px solid <?= $color1 ?>;
}

.tab-menu .nav-item.active, .profile-thumb-area .image-preview {
  border: 2px solid <?= $color1 ?>;
}

.section-header .section-title {
  border-bottom: 2px solid <?= $color1 ?>;
}

.pagination .page-item.active .page-link, .pagination .page-item:hover .page-link, .nav-tabs .nav-item .nav-link.active, .product-single-tab .nav-tabs .nav-item .nav-link.active  {
  border-color: <?= $color1 ?>;
}

.tip-hot:not(.tip-top):before {
  border-right-color: <?= $color1 ?>;
}

.footer-ribon::before {
  border-right: 15px solid <?= $color1 ?>;
}

.add-cart-box {
  border-top: 4px solid <?= $color1 ?>;
}

.account-area {
  border-top: 5px solid <?= $color1 ?>;
  border-bottom: 5px solid <?= $color1 ?>;
}

.nav-tabs .nav-item .nav-link.active{
  color:  <?= $color1 ?>;
}

.btn--base{
  box-shadow: 0px 0px 7px 0 <?= $color1 ?>70;
}

.custom-btn{
  color : <?= $color1 ?>;
}

.custom-btn:hover{
  color : <?= $color1 ?>;
}

.info-icon{
  box-shadow: 0px 0px 7px 0 <?= $color1 ?>70;
}

.blog-content .title a:hover{
  color : <?= $color1 ?>;
}

.contact-info-icon i{
  color : <?= $color1 ?>;
}
.submit-btn{
  box-shadow:0px 0px 7px 0 <?= $color1 ?>70;
}

.page-item.disabled span{
  background: <?= $color1 ?>6b !important;
  border-color: <?= $color1 ?>6b !important;
}

.btn--base:focus, .btn--base:hover {
  background :  <?= $color1 ?>d9;
}
.dropdown-list>.dropdown-list__item:hover {
    background-color: <?= $color1 ?> !important;
}
@media only screen and (max-width: 991px) {
  .custom-table tbody tr td::before {
    color: <?= $color1 ?>;
  }
}

.forgot-pass{
  color: <?= $color1 ?>;
}
.forgot-pass:hover{
  color: <?= $color1 ?>;
}
.any-account a{
  color: <?= $color1 ?>;
}

.select2-results__option.select2-results__option--selected,
.select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
  background-color: <?= $color1 ?> !important;

}

.verification-code-wrapper a{
  color: <?= $color1 ?>;
}

.profile-image .image-upload-input-wrapper label {
  background: <?= $color1 ?> !important;
}

.btn--base.active {
  background-color: <?= $color1 ?>;
}

.xzoom-gallery5.xactive{
  border: 1px solid <?= $color1 ?>;
}

.btn--base:focus,
.btn--base:hover,
.btn--base:active {
    background: <?= $color1 ?>cc !important;
}

.verification-code span {
    border: 1px solid <?= $color1 ?>39 !important;
    color: <?= $color1 ?>;
}