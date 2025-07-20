<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BurzakhMember;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\BurzakhMemberDocumentSubmission;
use App\Models\CdaSubmission;
use App\Models\RtaSubmission;
use App\Models\BurzakhUserSubmissionToSupervisor;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordOtp;
use App\Models\Ambulance;
use App\Models\BurzakhNotification;
use App\Models\BurzakhUserSubmissionToMancipality;
use App\Models\CemeteryCase;
use App\Models\DispatchAmbulance;
use App\Models\Mortician;
use App\Models\Notification;
use App\Models\RecentActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\SupportMessage;
use App\Models\VideoMeetTiming;
use App\Models\VisitorAlert;

require_once base_path('vendor/twilio/twilio_autoload.php');

use Twilio\Rest\Client;


class BurzakhAppController extends Controller
{
    //
    protected $base_url="https://healthhive.me";
    public function register(Request $request)
    {

        // $twilio = new Client('AC98d333ba5c18895c4e6eb1513c8eba82', 'b79b24c66ac450516ce32735f756752c');

        // $twilio->messages->create(
        //     '+923224124179',
        //     [
        //         'from' => '+1 857 688 0692',
        //         'body' => 'Hello from Twilio!'
        //     ]
        // );
        // die();
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|unique:burzakh_members,email',
            'phone_number'   => 'required|string|max:20',
            'password'       => 'required|string|confirmed',
            'passport_copy'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'marsoom'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'device_token'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $passportPath = $request->file('passport_copy')->store('documents/passports', 'public');
        $marsoomPath = null;
        if ($request->hasFile('marsoom')) {
            $marsoomPath = $request->file('marsoom')->store('documents/marsoom', 'public');
        }

        $member = BurzakhMember::create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'email'          => $request->email,
            'phone_number'   => $request->phone_number,
            'password'       => Hash::make($request->password),
            'passport_copy'  => $this->base_url . "/storage/app/public/". $passportPath,
            'marsoom'        => $this->base_url . "/storage/app/public/". $marsoomPath,
            'device_token'   => $request->device_token,
        ]);

        return response()->json(['message' => 'Registration successful', 'user' => $member], 201);
    }

    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'first_name'     => 'required|string|max:255',
    //         'last_name'      => 'required|string|max:255',
    //         'email'          => 'required|email|unique:burzakh_members,email',
    //         'phone_number'   => 'required|string|max:20',
    //         'password'       => 'required|string|min:6|confirmed',
    //         'passport_copy'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //         'marsoom'        => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //         'uae_pass'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $otp = rand(100000, 999999);
    //     $phone = $request->phone_number;

    //     // Store files temporarily
    //     $passportPath = $request->file('passport_copy')->store('temp/passports');
    //     $marsoomPath  = $request->file('marsoom')->store('temp/marsoom');
    //     $uaePassPath  = $request->file('uae_pass')->store('temp/uae_pass');

    //     // Save all registration data temporarily
    //     $data = [
    //         'first_name'    => $request->first_name,
    //         'last_name'     => $request->last_name,
    //         'email'         => $request->email,
    //         'phone_number'  => $request->phone_number,
    //         'password'      => bcrypt($request->password), // store hashed already
    //         'passport_copy' => $passportPath,
    //         'marsoom'       => $marsoomPath,
    //         'uae_pass'      => $uaePassPath,
    //     ];

    //     Cache::put('pending_user_' . $phone, $data, now()->addMinutes(10));
    //     Cache::put('phone_verification_' . $phone, $otp, now()->addMinutes(5));

    //     // Send OTP
    //     $twilio = new Client('AC98d333ba5c18895c4e6eb1513c8eba82', 'b79b24c66ac450516ce32735f756752c');
    //     $twilio->messages->create($phone, [
    //         'from' => '+1 857 688 0692',
    //         'body' => "Your verification code is: $otp"
    //     ]);

    //     return response()->json([
    //         'message' => 'OTP sent to your phone. Please verify.',
    //         'phone' => $phone
    //     ]);
    // }


    // public function verifyPhoneAndCompleteRegistration(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'phone_number' => 'required|string',
    //         'otp'          => 'required|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $phone = $request->phone_number;
    //     $cachedOtp = Cache::get('phone_verification_' . $phone);
    //     $userData = Cache::get('pending_user_' . $phone);

    //     if (!$cachedOtp || $cachedOtp != $request->otp) {
    //         return response()->json(['message' => 'Invalid or expired OTP'], 403);
    //     }

    //     if (!$userData) {
    //         return response()->json(['message' => 'No registration data found'], 404);
    //     }

    //     Move files from temp to public
    //     $passportPath = $request->file('passport_copy')->store('documents/passports', 'public');
    //     $marsoomPath  = $request->file('marsoom')->store('documents/marsoom', 'public');
    //     $uaePassPath  = $request->file('uae_pass')->store('documents/uae_pass', 'public');

    //     // Create user
    //     $member = BurzakhMember::create([
    //     'first_name'     => $request->first_name,
    //     'last_name'      => $request->last_name,
    //     'email'          => $request->email,
    //     'phone_number'   => $request->phone_number,
    //     'password'       => Hash::make($request->password),
    //     'passport_copy'  => asset("storage/" . $passportPath),
    //     'marsoom'        => asset("storage/" . $marsoomPath),
    //     'uae_pass'       => asset("storage/" . $uaePassPath),
    // ]);

    //     // Clean up
    //     Cache::forget('pending_user_' . $phone);
    //     Cache::forget('phone_verification_' . $phone);

    //     return response()->json(['message' => 'Registration successful', 'user' => $member], 201);
    // }



    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:burzakh_members,email',
        ]);

        $otp = rand(1000, 9999);

        DB::table('burzakh_password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['otp' => $otp, 'created_at' => now()]
        );

        Mail::to($request->email)->send(new ForgotPasswordOtp($otp));

        return response()->json(['message' => 'OTP sent to your email.'], 200);
    }

    // public function resetPassword(Request $request)
    // {
    //     // echo"hrllo";die();
    //     $request->validate([
    //         'email'                 => 'required|email|exists:burzakh_members,email',
    //         'otp'                   => 'required|digits:4',
    //         'password'              => 'required|string|min:6|confirmed',
    //     ]);
    //     // die('hello')

    //     $otpRecord = DB::table('burzakh_password_resets')
    //         ->where('email', $request->email)
    //         ->where('otp', $request->otp)
    //         ->first();

    //     if (!$otpRecord || Carbon::parse($otpRecord->created_at)->addMinutes(10)->isPast()) {
    //         return response()->json(['message' => 'Invalid or expired OTP'], 400);
    //     }

    //     $user = BurzakhMember::where('email', $request->email)->first();
    //     $user->password = Hash::make($request->password);
    //     $user->save();

    //     DB::table('burzakh_password_resets')->where('email', $request->email)->delete();

    //     return response()->json(['message' => 'Password successfully updated.'], 200);
    // }


//     public function resetPassword(Request $request)
// {
//     try {
//         $request->validate([
//             'email'    => 'required|email|exists:burzakh_members,email',
//             'otp'      => 'required|digits:4',
//             'password' => 'required|string|min:6|confirmed',
//         ]);

//         $otpRecord = DB::table('burzakh_password_resets')
//             ->where('email', $request->email)
//             ->where('otp', $request->otp)
//             ->first();

//         if (!$otpRecord || Carbon::parse($otpRecord->created_at)->addMinutes(10)->isPast()) {
//             return response()->json(['message' => 'Invalid or expired OTP'], 400);
//         }

//         $user = BurzakhMember::where('email', $request->email)->first();

//         if (!$user) {
//             return response()->json(['message' => 'User not found.'], 404);
//         }

//         $user->password = Hash::make($request->password);
//         $user->save();

//         DB::table('burzakh_password_resets')->where('email', $request->email)->delete();

//         return response()->json(['message' => 'Password successfully updated.'], 200);

//     } catch (ValidationException $e) {
//         return response()->json([
//             'status' => 'validation_error',
//             'errors' => $e->errors(),
//         ], 422);
//     } catch (\Exception $e) {
//         return response()->json([
//             'status' => 'error',
//             'message' => 'An unexpected error occurred.',
//             'details' => $e->getMessage(), // Optional: remove in production
//         ], 500);
//     }
// }

public function verifyOtp(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email|exists:burzakh_members,email',
            'otp'   => 'required|digits:4',
        ]);

        $otpRecord = DB::table('burzakh_password_resets')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$otpRecord || Carbon::parse($otpRecord->created_at)->addMinutes(10)->isPast()) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        return response()->json(['message' => 'OTP verified successfully.'], 200);

    } catch (ValidationException $e) {
        return response()->json([
            'status' => 'validation_error',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'An unexpected error occurred.',
            'details' => $e->getMessage(),
        ], 500);
    }
}


public function setNewPassword(Request $request)
{
    try {
        $request->validate([
            'email'    => 'required|email|exists:burzakh_members,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = BurzakhMember::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Clean up OTP record after successful password reset
        DB::table('burzakh_password_resets')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password successfully updated.'], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }






    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'login'    => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     // Find user by email or phone
    //     $member = BurzakhMember::where('email', $request->login)
    //         ->orWhere('phone_number', $request->login)
    //         ->first();

    //     if (!$member || !Hash::check($request->password, $member->password)) {
    //         return response()->json(['message' => 'Invalid login credentials'], 401);
    //     }

    //     return response()->json([
    //         'message' => 'Login successful',
    //         'user'    => $member
    //     ]);
    // }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login'        => 'required|string',
            'password'     => 'required|string',
            'device_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find user by email or phone
        $member = BurzakhMember::where('email', $request->login)
            ->orWhere('phone_number', $request->login)
            ->first();

        if (!$member || !Hash::check($request->password, $member->password)) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        // Update device_token if provided
        if ($request->filled('device_token')) {
            $member->device_token = $request->device_token;
            $member->save();
        }

        return response()->json([
            'message' => 'Login successful',
            'user'    => $member
        ]);
    }



    public function userDocuemntSubmission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'death_notification_file'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'hospital_certificate'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'passport_or_emirate_id_front'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'passport_or_emirate_id_back'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            // 'additional_document_upload_user'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'user_id'                       => 'required',
            'resting_place'                 => 'required',
            'name_of_deceased'              => 'required',
            'date_of_death'                 => 'required',
            'location'                      => 'required',
            'gender'                       => 'required',
            'age'                          => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Store files
        $deathNotificationFilePath  = $request->file('death_notification_file')->store('documents/death_notification_files', 'public');
        $hospitalCertificatePath    = $request->file('hospital_certificate')->store('documents/hospital_certificates', 'public');
        $passportOrEmirateIdFront   = $request->file('passport_or_emirate_id_front')->store('documents/passport_or_emirate_ids_front', 'public');
        $passportOrEmirateIdBack    = $request->file('passport_or_emirate_id_back')->store('documents/passport_or_emirate_ids_back', 'public');
        // $addtionalDocument    = $request->file('additional_document_upload_user')->store('documents/additional_document_upload_user', 'public');
        $user_id                    = $request->user_id;     
        $resting_place              = $request->resting_place;     
        $name_of_deceased           = $request->name_of_deceased;     
        $date_of_death              = $request->date_of_death;     
        $location                   = $request->location;     

        $data = BurzakhMemberDocumentSubmission::create([
            'death_notification_file'      => $this->base_url . "/storage/app/public/" . $deathNotificationFilePath,
            'hospital_certificate'         => $this->base_url . "/storage/app/public/" . $hospitalCertificatePath,
            'passport_or_emirate_id_front' => $this->base_url . "/storage/app/public/" . $passportOrEmirateIdFront,
            'passport_or_emirate_id_back'  => $this->base_url . "/storage/app/public/" . $passportOrEmirateIdBack,
            // 'additional_document_upload_user'  => $this->base_url . "/storage/app/public/" . $addtionalDocument,
            'user_id'                      => $user_id,
            'resting_place'                => $resting_place,
            'name_of_deceased'             => $name_of_deceased,
            'date_of_death'                => $date_of_death,
            'location'                     => $location,
            'ratio'                        => 0.2,
            'gender'                       => $request->gender,
            'age'                          => $request->age,
        ]);

        $data = RecentActivity::create([
            'user_id'        => $request->user_id,
            'activity_name'  => "Document Submission",
            'status'         => "Pending Approval"
        ]);

        return response()->json(['message' => 'Submission successful', 'data' => $data], 201);
    }

    public function editAddtionalUploadDocumentUser(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'additional_document_upload_user'            => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'user_id'            => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Store files
        $addtionalDocument    = $request->file('additional_document_upload_user')->store('documents/additional_document_upload_user', 'public');
        
        $data = BurzakhMemberDocumentSubmission::where('id' , $id)->update([
            'additional_document_upload_user'  => $this->base_url . "/storage/app/public/" . $addtionalDocument,
        ]);

        $data = RecentActivity::create([
            'user_id'        => $request->user_id,
            'activity_name'  => "Document Submission",
            'status'         => "Pending Approval"
        ]);

        return response()->json(['message' => 'Submission successful', 'data' => $data], 201);
    }

    public function CDASubmission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mourning_start_date'     => 'required',
            'mourning_end_date'     => 'required',
            // 'time'                    => 'required',
            'location_of_tent'        => 'required',
            'user_id'                 => 'required',
            'case_name'               => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $submission = CdaSubmission::create([
            'mourning_start_date'     => $request->mourning_start_date,
            'mourning_end_date'     => $request->mourning_end_date,
            // 'time'                    => $request->time,
            'location_of_tent'        => $request->location_of_tent,
            'user_id'                 => $request->user_id,
            'case_name'               => $request->case_name,
        ]);
        $data = RecentActivity::create([
            'user_id'        => $request->user_id,
            'activity_name'  => "CDA Submission",
            'status'         => "Pending Request"
        ]);

        return response()->json(['message' => 'CDA Submission successful', 'data' => $submission], 201);
    }

    public function cancelCDARequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:burzakh_members,id',
            'submission_id' => 'required|exists:cda_submissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user_id = $request->user_id;
        $submission_id = $request->submission_id;

        $submission = CdaSubmission::where('id', $submission_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$submission) {
            return response()->json([
                'message' => 'Submission not found or does not belong to the user.'
            ], 404);
        }

        $submission->status = 'Cancelled';
        $submission->save();

        $activity = RecentActivity::create([
            'user_id' => $user_id,
            'activity_name' => 'CDA Request Cancelled',
            'status' => 'Request Cancelled'
        ]);

        return response()->json([
            'message' => 'CDA Submission cancelled successfully',
            'submission' => $submission,
            'activity_logged' => $activity
        ], 200);
    }

    public function listPendingCDARequests($user_id)
    {
        $user = BurzakhMember::find($user_id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $pendingRequest = CdaSubmission::where('user_id', $user_id)
            // ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$pendingRequest) {
            return response()->json([
                'message' => 'No pending CDA requests found',
                'data' => []
            ], 200);
        }

        return response()->json([
            'message' => 'Pending CDA request retrieved successfully',
            'data' => $pendingRequest
        ], 200);
    }


    public function getCasesName($user_id){
        $getCaseName = BurzakhMemberDocumentSubmission::where('user_id',$user_id)->get('name_of_deceased');
        return response()->json(['message' => 'cases retrieved successful', 'data' => $getCaseName], 201);
    }

    public function RTASubmission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mourning_start_date'     => 'required',
            'time'                    => 'required',
            'location_of_house'       => 'required',
            'signs_required'          => 'required',
            'custom_text_for_sign'    => 'required',
            'user_id'                 => 'required',
            'case_name'               => 'required',
            'mourning_end_date'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $submission = RtaSubmission::create([
            'mourning_start_date'     => $request->mourning_start_date,
            'time'                    => $request->time,
            'location_of_house'        => $request->location_of_house,
            'signs_required'          => $request->signs_required,
            'custom_text_for_sign'    => $request->custom_text_for_sign,
            'user_id'                 => $request->user_id,
            'case_name'               => $request->case_name,
            'mourning_end_date'       => $request->mourning_end_date,
        ]);
        $data = RecentActivity::create([
            'user_id'        => $request->user_id,
            'activity_name'  => "RTA Submission",
            'status'         => "Pending Request"
        ]);

        return response()->json(['message' => 'RTA Submission successful', 'data' => $submission], 201);
    }


    public function cancelRTARequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:burzakh_members,id',
            'submission_id' => 'required|exists:rta_submissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user_id = $request->user_id;
        $submission_id = $request->submission_id;

        $submission = RtaSubmission::where('id', $submission_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$submission) {
            return response()->json([
                'message' => 'Submission not found or does not belong to the user.'
            ], 404);
        }

        $submission->status = 'Cancelled';
        $submission->save();

        $activity = RecentActivity::create([
            'user_id' => $user_id,
            'activity_name' => 'RTA Request Cancelled',
            'status' => 'Request Cancelled'
        ]);

        return response()->json([
            'message' => 'RTA Submission cancelled successfully',
            'submission' => $submission,
            'activity_logged' => $activity
        ], 200);
    }

    public function listPendingRTARequests($user_id)
    {
        $user = BurzakhMember::find($user_id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $latestRequest = RtaSubmission::where('user_id', $user_id)
            // ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->first();


        if (!$latestRequest) {
            return response()->json([
                'message' => 'No pending RTA requests found',
                'data' => []
            ], 200);
        }
        return response()->json([
            'message' => 'Pending RTA requests retrieved successfully',
            'data' => $latestRequest
        ], 200);
    }


    public function GetUserCases($id)
    {
        $submissions = BurzakhMemberDocumentSubmission::where('user_id',$id)->orderBy('id', 'asc')->get();

        // Add case names at runtime
        foreach ($submissions as $index => $submission) {
            $submission->case_name = ($index + 1);
        }

        return response()->json([
            'message' => 'Submissions retrieved successfully',
            'data' => $submissions
        ], 200);
    }

    public function GetSingleCase($userId, $caseId)
    {
        $submission = BurzakhMemberDocumentSubmission::where('id', $caseId)
            ->where('user_id', $userId)
            ->first();

        if (!$submission) {
            return response()->json([
                'message' => 'Case not found',
            ], 404);
        }

        // Get all cases for this user
        $submissions = BurzakhMemberDocumentSubmission::where('user_id', $userId)
            ->orderBy('id', 'asc')
            ->get();

        // Find the index of this case
        $caseIndex = $submissions->search(function ($item) use ($caseId) {
            return $item->id == $caseId;
        });

        $submission->case_name = ($caseIndex + 1);

        return response()->json([
            'message' => 'Case details retrieved successfully',
            'data' => $submission,
        ], 200);
    }


    public function sendRequestToMancipality(Request $request){
        $validator = Validator::make($request->all(), [
            'burial_place'                   => 'required',
            'burial_timing'                  => 'nullable',
            'preferred_cemetery'             => 'nullable',
            'user_id'                        => 'required',
            'case_name'                      => 'required',
            'religion'                       => 'required',
            'sect'                           => 'required',
            'special_request'                => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = BurzakhUserSubmissionToMancipality::create([
            'burial_place'               => $request->burial_place,
            'burial_timing'              => $request->burial_timing,
            'preferred_cemetery'         => $request->preferred_cemetery,
            'user_id'                    => $request->user_id,
            'case_name'                  => $request->case_name,
            'religion'                   => $request->religion,
            'sect'                       => $request->sect,
            'special_request'            => $request->special_request,
        ]);
        $activity = RecentActivity::create([
            'user_id'        => $request->user_id,
            'activity_name'  => "Dubai Mancipality Request Submission",
            'status'         => "Pending Approval"
        ]);
        $updateSubmissionStatus=BurzakhMemberDocumentSubmission::where('user_id',$request->user_id)->where('name_of_deceased',$request->case_name)->update(['burial_submission_status'=>'Submitted']);

        return response()->json(['message' => 'Submission successful to Mancipality', 'data' => $data], 201);
    }

    public function userSubmissionToGraveSupervisor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'burial_timing'                       => 'required',
            'preferred_cemetery'                  => 'required',
            'loved_one_city'                      => 'required',
            'police_clearence_certificate'        => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'user_id'                             => 'required',
            'case_id'                             => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $policaClearanceCertificatePath    = $request->file('police_clearence_certificate')->store('documents/police_clearence_certificate', 'public');

        $data = BurzakhUserSubmissionToSupervisor::create([
            'burial_timing'                      => $request->burial_timing,
            'preferred_commentary'               => $request->preferred_cemetery,
            'loved_one_city'                     => $request->loved_one_city,
            'police_clearence_certificate'       => $this->base_url . "/storage/app/public/" . $policaClearanceCertificatePath,
            'user_id'                            => $request->user_id,
            'case_id'                            => $request->case_id,
        ]);
        $data = RecentActivity::create([
            'user_id'        => $request->user_id,
            'activity_name'  => "Burial Submission",
            'status'         => "Pending Approval"
        ]);

        return response()->json(['message' => 'Submission successful to Grave Supervisor', 'data' => $data], 201);
    }


    public function graveyardDocumentsStatus($id)
    {
        $submissions = BurzakhUserSubmissionToSupervisor::where('case_id', $id)
            ->orderBy('id', 'desc')
            ->limit(1)
            ->get(['passport_status', 'death_notification_status', 'hospital_certificate_status', 'police_clearence_certificate_status']);

        return response()->json([
            'message' => 'Statuses retrieved successfully',
            'data' => $submissions
        ], 200);
    }

    // public function userRecentActivities($id)
    // {
    //     $activities = RecentActivity::where('user_id', $id)
    //         ->orderBy('id', 'desc')
    //         ->limit(3)
    //         ->get(['user_id', 'activity_name', 'status' ,'created_at']);

    //     $formattedActivities = $activities->map(function ($activity) {
    //         return [
    //             'user_id' => $activity->user_id,
    //             'activity_name' => $activity->activity_name,
    //             'status' => $activity->status,
    //             'time_ago' => $activity->created_at->diffForHumans(),
    //         ];
    //     });

    //     return response()->json([
    //         'message' => 'Activities retrieved successfully',
    //         'data' => $formattedActivities
    //     ], 200);
    // }

    private function getActivityTranslations($activityName)
    {
        $translations = [
            'CDA Submission' => [
                'en' => 'CDA Submission',
                'ar' => 'تقديم CDA',
                'ur' => 'سی ڈی اے جمع کروا دیا',
                'ml' => 'CDA സമർപ്പണം',
                'ru' => 'Подача CDA',
                'zh' => '提交 CDA'
            ],
            'CDA Request Cancelled' => [
                'en' => 'CDA Request Cancelled',
                'ar' => 'تم إلغاء طلب CDA',
                'ur' => 'سی ڈی اے درخواست منسوخ کی گئی',
                'ml' => 'CDA അഭ്യർത്ഥന റദ്ദാക്കി',
                'ru' => 'Запрос CDA отменён',
                'zh' => '已取消 CDA 请求'
            ],
            'Police Support Requested' => [
                'en' => 'Police Support Requested',
                'ar' => 'تم طلب دعم الشرطة',
                'ur' => 'پولیس مدد کی درخواست کی گئی',
                'ml' => 'പോലീസ് പിന്തുണ അഭ്യർത്ഥിച്ചു',
                'ru' => 'Запрошена поддержка полиции',
                'zh' => '请求了警察支持'
            ],
            'Cemetery Support Requested' => [
                'en' => 'Cemetery Support Requested',
                'ar' => 'تم طلب دعم المقبرة',
                'ur' => 'قبرستان کی مدد کی درخواست کی گئی',
                'ml' => 'ശ്മശാന സഹായം അഭ്യർത്ഥിച്ചു',
                'ru' => 'Запрошена поддержка кладбища',
                'zh' => '请求了墓地支持'
            ],
            'RTA Support Requested' => [
                'en' => 'RTA Support Requested',
                'ar' => 'تم طلب دعم RTA',
                'ur' => 'آر ٹی اے مدد کی درخواست کی گئی',
                'ml' => 'RTA പിന്തുണ അഭ്യർത്ഥിച്ചു',
                'ru' => 'Запрошена поддержка RTA',
                'zh' => '请求了RTA支持'
            ],
            'Burzakh Support Requested' => [
                'en' => 'Burzakh Support Requested',
                'ar' => 'تم طلب دعم برزخ',
                'ur' => 'برزخ کی مدد کی درخواست کی گئی',
                'ml' => 'ബുർസഖ് പിന്തുണ അഭ്യർത്ഥിച്ചു',
                'ru' => 'Запрошена поддержка Burzakh',
                'zh' => '请求了Burzakh支持'
            ],
            'RTA Submission' => [
                'en' => 'RTA Submission',
                'ar' => 'تقديم RTA',
                'ur' => 'آر ٹی اے جمع کروا دیا',
                'ml' => 'RTA സമർപ്പണം',
                'ru' => 'Подача RTA',
                'zh' => '提交 RTA'
            ],
            'RTA Request Cancelled' => [
                'en' => 'RTA Request Cancelled',
                'ar' => 'تم إلغاء طلب RTA',
                'ur' => 'آر ٹی اے درخواست منسوخ کی گئی',
                'ml' => 'RTA അഭ്യർത്ഥന റദ്ദാക്കി',
                'ru' => 'Запрос RTA отменён',
                'zh' => '已取消 RTA 请求'
            ],
            'Document Submission' => [
                'en' => 'Document Submission',
                'ar' => 'تقديم المستندات',
                'ur' => 'دستاویز جمع کروا دیا',
                'ml' => 'ഡോക്യുമെന്റ് സമർപ്പണം',
                'ru' => 'Подача документов',
                'zh' => '提交文件'
            ],
            'Support Requested' => [
                'en' => 'Support Requested',
                'ar' => 'تم طلب الدعم',
                'ur' => 'مدد کی درخواست کی گئی',
                'ml' => 'പിന്തുണ അഭ്യർത്ഥിച്ചു',
                'ru' => 'Запрошена поддержка',
                'zh' => '请求了支持'
            ],
            'Your request of RTA has been approved. They will contact you soon!' => [
                'en' => 'Your request of RTA has been approved. They will contact you soon!',
                'ar' => 'تمت الموافقة على طلبك لـ RTA. سيتواصلون معك قريبًا!',
                'ur' => 'آپ کی آر ٹی اے کی درخواست منظور کر لی گئی ہے۔ وہ آپ سے جلد رابطہ کریں گے!',
                'ml' => 'നിങ്ങളുടെ RTA അഭ്യർത്ഥന അംഗീകരിച്ചിരിക്കുന്നു. അവർ ഉടൻ നിങ്ങളുടെ ബന്ധപ്പെടും!',
                'ru' => 'Ваш запрос на RTA был одобрен. Они скоро свяжутся с вами!',
                'zh' => '您的 RTA 请求已获批准，他们将尽快与您联系！'
            ],
            'Your request of RTA has been rejected.' => [
                'en' => 'Your request of RTA has been rejected.',
                'ar' => 'تم رفض طلبك لـ RTA.',
                'ur' => 'آپ کی آر ٹی اے کی درخواست مسترد کر دی گئی ہے۔',
                'ml' => 'നിങ്ങളുടെ RTA അഭ്യർത്ഥന നിഷേധിച്ചിരിക്കുന്നു.',
                'ru' => 'Ваш запрос на RTA был отклонён.',
                'zh' => '您的 RTA 请求已被拒绝。'
            ],
            'Your request of CDA has been approved. They will contact you soon!' => [
                'en' => 'Your request of CDA has been approved. They will contact you soon!',
                'ar' => 'تمت الموافقة على طلبك لـ CDA. سيتواصلون معك قريبًا!',
                'ur' => 'آپ کی سی ڈی اے کی درخواست منظور کر لی گئی ہے۔ وہ آپ سے جلد رابطہ کریں گے!',
                'ml' => 'നിങ്ങളുടെ CDA അഭ്യർത്ഥന അംഗീകരിച്ചിരിക്കുന്നു. അവർ ഉടൻ നിങ്ങളുടെ ബന്ധപ്പെടും!',
                'ru' => 'Ваш запрос на CDA был одобрен. Они скоро свяжутся с вами!',
                'zh' => '您的 CDA 请求已获批准，他们将尽快与您联系！'
            ],
            'Your request of CDA has been rejected.' => [
                'en' => 'Your request of CDA has been rejected.',
                'ar' => 'تم رفض طلبك لـ CDA.',
                'ur' => 'آپ کی سی ڈی اے کی درخواست مسترد کر دی گئی ہے۔',
                'ml' => 'നിങ്ങളുടെ CDA അഭ്യർത്ഥന നിഷേധിച്ചിരിക്കുന്നു.',
                'ru' => 'Ваш запрос на CDA был отклонён.',
                'zh' => '您的 CDA 请求已被拒绝。'
            ],

           'Your documents has been approved by the police.' => [
                'en' => 'Your documents has been approved by the police.',
                'ar' => 'تمت الموافقة على مستنداتك من قبل الشرطة.',
                'ur' => 'آپ کے دستاویزات کو پولیس نے منظور کر لیا ہے۔',
                'ml' => 'നിങ്ങളുടെ രേഖകൾ പോലീസിന്റെ അംഗീകാരം നേടി.',
                'ru' => 'Ваши документы были одобрены полицией.',
                'zh' => '您的文件已被警方批准。'
            ],
            'Pending Approval' => [
                'en' => 'Pending Approval',
                'ar' => 'في انتظار الموافقة',
                'ur' => 'منظوری کا منتظر',
                'ml' => 'അനുമതി കാത്തിരിക്കുന്നു',
                'ru' => 'Ожидает одобрения',
                'zh' => '等待批准'
            ],
            'Pending Request' => [
                'en' => 'Pending Request',
                'ar' => 'طلب معلق',
                'ur' => 'زیر التواء درخواست',
                'ml' => 'വിചാരണ കാത്തിരിക്കുന്നു',
                'ru' => 'Ожидающий запрос',
                'zh' => '待处理请求'
            ],
            'Request Pending' => [
                'en' => 'Request Pending',
                'ar' => 'الطلب قيد الانتظار',
                'ur' => 'درخواست زیر التواء ہے',
                'ml' => 'അഭ്യര്‍ത്ഥന കാത്തിരിക്കുന്നു',
                'ru' => 'Запрос в ожидании',
                'zh' => '请求待处理'
            ],

            'RTA has sent you message.' => [
                'en' => 'RTA has sent you a message.',
                'ar' => 'أرسل لك RTA رسالة.',
                'ur' => 'آر ٹی اے نے آپ کو پیغام بھیجا ہے۔',
                'ml' => 'RTA നിങ്ങൾക്ക് സന്ദേശം അയച്ചിട്ടുണ്ട്.',
                'ru' => 'RTA отправил вам сообщение.',
                'zh' => 'RTA 给您发送了消息。'
            ],

            'CDA has sent you message.' => [
                'en' => 'CDA has sent you a message.',
                'ar' => 'أرسل لك CDA رسالة.',
                'ur' => 'سی ڈی اے نے آپ کو پیغام بھیجا ہے۔',
                'ml' => 'CDA നിങ്ങൾക്ക് സന്ദേശം അയച്ചിട്ടുണ്ട്.',
                'ru' => 'CDA отправил вам сообщение.',
                'zh' => 'CDA 给您发送了消息。'
            ],

            'Your request of RTA has been rejected. Reason:' => [
                'en' => 'Your request of RTA has been rejected. Reason:',
                'ar' => 'تم رفض طلبك لـ RTA. السبب:',
                'ur' => 'آپ کی آر ٹی اے کی درخواست مسترد کر دی گئی ہے۔ وجہ:',
                'ml' => 'നിങ്ങളുടെ RTA അഭ്യർത്ഥന നിരസിച്ചിരിക്കുന്നു. കാരണം:',
                'ru' => 'Ваш запрос на RTA был отклонен. Причина:',
                'zh' => '您的 RTA 请求被拒绝。原因：'
            ],

            'Your request of CDA has been rejected. Reason:' => [
                'en' => 'Your request of CDA has been rejected. Reason:',
                'ar' => 'تم رفض طلبك لـ CDA. السبب:',
                'ur' => 'آپ کی سی ڈی اے کی درخواست مسترد کر دی گئی ہے۔ وجہ:',
                'ml' => 'നിങ്ങളുടെ CDA അഭ്യർത്ഥന നിരസിച്ചിരിക്കുന്നു. കാരണം:',
                'ru' => 'Ваш запрос на CDA был отклонен. Причина:',
                'zh' => '您的 CDA 请求被拒绝。原因：'
            ],

            'RTA submission updated.' => [
                'en' => 'RTA submission updated.',
                'ar' => 'تم تحديث تقديم RTA.',
                'ur' => 'آر ٹی اے کی جمع کرائی گئی درخواست کو اپ ڈیٹ کر دیا گیا ہے۔',
                'ml' => 'RTA സമർപ്പണം പുതുക്കിയിട്ടുണ്ട്.',
                'ru' => 'Отправка RTA обновлена.',
                'zh' => 'RTA 提交已更新。'
            ],

            'CDA submission updated.' => [
                'en' => 'CDA submission updated.',
                'ar' => 'تم تحديث تقديم CDA.',
                'ur' => 'سی ڈی اے کی جمع کرائی گئی درخواست کو اپ ڈیٹ کر دیا گیا ہے۔',
                'ml' => 'CDA സമർപ്പണം പുതുക്കിയിട്ടുണ്ട്.',
                'ru' => 'Отправка CDA обновлена.',
                'zh' => 'CDA 提交已更新。'
            ],


            
        ];

        return $translations[$activityName] ?? array_fill_keys(['en', 'ar', 'ur', 'ml', 'ru', 'zh'], $activityName);
    }

    public function userRecentActivities($id)
    {
        $activities = RecentActivity::where('user_id', $id)
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get(['user_id', 'activity_name', 'status', 'created_at']);

        $formattedActivities = $activities->map(function ($activity) {
            $createdAt = Carbon::parse($activity->created_at);
            $now = Carbon::now();
            $diffInSeconds = $createdAt->diffInSeconds($now);
            $diffInMinutes = $createdAt->diffInMinutes($now);
            $diffInHours = $createdAt->diffInHours($now);
            $diffInDays = $createdAt->diffInDays($now);

            if ($diffInSeconds < 60) {
                $timeAgo = $diffInSeconds . 's ago';
            } elseif ($diffInMinutes < 60) {
                $timeAgo = $diffInMinutes . 'm ago';
            } elseif ($diffInHours < 24) {
                $timeAgo = $diffInHours . 'h ago';
            } else {
                $timeAgo = $diffInDays . 'd ago';
            }

            return [
                'user_id' => $activity->user_id,
                'activity_name' => $activity->activity_name,
                'activity_name_translation' => $this->getActivityTranslations($activity->activity_name),
                'status' => $this->getActivityTranslations($activity->status),
                // 'status' => $activity->status,
                'time_ago' => $timeAgo,
            ];
        });

        return response()->json([
            'message' => [
                'en' => 'Activities retrieved successfully',
                'ar' => 'تم استرجاع الأنشطة بنجاح',
                'ur' => 'سرگرمیاں کامیابی سے حاصل کی گئیں',
                'ml' => 'പ്രവർത്തനങ്ങൾ വിജയകരമായി നേടി',
                'ru' => 'Деятельность успешно получена',
                'zh' => '活动获取成功'
            ],
            'data' => $formattedActivities
        ], 200);
    }



    // public function userSupoortMessage(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'user_id'         => 'required',
    //         'admin_type'      => 'required',
    //         'message'         => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $submission = SupportMessage::create([
    //         'user_id'                      => $request->user_id,
    //         'admin_type'                   => $request->admin_type,
    //         'message'                      => $request->message,
    //     ]);
    //     $data = RecentActivity::create([
    //         'user_id'        => $request->user_id,
    //         'activity_name'  => "Support Requested",
    //         'status'         => "Request Pending"
    //     ]);

    //     return response()->json(['message' => 'Submission successful to Grave Supervisor', 'data' => $submission], 201);
    // }
    public function userSupoortMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required',
            'admin_type' => 'required',
            'message'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $adminType = $request->admin_type;
        $role = null;
        $notificationMessage = null;

        switch ($adminType) {
            case 'rta_help':
                $role = 'rta';
                $notificationMessage = "You received a message from user.";
                break;
            case 'cda_help':
                $role = 'cda';
                $notificationMessage = "You received a message from user.";
                break;
            case 'mancipality_assistence':
                $role = 'mancipality';
                $notificationMessage = "You received a message from user.";
                break;
            case 'mancipality_reply':
                $role = 'user';
                $notificationMessage = "You received a message from the Municipality.";
                break;
            case 'cda_reply':
                $role = 'user';
                $notificationMessage = "You received a message from CDA.";
                break;
            case 'rta_reply':
                $role = 'user';
                $notificationMessage = "You received a message from RTA.";
                break;
            default:
                return response()->json(['error' => 'Invalid admin_type provided.'], 400);
        }

        $submission = SupportMessage::create([
            'user_id'    => $request->user_id,
            'admin_type' => $adminType,
            'message'    => $request->message,
        ]);

        // Log recent activity
        RecentActivity::create([
            'user_id'       => $request->user_id,
            'activity_name' => "Support Requested",
            'status'        => "Request Pending",
        ]);

        // Create notification
        BurzakhNotification::create([
            'user_id'              => $request->user_id,
            'notification_message' => $notificationMessage,
            'role'                 => $role,
        ]);

        return response()->json([
            'message' => 'Message sent successfully.',
            'data'    => $submission
        ], 201);
    }


    public function listSupportMessages($userId, $adminType)
    {
        $messages = SupportMessage::where('user_id', $userId)
            ->where('admin_type', $adminType)
            ->orderBy('id', 'desc')
            ->get(['user_id', 'admin_type', 'message']);

        return response()->json([
            'message' => 'Messages retrieved successfully',
            'data' => $messages
        ], 200);
    }

    public function getPolicePendingRequests()
    {
        $today = Carbon::today()->toDateString();

        $counts = BurzakhMemberDocumentSubmission::selectRaw("
            COUNT(CASE WHEN case_status = 'pending' THEN 1 END) as pending_cases,
            COUNT(CASE WHEN case_status = 'schedule_call' THEN 1 END) as schedule_call_cases,
            COUNT(CASE WHEN case_status = 'approved' AND DATE(created_at) = ? THEN 1 END) as approved_cases_today,
            COUNT(CASE WHEN case_status = 'permit_issued' THEN 1 END) as permit_issued_cases
        ", [$today])->first();

        return response()->json([
            'message' => 'Cases retrieved successfully',
            'pending_cases' => $counts->pending_cases,
            'schedule_call_cases' => $counts->schedule_call_cases,
            'approved_cases_today' => $counts->approved_cases_today,
            'permit_issued_cases' => $counts->permit_issued_cases,
        ], 200);
    }

    public function searchCases(Request $request)
    {
        $query = BurzakhMemberDocumentSubmission::with('user'); 

        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'pending':
                    $query->where('case_status', 'pending');
                    break;
                case 'schedule_call':
                    $query->where('case_status', 'schedule_call');
                    break;
                case 'awaiting_final_approval':
                    $query->where('case_status', 'awaiting_final_approval');
                    break;
            }
        }

        $results = $query->orderByDesc('id')->get();

        return response()->json([
            'message' => 'Filtered cases retrieved successfully',
            'data' => $results,
        ]);
    }


    public function getCaseDetails($id){
        // Query
        $query = BurzakhMemberDocumentSubmission::with('user'); 
        $results = $query->where('id',$id)->get();

        return response()->json([
            'message' => 'Filtered cases retrieved successfully',
            'data' => $results,
        ]);
    }

    public function updateApproveCasePolice(Request $request, $case_id, $user_id)
    {
        $case = BurzakhMemberDocumentSubmission::where('id', $case_id)
                    ->where('user_id', $user_id)
                    ->first();

        if (!$case) {
            return response()->json([
                'message' => 'Case not found or does not belong to this user.',
                'data' => null,
            ], 404);
        }

        try {
            $validated = $request->validate([
                'police_clearance_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }

        $filePath = $case->police_clearance;

        if ($request->hasFile('police_clearance_certificate')) {
            $file = $request->file('police_clearance_certificate');
            $filePath = $file->store('police_clearances', 'public');
        }

        $case->update([
            'case_status' => 'approved',
            'death_notification_file_status' => 'approved',
            'hospital_certificate_status' => 'approved',
            'passport_or_emirate_id_front_status' => 'approved',
            'passport_or_emirate_id_back_status' => 'approved',
            'police_clearance' => $this->base_url . "/storage/app/public/" . $filePath,
            'ratio' => 0.4,
        ]);

        $sendNotification = BurzakhNotification::create([
            'user_id'               => $user_id,
            'notification_message'  => "Your documents has been approved by the police.",
        ]);

        return response()->json([
            'message' => 'Case approved successfully.',
            'data' => $case,
        ]);
    }


    public function timingVideoCall(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'              => 'required',
            'date'                 => 'required',
            'time'                 => 'required',
            'admin_id'             => 'required',
            'meeting_id'           => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user_id           = $request->user_id;     
        $date              = $request->date;     
        $time              = $request->time;     
        $admin_id          = $request->admin_id;     
        $meeting_id        = $request->meeting_id;     

        $data = VideoMeetTiming::create([
            'user_id'      => $user_id,
            'date'         => $date,
            'admin_id'     => $admin_id,
            'time'         => $time,
            'meeting_id'   => $meeting_id,
        ]);

        return response()->json(['message' => 'Submission successful', 'data' => $data], 201);
    }

    public function policeQuickActions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'additional_document'        => 'nullable|string',
            'send_notification_message'  => 'nullable|string',
            'admin_id'                   => 'required|integer|exists:burzakh_members,id',
            'user_id'                    => 'required|integer|exists:burzakh_members,id',
            'case_id'                    => 'required|integer|exists:burzakh_member_document_submissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = $request->user_id;
        $caseId = $request->case_id;

        $case = BurzakhMemberDocumentSubmission::where('user_id', $userId)
                    ->where('id', $caseId)
                    ->first();

        if (!$case) {
            return response()->json(['message' => 'Case not found.'], 404);
        }

        if (!is_null($request->additional_document)) {
            $case->additional_document = $request->additional_document;
        }

        if (!is_null($request->send_notification_message)) {
            $case->send_notification_message = $request->send_notification_message;
        }

        $case->admin_id = $request->admin_id;

        $case->save();

        if (!is_null($request->send_notification_message)) {
            $sendNotification = BurzakhNotification::create([
                'user_id'               => $userId,
                'notification_message'  => $request->send_notification_message,
            ]);

            RecentActivity::create([
                'user_id'        => $userId,
                'activity_name'  => "You recieved message from police.",
                'status'         => "Recieved notication"
            ]);
        }

        return response()->json([
            'message' => 'Submission successful',
            'data'    => $case
        ], 201);
    }




    // RTAA
    // public function listingRTARequests()
    // {
    //     $submissions = RtaSubmission::with('user')->get();
    //     // var_dump($submissions->case_name);die();

    //     $todayDate = Carbon::today()->toDateString();

    //     $pendingRequests = [];
    //     $counts = [
    //         'Pending' => 0,
    //         'Approved' => 0,
    //         'Rejected' => 0,
    //         'TodayTent' => 0,
    //         'Cancelled' => 0,
    //     ];
    //     $get_case_details='';
    //     foreach ($submissions as $submission) {
    //         $caseName = $submission->case_name;
    //         if ($submission->status === 'Pending') {
    //             $counts['Pending']++;
    //             $pendingRequests[] = $submission;
    //         } elseif ($submission->status === 'Approved') {
    //             $counts['Approved']++;
    //             if (Carbon::parse($submission->mourning_start_date)->toDateString() === $todayDate) {
    //                 $counts['TodayTent']++;
    //             }
    //         } elseif ($submission->status === 'Rejected') {
    //             $counts['Rejected']++;
    //         } elseif ($submission->status === 'Cancelled') {
    //             $counts['Cancelled']++;
    //         }

    //         $get_case_details = BurzakhMemberDocumentSubmission::where('name_of_deceased', $caseName)->get();
    //     // var_dump($get_case_details);die();
    //     }

        

    //     return response()->json([
    //         'message' => 'RTA requests retrieved successfully',
    //         'PendingCount' => $counts['Pending'],
    //         'ApprovedCount' => $counts['Approved'],
    //         'RejectedCount' => $counts['Rejected'],
    //         'TodayTent' => $counts['TodayTent'],
    //         'Cancelled' => $counts['Cancelled'],
    //         'AllRequests' => $submissions,
    //         'case_details' => $get_case_details
    //     ], 200);
    // }
    public function listingRTARequests()
    {
        $submissions = RtaSubmission::with(['user', 'caseDetails'])->get();

        $todayDate = Carbon::today()->toDateString();

        $pendingRequests = [];
        $counts = [
            'Pending' => 0,
            'Approved' => 0,
            'Rejected' => 0,
            'TodayTent' => 0,
            'Cancelled' => 0,
        ];

        foreach ($submissions as $submission) {
            if ($submission->status === 'Pending') {
                $counts['Pending']++;
                $pendingRequests[] = $submission;
            } elseif ($submission->status === 'Approved') {
                $counts['Approved']++;
                if (Carbon::parse($submission->mourning_start_date)->toDateString() === $todayDate) {
                    $counts['TodayTent']++;
                }
            } elseif ($submission->status === 'Rejected') {
                $counts['Rejected']++;
            } elseif ($submission->status === 'Cancelled') {
                $counts['Cancelled']++;
            }
        }

        return response()->json([
            'message' => 'RTA requests retrieved successfully',
            'PendingCount' => $counts['Pending'],
            'ApprovedCount' => $counts['Approved'],
            'RejectedCount' => $counts['Rejected'],
            'TodayTent' => $counts['TodayTent'],
            'Cancelled' => $counts['Cancelled'],
            'AllRequests' => $submissions,
        ], 200);
    }


    
    public function filterRTARequests(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $query = RtaSubmission::with('user');

        if ($filter === 'this_day') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($filter === 'this_week') {
            $query->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
        } elseif ($filter === 'this_month') {
            $query->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ]);
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Filtered RTA requests retrieved successfully',
            'count' => $requests->count(),
            'data' => $requests,
        ]);
    }



    // public function updateRTARequestStatus(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'action'  => 'required|in:approve,reject',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $rta = RtaSubmission::find($id);

    //     if (!$rta) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'RTA request not found.',
    //         ], 404);
    //     }

    //     $action = $validator->validated()['action'];
    //     $message = '';

    //     if ($action === 'approve') {
    //         $rta->status = 'Approved';
    //         $message = "Your request of RTA has been approved. They will contact you soon!";
    //     } elseif ($action === 'reject') {
    //         $rta->status = 'Rejected';
    //         $message = "Your request of RTA has been rejected.";
    //     }

    //     $rta->save();

    //     $sendNotification = BurzakhNotification::create([
    //         'user_id'               => $rta->user_id,
    //         'notification_message'  => $message,
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'RTA request status updated successfully.',
    //         'data' => $rta,
    //     ]);
    // }
    public function updateRTARequestStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|max:255',
        ], [
            'rejection_reason.required_if' => 'Rejection reason is required when rejecting the request.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rta = RtaSubmission::find($id);

        if (!$rta) {
            return response()->json([
                'status' => false,
                'message' => 'RTA request not found.',
            ], 404);
        }

        $action = $request->action;
        $rejectionReason = $request->rejection_reason;
        $message = '';

        if ($action === 'approve') {
            $rta->status = 'Approved';
            $message = "Your request of RTA has been approved. They will contact you soon!";
        } elseif ($action === 'reject') {
            $rta->status = 'Rejected';
            $rta->rejection_reason = $rejectionReason;
            $message = "Your request of RTA has been rejected. Reason: " . $rejectionReason;
        }

        $rta->save();

        BurzakhNotification::create([
            'user_id' => $rta->user_id,
            'notification_message' => $message,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'RTA request status updated successfully.',
            'data' => $rta,
        ]);
    }




    public function updateRTARequestUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mourning_start_date' => 'nullable|date',
            'mourning_end_date' => 'nullable|date',
            'location_of_house' => 'nullable|string|max:255',
            'signs_required' => 'nullable|string|max:255',
            'custom_text_for_sign' => 'nullable|string|max:255',
            'user_id' => 'required|integer|exists:burzakh_members,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $rta = RtaSubmission::find($id);
    
        if (!$rta) {
            return response()->json([
                'status' => false,
                'message' => 'RTA request not found.',
            ], 404);
        }

        if ($request->filled('mourning_start_date')) {
            $rta->mourning_start_date = $request->mourning_start_date;
        }

        if ($request->filled('mourning_end_date')) {
            $rta->mourning_end_date = $request->mourning_end_date;
        }
    
        if ($request->filled('location_of_house')) {
            $rta->location_of_house = $request->location_of_house;
        }
    
        if ($request->filled('signs_required')) {
            $rta->signs_required = $request->signs_required;
        }
    
        if ($request->filled('custom_text_for_sign')) {
            $rta->custom_text_for_sign = $request->custom_text_for_sign;
        }
    
        if ($request->filled('user_id')) {
            $rta->user_id = $request->user_id;
        }
    
        $rta->status = "Pending";
        $rta->save();
    
        BurzakhNotification::create([
            'user_id' => $rta->user_id,
            'notification_message' => 'RTA submission updated.',
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'RTA request updated successfully.',
            'data' => $rta,
        ]);
    }
    



    public function getRTACaseDetails($id){
        // Query
        $query = RTASubmission::with('user','caseDetails'); 
        $results = $query->where('id',$id)->get();

        return response()->json([
            'message' => 'Case details retrieved successfully',
            'data' => $results,
        ]);
    }


    public function RTASupoortMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'         => 'required',
            'message'         => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $submission = SupportMessage::create([
            'user_id'                      => $request->user_id,
            'admin_type'                   => "rta_reply",
            'message'                      => $request->message,
            'role'                         => "rta",
        ]);

        $sendNotification = BurzakhNotification::create([
            'user_id'               => $request->user_id,
            'notification_message'  => "Rta has sent you message.",
        ]);

        return response()->json(['message' => 'Submission successful to Grave Supervisor', 'data' => $submission], 201);
    }

    // public function listRTASupportMessages()
    // {
    //     $messages = SupportMessage::all();

    //     return response()->json([
    //         'message' => 'Messages retrieved successfully',
    //         'data' => $messages
    //     ], 200);
    // }

    public function listRTASupportMessagedUsers()
    {
        $messages = SupportMessage::where('role', 'user')
                ->where('admin_type', 'rta_help')
                ->with('user')->get();

        return response()->json([
            'message' => 'Messages retrieved successfully',
            'data' => $messages
        ], 200);
    }


    public function getRTASupportChat($userId)
    {
        $messages = SupportMessage::where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('role', 'user')
                    ->where('admin_type', 'rta_help');
            })->orWhere(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('role', 'rta')
                    ->where('admin_type', 'rta_reply');
            })
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'message' => 'User conversation retrieved successfully',
            'data'    => $messages,
        ]);
    }



    // CDAA
    // public function listingCDARequests()
    // {
    //     $submissions = CdaSubmission::with('user')->get();

    //     $todayDate = Carbon::today()->toDateString();

    //     $pendingRequests = [];
    //     $counts = [
    //         'Pending' => 0,
    //         'Approved' => 0,
    //         'Rejected' => 0,
    //         'TodayTent' => 0,
    //         'Cancelled' => 0,
    //     ];

    //     $get_case_details='';
    //     foreach ($submissions as $submission) {
    //         $caseName = $submission->case_name;
    //         if ($submission->status === 'Pending') {
    //             $counts['Pending']++;
    //             $pendingRequests[] = $submission;
    //         } elseif ($submission->status === 'Approved') {
    //             $counts['Approved']++;
    //             if (Carbon::parse($submission->mourning_start_date)->toDateString() === $todayDate) {
    //                 $counts['TodayTent']++;
    //             }
    //         } elseif ($submission->status === 'Rejected') {
    //             $counts['Rejected']++;
    //         } elseif ($submission->status === 'Cancelled') {
    //             $counts['Cancelled']++;
    //         }
    //         $get_case_details = BurzakhMemberDocumentSubmission::where('name_of_deceased', $caseName)->get();
    //     }

    //     return response()->json([
    //         'message' => 'CDA requests retrieved successfully',
    //         'PendingCount' => $counts['Pending'],
    //         'ApprovedCount' => $counts['Approved'],
    //         'RejectedCount' => $counts['Rejected'],
    //         'TodayTent' => $counts['TodayTent'],
    //         'Cancelled' => $counts['Cancelled'],
    //         'AllRequests' => $submissions,
    //         'case_details' => $get_case_details
    //     ], 200);
    // }


    public function listingCDARequests()
    {
        $submissions = CdaSubmission::with(['user', 'caseDetails'])->get();

        $todayDate = Carbon::today()->toDateString();

        $pendingRequests = [];
        $counts = [
            'Pending' => 0,
            'Approved' => 0,
            'Rejected' => 0,
            'TodayTent' => 0,
            'Cancelled' => 0,
        ];

        foreach ($submissions as $submission) {
            if ($submission->status === 'Pending') {
                $counts['Pending']++;
                $pendingRequests[] = $submission;
            } elseif ($submission->status === 'Approved') {
                $counts['Approved']++;
                if (Carbon::parse($submission->mourning_start_date)->toDateString() === $todayDate) {
                    $counts['TodayTent']++;
                }
            } elseif ($submission->status === 'Rejected') {
                $counts['Rejected']++;
            } elseif ($submission->status === 'Cancelled') {
                $counts['Cancelled']++;
            }
        }

        return response()->json([
            'message' => 'CDA requests retrieved successfully',
            'PendingCount' => $counts['Pending'],
            'ApprovedCount' => $counts['Approved'],
            'RejectedCount' => $counts['Rejected'],
            'TodayTent' => $counts['TodayTent'],
            'Cancelled' => $counts['Cancelled'],
            'AllRequests' => $submissions,
        ], 200);
    }


    public function filterCDARequests(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $query = CdaSubmission::with('user');

        if ($filter === 'this_day') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($filter === 'this_week') {
            $query->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
        } elseif ($filter === 'this_month') {
            $query->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ]);
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Filtered CDA requests retrieved successfully',
            'count' => $requests->count(),
            'data' => $requests,
        ]);
    }


    // public function updateCDARequestStatus(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'action' => 'required|in:approve,reject',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $cda = CdaSubmission::find($id);

    //     if (!$cda) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'CDA request not found.',
    //         ], 404);
    //     }

    //     $action = $validator->validated()['action'];

    //     if ($action === 'approve') {
    //         $cda->status = 'Approved';
    //         $message = "Your request of CDA has been approved. They will contact you soon!";
    //     } elseif ($action === 'reject') {
    //         $cda->status = 'Rejected';
    //         $message = "Your request of CDA has been rejected.";
    //     }

    //     $cda->save();

    //     $sendNotification = BurzakhNotification::create([
    //         'user_id'               => $cda->user_id,
    //         'notification_message'  => $message,
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'CDA request status updated successfully.',
    //         'data' => $cda,
    //     ]);
    // }

    public function updateCDARequestStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|max:255',
        ], [
            'rejection_reason.required_if' => 'Rejection reason is required when rejecting the request.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cda = CdaSubmission::find($id);

        if (!$cda) {
            return response()->json([
                'status' => false,
                'message' => 'cda request not found.',
            ], 404);
        }

        $action = $request->action;
        $rejectionReason = $request->rejection_reason;
        $message = '';

        if ($action === 'approve') {
            $cda->status = 'Approved';
            $message = "Your request of CDA has been approved. They will contact you soon!";
        } elseif ($action === 'reject') {
            $cda->status = 'Rejected';
            $cda->rejection_reason = $rejectionReason;
            $message = "Your request of CDA has been rejected. Reason: " . $rejectionReason;
        }

        $cda->save();

        BurzakhNotification::create([
            'user_id' => $cda->user_id,
            'notification_message' => $message,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'CDA request status updated successfully.',
            'data' => $cda,
        ]);
    }




    public function updateCDARequestUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mourning_start_date' => 'nullable|date',
            'mourning_end_date' => 'nullable|date',
            'location_of_tent' => 'nullable|string|max:255',
            'user_id' => 'required|integer|exists:burzakh_members,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $cda = CdaSubmission::find($id);
    
        if (!$cda) {
            return response()->json([
                'status' => false,
                'message' => 'CDA request not found.',
            ], 404);
        }

        if ($request->filled('mourning_start_date')) {
            $cda->mourning_start_date = $request->mourning_start_date;
        }

        if ($request->filled('mourning_end_date')) {
            $cda->mourning_end_date = $request->mourning_end_date;
        }
    
        if ($request->filled('location_of_tent')) {
            $cda->location_of_tent = $request->location_of_tent;
        }
    
        if ($request->filled('user_id')) {
            $cda->user_id = $request->user_id;
        }
        $cda->status = "Pending";
        $cda->save();
    
        BurzakhNotification::create([
            'user_id' => $cda->user_id,
            'notification_message' => 'CDA submission updated.',
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'CDA request updated successfully.',
            'data' => $cda,
        ]);
    }
    


    public function getCDACaseDetails($id){
        // Query
        $query = CDASubmission::with('user','caseDetails'); 
        $results = $query->where('id',$id)->get();

        return response()->json([
            'message' => 'Case details retrieved successfully',
            'data' => $results,
        ]);
    }


    public function CDASupoortMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'         => 'required',
            'message'         => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $submission = SupportMessage::create([
            'user_id'                      => $request->user_id,
            'admin_type'                   => "cda_reply",
            'message'                      => $request->message,
            'role'                         => "cda",
        ]);

        $sendNotification = BurzakhNotification::create([
            'user_id'               => $request->user_id,
            'notification_message'  => "Cda has sent you message.",
        ]);

        return response()->json(['message' => 'Message sent successfully.', 'data' => $submission], 201);
    }
    

    public function fetchNotifications($id)
    {
        $notifications = BurzakhNotification::where('user_id', $id)
            ->orderBy('id', 'desc')
            ->get();
    
        if ($notifications->isNotEmpty()) {
            $translatedNotifications = $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->notification_message,
                    'translations' => $this->getActivityTranslations($notification->notification_message),
                    'message' => $notification->role,
                    'created_at' => $notification->created_at,
                ];
            });
    
            return response()->json([
                'message' => "Notifications fetched successfully.",
                'notifications' => $translatedNotifications,
            ], 200);
        } else {
            return response()->json([
                'message' => "No notifications found.",
                'notifications' => [],
            ], 200);
        }
    }

    public function listCDASupportMessagedUsers()
    {
        $messages = SupportMessage::where('role', 'user')
                ->where('admin_type', 'cda_assistence')
                ->with('user')->get();

        return response()->json([
            'message' => 'Messages retrieved successfully',
            'data' => $messages
        ], 200);
    }


    public function getCDASupportChat($userId)
    {
        $messages = SupportMessage::where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('role', 'user')
                    ->where('admin_type', 'cda_assistence');
            })->orWhere(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('role', 'cda')
                    ->where('admin_type', 'cda_reply');
            })
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'message' => 'User conversation retrieved successfully',
            'data'    => $messages,
        ]);
    }

    // Mancipalityy
    public function listingMancipalityRequests()
    {
        $submissions = BurzakhUserSubmissionToMancipality::with(['user', 'caseDetails'])->orderBy('id','desc')->get();

        $todayDate = Carbon::today()->toDateString();

        $pendingRequests = [];
        $counts = [
            'Pending' => 0,
            'Approved' => 0,
            'Rejected' => 0,
            'TodayBurials' => 0,
            'Cancelled' => 0,
        ];
        // var_dump($todayDate);die();
        

        foreach ($submissions as $submission) {
            if ($submission->status === 'Pending') {
                $counts['Pending']++;
                $pendingRequests[] = $submission;
            } elseif ($submission->status === 'Approve') {
                $counts['Approved']++;
                if (Carbon::parse($submission->created_at)->toDateString() === $todayDate) {
                    $counts['TodayBurials']++;
                }
            } elseif ($submission->status === 'Rejected') {
                $counts['Rejected']++;
            } elseif ($submission->status === 'Cancelled') {
                $counts['Cancelled']++;
            }
        }

        return response()->json([
            'message' => 'Mancipality requests retrieved successfully',
            'PendingCount' => $counts['Pending'],
            'ApprovedCount' => $counts['Approved'],
            'RejectedCount' => $counts['Rejected'],
            'TodayBurials' => $counts['TodayBurials'],
            'Cancelled' => $counts['Cancelled'],
            'AllRequests' => $submissions,
        ], 200);
    }

    public function fetchAmbulances()
    {
        $statuses = ['Active', 'Maintenance','Dispatched'];

        $ambulances = Ambulance::with('dispatchInfo')->get();
        $grouped = $ambulances->groupBy('status');

        $counts = [];
        foreach ($statuses as $status) {
            $counts[$status . '_count'] = isset($grouped[$status]) ? $grouped[$status]->count() : 0;
        }

        return response()->json([
            'message' => 'Ambulances retrieved successfully',
            'counts' => $counts,
            'ambulances' => $ambulances,
        ], 200);
    }

    public function fetchAmbulanceDetails($id){
        $get_ambulance_details = Ambulance::where('id',$id)->get();
        return response()->json([
            'message' => 'Ambulance retrieved successfully',
            'Ambulances' => $get_ambulance_details,
        ], 200);
    }

    public function getMancipalityCaseDetails($id){
        // Query
        $query = BurzakhUserSubmissionToMancipality::with('user','caseDetails'); 
        $results = $query->where('id',$id)->get();

        return response()->json([
            'message' => 'Case details retrieved successfully',
            'data' => $results,
        ]);
    }


    // public function updateMancipalityRequestStatus(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'action' => 'required|in:approve',
    //         'grave_number' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $mancipality = BurzakhUserSubmissionToMancipality::find($id);

    //     if (!$mancipality) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'mancipality request not found.',
    //         ], 404);
    //     }

    //     $action = $request->action;
    //     $grave_number = $request->grave_number;
    //     $message = '';

    //     if ($action === 'approve') {
    //         $mancipality->status = 'Approve';
    //         $mancipality->grave_number = $grave_number;
    //         $message = "Your request of Mancipality has been approved. Grave Number: " . $grave_number;

    //         $cemetry_case_submission = CemeteryCase::create([
    //             'grave_number' => $grave_number,
    //             'user_id' => $mancipality->user_id,
    //             'case_name' => $mancipality->case_name,
    //         ]);
    //     }

    //     $mancipality->save();

    //     BurzakhNotification::create([
    //         'user_id' => $mancipality->user_id,
    //         'notification_message' => $message,
    //     ]);

    //     RecentActivity::create([
    //         'user_id'        => $mancipality->user_id,
    //         'activity_name'  => "Grave number assigned by mancipality.",
    //         'status'         => "Request Approved"
    //     ]);

    //     $updateSubmissionStatus=BurzakhMemberDocumentSubmission::where('user_id',$mancipality->user_id)->where('name_of_deceased',$mancipality->case_name)->update(['burial_submission_status'=>'Approved','ratio'=>0.6]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Mancipality request status updated successfully.',
    //         'data' => $mancipality,
    //     ]);
    // }
    public function updateMancipalityRequestStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,assign_grave_number',
            'grave_number' => 'required_if:action,assign_grave_number',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mancipality = BurzakhUserSubmissionToMancipality::find($id);

        if (!$mancipality) {
            return response()->json([
                'status' => false,
                'message' => 'Mancipality request not found.',
            ], 404);
        }

        $action = $request->action;
        $message = '';

        if ($action === 'approve') {
            $mancipality->status = 'Approve';
            $message = "Your request to the Mancipality has been approved.";

            // Log approval activity
            RecentActivity::create([
                'user_id'       => $mancipality->user_id,
                'activity_name' => "Mancipality request approved.",
                'status'        => "Request Approved"
            ]);
        }

        if ($action === 'assign_grave_number') {
            $grave_number = $request->grave_number;
            $mancipality->grave_number = $grave_number;
            $mancipality->grave_status = "grave-number-assigned";
            $message = "Grave number assigned by Mancipality: " . $grave_number;

            // Create cemetery case
            CemeteryCase::create([
                'grave_number' => $grave_number,
                'user_id'      => $mancipality->user_id,
                'case_name'    => $mancipality->case_name,
            ]);

            // Log assignment activity
            RecentActivity::create([
                'user_id'       => $mancipality->user_id,
                'activity_name' => "Grave number assigned by Mancipality.",
                'status'        => "Grave Assigned"
            ]);
        }

        $mancipality->save();

        // Send notification if any message was generated
        if ($message) {
            BurzakhNotification::create([
                'user_id'              => $mancipality->user_id,
                'notification_message' => $message,
            ]);
        }

        // Update member document status only if approved
        if ($action === 'approve') {
            BurzakhMemberDocumentSubmission::where('user_id', $mancipality->user_id)
                ->where('name_of_deceased', $mancipality->case_name)
                ->update([
                    'burial_submission_status' => 'Approved',
                    'ratio' => 0.6
                ]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Mancipality request status updated successfully.',
            'data'    => $mancipality,
        ]);
    }


    public function MancipalitySupoortMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'         => 'required',
            'message'         => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $submission = SupportMessage::create([
            'user_id'                      => $request->user_id,
            'admin_type'                   => "mancipality_reply",
            'message'                      => $request->message,
            'role'                         => "mancipality",
        ]);

        $sendNotification = BurzakhNotification::create([
            'user_id'               => $request->user_id,
            'notification_message'  => "Mancipality has sent you message.",
        ]);

        $recentActivity = RecentActivity::create([
            'user_id'        => $request->user_id,
            'activity_name'  => "Mancipality sent you message.",
            'status'         => "Request Approved"
        ]);

        return response()->json(['message' => 'Message sent successfully.', 'data' => $submission], 201);
    }

    public function listMancipalitySupportMessagedUsers()
    {
        $messages = SupportMessage::where('role', 'user')
                ->where('admin_type', 'mancipality_assistence')
                ->with('user')->get();

        return response()->json([
            'message' => 'Messages retrieved successfully',
            'data' => $messages
        ], 200);
    }


    public function getMancipalitySupportChat($userId)
    {
        $messages = SupportMessage::where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('role', 'user')
                    ->where('admin_type', 'mancipality_assistence');
            })->orWhere(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('role', 'mancipality')
                    ->where('admin_type', 'mancipality_reply');
            })
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'message' => 'User conversation retrieved successfully',
            'data'    => $messages,
        ]);
    }


    public function DispatchAmbulance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'case_name'         => 'required_without:standby_mosque|nullable|string',
            'standby_mosque'    => 'required_without:case_name|nullable|string',
            'additional_notes'  => 'nullable|string',
            'vehicle_number'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $getCaseInfo=BurzakhMemberDocumentSubmission::where('name_of_deceased',$request->case_name)->first();
        $getUserId='';
        if($getCaseInfo!==NULL){
            $getUserId=$getCaseInfo->user_id;
        }
        else{
            return response()->json(['message' => 'Case not found.'], 401);
        }

        $submission = DispatchAmbulance::create([
            'case_name'               => $request->case_name,
            'standby_mosque'          => $request->standby_mosque,
            'additional_notes'        => $request->additional_notes,
            'vehicle_number'          => $request->vehicle_number,
        ]);

        $submission = DispatchAmbulance::where('vehicle_number',$request->vehicle_number)->where('case_name',$request->case_name)->update([
            'status'=>'Dispatched'
        ]);

        $getCaseInfo->update([
            'ambulance_dispatched' => "true",
        ]);

        
        // var_dump($getUserId);
        $sendNotification = BurzakhNotification::create([
            'user_id'               => $getUserId,
            'notification_message'  => "The ambulance is dispatched by Mancipality for Case ".$request->case_name."",
        ]);

        $updateSubmissionStatus = BurzakhMemberDocumentSubmission::where('user_id',$getCaseInfo->user_id)->where('name_of_deceased',$request->case_name)->update(['ratio' => 0.8]);

        
        $recentActivity = RecentActivity::create([
            'user_id'        => $getUserId,
            'activity_name'  => "Mancipality dispatched a ambulance.",
            'status'         => "Request Approved"
        ]);

        return response()->json(['message' => 'Dispatched successfully.', 'data' => $submission], 201);
    }

    public function fetchMancipalityNotifications()
    {
        $notifications = BurzakhNotification::where('role', 'mancipality')
            ->orderBy('id', 'desc')
            ->get();
    
        
            return response()->json([
                'message' => "Notifications fetched successfully.",
                'notifications' => $notifications,
            ], 200);
        
    }


    // Cemeteryy
    public function getCemeteryCases()
    {
        $submissions = CemeteryCase::with(['user', 'caseDetails'])->get();
        $todayDate = Carbon::today()->toDateString();

        $pendingRequests = [];
        $counts = [
            'Pending' => 0,
            'Approved' => 0,
            'TotalCases' => CemeteryCase::count(),
            'TodayBurials' => 0,
            'ActiveMorticians' => Mortician::where('status', 'active')->count(),
        ];

        foreach ($submissions as $submission) {
            if ($submission->status === 'Pending') {
                $counts['Pending']++;
                $pendingRequests[] = $submission;
            } elseif ($submission->status === 'Approved') {
                $counts['Approved']++;
            }
            if (Carbon::parse($submission->created_at)->toDateString() === $todayDate) {
                $counts['TodayBurials']++;
            }
        }

        $get_cases = CemeteryCase::orderBy('id', 'desc')->with('user', 'caseDetails','mortician', 'mancipalityRecord')->get();
        

        return response()->json([
            'message' => 'Cases fetched successfully.',
            'PendingCount' => $counts['Pending'],
            'ApprovedCount' => $counts['Approved'],
            'TodayBurials' => $counts['TodayBurials'],
            'TotalCases' => $counts['TotalCases'],
            'ActiveMorticians' => $counts['ActiveMorticians'],
            'cases' => $get_cases,
        ], 200);
    }

    public function getActiveMorticians(){
        $get_morticians = Mortician::where('status', 'active')->get();
        return response()->json([
            'message' => 'Morticians fetched successfully.',
            'Morticians' => $get_morticians,
        ], 200);
    }

    public function assignMortician(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'case_name' => 'required',
            'mortician_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cemetry_case = CemeteryCase::find($id);

        if (!$cemetry_case) {
            return response()->json([
                'status' => false,
                'message' => 'Case not found.',
            ], 404);
        }

        $newMorticianId = $request->mortician_id;
        $oldMorticianId = $cemetry_case->mortician_id;

        $cemetry_case->status = 'Assigned';
        $cemetry_case->mortician_id = $newMorticianId;
        $cemetry_case->save();

        if ($oldMorticianId && $oldMorticianId != $newMorticianId) {
            $oldMortician = Mortician::find($oldMorticianId);
            if ($oldMortician) {
                $oldMortician->case_name = NULL;
                $oldMortician->case_name = $request->case_name;
                $oldMortician->status = 'Active';
                $oldMortician->save();
            }
        }

        $newMortician = Mortician::find($newMorticianId);
        if ($newMortician) {
            $newMortician->case_name = $request->case_name;
            $newMortician->status = 'Active';
            $newMortician->save();
        }

        BurzakhNotification::create([
            'user_id' => $cemetry_case->user_id,
            'notification_message' => 'Your case is assigned to a mortician by Cemetery',
        ]);

        // BurzakhNotification::create([
        //     'user_id' => $cemetry_case->user_id,
        //     'notification_message' => 'Dubai Macipality assigned you a case.',
        //     'role' => 'mortician',
        // ]);

        RecentActivity::create([
            'user_id'       => $cemetry_case->user_id,
            'activity_name' => "Your case is assigned to a mortician by Cemetery",
            'status'        => "Request Approved"
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Case assigned to mortician successfully',
            'data' => $cemetry_case,
        ]);
    }

    public function createVisitorAlert(Request $request){
        $validator = Validator::make($request->all(), [
            'name'              => 'required',
            'gender'            => 'required',
            'alert_time'        => 'required',
            'cemetery_location' => 'required',
            'mosque_name'       => 'required',
            'description'       => 'required',
            'description_arabic'=> 'required',
            'status'            => 'required',
            'make_as_important' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $create_visitor_alerts = VisitorAlert::create([
            'name'              => $request->name,
            'gender'            => $request->gender,
            'alert_time'        => $request->alert_time,
            'cemetery_location' => $request->cemetery_location,
            'mosque_name'       => $request->mosque_name,
            'description'       => $request->description,
            'description_arabic'=> $request->description_arabic,
            'status'            => $request->status,
            'make_as_important' => $request->make_as_important,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Visitor alert craeted successfully',
            'data' => $create_visitor_alerts,
        ]);
    }

    public function fetchVisitorAlerts(Request $request)
    {
        $query = VisitorAlert::query();

        $day = $request->query('day', 'today');
        if ($day === 'tomorrow') {
            $date = now()->addDay()->format('Y-m-d');
        } else {
            $date = now()->format('Y-m-d');
        }

        $query->whereDate('created_at', $date);

        if ($request->filled('cemetery_location')) {
            $query->where('cemetery_location', $request->cemetery_location);
        }

        if ($request->filled('prayer_time')) {
            $query->where('alert_time', ucfirst(strtolower($request->prayer_time)));
        }

        $alerts = $query->orderBy('alert_time')->get();
        $count = $alerts->count();

        return response()->json([
            'status'  => true,
            'message' => 'Visitor alerts fetched successfully',
            'count'   => $count,
            'data'    => $alerts,
        ]);
    }


    

    
    public function getMorticianStatuses()
    {
        $statuses = ['Active', 'off-duty', 'busy'];

        $morticians = Mortician::whereIn('status', $statuses)
            ->with('caseDetails')
            ->get();

        // Count per status
        $statusCounts = array_fill_keys($statuses, 0);
        foreach ($morticians as $mortician) {
            if (in_array($mortician->status, $statuses)) {
                $statusCounts[$mortician->status]++;
            }
        }

        // Prepare response
        $response = [
            'status_counts' => $statusCounts,
            'morticians' => $morticians
        ];

        return response()->json($response);
    }



    public function removeMortician(Request $request, $id)
    {
        $cemetry_case = CemeteryCase::find($id);

        if (!$cemetry_case) {
            return response()->json([
                'status' => false,
                'message' => 'Cemetery case not found.',
            ], 404);
        }

        $morticianId = $cemetry_case->mortician_id;

        if ($morticianId) {
            $mortician = Mortician::find($morticianId);

            if ($mortician) {
                $mortician->status = 'Active';
                $mortician->case_name = NULL;
                $mortician->save();
            }

            $cemetry_case->mortician_id = NULL;
            $cemetry_case->status = 'Pending';
            $cemetry_case->save();

            return response()->json([
                'status' => true,
                'message' => 'Mortician removed and set to Active successfully.',
                'data' => $cemetry_case,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'No mortician was assigned to this case.',
        ]);
    }


    // Morticiann
    public function getMorticians($id)
    {
        $mortician = Mortician::where('user_id', $id)
            // ->with('cemeteryCases.caseDetails','cemeteryCases.mancipalityRecord')
            ->with(['cemeteryCases' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }, 'cemeteryCases.caseDetails', 'cemeteryCases.mancipalityRecord'])
            ->first();
    
        if (!$mortician) {
            return response()->json(['message' => 'Mortician not found'], 404);
        }
    
        $cases = CemeteryCase::where('mortician_id', $mortician->id)->get();
    
        $counts = [
            'Pending' => 0,
            'Assigned' => 0,
            'Ghusl-in-Progress' => 0,
            'Completed' => 0,
        ];
    
        foreach ($cases as $case) {
            $status = $case->status;
    
            if (isset($counts[$status])) {
                $counts[$status]++;
            }
        }
    
        return response()->json([
            'case_counts' => $counts,
            // 'total_cases' => $cases->count(),
            'mortician' => $mortician,
        ]);
    }


    public function updateMorticianStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Active,off-duty'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mortician = Mortician::where('user_id',$id)->first();

        if (!$mortician) {
            return response()->json(['message' => 'Mortician not found'], 404);
        }

        $mortician->status = $request->status;
        $mortician->save();

        return response()->json([
            'message' => 'Mortician status updated successfully',
            'mortician' => $mortician
        ]);
    }

    public function updateMorticianCaseStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:ghusal-started,ghusal-completed'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cemetery_case = CemeteryCase::find($id);

        if (!$cemetery_case) {
            return response()->json(['message' => 'Cemetery Case not found'], 404);
        }

        $cemetery_case->status = $request->status;
        $cemetery_case->save();

        return response()->json([
            'message' => 'Cemetery case status updated successfully',
            'cemetery_case' => $cemetery_case
        ]);
    }

    public function fetchMorticianNotifications()
    {
        $notifications = BurzakhNotification::where('role', 'mortician')
            ->orderBy('id', 'desc')
            ->get();
    
        
            return response()->json([
                'message' => "Notifications fetched successfully.",
                'notifications' => $notifications,
            ], 200);
        
    }

  
    // Ambulancee
    // public function listAmbulanceDriverCases($id)
    // {
    //     $ambulance = Ambulance::where('user_id', $id)
    //         ->with('dispatchInfo.caseDetails') 
    //         ->first(); 

    //     if (!$ambulance) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Ambulance not found for this driver.',
    //         ], 404);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Cases fetched successfully.',
    //         'ambulance' => $ambulance,
    //     ], 200);
    // }
    public function listAmbulanceDriverCases($id)
    {
        $ambulance = Ambulance::where('user_id', $id)
            ->with(['dispatchInfo.caseDetails','dispatchInfo.municipalitySubmission']) 
            ->first();

        if (!$ambulance) {
            return response()->json([
                'success' => false,
                'message' => 'Ambulance not found for this driver.',
            ], 404);
        }

        $sortedDispatches = $ambulance->dispatchInfo->sortByDesc('created_at')->values();

        $count = $sortedDispatches->count();

        $dispatchWithPriority = $sortedDispatches->map(function ($item, $index) use ($count) {
            if ($index === 0) {
                $item->priority = 'High';
            } elseif ($index === $count - 1) {
                $item->priority = 'Low';
            } else {
                $item->priority = 'Medium';
            }
            return $item;
        });

        $ambulance->setRelation('dispatchInfo', $dispatchWithPriority);

        return response()->json([
            'success' => true,
            'message' => 'Cases fetched successfully.',
            'ambulance' => $ambulance,
        ], 200);
    }




    public function updateAmbulanceStatus(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,on-route,busy',
            'user_id' => 'required|string',
        ]);

        $updated = Ambulance::where('user_id', $validated['user_id'])->update([
            'status' => $validated['status']
        ]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Ambulance status updated successfully.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No matching ambulance found or status is already set.',
            ], 404);
        }
    }

    
    public function updateDispatchedCaseStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:arrived,picked-up,delivered',
        ]);
    
        $case = DispatchAmbulance::find($id);
    
        if (!$case) {
            return response()->json([
                'success' => false,
                'message' => 'Dispatched case not found.',
            ], 404);
        }
    
        $case->status = $validated['status'];
        $case->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Dispatched case status updated successfully.',
            'data' => $case,
        ], 200);
    }


    // Masterr Admin

    // public function loadMasterAdminView()
    // {
    //     $cases = BurzakhMemberDocumentSubmission::with('user')
    //             ->latest()
    //             ->take(50)
    //             ->get();
    //     $total_cases = BurzakhMemberDocumentSubmission::count();
    //     $active_cases = BurzakhMemberDocumentSubmission::where('ratio', '<', 1)->count();
    //     $pending_cases = BurzakhMemberDocumentSubmission::where('ratio', 0.2)->count();

    //     $target = 25;
    //     $completed_today = BurzakhMemberDocumentSubmission::where('ratio', 1)
    //         ->whereDate('updated_at', Carbon::today())->count();
    //     $progress_percent = $target > 0 ? round(($completed_today / $target) * 100) : 0;

    //     $currentMonthCases = BurzakhMemberDocumentSubmission::whereMonth('created_at', Carbon::now()->month)
    //         ->whereYear('created_at', Carbon::now()->year)->count();

    //     $lastMonthCases = BurzakhMemberDocumentSubmission::whereMonth('created_at', Carbon::now()->subMonth()->month)
    //         ->whereYear('created_at', Carbon::now()->subMonth()->year)->count();

    //     $monthly_change_percent = $lastMonthCases > 0
    //         ? round((($currentMonthCases - $lastMonthCases) / $lastMonthCases) * 100, 1)
    //         : ($currentMonthCases > 0 ? 100 : 0);

    //     $current = BurzakhMemberDocumentSubmission::where('ratio', '>=', 0.6)
    //         ->whereNotNull('updated_at')
    //         ->whereMonth('created_at', Carbon::now()->month)
    //         ->whereYear('created_at', Carbon::now()->year)
    //         ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_minutes'))
    //         ->first();

    //     $current_avg_minutes = $current->avg_minutes ?? 0;
    //     $avg_processing_hours = round($current_avg_minutes / 60, 1);

    //     $last = BurzakhMemberDocumentSubmission::where('ratio', '>=', 0.6)
    //         ->whereNotNull('updated_at')
    //         ->whereMonth('created_at', Carbon::now()->subMonth()->month)
    //         ->whereYear('created_at', Carbon::now()->subMonth()->year)
    //         ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_minutes'))
    //         ->first();

    //     $last_avg_minutes = $last->avg_minutes ?? 0;

    //     if ($last_avg_minutes > 0) {
    //         $speed_percent = round((($last_avg_minutes - $current_avg_minutes) / $last_avg_minutes) * 100, 1);
    //         $speed_trend = $speed_percent > 0 ? 'faster' : 'slower';
    //         $speed_color = $speed_percent > 0 ? 'text-success' : 'text-danger';
    //         $speed_arrow = $speed_percent > 0 ? 'up' : 'down';
    //     } else {
    //         $speed_percent = 0;
    //         $speed_trend = 'faster';
    //         $speed_color = 'text-success';
    //         $speed_arrow = 'up';
    //     }

    //     return view('admin-views.burzakh.frontend.index', compact(
    //         'total_cases',
    //         'active_cases',
    //         'pending_cases',
    //         'completed_today',
    //         'progress_percent',
    //         'monthly_change_percent',
    //         'avg_processing_hours',
    //         'speed_percent',
    //         'speed_trend',
    //         'speed_color',
    //         'speed_arrow',
    //         'cases'
    //     ));
    // }

    public function loadMasterAdminView()
    {
        $cases = BurzakhMemberDocumentSubmission::with('user')
            ->latest()
            ->take(50)
            ->get();

        $total_users = BurzakhMember::count();
        $total_cases = BurzakhMemberDocumentSubmission::count();
        $active_cases = BurzakhMemberDocumentSubmission::where('ratio', '<', 1)->count();
        $pending_cases = BurzakhMemberDocumentSubmission::where('ratio', 0.2)->count();

        $target = 25;
        $completed_today = BurzakhMemberDocumentSubmission::where('ratio', 1)
            ->whereDate('updated_at', Carbon::today())->count();

        $progress_percent = $target > 0 ? round(($completed_today / $target) * 100) : 0;

        $currentMonthCases = BurzakhMemberDocumentSubmission::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)->count();

        $lastMonthCases = BurzakhMemberDocumentSubmission::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)->count();

        $monthly_change_percent = $lastMonthCases > 0
            ? round((($currentMonthCases - $lastMonthCases) / $lastMonthCases) * 100, 1)
            : ($currentMonthCases > 0 ? 100 : 0);

        $current = BurzakhMemberDocumentSubmission::where('ratio', '>=', 0.6)
            ->whereNotNull('updated_at')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_minutes'))
            ->first();

        $current_avg_minutes = $current->avg_minutes ?? 0;
        $avg_processing_hours = round($current_avg_minutes / 60, 1);

        $last = BurzakhMemberDocumentSubmission::where('ratio', '>=', 0.6)
            ->whereNotNull('updated_at')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_minutes'))
            ->first();

        $last_avg_minutes = $last->avg_minutes ?? 0;

        if ($last_avg_minutes > 0) {
            $speed_percent = round((($last_avg_minutes - $current_avg_minutes) / $last_avg_minutes) * 100, 1);
            $speed_trend = $speed_percent > 0 ? 'faster' : 'slower';
            $speed_color = $speed_percent > 0 ? 'text-success' : 'text-danger';
            $speed_arrow = $speed_percent > 0 ? 'up' : 'down';
        } else {
            $speed_percent = 0;
            $speed_trend = 'faster';
            $speed_color = 'text-success';
            $speed_arrow = 'up';
        }

        $critical_alerts = [];

        foreach ($cases as $case) {
            $diffHours = Carbon::parse($case->updated_at)->diffInHours($case->created_at);
            if ($diffHours >= 3) {
                if ($case->ratio == 0.2) {
                    $critical_alerts[] = "Police has not responded for 3+ hours for case ID BUR-2025-{$case->id}";
                } elseif ($case->ratio == 0.4) {
                    $critical_alerts[] = "Municipality has not responded for 3+ hours for case ID BUR-2025-{$case->id}";
                } elseif ($case->ratio == 0.6) {
                    $critical_alerts[] = "Cemetery has not responded for 3+ hours for case ID BUR-2025-{$case->id}";
                }
            }
        }

        $monthlyStats = BurzakhMemberDocumentSubmission::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::create()->month($item->month)->format('M') => $item->total];
            });

        $resolvedStats = BurzakhMemberDocumentSubmission::selectRaw('MONTH(updated_at) as month, COUNT(*) as total')
            ->where('ratio', 1)
            ->whereYear('updated_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::create()->month($item->month)->format('M') => $item->total];
            });

        // Normalize data for all 12 months
        $allMonths = collect(range(1, 12))->map(function ($m) {
            return Carbon::create()->month($m)->format('M');
        });

        $chartLabels = $allMonths->toArray();
        $casesData = $allMonths->map(fn($m) => $monthlyStats[$m] ?? 0)->toArray();
        $resolvedData = $allMonths->map(fn($m) => $resolvedStats[$m] ?? 0)->toArray();

        // To load recent user activity at administration section 
        $adminTypes = ['police', 'rta', 'cda', 'mancipality', 'mortician', 'ambulance'];

        $latestLogins = [];

        foreach ($adminTypes as $type) {
            $latest = BurzakhMember::where('admin_type', $type)
                ->orderBy('updated_at', 'desc')
                ->first();

            if ($latest) {
                $latestLogins[] = [
                    'name'       => $latest->first_name . ' ' . $latest->last_name ?? 'N/A',
                    'type'       => ucfirst($type),
                    'login_time' => $latest->updated_at ? Carbon::parse($latest->updated_at)->diffForHumans() : 'N/A',
                ];
            }
        }


        // Fetch all user to shwo at manage users section
        $allUsers = BurzakhMember::orderBy('updated_at', 'desc')->get();

        return view('admin-views.burzakh.frontend.index', compact(
            'total_cases',
            'active_cases',
            'pending_cases',
            'completed_today',
            'progress_percent',
            'monthly_change_percent',
            'avg_processing_hours',
            'speed_percent',
            'speed_trend',
            'speed_color',
            'speed_arrow',
            'cases',
            'critical_alerts',
            'total_users',
            'chartLabels',
            'casesData',
            'resolvedData',
            'latestLogins',
            'allUsers'
        ));
    }


    public function userDocuemntSubmissionFromMaster(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'death_notification_file'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'hospital_certificate'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'passport_or_emirate_id_front'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'passport_or_emirate_id_back'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'user_id'                       => 'required',
            'resting_place'                 => 'required',
            'name_of_deceased'              => 'required',
            'date_of_death'                 => 'required',
            'location'                      => 'required',
            // 'gender'                      => 'required',
            // 'age'                      => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Store files
        $deathNotificationFilePath  = $request->file('death_notification_file')->store('documents/death_notification_files', 'public');
        $hospitalCertificatePath    = $request->file('hospital_certificate')->store('documents/hospital_certificates', 'public');
        $passportOrEmirateIdFront   = $request->file('passport_or_emirate_id_front')->store('documents/passport_or_emirate_ids_front', 'public');
        $passportOrEmirateIdBack    = $request->file('passport_or_emirate_id_back')->store('documents/passport_or_emirate_ids_back', 'public');

        // Save to DB
        $submission = BurzakhMemberDocumentSubmission::create([
            'death_notification_file'      => $this->base_url . "/storage/app/public/" . $deathNotificationFilePath,
            'hospital_certificate'         => $this->base_url . "/storage/app/public/" . $hospitalCertificatePath,
            'passport_or_emirate_id_front' => $this->base_url . "/storage/app/public/" . $passportOrEmirateIdFront,
            'passport_or_emirate_id_back'  => $this->base_url . "/storage/app/public/" . $passportOrEmirateIdBack,
            'user_id'                      => $request->user_id,
            'resting_place'                => $request->resting_place,
            'name_of_deceased'             => $request->name_of_deceased,
            'date_of_death'                => $request->date_of_death,
            'location'                     => $request->location,
            'ratio'                        => 0.2,
            // 'gender'                       => $request->gender,
            // 'age'                          => $request->age,
        ]);

        // Log activity
        RecentActivity::create([
            'user_id'       => $request->user_id,
            'activity_name' => "Document Submission",
            'status'        => "Pending Approval"
        ]);

        return redirect()->back()->with('success', 'Submission successful');
    }



    public function storeAlert(Request $request)
    {
        $request->validate([
            'notification_message' => 'required|string|max:1000',
        ]);

        BurzakhNotification::create([
            'notification_message' => $request->notification_message,
            'role'                 => 'user',
            'user_id'              => 'all',
        ]);

        return response()->json(['success' => true]);
    }


    public function loadDepartmentsData()
    {
        $departments = [
            [
                'role' => 'police',
                'name' => 'Police',
                'nameAr' => 'الشرطة',
                'color' => 'bg-primary',
                'icon' => 'fas fa-shield-alt',
            ],
            [
                'role' => 'cda',
                'name' => 'CDA',
                'nameAr' => 'هيئة تنمية العاصمة',
                'color' => 'bg-success',
                'icon' => 'fas fa-building',
            ],
            [
                'role' => 'rta',
                'name' => 'RTA',
                'nameAr' => 'هيئة الطرق والمواصلات',
                'color' => 'bg-warning',
                'icon' => 'fas fa-bus',
            ],
            [
                'role' => 'cemetery',
                'name' => 'Cemetery',
                'nameAr' => 'المقبرة',
                'color' => 'bg-danger',
                'icon' => 'fas fa-cross',
            ],
        ];

        foreach ($departments as &$dept) {
            $members = BurzakhMember::where('admin_type', $dept['role'])->get();
            $activeUsers = $members->where('admin_type', $dept['role'])->count();
            $totalUsers = $members->count();

            $userIds = $members->pluck('id')->toArray();

            $casesProcessed = 0;

            switch ($dept['role']) {
                case 'police':
                    $casesProcessed = BurzakhMemberDocumentSubmission::where('ratio', '>', 0.2)
                        ->count();
                    break;

                case 'rta':
                    $casesProcessed = BurzakhMemberDocumentSubmission::where('ratio', '>', 0.6)
                        ->count();
                    break;

                case 'cda':
                    $casesProcessed = BurzakhMemberDocumentSubmission::where('ratio', '>', 0.4)
                        ->count();
                    break;

                case 'cemetery':
                    $casesProcessed = BurzakhMemberDocumentSubmission::where('ratio', '>', 0.8)
                        ->count();
                    break;

                default:
                    0;
                    break;
            }

            $dept['activeUsers'] = $activeUsers;
            $dept['totalUsers'] = $totalUsers;
            $dept['casesProcessed'] = $casesProcessed;
        }

        return response()->json($departments);
    }


    public function addOfficer(Request $request)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'phone_number' => 'required|string',
            'password'     => 'required|string|min:6',
            'role'         => 'required|in:police,rta,cda,cemetery',
        ]);

        BurzakhMember::create([
            'first_name'   => $validated['first_name'],
            'last_name'    => $validated['last_name'],
            'email'        => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password'     => Hash::make($validated['password']),
            'admin_type'   => $validated['role'],
        ]);

        return response()->json(['success' => true]);
    }

}
