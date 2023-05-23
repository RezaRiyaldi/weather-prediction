<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url() ?>" class="text-nowrap logo-img">
                <!-- <img src="../assets/images/logos/dark-logo.svg" width="180" alt="" /> -->
                <h5 class="d-flex align-items-center gap-2">
                    <i class="ti ti-cloud-pin text-info" style="font-size: 46px;"></i>
                    <span style="font-size: 18px;">Weather <br>Prediction</span>
                </h5>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url() ?>" aria-expanded="false">
                        <span>
                            <i class="ti ti-cloud-search"></i>
                        </span>
                        <span class="hide-menu">Info Cuaca</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url() ?>tools" aria-expanded="false">
                        <span>
                            <i class="ti ti-tools"></i>
                        </span>
                        <span class="hide-menu">Tools</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>