
        // Global variables
        let currentLanguage = 'en';
        let currentTab = 'overview';
        let currentOperationsView = 'personnel';
        let currentAdminView = 'overview';
        let performanceChart = null;

        // Enhanced sample data with complete Arabic translations
        const entities = [
            {
                id: 1,
                name: 'Ahmed Al-Mansoori',
                nameAr: 'أحمد المنصوري',
                department: 'Dubai Police',
                departmentAr: 'شرطة دبي',
                status: 'active',
                currentTask: 'Processing case BZ-2024-156',
                currentTaskAr: 'معالجة الحالة BZ-2024-156',
                currentCaseId: 'BZ-2024-156',
                timeStarted: new Date(Date.now() - 2 * 60 * 60 * 1000),
                loginTime: '08:45',
                location: 'Dubai Police HQ',
                locationAr: 'مقر شرطة دبي',
                casesHandled: 12,
                avgResponseTime: '1.8h',
                avgResponseTimeAr: '١.٨ ساعة',
                efficiency: 96
            },
            {
                id: 2,
                name: 'Fatima Al-Zahra',
                nameAr: 'فاطمة الزهراء',
                department: 'Dubai Municipality Cemetery Division',
                departmentAr: 'قسم المقابر - بلدية دبي',
                status: 'active',
                currentTask: 'Burial slot assignment for BZ-2024-157',
                currentTaskAr: 'تخصيص موقع الدفن للحالة BZ-2024-157',
                currentCaseId: 'BZ-2024-157',
                timeStarted: new Date(Date.now() - 1.5 * 60 * 60 * 1000),
                loginTime: '09:12',
                location: 'Al Qusais Cemetery',
                locationAr: 'مقبرة القصيص',
                casesHandled: 8,
                avgResponseTime: '2.1h',
                avgResponseTimeAr: '٢.١ ساعة',
                efficiency: 94
            },
            {
                id: 3,
                name: 'Mohammed Al-Rashid',
                nameAr: 'محمد الراشد',
                department: 'Road Transport Authority',
                departmentAr: 'هيئة الطرق والمواصلات',
                status: 'delayed',
                currentTask: 'Transport coordination for 3 cases',
                currentTaskAr: 'تنسيق النقل لـ ٣ حالات',
                currentCaseId: 'BZ-2024-158',
                timeStarted: new Date(Date.now() - 4 * 60 * 60 * 1000),
                loginTime: '08:30',
                location: 'RTA Transport Hub',
                locationAr: 'مركز النقل - هيئة الطرق والمواصلات',
                casesHandled: 6,
                avgResponseTime: '3.5h',
                avgResponseTimeAr: '٣.٥ ساعة',
                efficiency: 87
            },
            {
                id: 4,
                name: 'Aisha Al-Maktoum',
                nameAr: 'عائشة المكتوم',
                department: 'Community Development Authority',
                departmentAr: 'هيئة تنمية المجتمع',
                status: 'busy',
                currentTask: 'Family consultation for BZ-2024-159',
                currentTaskAr: 'استشارة عائلية للحالة BZ-2024-159',
                currentCaseId: 'BZ-2024-159',
                timeStarted: new Date(Date.now() - 1 * 60 * 60 * 1000),
                loginTime: '09:45',
                location: 'CDA Family Services',
                locationAr: 'خدمات الأسرة - هيئة تنمية المجتمع',
                casesHandled: 4,
                avgResponseTime: '2.8h',
                avgResponseTimeAr: '٢.٨ ساعة',
                efficiency: 92
            },
            {
                id: 5,
                name: 'Omar Al-Hashimi',
                nameAr: 'عمر الهاشمي',
                department: 'Licensed Morticians',
                departmentAr: 'المرخصون لأعمال الجنائز',
                status: 'offline',
                currentTask: '',
                currentTaskAr: '',
                currentCaseId: '',
                timeStarted: null,
                loginTime: '',
                location: 'Al Barsha Mortuary',
                locationAr: 'مشرحة البرشاء',
                casesHandled: 0,
                avgResponseTime: '0h',
                avgResponseTimeAr: '٠ ساعة',
                efficiency: 0
            }
        ];

        const cases = [
            {
                id: 'BZ-2024-156',
                deceasedName: 'Ahmed Khalil Al-Mansoori',
                deceasedNameAr: 'أحمد خليل المنصوري',
                dateOfDeath: '2024-05-22',
                dateRegistered: new Date('2024-05-22T08:30:00'),
                assignedTo: 'Ahmed Al-Mansoori',
                assignedToAr: 'أحمد المنصوري',
                assignedEntity: 'Dubai Police',
                assignedEntityAr: 'شرطة دبي',
                status: 'in_progress',
                priority: 'high',
                currentStage: 'police_clearance',
                currentStageAr: 'الموافقة الأمنية',
                progress: 25,
                nationality: 'UAE',
                nationalityAr: 'الإمارات',
                age: 67,
                gender: 'Male',
                genderAr: 'ذكر',
                estimatedCompletion: new Date('2024-05-22T16:00:00')
            },
            {
                id: 'BZ-2024-157',
                deceasedName: 'Fatima Mohammed Al-Zahra',
                deceasedNameAr: 'فاطمة محمد الزهراء',
                dateOfDeath: '2024-05-21',
                dateRegistered: new Date('2024-05-21T14:15:00'),
                assignedTo: 'Fatima Al-Zahra',
                assignedToAr: 'فاطمة الزهراء',
                assignedEntity: 'Dubai Municipality Cemetery Division',
                assignedEntityAr: 'قسم المقابر - بلدية دبي',
                status: 'pending_approval',
                priority: 'medium',
                currentStage: 'cemetery_assignment',
                currentStageAr: 'تخصيص المقبرة',
                progress: 75,
                nationality: 'UAE',
                nationalityAr: 'الإمارات',
                age: 45,
                gender: 'Female',
                genderAr: 'أنثى',
                estimatedCompletion: new Date('2024-05-22T14:00:00')
            },
            {
                id: 'BZ-2024-158',
                deceasedName: 'John Michael Smith',
                deceasedNameAr: 'جون مايكل سميث',
                dateOfDeath: '2024-05-20',
                dateRegistered: new Date('2024-05-20T16:45:00'),
                assignedTo: 'Mohammed Al-Rashid',
                assignedToAr: 'محمد الراشد',
                assignedEntity: 'Road Transport Authority',
                assignedEntityAr: 'هيئة الطرق والمواصلات',
                status: 'delayed',
                priority: 'high',
                currentStage: 'transport_coordination',
                currentStageAr: 'تنسيق النقل',
                progress: 50,
                nationality: 'USA',
                nationalityAr: 'الولايات المتحدة',
                age: 54,
                gender: 'Male',
                genderAr: 'ذكر',
                estimatedCompletion: new Date('2024-05-23T12:00:00')
            }
        ];

        const departments = [
            {
                id: 'dp',
                name: 'Dubai Police',
                nameAr: 'شرطة دبي',
                icon: 'fas fa-shield-alt',
                color: 'blue',
                activeUsers: 15,
                totalUsers: 20,
                currentLoad: 68,
                efficiency: 96,
                casesProcessed: 342
            },
            {
                id: 'dmc',
                name: 'Dubai Municipality Cemetery Division',
                nameAr: 'قسم المقابر - بلدية دبي',
                icon: 'fas fa-map-marker-alt',
                color: 'emerald',
                activeUsers: 12,
                totalUsers: 15,
                currentLoad: 75,
                efficiency: 94,
                casesProcessed: 287
            },
            {
                id: 'rta',
                name: 'Road Transport Authority',
                nameAr: 'هيئة الطرق والمواصلات',
                icon: 'fas fa-truck',
                color: 'amber',
                activeUsers: 8,
                totalUsers: 12,
                currentLoad: 82,
                efficiency: 87,
                casesProcessed: 198
            },
            {
                id: 'cda',
                name: 'Community Development Authority',
                nameAr: 'هيئة تنمية المجتمع',
                icon: 'fas fa-users',
                color: 'purple',
                activeUsers: 6,
                totalUsers: 10,
                currentLoad: 55,
                efficiency: 92,
                casesProcessed: 156
            }
        ];

        const systemUsers = [
            {
                id: 1,
                fullName: 'Ahmed Al-Mansoori',
                phone: '+971 50 123 4567',
                email: 'ahmed.almansoori@gov.ae',
                workIdText: 'DP-2024-001',
                loginEmail: 'ahmed.almansoori@burzakh.gov.ae',
                authority: 'Dubai Police',
                role: 'Administrator',
                status: 'active',
                lastLogin: new Date('2024-05-22T08:45:00'),
                casesHandled: 342
            },
            {
                id: 2,
                fullName: 'Fatima Al-Zahra',
                phone: '+971 55 234 5678',
                email: 'fatima.alzahra@dm.gov.ae',
                workIdText: 'DM-2024-015',
                loginEmail: 'fatima.alzahra@burzakh.gov.ae',
                authority: 'Dubai Municipality',
                role: 'Supervisor',
                status: 'active',
                lastLogin: new Date('2024-05-22T09:12:00'),
                casesHandled: 287
            },
            {
                id: 3,
                fullName: 'Mohammed Al-Rashid',
                phone: '+971 52 345 6789',
                email: 'mohammed.alrashid@rta.ae',
                workIdText: 'RTA-2024-088',
                loginEmail: 'mohammed.alrashid@burzakh.gov.ae',
                authority: 'Road Transport Authority',
                role: 'Operator',
                status: 'active',
                lastLogin: new Date('2024-05-22T08:30:00'),
                casesHandled: 198
            },
            {
                id: 4,
                fullName: 'Aisha Al-Maktoum',
                phone: '+971 56 456 7890',
                email: 'aisha.almaktoum@cda.ae',
                workIdText: 'CDA-2024-042',
                loginEmail: 'aisha.almaktoum@burzakh.gov.ae',
                authority: 'Community Development Authority',
                role: 'Operator',
                status: 'suspended',
                lastLogin: new Date('2024-05-20T16:20:00'),
                casesHandled: 156
            },
            {
                id: 5,
                fullName: 'Omar Al-Hashimi',
                phone: '+971 54 567 8901',
                email: 'omar.alhashimi@morticians.ae',
                workIdText: 'MOR-2024-003',
                loginEmail: 'omar.alhashimi@burzakh.gov.ae',
                authority: 'Licensed Morticians',
                role: 'Operator',
                status: 'inactive',
                lastLogin: new Date('2024-05-18T14:30:00'),
                casesHandled: 123
            },
            {
                id: 6,
                fullName: 'Sara Al-Ahmed',
                phone: '+971 58 678 9012',
                email: 'sara.alahmed@ambulance.ae',
                workIdText: 'AMB-2024-027',
                loginEmail: 'sara.alahmed@burzakh.gov.ae',
                authority: 'Ambulance Staff',
                role: 'Viewer',
                status: 'active',
                lastLogin: new Date('2024-05-22T07:15:00'),
                casesHandled: 89
            }
        ];

        const recentActivities = [
            { 
                id: 1, 
                type: 'approval', 
                description: 'Funeral permit approved for Case #BZ-2024-156', 
                descriptionAr: 'تمت الموافقة على تصريح الجنازة للحالة رقم BZ-2024-156',
                timestamp: '14:32', 
                department: 'Cemetery Authority',
                departmentAr: 'سلطة المقابر',
                priority: 'normal' 
            },
            { 
                id: 2, 
                type: 'submission', 
                description: 'New case submitted by Police Department', 
                descriptionAr: 'حالة جديدة مقدمة من قبل إدارة الشرطة',
                timestamp: '14:28', 
                department: 'Police Department',
                departmentAr: 'إدارة الشرطة',
                priority: 'high' 
            },
            { 
                id: 3, 
                type: 'completion', 
                description: 'Transportation coordination completed', 
                descriptionAr: 'تم الانتهاء من تنسيق النقل',
                timestamp: '14:15', 
                department: 'Transport Authority',
                departmentAr: 'هيئة النقل',
                priority: 'normal' 
            },
            { 
                id: 4, 
                type: 'delay', 
                description: 'Case BZ-2024-158 exceeds 2-hour limit', 
                descriptionAr: 'الحالة BZ-2024-158 تتجاوز حد الساعتين',
                timestamp: '14:10', 
                department: 'RTA',
                departmentAr: 'هيئة الطرق والمواصلات',
                priority: 'high' 
            },
            { 
                id: 5, 
                type: 'assignment', 
                description: 'Case BZ-2024-159 assigned to CDA', 
                descriptionAr: 'تم تعيين الحالة BZ-2024-159 لهيئة تنمية المجتمع',
                timestamp: '14:05', 
                department: 'System',
                departmentAr: 'النظام',
                priority: 'normal' 
            }
        ];

        // Utility functions
        function t(englishText, arabicText) {
            return currentLanguage === 'ar' ? arabicText : englishText;
        }

        // Add Arabic number formatting
        function formatNumber(num) {
            if (currentLanguage === 'ar') {
                // Convert to Arabic-Indic numerals
                const arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
                return num.toString().replace(/[0-9]/g, (digit) => arabicNumerals[digit]);
            }
            return num.toString();
        }

        function formatTime(date) {
            const locale = currentLanguage === 'ar' ? 'ar-AE' : 'en-US';
            return date.toLocaleTimeString(locale, { hour: '2-digit', minute: '2-digit' });
        }

        function formatDate(date) {
            const locale = currentLanguage === 'ar' ? 'ar-AE' : 'en-US';
            return date.toLocaleDateString(locale);
        }

        function getStatusText(status) {
            const statusTranslations = {
                'active': { en: 'ACTIVE', ar: 'نشط' },
                'busy': { en: 'BUSY', ar: 'مشغول' },
                'delayed': { en: 'DELAYED', ar: 'متأخر' },
                'offline': { en: 'OFFLINE', ar: 'غير متصل' },
                'in_progress': { en: 'IN PROGRESS', ar: 'قيد التقدم' },
                'pending_approval': { en: 'PENDING', ar: 'في الانتظار' },
                'completed': { en: 'COMPLETED', ar: 'مكتمل' },
                'high': { en: 'HIGH', ar: 'عالي' },
                'medium': { en: 'MEDIUM', ar: 'متوسط' },
                'normal': { en: 'NORMAL', ar: 'عادي' },
                'low': { en: 'LOW', ar: 'منخفض' },
                'urgent': { en: 'URGENT', ar: 'عاجل' }
            };
            
            return statusTranslations[status] ? statusTranslations[status][currentLanguage] : status.toUpperCase();
        }

        function isTaskDelayed(entity) {
            if (!entity.timeStarted) return false;
            const diffInHours = (new Date() - entity.timeStarted) / (1000 * 60 * 60);
            return diffInHours > 2;
        }

        function getStatusClass(status) {
            switch (status) {
                case 'active':
                case 'completed':
                case 'operational':
                    return 'status-active';
                case 'delayed':
                case 'urgent':
                case 'high':
                    return 'status-delayed';
                case 'pending_approval':
                case 'pending':
                case 'medium':
                case 'busy':
                    return 'status-pending';
                case 'offline':
                case 'inactive':
                case 'low':
                case 'normal':
                default:
                    return 'status-offline';
            }
        }

        function getStatusIcon(status) {
            switch (status) {
                case 'active':
                case 'operational':
                    return 'fas fa-play-circle';
                case 'busy':
                    return 'fas fa-clock';
                case 'offline':
                case 'inactive':
                    return 'fas fa-stop-circle';
                case 'in_progress':
                    return 'fas fa-spinner';
                case 'pending_approval':
                    return 'fas fa-exclamation-circle';
                case 'completed':
                    return 'fas fa-check-circle';
                case 'delayed':
                    return 'fas fa-exclamation-triangle';
                default:
                    return 'fas fa-info-circle';
            }
        }

        function getActivityIcon(type) {
            switch (type) {
                case 'approval':
                    return 'fas fa-check-circle text-success';
                case 'submission':
                    return 'fas fa-file-plus text-info';
                case 'completion':
                    return 'fas fa-check text-success';
                case 'delay':
                    return 'fas fa-exclamation-triangle text-danger';
                case 'assignment':
                    return 'fas fa-user-plus text-info';
                default:
                    return 'fas fa-info-circle text-secondary';
            }
        }

        // Initialize the application
        function init() {
            updateCurrentTime();
            setInterval(updateCurrentTime, 60000); // Update every minute
            
            setupEventListeners();
            updateLanguageDisplay();
            switchTab('overview');
            
            // Animate metrics on load
            setTimeout(() => {
                animateCounters();
            }, 500);
        }

        function updateCurrentTime() {
            const now = new Date();
            const locale = currentLanguage === 'ar' ? 'ar-AE' : 'en-US';
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            
            document.getElementById('currentDate').textContent = now.toLocaleDateString(locale, options);
            document.getElementById('lastUpdated').textContent = now.toLocaleTimeString(locale, {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function setupEventListeners() {
            // Mobile menu toggle
            document.getElementById('mobileMenuBtn').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('show');
            });

            // Language toggle
            document.getElementById('languageToggle').addEventListener('click', toggleLanguage);

            // Notification toggle
            document.getElementById('notificationBtn').addEventListener('click', function() {
                const dropdown = document.getElementById('notificationsDropdown');
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            });

            // Close notifications when clicking outside
            document.addEventListener('click', function(e) {
                const notificationBtn = document.getElementById('notificationBtn');
                const dropdown = document.getElementById('notificationsDropdown');
                if (!notificationBtn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });

            // Sidebar navigation
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tab = this.getAttribute('data-tab');
                    if (tab) {
                        switchTab(tab);
                    }
                });
            });

            // Operations sub-navigation
            document.addEventListener('click', function(e) {
                if (e.target.closest('#operationsTabs .nav-link')) {
                    e.preventDefault();
                    const view = e.target.closest('.nav-link').getAttribute('data-view');
                    if (view) {
                        switchOperationsView(view);
                    }
                }
            });

            // Admin sub-navigation
            document.addEventListener('click', function(e) {
                if (e.target.closest('#adminTabs .nav-link')) {
                    e.preventDefault();
                    const view = e.target.closest('.nav-link').getAttribute('data-view');
                    if (view) {
                        switchAdminView(view);
                    }
                }
            });
        }

        function toggleLanguage() {
            currentLanguage = currentLanguage === 'en' ? 'ar' : 'en';
            document.documentElement.dir = currentLanguage === 'ar' ? 'rtl' : 'ltr';
            document.documentElement.lang = currentLanguage;
            
            // Apply RTL-specific class for better styling control
            if (currentLanguage === 'ar') {
                document.body.classList.add('rtl-mode');
            } else {
                document.body.classList.remove('rtl-mode');
            }
            
            updateLanguageDisplay();
            updatePageTitle();
            
            // Refresh current content to apply language changes
            switch (currentTab) {
                case 'overview':
                    loadOverviewContent();
                    break;
                case 'operations':
                    loadOperationsContent();
                    break;
                case 'departments':
                    loadDepartmentsContent();
                    break;
                case 'administration':
                    loadAdministrationContent();
                    break;
            }
        }

        function updateLanguageDisplay() {
            const langBtn = document.getElementById('languageToggle');
            langBtn.textContent = currentLanguage === 'en' ? 'العربية' : 'English';

            // Show/hide language-specific text
            document.querySelectorAll('.nav-text').forEach(el => {
                el.classList.toggle('d-none', currentLanguage === 'ar');
            });
            document.querySelectorAll('.nav-text-ar').forEach(el => {
                el.classList.toggle('d-none', currentLanguage === 'en');
            });
        }

        function updatePageTitle() {
            const titles = {
                overview: { en: 'System Overview', ar: 'نظرة عامة على النظام' },
                operations: { en: 'Operations & Case Management', ar: 'العمليات وإدارة الحالات' },
                departments: { en: 'Department Management', ar: 'إدارة الأقسام' },
                analytics: { en: 'Analytics Dashboard', ar: 'لوحة التحليلات' },
                administration: { en: 'System Administration', ar: 'إدارة النظام' }
            };
            
            const title = titles[currentTab];
            if (title) {
                document.getElementById('pageTitle').textContent = title[currentLanguage];
            }
        }

        function switchTab(tabName) {
            currentTab = tabName;
            
            // Update sidebar navigation
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
            
            // Hide all tab content
            document.querySelectorAll('.tab-content-custom').forEach(tab => {
                tab.classList.add('d-none');
            });
            
            // Show selected tab
            const selectedTab = document.getElementById(tabName + 'Tab');
            if (selectedTab) {
                selectedTab.classList.remove('d-none');
                selectedTab.classList.add('fade-in');
            }
            
            updatePageTitle();
            
            // Load tab-specific content
            switch (tabName) {
                case 'overview':
                    loadOverviewContent();
                    break;
                case 'operations':
                    loadOperationsContent();
                    break;
                case 'departments':
                    loadDepartmentsContent();
                    break;
                case 'analytics':
                    loadAnalyticsContent();
                    break;
                case 'administration':
                    loadAdministrationContent();
                    break;
            }
        }

        function switchOperationsView(viewName) {
            currentOperationsView = viewName;
            
            // Update navigation
            document.querySelectorAll('#operationsTabs .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            document.querySelector(`#operationsTabs [data-view="${viewName}"]`).classList.add('active');
            
            // Hide all views
            document.querySelectorAll('.operations-view').forEach(view => {
                view.classList.add('d-none');
            });
            
            // Show selected view
            const selectedView = document.getElementById(viewName + 'View');
            if (selectedView) {
                selectedView.classList.remove('d-none');
            }
            
            // Load view-specific content
            switch (viewName) {
                case 'personnel':
                    loadPersonnelView();
                    break;
                case 'cases':
                    loadCasesView();
                    break;
                case 'workflow':
                    loadWorkflowView();
                    break;
            }
        }

        function switchAdminView(viewName) {
            currentAdminView = viewName;
            
            // Update navigation
            document.querySelectorAll('#adminTabs .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            document.querySelector(`#adminTabs [data-view="${viewName}"]`).classList.add('active');
            
            // Hide all views
            document.querySelectorAll('.admin-view').forEach(view => {
                view.classList.add('d-none');
            });
            
            // Show selected view
            const selectedView = document.getElementById('admin' + viewName.charAt(0).toUpperCase() + viewName.slice(1));
            if (selectedView) {
                selectedView.classList.remove('d-none');
            }
            
            // Load view-specific content
            switch (viewName) {
                case 'overview':
                    loadAdminOverview();
                    break;
                case 'users':
                    loadAdminUsers();
                    break;
                case 'system':
                    loadAdminSystem();
                    break;
                case 'logs':
                    loadAdminLogs();
                    break;
            }
        }

        function animateCounters() {
            const counters = document.querySelectorAll('[id$="Cases"], [id$="Approvals"]');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/,/g, ''));
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = target.toLocaleString();
                        clearInterval(timer);
                    } else {
                        counter.textContent = Math.floor(current).toLocaleString();
                    }
                }, 30);
            });
        }

        function loadOverviewContent() {
            // loadEntitiesGrid();
            loadActivityFeed();
        }

        // function loadEntitiesGrid() {
        //     const grid = document.getElementById('entitiesGrid');
        //     if (!grid) return;
            
        //     grid.innerHTML = entities.map(entity => {
        //         const isDelayed = isTaskDelayed(entity);
        //         const statusClass = isDelayed ? 'delayed' : entity.status;
                
        //         return `
        //             <div class="col-lg-6">
        //                 <div class="entity-card ${statusClass}">
        //                     <div class="d-flex justify-content-between align-items-start mb-3">
        //                         <div class="d-flex align-items-center">
        //                             <div class="entity-avatar bg-${getEntityColor(entity.status)}" style="background-color: ${getEntityBgColor(statusClass)};">
        //                                 <i class="fas fa-user"></i>
        //                             </div>
        //                             <div>
        //                                 <div class="fw-bold">${entity.name}</div>
        //                                 <div class="text-muted small">${entity.department}</div>
        //                                 <div class="text-muted small">
        //                                     <i class="fas fa-map-marker-alt me-1"></i>
        //                                     ${entity.location}
        //                                 </div>
        //                             </div>
        //                         </div>
        //                         <div class="text-end">
        //                             <div class="h4 fw-bold mb-0">${entity.efficiency}%</div>
        //                             <div class="text-muted small">${t('Efficiency', 'الكفاءة')}</div>
        //                         </div>
        //                     </div>
                            
        //                     <div class="mb-3">
        //                         <div class="d-flex justify-content-between align-items-center mb-2">
        //                             <span class="status-badge ${getStatusClass(statusClass)}">
        //                                 <i class="${getStatusIcon(statusClass)} me-1"></i>
        //                                 ${getStatusText(isDelayed ? 'delayed' : entity.status)}
        //                             </span>
        //                             ${entity.currentCaseId ? `<span class="badge bg-secondary">${entity.currentCaseId}</span>` : ''}
        //                         </div>
        //                         <div class="small text-muted mb-2">
        //                             ${entity.currentTask || t('No active task assigned', 'لا توجد مهمة نشطة مخصصة')}
        //                         </div>
        //                         ${entity.timeStarted && isDelayed ? `
        //                             <div class="small text-danger">
        //                                 ${Math.floor((new Date() - entity.timeStarted) / (1000 * 60 * 60))}h ${t('elapsed', 'مضى')}
        //                             </div>
        //                         ` : ''}
        //                     </div>

        //                     <div class="performance-grid mb-3">
        //                         <div class="performance-item">
        //                             <div class="performance-value">${formatNumber(entity.casesHandled)}</div>
        //                             <div class="performance-label">${t('Cases', 'الحالات')}</div>
        //                         </div>
        //                         <div class="performance-item">
        //                             <div class="performance-value">${entity.avgResponseTime}</div>
        //                             <div class="performance-label">${t('Response', 'الاستجابة')}</div>
        //                         </div>
        //                         <div class="performance-item">
        //                             <div class="performance-value">${entity.loginTime || 'N/A'}</div>
        //                             <div class="performance-label">${t('Login', 'تسجيل الدخول')}</div>
        //                         </div>
        //                     </div>

        //                     <div class="d-flex justify-content-between">
        //                         <div>
        //                             <button class="btn btn-outline-primary btn-sm me-2">
        //                                 <i class="fas fa-eye me-1"></i>
        //                                 ${t('View', 'عرض')}
        //                             </button>
        //                             <button class="btn btn-outline-secondary btn-sm">
        //                                 <i class="fas fa-comment me-1"></i>
        //                                 ${t('Contact', 'اتصال')}
        //                             </button>
        //                         </div>
        //                         <small class="text-muted align-self-end">ID: ${entity.id}</small>
        //                     </div>
        //                 </div>
        //             </div>
        //         `;
        //     }).join('');
        // }
        // async function loadEntitiesGrid() {
        //     const grid = document.getElementById('entitiesGrid');
        //     if (!grid) return;
        
        //     try {
        //         const response = await fetch('/admin/fetch-entities'); 
        //         const data = await response.json();
        
        //         const entities = data.entities || [];
        
        //         grid.innerHTML = entities.map(entity => {
        //             const isDelayed = isTaskDelayed(entity);
        //             const statusClass = isDelayed ? 'delayed' : entity.status;
        
        //                     return `
        //             <div class="col-lg-6">
        //                 <div class="entity-card ${statusClass}">
        //                     <div class="d-flex justify-content-between align-items-start mb-3">
        //                         <div class="d-flex align-items-center">
        //                             <div class="entity-avatar bg-${getEntityColor(entity.status)}" style="background-color: ${getEntityBgColor(statusClass)};">
        //                                 <i class="fas fa-user"></i>
        //                             </div>
        //                             <div>
        //                                 <div class="fw-bold">${entity.name}</div>
        //                                 <div class="text-muted small">${entity.department}</div>
        //                                 <div class="text-muted small">
        //                                     <i class="fas fa-map-marker-alt me-1"></i>
        //                                     ${entity.location}
        //                                 </div>
        //                             </div>
        //                         </div>
        //                         <div class="text-end">
        //                             <div class="h4 fw-bold mb-0">${entity.efficiency}%</div>
        //                             <div class="text-muted small">${t('Efficiency', 'الكفاءة')}</div>
        //                         </div>
        //                     </div>
                            
        //                     <div class="mb-3">
        //                         <div class="d-flex justify-content-between align-items-center mb-2">
        //                             <span class="status-badge ${getStatusClass(statusClass)}">
        //                                 <i class="${getStatusIcon(statusClass)} me-1"></i>
        //                                 ${getStatusText(isDelayed ? 'delayed' : entity.status)}
        //                             </span>
        //                             ${entity.currentCaseId ? `<span class="badge bg-secondary">${entity.currentCaseId}</span>` : ''}
        //                         </div>
        //                         <div class="small text-muted mb-2">
        //                             ${entity.currentTask || t('No active task assigned', 'لا توجد مهمة نشطة مخصصة')}
        //                         </div>
        //                         ${entity.timeStarted && isDelayed ? `
        //                             <div class="small text-danger">
        //                                 ${Math.floor((new Date() - entity.timeStarted) / (1000 * 60 * 60))}h ${t('elapsed', 'مضى')}
        //                             </div>
        //                         ` : ''}
        //                     </div>

        //                     <div class="performance-grid mb-3">
        //                         <div class="performance-item">
        //                             <div class="performance-value">${formatNumber(entity.casesHandled)}</div>
        //                             <div class="performance-label">${t('Cases', 'الحالات')}</div>
        //                         </div>
        //                         <div class="performance-item">
        //                             <div class="performance-value">${entity.avgResponseTime}</div>
        //                             <div class="performance-label">${t('Response', 'الاستجابة')}</div>
        //                         </div>
        //                         <div class="performance-item">
        //                             <div class="performance-value">${entity.loginTime || 'N/A'}</div>
        //                             <div class="performance-label">${t('Login', 'تسجيل الدخول')}</div>
        //                         </div>
        //                     </div>

        //                     <div class="d-flex justify-content-between">
        //                         <div>
        //                             <button class="btn btn-outline-primary btn-sm me-2">
        //                                 <i class="fas fa-eye me-1"></i>
        //                                 ${t('View', 'عرض')}
        //                             </button>
        //                             <button class="btn btn-outline-secondary btn-sm">
        //                                 <i class="fas fa-comment me-1"></i>
        //                                 ${t('Contact', 'اتصال')}
        //                             </button>
        //                         </div>
        //                         <small class="text-muted align-self-end">ID: ${entity.id}</small>
        //                     </div>
        //                 </div>
        //             </div>
        //         `;
        //     }).join('');
        //     } catch (error) {
        //         console.error('Failed to load entities:', error);
        //         grid.innerHTML = '<div class="text-danger">Failed to load data.</div>';
        //     }
        // }
        

        function getEntityColor(status) {
            switch (status) {
                case 'active': return 'success';
                case 'busy': return 'warning';
                case 'delayed': return 'danger';
                case 'offline': return 'secondary';
                default: return 'primary';
            }
        }

        function getEntityBgColor(status) {
            switch (status) {
                case 'active': return '#28a745';
                case 'busy': return '#ffc107';
                case 'delayed': return '#dc3545';
                case 'offline': return '#6c757d';
                default: return '#007bff';
            }
        }

        function loadActivityFeed() {
            const feed = document.getElementById('activityFeed');
            if (!feed) return;
            
            feed.innerHTML = recentActivities.map(activity => `
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="${getActivityIcon(activity.type)}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small fw-medium">
                            ${currentLanguage === 'ar' ? activity.descriptionAr : activity.description}
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span class="text-muted small">${activity.timestamp}</span>
                            <span class="badge bg-light text-dark small">
                                ${currentLanguage === 'ar' ? activity.departmentAr : activity.department}
                            </span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function loadOperationsContent() {
            switch (currentOperationsView) {
                case 'personnel':
                    loadPersonnelView();
                    break;
                case 'cases':
                    loadCasesView();
                    break;
                case 'workflow':
                    loadWorkflowView();
                    break;
            }
        }

        function loadPersonnelView() {
            const grid = document.getElementById('personnelGrid');
            if (!grid) return;
            
            grid.innerHTML = entities.map(entity => {
                const isDelayed = isTaskDelayed(entity);
                const statusClass = isDelayed ? 'delayed' : entity.status;
                const assignedCase = cases.find(c => c.id === entity.currentCaseId);
                
                return `
                    <div class="col-xl-6">
                        <div class="entity-card ${statusClass}">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="entity-avatar" style="background-color: ${getEntityBgColor(statusClass)};">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">${entity.name}</div>
                                        <div class="text-muted small">${entity.department}</div>
                                        <div class="text-muted small">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            ${entity.location}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="h4 fw-bold mb-0">${entity.efficiency}%</div>
                                    <div class="text-muted small mb-2">${t('Efficiency', 'الكفاءة')}</div>
                                    <span class="status-badge ${getStatusClass(statusClass)}">
                                        <i class="${getStatusIcon(statusClass)} me-1"></i>
                                        ${isDelayed ? t('DELAYED', 'متأخر') : entity.status.toUpperCase()}
                                    </span>
                                </div>
                            </div>

                            ${assignedCase ? `
                                <div class="mb-3 p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small fw-semibold">${t('Current Case:', 'الحالة الحالية:')}</span>
                                        <span class="badge bg-secondary">${assignedCase.id}</span>
                                    </div>
                                    <div class="small fw-medium">${assignedCase.deceasedName}</div>
                                    <div class="small text-muted">${assignedCase.currentStage.replace('_', ' ').toUpperCase()}</div>
                                    
                                    <div class="mt-2">
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span>${t('Progress', 'التقدم')}</span>
                                            <span class="fw-semibold">${assignedCase.progress}%</span>
                                        </div>
                                        <div class="progress-custom">
                                            <div class="progress-bar-custom" style="width: ${assignedCase.progress}%; background-color: ${assignedCase.progress > 75 ? '#28a745' : assignedCase.progress > 50 ? '#007bff' : assignedCase.progress > 25 ? '#ffc107' : '#6c757d'};"></div>
                                        </div>
                                    </div>
                                    
                                    ${entity.timeStarted ? `
                                        <div class="small text-muted mt-2">
                                            ${t('Started:', 'بدأ:')} ${formatTime(entity.timeStarted)}
                                            ${isDelayed ? `<span class="text-danger fw-semibold ms-2">(${Math.floor((new Date() - entity.timeStarted) / (1000 * 60 * 60))}h ${t('elapsed', 'مضى')})</span>` : ''}
                                        </div>
                                    ` : ''}
                                </div>
                            ` : `
                                <div class="mb-3 p-3 bg-light rounded">
                                    <div class="small text-muted">${t('No active case assigned', 'لا توجد حالة نشطة مخصصة')}</div>
                                </div>
                            `}

                            <div class="performance-grid mb-3">
                                <div class="performance-item">
                                    <div class="performance-value">${entity.casesHandled}</div>
                                    <div class="performance-label">${t('Cases Today', 'الحالات اليوم')}</div>
                                </div>
                                <div class="performance-item">
                                    <div class="performance-value">${entity.avgResponseTime}</div>
                                    <div class="performance-label">${t('Avg Time', 'الوقت المتوسط')}</div>
                                </div>
                                <div class="performance-item">
                                    <div class="performance-value">${entity.loginTime || 'N/A'}</div>
                                    <div class="performance-label">${t('Login Time', 'وقت الدخول')}</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <div>
                                    <button class="btn btn-outline-primary btn-sm me-2">
                                        <i class="fas fa-eye me-1"></i>
                                        ${t('View Details', 'عرض التفاصيل')}
                                    </button>
                                    <button class="btn btn-outline-success btn-sm me-2">
                                        <i class="fas fa-comment me-1"></i>
                                        ${t('Message', 'رسالة')}
                                    </button>
                                    ${assignedCase ? `
                                        <button class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit me-1"></i>
                                            ${t('Reassign', 'إعادة تعيين')}
                                        </button>
                                    ` : ''}
                                </div>
                                <small class="text-muted align-self-end">ID: ${entity.id}</small>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function loadCasesView() {
            // Update case count cards
            document.getElementById('inProgressCount').textContent = cases.filter(c => c.status === 'in_progress').length;
            document.getElementById('pendingCount').textContent = cases.filter(c => c.status === 'pending_approval').length;
            document.getElementById('delayedCount').textContent = cases.filter(c => c.status === 'delayed').length;
            document.getElementById('completedCount').textContent = cases.filter(c => c.status === 'completed').length;

            // Load cases table
            const tableBody = document.getElementById('casesTableBody');
            if (!tableBody) return;
            
            tableBody.innerHTML = cases.map(caseItem => `
                <tr onclick="selectCase('${caseItem.id}')" style="cursor: pointer;">
                    <td>
                        <div>
                            <div class="d-flex align-items-center">
                                <div class="fw-medium">${caseItem.deceasedName}</div>
                                <span class="status-badge ${getStatusClass(caseItem.priority)} ms-2">
                                    <i class="${getStatusIcon(caseItem.priority)} me-1"></i>
                                    ${caseItem.priority.toUpperCase()}
                                </span>
                            </div>
                            <div class="small text-muted mt-1">
                                ${caseItem.id} • ${caseItem.nationality} • ${caseItem.age}y ${caseItem.gender.charAt(0)}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="status-badge ${getStatusClass(caseItem.status)}">
                                <i class="${getStatusIcon(caseItem.status)} me-1"></i>
                                ${caseItem.status.replace('_', ' ').toUpperCase()}
                            </span>
                            <div class="mt-2">
                                <div class="progress-custom">
                                    <div class="progress-bar-custom" style="width: ${caseItem.progress}%; background-color: ${caseItem.progress > 75 ? '#28a745' : caseItem.progress > 50 ? '#007bff' : caseItem.progress > 25 ? '#ffc107' : '#6c757d'};"></div>
                                </div>
                                <div class="small text-muted mt-1">${caseItem.progress}% ${t('complete', 'مكتمل')}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div class="fw-medium">${caseItem.assignedTo}</div>
                            <div class="small text-muted">${caseItem.assignedEntity}</div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div class="small">
                                ${t('Registered:', 'مسجل:')} ${formatDate(caseItem.dateRegistered)}
                            </div>
                            <div class="small text-muted">
                                ${t('Est. completion:', 'الإنجاز المقدر:')} ${formatDate(caseItem.estimatedCompletion)}
                            </div>
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-outline-primary btn-sm me-1" onclick="event.stopPropagation(); viewCase('${caseItem.id}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-success btn-sm me-1" onclick="event.stopPropagation(); editCase('${caseItem.id}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="event.stopPropagation(); moreActions('${caseItem.id}')">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function loadWorkflowView() {
            // Load active workflows
            const activeWorkflows = document.getElementById('activeWorkflows');
            if (activeWorkflows) {
                activeWorkflows.innerHTML = cases.filter(c => c.status === 'in_progress').map(caseItem => `
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-medium">${caseItem.deceasedName}</div>
                                <div class="small text-muted">${caseItem.id}</div>
                            </div>
                            <span class="status-badge ${getStatusClass(caseItem.priority)}">
                                ${caseItem.priority.toUpperCase()}
                            </span>
                        </div>
                        
                        <div class="mb-2">
                            <div class="small fw-medium mb-2">${t('Timeline Steps', 'خطوات الجدول الزمني')}</div>
                            <div class="d-flex align-items-center mb-1">
                                <div class="bg-success rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                <div class="small">${t('REGISTERED', 'مسجل')}</div>
                                <div class="ms-auto small text-muted">${formatTime(caseItem.dateRegistered)}</div>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <div class="bg-primary rounded-circle me-2 pulse" style="width: 12px; height: 12px;"></div>
                                <div class="small">${caseItem.currentStage.replace('_', ' ').toUpperCase()}</div>
                                <div class="ms-auto small text-muted">${t('In Progress', 'قيد التقدم')}</div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                <div class="small text-muted">${t('COMPLETION', 'الإنجاز')}</div>
                                <div class="ms-auto small text-muted">${t('Pending', 'في الانتظار')}</div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            // Load issues and delays
            const issuesDelays = document.getElementById('issuesDelays');
            if (issuesDelays) {
                const delayedCases = cases.filter(c => c.status === 'delayed');
                const delayedEntities = entities.filter(e => isTaskDelayed(e));
                
                issuesDelays.innerHTML = [
                    ...delayedCases.map(caseItem => `
                        <div class="border border-danger bg-danger bg-opacity-10 rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="fw-medium">${caseItem.deceasedName}</div>
                                <span class="badge bg-danger">${t('DELAYED', 'متأخر')}</span>
                            </div>
                            <div class="small text-muted mb-1">${caseItem.id} • ${caseItem.assignedTo}</div>
                            <div class="small text-danger">
                                ${t('Stuck at:', 'عالق في:')} ${caseItem.currentStage.replace('_', ' ').toUpperCase()}
                            </div>
                        </div>
                    `),
                    ...delayedEntities.map(entity => `
                        <div class="border border-warning bg-warning bg-opacity-10 rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="fw-medium">${entity.name}</div>
                                <span class="badge bg-warning">${t('OVERTIME', 'وقت إضافي')}</span>
                            </div>
                            <div class="small text-muted mb-1">${entity.department}</div>
                            <div class="small text-warning">
                                ${entity.currentCaseId} • ${Math.floor((new Date() - entity.timeStarted) / (1000 * 60 * 60))}h ${t('elapsed', 'مضى')}
                            </div>
                        </div>
                    `)
                ].join('');
            }
        }

        // function loadDepartmentsContent() {
        //     const grid = document.getElementById('departmentsGrid');
        //     if (!grid) return;
            
        //     grid.innerHTML = departments.map(dept => `
        //         <div class="col-md-6 col-xl-4">
        //             <div class="department-card">
        //                 <div class="d-flex align-items-center mb-3">
        //                     <div class="department-icon ${dept.color}">
        //                         <i class="${dept.icon}"></i>
        //                     </div>
        //                     <div>
        //                         <div class="fw-bold">${currentLanguage === 'ar' ? dept.nameAr : dept.name}</div>
        //                         <div class="small text-muted">${currentLanguage === 'ar' ? dept.name : dept.nameAr}</div>
        //                     </div>
        //                 </div>
        //                 <div class="row g-3">
        //                     <div class="col-6">
        //                         <div class="text-center">
        //                             <div class="fw-bold">${dept.activeUsers}/${dept.totalUsers}</div>
        //                             <div class="small text-muted">${t('Active Users', 'المستخدمون النشطون')}</div>
        //                         </div>
        //                     </div>
        //                     <div class="col-6">
        //                         <div class="text-center">
        //                             <div class="fw-bold">${dept.efficiency}%</div>
        //                             <div class="small text-muted">${t('Efficiency', 'الكفاءة')}</div>
        //                         </div>
        //                     </div>
        //                     <div class="col-6">
        //                         <div class="text-center">
        //                             <div class="fw-bold">${dept.casesProcessed}</div>
        //                             <div class="small text-muted">${t('Cases Processed', 'الحالات المعالجة')}</div>
        //                         </div>
        //                     </div>
        //                     <div class="col-6">
        //                         <div class="text-center">
        //                             <div class="fw-bold">${dept.currentLoad}%</div>
        //                             <div class="small text-muted">${t('Current Load', 'الحمولة الحالية')}</div>
        //                         </div>
        //                     </div>
        //                 </div>
        //             </div>
        //         </div>
        //     `).join('');
        // }

        // function loadAnalyticsContent() {
        //     const ctx = document.getElementById('performanceChart');
        //     if (!ctx) return;
            
        //     if (performanceChart) {
        //         performanceChart.destroy();
        //     }
            
        //     performanceChart = new Chart(ctx, {
        //         type: 'line',
        //         data: {
        //             labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        //             datasets: [{
        //                 label: t('Cases', 'الحالات'),
        //                 data: [980, 1050, 1120, 1200, 1247],
        //                 borderColor: '#1e3a8a',
        //                 backgroundColor: 'rgba(30, 58, 138, 0.1)',
        //                 tension: 0.4
        //             }, {
        //                 label: t('Resolved', 'محلول'),
        //                 data: [920, 995, 1075, 1152, 1172],
        //                 borderColor: '#10b981',
        //                 backgroundColor: 'rgba(16, 185, 129, 0.1)',
        //                 tension: 0.4
        //             }]
        //         },
        //         options: {
        //             responsive: true,
        //             maintainAspectRatio: false,
        //             plugins: {
        //                 legend: {
        //                     position: 'top',
        //                 }
        //             },
        //             scales: {
        //                 y: {
        //                     beginAtZero: true
        //                 }
        //             }
        //         }
        //     });
        // }
        async function loadDepartmentsContent() {
    const grid = document.getElementById('departmentsGrid');
    if (!grid) return;

    try {
        const response = await fetch('/admin/departments');
        const departments = await response.json();

        grid.innerHTML = departments.map(dept => {
            const efficiency = 100; // static
            const currentLoad = dept.totalUsers > 0 
                ? ((dept.activeUsers / dept.totalUsers) * 100).toFixed(1) 
                : 0;

            return `
                <div class="col-md-6 col-xl-4">
                    <div class="department-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="department-icon ${dept.color}">
                                <i class="${dept.icon}"></i>
                            </div>
                            <div>
                                <div class="fw-bold">${currentLanguage === 'ar' ? dept.nameAr : dept.name}</div>
                                <div class="small text-muted">${currentLanguage === 'ar' ? dept.name : dept.nameAr}</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="fw-bold">${dept.activeUsers}/${dept.totalUsers}</div>
                                    <div class="small text-muted">${t('Active Users', 'المستخدمون النشطون')}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="fw-bold">${efficiency}%</div>
                                    <div class="small text-muted">${t('Efficiency', 'الكفاءة')}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="fw-bold">${dept.casesProcessed}</div>
                                    <div class="small text-muted">${t('Cases Processed', 'الحالات المعالجة')}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="fw-bold">25%</div>
                                    <div class="small text-muted">${t('Current Load', 'الحمولة الحالية')}</div>
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button class="btn btn-sm btn-primary" onclick="openAddOfficerModal('${dept.role}')">
                                    Add ${dept.name} Officer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    } catch (error) {
        console.error('Error loading departments:', error);
    }
}



function openAddOfficerModal(role) {
    document.getElementById('officerRole').value = role;
    const modal = new bootstrap.Modal(document.getElementById('addOfficerModal'));
    modal.show();
}

document.getElementById('addOfficerForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const response = await fetch('/admin/add-officer', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });

        const result = await response.json();
        if (result.success) {
            alert('Officer added successfully!');
            this.reset();
            bootstrap.Modal.getInstance(document.getElementById('addOfficerModal')).hide();
        } else {
            alert(result.message || 'Failed to add officer');
        }
    } catch (error) {
        console.error('Error adding officer:', error);
        alert('An error occurred. Please try again.');
    }
});







        function loadAnalyticsContent() {
            const ctx = document.getElementById('performanceChart');
            if (!ctx) return;
        
            if (performanceChart) {
                performanceChart.destroy();
            }
        
            performanceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: analyticsChartData.labels,
                    datasets: [
                        {
                            label: t('Cases', 'الحالات'),
                            data: analyticsChartData.cases,
                            borderColor: '#1e3a8a',
                            backgroundColor: 'rgba(30, 58, 138, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: t('Resolved', 'محلول'),
                            data: analyticsChartData.resolved,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
        

        function loadAdministrationContent() {
            switch (currentAdminView) {
                case 'overview':
                    loadAdminOverview();
                    break;
                case 'users':
                    loadAdminUsers();
                    break;
                case 'system':
                    loadAdminSystem();
                    break;
                case 'logs':
                    loadAdminLogs();
                    break;
            }
        }

        function loadAdminOverview() {
            // Update user counts
            document.getElementById('activeUsersCount').textContent = systemUsers.filter(u => u.status === 'active').length;
            document.getElementById('totalUsersCount').textContent = systemUsers.length;
            document.getElementById('suspendedUsersCount').textContent = systemUsers.filter(u => u.status === 'suspended').length;
            
            // Load user activity feed
            const activityFeed = document.getElementById('userActivityFeed');
            if (activityFeed) {
                const activities = systemUsers.slice(0, 5).map(user => ({
                    user: user.fullName,
                    action: 'login',
                    time: user.lastLogin ? formatTime(user.lastLogin) : 'Never',
                    details: `Last login from ${user.authority}`
                }));
                
                activityFeed.innerHTML = activities.map(activity => `
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="small fw-medium">${activity.user}</div>
                            <div class="small text-muted">${activity.details}</div>
                            <div class="small text-muted">${activity.time}</div>
                        </div>
                    </div>
                `).join('');
            }
        }

        function loadAdminUsers() {
            const tableBody = document.getElementById('usersTableBody');
            if (!tableBody) return;
            
            tableBody.innerHTML = systemUsers.map(user => `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-user text-muted"></i>
                            </div>
                            <div>
                                <div class="fw-medium">${user.fullName}</div>
                                <div class="small text-muted">${user.workIdText}</div>
                                <div class="small text-muted">${user.loginEmail}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div class="small">${user.phone}</div>
                            <div class="small text-muted">${user.email}</div>
                            <div class="small text-muted mt-1">${user.authority}</div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div class="fw-medium">${user.role}</div>
                            <span class="status-badge ${getStatusClass(user.status)} mt-1">
                                <i class="${getStatusIcon(user.status)} me-1"></i>
                                ${user.status.toUpperCase()}
                            </span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div class="small">${user.casesHandled} ${t('cases', 'حالات')}</div>
                            <div class="small text-muted">
                                ${user.lastLogin ? formatDate(user.lastLogin) : t('Never logged in', 'لم يسجل الدخول')}
                            </div>
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-outline-primary btn-sm me-1" onclick="editUser(${user.id})" title="${t('Edit User', 'تحرير المستخدم')}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-warning btn-sm me-1" onclick="resetUserPassword(${user.id})" title="${t('Reset Password', 'إعادة تعيين كلمة المرور')}">
                                <i class="fas fa-key"></i>
                            </button>
                            <button class="btn btn-outline-${user.status === 'active' ? 'danger' : 'success'} btn-sm me-1" onclick="toggleUserStatus(${user.id})" title="${user.status === 'active' ? t('Suspend User', 'إيقاف المستخدم') : t('Activate User', 'تفعيل المستخدم')}">
                                <i class="fas fa-${user.status === 'active' ? 'ban' : 'check-circle'}"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="deleteUser(${user.id})" title="${t('Delete User', 'حذف المستخدم')}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function loadAdminSystem() {
            // System control is already loaded in HTML
        }

        function loadAdminLogs() {
            // Logs are already loaded in HTML
        }

        // Modal functions
        function showNewCaseModal() {
            const modal = new bootstrap.Modal(document.getElementById('newCaseModal'));
            modal.show();
        }

        function showUserFormModal(userId = null) {
            const modal = new bootstrap.Modal(document.getElementById('userFormModal'));
            const form = document.getElementById('userForm');
            
            if (userId) {
                const user = systemUsers.find(u => u.id === userId);
                if (user) {
                    document.getElementById('userFormTitle').innerHTML = `
                        <span class="nav-text">Edit User</span>
                        <span class="nav-text-ar d-none">تحرير المستخدم</span>
                    `;
                    document.getElementById('userFullName').value = user.fullName;
                    document.getElementById('userPhone').value = user.phone;
                    document.getElementById('userEmail').value = user.email;
                    document.getElementById('userWorkId').value = user.workIdText;
                    document.getElementById('userAuthority').value = user.authority;
                    document.getElementById('userRole').value = user.role;
                    document.getElementById('userLoginEmail').value = user.loginEmail;
                    form.setAttribute('data-editing', userId);
                }
            } else {
                document.getElementById('userFormTitle').innerHTML = `
                    <span class="nav-text">Add New User</span>
                    <span class="nav-text-ar d-none">إضافة مستخدم جديد</span>
                `;
                form.reset();
                form.removeAttribute('data-editing');
            }
            
            modal.show();
        }

        function showBroadcastModal() {
            const modal = new bootstrap.Modal(document.getElementById('broadcastModal'));
            modal.show();
        }

        // Action functions
        function submitNewCase() {
            const newCaseId = `BZ-2024-${Math.floor(Math.random() * 1000) + 200}`;
            alert(`${t('New case registered successfully!', 'تم تسجيل الحالة الجديدة بنجاح!')}\n\n${t('Case ID:', 'معرف الحالة:')} ${newCaseId}\n${t('The case has been assigned to the appropriate department for processing.', 'تم تعيين الحالة للقسم المناسب للمعالجة.')}`);
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('newCaseModal'));
            modal.hide();
        }

        function submitUserForm() {
            const form = document.getElementById('userForm');
            const password = document.getElementById('userPassword').value;
            const confirmPassword = document.getElementById('userConfirmPassword').value;
            
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }
            
            const userData = {
                fullName: document.getElementById('userFullName').value,
                phone: document.getElementById('userPhone').value,
                email: document.getElementById('userEmail').value,
                workIdText: document.getElementById('userWorkId').value,
                authority: document.getElementById('userAuthority').value,
                role: document.getElementById('userRole').value,
                loginEmail: document.getElementById('userLoginEmail').value
            };
            
            const editingId = form.getAttribute('data-editing');
            
            if (editingId) {
                alert(`${t('User updated successfully!', 'تم تحديث المستخدم بنجاح!')} ${userData.fullName}`);
            } else {
                alert(`${t('User created successfully!', 'تم إنشاء المستخدم بنجاح!')} ${userData.fullName}`);
            }
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('userFormModal'));
            modal.hide();
            
            // Reload users if on admin users view
            if (currentTab === 'administration' && currentAdminView === 'users') {
                loadAdminUsers();
            }
        }

        function sendBroadcast() {
            const message = document.getElementById('broadcastMessage').value;
            const priority = document.getElementById('broadcastPriority').value;
            
            if (!message.trim()) {
                alert('Please enter a broadcast message');
                return;
            }
            
            alert(`${t('Broadcast notification sent to 247 users:', 'تم إرسال إشعار للبث إلى 247 مستخدم:')}\n"${message}"\n\n${t('Priority:', 'الأولوية:')} ${priority.toUpperCase()}`);
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('broadcastModal'));
            modal.hide();
        }

        function generateReport() {
            alert(`${t('Generating comprehensive system report...', 'جاري إنشاء تقرير شامل للنظام...')}\n\n${t('Report will include:', 'سيتضمن التقرير:')}\n• ${t('Case statistics', 'إحصائيات الحالات')}\n• ${t('Department performance', 'أداء الأقسام')}\n• ${t('User activity', 'نشاط المستخدمين')}\n• ${t('System metrics', 'مقاييس النظام')}`);
        }

        // function sendAlert() {
        //     const message = prompt(t('Enter alert message:', 'أدخل رسالة التنبيه:'));
        //     if (message && message.trim()) {
        //         alert(`${t('Alert sent to all users:', 'تم إرسال التنبيه لجميع المستخدمين:')}\n\n"${message}"\n\n${t('Recipients:', 'المستلمون:')} 247 ${t('users', 'مستخدم')}`);
        //     }
        // }
        
    // function sendAlert() {
    //     const message = prompt('Enter alert message / أدخل رسالة التنبيه:');
    //     if (message && message.trim()) {
    //         fetch("{{ route('send.alert') }}", {
    //             method: "POST",
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //             },
    //             body: JSON.stringify({
    //                 notification_message: message.trim()
    //             })
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 alert('✅ Alert saved successfully!');
    //             } else {
    //                 alert('❌ Failed to save alert.');
    //             }
    //         })
    //         .catch(error => {
    //             console.error(error);
    //             alert('❌ An error occurred.');
    //         });
    //     }
    // }

        function initiateBackup() {
            const confirmed = confirm(t('Are you sure you want to initiate a manual backup? This process may take 10-15 minutes.', 'هل أنت متأكد من أنك تريد بدء نسخة احتياطية يدوية؟ قد تستغرق هذه العملية 10-15 دقيقة.'));
            if (confirmed) {
                alert(t('Manual backup initiated. You will be notified when the process is complete.', 'تم بدء النسخة الاحتياطية اليدوية. سيتم إشعارك عند اكتمال العملية.'));
            }
        }

        function exportDatabase() {
            const confirmed = confirm(t('Are you sure you want to export the entire system database? This will create a large file.', 'هل أنت متأكد من أنك تريد تصدير قاعدة بيانات النظام بالكامل؟ سيؤدي هذا إلى إنشاء ملف كبير.'));
            if (confirmed) {
                const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
                alert(`${t('Database export initiated. File:', 'تم بدء تصدير قاعدة البيانات. الملف:')} burzakh_db_export_${timestamp}.sql\n${t('Estimated size:', 'الحجم المقدر:')} ~2.5 GB`);
            }
        }

        // User management functions
        function editUser(userId) {
            showUserFormModal(userId);
        }

        function resetUserPassword(userId) {
            const user = systemUsers.find(u => u.id === userId);
            if (user) {
                const confirmed = confirm(`${t('Are you sure you want to reset the password for', 'هل أنت متأكد من أنك تريد إعادة تعيين كلمة المرور لـ')} ${user.fullName}?`);
                if (confirmed) {
                    alert(`${t('Password reset email sent to', 'تم إرسال بريد إلكتروني لإعادة تعيين كلمة المرور إلى')} ${user.fullName} (${user.loginEmail})`);
                }
            }
        }

        function toggleUserStatus(userId) {
            const user = systemUsers.find(u => u.id === userId);
            if (user) {
                const newStatus = user.status === 'active' ? 'suspended' : 'active';
                const action = newStatus === 'suspended' ? 'suspend' : 'activate';
                const confirmed = confirm(`${t('Are you sure you want to', 'هل أنت متأكد من أنك تريد')} ${action} ${user.fullName}?`);
                if (confirmed) {
                    user.status = newStatus;
                    alert(`${user.fullName} ${t('has been', 'تم')} ${newStatus} ${t('successfully.', 'بنجاح.')}`);
                    loadAdminUsers();
                }
            }
        }

        function deleteUser(userId) {
            const user = systemUsers.find(u => u.id === userId);
            if (user) {
                const confirmed = confirm(`${t('Are you sure you want to delete', 'هل أنت متأكد من أنك تريد حذف')} ${user.fullName}? ${t('This action cannot be undone.', 'هذا الإجراء لا يمكن التراجع عنه.')}`);
                if (confirmed) {
                    const index = systemUsers.findIndex(u => u.id === userId);
                    if (index > -1) {
                        systemUsers.splice(index, 1);
                        alert(`${user.fullName} ${t('has been deleted successfully.', 'تم حذفه بنجاح.')}`);
                        loadAdminUsers();
                        loadAdminOverview(); // Update counts
                    }
                }
            }
        }

        // Case management functions
        function selectCase(caseId) {
            const caseItem = cases.find(c => c.id === caseId);
            if (caseItem) {
                alert(`${t('Selected case:', 'الحالة المحددة:')} ${caseItem.deceasedName} (${caseItem.id})\n${t('Status:', 'الحالة:')} ${caseItem.status}\n${t('Progress:', 'التقدم:')} ${caseItem.progress}%`);
            }
        }

        function viewCase(caseId) {
            alert(`${t('Viewing case details for:', 'عرض تفاصيل الحالة لـ:')} ${caseId}`);
        }

        function editCase(caseId) {
            alert(`${t('Editing case:', 'تحرير الحالة:')} ${caseId}`);
        }

        function moreActions(caseId) {
            alert(`${t('More actions for case:', 'المزيد من الإجراءات للحالة:')} ${caseId}`);
        }

        // Initialize the application when DOM is loaded
        document.addEventListener('DOMContentLoaded', init);