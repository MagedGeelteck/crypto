@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="reset-area mt-30">
        <div class="panel-body section--bg border--base">
            <form class="register" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="profile-image">
                            <x-image-uploader class="w-100" type="userProfile" image="{{ auth()->user()->image }}" :required=false :profile=true />
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label">@lang('First Name')</label>
                        <input class="form-control form--control" name="firstname" type="text" value="{{ $user->firstname }}" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label">@lang('Last Name')</label>
                        <input class="form-control form--control" name="lastname" type="text" value="{{ $user->lastname }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label class="form-label">@lang('E-mail Address')</label>
                        <input class="form-control form--control" value="{{ $user->email }}" readonly>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label">@lang('Mobile Number')</label>
                        <input class="form-control form--control" value="{{ $user->mobile }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label class="form-label">@lang('Address')</label>
                        <input class="form-control form--control" name="address" type="text" value="{{ @$user->address }}">
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label">@lang('State')</label>
                        <input class="form-control form--control" name="state" type="text" value="{{ @$user->state }}">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-4">
                        <label class="form-label">@lang('Zip Code')</label>
                        <input class="form-control form--control" name="zip" type="text" value="{{ @$user->zip }}">
                    </div>

                    <div class="form-group col-sm-4">
                        <label class="form-label">@lang('City')</label>
                        <input class="form-control form--control" name="city" type="text" value="{{ @$user->city }}">
                    </div>

                    <div class="form-group col-sm-4">
                        <label class="form-label">@lang('Country')</label>
                        <input class="form-control form--control" value="{{ @$user->country_name }}" disabled>
                    </div>

                </div>

                <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>

            </form>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .profile-image {
            width: 150px;
            height: 150px;
            margin: 0 auto;
            margin-bottom: 30px;
        }

        .image-upload-preview {
            height: 150px;
            width: 150px;
            border-radius: 50%;
            box-shadow: none;
            border: 2px solid #e5e5e5;
            background-color: #fff;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        .profile-image .image-upload-wrapper {
            position: relative;
        }

        .profile-image .image-upload-input-wrapper label {
            border: 1px solid #e5e5e5;
            position: absolute;
            right: 12px;
            bottom: 1px;
            height: 30px;
            width: 30px;
            text-align: center;
            line-height: 30px;
            border-radius: 50%;
            background: #ee3d43 !important;
            cursor: pointer;
        }

        .profile-image .image-upload-input {
            display: none !important;
        }

        .profile-image .image--uploader small {
            display: none;
        }
    </style>
@endpush
