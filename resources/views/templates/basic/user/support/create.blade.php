@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="mt-30">
        <div class="text-end">
            <a class="btn btn-sm btn--base" href="{{ route('ticket.index') }}">@lang('My Support Ticket')</a>
        </div>
        <div class="card custom--card mt-20">
            <div class="card-body">
                <form class="disableSubmission" action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-label">@lang('Subject')</label>
                            <input class="form-control form--control" name="subject" type="text" value="{{ old('subject') }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">@lang('Priority')</label>
                            <select class="form-select form--control select2" name="priority" data-minimum-results-for-search="-1" required>
                                <option value="3">@lang('High')</option>
                                <option value="2">@lang('Medium')</option>
                                <option value="1">@lang('Low')</option>
                            </select>
                        </div>
                        <div class="col-12 form-group">
                            <label class="form-label">@lang('Message')</label>
                            <textarea class="form-control form--control" id="inputMessage" name="message" rows="6" required>{{ old('message') }}</textarea>
                        </div>

                        <div class="col-md-9">
                            <button class="btn btn-dark btn-sm addAttachment my-2" type="button"> <i class="fas fa-plus"></i> @lang('Add Attachment') </button>
                            <p class="mb-2 text--small"><span class="text--info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                            <div class="row fileUploadsContainer">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn--base w-100 my-2" type="submit"><i class="las la-paper-plane"></i> @lang('Submit')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-6 col-md-12 removeFileInput">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control form--control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile bg--danger border--danger"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `)
            });
            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush
