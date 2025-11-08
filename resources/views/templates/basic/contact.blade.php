@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $contactContent = getContent('contact_us.content', true);
        $contactElement = getContent('contact_us.element');
    @endphp

    <div class="contact-section py-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="mb-3">@lang('Open a Support Ticket')</h3>
                            @auth
                                <form class="disableSubmission" action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">@lang('Username')</label>
                                            <input class="form-control" value="{{ auth()->user()->username }}" readonly>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">@lang('Subject')</label>
                                            <input class="form-control" name="subject" type="text" value="{{ old('subject') }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">@lang('Priority')</label>
                                            <select class="form-select" name="priority" required data-minimum-results-for-search="-1">
                                                <option value="3">@lang('High')</option>
                                                <option value="2">@lang('Medium')</option>
                                                <option value="1">@lang('Low')</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">@lang('Message')</label>
                                            <textarea class="form-control" name="message" rows="6" required>{{ old('message') }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-sm btn-outline-secondary addAttachment mb-2">
                                                <i class="fas fa-plus"></i> @lang('Add Attachment')
                                            </button>
                                            <p class="text-muted mb-2"><small>@lang('Max 5 files | Max size ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed: .jpg, .jpeg, .png, .pdf, .doc, .docx')</small></p>
                                            <div class="row fileUploadsContainer"></div>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button class="btn btn--base" type="submit"><i class="las la-paper-plane"></i> @lang('Submit Ticket')</button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="py-4 text-center">
                                    <h5 class="mb-3">@lang('You need to log in to open a support ticket')</h5>
                                    <a class="btn btn--base" href="{{ route('user.login') }}">@lang('Login')</a>
                                    <a class="btn btn-outline--base ms-2" href="{{ route('user.register') }}">@lang('Register')</a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection

@push('script')
    <script>
        (function($){
            "use strict";
            let fileAdded = 0;
            $('.addAttachment').on('click', function(){
                fileAdded++;
                if(fileAdded === 5){
                    $(this).attr('disabled', true);
                }
                $('.fileUploadsContainer').append(`
                    <div class="col-md-6 removeFileInput">
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text btn btn-danger removeFile"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `);
            });
            $(document).on('click','.removeFile',function(){
                fileAdded--;
                $('.addAttachment').removeAttr('disabled');
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush
