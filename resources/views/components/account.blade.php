@if(auth()->check())
@php $user = auth()->user()->customer @endphp
<div class="search_head">
    <div class="search_in big_search">
        <input type="text" placeholder="Поиск">
        <button class="go_search"><img src="{{ asset('catalog/assets/img/icons/search.svg') }}" alt="studentik.online"></button>
    </div>
</div>
<div class="btn_catcher clearfix">

    <button class="btn_hide_submenu">
        @if($user->totalUnreadMessages() or $user->totalUnreadNotifications())
        <span class="red_art">{{ $user->totalUnreadMessages() + $user->totalUnreadNotifications() }}</span>
        @endif
        <svg xmlns="http://www.w3.org/2000/svg" height="512pt" viewBox="0 0 512 512" width="512pt"><path d="m256 512c-68.378906 0-132.667969-26.628906-181.019531-74.980469-48.351563-48.351562-74.980469-112.640625-74.980469-181.019531s26.628906-132.667969 74.980469-181.019531c48.351562-48.351563 112.640625-74.980469 181.019531-74.980469s132.667969 26.628906 181.019531 74.980469c48.351563 48.351562 74.980469 112.640625 74.980469 181.019531s-26.628906 132.667969-74.980469 181.019531c-48.351562 48.351563-112.640625 74.980469-181.019531 74.980469zm0-472c-119.101562 0-216 96.898438-216 216s96.898438 216 216 216 216-96.898438 216-216-96.898438-216-216-216zm138.285156 182-28.285156-28.285156-110 110-110-110-28.285156 28.285156 138.285156 138.285156zm0 0"/></svg>
    </button>
    @if($user->isCustomer())
    <a href="{{ route('order.create') }}" class="go_zakaz">
        <span>Разместить заказ</span>
    </a>
    @endif

    <div class="clearfix fortest">
        <div class="srcc small_search">
            <a href="{{ route('search') }}">
                <svg  width="21" height="21" viewBox="0 0 21 21" fill="none">
                    <path d="M16.031 14.617L20.314 18.899L18.899 20.314L14.617 16.031C13.0237 17.3082 11.042 18.0029 9 18C4.032 18 0 13.968 0 9C0 4.032 4.032 0 9 0C13.968 0 18 4.032 18 9C18.0029 11.042 17.3082 13.0237 16.031 14.617ZM14.025 13.875C15.2941 12.5699 16.0029 10.8204 16 9C16 5.132 12.867 2 9 2C5.132 2 2 5.132 2 9C2 12.867 5.132 16 9 16C10.8204 16.0029 12.5699 15.2941 13.875 14.025L14.025 13.875Z" fill="#999999"/>
                </svg>
            </a>
        </div>
        <div class="prm">
            <a href="#">
                <span class="big_premi">Премиум</span>
                <svg class="small_premi" height="512" viewBox="0 0 192 192" width="512"><circle cx="96" cy=
                        "32" r="8"/><circle cx="184" cy="72" r="8"/><circle cx="8" cy="72" r="8"/><path
                        d="m23.805 160h144.395l15.61-70.265a8 8 0 0 0
                                -12.248-8.391l-40.462 26.972-27.945-55.894a8 8
                                0 0 0 -14.31 0l-27.945 55.894-40.463-26.972a8 8 0 0 0 -12.247 8.391z"/>
                    <path d="m24 168v16a8 8 0 0 0 8 8h128a8 8 0 0 0 8-8v-16z"/></svg>
            </a>
        </div>
        <div class="msg">
            <a href="{{ route('messages') }}">
                <svg xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" viewBox="0 0 48 48" version="1.1" id="svg13994" sodipodi:docname="SMS22323.svg" inkscape:version="1.1.1 (3bf5ae0d25, 2021-09-20)">
                    <sodipodi:namedview id="namedview14602" pagecolor="#ffffff" bordercolor="#666666" borderopacity="1.0" inkscape:pageshadow="2" inkscape:pageopacity="0.0" inkscape:pagecheckerboard="0" showgrid="false" inkscape:zoom="17.333333" inkscape:cx="23.971154" inkscape:cy="24.028846" inkscape:window-width="1920" inkscape:window-height="1017" inkscape:window-x="-8" inkscape:window-y="-8" inkscape:window-maximized="1" inkscape:current-layer="g13988"/>
                    <defs id="defs13954">
                        <linearGradient id="linearGradient3764" x1="1" x2="47" gradientUnits="userSpaceOnUse" gradientTransform="matrix(0,-1,1,0,-1.5e-6,47.999998)">
                            <stop stop-color="#2cadc8" stop-opacity="1" id="stop13939"/>
                            <stop offset="1" stop-color="#35b6d3" stop-opacity="1" id="stop13941"/>
                        </linearGradient>
                        <clipPath id="clipPath-247352994">
                            <g transform="translate(0,-1004.3622)" id="g13946">
                                <path d="m -24 13 c 0 1.105 -0.672 2 -1.5 2 -0.828 0 -1.5 -0.895 -1.5 -2 0 -1.105 0.672 -2 1.5 -2 0.828 0 1.5 0.895 1.5 2 z" transform="matrix(15.333333,0,0,11.5,414.99999,878.8622)" fill="#1890d0" id="path13944"/>
                            </g>
                        </clipPath>
                        <clipPath id="clipPath-252752283">
                            <g transform="translate(0,-1004.3622)" id="g13951">
                                <path d="m -24 13 c 0 1.105 -0.672 2 -1.5 2 -0.828 0 -1.5 -0.895 -1.5 -2 0 -1.105 0.672 -2 1.5 -2 0.828 0 1.5 0.895 1.5 2 z" transform="matrix(15.333333,0,0,11.5,414.99999,878.8622)" fill="#1890d0" id="path13949"/>
                            </g>
                        </clipPath>
                    </defs>
                    <g id="g13992">
                        <path d="m 40.03 7.531 c 3.712 4.084 5.969 9.514 5.969 15.469 0 12.703 -10.297 23 -23 23 c -5.954 0 -11.384 -2.256 -15.469 -5.969 4.178 4.291 10.01 6.969 16.469 6.969 c 12.703 0 23 -10.298 23 -23 0 -6.462 -2.677 -12.291 -6.969 -16.469 z" opacity="0.1" id="path13990"/>
                    </g>
                    <g id="g13966">
                        <path d="m 24 1 c 12.703 0 23 10.297 23 23 c 0 12.703 -10.297 23 -23 23 -12.703 0 -23 -10.297 -23 -23 0 -12.703 10.297 -23 23 -23 z" fill="url(#linearGradient3764)" fill-opacity="1" id="path13964"/>
                    </g>
                    <g id="g13962">
                        <path d="m 36.31 5 c 5.859 4.062 9.688 10.831 9.688 18.5 c 0 12.426 -10.07 22.5 -22.5 22.5 c -7.669 0 -14.438 -3.828 -18.5 -9.688 c 1.037 1.822 2.306 3.499 3.781 4.969 c 4.085 3.712 9.514 5.969 15.469 5.969 c 12.703 0 23 -10.298 23 -23 c 0 -5.954 -2.256 -11.384 -5.969 -15.469 c -1.469 -1.475 -3.147 -2.744 -4.969 -3.781 z m 4.969 3.781 c 3.854 4.113 6.219 9.637 6.219 15.719 c 0 12.703 -10.297 23 -23 23 c -6.081 0 -11.606 -2.364 -15.719 -6.219 c 4.16 4.144 9.883 6.719 16.219 6.719 c 12.703 0 23 -10.298 23 -23 c 0 -6.335 -2.575 -12.06 -6.719 -16.219 z" opacity="0.05" id="path13956"/>
                        <path d="m 41.28 8.781 c 3.712 4.085 5.969 9.514 5.969 15.469 c 0 12.703 -10.297 23 -23 23 c -5.954 0 -11.384 -2.256 -15.469 -5.969 c 4.113 3.854 9.637 6.219 15.719 6.219 c 12.703 0 23 -10.298 23 -23 c 0 -6.081 -2.364 -11.606 -6.219 -15.719 z" opacity="0.1" id="path13958"/>
                        <path d="m 31.25 2.375 c 8.615 3.154 14.75 11.417 14.75 21.13 c 0 12.426 -10.07 22.5 -22.5 22.5 c -9.708 0 -17.971 -6.135 -21.12 -14.75 a 23 23 0 0 0 44.875 -7 a 23 23 0 0 0 -16 -21.875 z" opacity="0.2" id="path13960"/>
                    </g>
                    <g id="g13968"/>
                    <g id="g13980">
                        <g clip-path="url(#clipPath-247352994)" id="g13978">
                            <g transform="translate(1,1)" id="g13976">
                                <g opacity="0.1" id="g13974">
                                    <!-- color: #35b6d3 -->
                                    <g id="g13972">
                                        <path d="m 13.438 13 c -0.809 0 -1.438 0.629 -1.438 1.438 l 0 15.875 c 0 0.809 0.629 1.438 1.438 1.438 l 4.563 0 l 0 5.25 l 6 -5.25 l 10.563 0 c 0.809 0 1.441 -0.629 1.441 -1.438 l 0 -15.875 c 0 -0.809 -0.66 -1.438 -1.473 -1.438 m -21.09 0" fill="#000" stroke="none" fill-rule="nonzero" fill-opacity="1" id="path13970"/>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                    <g id="g13988">
                        <g clip-path="url(#clipPath-252752283)" id="g13986">
                            <!-- color: #35b6d3 -->
                            <g id="g13984">
                                <path d="m 13.438 13 c -0.809 0 -1.438 0.629 -1.438 1.438 l 0 15.875 c 0 0.809 0.629 1.438 1.438 1.438 l 4.563 0 l 0 5.25 l 6 -5.25 l 10.563 0 c 0.809 0 1.441 -0.629 1.441 -1.438 l 0 -15.875 c 0 -0.809 -0.66 -1.438 -1.473 -1.438 m -21.09 0" fill="#f9f9f9" stroke="none" fill-rule="nonzero" fill-opacity="1" id="path13982"/>
                            </g>
                        </g>
                        <path style="fill:#f9f9f9;fill-opacity:0.956863;stroke-width:0.0576923" d="m 18.028846,34.340028 v -2.604636 l -2.495192,-0.01673 c -2.430184,-0.0163 -2.501205,-0.01998 -2.725962,-0.141259 -0.3021,-0.163015 -0.5139,-0.391925 -0.658793,-0.712014 -0.116438,-0.257229 -0.11753,-0.334871 -0.118786,-8.44523 -9.76e-4,-6.309727 0.01498,-8.240033 0.06964,-8.423077 0.09671,-0.323874 0.577446,-0.812268 0.881019,-0.895046 0.162829,-0.0444 3.415487,-0.06286 11.048077,-0.06269 l 10.817308,2.42e-4 0.29167,0.135817 c 0.341132,0.158849 0.67154,0.519127 0.766374,0.835658 0.04947,0.165129 0.06663,2.360647 0.06569,8.409091 -0.0013,8.110359 -0.0023,8.188001 -0.118786,8.44523 -0.144893,0.320089 -0.356693,0.548999 -0.658793,0.712014 -0.230699,0.124486 -0.232461,0.124533 -5.740385,0.15337 l -5.509615,0.02885 -2.798077,2.44609 c -1.538943,1.345348 -2.869471,2.511984 -2.956731,2.592524 l -0.158654,0.146435 v -2.604636 z" id="path15113"/>
                        <path style="fill:#23869a;fill-opacity:1;stroke-width:0.0576923" d="m 19.01191,37.061826 0.0023,-0.917595 2.47931,-2.163462 2.479309,-2.163461 5.494358,-0.02885 5.494356,-0.02885 0.309823,-0.171598 c 0.170402,-0.09438 0.371094,-0.251932 0.445983,-0.350116 0.327836,-0.429815 0.311502,0.03631 0.311502,-8.889385 0,-4.517645 0.01427,-8.213901 0.03171,-8.213901 0.08933,0 0.364642,0.185493 0.527865,0.355655 0.403936,0.421107 0.373899,-0.224338 0.40765,8.75973 0.03416,9.092609 0.05786,8.527765 -0.378184,9.011402 -0.433011,0.480269 0.01555,0.447442 -6.133507,0.44887 l -5.457926,0.0013 -2.907458,2.545771 c -1.599102,1.400174 -2.952891,2.585448 -3.00842,2.633942 -0.09563,0.08351 -0.10084,0.0397 -0.09867,-0.829425 z" id="path18029"/>
                        <path style="fill:#23869a;fill-opacity:1;stroke-width:0.0576923" d="m 13.775639,32.564488 c -0.298686,-0.148842 -0.54425,-0.409663 -0.651644,-0.692131 -0.05011,-0.131812 -0.04515,-0.149505 0.03349,-0.119327 0.05078,0.01949 1.154632,0.03543 2.452999,0.03543 H 17.97115 V 32.25 32.711538 h -1.950211 -1.950211 z" id="path18068"/>
                    </g>
                </svg>
                @if($user->totalUnreadMessages())
                <span>{{ $user->totalUnreadMessages() }}</span>
                @endif
            </a>
        </div>
        <div class="cos">
            <a href="{{ route('account.event') }}">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" viewBox="0 0 48 48" version="1.1" id="svg1167">
                    <defs id="defs1127">
                        <linearGradient id="linearGradient3764" x1="1" x2="47" gradientUnits="userSpaceOnUse" gradientTransform="rotate(-90,23.999998,24)">
                            <stop stop-color="#2cadc8" stop-opacity="1" id="stop1112"/>
                            <stop offset="1" stop-color="#35b6d3" stop-opacity="1" id="stop1114"/>
                        </linearGradient>
                        <clipPath id="clipPath-247352994">
                            <g transform="translate(0,-1004.3622)" id="g1119">
                                <path d="m -24 13 c 0 1.105 -0.672 2 -1.5 2 -0.828 0 -1.5 -0.895 -1.5 -2 0 -1.105 0.672 -2 1.5 -2 0.828 0 1.5 0.895 1.5 2 z" transform="matrix(15.333333,0,0,11.5,414.99999,878.8622)" fill="#1890d0" id="path1117"/>
                            </g>
                        </clipPath>
                        <clipPath id="clipPath-252752283">
                            <g transform="translate(0,-1004.3622)" id="g1124">
                                <path d="m -24 13 c 0 1.105 -0.672 2 -1.5 2 -0.828 0 -1.5 -0.895 -1.5 -2 0 -1.105 0.672 -2 1.5 -2 0.828 0 1.5 0.895 1.5 2 z" transform="matrix(15.333333,0,0,11.5,414.99999,878.8622)" fill="#1890d0" id="path1122"/>
                            </g>
                        </clipPath>
                        <style id="style5694">.cls-1{fill:none;}</style>
                        <style id="style6544">.cls-1{fill:none;}</style>
                    </defs>
                    <g id="g1135">
                        <path d="m 36.31 5 c 5.859 4.062 9.688 10.831 9.688 18.5 c 0 12.426 -10.07 22.5 -22.5 22.5 c -7.669 0 -14.438 -3.828 -18.5 -9.688 c 1.037 1.822 2.306 3.499 3.781 4.969 c 4.085 3.712 9.514 5.969 15.469 5.969 c 12.703 0 23 -10.298 23 -23 c 0 -5.954 -2.256 -11.384 -5.969 -15.469 c -1.469 -1.475 -3.147 -2.744 -4.969 -3.781 z m 4.969 3.781 c 3.854 4.113 6.219 9.637 6.219 15.719 c 0 12.703 -10.297 23 -23 23 c -6.081 0 -11.606 -2.364 -15.719 -6.219 c 4.16 4.144 9.883 6.719 16.219 6.719 c 12.703 0 23 -10.298 23 -23 c 0 -6.335 -2.575 -12.06 -6.719 -16.219 z" opacity="0.05" id="path1129"/>
                        <path d="m 41.28 8.781 c 3.712 4.085 5.969 9.514 5.969 15.469 c 0 12.703 -10.297 23 -23 23 c -5.954 0 -11.384 -2.256 -15.469 -5.969 c 4.113 3.854 9.637 6.219 15.719 6.219 c 12.703 0 23 -10.298 23 -23 c 0 -6.081 -2.364 -11.606 -6.219 -15.719 z" opacity="0.1" id="path1131"/>
                        <path d="m 31.25 2.375 c 8.615 3.154 14.75 11.417 14.75 21.13 c 0 12.426 -10.07 22.5 -22.5 22.5 c -9.708 0 -17.971 -6.135 -21.12 -14.75 a 23 23 0 0 0 44.875 -7 a 23 23 0 0 0 -16 -21.875 z" opacity="0.2" id="path1133"/>
                    </g>
                    <g id="g1139" transform="translate(0.05769231,-0.05769231)" style="fill:#ff8c3c;fill-opacity:0.93574297">
                        <path d="M 24,1 C 36.703,1 47,11.297 47,24 47,36.703 36.703,47 24,47 11.297,47 1,36.703 1,24 1,11.297 11.297,1 24,1 Z" fill="url(#linearGradient3764)" fill-opacity="1" id="path1137" style="fill:#ff8c3c;fill-opacity:0.93574297"/>
                    </g>
                    <g id="g1141"/>
                    <g id="g1165">
                        <path d="m 40.03 7.531 c 3.712 4.084 5.969 9.514 5.969 15.469 0 12.703 -10.297 23 -23 23 c -5.954 0 -11.384 -2.256 -15.469 -5.969 4.178 4.291 10.01 6.969 16.469 6.969 c 12.703 0 23 -10.298 23 -23 0 -6.462 -2.677 -12.291 -6.969 -16.469 z" opacity="0.1" id="path1163"/>
                    </g>
                    <g id="g5721" transform="translate(8.03,7.531)">
                        <g data-name="Layer 2" id="Layer_2">
                            <path d="m 16,29 a 4,4 0 0 1 -4,-4 1,1 0 0 1 1,-1 h 6 a 1,1 0 0 1 1,1 4,4 0 0 1 -4,4 z m -1.73,-3 a 2,2 0 0 0 3.46,0 z" id="path5700"/>
                            <path d="M 18,7 H 14 A 1,1 0 0 1 13,6 3,3 0 0 1 19,6 1,1 0 0 1 18,7 Z M 16,5 Z" id="path5702"/>
                            <path d="M 27,26 H 5 A 1,1 0 0 1 4,25 7,7 0 0 1 7,19.25 V 14 a 9,9 0 0 1 8.94,-9 h 0.11 a 9,9 0 0 1 9,9 v 5.25 A 7,7 0 0 1 28,25 v 0 a 1,1 0 0 1 -1,1 z M 6.1,24 H 25.9 A 5,5 0 0 0 23.5,20.67 1,1 0 0 1 23,19.8 V 14 A 7,7 0 1 0 9,14 v 5.8 A 1,1 0 0 1 8.5,20.67 5,5 0 0 0 6.1,24 Z" id="path5704"/>
                        </g>
                        <g id="frame">
                            <rect class="cls-1" height="32" width="32" id="rect5707" x="0" y="0"/>
                        </g>
                    </g>
                    <g id="g6571" transform="translate(8.90085,8.165742)">
                        <g data-name="Layer 2" id="Layer_2-7" style="fill:#e27b35;fill-opacity:0.95686275">
                            <path d="m 16,29 a 4,4 0 0 1 -4,-4 1,1 0 0 1 1,-1 h 6 a 1,1 0 0 1 1,1 4,4 0 0 1 -4,4 z m -1.73,-3 a 2,2 0 0 0 3.46,0 z" id="path6550" style="fill:#e27b35;fill-opacity:0.95686275"/>
                            <path d="M 18,7 H 14 A 1,1 0 0 1 13,6 3,3 0 0 1 19,6 1,1 0 0 1 18,7 Z M 16,5 Z" id="path6552" style="fill:#e27b35;fill-opacity:0.95686275"/>
                            <path d="M 27,26 H 5 A 1,1 0 0 1 4,25 7,7 0 0 1 7,19.25 V 14 a 9,9 0 0 1 8.94,-9 h 0.11 a 9,9 0 0 1 9,9 v 5.25 A 7,7 0 0 1 28,25 v 0 a 1,1 0 0 1 -1,1 z M 6.1,24 H 25.9 A 5,5 0 0 0 23.5,20.67 1,1 0 0 1 23,19.8 V 14 A 7,7 0 1 0 9,14 v 5.8 A 1,1 0 0 1 8.5,20.67 5,5 0 0 0 6.1,24 Z" id="path6554" style="fill:#e27b35;fill-opacity:0.95686275"/>
                        </g>
                        <g id="frame-7">
                            <rect class="cls-1" height="32" width="32" id="rect6557" x="0" y="0"/>
                        </g>
                    </g>
                    <path style="fill:#ffffff;fill-opacity:1;stroke-width:0.0815892" d="m 23.751075,10.4984 c -1.241515,-0.02853 -2.29568,0.995538 -2.684225,2.116716 -0.116792,0.568208 -0.833906,0.536066 -1.237636,0.84201 -2.10637,1.121239 -3.76544,3.050835 -4.481791,5.334344 -0.585421,1.683084 -0.33502,3.502731 -0.427301,5.248802 -0.01008,0.905354 -0.02016,1.810706 -0.03024,2.71606 -1.77502,1.280957 -2.921952,3.440431 -2.941675,5.632845 -0.06846,0.649971 0.480373,1.275688 1.147277,1.221047 2.333306,0.09613 4.670188,0.07092 7.004955,0.112587 0.547393,1.753821 2.328544,3.075796 4.191169,2.880323 1.662709,-0.03438 3.094812,-1.268831 3.648092,-2.793893 0.327066,-0.286123 0.987355,-0.04728 1.446136,-0.12589 1.963852,-0.03013 3.937425,0.06705 5.895301,-0.09567 0.696528,-0.155407 0.993859,-0.923403 0.834645,-1.566723 -0.13246,-1.734424 -0.875593,-3.421326 -2.164932,-4.605216 -0.324666,-0.400046 -0.950173,-0.673786 -0.791963,-1.276564 -0.06038,-2.13647 0.04528,-4.283052 -0.139461,-6.413223 -0.604036,-3.008405 -2.849544,-5.557589 -5.672365,-6.705137 -0.476943,-0.212234 -0.410136,-0.850496 -0.738128,-1.207744 -0.561343,-0.836059 -1.550343,-1.438359 -2.581375,-1.315627 -0.09216,3.19e-4 -0.184319,6.38e-4 -0.276479,9.56e-4 z m 0.276957,4.218738 c 1.140366,-0.06837 2.273779,0.188149 3.267872,0.76952 1.709444,0.892227 2.975729,2.570223 3.436947,4.432432 0.242314,2.243509 0.101445,4.514252 0.209399,6.769466 0.05843,0.519413 -0.106322,1.211945 0.446503,1.519651 1.147268,0.740735 2.107909,1.865165 2.406085,3.216878 -6.508853,-0.0068 -13.017857,0.01798 -19.5266,-0.02597 0.315333,-1.381063 1.316831,-2.476643 2.465318,-3.245736 0.548992,-0.380715 0.290254,-1.149933 0.387044,-1.708512 0.06599,-2.099626 0.02704,-4.210781 0.113926,-6.303773 0.501518,-1.848057 1.592414,-3.594091 3.308913,-4.534916 1.002943,-0.60151 2.14104,-0.959533 3.304204,-0.888269 0.06013,-2.49e-4 0.12026,-5.23e-4 0.180389,-7.67e-4 z M 23.132463,33.69588 c 0.785251,-3.85e-4 1.570391,0.01506 2.355571,0.02358 -0.712644,0.897754 -2.227375,0.892979 -2.92558,-0.01721 0.189942,-0.0055 0.380008,-0.0053 0.570009,-0.0064 z" id="path6466"/>
                    <path style="fill:#ffffff;fill-opacity:1;stroke-width:0.0441942" d="m 24.814597,14.587271 c -2.777106,-0.0029 -5.492544,1.67544 -6.68829,4.188877 -0.485928,0.929074 -0.788887,1.963148 -0.769613,3.017863 -0.054,2.10141 -0.0065,4.217339 -0.0848,6.303125 -0.198416,0.333167 -0.618945,0.46219 -0.890702,0.734815 -0.768002,0.627455 -1.379467,1.453955 -1.71798,2.3851 -0.138026,0.454763 0.35328,0.825846 0.781163,0.741693 6.11183,0.07326 12.224391,0.04462 18.336547,0.0633 0.467916,-0.0066 0.72559,-0.538955 0.526721,-0.938616 -0.388556,-1.444193 -1.460807,-2.585952 -2.659522,-3.423314 -0.145187,-0.218649 -0.07256,-0.526331 -0.110672,-0.777018 -0.08931,-2.177465 -0.03216,-4.363952 -0.183536,-6.541736 -0.121025,-1.362441 -0.813564,-2.612681 -1.662938,-3.65806 -0.707607,-0.849713 -1.624779,-1.575861 -2.724614,-1.819942 -0.700455,-0.188903 -1.426068,-0.284802 -2.151764,-0.276087 z" id="path3277"/>
                    <path style="fill:#ffffff;fill-opacity:1;stroke-width:0.03125" d="m 23.014892,14.050537 c -1.364348,0.13926 -2.655945,0.745498 -3.731975,1.580734 -1.387575,1.114248 -2.304902,2.741293 -2.737916,4.450778 -0.04586,1.261412 -0.03709,2.526108 -0.06717,3.786908 -0.02839,1.217348 -0.03203,2.442317 -0.10069,3.649781 -0.260748,0.269082 -0.604906,0.459878 -0.877563,0.723389 -0.901751,0.782084 -1.626076,1.812922 -1.892578,2.988403 -0.08881,0.419146 0.256634,0.845797 0.684936,0.844482 0.358636,0.03301 0.789027,-0.04427 0.958233,-0.404281 0.09345,-0.245566 0.151929,-0.507604 0.291354,-0.737304 0.418798,-0.815434 1.079244,-1.485352 1.841539,-1.985429 0.310613,-0.235531 0.622896,-0.56337 0.579171,-0.98426 0.06202,-2.172008 0.04917,-4.372498 0.108024,-6.524224 0.02234,-1.075407 0.489045,-2.083345 1.028234,-2.992654 0.920941,-1.484778 2.458685,-2.527637 4.13241,-2.982382 0.417791,-0.101707 0.688185,-0.595603 0.498171,-0.991083 -0.118953,-0.278796 -0.415683,-0.443248 -0.714175,-0.422858 z" id="path5619"/>
                    <path style="fill:#ffffff;fill-opacity:1;stroke-width:0.03125" d="m 23.889892,10.504639 c -0.949464,-0.04515 -1.847363,0.497124 -2.369208,1.270452 -0.256776,0.333742 -0.381932,0.740521 -0.573602,1.107783 -0.29128,0.307982 -0.749961,0.348185 -1.09585,0.577742 -2.15569,1.148185 -3.865435,3.151138 -4.541285,5.510319 -0.411448,1.223954 -0.31057,2.531276 -0.351512,3.801099 -0.02112,1.33309 -0.02202,2.666833 -0.07391,3.999206 -1.840786,1.356555 -3.016348,3.630858 -2.906433,5.931519 0.09308,0.50296 0.566669,0.923415 1.091253,0.887314 2.307081,0.108504 4.618461,0.05395 6.926012,0.137202 0.20766,-0.03113 0.172044,0.259384 0.26705,0.387531 0.651123,1.505043 2.25867,2.606506 3.922167,2.477967 1.155641,0.0077 2.26214,-0.586511 2.980314,-1.477055 0.3442,-0.38238 0.574882,-0.852483 0.774996,-1.317806 0.31314,-0.200891 0.710429,-0.09803 1.061461,-0.13194 2.01115,-0.02141 4.024397,0.02847 6.033904,-0.06964 0.547393,0.0218 1.002609,-0.441067 1.077426,-0.964495 0.05647,-0.566639 -0.06818,-1.136361 -0.177983,-1.690668 -0.298254,-1.323895 -0.974402,-2.568527 -1.972103,-3.495184 -0.261098,-0.280885 -0.57521,-0.527928 -0.774963,-0.857483 -0.08236,-0.602869 -0.03532,-1.218343 -0.0567,-1.826168 -0.02567,-1.58373 0.01276,-3.170983 -0.10568,-4.751549 -0.131431,-1.209347 -0.648092,-2.348751 -1.301244,-3.36314 -0.978295,-1.487372 -2.372905,-2.697112 -3.991699,-3.440918 -0.291219,-0.138505 -0.648689,-0.248864 -0.758041,-0.592112 -0.172998,-0.515883 -0.447789,-1.001687 -0.866655,-1.358219 -0.516858,-0.467182 -1.198073,-0.795919 -1.907953,-0.750784 -0.103237,-0.0023 -0.206508,-0.0022 -0.309759,-9.72e-4 z M 24.2854,33.694092 c 0.404963,0.0067 0.812119,-0.0098 1.2146,0.04443 -0.564769,0.638704 -1.558486,0.859308 -2.323364,0.461243 -0.251134,-0.112767 -0.456398,-0.299676 -0.644349,-0.496643 0.58437,-0.003 1.168732,-0.0093 1.753113,-0.009 z" id="path5697"/>
                </svg>

                @if($user->totalUnreadNotifications())
                <span>{{ $user->totalUnreadNotifications() }}</span>
                @endif

            </a>
        </div>
    </div>
</div>

<div class="coast">
    <a href="{{ route('finance') }}">{{ $user->balance }}р.</a>
</div>
<div class="akk">

    <div class="dropdown_gor">
        <a  class="dropbtn">
            <img src="{{ thumbnail($user->getImage(), 100) }}" class="avatarka" alt="studentik.online">
            <span class="log_name">{{ $user->login }}</span>
            <img src="{{ asset('catalog/assets/img/icons/arrow.svg') }}" class="arrow" alt="studentik.online">
        </a>
        <div class="dropdown-content">
            <a href="{{ route('account.profile', $user->login) }}">Профиль</a>
            <a href="{{ route('account.setting') }}">Настройка</a>
            <a href="{{ route('account.password') }}">Сменить пароль</a>
            <a href="{{ route('sign_in.logout') }}">Выход</a>
        </div>
    </div>
</div>

@else
<div class="akk_enter">
    <div class="login">
        <div class="btn_split clearfix">
            <a href="{{ route('sign_in', ['act' => 'register']) }}" class="regs">Регистрация</a>
            <a href="{{ route('sign_in') }}" class="in">Вход</a>
        </div>
    </div>
</div>
@endif
