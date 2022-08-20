<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="Панель управления">

    <title>@yield('title') {{ config('app.name', 'Laravel') }}</title>

    <!-- Vendor css -->
    <link href="{{ asset('manager/lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('manager/lib/Ionicons/css/ionicons.css') }}" rel="stylesheet">
    <link href="{{ asset('manager/lib/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('manager/lib/datatables/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('manager/lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <!-- Shamcey CSS -->
    <link href="{{ asset('manager/css/shamcey.css') }}" rel="stylesheet">
    <link href="{{ asset('manager/css/manager.css') }}" rel="stylesheet">
    @yield('styles')
</head>

<body>

<div class="sh-logopanel">
    <a href="" class="sh-logo-text">
        <img src="{{ asset('img/logo.png') }}" alt="">
    </a>
    <a id="navicon" href="" class="sh-navicon d-none d-xl-block"><i class="icon ion-navicon"></i></a>
    <a id="naviconMobile" href="" class="sh-navicon d-xl-none"><i class="icon ion-navicon"></i></a>
</div><!-- sh-logopanel -->

@include('admin.partials.navigation')<!-- sh-sideleft-menu -->

<div class="sh-headpanel">
    <div class="sh-headpanel-left">

        <!-- START: HIDDEN IN MOBILE -->
        <a href="" class="sh-icon-link">
            <div>
                <i class="icon ion-ios-folder-outline"></i>
                <span>Directory</span>
            </div>
        </a>
        <a href="" class="sh-icon-link">
            <div>
                <i class="icon ion-ios-calendar-outline"></i>
                <span>Events</span>
            </div>
        </a>
        <a href="" class="sh-icon-link">
            <div>
                <i class="icon ion-ios-gear-outline"></i>
                <span>Settings</span>
            </div>
        </a>
        <!-- END: HIDDEN IN MOBILE -->

        <!-- START: DISPLAYED IN MOBILE ONLY -->
        <div class="dropdown dropdown-app-list">
            <a href="" data-toggle="dropdown" class="dropdown-link">
                <i class="icon ion-ios-keypad tx-18"></i>
            </a>
            <div class="dropdown-menu">
                <div class="row no-gutters">
                    <div class="col-4">
                        <a href="" class="dropdown-menu-link">
                            <div>
                                <i class="icon ion-ios-folder-outline"></i>
                                <span>Directory</span>
                            </div>
                        </a>
                    </div><!-- col-4 -->
                    <div class="col-4">
                        <a href="" class="dropdown-menu-link">
                            <div>
                                <i class="icon ion-ios-calendar-outline"></i>
                                <span>Events</span>
                            </div>
                        </a>
                    </div><!-- col-4 -->
                    <div class="col-4">
                        <a href="" class="dropdown-menu-link">
                            <div>
                                <i class="icon ion-ios-gear-outline"></i>
                                <span>Settings</span>
                            </div>
                        </a>
                    </div><!-- col-4 -->
                </div><!-- row -->
            </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
        <!-- END: DISPLAYED IN MOBILE ONLY -->

    </div><!-- sh-headpanel-left -->

    <div class="sh-headpanel-right">
        {{--<div class="dropdown mg-r-10">
            <a href="" class="dropdown-link dropdown-link-notification">
                <i class="icon ion-ios-filing-outline tx-24"></i>
            </a>
        </div>
        <div class="dropdown dropdown-notification">
            <a href="" data-toggle="dropdown" class="dropdown-link dropdown-link-notification">
                <i class="icon ion-ios-bell-outline tx-24"></i>
                <span class="square-8"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-menu-header">
                    <label>Notifications</label>
                    <a href="">Mark All as Read</a>
                </div><!-- d-flex -->

                <div class="media-list">
                    <!-- loop starts here -->
                    <a href="" class="media-list-link read">
                        <div class="media pd-x-20 pd-y-15">
                            <img src="{{ asset('manager/img/img8.jpg') }}" class="wd-40 rounded-circle" alt="">
                            <div class="media-body">
                                <p class="tx-13 mg-b-0"><strong class="tx-medium">Suzzeth Bungaos</strong> tagged you and 18 others in a post.</p>
                                <span class="tx-12">October 03, 2017 8:45am</span>
                            </div>
                        </div><!-- media -->
                    </a>
                    <!-- loop ends here -->
                    <a href="" class="media-list-link read">
                        <div class="media pd-x-20 pd-y-15">
                            <img src="{{ asset('manager/img/img9.jpg') }}" class="wd-40 rounded-circle" alt="">
                            <div class="media-body">
                                <p class="tx-13 mg-b-0"><strong class="tx-medium">Mellisa Brown</strong> appreciated your work <strong class="tx-medium">The Social Network</strong></p>
                                <span class="tx-12">October 02, 2017 12:44am</span>
                            </div>
                        </div><!-- media -->
                    </a>
                    <a href="" class="media-list-link read">
                        <div class="media pd-x-20 pd-y-15">
                            <img src="{{ asset('manager/img/img10.jpg') }}" class="wd-40 rounded-circle" alt="">
                            <div class="media-body">
                                <p class="tx-13 mg-b-0">20+ new items added are for sale in your <strong class="tx-medium">Sale Group</strong></p>
                                <span class="tx-12">October 01, 2017 10:20pm</span>
                            </div>
                        </div><!-- media -->
                    </a>
                    <a href="" class="media-list-link read">
                        <div class="media pd-x-20 pd-y-15">
                            <img src="{{ asset('manager/img/img5.jpg') }}" class="wd-40 rounded-circle" alt="">
                            <div class="media-body">
                                <p class="tx-13 mg-b-0"><strong class="tx-medium">Julius Erving</strong> wants to connect with you on your conversation with <strong class="tx-medium">Ronnie Mara</strong></p>
                                <span class="tx-12">October 01, 2017 6:08pm</span>
                            </div>
                        </div><!-- media -->
                    </a>
                    <div class="media-list-footer">
                        <a href="" class="tx-12"><i class="fa fa-angle-down mg-r-5"></i> Show All Notifications</a>
                    </div>
                </div><!-- media-list -->
            </div><!-- dropdown-menu -->
        </div>--}}
        <div class="dropdown dropdown-profile">
            <a href="" data-toggle="dropdown" class="dropdown-link">
                <img src="{{ asset('manager/img/img1.jpg') }}" class="wd-60 rounded-circle" alt="">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="media align-items-center">
                    <img src="{{ asset('manager/img/img1.jpg') }}" class="wd-60 ht-60 rounded-circle bd pd-5" alt="">
                    <div class="media-body">
                        <h6 class="tx-inverse tx-15 mg-b-5">{{ auth()->user()->name }}</h6>
                        <p class="mg-b-0 tx-12">{{ auth()->user()->email }}</p>
                    </div><!-- media-body -->
                </div><!-- media -->
                <hr>
                <ul class="dropdown-profile-nav">
                    {{--<li><a href=""><i class="icon ion-ios-person"></i> Edit Profile</a></li>
                    <li><a href=""><i class="icon ion-ios-gear"></i> Settings</a></li>
                    <li><a href=""><i class="icon ion-ios-download"></i> Downloads</a></li>
                    <li><a href=""><i class="icon ion-ios-star"></i> Favorites</a></li>
                    <li><a href=""><i class="icon ion-power"></i> Sign Out</a></li>--}}
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"><i class="icon ion-power"></i> {{ __('Выйти') }} </a>
                        </form>
                    </li>
                </ul>
            </div><!-- dropdown-menu -->
        </div>
    </div><!-- sh-headpanel-right -->
</div><!-- sh-headpanel -->

<div class="sh-mainpanel">
    @yield('content')
    <div class="sh-footer">
        <div>Copyright &copy; 2022. All Rights Reserved.</div>
    </div><!-- sh-footer -->
</div>


<script src="{{ asset('manager/lib/jquery/jquery.js') }}"></script>
<script src="{{ asset('manager/lib/popper.js/popper.js') }}"></script>
<script src="{{ asset('manager/lib/bootstrap/bootstrap.js') }}"></script>
<script src="{{ asset('manager/lib/jquery-ui/jquery-ui.js') }}"></script>
<script src="{{ asset('manager/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js') }}"></script>
<script src="{{ asset('manager/lib/moment/moment.js') }}"></script>
<script src="{{ asset('manager/lib/Flot/jquery.flot.js') }}"></script>
<script src="{{ asset('manager/lib/Flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('manager/lib/flot-spline/jquery.flot.spline.js') }}"></script>

<script src="{{ asset('manager/js/shamcey.js') }}"></script>
<script src="{{ asset('manager/js/dashboard.js') }}"></script>
@yield('scripts')
</body>
</html>
