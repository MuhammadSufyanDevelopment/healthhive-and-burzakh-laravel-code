<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\DeliveryCountryCode;
use App\Models\DeliveryZipCode;
use App\Models\GuestUser;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Review;
use App\Models\ShippingAddress;
use App\Models\SupportTicket;
use App\Models\SupportTicketConv;
use App\Models\Wishlist;
use App\Traits\CommonTrait;
use App\Traits\PdfGenerator;
use App\Traits\FileManagerTrait;
use App\Models\User;
use App\Utils\CustomerManager;
use App\Utils\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\HealthCareJob;
use App\Models\Message;
use App\Models\Application;
use App\Models\JobPosting;
use App\Models\VendorJob;
use App\Models\Product;
use App\Http\Requests\ProductAddRequest;
use App\Services\ProductService;
use Carbon\Carbon;
use App\Models\StaffId;

class CustomerController extends Controller
{
    use CommonTrait, PdfGenerator, FileManagerTrait;

    public function info(Request $request)
    {
        $user = $request->user();
        $getUser = User::where(['id' => $user->id])->first();
        $referralUserCount = User::where('referred_by', $user->id)->count();
        $user->referral_user_count = $referralUserCount;
        $user->orders_count = User::withCount('orders')->find($user->id)->orders_count;
        $user->is_phone_verified = $getUser->is_phone_verified;
        $user->email_verification_token = $getUser->email_verification_token;
        $user->email_verified_at = $getUser->email_verified_at;
        return response()->json($user, 200);
    }

    public function create_support_ticket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'type' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $images = [];
        if ($request->file('image')) {
            foreach ($request->image as $key => $value) {
                $image_name = $this->upload('support-ticket/', 'webp', $value);
                $images[] = [
                    'file_name' => $image_name,
                    'storage' => getWebConfig(name: 'storage_connection_type') ?? 'public',
                ];
            }
        }

        $request['customer_id'] = $request->user()->id;
        $request['status'] = 'pending';
        $request['attachment'] = $images;

        try {
            CustomerManager::create_support_ticket($request);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => [
                    'code' => 'failed',
                    'message' => 'Something went wrong',
                ],
            ], 422);
        }
        return response()->json(['message' => 'Support ticket created successfully.'], 200);
    }

    public function account_delete(Request $request, $id)
    {
        if ($request->user()->id == $id) {
            $user = User::find($id);

            $ongoing = ['out_for_delivery', 'processing', 'confirmed', 'pending'];
            $order = Order::where('customer_id', $user->id)->where('is_guest', 0)->whereIn('order_status', $ongoing)->count();
            if ($order > 0) {
                return response()->json(['message' => 'You can`t delete account due ongoing_order!!'], 403);
            }

            $this->delete('/profile/' . $user['image']);

            $user->delete();
            return response()->json(['message' => 'Your account deleted successfully'], 200);

        } else {
            return response()->json(['message' => 'access_denied!!'], 403);
        }
    }

    public function reply_support_ticket(Request $request, $ticket_id)
    {
        DB::table('support_tickets')->where(['id' => $ticket_id])->update([
            'status' => 'open',
            'updated_at' => now(),
        ]);

        $images = [];
        if ($request->file('image')) {
            foreach ($request->image as $key => $value) {
                $image_name = $this->upload('support-ticket/', 'webp', $value);
                $images[] = [
                    'file_name' => $image_name,
                    'storage' => getWebConfig(name: 'storage_connection_type') ?? 'public',
                ];
            }
        }

        $support = new SupportTicketConv();
        $support->support_ticket_id = $ticket_id;
        $support->attachment = $images;
        $support->admin_id = 0;
        $support->customer_message = $request['message'];
        $support->save();
        return response()->json(['message' => 'Support ticket reply sent.'], 200);
    }

    public function get_support_tickets(Request $request)
    {
        return response()->json(SupportTicket::where('customer_id', $request->user()->id)->latest()->get(), 200);
    }

    public function get_support_ticket_conv($ticket_id)
    {
        $conversations = SupportTicketConv::where('support_ticket_id', $ticket_id)->get();
        $support_ticket = SupportTicket::find($ticket_id);

        $conversations = $conversations->toArray();

        if ($support_ticket) {
            $description = array(
                'support_ticket_id' => $ticket_id,
                'admin_id' => null,
                'customer_message' => $support_ticket->description,
                'admin_message' => null,
                'attachment' => $support_ticket->attachment,
                'attachment_full_url' => $support_ticket->attachment_full_url,
                'position' => 0,
                'created_at' => $support_ticket->created_at,
                'updated_at' => $support_ticket->updated_at,
            );
            array_unshift($conversations, $description);
        }
        return response()->json($conversations, 200);
    }

    public function support_ticket_close($id)
    {
        $ticket = SupportTicket::find($id);
        if ($ticket) {
            $ticket->status = 'close';
            $ticket->updated_at = now();
            $ticket->save();
            return response()->json(['message' => 'Successfully close the ticket'], 200);
        }
        return response()->json(['message' => 'Ticket not found'], 403);
    }

    public function add_to_wishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $wishlist = Wishlist::where('customer_id', $request->user()->id)->where('product_id', $request->product_id)->first();

        if (empty($wishlist)) {
            $wishlist = new Wishlist;
            $wishlist->customer_id = $request->user()->id;
            $wishlist->product_id = $request->product_id;
            $wishlist->save();
            return response()->json(['message' => 'successfully added!'], 200);
        }

        return response()->json(['message' => 'Already in your wishlist'], 409);
    }

    public function remove_from_wishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $wishlist = Wishlist::where('customer_id', $request->user()->id)->where('product_id', $request->product_id)->first();

        if (!empty($wishlist)) {
            Wishlist::where(['customer_id' => $request->user()->id, 'product_id' => $request->product_id])->delete();
            return response()->json(['message' => translate('successfully removed!')], 200);

        }
        return response()->json(['message' => translate('No such data found!')], 404);
    }

    public function wish_list(Request $request)
    {

        $wishlist = Wishlist::whereHas('wishlistProduct', function ($q) {
            return $q;
        })->with(['productFullInfo'])->where('customer_id', $request->user()->id)->get();

        $wishlist->map(function ($data) {
            $data['productFullInfo'] = Helpers::product_data_formatting(json_decode($data['productFullInfo'], true));
            return $data;
        });

        return response()->json($wishlist, 200);
    }

    public function address_list(Request $request): JsonResponse
    {
        $user = Helpers::getCustomerInformation($request);
        if ($user == 'offline') {
            $data = ShippingAddress::where(['customer_id' => $request->guest_id, 'is_guest' => 1])->get();
        } else {
            $data = ShippingAddress::where(['customer_id' => $user->id, 'is_guest' => '0'])->get();
        }
        return response()->json($data, 200);
    }

    public function add_new_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'is_billing' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $zip_restrict_status = getWebConfig(name: 'delivery_zip_code_area_restriction');
        $country_restrict_status = getWebConfig(name: 'delivery_country_restriction');

        if ($country_restrict_status && !self::delivery_country_exist_check($request->input('country'))) {
            return response()->json(['message' => translate('Delivery_unavailable_for_this_country')], 403);

        } elseif ($zip_restrict_status && !self::delivery_zipcode_exist_check($request->input('zip'))) {
            return response()->json(['message' => translate('Delivery_unavailable_for_this_zip_code_area')], 403);
        }

        $user = Helpers::getCustomerInformation($request);

        $address = [
            'customer_id' => $user == 'offline' ? $request->guest_id : $user->id,
            'is_guest' => $user == 'offline' ? 1 : 0,
            'contact_person_name' => $request->contact_person_name,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'country' => $request->country,
            'phone' => $request->phone,
            'email' => $request->email,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_billing' => $request->is_billing,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        ShippingAddress::insert($address);
        return response()->json(['message' => translate('successfully added!')], 200);
    }

    public function update_address(Request $request): JsonResponse
    {
        $user = Helpers::getCustomerInformation($request);

        $shippingAddress = ShippingAddress::where([
            'customer_id' => $user == 'offline' ? $request->guest_id : $user->id,
            'id' => $request->id
        ])->first();

        if (!$shippingAddress) {
            return response()->json(['message' => translate('not_found')], 200);
        }

        $zipRestrictStatus = getWebConfig(name: 'delivery_zip_code_area_restriction');
        $countryRestrictStatus = getWebConfig(name: 'delivery_country_restriction');

        if ($countryRestrictStatus && !self::delivery_country_exist_check($request->input('country'))) {
            return response()->json(['error_type' => 'address', 'message' => translate('Delivery_unavailable_for_this_country')], 403);
        } elseif ($zipRestrictStatus && !self::delivery_zipcode_exist_check($request->input('zip'))) {
            return response()->json(['error_type' => 'zip_code', 'message' => translate('Delivery_unavailable_for_this_zip_code_area')], 403);
        }

        $shippingAddress->update([
            'customer_id' => $user == 'offline' ? $request->guest_id : $user->id,
            'is_guest' => $user == 'offline' ? 1 : 0,
            'contact_person_name' => $request['contact_person_name'],
            'address_type' => $request['address_type'],
            'address' => $request['address'],
            'city' => $request['city'],
            'zip' => $request['zip'],
            'country' => $request['country'],
            'phone' => $request['phone'],
            'latitude' => $request['latitude'],
            'longitude' => $request['longitude'],
            'is_billing' => $request['is_billing'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => translate('update_successful')], 200);
    }

    public function delete_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $user = Helpers::getCustomerInformation($request);

        $shipping_address = ShippingAddress::where(['id' => $request['address_id']])
            ->when($user == 'offline', function ($query) use ($request) {
                $query->where(['customer_id' => $request->guest_id, 'is_guest' => 1]);
            })
            ->when($user != 'offline', function ($query) use ($user) {
                $query->where(['customer_id' => $user->id, 'is_guest' => '0']);
            })->first();

        if ($shipping_address && $shipping_address->delete()) {
            return response()->json(['message' => 'successfully removed!'], 200);
        }
        return response()->json(['message' => translate('No such data found!')], 404);
    }

    public function get_order_list(Request $request)
    {
        $status = array(
            'ongoing' => ['out_for_delivery', 'processing', 'confirmed', 'pending'],
            'canceled' => ['canceled', 'failed', 'returned'],
            'delivered' => ['delivered'],
        );

        $orders = Order::with('details.product', 'deliveryMan', 'seller.shop')
            ->withSum('details as order_details_count', 'qty')
            ->where(['customer_id' => $request->user()->id, 'is_guest' => '0'])
            ->when($request->status && $request->status != 'all', function ($query) use ($request, $status) {
                $query->whereIn('order_status', $status[$request->status])
                    ->when($request->type == 'reorder', function ($query) use ($request) {
                        $query->where('order_type', 'default_type');
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $orders->map(function ($data) {
            $data->details->map(function ($query) {
                $query['product'] = Helpers::product_data_formatting(json_decode($query['product'], true));
                return $query;
            });

            return $data;
        });

        $orders = [
            'total_size' => $orders->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders->items()
        ];
        return response()->json($orders, 200);
    }

    public function get_order_details(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $user = Helpers::getCustomerInformation($request);

        $detailsList = OrderDetail::with('productAllStatus', 'order.deliveryMan', 'verificationImages', 'seller.shop')
            ->whereHas('order', function ($query) use ($request, $user) {
                $query->where([
                    'customer_id' => $user == 'offline' ? $request->guest_id : $user->id,
                    'is_guest' => $user == 'offline' ? 1 : '0'
                ])->orWhere([
                    'seller_id' => $user == 'offline' ? $request->guest_id : $user->id,
                    'is_guest' => $user == 'offline' ? 1 : '0'
                ]);
            })
            ->where(['order_id' => $request['order_id']])
            ->get();

        $detailsList->map(function ($query) use($user) {
            $query['variation'] = json_decode($query['variation'], true);
            $product = json_decode($query['product_details'], true);
            $product['thumbnail_full_url'] = $query?->productAllStatus?->thumbnail_full_url;
            if ($product['product_type'] == 'digital' && $product['digital_product_type'] == 'ready_product' && $product['digital_file_ready']) {
                $checkFilePath = storageLink('product/digital-product', $product['digital_file_ready'], ($product['storage_path'] ?? 'public'));
                $product['digital_file_ready_full_url'] = $checkFilePath;
            }
            $query['product_details'] = Helpers::product_data_formatting_for_json_data($product);

            $reviews = Review::where(['product_id' => $query['product_id'], 'customer_id' => $user->id])->whereNull('delivery_man_id')->get();
            $reviewData = null;
            foreach ($reviews as $review) {
                if ($review->order_id == $query['order_id']) {
                    $reviewData = $review;
                }
            }

            if (isset($reviews[0]) && is_null($reviewData)) {
                $reviewData = ($reviews[0]['order_id'] == null ? $reviews[0] : null);
            }
            $query['reviewData'] = $reviewData;
            return $query;
        });
        return response()->json($detailsList, 200);
    }

    public function getOrderInvoice(Request $request)
    {
        $order = Order::with('seller')->with('shipping')->where('id', $request['order_id'])->first();
        $data["email"] = $order->customer["email"];
        $data["order"] = $order;
        $invoiceSettings = json_decode(BusinessSetting::where(['type' => 'invoice_settings'])->first()?->value, true);
        $mpdf_view = \View::make(VIEW_FILE_NAMES['order_invoice'], compact('order', 'invoiceSettings'));
        $mpdf = new \Mpdf\Mpdf(['default_font' => 'FreeSerif', 'mode' => 'utf-8', 'format' => [190, 250], 'autoLangToFont' => true]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        if ($pdfType = 'invoice') {
            $footerHtml = $this->footerHtml(requestFrom: 'web');
            $mpdf->SetHTMLFooter($footerHtml);
        }
        $mpdf_view = $mpdf_view->render();
        $mpdf->WriteHTML($mpdf_view);
        $pdfContentStr = $mpdf->Output('', 'S');
        $pdfContentBytes = $pdfContentStr;
        $byteArray = array_values(unpack('C*', $pdfContentBytes));
        return response()->json($byteArray);
    }

    public function get_order_by_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $order = Order::withCount('orderDetails')->with(['deliveryMan', 'offlinePayments', 'verificationImages'])->where(['id' => $request['order_id']])->first();
        if (isset($order['offlinePayments'])) {
            $order['offlinePayments']->payment_info = $order->offlinePayments->payment_info;
        }
        return response()->json($order, 200);
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'trade_license_number' => 'nullable',
            'national_id_number' => 'nullable',
        ], [
            'f_name.required' => translate('First name is required!'),
            'l_name.required' => translate('Last name is required!'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $checkEmail = User::where('email', $request['email'])->where('id', '!=', $request->user()->id)->first();
        if ($checkEmail) {
            return response()->json([
                'errors' => [
                    ['code' => 'invalid_email', 'message' => translate('Email_already_exists')]
                ]
            ], 403);
        }

        $checkPhone = User::where('phone', $request['phone'])->where('id', '!=', $request->user()->id)->first();
        if ($checkPhone) {
            return response()->json([
                'errors' => [
                    ['code' => 'invalid_phone', 'message' => translate('Phone_already_exists')]
                ]
            ], 403);
        }

        if ($request->has('image')) {
            $imageName = $this->update('profile/', $request->user()->image, 'webp', $request->file('image'));
        } else {
            $imageName = $request->user()->image;
        }

        if ($request['password'] != null && strlen($request['password']) > 5) {
            $pass = bcrypt($request['password']);
        } else {
            $pass = $request->user()->password;
        }

        $user = User::where(['id' => $request->user()->id])->first();
        $userDetails = [
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'name' => $request->name,
            'trade_license_number' => $request->trade_license_number,
            'national_id_number' => $request->national_id_number,
            'image' => $imageName,
            'phone' => $user['is_phone_verified'] ? $user['phone'] : $request['phone'],
            'email' => $request['email'],
            'is_phone_verified' => $request['phone'] == $user['phone'] ? $user['is_phone_verified'] : 0,
            'is_email_verified' => $request['email'] == $user['email'] ? $user['is_email_verified'] : 0,
            'email_verified_at' => $request['email'] == $user['email'] ? $user['email_verified_at'] : null,
            'password' => $pass,
            'updated_at' => now(),
        ];

        User::where(['id' => $request->user()->id])->update($userDetails);
        return response()->json(['message' => translate('successfully updated!')], 200);
    }

    public function update_cm_firebase_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $user = Helpers::getCustomerInformation($request);

        if ($user == 'offline') {
            $guest = GuestUser::find($request->guest_id);
            $guest->fcm_token = $request['cm_firebase_token'];
            $guest->save();
        } else {
            DB::table('users')->where('id', $user->id)->update([
                'cm_firebase_token' => $request['cm_firebase_token'],
            ]);
        }

        return response()->json(['message' => translate('successfully updated!')], 200);
    }

    public function get_restricted_country_list(Request $request)
    {
        $stored_countries = DeliveryCountryCode::orderBy('country_code', 'ASC')->pluck('country_code')->toArray();
        $country_list = COUNTRIES;

        $countries = array();

        foreach ($country_list as $country) {
            if (in_array($country['code'], $stored_countries)) {
                $countries [] = $country['name'];
            }
        }

        if ($request->search) {
            $countries = array_values(preg_grep('~' . $request->search . '~i', $countries));
        }

        return response()->json($countries, 200);
    }

    public function get_restricted_zip_list(Request $request)
    {
        $zipcodes = DeliveryZipCode::orderBy('zipcode', 'ASC')
            ->when($request->search, function ($query) use ($request) {
                $query->where('zipcode', 'like', "%{$request->search}%");
            })
            ->get();

        return response()->json($zipcodes, 200);
    }

    public function language_change(Request $request)
    {
        $user = $request->user();
        $user->app_language = $request->current_language;
        $user->save();

        return response()->json(['message' => 'Successfully change'], 200);
    }

    public function healthCareJobList(){
        $healthCareJobList = HealthCareJob::whereIn('job_type',['healthcare-worker', 'student','part time','Hands on Training','Workshops','Lectures'])
        ->orderBy('id','desc')
        ->get();

        if($healthCareJobList->isNotEmpty()){
            return response()->json([
                'message'=>'Jobs Fetched Successfully',
                'jobs'=>$healthCareJobList
            ]);
        }
        else{
            return response()->json([
                'message'=>'No Jobs found'
            ]);
        }
    }

    public function messageRecruiter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'recruiter_id' => 'required',
            'healthcare_worker_id' => 'nullable|integer',
            'student_id' => 'nullable|integer',
        ], [
            'healthcare_worker_id.required_without' => 'Either healthcare worker ID or student ID is required.',
            'student_id.required_without' => 'Either student ID or healthcare worker ID is required.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (!$request->healthcare_worker_id && !$request->student_id) {
                $validator->errors()->add('healthcare_worker_id', 'Either healthcare worker ID or student ID must be provided.');
                $validator->errors()->add('student_id', 'Either student ID or healthcare worker ID must be provided.');
            }
            if ($request->healthcare_worker_id && $request->student_id) {
                $validator->errors()->add('healthcare_worker_id', 'Only one of healthcare worker ID or student ID should be provided.');
                $validator->errors()->add('student_id', 'Only one of student ID or healthcare worker ID should be provided.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $recruiterId = $request->recruiter_id;
        $findRecruiter = User::find($recruiterId);

        if (!$findRecruiter) {
            return response()->json(['errors' => 'Recruiter not found'], 403);
        }

        $sendMessage = Message::create([
            'title' => $request->title,
            'description' => $request->description,
            'healthcare_worker_id' => $request->healthcare_worker_id,
            'student_id' => $request->student_id,
            'recruiter_id' => $recruiterId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($sendMessage) {
            return response()->json(['success' => 'Message sent successfully!']);
        } else {
            return response()->json(['error' => 'Error sending message!']);
        }
    }

    public function singleHealthCareJob($id)
    {
        $healthCareJob = HealthCareJob::where('id', $id)->first();

        if ($healthCareJob) {
            return response()->json([
                'message' => 'Job Fetched Successfully',
                'job' => $healthCareJob
            ]);
        } else {
            return response()->json([
                'message' => 'Job not found'
            ]);
        }
    }


    public function ApplyToJob(Request $request){
        $validator = Validator::make($request->all(), [
            'job_id' => 'required',
            'full_name' => 'required',
            'phone_number' => 'required',
            'email_address' => 'required',
            'university' => 'required',
            'student_id' => 'nullable',
            'resume' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg|max:10240', 
            'experience' => 'nullable',
            'graduation_date' => 'nullable',
            'healthcare_worker_id' => 'nullable',
            'recruiter_id' => 'required',
            'cover_letter' => 'nullable',
        ], [
            'required_without' => 'Either :attribute or :other is required.'
        ]);
        $validator->after(function ($validator) use ($request) {
            if (empty($request->student_id) && empty($request->healthcare_worker_id)) {
                $validator->errors()->add('student_id', 'Either student_id or healthcare_worker_id is required.');
                $validator->errors()->add('healthcare_worker_id', 'Either healthcare_worker_id or student_id is required.');
            }
        });
    
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }
    
        $healthcareWorkerId = $request->healthcare_worker_id;

    if ($healthcareWorkerId) {
        $findHealthcareWorker = User::where('id', $healthcareWorkerId)->where('is_health_care_worker', 1)->first();
        if (!$findHealthcareWorker) {
            return response()->json(['errors' => 'HealthCare worker not found'], 403);
        }
    }

    $studentId = $request->student_id;

    if ($studentId) {
        $findStudent = User::where('id', $studentId)->where('is_student', 1)->first();
        if (!$findStudent) {
            return response()->json(['errors' => 'Student not found'], 403);
        }
    }

        $jobId = $request->job_id;
    
        $findjobId = HealthCareJob::find($jobId);
        if (!$findjobId) {
            return response()->json(['errors' => 'Job not found'], 403);
        }
    
        $existingApplication = Application::where('job_id', $request->job_id)
                                          ->where('healthcare_worker_id', $request->healthcare_worker_id)
                                          ->first();
    
        if ($existingApplication) {
            return response()->json(['error' => 'You have already applied for this job.'], 403);
        }
    
        $resumeUrl = null;
        if ($request->hasFile('resume') && $request->file('resume')->isValid()) {
            $fileName = time() . '-' . $request->file('resume')->getClientOriginalName();
            $path = $request->file('resume')->move(public_path('resumes'), $fileName);
            $resumeUrl = url('public/resumes/' . $fileName);
        } else {
            return response()->json(['error' => 'Invalid resume file'], 400);
        }
    
        $sendMessage = Application::create([
            'job_id' => $jobId,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'email_address' => $request->email_address,
            'university' => $request->university,
            'student_id' => $request->student_id,
            'resume' => $resumeUrl,
            'experience' => $request->experience,
            'cover_letter' => $request->cover_letter,
            'graduation_date' => $request->graduation_date,
            'healthcare_worker_id' => $request->healthcare_worker_id,
            'recruiter_id' => $request->recruiter_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        if ($sendMessage) {
            return response()->json(['success' => 'Application sent successfully!']);
        } else {
            return response()->json(['error' => 'Error sending Application!']);
        }
    }




    public function jobPosting(Request $request){
        $validator = Validator::make($request->all(), [
            'job_title' => 'required',
            'brand' => 'required',
            'job_type' => 'required',
            'job_description' => 'required',
            'recruiter_id' => 'required',
            'experience' => 'nullable',
            'graduation_year' => 'nullable',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }
    
        $recruiterId = $request->recruiter_id;
    
        $findRecruiter = User::find($recruiterId);
        if (!$findRecruiter) {
            return response()->json(['errors' => 'Recruiter not found'], 403);
        }
    
        $sendMessage = HealthCareJob::create([
            'job_title' => $request->job_title,
            'brand' => $request->brand,
            'job_type' => $request->job_type,
            'job_description' => $request->job_description,
            'recruiter_id' => $recruiterId,
            'experience' => $request->experience,
            'graduation_year' => $request->graduation_year,
            'status' => "all",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    
        if ($sendMessage) {
            return response()->json(['success' => 'Job posted successfully!']);
        } else {
            return response()->json(['error' => 'Error posting Job!']);
        }
    }



    public function jobListing(Request $request){
        $validator = Validator::make($request->all(), [
            'recruiter_id' => 'required|exists:users,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }
        $recruiterId = $request->recruiter_id;
        $recruiterJobs = HealthCareJob::where('recruiter_id', $recruiterId)->orderBy('id','desc')->get();
        if($recruiterJobs){
            return response()->json([
                'success' => true,
                'data' => $recruiterJobs
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'No Jobs found'
            ], 404);
        }
    }



//     public function CreateProductVendor(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'name' => 'required|string|max:255',
//             'slug' => 'required|string|unique:products,slug',
//             'category_ids' => 'required|array',
//             'category_ids.*.id' => 'required|exists:categories,id',
//             'category_ids.*.position' => 'required|integer',
//             'category_id' => 'required|exists:categories,id',
//             'sub_category_id' => 'nullable|exists:categories,id',
//             'brand_id' => 'nullable|exists:brands,id',
//             'unit' => 'required|string|max:50',
//             'min_qty' => 'required|integer|min:1',
//             'images' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//             'color_image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//             'thumbnail' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
//             'unit_price' => 'required|numeric',
//             'tax' => 'nullable|numeric',
//             'tax_type' => 'nullable|string',
//             'tax_model' => 'nullable|string',
//             'discount' => 'nullable|numeric',
//             'current_stock' => 'required|integer|min:0',
//             'details' => 'required|string',
//             'status' => 'required',
//             'meta_title' => 'nullable|string|max:255',
//             'meta_description' => 'nullable|string',
//             'meta_image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//             'request_status' => 'nullable|string',
//             'shipping_cost' => 'nullable|numeric',
//             'code' => 'required|string|unique:products,code',
//             'status' => 'required',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
//         }

//         $imagePaths = $this->handleImageUploads($request);

//         $product = Product::create([

// // 'product_type' => 'required|string',
//             // 'sub_sub_category_id' => 'nullable|exists:categories,id',
//             // 'refundable' => 'required|boolean',
//             // 'digital_product_type' => 'nullable|string',
//             // 'digital_file_ready' => 'nullable|boolean',
//             // 'digital_file_ready_storage_type' => 'nullable|string',
//             // 'preview_file' => 'nullable|file|mimes:pdf,mp4|max:2048',
//             // 'featured' => 'nullable|boolean',
//             // 'flash_deal' => 'nullable|boolean',
//             // 'video_provider' => 'nullable|string',
//             // 'video_url' => 'nullable|string|url',
//             // 'colors' => 'nullable|array',
//             // 'variant_product' => 'nullable|boolean',
//             // 'attributes' => 'nullable|array',
//             // 'choice_options' => 'nullable|array',
//             // 'variation' => 'nullable|array',
//             // 'digital_product_file_types' => 'nullable|array',
//             // 'digital_product_extensions' => 'nullable|array',
//             // 'published' => 'nullable|boolean',
//             // 'purchase_price' => 'required|numeric',
//             // 'discount_type' => 'nullable|string',
//             // 'minimum_order_qty' => 'required|integer|min:1',
//             // 'free_shipping' => 'nullable|boolean',
//             // 'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,pdf|max:2048',
//             // 'featured_status' => 'nullable|string',
//             // 'denied_note' => 'nullable|string',
//             // 'multiply_qty' => 'nullable|integer',
//             // 'temp_shipping_cost' => 'nullable|numeric',
//             // 'is_shipping_cost_updated' => 'nullable|boolean',


//             'added_by' => 'admin',
//             'user_id' => $request->vendor_id,
//             'name' => $request->name,
//             'slug' => $request->slug,
//             // 'product_type' => $request->product_type,
//             'category_ids' => json_encode($request->category_ids),
//             'category_id' => $request->category_id,
//             'sub_category_id' => $request->sub_category_id,
//             // 'sub_sub_category_id' => $request->sub_sub_category_id,
//             'brand_id' => $request->brand_id,
//             'unit' => $request->unit,
//             'min_qty' => $request->min_qty,
//             // 'refundable' => $request->refundable,
//             // 'digital_product_type' => $request->digital_product_type,
//             // 'digital_file_ready' => $request->digital_file_ready,
//             // 'digital_file_ready_storage_type' => $request->digital_file_ready_storage_type,
//             'images' => $imagePaths['images'] ?? null,
//             'color_image' => $imagePaths['color_image'] ?? null,
//             'thumbnail' => $imagePaths['thumbnail'] ?? null,
//             // 'preview_file' => $imagePaths['preview_file'] ?? null,
//             // 'featured' => $request->featured,
//             // 'flash_deal' => $request->flash_deal,
//             // 'video_provider' => $request->video_provider,
//             // 'video_url' => $request->video_url,
//             // 'colors' => json_encode($request->colors)??[],
//             'colors' => $request->has('colors') ? json_encode($request->variation) : json_encode([]),

//             // 'variant_product' => $request->variant_product,
//             // 'attributes' => json_encode($request->attributes),
//             // 'choice_options' => json_encode($request->choice_options),
//             'variation' => $request->has('variation') ? json_encode($request->variation) : json_encode([]),

//             // 'digital_product_file_types' => json_encode($request->digital_product_file_types),
//             // 'digital_product_extensions' => json_encode($request->digital_product_extensions),
//             // 'published' => $request->published,
//             'unit_price' => $request->unit_price,
//             // 'purchase_price' => $request->purchase_price,
//             'tax' => $request->tax,
//             'tax_type' => $request->tax_type,
//             'tax_model' => $request->tax_model,
//             'discount' => $request->discount,
//             // 'discount_type' => $request->discount_type,
//             'current_stock' => $request->current_stock,
//             // 'minimum_order_qty' => $request->minimum_order_qty,
//             'details' => $request->details,
//             // 'free_shipping' => $request->free_shipping,
//             // 'attachment' => $imagePaths['attachment'] ?? null,
//             'status' => $request->status,
//             // 'featured_status' => $request->featured_status,
//             'meta_title' => $request->meta_title,
//             'meta_description' => $request->meta_description,
//             'meta_image' => $imagePaths['meta_image'] ?? null,
//             'request_status' => $request->request_status,
//             // 'denied_note' => $request->denied_note,
//             'shipping_cost' => $request->shipping_cost,
//             // 'multiply_qty' => $request->multiply_qty,
//             // 'temp_shipping_cost' => $request->temp_shipping_cost,
//             // 'is_shipping_cost_updated' => $request->is_shipping_cost_updated,
//             'code' => $request->code,
//             'status' => $request->status,
//             'request_status' => $request->status,
//         ]);

//         return response()->json(['success' => 'Product created successfully!', 'data' => $product], 201);
//     }

// working_old
// public function CreateProductVendor(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'name' => 'required|string|max:255',
//         'slug' => 'required|string|unique:products,slug',
//         'category_ids' => 'nullable|array',
//         'category_ids.*.id' => 'required|exists:categories,id',
//         'category_ids.*.position' => 'required|integer',
//         'category_id' => 'required|exists:categories,id',
//         'sub_category_id' => 'nullable|exists:categories,id',
//         'brand_id' => 'nullable|exists:brands,id',
//         'unit' => 'required|string|max:50',
//         'min_qty' => 'required|integer|min:1',
//         'images' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//         'color_image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//         'thumbnail' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//         'unit_price' => 'required|numeric',
//         'tax' => 'nullable|numeric',
//         'tax_type' => 'nullable|string',
//         'tax_model' => 'nullable|string',
//         'discount' => 'nullable|numeric',
//         'current_stock' => 'required|integer|min:0',
//         'details' => 'required|string',
//         'status' => 'required',
//         'meta_title' => 'nullable|string|max:255',
//         'meta_description' => 'nullable|string',
//         'meta_image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
//         'request_status' => 'nullable|string',
//         'shipping_cost' => 'nullable|numeric',
//         'code' => 'required|string|unique:products,code',
//         'status' => 'required',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
//     }

//     // Generate category_ids field dynamically based on category and sub_category
//     $categoryIds = [];

//     // For the main category
//     $categoryIds[] = [
//         'id' => (string) $request->category_id,
//         'position' => 1,
//     ];

//     // If sub_category is provided, add it with position 2
//     if ($request->sub_category_id) {
//         $categoryIds[] = [
//             'id' => (string) $request->sub_category_id,
//             'position' => 2,
//         ];
//     }

//     $imagePaths = $this->handleImageUploads($request);

//     $product = Product::create([
//         'added_by' => 'admin',
//         'user_id' => $request->vendor_id,
//         'name' => $request->name,
//         'slug' => $request->slug,
//         'category_ids' => json_encode($categoryIds), // Store dynamic category_ids
//         'category_id' => $request->category_id,
//         'sub_category_id' => $request->sub_category_id,
//         'brand_id' => $request->brand_id,
//         'unit' => $request->unit,
//         'min_qty' => $request->min_qty,
//         'images' => $imagePaths['images'] ?? null,
//         'color_image' => $imagePaths['color_image'] ?? null,
//         'thumbnail' => $imagePaths['thumbnail'] ?? null,
//         'colors' => $request->has('colors') ? json_encode($request->variation) : json_encode([]),
//         'variation' => $request->has('variation') ? json_encode($request->variation) : json_encode([]),
//         'unit_price' => $request->unit_price,
//         'tax' => $request->tax,
//         'tax_type' => $request->tax_type,
//         'tax_model' => $request->tax_model,
//         'discount' => $request->discount,
//         'current_stock' => $request->current_stock,
//         'details' => $request->details,
//         'status' => $request->status,
//         'meta_title' => $request->meta_title,
//         'meta_description' => $request->meta_description,
//         'meta_image' => $imagePaths['meta_image'] ?? null,
//         'request_status' => $request->request_status,
//         'shipping_cost' => $request->shipping_cost,
//         'code' => $request->code,
//         'status' => $request->status,
//         'request_status' => $request->status,
//     ]);

//     return response()->json(['success' => 'Product created successfully!', 'data' => $product], 201);
// }


// private function handleImageUploads(Request $request)
// {
//     $uploadedPaths = [];

//     if ($request->hasFile('images')) {
//         $uploadedPaths['images'] = $this->storeFile($request->file('images'), 'products/images');
//     }
//     if ($request->hasFile('color_image')) {
//         $uploadedPaths['color_image'] = $this->storeFile($request->file('color_image'), 'products/images');
//     }
//     if ($request->hasFile('thumbnail')) {
//         $uploadedPaths['thumbnail'] = $this->storeFile($request->file('thumbnail'), 'products/thumbnails');
//     }
//     if ($request->hasFile('meta_image')) {
//         $uploadedPaths['meta_image'] = $this->storeFile($request->file('meta_image'), 'products/meta_images');
//     }

//     return $uploadedPaths;
// }

// private function storeFile($file, $path)
// {
//     // Generate a unique filename
//     $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

//     // Store the file in the public disk
//     return $file->storeAs('public/' . $path, $filename);
// }
// end




    public function CreateProductVendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required',
            'sub_category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit' => 'required|string|max:50',
            'min_qty' => 'required|integer|min:1',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'unit_price' => 'required|numeric',
            'tax' => 'nullable|numeric',
            'tax_type' => 'nullable|string',
            'tax_model' => 'nullable|string',
            'discount' => 'nullable|numeric',
            'current_stock' => 'required|integer|min:0',
            'details' => 'required|string',
            'status' => 'required|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'shipping_cost' => 'nullable|numeric',
            'code' => 'required|string|unique:products,code',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $imagePaths = $this->handleImageUploads($request);

        $categoryIds = [
            ['id' => (string) $request->category_id, 'position' => 1]
        ];

        if ($request->sub_category_id) {
            $categoryIds[] = ['id' => (string) $request->sub_category_id, 'position' => 2];
        }

        $product = Product::create([
            'added_by' => 'healthhive_vendor',
            'user_id' => $request->vendor_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'product_type' => 'physical',
            'type' => $request->type,
            'category_ids' => json_encode($categoryIds),
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'brand_id' => $request->brand_id,
            'unit' => $request->unit,
            'min_qty' => $request->min_qty,
            'refundable' => $request->refundable ?? 1,
            'images' => $imagePaths['images'],
            'thumbnail' => $imagePaths['thumbnail'],
            'thumbnail_storage_type' => 'public',
            'unit_price' => $request->unit_price,
            'purchase_price' => $request->purchase_price ?? 0,
            'tax' => $request->tax ?? 0,
            'tax_type' => $request->tax_type ?? 'percent',
            'tax_model' => $request->tax_model ?? 'include',
            'discount' => $request->discount ?? 0,
            'discount_type' => 'percent',
            'current_stock' => $request->current_stock,
            'details' => $request->details,
            'free_shipping' => $request->free_shipping ?? 0,
            'meta_title' => $request->meta_title ?? $request->name,
            'meta_description' => $request->meta_description ?? strip_tags($request->details),
            'meta_image' => $imagePaths['meta_image'] ?? null,
            'shipping_cost' => $request->shipping_cost ?? 0,
            'code' => $request->code,
            'status' => $request->status,
            'request_status' => $request->status,
            'colors' => $request->has('colors') ? json_encode($request->variation) : json_encode([]),
            'variation' => $request->has('variation') ? json_encode($request->variation) : json_encode([]),
            'choice_options' => $request->has('choice_options') ? json_encode($request->choice_options) : json_encode([]),
        ]);

        $response = $product->toArray();
        $response['images_full_url'] = $this->getImageFullUrl($imagePaths['images'],'product');
        $response['thumbnail_full_url'] = $this->getImageFullUrl($imagePaths['thumbnail'], 'product/thumbnail');
        $response['meta_image_full_url'] = $this->getImageFullUrl($imagePaths['meta_image'], 'product/meta');

        return response()->json($response, 201);
    }

    private function handleImageUploads(Request $request)
    {
        $imagePaths = ['images' => '', 'thumbnail' => '', 'meta_image' => ''];

        if ($request->hasFile('images')) {
            $path = $request->file('images')->store('product', 'public');
            $imagePaths['images'] = basename($path);
        }

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('product/thumbnail', 'public');
            $imagePaths['thumbnail'] = basename($path);
        }

        if ($request->hasFile('meta_image')) {
            $path = $request->file('meta_image')->store('product/meta', 'public');
            $imagePaths['meta_image'] = basename($path);
        }

        return $imagePaths;
    }

    private function formatImageUrls($images)
    {
        return array_map(function($img) {
            return [
                'key' => $img['image_name'],
                'path' => asset("storage/app/public/product/{$img['image_name']}"),
                'status' => 200
            ];
        }, $images);
    }

    private function getImageFullUrl($imageName, $folder)
    {
        if ($imageName) {
            return [
                'key' => $imageName,
                'path' => asset("storage/app/public/{$folder}/{$imageName}"),
                'status' => 200
            ];
        }

        return ['key' => null, 'path' => null, 'status' => 404];
    }



    public function fetchVendorProduct(Request $request){
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:users,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }
        $vendorId = $request->vendor_id;
        $vendorProduct = Product::where('user_id', $vendorId)->get();
        if($vendorProduct){
            return response()->json([
                'success' => true,
                'data' => $vendorProduct
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'No product found'
            ], 404);
        }
    }

    public function healthCareJobListFilters(Request $request)
    {
        $filterDuration = $request->query('duration');

        $startDate = null;
        if ($filterDuration === '3-months') {
            $startDate = now()->subMonths(3);
        } elseif ($filterDuration === '6-months') {
            $startDate = now()->subMonths(6);
        } elseif ($filterDuration === '1-year') {
            $startDate = now()->subYear();
        }

        $query = HealthCareJob::whereIn('job_type', ['healthcare-worker', 'student','part time','Hands on Training','Workshops','Lectures']);

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        $healthCareJobList = $query->orderBy('id', 'desc')->get();

        if ($healthCareJobList->isNotEmpty()) {
            return response()->json([
                'message' => 'Jobs Fetched Successfully',
                'jobs' => $healthCareJobList
            ]);
        } else {
            return response()->json([
                'message' => 'No Jobs found for Health Care Workers'
            ]);
        }
    }



    public function fetchVendorOrders(Request $request){
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }
        $vendorId = $request->vendor_id;
        $vendorOrder =  Order::where('seller_id',$vendorId)->with('details.product', 'deliveryMan', 'seller.shop')->get();
        if($vendorOrder){
            return response()->json([
                'success' => true,
                'data' => $vendorOrder
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'No Order found'
            ], 404);
        }
    }



   
    
    public function fetchVendorOrderStatistics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $vendorId = $request->vendor_id;
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $orders = Order::where('seller_id', $vendorId)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        $statistics = [
            'week_1' => 0,
            'week_2' => 0,
            'week_3' => 0,
            'week_4' => 0,
        ];

        $totalSales = 0;
        $totalOrders = $orders->count();

        foreach ($orders as $order) {
            $weekNumber = ceil(Carbon::parse($order->created_at)->day / 7);
            $weekKey = 'week_' . $weekNumber;

            if (isset($statistics[$weekKey])) {
                $statistics[$weekKey] += $order->order_amount;
            }

            $totalSales += $order->order_amount;
        }

        $previousMonth = Carbon::createFromDate($year, $month, 1)->subMonth();
        $prevOrders = Order::where('seller_id', $vendorId)
            ->whereBetween('created_at', [$previousMonth->startOfMonth(), $previousMonth->endOfMonth()])
            ->get();

        $previousSales = $prevOrders->sum('order_amount');
        $previousOrdersCount = $prevOrders->count();

        $salesGrowth = $previousSales > 0 ? (($totalSales - $previousSales) / $previousSales) * 100 : 0;
        $ordersGrowth = $previousOrdersCount > 0 ? (($totalOrders - $previousOrdersCount) / $previousOrdersCount) * 100 : 0;

        $bestSellingProducts = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select('order_details.product_id', 'products.name as product_name', DB::raw('SUM(order_details.qty) as total_quantity'))
            ->whereIn('order_details.order_id', $orders->pluck('id'))
            ->groupBy('order_details.product_id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $statistics,
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'sales_growth_percentage' => round($salesGrowth, 2),
            'orders_growth_percentage' => round($ordersGrowth, 2),
            'best_selling_products' => $bestSellingProducts
        ]);
    }


    public function fetchRecruiterApplications(Request $request){
        $validator = Validator::make($request->all(), [
            'recruiter_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }
        $recruiterId = $request->recruiter_id;
        $recruiterMessage = Application::where('recruiter_id', $recruiterId)
        ->with(['job' => function ($query) {
            $query->select('id', 'job_title');
        }])
        ->orderBy('id','desc')
        ->get();

        if($recruiterMessage){
            return response()->json([
                'success' => true,
                'data' => $recruiterMessage
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'No Message found'
            ], 404);
        }
    }

    public function fetchRecruiterApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recruiter_id' => 'required',
            'application_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $recruiter_id = $request->query('recruiter_id');
        $application_id = $request->query('application_id');

        $application = Application::where('recruiter_id', $recruiter_id)
            ->where('id', $application_id)
            ->first();

        return $application
            ? response()->json(['success' => true, 'data' => $application])
            : response()->json(['success' => false, 'message' => 'Application not found'], 404);
    }


    public function markAsDeleted($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        try {
            $user->update([
                'is_deleted' => 1,
                'email' => 'deleted_' . time() . '_' . $user->email,
            ]);

            return response()->json([
                'message' => 'User marked as deleted successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to mark user as deleted',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // public function uploadVideo(Request $request)
    // {
    //     $request->validate([
    //         'video_lecture' => 'required|file|mimes:mp4,mov,avi|max:51200',
    //     ]);

    //     $file = $request->file('video_lecture');
    //     $filename = uniqid('video_', true) . '.' . $file->getClientOriginalExtension();

    //     $path = $file->storeAs('videos', $filename, 'public');

    //     return response()->json([
    //         'message' => 'Video uploaded successfully',
    //         'path' => asset('storage/app/public/' . $path),
    //     ], 201);
    // }

    // public function uploadVideo(Request $request)
    // {
    //     $request->validate([
    //         'video_lecture' => 'required|file|mimes:mp4,mov,avi|max:51200',
    //         'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
    //     ]);

    //     // Handle video upload
    //     $videoFile = $request->file('video_lecture');
    //     $videoFilename = uniqid('video_', true) . '.' . $videoFile->getClientOriginalExtension();
    //     $videoPath = $videoFile->storeAs('videos', $videoFilename, 'public');

    //     // Handle thumbnail upload
    //     $thumbnailFile = $request->file('thumbnail');
    //     $thumbnailFilename = uniqid('thumbnail_', true) . '.' . $thumbnailFile->getClientOriginalExtension();
    //     $thumbnailPath = $thumbnailFile->storeAs('thumbnails', $thumbnailFilename, 'public');

    //     return response()->json([
    //         'message' => 'Video and thumbnail uploaded successfully',
    //         'video_path' => asset('storage/app/public/videos/' . $videoFilename),
    //         'thumbnail_path' => asset('storage/app/public/thumbnails/' . $thumbnailFilename),
    //     ], 201);
    // }

    public function uploadVideo(Request $request)
{
    $request->validate([
        'video_lecture' => 'required|file|mimes:mp4,mov,avi|max:51200',
        'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
    ]);

    // Generate a unique ID to ensure matching filenames
    $uniqueId = uniqid('media_', true);

    // Handle video upload
    $videoFile = $request->file('video_lecture');
    $videoFilename = $uniqueId . '.' . $videoFile->getClientOriginalExtension();
    $videoPath = $videoFile->storeAs('videos', $videoFilename, 'public');

    // Handle thumbnail upload
    $thumbnailFile = $request->file('thumbnail');
    $thumbnailFilename = $uniqueId . '.' . $thumbnailFile->getClientOriginalExtension();
    $thumbnailPath = $thumbnailFile->storeAs('thumbnails', $thumbnailFilename, 'public');

    return response()->json([
        'message' => 'Video and thumbnail uploaded successfully',
        'video_path' => asset('storage/app/public/videos/' . $videoFilename),
        'thumbnail_path' => asset('storage/app/public/thumbnails/' . $thumbnailFilename),
    ], 201);
}



    // public function getAllVideos()
    // {
    //     $videos = Storage::disk('public')->files('videos');

    //     if (empty($videos)) {
    //         return response()->json([
    //             'message' => 'No videos found',
    //             'videos' => []
    //         ], 404);
    //     }

    //     $videoUrls = array_map(fn($video) => asset('storage/app/public/' . $video), $videos);

    //     return response()->json([
    //         'message' => 'Videos retrieved successfully',
    //         'videos' => $videoUrls
    //     ], 200);
    // }

    public function getAllVideos()
    {
        $videoFiles = Storage::disk('public')->files('videos');
        $thumbnailFiles = Storage::disk('public')->files('thumbnails');
        // var_dump($thumbnailFiles);die();
    
        if (empty($videoFiles)) {
            return response()->json([
                'message' => 'No videos found',
                'videos' => []
            ], 404);
        }
    
        $videos = [];
    
        foreach ($videoFiles as $video) {
            // Get video filename without extension
            $videoFilename = pathinfo($video, PATHINFO_FILENAME);
    
            // Find the corresponding thumbnail (exact match with different extension)
            $thumbnail = collect($thumbnailFiles)->first(function ($thumb) use ($videoFilename) {
                return pathinfo($thumb, PATHINFO_FILENAME) === $videoFilename;
            });
    
            $videos[] = [
                'video_url' => asset('storage/app/public/' . $video),
                'thumbnail_url' => $thumbnail ? asset('storage/app/public/' . $thumbnail) : null,
            ];
        }
    
        return response()->json([
            'message' => 'Videos retrieved successfully',
            'videos' => $videos
        ], 200);
    }


    public function CollectStaffId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'staff_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $user_id = $request->user_id;
        $staff_id = $request->staff_id;

        $application = StaffId::create([
            'user_id'=>$user_id,
            'staff_id'=>$staff_id
        ]);

        return $application
            ? response()->json(['success' => true, 'staff_id' => $staff_id])
            : response()->json(['success' => false, 'message' => 'Error storing Staff id'], 400);
    }


    public function getAllProducts(){
        $products = Product::all();
        return response()->json([
            'message' => 'Products retrieved successfully',
            'products' => $products
        ], 200);
    }
    


}
