<div class="top-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="btn mobile-menu-btn me-3" id="mobileMenuBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h4 class="mb-0 fw-semibold" id="pageTitle">System Overview</h4>
                        <small class="text-muted" id="currentDate"></small>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <!-- Search -->
                    <div class="search-input me-3 d-none d-sm-block position-relative">
                        <input type="text" class="form-control" placeholder="Search..." id="globalSearch">
                        <i class="fas fa-search search-icon"></i>
                    </div>

                    <!-- Language Toggle -->
                    <button class="language-toggle me-3" id="languageToggle">العربية</button>

                    <!-- Notifications -->
                    <div class="position-relative me-3">
                        <button class="btn btn-light position-relative" id="notificationBtn">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge" id="notificationBadge">3</span>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div class="dropdown-menu dropdown-menu-end" id="notificationsDropdown" style="width: 300px; display: none;">
                            <div class="px-3 py-2 border-bottom">
                                <h6 class="mb-0">
                                    <span class="nav-text">Notifications</span>
                                    <span class="nav-text-ar d-none">الإشعارات</span>
                                </h6>
                            </div>
                            <div class="custom-scroll" style="max-height: 300px;">
                                <div class="px-3 py-2 border-bottom">
                                    <div class="d-flex">
                                        <i class="fas fa-exclamation-triangle text-warning me-2 mt-1"></i>
                                        <div class="flex-grow-1">
                                            <div class="small fw-medium">Critical case BZ-2024-157 requires immediate attention</div>
                                            <div class="text-muted small">5 min ago</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-3 py-2 border-bottom">
                                    <div class="d-flex">
                                        <i class="fas fa-info-circle text-info me-2 mt-1"></i>
                                        <div class="flex-grow-1">
                                            <div class="small fw-medium">Daily system backup completed successfully</div>
                                            <div class="text-muted small">1 hour ago</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-3 py-2">
                                    <div class="d-flex">
                                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                        <div class="flex-grow-1">
                                            <div class="small fw-medium">Weekly performance report generated</div>
                                            <div class="text-muted small">2 hours ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <button class="btn btn-light">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>
        </div>