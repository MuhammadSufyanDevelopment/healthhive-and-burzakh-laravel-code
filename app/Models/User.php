<?php

namespace App\Models;

use App\Models\ShippingAddress;
use App\Traits\StorageTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $f_name
 * @property string $l_name
 * @property string $phone
 * @property string $image
 * @property string $email
 * @property $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property $created_at
 * @property $updated_at
 * @property string $street_address
 * @property string $country
 * @property string $city
 * @property string $zip
 * @property string $house_no
 * @property string $apartment_no
 * @property string|null $cm_firebase_token
 * @property bool $is_active
 * @property string|null $payment_card_last_four
 * @property string|null $payment_card_brand
 * @property string|null $payment_card_fawry_token
 * @property string|null $login_medium
 * @property string|null $social_id
 * @property bool $is_phone_verified
 * @property string|null $temporary_token
 * @property bool $is_email_verified
 * @property float $wallet_balance
 * @property float $loyalty_point
 * @property int $login_hit_count
 * @property bool $is_temp_blocked
 * @property $temp_block_time
 * @property string|null $referral_code
 * @property int $referred_by
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens,StorageTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'f_name',
        'l_name',
        'phone',
        'image',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'street_address',
        'country',
        'city',
        'zip',
        'house_no',
        'apartment_no',
        'cm_firebase_token',
        'is_active',
        'payment_card_last_four',
        'payment_card_brand',
        'payment_card_fawry_token',
        'login_medium',
        'social_id',
        'is_phone_verified',
        'temporary_token',
        'is_email_verified',
        'wallet_balance',
        'loyalty_point',
        'login_hit_count',
        'is_temp_blocked',
        'temp_block_time',
        'referral_code',
        'referred_by',
        'phone',
        'password',
        'email',
        'university_name',
        'student_id',
        'feild_of_study',
        'year_of_study',
        'university_card_or_fee_payment',
        'is_student',
        // Muhammad-Sufyan-New-feilds-for-product-recommendation
        'study_start_year',
        'study_duration', 
        'dob',
        'interests',
        'gender',
        // For vendor
        'trade_license_number',
        'national_id_number',
        'is_vendor',
        // For Health care worker
        'gpa',
        'graduation_date',
        'feild_of_work',
        'passport_or_national_id',
        'is_health_care_worker',
        // Muhammad-Sufyan-New-feilds-for-product-recommendation
        'years_of_experience',
        // For recruiters
        'organization_name',
        'employee_id_number',
        'emirates_id_number',
        'hospital_logo',
        'is_recruiter',
        'is_email_verified',
        'is_deleted',
        // Document verification
        'documents_verified',
        'verification_time',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'f_name' => 'string',
        'l_name' => 'string',
        'phone' => 'string',
        'image' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'street_address' => 'string',
        'country' => 'string',
        'city' => 'string',
        'zip' => 'string',
        'house_no' => 'string',
        'apartment_no' => 'string',
        'cm_firebase_token' => 'string',
        'is_active' => 'boolean',
        'payment_card_last_four' => 'string',
        'payment_card_brand' => 'string',
        'payment_card_fawry_token' => 'string',
        'login_medium' => 'string',
        'social_id' => 'string',
        'is_phone_verified' => 'boolean',
        'temporary_token' => 'string',
        'is_email_verified' => 'boolean',
        'wallet_balance' => 'float',
        'loyalty_point' => 'float',
        'login_hit_count' => 'integer',
        'is_temp_blocked' => 'boolean',
        'temp_block_time' => 'datetime',
        'referral_code' => 'string',
        'referred_by' => 'integer',
        'is_deleted' => 'integer',
        // Document verification
        'documents_verified'=>'integer',
    ];

    // Old Relation: wish_list
    public function wishList(): hasMany
    {
        return $this->hasMany(Wishlist::class, 'customer_id');
    }

    public function orders(): hasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function shipping(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address');
    }
    public function addresses(): HasMany
    {
        return $this->hasMany(ShippingAddress::class, 'customer_id');
    }
    public function refundOrders(): HasMany
    {
        return $this->hasMany(RefundRequest::class, 'customer_id')->where('status','refunded');
    }

    // Old Relation: compare_list
    public function compareList(): hasMany
    {
        return $this->hasMany(ProductCompare::class, 'user_id');
    }
    public function compare_list()
    {
        return $this->hasMany(ProductCompare::class, 'user_id');
    }
    public function wish_list()
    {
        return $this->hasMany(Wishlist::class, 'customer_id');
    }

    public function getImageFullUrlAttribute():array
    {
        $value = $this->image;
        if (count($this->storage) > 0 ) {
            $storage = $this->storage->where('key','image')->first();
        }
        return $this->storageLink('profile',$value,$storage['value'] ?? 'public');
    }
    protected $appends = ['image_full_url'];

    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            if($model->isDirty('image')){
                $storage = config('filesystems.disks.default') ?? 'public';
                DB::table('storages')->updateOrInsert([
                    'data_type' => get_class($model),
                    'data_id' => $model->id,
                    'key' => 'image',
                ], [
                    'value' => $storage,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

}
