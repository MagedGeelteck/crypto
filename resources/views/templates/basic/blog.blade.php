@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="blog-section pb-60">
        <div class="container">
            <div class="row mb-30-none">
                <div class="col-xl-12">
                    <div class="row">
                        @forelse($blogElements as $item)
                            <div class="col-lg-4 col-md-6 mb-30">
                                <div class="blog-item">
                                    <div class="blog-thumb">
                                        <a class="d-block" href="{{ route('blog.details', $item->slug) }}">
                                            <img src="{{ frontendImage('blog', 'thumb_' . @$item->data_values->image, '375x150') }}" alt="blog">
                                        </a>
                                    </div>
                                    <div class="blog-content">
                                        <span class="blog-date text-white"><i class="las la-calendar"></i> {{ showDateTime($item->created_at, 'M d, Y') }}</span>
                                        <h3 class="title text-white"><a href="{{ route('blog.details', $item->slug) }}">{{ strLimit(__(@$item->data_values->title), 80) }}</a></h3>
                                        <p>{{ strLimit(strip_tags(__(@$item->data_values->description_nic)), 200) }}</p>
                                        <div class="blog-btn">
                                            <a class="custom-btn" href="{{ route('blog.details', $item->slug) }}">@lang('Read More') <i class="las la-angle-double-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
            @if ($blogElements->hasPages())
            <div class="pagination-wrapper pt-5">
                {{ paginateLinks($blogElements) }}
            </div>
        @endif
        </div>
    </div>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection
