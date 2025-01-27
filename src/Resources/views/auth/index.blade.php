@extends('Vordia::layouts.app')

@section('content')
    <div class="col-6 offset-md-3 mt-5">
        <div class="card rounded-5 shadow-sm mt-5">
            <div class="card-body">
                <div class="mt-5">
                    <form class="mb-3" id="mobileForm">
                        <label for="mobileInput">شماره موبایل را وارد کنید :</label>
                        <input type="text" id="mobileInput" class="form-control rounded-5 text-center mt-3" placeholder="09---------">
                        <div id="mobileInputErrorText" class="text-danger mt-3">
                            <strong id="mobileInputErrorText"></strong>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-5 mt-2">دریافت کد تایید</button>
                        </div>
                    </form>
                </div>

                <form id="checkOTPForm">
                    <label for="checkOTPInput">رمز یکبار مصرف را وارد کنید :</label>
                    <input type="text" id="checkOTPInput" class="form-control rounded-5 text-center mt-3" placeholder="رمز یکبار مصرف">
                    <div id="checkOTPInputError" class="input-error-validation">
                        <strong id="checkOTPInputErrorText"></strong>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success rounded-5 mt-2">ورود به سایت</button>
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

        $('#mobileForm').submit(function(event){
            event.preventDefault();
            $.post("{{ url('/login') }}",
                {
                    '_token' : "{{ csrf_token() }}",
                    'mobile' : $('#mobileInput').val()

                } , function(response , status){
                    console.log(response , status);
                    loginToken = response.login_token;

                    $('#mobileForm').fadeOut();
                    $('#checkOTPForm').fadeIn();

                }).fail(function(response){
                console.log(response.responseJSON);
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

        $('#checkOTPForm').submit(function(event){
            event.preventDefault();

            $.post("{{ url('/check-otp') }}",
                {
                    '_token' : "{{ csrf_token() }}",
                    'otp' : $('#checkOTPInput').val(),
                    'login_token' : loginToken

                } , function(response , status){
                    console.log(response , status);
                    $(location).attr('href' , "{{ route('admin.index') }}");

                }).fail(function(response){
                console.log(response.responseJSON);
                $('#checkOTPInput').addClass('mb-1');
                $('#checkOTPInputError').fadeIn();
                $('#checkOTPInputErrorText').html(response.responseJSON.errors.otp[0]);
            })
        });
    </script>
@endsection

