<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Techpreneurship Bootcamp - Aham Learning</title>
    <meta name="description" content="Events HTML5 Template" />
    <meta name="author" content="TeslaThemes" />
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!-- Style CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7CLato:300,400,700,900,400italic,700italic" />
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link rel="stylesheet" href="assets/front/bet/css/vendors/icomoon.css" />
    <link rel="stylesheet" href="assets/front/bet/css/screen.css" />
</head>

<body id="page" data-smooth-scroll="on">
    <!-- Page Content -->
    <div class="page-wrapper">
        <!-- Buy Tickets Popup -->
        <div class="page-popup">
            <span class="close-popup-btn"></span>
            <span class="popup-overlay"></span>
            <div class="popup-inner-content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <form class="register-form" action="/register-for-bet" method="POST">
                                {{ csrf_field() }}
                                <div class="row custom-row">
                                    <div class="col-md-6">
                                        <div class="input-line">
                                            <h5 class="title"><span>BASIC INFORMATION</span></h5>
                                            <input type="text" name="full_name" class="form-input check-value" placeholder="" required style="color:black;" />
                                            <span class="placeholder">Full Name</span>
                                            <span class="bottom-line"></span>
                                        </div>
                                        <div class="input-line">
                                            <input type="number" name="age" class="form-input check-value" placeholder="" required style="color:black;" />
                                            <span class="placeholder">Your Age</span>
                                            <span class="bottom-line"></span>
                                        </div>
                                        <div class="input-line">
                                            <input type="text" name="school" class="form-input check-value" placeholder="" required style="color:black;" />
                                            <span class="placeholder">Your School</span>
                                            <span class="bottom-line"></span>
                                        </div>
                                        <div class="input-line">
                                            <input type="email" name="email" class="form-input check-value" placeholder="" required style="color:black;" />
                                            <span class="placeholder">Your E-mail Address</span>
                                            <span class="bottom-line"></span>
                                        </div>
                                        <div class="input-line">
                                            <input type="tel" name="mobile" class="form-input check-value" placeholder="" required style="color:black;" />
                                            <span class="placeholder">Your Phone Number</span>
                                            <span class="bottom-line"></span>
                                        </div>
                                        <div class="input-line">
                                            <input type="tel" name="address" class="form-input check-value" placeholder="" required style="color:black;" />
                                            <span class="placeholder">Your Mailing Address</span>
                                            <span class="bottom-line"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="title"><span>APPLICATION QUESTIONS</span></h5>
                                        <br>
                                        <div class="input-line">
                                            <input type="textarea" name="other_programs" class="form-input check-value" placeholder="" required style="color:black;" />
                                            <span class="placeholder">Do you have any experience attending Leadership Conferences or Entrepreneurship Programs? </span>
                                            <span class="bottom-line"></span>
                                        </div>
                                        <div class="input-line">
                                            <input type="textarea" name="programming_exp" class="form-input check-value" placeholder="" required style="color:black;" />
                                            <span class="placeholder">Do you have any prior experience with programming or computer science?</span>
                                            <span class="bottom-line"></span>
                                        </div>
                                        <div class="input-line">
                                            <input type="textarea" name="business_vertical" class="form-input check-value" placeholder="" required style="color:black;" />
                                            <span class="placeholder">Which vertical of business interests you the most and why?</span>
                                            <span class="bottom-line"></span>
                                        </div>
                                        <div class="input-line">
                                            <input type="textarea" name="summer_exp" class="form-input check-value" placeholder="" required style="color:black;" />
                                            <span class="placeholder">How did you spend your last two summers?</span>
                                            <span class="bottom-line"></span>
                                        </div>
                                        <div class="input-line">
                                            <input type="textarea" name="fav_books" class="form-input check-value" placeholder="" required style="color:black;" />
                                            <span class="placeholder">Name your favorite books, authors, films, and/or artists.</span>
                                            <span class="bottom-line"></span>
                                        </div>
                                        <div class="input-line">
                                            <input type="textarea" name="challenge" class="form-input check-value" placeholder="" required style="color:black;" />
                                            <span class="placeholder">According to you, what is the most significant challenge that human civilisation faces today and why?</span>
                                            <span class="bottom-line"></span>
                                        </div>
                                    </div>
                                </div>
                                <p>Admission to the Techpreneurship Bootcamp is selective and registering does not guarantee enrollment. The cost for the program is 15 Aham Credits or INR 15000 (for non-Aham students only). Students who have been selected will be contacted with payment details.</p>
                                <p>By pressing the "Submit" button you're indicating that if selected, you will attend the entirety of the program (June 19th - July 1st).</p>
                                <div class="btn-wrapper align-center">
                                    <button class="submit-button btn" type="submit">Submit Your Application</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Header -->
        <header class="main-header" id="md-shadow">

            <div class="hidden-xs">
                <a href="ahamlearning.com/bet" style="float: left; margin-right: 275px; padding-top: 8px;">
                    <img src="https://res.cloudinary.com/ahamlearning/image/upload/c_fit,h_50,q_auto:eco,f_auto/v1466848309/logo_huge_lfpjpa.png" alt="Aham Learning Hub" class="img-responsive" style="max-height:50px;margin-top: 10px;float: left;">
                </a>
            </div>

            <div class="hidden-sm hidden-md hidden-lg">
                <a href="ahamlearning.com/bet" style="float: left; margin-right: 145px; padding-top: 8px;">
                    <img src="https://res.cloudinary.com/ahamlearning/image/upload/c_fit,h_50,q_auto:eco,f_auto/v1466848309/logo_huge_lfpjpa.png" alt="Aham Learning Hub" class="img-responsive" style="max-height:50px;margin-top: 5px;float: left;">
                </a>
            </div>

            <span style="text-align: right;" class="mobile-nav-toggle">
          <i class="line"></i>
          <i class="line"></i>
          <i class="line"></i>
        </span>
            <!--Navigation-->
            <nav class="main-nav">
                <span class="active-indicator"></span>
                <ul class="clean-list">
                    <li class="active">
                        <a href="#home">Home</a>
                    </li>
                    <li>
                        <a href="#about">About</a>
                    </li>
                    <li>
                        <a href="#schedule">Schedule</a>
                    </li>
                    <li>
                        <a href="#speakers">Coaches</a>
                    </li>
                    <li>
                        <a href="#pricing">Stages</a>
                    </li>
                    <li>
                        <a href="http://www.ahamlearning.com">Aham</a>
                    </li>
                </ul>
            </nav>
            <a href="#" class="top-tickets">Register Now!</a>
        </header>
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Intro Section -->
            <section class="section section-home no-padding" id="home">
                <div class="intro-box">
                    <div class="intro-box-content">
                        <div class="container">
                            <div class="event-info">
                                <br>
                                <br>
                                <h1 class="event-name">TECHPRENEURSHIP BOOTCAMP</h1>
                                <p class="event-date">June 19 - July 1, Hyderabad</p>
                            </div>
                        </div>
                        <div class="countdown-box" id="font-countdown">
                            <div class="container">
                                <ul class="clean-list countdown-timmer clearfix" data-duedate="2017/6/19">
                                    <li class="timmer-option">
                                        <span class="value days"></span>
                                        <span class="title">Days</span>
                                    </li>
                                    <li class="timmer-option">
                                        <span class="value hours"></span>
                                        <span class="title">Hours</span>
                                    </li>
                                    <li class="timmer-option">
                                        <span class="value minutes"></span>
                                        <span class="title">Minutes</span>
                                    </li>
                                    <li class="timmer-option">
                                        <span class="value seconds"></span>
                                        <span class="title">Seconds</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="btn-wrapper align-center">
                            <a href="#" class="btn filled top-tickets">Register</a>
                        </div>
                    </div>
                    <img src="assets/front/bet/img/tech.jpg" alt="intro box cover" class="intro-box-cover" />
                </div>
            </section>
            <!-- About Section -->
            <section class="section section-about" id="about">
                <!-- About Box Wrapper -->
                <div class="container">
                    <div class="about-box" id="md-shadow">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="image-wrapper translate-left">
                                    <img src="assets/front/bet/img/LogoTech.png" alt="about image cover" id="md-shadow" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-box">
                                    <h3>Techpreneurship <span>June 2017</span></h3>
                                    <p>A 12-day, 72 hour boot camp to empower young adults to make an impact on their communities through technology-enabled (technology-driven) entrepreneurship.</p>
                                </div>
                                <div class="image-wrapper translate-left-bottom">
                                    <img src="assets/front/bet/img/bg2.jpg" alt="about image cover" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Counter Boxes -->
                <div class="counter-boxes">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 counter-box-wrapper">
                                <div class="counter-box" data-counter-value="30" data-state="0">
                                    <span class="value-container"></span>
                                    <p class="box-title">Participants</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 counter-box-wrapper">
                                <div class="counter-box" data-counter-value="6" data-state="0">
                                    <span class="value-container"></span>
                                    <p class="box-title">Teams</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 counter-box-wrapper">
                                <div class="counter-box" data-counter-value="12" data-state="0">
                                    <span class="value-container"></span>
                                    <p class="box-title">Days</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 counter-box-wrapper">
                                <div class="counter-box" data-counter-value="11" data-state="0">
                                    <span class="value-container"></span>
                                    <p class="box-title">Coaches and Mentors</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Program Section -->
            <section class="section section-program" id="schedule" data-parallax-bg="assets/front/bet/img/classroom1.png">
                <div class="box-img-wrapper">
                    <div class="box-img">
                        <span></span>
                    </div>
                </div>
                <h2 class="section-header container align-center inverted">Schedule<span><i class="icon"></i></span></h2>
                <div class="program-tabs">
                    <div class="tabed-content">
                        <div class="tabs-body">
                            <div id="prgrm-tb-1" class="tab-item current">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <span class="timming"></span>
                                                    <h5 class="title">What is Entrepreneurship?</h5>
                                                    <ul style="font-weight:bold">
                                                        <li>Qualities of an Entrepreneur</li>
                                                        <li>Entrepreneurial Journeys</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <span class="timming"></span>
                                                    <h5 class="title">What is Market?</h5>
                                                </div>
                                                <ul style="font-weight:bold">
                                                    <li>Choosing the Market</li>
                                                    <li>Market Positioning</li>
                                                    <li>Competitors</li>
                                                    <li>Pricing Strategies</li>
                                                    <li>Human Centric Design Thinking</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <span class="timming"></span>
                                                    <h5 class="title">Profit, Value Added</h5>
                                                </div>
                                                <ul style="font-weight:bold">
                                                    <li>Why Should People Buy Your Product</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prgrm-tb-2" class="tab-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Ideation</h5>
                                                </div>
                                                <ul style="font-weight:bold">
                                                    <li>Team Formation</li>
                                                    <li>Overview of Business Verticals</li>
                                                    <li>Identifying Problem Areas</li>
                                                    <li>Defining a Problem</li>
                                                    <li>Brainstorming</li>
                                                    <li>Elevator Pitch</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prgrm-tb-3" class="tab-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Business Strategy</h5>
                                                </div>
                                                <ul style="font-weight:bold">
                                                    <li>What is a Business Plan?</li>
                                                    <li>Scope of a Business</li>
                                                    <li>Feasibility Study</li>
                                                    <li>Mission and Vision</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prgrm-tb-4" class="tab-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Prototyping Stage</h5>
                                                </div>
                                                <ul style="font-weight:bold">
                                                    <li>What Role Does Technology Play in Entrepreneurship?</li>
                                                    <li>Tech-driven Entrepreneurship Case Studies</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Technology Part Starts Here!</h5>
                                                </div>
                                                <ul style="font-weight:bold">
                                                    <li>Introduction to Web Technologies</li>
                                                    <li>Introduction to HTML</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prgrm-tb-5" class="tab-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">HTML Part II - Coding Challenges</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Introduction to Styles, CSS</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">HTML and CSS Coding Challenges</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prgrm-tb-6" class="tab-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Introduction to Javascript</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Javascript Coding Challenges</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Sketching and Wireframe Design</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prgrm-tb-7" class="tab-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">User Interface / Experience Design</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Website Essentials</h5>
                                                </div>
                                                <ul style="font-weight:bold">
                                                    <li>Login Page</li>
                                                    <li>Dashboard/Profile Page</li>
                                                    <li>Product Catalogue</li>
                                                    <li>Cart</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Hack Session 1</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prgrm-tb-8" class="tab-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">User Interface / Experience Design</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Hack Session 2</h5>
                                                    <h5 class="title">Test/Feedback Session 1</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Hack Session 3</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prgrm-tb-9" class="tab-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Competitor/Market Analysis</h5>
                                                </div>
                                                <ul style="font-weight:bold">
                                                    <li>Product Differentiation</li>
                                                    <li>SWOT Analysis</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Hack Session 4</h5>
                                                    <h5 class="title">Test/Feedback Session 2</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Hack Session 5</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prgrm-tb-10" class="tab-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Hack Session 6</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Hack Session 7</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                    <h5 class="title">Final Product Demo</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prgrm-tb-11" class="tab-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="program-box">
                                              <div class="box-header">
                                                    <h5 class="title"> Selling Your Idea </h5>
                                              </div>
                                                <ul style="font-weight:bold">
                                                    <li>What is a Business Plan Presentation?</li>
                                                    <li>What is an Investor Pitch?</li>
                                                    <li>What is the Role of Communication in a Business?</li>
                                                    <li>Presentation Skills</li>
                                                    <li>Presentation Making Session 1</li>
                                                    <li>Mock Presentation 1</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prgrm-tb-12" class="tab-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="program-box">
                                                <div class="box-header">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="program-box">
                                              <div class="box-header">
                                                    <h5 class="title">Finals</h5>
                                              </div>
                                                <ul style="font-weight:bold">
                                                    <li>Presentation Making Session 2</li>
                                                    <li>Final Presentation and Demo</li>
                                                    <li>Judgement</li>
                                                    <li>Gala Dinner and Award Presentation</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs-header">
                            <div class="container">
                                <ul class="clean-list">
                                    <li data-tab-link="prgrm-tb-1" class="tab-link current">
                                        <i></i>
                                        <div class="tab-link-controll">
                                            <span class="date">JUNE 19</span>
                                        </div>
                                    </li>
                                    <li data-tab-link="prgrm-tb-2" class="tab-link">
                                        <i></i>
                                        <div class="tab-link-controll">
                                            <span class="date">JUNE 20</span>
                                        </div>
                                    </li>
                                    <li data-tab-link="prgrm-tb-3" class="tab-link">
                                        <i></i>
                                        <div class="tab-link-controll">
                                            <span class="date">JUNE 21</span>
                                        </div>
                                    </li>
                                    <li data-tab-link="prgrm-tb-4" class="tab-link">
                                        <i></i>
                                        <div class="tab-link-controll">
                                            <span class="date">JUNE 22</span>
                                        </div>
                                    </li>
                                    <li data-tab-link="prgrm-tb-5" class="tab-link">
                                        <i></i>
                                        <div class="tab-link-controll">
                                            <span class="date">JUNE 23</span>
                                        </div>
                                    </li>
                                    <li data-tab-link="prgrm-tb-6" class="tab-link">
                                        <i></i>
                                        <div class="tab-link-controll">
                                            <span class="date">JUNE 24</span>
                                        </div>
                                    </li>
                                    <li data-tab-link="prgrm-tb-7" class="tab-link">
                                        <i></i>
                                        <div class="tab-link-controll">
                                            <span class="date">JUNE 26</span>
                                        </div>
                                    </li>
                                    <li data-tab-link="prgrm-tb-8" class="tab-link">
                                        <i></i>
                                        <div class="tab-link-controll">
                                            <span class="date">JUNE 27</span>
                                        </div>
                                    </li>
                                    <li data-tab-link="prgrm-tb-9" class="tab-link">
                                        <i></i>
                                        <div class="tab-link-controll">
                                            <span class="date">JUNE 28</span>
                                        </div>
                                    </li>
                                    <li data-tab-link="prgrm-tb-10" class="tab-link">
                                        <i></i>
                                        <div class="tab-link-controll">
                                            <span class="date">JUNE 29</span>
                                        </div>
                                    </li>
                                    <li data-tab-link="prgrm-tb-11" class="tab-link">
                                        <i></i>
                                        <div class="tab-link-controll">
                                            <span class="date">JUNE 30</span>
                                        </div>
                                    </li>
                                    <li data-tab-link="prgrm-tb-12" class="tab-link">
                                        <i></i>
                                        <div class="tab-link-controll">
                                            <span class="date">JULY 1</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Speakers Section -->
            <section class="section section-speakers" id="speakers">
                <h2 class="section-header container align-center">COACHES<span></span></h2>
                <div class="container">
                    <div class="speakers-tabs">
                        <div class="tabed-content">
                            <div class="row row-fit">
                                <div class="col-md-6">
                                    <div class="tabs-body">
                                        <div id="nr-1" class="tab-item current">
                                            <div class="speaker-info-box">
                                                <h3 class="theme-title">Entrepreneurship</h3>
                                                <div class="event-meta">
                                                    <div class="speaker-info">
                                                        <img src="assets/front/bet/img/ajitha.jpg" alt="speaker image" />
                                                        <span class="name">Ajitha Molakapalli</span>
                                                    </div>
                                                    <span class="date"></span>
                                                </div>
                                                <p class="theme-description">Founder and CEO of Aham Learning Hub, Ajitha has always been interested in education and its integration with technology. Having been in technology for the past 18 years in companies like PayPal and Sun Microsystems, she is trying to disrupt the perception of education for young adults.</p>
                                                <div class="social-block-wrapper">
                                                    <ul class="clean-list social-block">
                                                        <li>
                                                            <a href="https://www.linkedin.com/in/ajitham/"><i class="icon-linkedin" target="_blank"></i></a>
                                                        </li>
                                                        <li>
                                                            <a href="https://www.facebook.com/ajitha.atluri"><i class="icon-facebook" target="_blank"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="nr-2" class="tab-item">
                                            <div class="speaker-info-box">
                                                <h3 class="theme-title">Web Technologies</h3>
                                                <div class="event-meta">
                                                    <div class="speaker-info">
                                                        <img src="assets/front/bet/img/manasa.jpg" alt="speaker image" />
                                                        <span class="name">Manasa Madapu</span>
                                                    </div>
                                                    <span class="date"></span>
                                                </div>
                                                <p class="theme-description">She is the Co-Founder & CEO of the product development company Betalectic, which specializes in building Web Applications, Mobile Apps, ERP tools and infrastructure. An entrepreneur herself, and with plenty of prior experience in management and software, she has been an integral part of Aham since its conception.
                                                </p>
                                                <div class="social-block-wrapper">
                                                    <ul class="clean-list social-block">
                                                        <li>
                                                            <a href="https://www.linkedin.com/in/manasa-madapu-02388512a/"><i class="icon-linkedin" target="_blank"></i></a>
                                                        </li>
                                                        <li>
                                                            <a href="https://www.facebook.com/mansimadapu"><i class="icon-facebook" target="_blank"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="nr-3" class="tab-item">
                                            <div class="speaker-info-box">
                                                <h3 class="theme-title">Web Technologies</h3>
                                                <div class="event-meta">
                                                    <div class="speaker-info">
                                                        <img src="assets/front/bet/img/rajiv.jpg" alt="speaker image" />
                                                        <span class="name">Rajiv Seelam</span>
                                                    </div>
                                                    <span class="date"></span>
                                                </div>
                                                <p class="theme-description">Co-Founder & CTO of Betalectic and he is an Enthusiastic Web Developer. Rajiv is not one who is unfamiliar with the entrepreneurial processes. He has worked extensively with technologies like PHP, MySql, Graph DB,Postgres, Android etc, and has been an integral part of Aham’s growth since conception.
                                                </p>
                                                <div class="social-block-wrapper">
                                                    <ul class="clean-list social-block">
                                                        <li>
                                                            <a href="https://www.linkedin.com/in/rajivseelam/"><i class="icon-linkedin" target="_blank"></i></a>
                                                        </li>
                                                        <li>
                                                            <a href="https://www.facebook.com/seelamrajiv"><i class="icon-facebook" target="_blank"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="nr-4" class="tab-item">
                                            <div class="speaker-info-box">
                                                <h3 class="theme-title">Business Strategy</h3>
                                                <div class="event-meta">
                                                    <div class="speaker-info">
                                                        <img src="assets/front/bet/img/aditya.jpg" alt="speaker image" />
                                                        <span class="name">Aditya Atluri</span>
                                                    </div>
                                                    <span class="date"></span>
                                                </div>
                                                <p class="theme-description">Currently working at Kanvic as an Associate Consultant, Aditya has had extensive experience working with high performing teams and startups. Well versed with corporate and business strategies he is known for his ability to identify opportunities for growth. Aditya specializes in areas like Strategic Planning and Market Research.</p>
                                                <div class="social-block-wrapper">
                                                    <ul class="clean-list social-block">
                                                        <li>
                                                            <a href="https://www.linkedin.com/in/adityatluri/"><i class="icon-linkedin" target="_blank"></i></a>
                                                        </li>
                                                        <!--  <li>
                                    <a href="#"><i class="icon-facebook" target="_blank"></i></a>
                              </li>  -->
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="nr-5" class="tab-item">
                                            <div class="speaker-info-box">
                                                <h3 class="theme-title">Communication and Presentation</h3>
                                                <div class="event-meta">
                                                    <div class="speaker-info">
                                                        <img src="assets/front/bet/img/nagaraja.jpg" alt="speaker image" />
                                                        <span class="name">Nagaraj Padavala</span>
                                                    </div>
                                                    <span class="date"></span>
                                                </div>
                                                <p class="theme-description">A veteran of the industry, Nagaraj has over 16 years of IT Consulting experience. And 7 years of Learning and Organizational Development experience. Currently a Management Consultant at Pragna Services, he excels at Leadership Development, Behavioral skills, understanding of Business processes.</p>
                                                <div class="social-block-wrapper">
                                                    <ul class="clean-list social-block">
                                                        <li>
                                                            <a href="https://www.linkedin.com/in/nagaraja-padavala-8227a28/"><i class="icon-linkedin" target="_blank"></i></a>
                                                        </li>
                                                        <li>
                                                            <a href="https://www.facebook.com/profile.php?id=100013563161660"><i class="icon-facebook" target="_blank"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="nr-6" class="tab-item">
                                            <div class="speaker-info-box">
                                                <h3 class="theme-title">Technology</h3>
                                                <div class="event-meta">
                                                    <div class="speaker-info">
                                                        <img src="assets/front/bet/img/kiran.jpg" alt="speaker image" />
                                                        <span class="name">Kiran Bollepalli</span>
                                                    </div>
                                                    <span class="date"></span>
                                                </div>
                                                <p class="theme-description">Currently the Chief Mobile Architect at Betalectic, Kiran brings with him years of experience in technology. He has worked extensively with Android and other technologies like Core Java and Eclipse. Known for “getting the work done”, he is a highly motivated self-learner.
                                                </p>
                                                <div class="social-block-wrapper">
                                                    <ul class="clean-list social-block">
                                                        <li>
                                                            <a href="https://www.linkedin.com/in/kiran-bollepalli-7a270222/"><i class="icon-linkedin" target="_blank"></i></a>
                                                        </li>
                                                        <li>
                                                        <a href="https://www.facebook.com/kiran.bollepalli"><i class="icon-facebook" target="_blank"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="tabs-header">
                                        <ul class="clean-list clearfix">
                                            <li data-tab-link="nr-1" class="tab-link current">
                                                <div class="speaker-box">
                                                    <img src="assets/front/bet/img/ajitha.jpg" alt="speaker image" />
                                                </div>
                                            </li>
                                            <li data-tab-link="nr-2" class="tab-link">
                                                <div class="speaker-box">
                                                    <img src="assets/front/bet/img/manasa.jpg" alt="speaker image" />
                                                </div>
                                            </li>
                                            <li data-tab-link="nr-3" class="tab-link">
                                                <div class="speaker-box">
                                                    <img src="assets/front/bet/img/rajiv.jpg" alt="speaker image" />
                                                </div>
                                            </li>
                                            <li data-tab-link="nr-4" class="tab-link">
                                                <div class="speaker-box">
                                                    <img src="assets/front/bet/img/aditya.jpg" alt="speaker image" />
                                                </div>
                                            </li>
                                            <li data-tab-link="nr-5" class="tab-link">
                                                <div class="speaker-box">
                                                    <img src="assets/front/bet/img/nagaraja.jpg" alt="speaker image" />
                                                </div>
                                            </li>
                                            <li data-tab-link="nr-6" class="tab-link">
                                                <div class="speaker-box">
                                                    <img src="assets/front/bet/img/kiran.jpg" alt="speaker image" />
                                                </div>
                                            </li>
              
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- stages Section -->
            <section class="section section-pricing" id="pricing">
                <h2 class="section-header container align-center inverted">STAGES</h2>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4 pricing-table-wrapper">
                            <div class="pricing-table">
                                <div class="table-header">
                                    <h3 class="table-name">Ideate</h3>
                                </div>
                                <div class="table-body">
                                    <ul class="table-description clean-list">
                                        <li>Unlock entrepreneurial DNA </li>
                                        <li>Develop a solution-focused mindset </li>
                                        <li>Develop a action-oriented mindset</li>
                                        <li>Equip students with the skills and thoughts</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 pricing-table-wrapper">
                            <div class="pricing-table">
                                <div class="table-header">
                                    <h3 class="table-name">Prototype</h3>
                                </div>
                                <div class="table-body">
                                    <ul class="table-description clean-list">
                                        <li>Design Process</li>
                                        <li>Technology as a tool</li>
                                        <li>Teamwork and collabaration</li>
                                        <li>Make students adaptive to changes in technology</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 pricing-table-wrapper">
                            <div class="pricing-table">
                                <div class="table-header">
                                    <h3 class="table-name">Pitch</h3>
                                </div>
                                <div class="table-body">
                                    <ul class="table-description clean-list">
                                        <li>Presentation skills</li>
                                        <li>Communicate their ideas effectively</li>
                                        <li>To make a comprehensive business plan</li>
                                        <li>Develop a willingness to exploit opportunities</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Faq Block -->
            <br />
            <div class="faq-block">
                <h2 class="section-header container align-center">Frequently Asked Questions<span ></span></h2>
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="faq-item">
                                <h4 class="item-title">Who is this for?</h4>
                                <p class="item-description">This bootcamp is aimed at those who are in the age group of 15-18 years old. If you have a thirst for entrepreneurship and technology, this techpreneurship bootcamp is for you.</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="faq-item">
                                <h4 class="item-title">What will I learn?</h4>
                                <p class="item-description">You will be taught core skills to help convert your entrepreneurial ideas into market-viable prototypes
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="faq-item">
                                <h4 class="item-title">How do I register?</h4>
                                <p class="item-description">There <a href="#">Registration
                      Form</a> is at the top of the webpage. A total of 30 students will be a part of the bootcamp
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="faq-item">
                                <h4 class="item-title">Am I enrolled if I registered?</h4>
                                <p class="item-description">No. 30 students will be selected to be a part of the bootcamp. You will be contacted if you are selected, and payments will be made at that point of time.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Location Section -->
            <section class="section section-location no-padding" id="location">
                <div class="map-wrapper">
                    <!-- Map Popup -->
                    <div id="contact-popup" class="no-select">
                        <div class="popup-wrapper">
                            <ul class="clean-list contact-meta">
                                <li class="meta-option">
                                    <i class="icon icon-location2"></i>
                                    <p>Aham Learning Hub, Khajaguda</p>
                                </li>
                                <li class="meta-option">
                                    <i class="icon icon-phone"></i>
                                    <p>+91-7330666703</p>
                                </li>
                                <li class="meta-option">
                                    <i class="icon icon-envelope-o"></i>
                                    <p><a href="mailto:sathwik@ahamlearning.com">sathwik@ahamlearning.com</a></p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Map Canvas -->
                    <div id="map-canvas" class="contact-map" data-options='{
                 "marker": "assets/front/bet/img/map-marker-resize.png",
                 "marker_coord": {
                 "lat": "17.419303",
                 "lon": "78.374822"
                 },
                 "map_center": {
                 "lat": "17.419303",
                 "lon": "78.374822"
                 },
                 "zoom": "14"
                 }'></div>
                </div>
            </section>
        </div>
        <!-- Main Footer -->
        <footer class="main-footer">
            <section class="section section-about">
                <h2 class="section-header container align-center">About Aham Learning Hub<span ></span></h2>
                <div class="container">
                    <div class="text-box">
                        <p>Our vision at Aham is “to bring together passionate subject matter experts, 21st century learners, committed team, organized content, innovative teaching strategies and relevant technology to create the world’s best learning hubs where authentic and personalized learning on any subject is experienced”
                            <p>
                    </div>
                </div>
                <!-- Main Footer Area -->
                <div class="main-footer-area">
                    <div class="container">
                        <div class="text-box">
                            <p>Visit the links below for more information on Aham</p>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="social-block-wrapper" style="text-align:center">
                                    <div class="inner-content">
                                        <ul class="clean-list social-block">
                                            <li>
                                                <a href="https://www.facebook.com/AhamLearning/" target="_blank">
                                                    <i class="icon-facebook"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.ahamlearning.com" target="_blank">
                                                    <i class="icon-edge"></i>
                                                </a>
                                            </li>
                                            <!-- <li> -->
                                            <!--    <a href="https://www.instagram.com/teslathemes/" target="_blank"> -->
                                            <!--       <i class="icon-instagram"></i> -->
                                            <!--    </a> -->
                                            <!-- </li> -->
                                        </ul>
                                        <p class="copyrigts">&copy; 2017 <a href="https://www.ahamlearning.com/" target="_blank">Aham Learning Hub</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </footer>
        </section>
    </div>
    <!-- Scripts -->
    <script src="assets/front/bet/js/vendors/jquery.js"></script>
    <script src="assets/front/bet/js/vendors/modernizr.js"></script>
    <script src="assets/front/bet/js/vendors/countdown.js"></script>
    <script src="assets/front/bet/js/vendors/smooth-scroll.js"></script>
    <script src="assets/front/bet/js/vendors/slick.js"></script>
    <script src="assets/front/bet/js/vendors/instagram.js"></script>
    <script type="text/javascript" src='https://maps.google.com/maps/api/js?key=AIzaSyD6yd1GX6fFm2074DoQ9y68ZF8w4TQqk-k'></script> 
    <script src="assets/front/bet/js/vendors/infobox.js"></script>
    <script src="assets/front/bet/js/options.js"></script>
</body>

</html>
