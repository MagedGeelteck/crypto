@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="blog-details-section blog-section pb-60">
        <div class="container">
            <div class="row mb-30-none">
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-xl-12 mb-30">
                            <div class="blog-item">
                                <div class="blog-thumb">
                                    <img src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->image, '1280x500') }}" alt="@lang('blog')">
                                </div>
                                <div class="blog-content">
                                    <span class="blog-date text-white"><i class="las la-calendar"></i> {{ showDateTime($blog->created_at, 'M d, Y') }}</span>
                                    <h3 class="title text-white">{{ __(@$blog->data_values->title) }}</h3>
                                    @php echo @$blog->data_values->description_nic @endphp
                                </div>
                            </div>
                            <div class="blog-social-area d-flex justify-content-between align-items-center flex-wrap">
                                <h3 class="title">@lang('Share This Post')</h3>
                                <ul class="blog-social">
                                    <li>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://x.com/intent/tweet?text={{ __($blog->data_values->title) }}&amp;url={{ urlencode(url()->current()) }}&amp;hashtags=YourHashtags&amp;via=YourTwitterHandle" target="_blank">
                                            <i class="fa-brands fa-x-twitter"></i>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __($blog->data_values->title) }}&amp;summary=@php echo strLimit(strip_tags($blog->data_values->description_nic),100) @endphp" target="_blank">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="https://www.instagram.com/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="fb-comments" data-href="{{ url()->current() }}" data-numposts="5"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 mb-30">
                    <div class="sidebar">
                        <div class="widget-box mb-30">
                            <h5 class="widget-title">@lang('Latest Posts')</h5>
                            <div class="popular-widget-box">
                                @forelse($blogElements as $item)
                                    <div class="single-popular-item d-flex align-items-center flex-wrap">
                                        <div class="popular-item-thumb">
                                            <img src="{{ frontendImage('blog', 'thumb_' . @$item->data_values->image, '375x150') }}" alt="blog">
                                        </div>
                                        <div class="popular-item-content">
                                            <h5 class="title"><a href="{{ route('blog.details', $item->slug) }}">{{ strLimit(__(@$item->data_values->title), 30) }}</a></h5>
                                            <span class="blog-date">{{ showDateTime($blog->created_at, 'M d, Y') }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="single-popular-item d-flex align-items-center flex-wrap">

                                        <div class="popular-item-content">
                                            <h5 class="title">@lang('No blog found')</h5>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush
