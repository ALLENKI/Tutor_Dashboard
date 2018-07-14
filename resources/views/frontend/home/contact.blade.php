@extends('frontend.layouts.master')

@section('content')

 <section id="contents">
      <section class="section slim-container">
              <div class="container">
                <div class="row">
                  <div class="col-md-6 text-center boxed-contact-form">
                    <div class="heading-with-sub">
                      <h4 class="subtitle">It might take a couple of days to respond</h4>
                    </div>

                    <div class="sp-line-40 m-bottom-20"></div>

                    {!! BootForm::open()->action(route('contact.request')) !!}

                    {!! BootForm::text('Name', 'name')
                                  ->attribute('required','true') !!}

                    {!! BootForm::email('Email', 'email')
                                  ->attribute('required','true') !!}

                    {!! BootForm::select('Please select an option:', 'select_option')
                                          ->options([
                                                      'Feedback' => 'Feedback',
                                                      'Query' => 'Query',
                                                      'Contact Us' => 'Contact Us',
                                                    ]) !!}

                    {!! BootForm::textarea('Message', 'message') !!}

                    <div>
                      
                      <button type="submit" value="Submit" class="btn btn-medium btn-skin-dark btn-thick-border btn-block">Submit</button>

                    </div>


                    {!! BootForm::close() !!}


                  </div>

                  <div class="col-md-5 col-md-offset-1">
                    <h4 class="with-underline">How to find us?</h4>
                  <div class="google-maps-container"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3806.840998096401!2d78.37263891506615!3d17.419416588059438!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb9407baf3e60b%3A0xa76a5bd7d2f213ff!2sAham+Learning+Hub+(Learning+Center)!5e0!3m2!1sen!2sin!4v1494585158236" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe></div>
                   <br>
                    <br>
                    <ul class="icon-list">
                      <li><i class="oli oli-envelope"></i><a href="mailto:contactus@ahamlearning.com">contactus@ahamlearning.com</a></li>
                      <li><i class="oli oli-phone"></i>+91 7330666701</li>
                      <li><i class="oli oli-phone"></i>+91 7330666703</li>
                      <li><i class="oli oli-map"></i>Synergy Building, 3rd Floor, Above Andhra Bank, Khajaguda, Near Delhi Public School, Madhura Nagar Colony, Gachibowli, Hyderabad, Telangana-500008.</li>
                    </ul>
                  </div>
                </div>
              </div>
            </section>
      </section>


@stop