@extends('layouts.back-end.app')

@section('title', translate('subscriber_list'))

@section('content')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.min.css" rel="stylesheet">
<div class="page-wrapper">
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <thead class="table-light">
                            <tr>
                                <th>Sr#</th>
                                @if($users->is_student==1)
                                    <th>University Card or Fee Payment Slip</th>
                                @elseif($users->is_health_care_worker==1)
                                    <th>Passport or National ID</th>
                                @elseif($users->is_recruiter==1)
                                    <th>Passport or National ID</th>
                                    <th>Hospital Logo</th>
                                @elseif($users->is_vendor)
                                    <th>Trade License Number</th>
                                    <th>National ID Number</th>
                                @else
                                    <th>N/A</th>
                                @endif
                                <td>Accept / Reject</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-2">
                                            <h6 class="mb-0 font-14">{{$i++}}</h6>
                                        </div>
                                    </div>
                                </td>
                                @if($users->is_student==1)
                                    <td><img src="{{$users->university_card_or_fee_payment}}" alt="" width="50%" height="150px" class="clickable-image"></td>
                                @elseif($users->is_health_care_worker==1)
                                    <td><img src="{{$users->passport_or_national_id}}" alt="" width="50%" height="150px" class="clickable-image"></td>
                                @elseif($users->is_recruiter==1)
                                    <td><img src="{{$users->passport_or_national_id}}" alt="" width="50%" height="150px" class="clickable-image"></td>
                                    <td><img src="{{$users->hospital_logo}}" alt="" width="50%" height="150px" class="clickable-image"></td>
                                @elseif($users->is_vendor)
                                    <td>{{$users->trade_license_number}}</td>
                                    <td>{{$users->national_id_number}}</td>
                                @else
                                    <td>N/A</td>
                                @endif
                                <td>
                                    @if($users->documents_verified==0||$users->documents_verified==2)
                                    <a href="{{ route('admin.customer.verify-documents.accept', $users->id) }}" class="btn btn-secondary">Accept</a>
                                    <a href="{{ route('admin.customer.verify-documents.reject', $users->id) }}" class="btn btn-danger">Reject</a>
                                    @else
                                    Verification Completed
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.min.js"></script>

    <script>
        document.querySelectorAll('.clickable-image').forEach(function(image) {
            image.addEventListener('click', function() {
                const imgSrc = this.src;
                Swal.fire({
                    imageUrl: imgSrc,
                    imageWidth: 400,
                    imageHeight: 400,
                    imageAlt: 'Image',
                    showConfirmButton: false,
                    showCloseButton: false, 
                    allowOutsideClick: true,
                    background: 'transparent',
            
                });
            });
        });
    </script>
@endpush

