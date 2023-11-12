<ul class="navbar-nav sidebar sidebar-light accordion font-weight-bold" id="accordionSidebar" > 
  <a class="sidebar-brand d-flex align-items-center bg-gradient-primary  justify-content-center" href="index.php" style="height: 100px;WIDTH">
    <div class="sidebar-brand-icon" >
      <img src="img/logo/seu_logo.png">
    </div>
    <div class="sidebar-brand-text mx-3"  style="">
      <h6 style="font-family: Georgia, 'Times New Roman', Times, serif;font-weight: bold;font-size: 18px;">STUDENT MANAGEMENT SYSTEM</h6>
    </div>
   
  </a>
  <br>

  <li class="nav-item active font-weight-bold">
    <a class="nav-link" href="index.php">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>DASHBOARD</span></a>
  </li>

  <hr class="sidebar-divider">
  <li class="nav-item ">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapassests"
      aria-expanded="true" aria-controls="collapseBootstrapassests">
      <i class="fas fa-chalkboard-teacher"></i>
      <span>MANAGE LECTURES</span>
    </a>
    <div id="collapseBootstrapassests" class="collapse" aria-labelledby="headingBootstrap"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Manage Lectures</h6>
        <a class="collapse-item" href="Lecturer.php">Add Lecturers</a>
        <a class="collapse-item" href="LecturerEnroll.php">Lecture Enrollment</a>
        <!-- <a class="collapse-item" href="assetsCategoryList.php">Assets Category List</a>
             <a class="collapse-item" href="createAssets.php">Create Assets</a> -->
      </div>
    </div>
  </li>
  <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapschemes"
          aria-expanded="true" aria-controls="collapseBootstrapschemes">
          <i class="fas fa-home"></i>
          <span>Manage Schemes</span>
        </a>
        <div id="collapseBootstrapschemes" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Schemes</h6>
             <a class="collapse-item" href="createSchemes.php">Create Scheme</a>
            <a class="collapse-item" href="schemeList.php">Scheme List</a>
          </div>
        </div>
      </li> -->

  <hr class="sidebar-divider">

  </li>
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap2" aria-expanded="true"
      aria-controls="collapseBootstrap2">
      <i class="fas fa-user-graduate"></i>
      <span>MANAGE STUDENTS</span>
    </a>
    <div id="collapseBootstrap2" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">MANAGE STUDENTS</h6>
        <a class="collapse-item" href="Students.php">Add Students</a>
        <a class="collapse-item" href="GetId.php">Print Student Id Card</a>
        <!-- <a class="collapse-item" href="#">Assets Type</a> -->
      </div>
    </div>
  </li>

  <hr class="sidebar-divider">

  </li>
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapcon"
      aria-expanded="true" aria-controls="collapseBootstrapcon">
      <i class="fa fa-calendar-alt"></i>
      <span>MANAGE ACADEMIC </span>
    </a>
    <div id="collapseBootstrapcon" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Year Semester</h6>
        <a class="collapse-item" href="Academic.php">Add Year and Semester</a>
        <!-- <a class="collapse-item" href="addMemberToContLevel.php ">Add Member to Level</a> -->
      </div>
    </div>
  </li>

  <hr class="sidebar-divider">

  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap" aria-expanded="true"
      aria-controls="collapseBootstrap">
      <i class="fas fa-chalkboard"></i>
      <span>MANAGE COURSE</span>
    </a>
    <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">MANAGE COURSE</h6>
        <a class="collapse-item" href="Course.php">Add COURSE</a>
        <!-- <a class="collapse-item" href="#">Member List</a> -->
      </div>
    </div>
  </li>

  <hr class="sidebar-divider">

  </li>
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap1" aria-expanded="true"
      aria-controls="collapseBootstrap1">
      <i class="fa fa-file" ></i>
      <span>BULK ADD</span>
    </a>
    <div id="collapseBootstrap1" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">BULK ADD</h6>
        <a class="collapse-item" href="BulkAdd.php">Add Files</a>
        <!-- <a class="collapse-item" href="#">Assets Type</a> -->
      </div>
    </div>
    
  </li>

  <!-- <li class="nav-item">
        <a class="nav-link" href="forms.html">
          <i class="fab fa-fw fa-wpforms"></i>
          <span>Forms</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
          aria-controls="collapseTable">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Tables</h6>
            <a class="collapse-item" href="simple-tables.html">Simple Tables</a>
            <a class="collapse-item" href="datatables.html">DataTables</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="ui-colors.html">
          <i class="fas fa-fw fa-palette"></i>
          <span>UI Colors</span>
        </a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Examples
      </div>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePage" aria-expanded="true"
          aria-controls="collapsePage">
          <i class="fas fa-fw fa-columns"></i>
          <span>Pages</span>
        </a>
        <div id="collapsePage" class="collapse" aria-labelledby="headingPage" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Example Pages</h6>
            <a class="collapse-item" href="login.html">Login</a>
            <a class="collapse-item" href="register.html">Register</a>
            <a class="collapse-item" href="404.html">404 Page</a>
            <a class="collapse-item" href="blank.html">Blank Page</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span>
        </a>
      </li> -->

     

 


  <hr class="sidebar-divider">
 
  

</ul>

