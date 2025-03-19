@extends('Vordia::layouts.app')

@section('title')
    - ورود به سایت
@endsection

@section('content')
    <div class="col-md-4 offset-md-4 mt-5">
        <div class="card rounded-5 shadow-sm mt-5">
            <div class="card-body">
                <div class="text-center">
                    <i class="fa-duotone fa-solid fa-message-sms fa-4x"></i>
                </div>
                <div class="mt-3">

                    <form class="mb-3" id="mobileForm">
                        <label for="mobileInput">شماره موبایل را وارد کنید :</label>
                        <div class="row">
                            <div class="col-9 col-md-10">
                                <input type="text" id="mobileInput" class="form-control rounded-5 text-center mt-2"
                                       placeholder="---------09">
                            </div>
                            <div class="col-3 col-md-2">
                                <div class="card mt-2 rounded-5 text-center code">
                                    98+
                                </div>
                            </div>
                        </div>
                        <div id="mobileInputErrorText" class="text-danger mt-3">
                            <strong id="mobileInputErrorText"></strong>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-5 mt-2">دریافت کد تایید</button>
                        </div>
                    </form>

                </div>

                <form id="checkOTPForm">
                    <label for="checkOTPInput">رمز یک بار مصرف را وارد کنید :</label>
                    <input type="text" id="checkOTPInput" class="form-control rounded-5 text-center mt-3"
                           placeholder="رمز یک بار مصرف">
                    <div id="checkOTPInputError" class="input-error-validation">
                        <strong id="checkOTPInputErrorText"></strong>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success rounded-5 mt-2">ورود به سایت</button>
                        <div>
                            <button class="btn btn-secondary btn-sm rounded-5" id="resendOTP" type="submit">ارسال مجدد
                            </button>
                            <span id="Time" class="fw-bold"></span>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let loginToken;
        $('#checkOTPForm').hide();

        $('#mobileForm').submit(function (event) {
            event.preventDefault();
            $.post("{{ url('/login') }}",
                {
                    '_token': "{{ csrf_token() }}",
                    'mobile': $('#mobileInput').val()

                }, function (response) {
                    loginToken = response.login_token;

                    $('#mobileForm').fadeOut();
                    $('#checkOTPForm').fadeIn();

                    toastr.success('کد تاییدیه به شماره موبایل شما ارسال شد!');
                }).fail(function (response) {
                if (response.responseJSON && response.responseJSON.errors && response.responseJSON.errors.mobile) {
                    $('#mobileInput').addClass('mb-1');
                    $('#mobileInputError').fadeIn();
                    $('#mobileInputErrorText').html(response.responseJSON.errors.mobile[0]);

                } else {
                    $('#mobileInputErrorText').html('An error occurred. Please try again.');
                    $('#mobileInputError').fadeIn();
                }
            });
        });

        $('#checkOTPForm').submit(function (event) {
            event.preventDefault();
            $.post("{{ url('/checkOTP') }}",
                {
                    '_token': "{{ csrf_token() }}",
                    'otp': $('#checkOTPInput').val(),
                    'login_token': loginToken

                }, function () {
                    $(location).attr('href', "{{ route('admin.index') }}");
                    toastr.success('با موفقیت تایید شد!');
                }).fail(function (response) {
                $('#checkOTPInput').addClass('mb-1');
                $('#checkOTPInputError').fadeIn();
                $('#checkOTPInputErrorText').html(response.responseJSON.errors.otp[0]);
            })
        });

        $('#resendOTP').click(function (e) {

            e.preventDefault();

            $.post("{{ url('/resendOTP') }}",
                {
                    '_token': "{{ csrf_token() }}",
                    'login_token': loginToken

                }, function (response) {
                    loginToken = response.login_token;
                    toastr.success('رمز یک بار مصرف به شماره موبایل شما ارسال شد!');

                    $('#resendOTP').fadeOut();
                    timer();
                    $('#Time').fadeIn();

                }).fail(function () {
                toastr.error('مشکل در ارسال دوباره رمز یکبار مصرف، مجددا تلاش کنید');
            })
        });

        function timer() {
            let time = "1:01";
            let interval = setInterval(function () {
                let countdown = time.split(':');
                let minutes = parseInt(countdown[0], 10);
                let seconds = parseInt(countdown[1], 10);
                --seconds;
                minutes = (seconds < 0) ? --minutes : minutes;
                if (minutes < 0) {
                    clearInterval(interval);
                    $('#Time').hide();
                    $('#resendOTP').fadeIn();
                }

                seconds = (seconds < 0) ? 59 : seconds;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                $('#Time').html(minutes + ':' + seconds);
                time = minutes + ':' + seconds;
            }, 1000);
        }
    </script>
@endsection

