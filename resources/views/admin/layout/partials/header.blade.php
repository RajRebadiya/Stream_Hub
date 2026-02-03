 <!-- Topbar Start -->
 <header class="app-topbar">
     <div class="container-fluid topbar-menu">
         <div class="d-flex align-items-center gap-2">
             <!-- Topbar Brand Logo -->
             <div class="logo-topbar">
                 <!-- Logo light -->
                 <a href="index.html" class="logo-light">
                     <span class="logo-lg">
                         <img src="{{ asset('admin/assets/images/logo.png') }}" alt="logo" />
                     </span>
                     <span class="logo-sm">
                         <img src="{{ asset('admin/assets/images/logo-sm.png') }}" alt="small logo" />
                     </span>
                 </a>

                 <!-- Logo Dark -->
                 <a href="index.html" class="logo-dark">
                     <span class="logo-lg">
                         <img src="{{ asset('admin/assets/images/logo-black.png') }}" alt="dark logo" />
                     </span>
                     <span class="logo-sm">
                         <img src="{{ asset('admin/assets/images/logo-sm.png') }}" alt="small logo" />
                     </span>
                 </a>
             </div>

             <!-- Sidebar Menu Toggle Button -->
             <button class="sidenav-toggle-button btn btn-primary btn-icon">
                 <i class="ti ti-menu-4"></i>
             </button>

             <!-- Horizontal Menu Toggle Button -->
             <button class="topnav-toggle-button px-2" data-bs-toggle="collapse" data-bs-target="#topnav-menu">
                 <i class="ti ti-menu-4"></i>
             </button>



         </div>

         <div class="d-flex align-items-center gap-2">
             <div id="theme-dropdown" class="topbar-item d-none d-sm-flex">
                 <div class="dropdown">
                     <button class="topbar-link" data-bs-toggle="dropdown" type="button" aria-haspopup="false"
                         aria-expanded="false">
                         <i class="ti ti-sun topbar-link-icon d-none" id="theme-icon-light"></i>
                         <i class="ti ti-moon topbar-link-icon d-none" id="theme-icon-dark"></i>
                         <i class="ti ti-sun-moon topbar-link-icon d-none" id="theme-icon-system"></i>
                     </button>
                     <div class="dropdown-menu dropdown-menu-end" data-thememode="dropdown">
                         <label class="dropdown-item cursor-pointer">
                             <input class="form-check-input" type="radio" name="data-bs-theme" value="light"
                                 style="display: none" />
                             <i class="ti ti-sun align-middle me-1 fs-16"></i>
                             <span class="align-middle">Light</span>
                         </label>
                         <label class="dropdown-item cursor-pointer">
                             <input class="form-check-input" type="radio" name="data-bs-theme" value="dark"
                                 style="display: none" />
                             <i class="ti ti-moon align-middle me-1 fs-16"></i>
                             <span class="align-middle">Dark</span>
                         </label>
                         <label class="dropdown-item cursor-pointer">
                             <input class="form-check-input" type="radio" name="data-bs-theme" value="system"
                                 style="display: none" />
                             <i class="ti ti-sun-moon align-middle me-1 fs-16"></i>
                             <span class="align-middle">System</span>
                         </label>
                     </div>
                     <!-- end dropdown-menu-->
                 </div>
                 <!-- end dropdown-->
             </div>



             <div id="fullscreen-toggler" class="topbar-item d-none d-md-flex">
                 <button class="topbar-link" type="button" data-toggle="fullscreen">
                     <i class="ti ti-maximize topbar-link-icon"></i>
                     <i class="ti ti-minimize topbar-link-icon d-none"></i>
                 </button>
             </div>



             <div class="topbar-item d-none d-sm-flex">
                 <button class="topbar-link btn-theme-setting" data-bs-toggle="offcanvas"
                     data-bs-target="#theme-settings-offcanvas" type="button">
                     <i class="ti ti-settings topbar-link-icon"></i>
                 </button>
             </div>



             <div id="user-dropdown-detailed" class="topbar-item nav-user">
                 <div class="dropdown">
                     <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown"
                         href="#!" aria-haspopup="false" aria-expanded="false">
                         <img src="{{ asset('admin/assets/images/users/user-1.jpg') }}" width="32"
                             class="rounded-circle me-lg-2 d-flex" alt="user-image" />
                         <div class="d-lg-flex align-items-center gap-1 d-none">
                             <span>
                                 <h5 class="my-0 lh-1 pro-username">David Dev</h5>
                                 <span class="fs-xs lh-1">Admin Head</span>
                             </span>
                             <i class="ti ti-chevron-down align-middle"></i>
                         </div>
                     </a>
                     <div class="dropdown-menu dropdown-menu-end">
                         <!-- Header -->
                         <div class="dropdown-header noti-title">
                             <h6 class="text-overflow m-0">Welcome back 👋!</h6>
                         </div>

                         <!-- My Profile -->
                         <a href="#!" class="dropdown-item">
                             <i class="ti ti-user-circle me-1 fs-lg align-middle"></i>
                             <span class="align-middle">Profile</span>
                         </a>

                         <!-- Notifications -->
                         <a href="javascript:void(0);" class="dropdown-item">
                             <i class="ti ti-bell-ringing me-1 fs-lg align-middle"></i>
                             <span class="align-middle">Notifications</span>
                         </a>

                         <!-- Settings -->
                         <a href="javascript:void(0);" class="dropdown-item">
                             <i class="ti ti-settings-2 me-1 fs-lg align-middle"></i>
                             <span class="align-middle">Account Settings</span>
                         </a>

                         <!-- Support -->
                         <a href="javascript:void(0);" class="dropdown-item">
                             <i class="ti ti-headset me-1 fs-lg align-middle"></i>
                             <span class="align-middle">Support Center</span>
                         </a>

                         <!-- Divider -->
                         <div class="dropdown-divider"></div>

                         <!-- Lock -->
                         <a href="auth-lock-screen.html" class="dropdown-item">
                             <i class="ti ti-lock me-1 fs-lg align-middle"></i>
                             <span class="align-middle">Lock Screen</span>
                         </a>

                         <!-- Logout -->
                         <a href="{{ route('logout') }}" class="dropdown-item fw-semibold">
                             <i class="ti ti-logout me-1 fs-lg align-middle"></i>
                             <span class="align-middle">Log Out</span>
                         </a>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </header>
 <!-- Topbar End -->
