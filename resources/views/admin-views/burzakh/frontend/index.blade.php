@extends('admin-views.burzakh.layouts.master')

@section('title', 'Burzakh Dashboard')

@section('content')
    <!-- Content Area -->
    <div class="p-4">
        <!-- Overview Tab -->
        <div id="overviewTab" class="tab-content-custom">
            <!-- Executive Command Header -->
            <div class="executive-header mb-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-2 bg-white bg-opacity-10 rounded me-3">
                                <i class="fas fa-shield-alt fa-lg"></i>
                            </div>
                            <div>
                                <h2 class="fw-bold mb-1">
                                    <span class="nav-text">Master Control Dashboard</span>
                                    <span class="nav-text-ar d-none">لوحة التحكم الرئيسية</span>
                                </h2>
                                <p class="text-white-50 small mb-0">
                                    <span class="nav-text">Unified command center for Dubai funeral and burial operations</span>
                                    <span class="nav-text-ar d-none">مركز القيادة الموحد لعمليات الجنائز والدفن في دبي</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="live-indicator mb-2">
                            <div class="live-dot"></div>
                            <span class="nav-text">All Systems Operational</span>
                            <span class="nav-text-ar d-none">جميع الأنظمة تعمل</span>
                        </div>
                        <p class="text-white-50 small mb-0">
                            <span class="nav-text">Last updated:</span>
                            <span class="nav-text-ar d-none">آخر تحديث:</span>
                            <span id="lastUpdated"></span>
                        </p>
                        <div class="d-flex justify-content-lg-end mt-2">
                            <div class="me-3 small">
                                <i class="fas fa-users me-1"></i>
                                <span class="nav-text">{{$total_users}} Users</span>
                                <span class="nav-text-ar d-none">{{$total_users}} مستخدم</span>
                            </div>
                            <div class="small">
                                <i class="fas fa-database me-1"></i>
                                <span class="nav-text">99.9% Uptime</span>
                                <span class="nav-text-ar d-none">99.9% وقت التشغيل</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-lg">
                    <div class="metric-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="metric-label">
                                    <span class="nav-text">Total Cases</span>
                                    <span class="nav-text-ar d-none">إجمالي الحالات</span>
                                </div>
                                <div class="metric-value" id="totalCases">{{$total_cases}}</div>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                        <!-- <div class="d-flex align-items-center text-success small">
                            <i class="fas fa-arrow-up me-1"></i>
                            <span class="fw-semibold">
                                <span class="nav-text">+8.2% this month</span>
                                <span class="nav-text-ar d-none">+8.2% هذا الشهر</span>
                            </span>
                        </div> -->
                        <div class="d-flex align-items-center {{ $monthly_change_percent >= 0 ? 'text-success' : 'text-danger' }} small">
                            <i class="fas {{ $monthly_change_percent >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                            <span class="fw-semibold">
                                <span class="nav-text">
                                    {{ $monthly_change_percent >= 0 ? '+' : '' }}{{ $monthly_change_percent }}% this month
                                </span>
                                <span class="nav-text-ar d-none">
                                    {{ $monthly_change_percent >= 0 ? '+' : '' }}{{ $monthly_change_percent }}% هذا الشهر
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg">
                    <div class="metric-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="metric-label">
                                    <span class="nav-text">Active Cases</span>
                                    <span class="nav-text-ar d-none">الحالات النشطة</span>
                                </div>
                                <div class="metric-value" id="activeCases">{{$active_cases}}</div>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center text-muted small">
                            <div class="status-indicator status-online me-1"></div>
                            <span class="fw-semibold">
                                <span class="nav-text">Live monitoring</span>
                                <span class="nav-text-ar d-none">مراقبة مباشرة</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg">
                    <div class="metric-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="metric-label">
                                    <span class="nav-text">Completed Today</span>
                                    <span class="nav-text-ar d-none">مكتمل اليوم</span>
                                </div>
                                <div class="metric-value" id="completedToday">{{$completed_today}}</div>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center text-success small">
                            <i class="fas fa-bullseye me-1"></i>
                            <span class="fw-semibold">
                                <span class="nav-text">Target: 25 ({{ $progress_percent }}%)</span>
                                <span class="nav-text-ar d-none">الهدف: 25 ({{ $progress_percent }}%)</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg">
                    <div class="metric-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="metric-label">
                                    <span class="nav-text">Average Processing Time</span>
                                    <span class="nav-text-ar d-none">متوسط وقت المعالجة</span>
                                </div>
                                <div class="metric-value">{{ $avg_processing_hours }}h</div>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <!-- <div class="d-flex align-items-center text-success small">
                            <i class="fas fa-arrow-up me-1"></i>
                            <span class="fw-semibold">
                                <span class="nav-text">15% faster</span>
                                <span class="nav-text-ar d-none">15% أسرع</span>
                            </span>
                        </div> -->
                        <div class="d-flex align-items-center {{ $speed_color }} small">
                            <i class="fas fa-arrow-{{ $speed_arrow }} me-1"></i>
                            <span class="fw-semibold">
                                <span class="nav-text">{{ abs($speed_percent) }}% {{ $speed_trend }}</span>
                                <span class="nav-text-ar d-none">
                                    {{ abs($speed_percent) }}%
                                    {{ $speed_trend == 'faster' ? 'أسرع' : 'أبطأ' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg">
                    <div class="metric-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="metric-label">
                                    <span class="nav-text">Pending</span>
                                    <span class="nav-text-ar d-none">في الانتظار</span>
                                </div>
                                <div class="metric-value" id="pendingApprovals">{{$pending_cases}}</div>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center text-warning small">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <span class="fw-semibold">
                                <span class="nav-text">Needs attention</span>
                                <span class="nav-text-ar d-none">يحتاج انتباه</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="row g-4">
                <!-- Entity Operations Matrix -->
                <div class="col-xl-9">
                    <div class="bg-white border rounded-3 overflow-hidden">
                        <div class="p-3 border-bottom bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 fw-bold">
                                        <span class="nav-text">Entity Operations Matrix</span>
                                        <span class="nav-text-ar d-none">مصفوفة عمليات الكيانات</span>
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        <span class="nav-text">Real-time operational status and performance metrics for all entities</span>
                                        <span class="nav-text-ar d-none">حالة التشغيل في الوقت الفعلي ومقاييس الأداء لجميع الكيانات</span>
                                    </p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="live-indicator me-3">
                                        <div class="live-dot"></div>
                                        <span class="nav-text">LIVE</span>
                                        <span class="nav-text-ar d-none">مباشر</span>
                                    </div>
                                    <button class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-sync-alt me-1"></i>
                                        <span class="nav-text">Refresh</span>
                                        <span class="nav-text-ar d-none">تحديث</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="row g-3" id="entitiesGrid">
                                <div class="row">
                                    @foreach ($cases as $case)
                                        @php
                                            $user = $case->user;
                                            $statusClass = $case->ratio == 1 ? 'bg-success' : ($case->ratio >= 0.6 ? 'bg-warning' : 'bg-danger');
                                            $statusLabel = $case->ratio == 1 ? 'Completed' : ($case->ratio >= 0.6 ? 'In Progress' : 'Pending');
                                        @endphp

                                        <div class="col-lg-6">
                                            <div class="entity-card  mt-2">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="entity-avatar bg-dark">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                                            <div class="text-muted small">{{ $user->phone_number ?? 'N/A' }}</div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="h4 fw-bold mb-0">{{ round($case->ratio * 100) }}%</div>
                                                        <div class="text-muted small">Efficiency</div>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="status-badge {{ $statusClass }}">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            {{ $statusLabel }}
                                                        </span>
                                                        <span class="badge bg-secondary">Case: {{ $case->name_of_deceased }}</span>
                                                    </div>
                                                    <div class="small text-muted mb-2">
                                                        <strong>Deceased Info:</strong>
                                                        Date of Death – {{ $case->date_of_death ?? 'N/A' }}, 
                                                        Location – {{ $case->location ?? 'N/A' }}
                                                    </div>
                                                    <div class="small text-danger">
                                                        {{ $case->created_at->format('d M Y h:i A') }}
                                                    </div>
                                                </div>

                                                <div class="performance-grid mb-3">
                                                    <div class="performance-item">
                                                        <div class="performance-value">{{$total_cases}}</div> 
                                                        <div class="performance-label">Cases count</div>
                                                    </div>
                                                    <div class="performance-item">
                                                        <div class="performance-value">
                                                            @if ($case->updated_at)
                                                                {{ $case->created_at->diffInMinutes($case->updated_at) }} min
                                                            @else
                                                                N/A
                                                            @endif
                                                        </div>
                                                        <div class="performance-label">Response Time</div>
                                                    </div>
                                                    <div class="performance-item">
                                                        <div class="performance-value">
                                                            {{ $user->last_login ?? 'N/A' }}
                                                        </div>
                                                        <div class="performance-label">Last Login</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        
                                                        <a href="tel:{{ $user->phone_number ?? '#' }}" class="btn btn-outline-secondary btn-sm">
                                                            <i class="fas fa-phone me-1"></i>
                                                            Contact
                                                        </a>
                                                    </div>
                                                    <small class="text-muted align-self-end">Case Id: BUR-2025-{{ $case->id }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-xl-3">
                    <!-- Critical Alerts -->
                    <!-- <div class="bg-white border rounded-3 overflow-hidden mb-4">
                        <div class="p-3 border-bottom bg-danger bg-opacity-10">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                <h6 class="mb-0 fw-bold">
                                    <span class="nav-text">Critical Alerts</span>
                                    <span class="nav-text-ar d-none">تنبيهات حرجة</span>
                                </h6>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="alert-danger-custom alert-custom">
                                <div class="d-flex">
                                    <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold small">
                                            <span class="nav-text">Delay Alert</span>
                                            <span class="nav-text-ar d-none">تنبيه تأخير</span>
                                        </div>
                                        <div class="small text-muted">
                                            <span class="nav-text">RTA Transport delayed 3+ hours</span>
                                            <span class="nav-text-ar d-none">نقل هيئة الطرق والمواصلات متأخر 3+ ساعات</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alert-warning-custom alert-custom">
                                <div class="d-flex">
                                    <i class="fas fa-clock me-2 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold small">
                                            <span class="nav-text">Bottleneck</span>
                                            <span class="nav-text-ar d-none">اختناق</span>
                                        </div>
                                        <div class="small text-muted">
                                            <span class="nav-text">Municipality approval queue backing up</span>
                                            <span class="nav-text-ar d-none">قائمة انتظار موافقة البلدية تتراكم</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="bg-white border rounded-3 overflow-hidden mb-4">
                        <div class="p-3 border-bottom bg-danger bg-opacity-10">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                <h6 class="mb-0 fw-bold">
                                    <span class="nav-text">Critical Alerts</span>
                                    <span class="nav-text-ar d-none">تنبيهات حرجة</span>
                                </h6>
                            </div>
                        </div>
                        <div class="p-3">
                            @if(count($critical_alerts) > 0)
                                @foreach($critical_alerts as $alert)
                                    <div class="alert-danger-custom alert-custom mb-2">
                                        <div class="d-flex">
                                            <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                                            <div class="small">
                                                {{ $alert }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-light border d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <div class="small fw-semibold text-muted">
                                        <span class="nav-text">No critical alerts for now</span>
                                        <span class="nav-text-ar d-none">لا توجد تنبيهات حرجة حاليًا</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>


                    <!-- Live Activity -->
                    <!-- <div class="bg-white border rounded-3 overflow-hidden mb-4">
                        <div class="p-3 border-bottom bg-info bg-opacity-10">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chart-line text-info me-2"></i>
                                    <h6 class="mb-0 fw-bold">
                                        <span class="nav-text">Live Activity</span>
                                        <span class="nav-text-ar d-none">النشاط المباشر</span>
                                    </h6>
                                </div>
                                <div class="live-indicator">
                                    <div class="live-dot"></div>
                                    <span class="nav-text">LIVE</span>
                                    <span class="nav-text-ar d-none">مباشر</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="custom-scroll" style="max-height: 250px;" id="activityFeed">
                               
                            </div>
                        </div>
                    </div> -->

                    <!-- Quick Actions -->
                    <div class="bg-white border rounded-3 overflow-hidden">
                        <div class="p-3 border-bottom">
                            <h6 class="mb-0 fw-bold">
                                <span class="nav-text">Quick Actions</span>
                                <span class="nav-text-ar d-none">إجراءات سريعة</span>
                            </h6>
                        </div>
                        <div class="p-3">
                            <!-- <button class="btn btn-primary-custom w-100 mb-2" onclick="showNewCaseModal()">
                                <i class="fas fa-plus me-2"></i>
                                <span class="nav-text">New Case</span>
                                <span class="nav-text-ar d-none">حالة جديدة</span>
                            </button> -->
                            <!-- <button class="btn btn-outline-secondary w-100 mb-2" onclick="generateReport()">
                                <i class="fas fa-chart-bar me-2"></i>
                                <span class="nav-text">Generate Report</span>
                                <span class="nav-text-ar d-none">إنشاء تقرير</span>
                            </button> -->
                            <button class="btn btn-outline-secondary w-100" onclick="sendAlert()">
                                <i class="fas fa-bell me-2"></i>
                                <span class="nav-text">Send Alert</span>
                                <span class="nav-text-ar d-none">إرسال تنبيه</span>
                            </button>
                            

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operations Tab -->
        <div id="operationsTab" class="tab-content-custom d-none">
            <div class="bg-white border rounded-3">
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">
                                <span class="nav-text">Operations & Case Management Center</span>
                                <span class="nav-text-ar d-none">مركز العمليات وإدارة الحالات</span>
                            </h5>
                            <p class="text-muted small mb-0">
                                <span class="nav-text">Unified view of personnel operations and case management</span>
                                <span class="nav-text-ar d-none">عرض موحد لعمليات الموظفين وإدارة الحالات</span>
                            </p>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="live-indicator me-3">
                                <div class="live-dot"></div>
                                <span class="nav-text">LIVE</span>
                                <span class="nav-text-ar d-none">مباشر</span>
                            </div>
                            <button class="btn btn-primary-custom btn-sm">
                                <i class="fas fa-download me-1"></i>
                                <span class="nav-text">Export</span>
                                <span class="nav-text-ar d-none">تصدير</span>
                            </button>
                        </div>
                    </div>

                    <!-- Sub Navigation -->
                    <div class="mt-3">
                        <ul class="nav nav-pills" id="operationsTabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#" data-view="personnel">
                                    <i class="fas fa-users me-2"></i>
                                    <span class="nav-text">Personnel View</span>
                                    <span class="nav-text-ar d-none">عرض الموظفين</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-view="cases">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    <span class="nav-text">Cases View</span>
                                    <span class="nav-text-ar d-none">عرض الحالات</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-view="workflow">
                                    <i class="fas fa-project-diagram me-2"></i>
                                    <span class="nav-text">Workflow View</span>
                                    <span class="nav-text-ar d-none">عرض سير العمل</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filter-controls">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">
                                <span class="nav-text">Filter:</span>
                                <span class="nav-text-ar d-none">تصفية:</span>
                            </label>
                            <select class="form-select form-select-sm" id="caseFilter">
                                <option value="all">
                                    <span class="nav-text">All Cases</span>
                                    <span class="nav-text-ar d-none">جميع الحالات</span>
                                </option>
                                <option value="in_progress">
                                    <span class="nav-text">In Progress</span>
                                    <span class="nav-text-ar d-none">قيد التقدم</span>
                                </option>
                                <option value="pending_approval">
                                    <span class="nav-text">Pending Approval</span>
                                    <span class="nav-text-ar d-none">في انتظار الموافقة</span>
                                </option>
                                <option value="delayed">
                                    <span class="nav-text">Delayed</span>
                                    <span class="nav-text-ar d-none">متأخر</span>
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">
                                <span class="nav-text">Department:</span>
                                <span class="nav-text-ar d-none">القسم:</span>
                            </label>
                            <select class="form-select form-select-sm" id="departmentFilter">
                                <option value="all">
                                    <span class="nav-text">All Departments</span>
                                    <span class="nav-text-ar d-none">جميع الأقسام</span>
                                </option>
                                <option value="Dubai Police">Dubai Police</option>
                                <option value="Dubai Municipality">Municipality</option>
                                <option value="RTA">Transport Authority</option>
                                <option value="CDA">Community Development</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="delaysOnly">
                                <label class="form-check-label small fw-medium" for="delaysOnly">
                                    <span class="nav-text">Show delays only</span>
                                    <span class="nav-text-ar d-none">إظهار التأخيرات فقط</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary-custom mt-4 w-100" onclick="showNewCaseModal()">
                                <i class="fas fa-plus me-2"></i>
                                <span class="nav-text">New Case</span>
                                <span class="nav-text-ar d-none">حالة جديدة</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="p-3">
                    <!-- Personnel View -->
                    <div id="personnelView" class="operations-view">
                        <div class="row g-3" id="personnelGrid">
                            <!-- Personnel cards will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Cases View -->
                    <div id="casesView" class="operations-view d-none">
                        <!-- Cases Summary -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <div class="bg-info bg-opacity-10 border border-info border-opacity-25 rounded p-3">
                                    <div class="h4 fw-bold text-info mb-1" id="inProgressCount">0</div>
                                    <div class="small text-info">
                                        <span class="nav-text">In Progress</span>
                                        <span class="nav-text-ar d-none">قيد التقدم</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded p-3">
                                    <div class="h4 fw-bold text-warning mb-1" id="pendingCount">0</div>
                                    <div class="small text-warning">
                                        <span class="nav-text">Pending</span>
                                        <span class="nav-text-ar d-none">في الانتظار</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded p-3">
                                    <div class="h4 fw-bold text-danger mb-1" id="delayedCount">0</div>
                                    <div class="small text-danger">
                                        <span class="nav-text">Delayed</span>
                                        <span class="nav-text-ar d-none">متأخر</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded p-3">
                                    <div class="h4 fw-bold text-success mb-1" id="completedCount">0</div>
                                    <div class="small text-success">
                                        <span class="nav-text">Completed</span>
                                        <span class="nav-text-ar d-none">مكتمل</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cases Table -->
                        <div class="table-custom">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>
                                            <span class="nav-text">Case Details</span>
                                            <span class="nav-text-ar d-none">تفاصيل الحالة</span>
                                        </th>
                                        <th>
                                            <span class="nav-text">Status & Progress</span>
                                            <span class="nav-text-ar d-none">الحالة والتقدم</span>
                                        </th>
                                        <th>
                                            <span class="nav-text">Assignment</span>
                                            <span class="nav-text-ar d-none">التعيين</span>
                                        </th>
                                        <th>
                                            <span class="nav-text">Timeline</span>
                                            <span class="nav-text-ar d-none">الجدول الزمني</span>
                                        </th>
                                        <th class="text-end">
                                            <span class="nav-text">Actions</span>
                                            <span class="nav-text-ar d-none">الإجراءات</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="casesTableBody">
                                    <!-- Cases will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Workflow View -->
                    <div id="workflowView" class="operations-view d-none">
                        <div class="row g-4">
                            <!-- Active Workflows -->
                            <div class="col-lg-6">
                                <div class="bg-white border rounded-3">
                                    <div class="p-3 border-bottom bg-light">
                                        <h6 class="mb-0 fw-bold">
                                            <span class="nav-text">Active Workflows</span>
                                            <span class="nav-text-ar d-none">سير العمل النشط</span>
                                        </h6>
                                    </div>
                                    <div class="p-3" id="activeWorkflows">
                                        <!-- Workflows will be populated by JavaScript -->
                                    </div>
                                </div>
                            </div>

                            <!-- Issues & Delays -->
                            <div class="col-lg-6">
                                <div class="bg-white border rounded-3">
                                    <div class="p-3 border-bottom bg-danger bg-opacity-10">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                            <h6 class="mb-0 fw-bold">
                                                <span class="nav-text">Issues & Delays</span>
                                                <span class="nav-text-ar d-none">المشاكل والتأخيرات</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="p-3" id="issuesDelays">
                                        <!-- Issues will be populated by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Departments Tab -->
        <div id="departmentsTab" class="tab-content-custom d-none">
            <div class="row g-4" id="departmentsGrid">
                <!-- Departments will be populated by JavaScript -->
            </div>
        </div>

        <!-- Analytics Tab -->
        <div id="analyticsTab" class="tab-content-custom d-none">
            <div class="bg-white border rounded-3">
                <div class="p-3 border-bottom">
                    <h5 class="mb-1 fw-bold">
                        <span class="nav-text">Performance Analytics</span>
                        <span class="nav-text-ar d-none">تحليل الأداء</span>
                    </h5>
                    <p class="text-muted small mb-0">
                        <span class="nav-text">Comprehensive analytics and insights</span>
                        <span class="nav-text-ar d-none">تحليلات ورؤى شاملة</span>
                    </p>
                </div>
                <div class="p-3">
                    <div class="chart-container">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Administration Tab -->
        <div id="administrationTab" class="tab-content-custom d-none">
            <div class="bg-white border rounded-3">
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">
                                <span class="nav-text">System Administration Console</span>
                                <span class="nav-text-ar d-none">وحدة تحكم إدارة النظام</span>
                            </h5>
                            <p class="text-muted small mb-0">
                                <span class="nav-text">Comprehensive system management, user administration, and security controls</span>
                                <span class="nav-text-ar d-none">إدارة شاملة للنظام وإدارة المستخدمين وعناصر تحكم الأمان</span>
                            </p>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="live-indicator me-3">
                                <div class="live-dot"></div>
                                <span class="nav-text">SECURE</span>
                                <span class="nav-text-ar d-none">آمن</span>
                            </div>
                            <button class="btn btn-primary-custom btn-sm">
                                <i class="fas fa-shield-alt me-1"></i>
                                <span class="nav-text">Security Audit</span>
                                <span class="nav-text-ar d-none">تدقيق الأمان</span>
                            </button>
                        </div>
                    </div>

                    <!-- Sub Navigation -->
                    <div class="mt-3">
                        <ul class="nav nav-pills" id="adminTabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#" data-view="overview">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    <span class="nav-text">Overview</span>
                                    <span class="nav-text-ar d-none">نظرة عامة</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-view="users">
                                    <i class="fas fa-users me-2"></i>
                                    <span class="nav-text">Manage Users</span>
                                    <span class="nav-text-ar d-none">إدارة المستخدمين</span>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="#" data-view="system">
                                    <i class="fas fa-server me-2"></i>
                                    <span class="nav-text">System Control</span>
                                    <span class="nav-text-ar d-none">تحكم النظام</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-view="logs">
                                    <i class="fas fa-file-alt me-2"></i>
                                    <span class="nav-text">System Logs</span>
                                    <span class="nav-text-ar d-none">سجلات النظام</span>
                                </a>
                            </li> -->
                        </ul>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="p-3">
                    <!-- Overview -->
                    <div id="adminOverview" class="admin-view">
                        <!-- System Health Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="h4 fw-bold text-success mb-1" id="">{{$total_users}}</div>
                                            <div class="small text-success">
                                                <span class="nav-text">Active Users</span>
                                                <span class="nav-text-ar d-none">المستخدمون النشطون</span>
                                            </div>
                                        </div>
                                        <div class="p-2 bg-success bg-opacity-10 rounded">
                                            <i class="fas fa-user-check text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="bg-info bg-opacity-10 border border-info border-opacity-25 rounded p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="h4 fw-bold text-info mb-1" id="">{{$total_users}}</div>
                                            <div class="small text-info">
                                                <span class="nav-text">Total Users</span>
                                                <span class="nav-text-ar d-none">إجمالي المستخدمين</span>
                                            </div>
                                        </div>
                                        <div class="p-2 bg-info bg-opacity-10 rounded">
                                            <i class="fas fa-users text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="h4 fw-bold text-warning mb-1" id="suspendedUsersCount">0</div>
                                            <div class="small text-warning">
                                                <span class="nav-text">Suspended</span>
                                                <span class="nav-text-ar d-none">موقوف</span>
                                            </div>
                                        </div>
                                        <div class="p-2 bg-warning bg-opacity-10 rounded">
                                            <i class="fas fa-user-times text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="bg-secondary bg-opacity-10 border border-secondary border-opacity-25 rounded p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="h4 fw-bold text-secondary mb-1">99.9%</div>
                                            <div class="small text-secondary">
                                                <span class="nav-text">System Uptime</span>
                                                <span class="nav-text-ar d-none">وقت تشغيل النظام</span>
                                            </div>
                                        </div>
                                        <div class="p-2 bg-secondary bg-opacity-10 rounded">
                                            <i class="fas fa-server text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity & System Alerts -->
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="bg-white border rounded-3">
                                    <div class="p-3 border-bottom bg-light">
                                        <h6 class="mb-0 fw-bold">
                                            <span class="nav-text">Recent User Activity</span>
                                            <span class="nav-text-ar d-none">نشاط المستخدمين الأخير</span>
                                        </h6>
                                    </div>
                                    <div class="p-3">
                                        <div class="custom-scroll" style="max-height: 250px;">
                                            @forelse ($latestLogins as $login)
                                                <div class="activity-item mb-3">
                                                    <div class="activity-icon">
                                                        <i class="fas fa-user text-info"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="small fw-medium">{{ $login['type'] }}</div>
                                                        <div class="small text-muted">Last login from {{ $login['name'] }}</div>
                                                        <div class="small text-muted">{{ $login['login_time'] }}</div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-muted small">No login records found.</div>
                                            @endforelse
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="bg-white border rounded-3">
                                    <div class="p-3 border-bottom bg-light">
                                        <h6 class="mb-0 fw-bold">
                                            <span class="nav-text">System Alerts</span>
                                            <span class="nav-text-ar d-none">تنبيهات النظام</span>
                                        </h6>
                                    </div>
                                    <div class="p-3">
                                        <!-- <div class="alert-warning-custom alert-custom">
                                            <div class="d-flex">
                                                <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                                                <div>
                                                    <div class="fw-semibold small">
                                                        <span class="nav-text">Security Review</span>
                                                        <span class="nav-text-ar d-none">مراجعة أمنية</span>
                                                    </div>
                                                    <div class="small text-muted">
                                                        <span class="nav-text">1 user account suspended pending review</span>
                                                        <span class="nav-text-ar d-none">تم إيقاف حساب مستخدم واحد في انتظار المراجعة</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert-info-custom alert-custom">
                                            <div class="d-flex">
                                                <i class="fas fa-info-circle me-2 mt-1"></i>
                                                <div>
                                                    <div class="fw-semibold small">
                                                        <span class="nav-text">Backup Complete</span>
                                                        <span class="nav-text-ar d-none">اكتمال النسخ الاحتياطي</span>
                                                    </div>
                                                    <div class="small text-muted">
                                                        <span class="nav-text">Daily backup completed successfully</span>
                                                        <span class="nav-text-ar d-none">تم إكمال النسخ الاحتياطي اليومي بنجاح</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="alert-success-custom alert-custom">
                                            <div class="d-flex">
                                                <i class="fas fa-check-circle me-2 mt-1"></i>
                                                <div>
                                                    <div class="fw-semibold small">
                                                        <span class="nav-text">System Health</span>
                                                        <span class="nav-text-ar d-none">صحة النظام</span>
                                                    </div>
                                                    <div class="small text-muted">
                                                        <span class="nav-text">All systems operational</span>
                                                        <span class="nav-text-ar d-none">جميع الأنظمة تعمل</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Users Management -->
                    <div id="adminUsers" class="admin-view d-none">
                        <!-- User Management Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1">
                                    <span class="nav-text">User Management</span>
                                    <span class="nav-text-ar d-none">إدارة المستخدمين</span>
                                </h5>
                                <p class="text-muted small mb-0">
                                    <span class="nav-text">Add, edit, and manage system users across all authorities</span>
                                    <span class="nav-text-ar d-none">إضافة وتحرير وإدارة مستخدمي النظام عبر جميع السلطات</span>
                                </p>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="filter-controls">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-3">
                                    <label class="form-label small fw-medium">
                                        <span class="nav-text">Filter by Authority:</span>
                                        <span class="nav-text-ar d-none">تصفية بالسلطة:</span>
                                    </label>
                                    <select class="form-select form-select-sm" id="userAuthorityFilter">
                                        <option value="all">
                                            <span class="nav-text">All Authorities</span>
                                            <span class="nav-text-ar d-none">جميع السلطات</span>
                                        </option>
                                        <option value="dubai_police">Dubai Police</option>
                                        <option value="dubai_municipality">Dubai Municipality</option>
                                        <option value="rta">Road Transport Authority</option>
                                        <option value="cda">Community Development Authority</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium">
                                        <span class="nav-text">Search:</span>
                                        <span class="nav-text-ar d-none">بحث:</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" id="userSearchInput" placeholder="Search users...">
                                </div>
                                <div class="col-md-3">
                                    <div class="mt-4 small text-muted">
                                        <span class="nav-text">Total:</span>
                                        <span class="nav-text-ar d-none">الإجمالي:</span>
                                        <span id="userCount">{{$total_users}}</span>
                                        <span class="nav-text">users</span>
                                        <span class="nav-text-ar d-none">مستخدم</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Users Table -->
                        <div class="table-custom">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>
                                            <span class="nav-text">User Details</span>
                                            <span class="nav-text-ar d-none">تفاصيل المستخدم</span>
                                        </th>
                                        <th>
                                            <span class="nav-text">Contact & Authority</span>
                                            <span class="nav-text-ar d-none">جهة الاتصال والسلطة</span>
                                        </th>
                                        <th>
                                            <span class="nav-text">Role & Status</span>
                                            <span class="nav-text-ar d-none">الدور والحالة</span>
                                        </th>
                                        <th>
                                            <span class="nav-text">Activity</span>
                                            <span class="nav-text-ar d-none">النشاط</span>
                                        </th>
                                        <th class="text-end">
                                            <span class="nav-text">Actions</span>
                                            <span class="nav-text-ar d-none">الإجراءات</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allUsers as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $user->first_name }} {{ $user->last_name }}</div>
                                                        <div class="small text-muted">{{ $user->id ?? 'N/A' }}</div>
                                                        <div class="small text-muted">{{ $user->email ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="small">{{ $user->phone_number ?? 'N/A' }}</div>
                                                    <div class="small text-muted">{{ $user->email ?? 'N/A' }}</div>
                                                    <div class="small text-muted mt-1">{{ ucfirst($user->admin_type ?? 'N/A') }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-medium">{{ ucfirst($user->admin_type ?? 'N/A') }}</div>
                                                    <span class="status-badge text-success mt-1">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        Active
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="small">Cases: 2</div>
                                                    <div class="small text-muted">{{ $user->updated_at ? $user->updated_at->diffForHumans() : 'N/A' }}</div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-outline-primary btn-sm me-1">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-warning btn-sm me-1" title="Reset Password">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success btn-sm me-1" title="{{ $user->status === 'active' ? 'Suspend User' : 'Activate User' }}">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>

                    <!-- System Control -->
                    <div id="adminSystem" class="admin-view d-none">
                        <div class="row g-4">
                            <!-- System Operations -->
                            <div class="col-lg-6">
                                <div class="bg-white border rounded-3">
                                    <div class="p-3 border-bottom bg-light">
                                        <h6 class="mb-0 fw-bold">
                                            <span class="nav-text">System Operations</span>
                                            <span class="nav-text-ar d-none">عمليات النظام</span>
                                        </h6>
                                    </div>
                                    <div class="p-3">
                                        <div class="d-grid gap-2">
                                            <button class="quick-action-btn" onclick="initiateBackup()">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-database text-info me-3"></i>
                                                    <div>
                                                        <div class="fw-semibold small">
                                                            <span class="nav-text">Manual Backup</span>
                                                            <span class="nav-text-ar d-none">نسخة احتياطية يدوية</span>
                                                        </div>
                                                        <div class="text-muted small">
                                                            <span class="nav-text">Create system backup</span>
                                                            <span class="nav-text-ar d-none">إنشاء نسخة احتياطية للنظام</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <i class="fas fa-chevron-right text-muted"></i>
                                            </button>
                                            <button class="quick-action-btn" onclick="exportDatabase()">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-download text-success me-3"></i>
                                                    <div>
                                                        <div class="fw-semibold small">
                                                            <span class="nav-text">Export Database</span>
                                                            <span class="nav-text-ar d-none">تصدير قاعدة البيانات</span>
                                                        </div>
                                                        <div class="text-muted small">
                                                            <span class="nav-text">Download full database</span>
                                                            <span class="nav-text-ar d-none">تحميل قاعدة البيانات الكاملة</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <i class="fas fa-chevron-right text-muted"></i>
                                            </button>
                                            <button class="quick-action-btn" onclick="showBroadcastModal()">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-bullhorn text-warning me-3"></i>
                                                    <div>
                                                        <div class="fw-semibold small">
                                                            <span class="nav-text">Broadcast Message</span>
                                                            <span class="nav-text-ar d-none">رسالة إذاعية</span>
                                                        </div>
                                                        <div class="text-muted small">
                                                            <span class="nav-text">Send notification to all users</span>
                                                            <span class="nav-text-ar d-none">إرسال إشعار لجميع المستخدمين</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <i class="fas fa-chevron-right text-muted"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- System Status -->
                            <div class="col-lg-6">
                                <div class="bg-white border rounded-3">
                                    <div class="p-3 border-bottom bg-light">
                                        <h6 class="mb-0 fw-bold">
                                            <span class="nav-text">System Status</span>
                                            <span class="nav-text-ar d-none">حالة النظام</span>
                                        </h6>
                                    </div>
                                    <div class="p-3">
                                        <div class="system-status">
                                            <span class="nav-text">Database</span>
                                            <span class="nav-text-ar d-none">قاعدة البيانات</span>
                                            <div class="d-flex align-items-center">
                                                <div class="status-indicator status-online me-2"></div>
                                                <span class="small text-success">
                                                    <span class="nav-text">Online</span>
                                                    <span class="nav-text-ar d-none">متصل</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="system-status">
                                            <span class="nav-text">API Services</span>
                                            <span class="nav-text-ar d-none">خدمات API</span>
                                            <div class="d-flex align-items-center">
                                                <div class="status-indicator status-online me-2"></div>
                                                <span class="small text-success">
                                                    <span class="nav-text">Operational</span>
                                                    <span class="nav-text-ar d-none">تشغيلي</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="system-status">
                                            <span class="nav-text">Last Backup</span>
                                            <span class="nav-text-ar d-none">آخر نسخة احتياطية</span>
                                            <span class="small">
                                                <span class="nav-text">2 hours ago</span>
                                                <span class="nav-text-ar d-none">منذ ساعتين</span>
                                            </span>
                                        </div>
                                        <div class="system-status">
                                            <span class="nav-text">Storage Used</span>
                                            <span class="nav-text-ar d-none">التخزين المستخدم</span>
                                            <span class="small">2.4 GB / 10 GB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Logs -->
                    <div id="adminLogs" class="admin-view d-none">
                        <div class="row g-4">
                            <!-- System Errors -->
                            <div class="col-lg-6">
                                <div class="bg-white border rounded-3">
                                    <div class="p-3 border-bottom bg-danger bg-opacity-10">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-bug text-danger me-2"></i>
                                            <h6 class="mb-0 fw-bold">
                                                <span class="nav-text">System Errors</span>
                                                <span class="nav-text-ar d-none">أخطاء النظام</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <div class="custom-scroll" style="max-height: 250px;">
                                            <div class="border rounded p-3 mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-danger">HIGH</span>
                                                    <span class="text-muted small">2024-05-21 23:45:30</span>
                                                </div>
                                                <div class="fw-semibold small mb-1">TypeError: Cannot read property "length" of undefined</div>
                                                <div class="text-muted small">Analytics Dashboard - Monthly Report Generation</div>
                                            </div>
                                            <div class="border rounded p-3 mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-warning">MEDIUM</span>
                                                    <span class="text-muted small">2024-05-20 14:22:15</span>
                                                </div>
                                                <div class="fw-semibold small mb-1">Network Error: Failed to fetch cemetery data</div>
                                                <div class="text-muted small">Cemetery Systems - Capacity Overview</div>
                                            </div>
                                            <div class="border rounded p-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-info">LOW</span>
                                                    <span class="text-muted small">2024-05-19 09:33:45</span>
                                                </div>
                                                <div class="fw-semibold small mb-1">ReferenceError: broadcastNotification is not defined</div>
                                                <div class="text-muted small">Administration - Notification Broadcast</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- System Backups -->
                            <div class="col-lg-6">
                                <div class="bg-white border rounded-3">
                                    <div class="p-3 border-bottom bg-success bg-opacity-10">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-archive text-success me-2"></i>
                                            <h6 class="mb-0 fw-bold">
                                                <span class="nav-text">System Backups</span>
                                                <span class="nav-text-ar d-none">النسخ الاحتياطية للنظام</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <div class="custom-scroll" style="max-height: 250px;">
                                            <div class="border rounded p-3 mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-success">COMPLETED</span>
                                                    <span class="text-muted small">2.4 GB</span>
                                                </div>
                                                <div class="fw-semibold small mb-1">BACKUP-2024-052201</div>
                                                <div class="text-muted small">2024-05-22 02:00:00</div>
                                            </div>
                                            <div class="border rounded p-3 mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-success">COMPLETED</span>
                                                    <span class="text-muted small">2.3 GB</span>
                                                </div>
                                                <div class="fw-semibold small mb-1">BACKUP-2024-052101</div>
                                                <div class="text-muted small">2024-05-21 02:00:00</div>
                                            </div>
                                            <div class="border rounded p-3 mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-success">COMPLETED</span>
                                                    <span class="text-muted small">2.3 GB</span>
                                                </div>
                                                <div class="fw-semibold small mb-1">BACKUP-2024-052002</div>
                                                <div class="text-muted small">2024-05-20 16:30:00</div>
                                            </div>
                                            <div class="border rounded p-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-danger">FAILED</span>
                                                    <span class="text-muted small">--</span>
                                                </div>
                                                <div class="fw-semibold small mb-1">BACKUP-2024-051901</div>
                                                <div class="text-muted small">2024-05-19 02:00:00</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Model -->
<!-- Add Officer Modal -->
<div class="modal fade" id="addOfficerModal" tabindex="-1" aria-labelledby="addOfficerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addOfficerForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addOfficerModalLabel">Add Officer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <input type="hidden" name="role" id="officerRole">

          <div class="mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" required>
          </div>

          <div class="mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
          </div>

          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" class="form-control" name="phone_number" required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Officer</button>
        </div>
      </div>
    </form>
  </div>
</div>

    
    @include('admin-views.burzakh.frontend.models.master-admin-models')
    <script>
        function sendAlert() {
            const message = prompt('Enter alert message / أدخل رسالة التنبيه:');
            if (message && message.trim()) {
                fetch("{{ route('send.alert') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        notification_message: message.trim()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.success ? '✅ Alert saved!' : '❌ Failed to save alert.');
                })
                .catch(error => alert('❌ Error occurred.'));
            }
        }
    </script>
    <script>
        const analyticsChartData = {
            labels: @json($chartLabels),
            cases: @json($casesData),
            resolved: @json($resolvedData),
        };
    </script>
    @endsection
    