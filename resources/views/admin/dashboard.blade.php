@extends('layouts.admin')

@section('content')

        <div class="sh-breadcrumb">
            <nav class="breadcrumb">
                <span class="breadcrumb-item active">{{ __('Панель управления') }}</span>
            </nav>
        </div><!-- sh-breadcrumb -->
        <div class="sh-pagetitle">
            <div class="input-group">
                {{--<input type="search" class="form-control" placeholder="Search">
                <span class="input-group-btn">
                    <button class="btn"><i class="fa fa-search"></i></button>
                </span><!-- input-group-btn -->--}}
            </div><!-- input-group -->
            <div class="sh-pagetitle-left">
                <div class="sh-pagetitle-icon"><i class="icon ion-ios-home"></i></div>
                <div class="sh-pagetitle-title">
                    <span>{{ __('Добро пожаловать') }}</span>
                    <h2>{{ __('Панель управления') }}</h2>
                </div><!-- sh-pagetitle-left-title -->
            </div><!-- sh-pagetitle-left -->
        </div><!-- sh-pagetitle -->

        <div class="sh-pagebody">
            <div class="row row-sm">

                <div class="col-md-12">
                    <div class="alert alert-primary bd bd-primary pd-25 mg-b-20">
                        <h6 class="tx-14 mg-b-15">Панель состояния</h6>
                        <p class="mg-b-0 op-8">Здесь можно разместить информационные блоки.</p>
                    </div>
                </div>

               {{-- <div class="col-lg-8">
                    <div class="row row-xs">
                        <div class="col-6 col-sm-4 col-md">
                            <a href="#" class="shortcut-icon">
                                <div>
                                    <i class="icon ion-ios-albums-outline"></i>
                                    <span>Albums</span>
                                </div>
                            </a>
                        </div><!-- col -->
                        <div class="col-6 col-sm-4 col-md">
                            <a href="#" class="shortcut-icon">
                                <div>
                                    <i class="icon ion-ios-analytics-outline"></i>
                                    <span>Reports</span>
                                </div>
                            </a>
                        </div><!-- col -->
                        <div class="col-6 col-sm-4 col-md mg-t-10 mg-sm-t-0">
                            <a href="#" class="shortcut-icon">
                                <div>
                                    <i class="icon ion-ios-bookmarks-outline"></i>
                                    <span>Bookmarks</span>
                                </div>
                            </a>
                        </div><!-- col -->
                        <div class="col-6 col-sm-4 col-md mg-t-10 mg-md-t-0">
                            <a href="#" class="shortcut-icon">
                                <div>
                                    <i class="icon ion-ios-chatboxes-outline"></i>
                                    <span>Conversations</span>
                                </div>
                            </a>
                        </div><!-- col -->
                        <div class="col-6 col-sm-4 col-md mg-t-10 mg-md-t-0">
                            <a href="#" class="shortcut-icon">
                                <div>
                                    <i class="icon ion-ios-download-outline"></i>
                                    <span>Downloads</span>
                                </div>
                            </a>
                        </div><!-- col -->
                    </div><!-- row -->

                    <div class="card bd-primary mg-t-20">
                        <div class="card-header bg-primary tx-white">Daily Statistics</div>
                        <div class="card-body">
                            <div id="flotArea" class="ht-200 ht-sm-300"></div>
                        </div><!-- card-body -->
                    </div><!-- card -->

                    <div class="card bd-primary mg-t-20">
                        <div class="card-header bg-primary tx-white">Engine Reports</div>
                        <div class="table-responsive">
                            <table class="table table-striped mg-b-0">
                                <thead>
                                <tr>
                                    <th>Rendering Engine</th>
                                    <th>Browser</th>
                                    <th>Platforms</th>
                                    <th>Engine Version</th>
                                    <th>CSS Grade</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Trident</td>
                                    <td>Internet  Explorer 5.5</td>
                                    <td>Win 95+</td>
                                    <td class="center">5.5</td>
                                    <td class="center">A</td>
                                </tr>
                                <tr>
                                    <td>Presto</td>
                                    <td>Internet Explorer 6</td>
                                    <td>Win 98+</td>
                                    <td class="center">6</td>
                                    <td class="center">A</td>
                                </tr>
                                <tr>
                                    <td>Gecko</td>
                                    <td>Internet Explorer 7</td>
                                    <td>Win XP SP2+</td>
                                    <td class="center">7</td>
                                    <td class="center">A</td>
                                </tr>
                                <tr>
                                    <td>Webkit</td>
                                    <td>Internet  Explorer 5.5</td>
                                    <td>Win 95+</td>
                                    <td class="center">5.5</td>
                                    <td class="center">A</td>
                                </tr>
                                <tr>
                                    <td>Edge</td>
                                    <td>Internet  Explorer 5.5</td>
                                    <td>Win 95+</td>
                                    <td class="center">5.5</td>
                                    <td class="center">A</td>
                                </tr>
                                </tbody>
                            </table>
                        </div><!-- table-responsive -->
                    </div><!-- card -->

                    <div class="card bd-primary mg-t-20">
                        <div class="card-header bg-primary tx-white">Recent Comments</div>
                        <div class="card-body">
                            <div class="media-list">
                                <div class="media">
                                    <img src="../img/img9.jpg" class="wd-50 rounded-circle" alt="">
                                    <div class="media-body mg-l-20">
                                        <h6 class="tx-15 mg-b-5"><a href="">Sed ut perspiciatis unde omnis iste natus error sit voluptatem</a></h6>
                                        <p class="mg-b-20">by: <a href="">Rose Fay Orcullo</a></p>
                                        <p>Everyone realizes why a new common language would be desirable. To achieve this, it would be necessary to have uniform grammar.</p>
                                        <p class="mg-b-0">
                                            <a href="" class="btn btn-success pd-sm-x-20 mg-sm-r-5">Approve</a>
                                            <a href="" class="btn btn-secondary pd-sm-x-20">Reject</a>
                                        </p>
                                    </div><!-- media-body -->
                                </div><!-- media -->

                                <hr class="mg-y-20">

                                <div class="media">
                                    <img src="../img/img5.jpg" class="wd-50 rounded-circle" alt="">
                                    <div class="media-body mg-l-20">
                                        <h6 class="tx-15 mg-b-5"><a href="">But I must explain to you how all this mistaken</a></h6>
                                        <p class="mg-b-20">by: <a href="">Rolando Paloso</a></p>
                                        <p>Everyone realizes why a new common language would be desirable. To achieve this, it would be necessary to have uniform grammar.</p>
                                        <p class="mg-b-0">
                                            <a href="" class="btn btn-success pd-sm-x-20 mg-sm-r-5">Approve</a>
                                            <a href="" class="btn btn-secondary pd-sm-x-20">Reject</a>
                                        </p>
                                    </div><!-- media-body -->
                                </div><!-- media -->

                                <hr class="mg-y-20">

                                <div class="media">
                                    <img src="../img/img7.jpg" class="wd-50 rounded-circle" alt="">
                                    <div class="media-body mg-l-20">
                                        <h6 class="tx-15 mg-b-5"><a href="">On the other hand, we denounce with righteous indignation</a></h6>
                                        <p class="mg-b-20">by: <a href="">Richard Salomon</a></p>
                                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
                                        <p class="mg-b-0">
                                            <a href="" class="btn btn-success pd-sm-x-20 mg-sm-r-5">Approve</a>
                                            <a href="" class="btn btn-secondary pd-sm-x-20">Reject</a>
                                        </p>
                                    </div><!-- media-body -->
                                </div><!-- media -->
                            </div><!-- media-list -->
                        </div><!-- card-body -->
                    </div><!-- card -->

                </div><!-- col-8 -->
                <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                    <div class="alert alert-primary bd bd-primary pd-25 mg-b-20">
                        <h6 class="tx-14 mg-b-15">Some Announcement</h6>
                        <p class="mg-b-0 op-8">Best check yo self, you're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna.</p>
                    </div><!-- alert -->

                    <div class="card bd-primary">
                        <div class="card-header bg-primary tx-white">Widget Box</div>
                        <div class="card-body">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        </div><!-- card-body -->
                    </div><!-- card -->

                    <div class="card bd-primary mg-t-20">
                        <div class="card-header bg-primary tx-white">Real Time Updates</div>
                        <div class="card-body">
                            <p>You can update a chart periodically to get a real-time effect by using a timer to insert the new data in the plot and redraw it.</p>
                            <div id="flotRealtime" class="ht-200"></div>
                        </div><!-- card-body -->
                    </div><!-- card -->

                    <div class="card bd-primary card-calendar mg-t-20">
                        <div class="card-header bg-primary tx-white">Event Calendar</div>
                        <div class="datepicker"></div>
                    </div><!-- card -->

                    <div class="card bd-primary card-tab mg-t-20">
                        <div class="card-header bg-primary">
                            <nav class="nav">
                                <a href="#tabUsers" class="nav-link active" data-toggle="tab"><i class="icon ion-ios-contact-outline"></i></a>
                                <a href="#tabFavorites" class="nav-link" data-toggle="tab"><i class="icon ion-ios-star"></i></a>
                                <a href="#tabChat" class="nav-link" data-toggle="tab"><i class="icon ion-android-chat"></i></a>
                            </nav>
                        </div><!-- card-header -->
                        <div class="card-body tab-content">
                            <div id="tabUsers" class="tab-pane active">
                                <label class="card-tab-label">Recent Users</label>
                                <div class="media-list">
                                    <a href="" class="media">
                                        <img src="../img/img2.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Rowella Sombrio</h6>
                                            <p>Executive Director</p>
                                        </div><!-- media-body -->
                                    </a><!-- media -->
                                    <a href="" class="media">
                                        <img src="../img/img4.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Mary Grace Ceballos</h6>
                                            <p>Sales Supervisor</p>
                                        </div><!-- media-body -->
                                    </a><!-- media -->
                                    <a href="" class="media">
                                        <img src="../img/img5.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Archie Cantones</h6>
                                            <p>Software Engineer</p>
                                        </div><!-- media-body -->
                                    </a><!-- media -->
                                    <a href="" class="media">
                                        <img src="../img/img6.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Raffy Godinez</h6>
                                            <p>Full-Stack Developer</p>
                                        </div><!-- media-body -->
                                    </a><!-- media -->
                                    <a href="" class="media">
                                        <img src="../img/img7.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Allan Cadungog</h6>
                                            <p>Sales Supervisor</p>
                                        </div><!-- media-body -->
                                    </a><!-- media -->
                                </div><!-- media-list -->

                            </div><!-- tab-pane -->
                            <div id="tabFavorites" class="tab-pane">
                                <label class="card-tab-label">Favorites</label>
                                <div class="media-list">
                                    <div class="media">
                                        <img src="../img/img7.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Allan Cadungog</h6>
                                            <p>
                                                <a href="" class="mg-r-5">Message</a>
                                                <a href="">Call</a>
                                            </p>
                                        </div><!-- media-body -->
                                    </div><!-- media -->
                                    <div class="media">
                                        <img src="../img/img8.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Mary Jove Guden</h6>
                                            <p>
                                                <a href="" class="mg-r-5">Message</a>
                                                <a href="">Call</a>
                                            </p>
                                        </div><!-- media-body -->
                                    </div><!-- media -->
                                    <div class="media">
                                        <img src="../img/img9.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Charmaine Montuya</h6>
                                            <p>
                                                <a href="" class="mg-r-5">Message</a>
                                                <a href="">Call</a>
                                            </p>
                                        </div><!-- media-body -->
                                    </div><!-- media -->
                                    <div class="media">
                                        <img src="../img/img10.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Maricel Villalon</h6>
                                            <p>
                                                <a href="" class="mg-r-5">Message</a>
                                                <a href="">Call</a>
                                            </p>
                                        </div><!-- media-body -->
                                    </div><!-- media -->
                                    <div class="media">
                                        <img src="../img/img2.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Carlyn Salomon</h6>
                                            <p>
                                                <a href="" class="mg-r-5">Message</a>
                                                <a href="">Call</a>
                                            </p>
                                        </div><!-- media-body -->
                                    </div><!-- media -->
                                </div><!-- media-list -->
                            </div><!-- tab-pane -->
                            <div id="tabChat" class="tab-pane">
                                <label class="card-tab-label">Recent Comments</label>
                                <div class="media-list comment-list">
                                    <a href="" class="media">
                                        <img src="../img/img9.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Rose Fay Orcullo</h6>
                                            <p>Everyone realizes why a new common language would be desirable. To achieve this, it would be necessary to have uniform grammar.</p>
                                        </div><!-- media-body -->
                                    </a><!-- media -->
                                    <a href="" class="media">
                                        <img src="../img/img8.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Rowella Sombrio</h6>
                                            <p>If several languages coalesce, the grammar of the resulting language is more simple and regular.</p>
                                        </div><!-- media-body -->
                                    </a><!-- media -->
                                    <a href="" class="media">
                                        <img src="../img/img7.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Justin Mellejor</h6>
                                            <p>It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified.</p>
                                        </div><!-- media-body -->
                                    </a><!-- media -->
                                    <a href="" class="media">
                                        <img src="../img/img6.jpg" alt="">
                                        <div class="media-body">
                                            <h6>Raffy Godinez</h6>
                                            <p>It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified.</p>
                                        </div><!-- media-body -->
                                    </a><!-- media -->
                                </div><!-- media-list -->
                            </div><!-- tab-pane -->

                        </div><!-- card-body -->
                    </div><!-- card -->
                </div><!-- col-4 -->--}}
            </div><!-- row -->
        </div><!-- sh-pagebody -->
@endsection
