@if(!request()->routeIs('home'))
<div class="breadcrumb-area">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{__($pageTitle)}}</li>
            </ol>
        </nav>
    </div>
</div>
@endif