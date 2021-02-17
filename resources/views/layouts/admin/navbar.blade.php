
 <nav class="main-header navbar navbar-expand navbar-dark navbar-olive">
    <!-- Left navbar links -->


    <!-- SEARCH FORM -->


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->

        <!-- Notifications Dropdown Menu -->

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-home"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#"><span class="dropdown-item dropdown-header">My Profile</span> </a>
            <div class="dropdown-divider"></div>
            <a href="Javascript::void(0)" class="dropdown-item">
                <i class="fas fa-user mr-2"></i>


                    {{ Auth::user()->name }}

            </a>

            <div class="dropdown-divider"></div>
            <a href="" class="dropdown-item dropdown-footer" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>

    </ul>
</nav>
