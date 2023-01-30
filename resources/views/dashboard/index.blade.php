<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="generator" content="Hugo 0.88.1" />
    <title>Vela</title>
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{asset('dashboard/css/all.min.css')}}" />
    <!-- Bootstrap CSS -->
    <link href="{{asset('dashboard/css/bootstrap.rtl.min.css')}}" rel="stylesheet" />
    <!-- Google Fonts Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400&display=swap"
      rel="stylesheet"
    />
    <!-- Google Fonts Reem Kufi -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Reem+Kufi:wght@400;500;700&display=swap"
      rel="stylesheet"
    />
    <!-- Custom styles -->
    <link href="{{asset('dashboard/css/dashboard.rtl.css')}}" rel="stylesheet" />
  </head>
  <body>
  @guest
      @if (Route::has('login'))
          <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
          </li>
      @endif
      @if (Route::has('register'))
          <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
          </li>
      @endif
  @else
      <header class="navbar navbar-dark flex-md-nowrap shadow">
          <button
              class="navbar-toggler d-md-none collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#sidebarMenu"
              aria-controls="sidebarMenu"
              aria-expanded="false"
              aria-label="عرض/إخفاء لوحة التنقل"
          >
              <i class="fa-solid fa-bars fa-xl"></i>
          </button>
          <div class="my-logo">
              <a class="navbar-brand me-0" href=""
              ><img src="{{asset('dashboard/imgs/logo.svg')}}" width="110px" alt="logo"
                  /></a>
          </div>
      </header>
      <!-- EXIT MODAL -->
      <div
          class="modal fade"
          id="exitModal"
          tabindex="-1"
          aria-labelledby="exampleModalLabel"
          aria-hidden="true"
      >
          <div class="modal-dialog modal-dialog-centered modal-sm">
              <div class="modal-content">
                  <div class="modal-header">
                      <button
                          type="button"
                          class="btn-close"
                          data-bs-dismiss="modal"
                          aria-label="Close"
                      ></button>
                  </div>
                  <div class="modal-body" id="dash_exit_content">Are you sure you want to log out?</div>
                  <div class="modal-footer">
                      <button
                          type="button"
                          class="btn btn-secondary"
                          data-bs-dismiss="modal"
                          id="delete_modal_no"
                      >
                          No
                      </button>
                      <a class="btn btn-primary" href="{{ route('logout') }}"
                         onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" id="delete_modal_yes">
                          Yes
                      </a>

                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                          @csrf
                      </form>
                  </div>
              </div>
          </div>
      </div>
      <div class="container-fluid">
          <div class="row main-row">
              <nav id="sidebarMenu" class="d-md-flex sidebar collapse">
                  <div class="position-sticky pt-3">
                      <div class="logo-darkmode">
                          <a class="navbar-brand me-0" href="">
                              <img src="{{asset('dashboard/imgs/logo.svg')}}" width="110px" alt="logo" />
                          </a>
                          <button class="btn-compressed">
                              <div class="arrow-right">
                                  <i class="fa-solid fa-arrow-right fa-lg"></i>
                              </div>
                              <div class="arrow-left" style="display: none">
                                  <i class="fa-solid fa-arrow-left fa-lg"></i>
                              </div>
                          </button>
                      </div>
                      <input type="text" class="search-input" id="dash_search" placeholder="Search">
                      <ul class="nav flex-column">

                          <li class="nav-item">
                              <a
                                  id="reports-link"
                                  class="nav-link active"
                                  aria-current="page"
                                  title="Reports"
                              >
                                  <i class="fa-solid fa-clipboard-list"></i>
                                  <span class="nav-link-name" id="dash_reports">Reports</span>
                              </a>
                          </li>
{{--                          @hasrole('Super Admin')--}}

{{--                          <li class="nav-item">--}}
{{--                              <a id="tubes-link" class="nav-link" title="Contact">--}}
{{--                                  <i class="fa-solid fa-phone-volume"></i>--}}
{{--                                  <span class="nav-link-name" id="dash_contact">Contact</span>--}}
{{--                              </a>--}}
{{--                          </li>--}}
{{--                          @endhasrole--}}


                          <li class="nav-item">
                              <a id="admins-link" class="nav-link" title="Admins">
                                  <i class="fa-solid fa-user-tie"></i>
                                  <span class="nav-link-name" id="dash_admins">Admins Company</span>
                              </a>
                          </li>

{{--                          <li class="nav-item">--}}
{{--                              <a id="companies-link" class="nav-link" title="المحادثات">--}}
{{--                                  <i class="fa-solid fa-comments"></i>--}}
{{--                                  <span class="nav-link-name">المحادثات</span>--}}
{{--                              </a>--}}
{{--                          </li>--}}
                          @hasrole('Admin')

                          <li class="nav-item">
                              <a id="chats-link" class="nav-link" title="Chats">
                                  <i class="fa-solid fa-comment"></i>
                                  <span class="nav-link-name" id="dash_chats">Peer to Peer Chats</span>
                              </a>
                          </li>


                          <li class="nav-item">
                              <a id="groups-chats-link" class="nav-link" title="Chats">
                                  <i class="fa-solid fa-comments"></i>
                                  <span class="nav-link-name" id="dash_groups_chats">Groups Chats</span>
                              </a>
                          </li>
                          @endhasrole

                          @hasrole('Super Admin Company')

                          <li class="nav-item">
                              <a id="chats-link" class="nav-link" title="Chats">
                                  <i class="fa-solid fa-comment"></i>
                                  <span class="nav-link-name" id="dash_chats">Peer to Peer Chats</span>
                              </a>
                          </li>


                          <li class="nav-item">
                              <a id="groups-chats-link" class="nav-link" title="Chats">
                                  <i class="fa-solid fa-comments"></i>
                                  <span class="nav-link-name" id="dash_groups_chats">Groups Chats</span>
                              </a>
                          </li>
                          @endhasrole

                          <li class="nav-item">
                              <a id="users-link" class="nav-link" title="Employees">
                                  <i class="fa-solid fa-users"></i>
                                  <span class="nav-link-name" id="dash_employees">Employees</span>
                              </a>
                          </li>

                          <li class="nav-item">
                              <a id="store-link" class="nav-link" title="Meeting">
                                  <i class="fa-solid fa-video"></i>
                                  <span class="nav-link-name" id="dash_meeting">Meeting</span>
                              </a>
                          </li>


                          <li class="nav-item">
                            <div class="select-lang">
                            <i class="fa-solid fa-language" style="font-size: 21px;"></i>
                            <select name="lang" id="lang" onchange="changeLanguage()" class="input-select-lang">
                                <option value="en">English</option>
                                <option value="ar">العربية</option>
                            </select>
                            </div>
                          </li>


                      </ul>
                  </div>
                  <div class="profile" id="dash_profile">
                      @if(auth()->user()->image == null)
                          <img src="{{asset('dashboard/imgs/elliot.webp')}}" alt="profile picture" />
                      @else
                          <img src="{{asset('storage/admins/'.auth()->user()->image)}}" alt="profile picture" />
                      @endif
                      <div class="username">
                          <strong>{{auth()->user()->full_name}}</strong>
                          <span>{{auth()->user()->job}}</span>
                      </div>
                      <a
                          id="logout"
                          class="nav-link logout"
                          data-bs-toggle="modal"
                          data-bs-target="#exitModal"
                          onclick="changeModalLang()"
                      >
                          <i class="fa-solid fa-arrow-right-from-bracket"></i>
                      </a>
                  </div>
              </nav>
              <main class="main-content">
                  <iframe
                      src="{{route('reports')}}"
                      frameborder="0"
                      width="100%"
                      height="100%"
                      name="myiframe"
                  ></iframe>
              </main>
          </div>
      </div>
      <script src="{{asset('dashboard/js/bootstrap.bundle.min.js')}}"></script>
      <script src="{{asset('dashboard/js/all.min.js')}}"></script>
      <script src="{{asset('dashboard/js/dashboard.js')}}"></script>
  @endguest


  </body>
</html>
