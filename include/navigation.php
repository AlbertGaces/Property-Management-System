<nav class="navbar navbar-expand-lg navbar-dark bg-orange fixed-top">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
            <span class="navbar-toggler-icon" data-bs-target="#sidebar"></span>
        </button>
        <img src="images/logo.png" alt="" style="width: 40px; height: 40px;">
        &nbsp;<a class="navbar-brand me-auto ms-lg-0 ms-3 text-uppercase fw-bold text-dark" href="dashboard.php">Mahogany Villas Home Owners Association</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavBar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="topNavBar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle ms-2 text-dark p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="images/residents/<?php echo $_SESSION['photo'];?>" class="rounded rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                        <?php echo $_SESSION['name']?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-center">
                        <li>
                            <a href="#" class="text-dark text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalSignOut"><span class='bi bi-box-arrow-right fs-5'></span>&nbsp; Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start sidebar-nav bg-dark" tabindex="-1" id="sidebar" style="width: 250px;">
    <div class="offcanvas-body p-0">
        <nav class="navbar-dark">
            <ul class="navbar-nav">
                <li>
                    <a href="dashboard.php" class="nav-link px-3 mt-3 <?php if ($active == 'dashboard') {echo "active";}?>">
                        <span class="me-2"><i class="bi bi-grid-fill"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="my-2">
                    <hr class="dropdown-divider bg-light" />
                </li>
                <li>
                    <div class="small fw-bold text-uppercase px-3 text-light">
                        Menu
                    </div>
                </li>
                <li>
                    <a class="nav-link px-3 sidebar-link <?php if ($active == 'resident-records' || $active == 'owners' || $active == 'tenants') {echo "active";}?>" data-bs-toggle="collapse" href="#residents-menu">
                        <span class="me-2"><i class="bi bi-person-lines-fill"></i></span>
                        <span>Residents</span>
                        <span class="ms-auto">
                            <span class="right-icon">
                                <i class="bi bi-chevron-down"></i>
                            </span>
                        </span>
                    </a>
                    <div class="collapse" id="residents-menu">
                        <ul class="navbar-nav ps-3">
                            <li>
                                <a href="resident-records.php" class="nav-link px-3 <?php if ($active == 'resident-records') {echo "active";}?>">
                                    <span class="me-2"><i class="bi bi-file-earmark-text"></i></span>
                                    <span>Resident Records</span>
                                </a>
                            </li>
                            <li>
                                <a href="owners.php" class="nav-link px-3 <?php if ($active == 'owners') {echo "active";}?>">
                                    <span class="me-2"><i class="bi bi-key"></i></span>
                                    <span>Owners</span>
                                </a>
                            </li>
                            <li>
                                <a href="tenants.php" class="nav-link px-3 <?php if ($active == 'tenants') {echo "active";}?>">
                                    <span class="me-2"><i class="bi bi-person"></i></span>
                                    <span>Tenants</span>
                                </a>
                            </li>
                            <li>
                                <a href="occupants.php" class="nav-link px-3 <?php if ($active == 'occupants') {echo "active";}?>">
                                    <span class="me-2"><i class="bi bi-people"></i></span>
                                    <span>Occupants</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a class="nav-link px-3 sidebar-link <?php if ($active == 'property-records' || $active == 'residential' || $active == 'commercial') {echo "active";}?>" data-bs-toggle="collapse" href="#properties-menu">
                        <span class="me-2"><i class="bi bi-house-fill"></i></span>
                        <span>Properties</span>
                        <span class="ms-auto">
                            <span class="right-icon">
                                <i class="bi bi-chevron-down"></i>
                            </span>
                        </span>
                    </a>
                    <div class="collapse" id="properties-menu">
                        <ul class="navbar-nav ps-3">
                            <li>
                                <a href="property-records.php" class="nav-link px-3 <?php if ($active == 'property-records') {echo "active";}?>">
                                    <span class="me-2"><i class="bi bi-file-earmark-text"></i></span>
                                    <span>Property Records</span>
                                </a>
                            </li>
                            <li>
                                <a href="residential.php" class="nav-link px-3 <?php if ($active == 'residential') {echo "active";}?>">
                                    <span class="me-2"><i class="bi bi-house-door"></i></span>
                                    <span>Residential</span>
                                </a>
                            </li>
                            <li>
                                <a href="commercial.php" class="nav-link px-3 <?php if ($active == 'commercial') {echo "active";}?>">
                                    <span class="me-2"><i class="bi bi-shop"></i></span>
                                    <span>Commercial</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="my-2">
                    <hr class="dropdown-divider bg-light" />
                </li>
                <li>
                    <div class="small fw-bold text-uppercase px-3 text-light">
                        Extras
                    </div>
                </li>
                <li>
                    <a href="messaging.php" class="nav-link px-3 <?php if ($active == 'messaging') {echo "active";}?>">
                        <span class="me-2"><i class="bi bi-envelope-fill"></i></span>
                        <span>Messaging</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link px-3 sidebar-link <?php if ($active == 'archive-properties' || $active == 'archive-residents') {echo "active";}?>" data-bs-toggle="collapse" href="#archives-menu">
                        <span class="me-2"><i class="bi bi-archive-fill"></i></span>
                        <span>Archives</span>
                        <span class="ms-auto">
                            <span class="right-icon">
                                <i class="bi bi-chevron-down"></i>
                            </span>
                        </span>
                    </a>
                    <div class="collapse" id="archives-menu">
                        <ul class="navbar-nav ps-3">
                            <li>
                                <a href="archive-residents.php" class="nav-link px-3 <?php if ($active == 'archive-residents') {echo "active";}?>">
                                    <span class="me-2"><i class="bi bi-person"></i></span>
                                    <span>Residents</span>
                                </a>
                            </li>
                            <li>
                                <a href="archive-properties.php" class="nav-link px-3 <?php if ($active == 'archive-properties') {echo "active";}?>">
                                    <span class="me-2"><i class="bi bi-house-door"></i></span>
                                    <span>Properties</span>
                                </a>
                            </li>
                            <li>
                                <a href="archive-administrators.php" class="nav-link px-3 <?php if ($active == 'archive-administrators') {echo "active";}?>">
                                    <span class="me-2"><i class="bi bi-person-x"></i></span>
                                    <span>Administrators</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="map.php" class="nav-link px-3 <?php if ($active == 'map') {echo "active";}?>">
                        <span class="me-2"><i class="bi bi-compass-fill"></i></span>
                        <span>Map</span>
                    </a>
                </li>
                <li class="my-2">
                    <hr class="dropdown-divider bg-light" />
                </li>
                <li>
                    <a href="accounts.php" class="nav-link px-3 <?php if ($active == 'accounts') {echo "active";}?>">
                        <span class="me-2"><i class="bi bi-person-circle"></i></span>
                        <span>Accounts</span>
                    </a>
                </li>
                <li>
                    <a href="about.php" class="nav-link px-3 <?php if ($active == 'about') {echo "active";}?>">
                        <span class="me-2"><i class="bi bi-person-plus-fill"></i></span>
                        <span>About</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<div class="modal fade" id="modalSignOut" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Log Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Do you really want to log out now <?php echo $_SESSION['name'] ?>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" name="logOut">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>