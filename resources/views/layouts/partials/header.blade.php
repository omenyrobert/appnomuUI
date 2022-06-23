<header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md" style="background-color: #294658;">
          <div class="navbar-header" style="background-color: #294658; color: #fff;">
            <!-- This is for the sidebar toggle which is visible on mobile only -->
            <a
              class="nav-toggler waves-effect waves-light d-block bg-black d-md-none"
              href="javascript:void(0)"
              ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
              </svg></a>
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <a class="navbar-brand" href="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/html/material/index.html">
              <!-- Logo icon -->
              <b class="logo-icon">
                <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                <!-- Dark Logo icon -->
            <img src="{{asset('../images/img/logo.png')}}" style="width: 200px;">
              </b>
              <!--End Logo icon -->
              <!-- Logo text -->
            </a>
            <a
              class="topbartoggler d-block d-md-none bg-black waves-effect waves-light"
              href="javascript:void(0)"
              data-bs-toggle="collapse"
              data-bs-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent"
              aria-expanded="false"
              aria-label="Toggle navigation"
              ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
              </svg></a>
          </div>
          <!-- ============================================================== -->
          <!-- End Logo -->
          <!-- ============================================================== -->
          <div class="navbar-collapse collapse" id="navbarSupportedContent" style="background-color: #113c56;">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav" style="margin-left: 20px; width: 90%;">
            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav">
              <!-- ============================================================== -->
              <!-- Comment -->
              <!-- ============================================================== -->
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle waves-effect waves-dark"
                  href="#"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  style="background-color: #294658; border-radius: 5px; height: 35px; margin-top: 20px;"
                >
                  <img src="{{asset('../images/img/bell.svg')}}" style="margin-top: -42px;">
                  <div class="notify" style="margin-top: -20px;">
                    <span class="heartbit"></span> <span class="point"></span>
                  </div>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle waves-effect waves-dark"
                  href="#"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  style="background-color: #294658; border-radius: 5px; height: 35px; margin-top: 20px; margin-left: 8px;"
                >
                  <img src="{{asset('../images/img/chat.svg')}}" style="margin-top: -42px;">
                  <div class="notify" style="margin-top: -20px;">
                    <span class="heartbit"></span> <span class="point"></span>
                  </div>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle waves-effect waves-dark"
                  href="#"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  style="background-color: #294658; border-radius: 5px; height: 35px; margin-top: 20px; margin-left: 8px;"
                >
                  <img src="{{asset('../images/img/gift.svg')}}" style="margin-top: -42px;">
                  <div class="notify" style="margin-top: -20px;">
                    <span class="heartbit"></span> <span class="point"></span>
                  </div>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle waves-effect waves-dark"
                  href="#"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  style="background-color: #294658; border-radius: 5px; height: 35px; margin-top: 20px; margin-left: 8px;"
                >
                  <img src="{{asset('../images/img/envelope.svg')}}" style="margin-top: -42px;">
                  <div class="notify" style="margin-top: -20px;">
                    <span class="heartbit"></span> <span class="point"></span>
                  </div>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle waves-effect waves-dark"
                  href="#"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <img
                    src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/assets/images/users/1.jpg"
                    alt="user"
                    width="30"
                    class="profile-pic rounded-circle"
                  />
                </a>
                <div
                  class="
                    dropdown-menu dropdown-menu-end
                    user-dd
                    animated
                    flipInY
                  "
                >
                  <div
                    class="
                      d-flex
                      no-block
                      align-items-center
                      p-3
                      bg-info
                      text-white
                      mb-2 bg-warning
                    "
                  >
                    <div class="">
                      <img
                        src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/assets/images/users/1.jpg"
                        alt="user"
                        class="rounded-circle"
                        width="60"
                      />
                    </div>
                    <div class="ms-2">
                      <h4 class="mb-0 text-white">Omeny Robert</h4>
                      <p class="mb-0">robert@gmail.com</p>
                    </div>
                  </div>
                  <a class="dropdown-item" href="/profile"
                    ><i
                      data-feather="user"
                      class="feather-sm text-info me-1 ms-1"
                    ></i>
                    My Profile</a
                  >
                  <a class="dropdown-item" href="#"
                    ><i
                      data-feather="credit-card"
                      class="feather-sm text-info me-1 ms-1"
                    ></i>
                    My Balance</a
                  >
                  <a class="dropdown-item" href="#"
                    ><i
                      data-feather="mail"
                      class="feather-sm text-success me-1 ms-1"
                    ></i>
                    Inbox</a
                  >
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#"
                    ><i
                      data-feather="settings"
                      class="feather-sm text-warning me-1 ms-1"
                    ></i>
                    Account Setting</a
                  >
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{route('user.logout')}}"
                    ><i
                      data-feather="log-out"
                      class="feather-sm text-danger me-1 ms-1"
                    ></i>
                    Logout</a
                  >
                  <div class="dropdown-divider"></div>
                  <div class="pl-4 p-2">
                    <a href="/profile" class="btn d-block w-100 btn-warning rounded-pill"
                      >View Profile</a
                    >
                  </div>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a
                  class="
                    nav-link
                    dropdown-toggle
                    text-muted
                    waves-effect waves-dark
                  "
                  href="#"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <i class="flag-icon flag-icon-us"></i
                ></a>
                <div
                  class="
                    dropdown-menu dropdown-menu-end dropdown-menu-animate-up
                  "
                >
                  <a class="dropdown-item" href="#"
                    ><i class="flag-icon flag-icon-in"></i> India</a
                  >
                  <a class="dropdown-item" href="#"
                    ><i class="flag-icon flag-icon-fr"></i> French</a
                  >
                  <a class="dropdown-item" href="#"
                    ><i class="flag-icon flag-icon-cn"></i> China</a
                  >
                  <a class="dropdown-item" href="#"
                    ><i class="flag-icon flag-icon-de"></i> Dutch</a
                  >
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>