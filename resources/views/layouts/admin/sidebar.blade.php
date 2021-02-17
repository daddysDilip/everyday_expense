<style>

[class*="sidebar-dark"] .brand-link {
	background-color: #fff;
}


</style>
<aside class="main-sidebar elevation-4 sidebar-light-danger">
  <!-- Brand Logo -->
  <a href="{{ route('home') }}" class="brand-link navbar-white">
    <img src="{{ asset('images/logo.png') }}" alt="Everyday Expense" class="brand-image ">
    <span class="logo-lg" style="color: #0aa046;">Everyday Expense</span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
  <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('backend/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="Javascript::void(0)" class="d-block">
          {{ ucfirst(Auth::user()->name) }}
      </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview ">
          <a href="{{ url('admin') }}" class="nav-link {{!empty($activeTab) ? ($activeTab == "dashboard" ? "active" : "") : ""}} " >
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-item has-treeview {{!empty($activeTab) ? ($activeTab == "Role" || $activeTab == "Country" || $activeTab == "Languages" || $activeTab == "Content" || $activeTab == "Category" || $activeTab == "manage_permissions" || $activeTab == "Modules" ? "menu-open " : "") : ""}}">
            <a href="#" class="nav-link {{!empty($activeTab) ? ($activeTab == "Role" || $activeTab == "Country" || $activeTab == "Languages" || $activeTab == "Content" || $activeTab == "Category" || $activeTab == "manage_permissions" || $activeTab == "Modules" ? "active " : "") : ""}}">
                <i class="nav-icon fas fa-cog"></i>
                <p>Settings <i class="right fas fa-angle-left"></i> </p>
            </a>
            <ul class="nav nav-treeview">

                <li class="nav-item ">
                  <a href="{{ route('role') }}" class="nav-link {{!empty($activeTab) ? ($activeTab == "Role" ? "active" : "") : ""}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Roles</p>
                  </a>
                </li>

                <li class="nav-item ">
                  <a href="{{ route('module') }}" class="nav-link {{!empty($activeTab) ? ($activeTab == "Modules" ? "active" : "") : ""}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Modules</p>
                  </a>
                </li>

                <li class="nav-item ">
                  <a href="{{ route('permissions') }}" class="nav-link {{!empty($activeTab) ? ($activeTab == "manage_permissions" ? "active" : "") : ""}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Permissions</p>
                  </a>
                </li>

                <li class="nav-item ">
                  <a href="{{ route('country') }}" class="nav-link {{!empty($activeTab) ? ($activeTab == "Country" ? "active" : "") : ""}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Countries</p>
                  </a>
                </li>

                <li class="nav-item ">
                  <a href="{{ route('languages') }}" class="nav-link {{!empty($activeTab) ? ($activeTab == "Languages" ? "active" : "") : ""}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Languages</p>
                  </a>
                </li>

                <li class="nav-item ">
                  <a href="{{ route('content') }}" class="nav-link {{!empty($activeTab) ? ($activeTab == "Content" ? "active" : "") : ""}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Content</p>
                  </a>
                </li>

                

                <li class="nav-item ">
                  <a href="{{ route('category') }}" class="nav-link {{!empty($activeTab) ? ($activeTab == "Category" ? "active" : "") : ""}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Category</p>
                  </a>
                </li>

            </ul>
        </li>

        <li class="nav-item has-treeview {{!empty($activeTab) ? ($activeTab == "Translation" || $activeTab == "CategoryTranslat" ? "menu-open " : "") : ""}}">
            <a href="#" class="nav-link {{!empty($activeTab) ? ($activeTab == "Translation" || $activeTab == "CategoryTranslat" ? "active " : "") : ""}}">
                <i class="nav-icon fas fa-user-tie"></i>
                <p> Translations <i class="right fas fa-angle-left"></i> </p>
            </a>
            <ul class="nav nav-treeview">

                <li class="nav-item ">
                  <a href="{{ route('translation') }}" class="nav-link {{!empty($activeTab) ? ($activeTab == "Translation" ? "active" : "") : ""}}">
                      <i class="fa fa-globe nav-icon"></i>
                      <p>Content Translations</p>
                  </a>
                </li>

                <li class="nav-item ">
                  <a href="{{ route('categoryTranslat') }}" class="nav-link {{!empty($activeTab) ? ($activeTab == "CategoryTranslat" ? "active" : "") : ""}}">
                      <i class="fa fa-globe nav-icon"></i>
                      <p>Category Translations</p>
                  </a>
                </li>
            </ul>
        </li>

        <li class="nav-item has-treeview {{!empty($activeTab) ? ($activeTab == "AppUsers" ? "menu-open " : "") : ""}}">
            <a href="#" class="nav-link {{!empty($activeTab) ? ($activeTab == "AppUsers" ? "active " : "") : ""}}">
                <i class="nav-icon fas fa-user-tie"></i>
                <p>App Users <i class="right fas fa-angle-left"></i> </p>
            </a>
            <ul class="nav nav-treeview">

                <li class="nav-item ">
                  <a href="{{ route('appusers') }}" class="nav-link {{!empty($activeTab) ? ($activeTab == "AppUsers" ? "active" : "") : ""}}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>User List</p>
                  </a>
                </li>
            </ul>
        </li>



      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
