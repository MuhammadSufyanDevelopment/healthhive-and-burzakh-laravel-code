<!-- New Case Modal -->
<div class="modal fade" id="newCaseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="nav-text">Register New Case</span>
                        <span class="nav-text-ar d-none">تسجيل حالة جديدة</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.registerCase')}}" method="POST">
                        @csrf
                        <!-- Deceased Information -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">
                                <span class="nav-text">Deceased Information</span>
                                <span class="nav-text-ar d-none">معلومات المتوفى</span>
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <span class="nav-text">Full Name</span>
                                        <span class="nav-text-ar d-none">الاسم الكامل</span>
                                        *
                                    </label>
                                    <input type="text" name="name_of_deceased" class="form-control" required placeholder="Enter full name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <span class="nav-text">Date of Deceased</span>
                                        <span class="nav-text-ar d-none">تاريخ الوفاة</span>
                                        *
                                    </label>
                                    <!-- <input type="number" class="form-control" required min="0" max="150" name="age"> -->
                                    <input type="date" name="date_of_death" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <span class="nav-text">Gender</span>
                                        <span class="nav-text-ar d-none">الجنس</span>
                                        *
                                    </label>
                                    <select class="form-select" name="gender" required>
                                        <option value="">Select gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <span class="nav-text">Location</span>
                                        <span class="nav-text-ar d-none">الموقع</span>
                                        *
                                    </label>
                                    <input type="text" name="location" class="form-control" required placeholder="Enter Location">
                                    <!-- <select class="form-select" required>
                                        <option value="">Select nationality</option>
                                        <option value="UAE">UAE</option>
                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                        <option value="Egypt">Egypt</option>
                                        <option value="India">India</option>
                                        <option value="Pakistan">Pakistan</option>
                                        <option value="Philippines">Philippines</option>
                                        <option value="Other">Other</option>
                                    </select> -->
                                </div>
                                <!-- <div class="col-md-6">
                                    <label class="form-label">
                                        <span class="nav-text">Sect</span>
                                        <span class="nav-text-ar d-none">الطائفة</span>
                                        *
                                    </label>
                                    <input type="text" name="sect" class="form-control" required>
                                </div> -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <span class="nav-text">Religion</span>
                                        <span class="nav-text-ar d-none">الديانة</span>
                                        *
                                    </label>
                                    <select class="form-select" required>
                                        <option value="">Select religion</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Christianity">Christianity</option>
                                        <option value="Hinduism">Hinduism</option>
                                        <option value="Buddhism">Buddhism</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Family Contact Information -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">
                                <span class="nav-text">Family Contact Information</span>
                                <span class="nav-text-ar d-none">معلومات اتصال العائلة</span>
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <span class="nav-text">Contact Person Name</span>
                                        <span class="nav-text-ar d-none">اسم الشخص المخول بالاتصال</span>
                                        *
                                    </label>
                                    <input type="text" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <span class="nav-text">Relationship to Deceased</span>
                                        <span class="nav-text-ar d-none">العلاقة بالمتوفى</span>
                                        *
                                    </label>
                                    <select class="form-select" required>
                                        <option value="">Select relationship</option>
                                        <option value="Spouse">Spouse</option>
                                        <option value="Son">Son</option>
                                        <option value="Daughter">Daughter</option>
                                        <option value="Father">Father</option>
                                        <option value="Mother">Mother</option>
                                        <option value="Brother">Brother</option>
                                        <option value="Sister">Sister</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <span class="nav-text">Phone Number</span>
                                        <span class="nav-text-ar d-none">رقم الهاتف</span>
                                        *
                                    </label>
                                    <input type="tel" class="form-control" required placeholder="+971 XX XXX XXXX">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <span class="nav-text">Email Address</span>
                                        <span class="nav-text-ar d-none">عنوان البريد الإلكتروني</span>
                                    </label>
                                    <input type="email" class="form-control" placeholder="contact@example.com">
                                </div>
                            </div>
                        </div>

                        <!-- Case Priority -->
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="nav-text">Case Priority</span>
                                <span class="nav-text-ar d-none">أولوية الحالة</span>
                                *
                            </label>
                            <select class="form-select" required>
                                <option value="">Select priority</option>
                                <option value="normal">Normal</option>
                                <option value="high">High Priority</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="nav-text">Additional Notes</span>
                                <span class="nav-text-ar d-none">ملاحظات إضافية</span>
                            </label>
                            <textarea class="form-control" rows="3" placeholder="Enter any additional information..."></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <span class="nav-text">Cancel</span>
                                <span class="nav-text-ar d-none">إلغاء</span>
                            </button>
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-plus me-2"></i>
                                <span class="nav-text">Register Case</span>
                                <span class="nav-text-ar d-none">تسجيل الحالة</span>
                            </button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>

    <!-- User Form Modal -->
    <div class="modal fade" id="userFormModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userFormTitle">
                        <span class="nav-text">Add New User</span>
                        <span class="nav-text-ar d-none">إضافة مستخدم جديد</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <span class="nav-text">Full Name</span>
                                    <span class="nav-text-ar d-none">الاسم الكامل</span>
                                    *
                                </label>
                                <input type="text" class="form-control" id="userFullName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <span class="nav-text">Phone Number</span>
                                    <span class="nav-text-ar d-none">رقم الهاتف</span>
                                    *
                                </label>
                                <input type="tel" class="form-control" id="userPhone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <span class="nav-text">Email Address</span>
                                    <span class="nav-text-ar d-none">عنوان البريد الإلكتروني</span>
                                    *
                                </label>
                                <input type="email" class="form-control" id="userEmail" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <span class="nav-text">Work ID</span>
                                    <span class="nav-text-ar d-none">معرف العمل</span>
                                    *
                                </label>
                                <input type="text" class="form-control" id="userWorkId" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <span class="nav-text">Authority</span>
                                    <span class="nav-text-ar d-none">السلطة</span>
                                    *
                                </label>
                                <select class="form-select" id="userAuthority" required>
                                    <option value="Dubai Police">Dubai Police</option>
                                    <option value="Dubai Municipality">Dubai Municipality</option>
                                    <option value="Road Transport Authority">Road Transport Authority</option>
                                    <option value="Community Development Authority">Community Development Authority</option>
                                    <option value="Licensed Morticians">Licensed Morticians</option>
                                    <option value="Ambulance Staff">Ambulance Staff</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <span class="nav-text">Role</span>
                                    <span class="nav-text-ar d-none">الدور</span>
                                    *
                                </label>
                                <select class="form-select" id="userRole" required>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Supervisor">Supervisor</option>
                                    <option value="Operator">Operator</option>
                                    <option value="Viewer">Viewer</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">
                                    <span class="nav-text">Login Email</span>
                                    <span class="nav-text-ar d-none">بريد إلكتروني لتسجيل الدخول</span>
                                    *
                                </label>
                                <input type="email" class="form-control" id="userLoginEmail" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <span class="nav-text">Password</span>
                                    <span class="nav-text-ar d-none">كلمة المرور</span>
                                    *
                                </label>
                                <input type="password" class="form-control" id="userPassword" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <span class="nav-text">Confirm Password</span>
                                    <span class="nav-text-ar d-none">تأكيد كلمة المرور</span>
                                    *
                                </label>
                                <input type="password" class="form-control" id="userConfirmPassword" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <span class="nav-text">Cancel</span>
                        <span class="nav-text-ar d-none">إلغاء</span>
                    </button>
                    <button type="button" class="btn btn-primary-custom" onclick="submitUserForm()">
                        <span class="nav-text">Create User</span>
                        <span class="nav-text-ar d-none">إنشاء مستخدم</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Broadcast Modal -->
    <div class="modal fade" id="broadcastModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="nav-text">Send Broadcast Message</span>
                        <span class="nav-text-ar d-none">إرسال رسالة إذاعية</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            <span class="nav-text">Message</span>
                            <span class="nav-text-ar d-none">الرسالة</span>
                        </label>
                        <textarea class="form-control" id="broadcastMessage" rows="4" placeholder="Enter your message..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <span class="nav-text">Priority</span>
                            <span class="nav-text-ar d-none">الأولوية</span>
                        </label>
                        <select class="form-select" id="broadcastPriority">
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <span class="nav-text">Cancel</span>
                        <span class="nav-text-ar d-none">إلغاء</span>
                    </button>
                    <button type="button" class="btn btn-primary-custom" onclick="sendBroadcast()">
                        <span class="nav-text">Send Broadcast</span>
                        <span class="nav-text-ar d-none">إرسال الرسالة</span>
                    </button>
                </div>
            </div>
        </div>
    </div>