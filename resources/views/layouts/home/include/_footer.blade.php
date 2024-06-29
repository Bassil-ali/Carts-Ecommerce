        <footer id="footer" style="padding-top: 100px">
            <div class="top-ft">
                <div class="container">
                    <ul class="menu-ft">
                        <li><a href="{{ route('ShowUsagePolicy') }}">@lang('home.usage_policy')</a></li>
                        <li><a href="{{ route('showPrivacyPolicy') }}">@lang('home.privacy_policy')</a></li>
                        <li><a href="{{ route('showRecovery') }}">@lang('home.return_policy')</a></li>
                        <li><a href="{{ route('common_questions') }}">@lang('home.commonq_uestions')</a></li>
                        <li><a href="{{ route('showabout') }}">@lang('home.who_are_we')</a></li>
                        <li><a href="{{ route('contact') }}">@lang('home.connect_us')</a></li>
                    </ul>
                </div>
            </div>
            <div class="middle-ft">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="log-soci">
                                <div class="logo-ft">
                                    <figure><img src="{{ asset('home_file/images/logo.svg')}}" alt="" /></figure>
                                    <p>@lang('home.about')</p>
                                </div>
                                <ul class="social-media">
                                    <li class="twitter"><a href="{{ setting('twitter_link') }}"><i class="zmdi zmdi-twitter"></i></a></li>
                                    <li class="facebook"><a href="{{ setting('facebook_link') }}"><i class="zmdi zmdi-facebook"></i></a></li>
                                    <li class="instagram"><a href="{{ setting('youtube_link') }}"><i class="fa fa-youtube-play"></i></a></li>
                                    <li class="youtube"><a href="{{ setting('rss_link') }}"><i class="fa fa-rss"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <ul class="list-contact">
                                <li><img src="{{ asset('home_file/images/phone-call.svg')}}" /><p>{{ setting('phone_link') }}</p></li>
                                <li><img src="{{ asset('home_file/images/email.svg')}}" /><p>{{ setting('email_link') }}</p></li>
                                <li><img src="{{ asset('home_file/images/pin.svg')}}" />
                                    @if (app()->getLocale() == 'ar')    
                                        <p>{{ setting('location_ar') }}</p>
                                    @else
                                        <p>{{ setting('location_en') }}</p>
                                    @endif
                                </li>
                            </ul>
                        </div>
                        {{-- <div class="col-md-5">
                            <div class="payment">
                                <p>@lang('home.payment_method')</p>
                                
                                <ul>
                                    <p style="display:none;">{!!$payments = \App\Models\Payment::select('image')->get()!!}</p>
                                    @foreach ($payments as $payment)
                                        <li><img src="{{ $payment->image_path }}" width="50" /></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="bottom-ft">
                <div class="container">
                    <div class="copyright">
                        <p>Copyright © <?php echo date("Y")?>, MJAL STORE</p>
                    </div>
                </div>
            </div>
        </footer>
        <!--footer-->
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="zmdi zmdi-close"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true"> @lang('home.login')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false"> @lang('home.register')</a>
                            </li>
                        </ul>
                        <div class="form-reg">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                                        <div class="alert alert-danger print-error-msg-login" style="display:none">
                                            <ul></ul>
                                        </div>
                                    <form action="{{ route('LoginCline') }}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('post') }}
                                        
                                        <div class="form-group">
                                            <label><i class="fa fa-envelope"></i>@lang('dashboard.email')</label>
                                            <input type="email" id="email" name="email" class="form-control" placeholder="@lang('dashboard.email')" />
                                            <span class="text-danger" id="emailError"></span>
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fa fa-lock"></i>@lang('dashboard.password')</label>
                                            <input type="password" id="password" name="password" class="form-control" placeholder="@lang('dashboard.password')" />
                                                <span class="text-danger" id="passwordError"></span>
                                            {{-- <a class="forgot-pass"> @lang('forget_password')</a> --}}
                                        </div> 

<<<<<<< HEAD
                                         {!! NoCaptcha::display() !!}
=======
                                         {!! app('captcha')->display() !!}

                                         
                                         <input type="hidden" name="recaptcha" id="recaptcha">
>>>>>>> 3e98f0127e3653c6328c7b663312ee536f6c9346

                                        <div class="form-group text-center">
                                            <button class="btn-shop login-clinte"
                                                    data-url="{{ route('LoginCline') }}"
                                                    data-method="post"
                                            ><span>@lang('home.login')</span></button>
                                        </div>
                                        <div class="nt-account text-center">
                                            <p>@lang('home.don_account')<a>@lang('home.create_account')</a></p>
                                        </div>
                                        <b class="or-shape">@lang('home.or')</b>
                                        <ul class="list-social">
                                            <li><a href="/{{ app()->getLocale() }}/login/google" style="color: #3929aa"><i class="fa fa-google"></i></a></li>
                                            <li><a href="/{{ app()->getLocale() }}/login/facebook" style="color: #3929aa"><i class="fa fa-facebook"></i></a></li>
                                            <li><a href="/{{ app()->getLocale() }}/login/twitter" style="color: #3929aa"><i class="fa fa-twitter"></i></a></li>
                                        </ul>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                                    <form action="{{ route('registers') }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('post') }}
                                        <div class="form-group">
                                            <label><i class="fa fa-user"></i> @lang('home.name')</label>
                                            <input type="text" id="name" name="name" class="form-control" placeholder="@lang('home.name')" />
                                            <span class="text-danger" id="nameError"></span>
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fa fa-envelope"></i> @lang('home.email')</label>
                                            <input type="email" id="emailclinte" name="email" class="form-control" placeholder="@lang('home.email')" />
                                            <span class="text-danger" id="RegisrerEmailError"></span>
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fa fa-phone"></i> @lang('home.phone')</label>
                                            <input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="@lang('home.phone')"
                                             />
                                            <span class="text-danger" id="mobileNumberError"></span>
                                        </div>

                                        <div class="form-group">
                                            <label><i class="fa fa-lock"></i> @lang('home.password')</label>
                                            <input type="password" id="passwordclinte" name="password" class="form-control" placeholder="@lang('home.password')" />
                                            <span class="text-danger" id="RegisterPasswordError"></span>
                                        </div> 

<<<<<<< HEAD
                                         {!! NoCaptcha::renderJs() !!}


=======
                                        {!! app('captcha')->display() !!}


                                <input type="hidden" name="recaptcha" id="recaptcha">
>>>>>>> 3e98f0127e3653c6328c7b663312ee536f6c9346
                                        <div class="form-group text-center">
                                            <button class="btn-shop regiter-clinte"
                                                    data-url="{{ route('registers') }}"
                                                    data-method="post"
                                            ><span>@lang('home.register')</span></button>
                                        </div>
                                        <div class="nt-account text-center">
                                            <p>@lang('home.already_account')<a> @lang('home.login')</a></p>
                                        </div>
                                        <b class="or-shape">@lang('or')</b>
                                        <ul class="list-social">
<<<<<<< HEAD
                                            <li><a href="/{{ app()->getLocale() }}/login/facebook" style="color: #3929aa"><i class="fa fa-facebook"></i></a></li>
                                            <li><a href="/{{ app()->getLocale() }}/login/google" style="color: #3929aa"><i class="fa fa-google"></i></a></li>
=======
                                            <li><a href="/{{ app()->getLocale() }}/login/google" style="color: #3929aa"><i class="fa fa-google"></i></a></li>
                                            <li><a href="/{{ app()->getLocale() }}/login/facebook" style="color: #3929aa"><i class="fa fa-facebook"></i></a></li>
>>>>>>> 3e98f0127e3653c6328c7b663312ee536f6c9346
                                            <li><a href="/{{ app()->getLocale() }}/login/twitter" style="color: #3929aa"><i class="fa fa-twitter"></i></a></li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<div class="modal fade" id="modal-activation-code" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <p>@lang('home.activation_code')</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span class="zmdi zmdi-close"></span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('isverify')}}" method="post" class="form-site">
            {{ csrf_field() }}
            {{ method_field('post') }}
              <div class="form-group">
                  <label>@lang('home.Activation_code')</label>
                  <input name="code" id="code" type="number" id="tentacles" min="1" max="4" class="form-control" placeholder="@lang('home.enter_code')"  step="4"/>
                  <span class="text-danger" id="codeError"></span>
                  <span class="text-danger" id="codeErrors"></span>
              </div>
              <div class="option-activation-code">
                @auth('cliants')
                    <a href="{{ route('profiles.show',Auth::guard('cliants')->user()->id) }}" style="color: #6f42c1;">@lang('home.re_mobile_number')</a>
                @endif
                  <a class="return-verify return-code-verify activation_phone" href="{{ route('returnverify')}}" style="color: #6f42c1;" 
                        data-method="get"
                        data-url="{{ route('returnverify') }}"
                  >
                  <div id="countdown"></div>
                  <div class="Resend_the_code">@lang("home.Resend_the_code")</div>
                    </a>

              </div>
              <div class="form-group">
                  <button class="btn-shop activation_number"
                          data-url="{{ route('isverify') }}"
                          data-method="POST"
                  >@lang('home.activation')</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@push('loginReagiter')
    <script>

    $(document).ready(function() {

        $(".login-clinte").click(function(e){
            e.preventDefault();

            var url       = $(this).data('url');
            var method    = $(this).data('method');

            var email     = $('#email').val();
            var password  = $('#password').val();

            var redircte  = "{{ route('/') }}";
            // alert(redircte);

            $.ajax({
                url: url,
                method: method,
                data:{
                    email:email,
                    password:password,
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    if ($.isEmptyObject(response.error)) {
                        swal({
                            title: '@lang("home.addessuccfluy")',
                            timer: 100000,
                        });
                        swal.showLoading();
                        // $(".login-clinte").prop("disabled",true);
                        // alert(response.success);
                    } else {
                        printErrorMsg(response.error);
                    }

                   if(response.success) {
                        location.reload();
                        window.location.href = redircte;
                   }
                },//end of success
            });//end of ajax  

                function printErrorMsg(msg) {
                    $(".print-error-msg-login").find("ul").html('');
                    $(".print-error-msg-login").css('display', 'block');
                    $.each(msg, function(key, value) {
                        $(".print-error-msg-login").find("ul").empty();
                        $(".print-error-msg-login").find("ul").append('<li>' + value + '</li>');
                    });
                }//end of function printErrorMsg

        });//end of click


        $(".register-clinte").click(function(e){
            e.preventDefault();

            var url       = $(this).data('url');
            var method    = $(this).data('method');

            var name      = $('#name').val();
            var email     = $('#emailclinte').val();
            var phone     = $('#phone_number').val();
            var password  = $('#passwordclinte').val();
            var redircte  = "{{ route('/') }}";

            $('#nameError').text('');
            $('#RegisrerEmailError').text('');
            $('#mobileNumberError').text('');
            $('#RegisterPasswordError').text('');
            $('.Resend_the_code').empty('');

            $.ajax({
                url: url,
                method: method,
                data:{
                    email:email,
                    name:name,
                    phone_number:phone,
                    password:password,
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    
                    if (response.success == true) {


                        $(".register-clinte").prop("disabled",true);
                        
                        swal({
                            title: '@lang("home.addessuccfluy")',
                            timer: 10000,
                        });
                        swal.showLoading();

                        location.reload();
                        window.location.href = redircte;
<<<<<<< HEAD
                        $('#exampleModal').modal('hide');
                        $('#modal-activation-code').modal('show');
=======
                        // $('#exampleModal').modal('hide');
                        // $('#modal-activation-code').modal('show');
>>>>>>> 3e98f0127e3653c6328c7b663312ee536f6c9346

                        // var timeleft = 60;

                        // var downloadTimer = setInterval(function(){

                        //   if(timeleft <= 0){

                        //     $(".activation_phone").removeClass("disabled");
                        //     clearInterval(downloadTimer);

                        //     document.getElementById("countdown").innerHTML = ' @lang("home.Resend_the_code") ';
                        //     console.log('disabled');

                        //   } else {

                        //     $(".activation_phone").addClass("disabled");
                        //     document.getElementById("countdown").innerHTML = ' @lang("home.after_send") ' + timeleft;
                        //     console.log('Done');

                        //   }

                        //   timeleft -= 1;

                        // }, 1000);

                    } //end of if

                },//end of success
                error:function (response) {
                  $('#nameError').text(response.responseJSON.errors.name);
                  $('#RegisrerEmailError').text(response.responseJSON.errors.email);
                  $('#mobileNumberError').text(response.responseJSON.errors.phone_number);
                  $('#RegisterPasswordError').text(response.responseJSON.errors.password);
                }//end of errors
            });//end of ajax  

        });//end of click


        $(".activation_number").click(function(e){
            e.preventDefault();

            var url       = $(this).data('url');
            var method    = $(this).data('method');

            var code      = $('#code').val();
            var redircte  = "{{ route('/') }}";

            $('#codeError').text('');
            $('#codeErrors').text('');

            $.ajax({
                url: url,
                method: method,
                data:{
                    code:code,
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    if (response.success == true) {

                        $(".activation_number").prop("disabled",true);
                        
                        swal({
                            title: '@lang("home.addessuccfluy")',
                            timer: 1000,
                        });

                        location.reload();
                        window.location.href = redircte;

                    }//end of if

                    if (response.success == false) {

                        $("#codeErrors").text('@lang("home.error")');

                    }//end of if

                },//end of success
                error:function (response) {
                  $('#codeError').text(response.responseJSON.errors.code);
                  console.log(response);

                }//end of errors

            });//end of ajax  

        });//end of click


        $(".return-code-verify").click(function(e){
            e.preventDefault();

            var url       = $(this).data('url');
            var method    = $(this).data('method');

            $('.Resend_the_code').empty('');

            $.ajax({
                url: url,
                method: method,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    if (response.success == true) {

                        $(".activation_number").prop("disabled",true);
                        
                        swal({
                            title: '@lang("home.addessuccfluy")',
                            timer: 1000,
                        });

                    }//end of if

                    var timeleft = 60;

                        var downloadTimer = setInterval(function(){

                          if(timeleft <= 0){

                            $(".activation_phone").removeClass("disabled");
                            clearInterval(downloadTimer);

                            document.getElementById("countdown").innerHTML = ' @lang("home.Resend_the_code") ';
                            console.log('disabled');

                          } else {

                            $(".activation_phone").addClass("disabled");
                            document.getElementById("countdown").innerHTML = ' @lang("home.after_send") ' + timeleft;
                            console.log('Done');

                          }

                          timeleft -= 1;

                        }, 1000);

                }//end of success
            });//end of ajax  

        });//end of click

    });//end of document ready functiom

    </script>
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
<script>
        //  grecaptcha.ready(function() {
        //      grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'contact'}).then(function(token) {
        //         if (token) {
        //           document.getElementById('recaptcha').value = token;
        //         }
        //      });
        //  });
</script>
    
@endpush