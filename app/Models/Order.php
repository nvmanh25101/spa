<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_id',
        'admin_id',
        'voucher_id',
        'cart_id',
        'name_receiver',
        'phone_receiver',
        'address_receiver',
        'note',
        'price',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'delivered_at',
    ];

    public static function destroy($ids)
    {
        self::where('id', $ids)->update(['status' => OrderStatusEnum::DA_HUY]);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items', 'order_id', 'product_id')->withPivot('name', 'price',
            'quantity');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function getOrderDateAttribute(): ?string
    {
        $date = Carbon::create($this->created_at)->format('d/m/Y H:i:s');

        return $date ?: null;
    }

    public function getDeliveryDateAttribute()
    {
        if ($this->delivered_at === null) {
            return null;
        }
        $date = Carbon::create($this->delivered_at)->format('d/m/Y H:i:s');

        return $date ?: null;
    }
}
