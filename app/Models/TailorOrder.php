<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class TailorOrder extends Model
{
    public const CATEGORY_SIMPLE = 'simple';
    public const CATEGORY_DOUBLE_STITCH = 'double_stitch';
    public const CATEGORY_EMBROIDERY = 'embroidery';
    public const CATEGORY_DESIGN = 'design';
    public const CATEGORY_ALTERATION_A = 'alteration_a';
    public const CATEGORY_ALTERATION_B = 'alteration_b';
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'user_id',
        'assigned_user_id',
        'tailor_name',
        'invoice_number',
        'fatora_number',
        'thobe_category',
        'quantity',
        'order_date',
        'unit_price',
        'total_price',
        'status',
        'completed_at',
        'note',
        'hidden_from_dashboard',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'completed_at' => 'datetime',
            'hidden_from_dashboard' => 'boolean',
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    public static function categories(): array
    {
        return [
            self::CATEGORY_SIMPLE => [
                'label' => 'Simple',
                'description' => 'Standard thobe',
                'price' => 20,
            ],
            self::CATEGORY_DOUBLE_STITCH => [
                'label' => 'Double Stitch',
                'description' => 'Double stitch finish',
                'price' => 25,
            ],
            self::CATEGORY_EMBROIDERY => [
                'label' => 'Embroidery',
                'description' => 'Embroidery details',
                'price' => 25,
            ],
            self::CATEGORY_DESIGN => [
                'label' => 'Design',
                'description' => 'Custom design work',
                'price' => 30,
            ],
            self::CATEGORY_ALTERATION_A => [
                'label' => 'Alteration A',
                'description' => 'Alteration work',
                'price' => 5,
            ],
            self::CATEGORY_ALTERATION_B => [
                'label' => 'Alteration B',
                'description' => 'Alteration work',
                'price' => 10,
            ],
        ];
    }

    public static function categoryOptions(): array
    {
        return collect(self::categories())
            ->mapWithKeys(fn (array $category, string $key) => [
                $key => "{$category['label']} ({$category['description']}) - {$category['price']} QAR",
            ])
            ->all();
    }

    public static function unitPriceFor(string $category): float
    {
        return (float) (self::categories()[$category]['price'] ?? 0);
    }

    public static function nextInvoiceNumber(): string
    {
        $latestId = (int) self::max('id');

        return 'INV-' . str_pad((string) ($latestId + 1001), 4, '0', STR_PAD_LEFT);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::categories()[$this->thobe_category]['label'] ?? ucfirst(str_replace('_', ' ', $this->thobe_category));
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? ucfirst($this->status);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('order_date')->orderByDesc('id');
    }

    public function scopeCompletedWork(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeRevenueTotal(Builder $query): float
    {
        return (float) $query->sum(DB::raw('quantity * unit_price'));
    }
}
