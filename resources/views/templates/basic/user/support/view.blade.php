@extends($activeTemplate . 'layouts.' . $layout)
@section('content')

    @guest

        <div class="blog-section pb-60">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                    @endguest
                    <div class="dashboard-area {{ auth()->check() ? 'mt-20' : '' }}">
                        <div class="panel-card-header card-header-bg d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="m-0 text-white">
                                @php echo $myTicket->statusBadge; @endphp
                                [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                            </h5>
                            @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                                <button class="btn btn-danger close-button btn-sm confirmationBtn" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}" type="button"><i class="fas fa-lg fa-times-circle"></i>
                                </button>
                            @endif
                        </div>
                        <div class="panel-card-body panel-body-top">
                            <form class="disableSubmission" method="post" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row justify-content-between">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea class="form-control form--control" name="message" rows="4" required>{{ old('message') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <button class="btn btn-dark btn-sm addAttachment my-2" type="button"> <i class="fas fa-plus"></i> @lang('Add Attachment') </button>
                                        <p class="mb-2"><span class="text--info text--small">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                        <div class="row fileUploadsContainer">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn--base w-100 my-2" type="submit"><i class="la la-fw la-lg la-reply"></i> @lang('Reply')
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="dashboard-area mt-4">
                        <div class="panel-card-body">
                            @forelse($messages as $message)
                                @if ($message->admin_id == 0)
                                    <div class="row border--base border-radius-3 mx-2 my-3 border py-3">
                                        <div class="col-md-3 border-end text-end">
                                            <h5 class="my-3 text-white">{{ $message->ticket->name }}</h5>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="fw-bold my-3 text-white">
                                                @lang('Posted on') {{ showDateTime($message->created_at, 'l, dS F Y @ h:i a') }}</p>
                                            <p class="text-white">{{ $message->message }}</p>
                                            @if ($message->attachments->count() > 0)
                                                <div class="mt-2 d-flex flex-wrap gap-3">
                                                    @foreach ($message->attachments as $k => $image)
                                                        <a class="text--base" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa-regular fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="row border-radius-3 reply-bg mx-2 my-3 py-3">
                                        <div class="col-md-3 border-end text-end">
                                            <h5 class="my-3 text-white">{{ $message->admin->name }}</h5>
                                            <p class="lead text-white">@lang('Staff')</p>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="fw-bold my-3 text-white">
                                                @lang('Posted on') {{ showDateTime($message->created_at, 'l, dS F Y @ h:i a') }}</p>
                                            <p class="text-white">{{ $message->message }}</p>
                                            @if ($message->attachments->count() > 0)
                                                <div class="mt-2 d-flex flex-wrap gap-3">
                                                    @foreach ($message->attachments as $k => $image)
                                                        <a class="text--base" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa-regular fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="empty-message text-center">
                                    <img src="{{ asset('assets/images/empty_list.png') }}" alt="empty">
                                    <h5 class="text-muted">@lang('No replies found here!')</h5>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @guest
                    </div>
                </div>
            </div>
        </div>
    @endguest

    <x-confirmation-modal :custom="true" />
@endsection
@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }

        .reply-bg {
            background-color: #f9dc8429;
            border: 1px solid #f9dc8429;
        }

        .empty-message img {
            width: 120px;
            margin-bottom: 15px;
        }

        .panel-body-top {
            border-top: none;
        }

        .panel-card-header h5 {
            line-height: 25px;
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
