<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING    = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_SHIPPED    = 3;
    const STATUS_DELIVERED  = 4;
    const STATUS_CANCELLED  = 5;

    private const STATUS_LABELS = [
        self::STATUS_PENDING    => 'Pending',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_SHIPPED    => 'Shipped',
        self::STATUS_DELIVERED  => 'Delivered',
        self::STATUS_CANCELLED  => 'Cancelled',
    ];

    private const STATUS_COLORS = [
        self::STATUS_PENDING    => 'secondary',
        self::STATUS_PROCESSING => 'primary',
        self::STATUS_SHIPPED    => 'info',
        self::STATUS_DELIVERED  => 'success',
        self::STATUS_CANCELLED  => 'danger',
    ];

    protected $fillable = [
        'order_number',
        'customer_id',
        'user_id',
        'status',
        'subtotal',
        'tax',
        'total',
        'notes',
        'shipping_address',
        'due_date',
        'attachment',
    ];

    protected function casts(): array
    {
        return [
            'status'   => 'integer',
            'subtotal' => 'float',
            'tax'      => 'float',
            'total'    => 'float',
            'due_date' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'dark';
    }
}
