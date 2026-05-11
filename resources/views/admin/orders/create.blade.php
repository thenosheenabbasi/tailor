@extends('layouts.app', ['title' => 'New Tailor Invoice | Elite Tailoring'])

@section('content')
    @php
        $isEditMode = filled($order);
        $currentCategory = old('thobe_category', $order?->thobe_category);
        $currentQuantity = (int) old('quantity', $order?->quantity ?? 1);
        $initialFatora = old('fatora_number', $order?->fatora_number ?? 'F-1007');
        $initialDate = old('order_date', optional($order?->order_date)->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i'));
        try {
            $initialDisplayDate = filled($initialDate) ? \Illuminate\Support\Carbon::parse($initialDate)->format('m/d/Y h:i A') : '';
        } catch (\Throwable $exception) {
            $initialDisplayDate = $initialDate;
        }
        $categoryDropdownOptions = collect(\App\Models\TailorOrder::categories())->map(function (array $category, string $key) use ($currentCategory) {
            return [
                'key' => $key,
                'label' => $category['label'],
                'description' => ucfirst($category['description']),
                'price' => (float) $category['price'],
                'formatted_price' => number_format((float) $category['price'], 0) . ' QAR',
                'selected' => $currentCategory === $key,
            ];
        })->values();
    @endphp

    <style>
        .entry-luxury {
            max-width: 1080px;
            margin: 0 auto;
            color: #111111;
        }

        .entry-topbar {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 0.85rem;
        }

        .entry-topbar-copy {
            width: 100%;
            text-align: center;
        }

        .entry-kicker {
            margin: 0 0 0.25rem;
            color: rgba(17, 17, 17, 0.72);
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .entry-title {
            margin: 0;
            font-family: "Playfair Display", "Cormorant Garamond", serif;
            font-size: clamp(1.5rem, 2.2vw, 1.9rem);
            font-weight: 700;
            line-height: 1.08;
            color: #111111;
        }

        .entry-copy {
            margin: 0.3rem 0 0;
            color: var(--tailor-muted);
            font-size: 0.82rem;
        }

        .entry-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .entry-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            min-height: 3rem;
            padding: 0.78rem 1.2rem;
            border-radius: 0.85rem;
            border: 1px solid rgba(17, 17, 17, 0.22);
            color: #111111;
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            transition: transform 0.22s ease, border-color 0.22s ease, background 0.22s ease, box-shadow 0.22s ease;
        }

        .entry-action-btn:hover {
            transform: translateY(-1px);
            border-color: rgba(17, 17, 17, 0.38);
            box-shadow: 0 14px 24px rgba(17, 17, 17, 0.1);
        }

        .entry-action-btn svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        .entry-action-btn.whatsapp {
            border-color: rgba(37, 211, 102, 0.3);
            background: linear-gradient(180deg, #28d463 0%, #22c55e 100%);
            color: #ffffff;
        }

        .entry-action-btn.whatsapp:hover {
            border-color: rgba(37, 211, 102, 0.5);
            background: linear-gradient(180deg, #2fe06a 0%, #28d463 100%);
        }

        .entry-surface {
            border-radius: 1rem;
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.08);
            box-shadow: 0 10px 24px rgba(17, 17, 17, 0.08);
            overflow: hidden;
        }

        .entry-card {
            position: relative;
            padding: 0.95rem;
        }

        .entry-form {
            position: relative;
            z-index: 1;
        }

        .entry-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.85rem;
        }

        .entry-span-2 {
            grid-column: 1 / -1;
        }

        .entry-field {
            display: grid;
            gap: 0.42rem;
        }

        .entry-field .form-label {
            margin-bottom: 0;
            color: #8f897f;
            font-size: 0.68rem;
            letter-spacing: 0.15em;
        }

        .entry-field .form-control,
        .entry-field .form-select {
            min-height: 2.7rem;
            padding: 0.62rem 0.82rem;
            border-radius: 0.68rem !important;
            background-color: #ffffff;
            border: 1.5px solid rgba(17, 17, 17, 0.2);
            color: #111111;
            font-size: 0.88rem;
            box-shadow: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .entry-field .form-control:hover,
        .entry-field .form-select:hover {
            border-color: rgba(17, 17, 17, 0.34);
            background-color: #fffdfa;
        }

        .entry-field .form-control:focus,
        .entry-field .form-select:focus {
            border-color: var(--tailor-gold);
            background-color: #ffffff;
            box-shadow: 0 0 0 0.18rem rgba(215, 154, 30, 0.16);
        }

        .entry-field .form-control.is-invalid,
        .entry-field .form-select.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.14rem rgba(220, 53, 69, 0.08);
        }

        .entry-field .form-select {
            padding-right: 2.65rem;
            background-image: var(--bs-form-select-bg-img), var(--bs-form-select-bg-icon, none);
            background-repeat: no-repeat;
            background-position: right 0.9rem center;
            background-size: 16px 12px;
        }

        .entry-compact-grid {
            display: grid;
            grid-template-columns: 1.1fr 1fr 1fr;
            gap: 0.85rem;
            align-items: end;
        }

        .entry-quantity-wrap {
            display: grid;
            grid-template-columns: 2.5rem minmax(0, 1fr) 2.5rem;
            gap: 0.45rem;
            align-items: center;
        }

        .entry-stepper {
            width: 2.5rem;
            height: 2.7rem;
            border-radius: 0.68rem;
            border: 1px solid rgba(17, 17, 17, 0.12);
            background: #ffffff;
            color: #111111;
            font-size: 1.05rem;
            line-height: 1;
            transition: transform 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .entry-stepper:hover {
            transform: translateY(-1px);
            color: #111111;
            border-color: rgba(17, 17, 17, 0.32);
        }

        .entry-total-box {
            min-height: 2.7rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: 0.68rem;
            padding: 0.6rem 0.82rem;
            border: 1px solid rgba(17, 17, 17, 0.12);
            background: #ffffff;
        }

        .entry-total-label {
            color: var(--tailor-muted);
            font-size: 0.66rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        .entry-total-value {
            margin-top: 0.14rem;
            font-family: "Playfair Display", "Cormorant Garamond", serif;
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1;
            color: #111111;
        }

        .entry-note .form-control {
            min-height: 5.25rem;
            resize: vertical;
        }

        .entry-date-picker {
            position: relative;
        }

        .entry-date-control {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 2.7rem;
            align-items: stretch;
        }

        .entry-field .entry-date-control .form-control {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .entry-date-toggle {
            min-height: 2.7rem;
            border: 1.5px solid rgba(17, 17, 17, 0.2);
            border-left: 0;
            border-radius: 0 0.68rem 0.68rem 0;
            background: #ffffff;
            color: #111111;
            font-size: 1rem;
            font-weight: 800;
        }

        .entry-date-popover {
            position: absolute;
            top: calc(100% + 0.45rem);
            left: 0;
            z-index: 40;
            width: min(92vw, 21rem);
            padding: 0.8rem;
            border: 1px solid rgba(17, 17, 17, 0.14);
            border-radius: 0.85rem;
            background: #ffffff;
            color: #111111;
            box-shadow: 0 24px 46px rgba(17, 17, 17, 0.18);
            direction: ltr;
        }

        .entry-date-popover[hidden] {
            display: none;
        }

        .entry-date-header,
        .entry-date-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .entry-date-month {
            font-size: 0.95rem;
            font-weight: 800;
            text-align: center;
        }

        .entry-date-selectors {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(5.6rem, 0.65fr);
            gap: 0.35rem;
            flex: 1 1 auto;
            min-width: 0;
        }

        .entry-date-selector {
            min-width: 0;
            height: 2.05rem;
            border: 1px solid rgba(17, 17, 17, 0.16);
            border-radius: 0.5rem;
            background: #ffffff;
            color: #111111;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 0 0.35rem;
        }

        .entry-date-nav,
        .entry-date-day,
        .entry-date-action {
            border: 0;
            background: transparent;
            color: #111111;
            font: inherit;
        }

        .entry-date-nav {
            width: 2.05rem;
            height: 2.05rem;
            border-radius: 0.5rem;
            font-size: 1.1rem;
            line-height: 1;
        }

        .entry-date-nav:hover,
        .entry-date-day:hover,
        .entry-date-action:hover {
            background: #f4efe6;
        }

        .entry-date-weekdays,
        .entry-date-days {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 0.22rem;
        }

        .entry-date-weekdays {
            margin-top: 0.72rem;
            color: #111111;
            font-size: 0.76rem;
            font-weight: 800;
            text-align: center;
        }

        .entry-date-days {
            margin-top: 0.3rem;
        }

        .entry-date-day {
            min-height: 2.1rem;
            border-radius: 0.46rem;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .entry-date-day.muted {
            color: #8f897f;
            font-weight: 500;
        }

        .entry-date-day.selected {
            background: #006ad4;
            color: #ffffff;
        }

        .entry-date-footer {
            margin-top: 0.6rem;
        }

        .entry-date-action {
            min-height: 2rem;
            padding: 0 0.45rem;
            border-radius: 0.45rem;
            color: #006ad4;
            font-weight: 700;
        }

        .entry-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.7rem;
            margin-top: 1rem;
        }

        .entry-ghost-btn,
        .entry-save-btn {
            min-width: 8.6rem;
            min-height: 2.8rem;
            padding: 0.65rem 1.1rem;
            border-radius: 0.75rem !important;
            font-size: 0.86rem;
            font-weight: 700;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .entry-save-btn {
            box-shadow: 0 16px 24px rgba(0, 0, 0, 0.22);
        }

        .entry-save-btn:hover,
        .entry-ghost-btn:hover {
            transform: translateY(-1px);
        }

        .entry-ghost-btn {
            border: 1px solid rgba(17, 17, 17, 0.2);
            color: #111111;
            background: transparent;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .entry-ghost-btn:hover {
            color: #ffffff;
            background: #111111;
            border-color: #111111;
        }

        @media (max-width: 991.98px) {
            .entry-topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .entry-actions {
                justify-content: flex-start;
            }

            .entry-compact-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .entry-card {
                padding: 0.85rem;
            }

            .entry-grid,
            .entry-compact-grid {
                grid-template-columns: 1fr;
            }

            .entry-footer {
                flex-direction: column-reverse;
            }

            .entry-ghost-btn,
            .entry-save-btn {
                width: 100%;
            }

            .entry-date-popover {
                width: min(86vw, 21rem);
            }
        }
    </style>

    <div class="entry-luxury">
        <div class="entry-topbar">
            <div class="entry-topbar-copy">
                <p class="entry-kicker">Elite Tailoring</p>
                <h1 class="entry-title">{{ $isEditMode ? 'Edit Entry' : 'New Tailor Invoice' }}</h1>
                <p class="entry-copy">{{ $isEditMode ? 'Refine the tailor order record with luxury precision.' : 'Add a new tailor order entry' }}</p>
            </div>

        </div>

        <section class="entry-surface">
            <div class="entry-card">
                <form action="{{ $isEditMode ? route('admin.orders.update', $order) : route('admin.orders.store') }}" method="POST" class="entry-form">
                    @csrf
                    @if ($isEditMode)
                        @method('PATCH')
                    @endif

                    <div class="entry-grid">
                        <div class="entry-field">
                            <label for="fatora_number" class="form-label">Fatora # *</label>
                            <input type="text" id="fatora_number" name="fatora_number" value="{{ $initialFatora }}" class="form-control @error('fatora_number') is-invalid @enderror" placeholder="F-1007" required>
                            @error('fatora_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="entry-field">
                            <label for="assigned_user_id" class="form-label">Tailor Name *</label>
                            <select id="assigned_user_id" name="assigned_user_id" class="form-select @error('assigned_user_id') is-invalid @enderror" required>
                                <option value="">Select tailor</option>
                                @foreach ($assignableUsers as $assignableUser)
                                    <option value="{{ $assignableUser->id }}" @selected((int) old('assigned_user_id', $order?->assigned_user_id) === $assignableUser->id)>
                                        {{ $assignableUser->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="entry-field entry-span-2">
                            <label for="thobe_category" class="form-label">Category *</label>
                            <select id="thobe_category" name="thobe_category" class="form-select @error('thobe_category') is-invalid @enderror" required>
                                <option value="">Select category</option>
                                @foreach ($categoryDropdownOptions as $category)
                                    <option value="{{ $category['key'] }}" @selected($category['selected'])>
                                        {{ $category['label'] }} - {{ $category['formatted_price'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('thobe_category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="entry-field entry-span-2">
                            <div class="entry-compact-grid">
                                <div class="entry-field">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <div class="entry-quantity-wrap">
                                        <button type="button" class="entry-stepper" data-step="-1" aria-label="Decrease quantity">-</button>
                                        <input type="number" id="quantity" name="quantity" value="{{ max($currentQuantity, 1) }}" min="1" class="form-control text-center @error('quantity') is-invalid @enderror" required>
                                        <button type="button" class="entry-stepper" data-step="1" aria-label="Increase quantity">+</button>
                                    </div>
                                    @error('quantity')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="entry-field">
                                    <label for="order_date" class="form-label">Date</label>
                                    <div class="entry-date-picker" data-date-picker>
                                        <input type="hidden" id="order_date" name="order_date" value="{{ $initialDate }}" required>
                                        <div class="entry-date-control">
                                            <input type="text" id="order_date_display" value="{{ $initialDisplayDate }}" class="form-control @error('order_date') is-invalid @enderror" autocomplete="off" placeholder="Select Islamic date" data-date-display readonly required>
                                            <button type="button" class="entry-date-toggle" data-date-toggle aria-label="Open Islamic calendar" aria-expanded="false">▾</button>
                                        </div>
                                        <div class="entry-date-popover" data-date-popover role="dialog" aria-label="Choose Islamic date" lang="en" dir="ltr" hidden>
                                            <div class="entry-date-header">
                                                <button type="button" class="entry-date-nav" data-month-prev aria-label="Previous month">&lt;</button>
                                                <div class="entry-date-selectors">
                                                    <select class="entry-date-selector" data-hijri-month aria-label="Islamic month"></select>
                                                    <input type="number" class="entry-date-selector" data-hijri-year aria-label="Islamic year" min="1300" max="1600" step="1">
                                                </div>
                                                <button type="button" class="entry-date-nav" data-month-next aria-label="Next month">&gt;</button>
                                            </div>
                                            <div class="entry-date-month" data-month-label></div>
                                            <div class="entry-date-weekdays" data-weekdays></div>
                                            <div class="entry-date-days" data-days></div>
                                            <div class="entry-date-footer">
                                                <button type="button" class="entry-date-action" data-date-clear>Clear</button>
                                                <button type="button" class="entry-date-action" data-date-today>Today</button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('order_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="entry-field">
                                    <label for="total_preview" class="form-label">Total (QAR)</label>
                                    <div class="entry-total-box" id="total_preview" aria-live="polite">
                                        <div class="entry-total-label">Calculated total</div>
                                        <div class="entry-total-value"><span id="total-price">0</span> QAR</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="entry-field entry-span-2 entry-note">
                            <label for="note" class="form-label">Notes</label>
                            <textarea id="note" name="note" rows="5" class="form-control @error('note') is-invalid @enderror" placeholder="e.g. Gold thread embroidery, Premium client...">{{ old('note', $order?->note) }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="entry-footer">
                        <a href="{{ route('admin.orders.index', ['view' => 'invoices']) }}" class="entry-ghost-btn">Cancel</a>
                        <button type="submit" class="btn btn-tailor entry-save-btn">{{ $isEditMode ? 'Update Entry' : 'Save Entry' }}</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        (() => {
            const prices = @json($categoryPrices);
            const categorySelect = document.getElementById('thobe_category');
            const quantityInput = document.getElementById('quantity');
            const totalPrice = document.getElementById('total-price');
            const stepButtons = document.querySelectorAll('[data-step]');
            const datePicker = document.querySelector('[data-date-picker]');
            const dateDisplay = document.querySelector('[data-date-display]');
            const dateInput = document.getElementById('order_date');
            const dateToggle = document.querySelector('[data-date-toggle]');
            const datePopover = document.querySelector('[data-date-popover]');
            const monthLabel = document.querySelector('[data-month-label]');
            const weekdaysWrap = document.querySelector('[data-weekdays]');
            const daysWrap = document.querySelector('[data-days]');
            const hijriMonthSelect = document.querySelector('[data-hijri-month]');
            const hijriYearSelect = document.querySelector('[data-hijri-year]');
            const hijriMonthFormatter = new Intl.DateTimeFormat('en-US-u-ca-islamic-umalqura-nu-latn', {
                month: 'long',
                year: 'numeric',
            });
            const hijriPartsFormatter = new Intl.DateTimeFormat('en-US-u-ca-islamic-umalqura-nu-latn', {
                day: 'numeric',
                month: 'numeric',
                year: 'numeric',
            });
            const hijriDisplayFormatter = new Intl.DateTimeFormat('en-US-u-ca-islamic-umalqura-nu-latn', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
            });
            const hijriDayFormatter = new Intl.NumberFormat('en-US-u-nu-latn', {
                useGrouping: false,
            });
            const calendarWeekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            const hijriMonths = [
                'Muharram',
                'Safar',
                'Rabi al-Awwal',
                'Rabi al-Thani',
                'Jumada I',
                'Jumada II',
                'Rajab',
                'Shaʻban',
                'Ramadan',
                'Shawwal',
                'Dhuʻl-Qiʻdah',
                'Dhuʻl-Hijjah',
            ].map((label, index) => ({ value: index + 1, label }));

            const updateTotal = () => {
                const selectedPrice = Number(prices[categorySelect.value] || 0);
                const qty = Math.max(Number(quantityInput.value || 1), 1);
                totalPrice.textContent = (selectedPrice * qty).toFixed(0);
            };

            const pad = (value) => String(value).padStart(2, '0');

            const formatDisplayDate = (date) => hijriDisplayFormatter.format(date);

            const formatSubmitDate = (date) => {
                return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
            };

            const parseSubmitDate = (value) => {
                if (!value) {
                    return null;
                }

                const [datePart, timePart = '00:00'] = value.split('T');
                const [year, month, day] = datePart.split('-').map(Number);
                const [hour = 0, minute = 0] = timePart.split(':').map(Number);

                if ([year, month, day, hour, minute].some(Number.isNaN)) {
                    return null;
                }

                return new Date(year, month - 1, day, hour, minute);
            };

            const getHijriParts = (date) => {
                const parts = hijriPartsFormatter.formatToParts(date).reduce((carry, part) => {
                    if (['day', 'month', 'year'].includes(part.type)) {
                        carry[part.type] = Number(part.value);
                    }

                    return carry;
                }, {});

                return {
                    day: parts.day,
                    month: parts.month,
                    year: parts.year,
                };
            };

            const addHijriMonths = (year, month, amount) => {
                const monthIndex = (year * 12) + (month - 1) + amount;

                return {
                    year: Math.floor(monthIndex / 12),
                    month: (monthIndex % 12) + 1,
                };
            };

            const findGregorianForHijri = (year, month, day, baseDate = selectedDate) => {
                const base = new Date(baseDate);
                base.setHours(12, 0, 0, 0);

                const baseHijri = getHijriParts(base);
                const estimatedOffset = Math.round(
                    ((year - baseHijri.year) * 354.367)
                    + ((month - baseHijri.month) * 29.53)
                    + (day - baseHijri.day)
                );
                const startDate = new Date(base);
                startDate.setDate(base.getDate() + estimatedOffset - 20);

                for (let offset = 0; offset <= 40; offset += 1) {
                    const candidate = new Date(startDate);
                    candidate.setDate(startDate.getDate() + offset);

                    const candidateHijri = getHijriParts(candidate);

                    if (
                        candidateHijri.year === year
                        && candidateHijri.month === month
                        && candidateHijri.day === day
                    ) {
                        return candidate;
                    }
                }

                return null;
            };

            const isSameHijriMonth = (date, year, month) => {
                const hijri = getHijriParts(date);

                return hijri.year === year && hijri.month === month;
            };

            const syncDateSelectors = () => {
                if (hijriMonthSelect && hijriMonthSelect.options.length === 0) {
                    hijriMonths.forEach((month) => {
                        const option = document.createElement('option');
                        option.value = String(month.value);
                        option.textContent = month.label;
                        hijriMonthSelect.appendChild(option);
                    });
                }

                if (hijriMonthSelect) {
                    hijriMonthSelect.value = String(visibleHijriMonth);
                }

                if (hijriYearSelect) {
                    hijriYearSelect.value = String(visibleHijriYear);
                }
            };

            let selectedDate = parseSubmitDate(dateInput?.value) || new Date();
            let selectedHijri = getHijriParts(selectedDate);
            let visibleHijriYear = selectedHijri.year;
            let visibleHijriMonth = selectedHijri.month;

            const sameDate = (left, right) => (
                left.getFullYear() === right.getFullYear()
                && left.getMonth() === right.getMonth()
                && left.getDate() === right.getDate()
            );

            const setSelectedDate = (date) => {
                selectedDate = date;
                selectedHijri = getHijriParts(date);
                visibleHijriYear = selectedHijri.year;
                visibleHijriMonth = selectedHijri.month;

                if (dateInput) {
                    dateInput.value = formatSubmitDate(date);
                }

                if (dateDisplay) {
                    dateDisplay.value = formatDisplayDate(date);
                }

                renderCalendar();
            };

            const renderCalendar = () => {
                if (!monthLabel || !weekdaysWrap || !daysWrap) {
                    return;
                }

                const firstOfMonth = findGregorianForHijri(visibleHijriYear, visibleHijriMonth, 1);

                if (!firstOfMonth) {
                    return;
                }

                const firstGridDate = new Date(firstOfMonth);
                firstGridDate.setDate(firstOfMonth.getDate() - firstOfMonth.getDay());
                monthLabel.textContent = hijriMonthFormatter.format(firstOfMonth);
                syncDateSelectors();

                weekdaysWrap.innerHTML = '';
                calendarWeekdays.forEach((weekday) => {
                    const weekdayEl = document.createElement('div');
                    weekdayEl.textContent = weekday;
                    weekdaysWrap.appendChild(weekdayEl);
                });

                daysWrap.innerHTML = '';

                for (let index = 0; index < 42; index += 1) {
                    const date = new Date(firstGridDate);
                    date.setDate(firstGridDate.getDate() + index);

                    const button = document.createElement('button');
                    const hijriDate = getHijriParts(date);

                    button.type = 'button';
                    button.className = 'entry-date-day';
                    button.textContent = hijriDayFormatter.format(hijriDate.day);
                    button.dataset.year = String(date.getFullYear());
                    button.dataset.month = String(date.getMonth());
                    button.dataset.day = String(date.getDate());

                    if (!isSameHijriMonth(date, visibleHijriYear, visibleHijriMonth)) {
                        button.classList.add('muted');
                    }

                    if (sameDate(date, selectedDate)) {
                        button.classList.add('selected');
                    }

                    daysWrap.appendChild(button);
                }
            };

            const openCalendar = () => {
                if (!datePopover || !dateToggle) {
                    return;
                }

                datePopover.hidden = false;
                dateToggle.setAttribute('aria-expanded', 'true');
                renderCalendar();
            };

            const closeCalendar = () => {
                if (!datePopover || !dateToggle) {
                    return;
                }

                datePopover.hidden = true;
                dateToggle.setAttribute('aria-expanded', 'false');
            };

            stepButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const step = Number(button.dataset.step || 0);
                    const nextValue = Math.max(Number(quantityInput.value || 1) + step, 1);
                    quantityInput.value = String(nextValue);
                    updateTotal();
                });
            });

            quantityInput.addEventListener('input', () => {
                if (Number(quantityInput.value) < 1 || Number.isNaN(Number(quantityInput.value))) {
                    quantityInput.value = '1';
                }

                updateTotal();
            });

            categorySelect.addEventListener('change', updateTotal);
            dateDisplay?.addEventListener('focus', openCalendar);
            dateDisplay?.addEventListener('click', openCalendar);
            dateDisplay?.addEventListener('change', () => {
                dateDisplay.value = selectedDate ? formatDisplayDate(selectedDate) : '';
            });

            dateToggle?.addEventListener('click', () => {
                if (datePopover?.hidden) {
                    openCalendar();
                    return;
                }

                closeCalendar();
            });

            document.querySelector('[data-month-prev]')?.addEventListener('click', () => {
                const previousMonth = addHijriMonths(visibleHijriYear, visibleHijriMonth, -1);
                visibleHijriYear = previousMonth.year;
                visibleHijriMonth = previousMonth.month;

                renderCalendar();
            });

            document.querySelector('[data-month-next]')?.addEventListener('click', () => {
                const nextMonth = addHijriMonths(visibleHijriYear, visibleHijriMonth, 1);
                visibleHijriYear = nextMonth.year;
                visibleHijriMonth = nextMonth.month;

                renderCalendar();
            });

            hijriMonthSelect?.addEventListener('change', () => {
                visibleHijriMonth = Number(hijriMonthSelect.value);
                renderCalendar();
            });

            hijriYearSelect?.addEventListener('change', () => {
                const nextYear = Number(hijriYearSelect.value);

                if (!Number.isNaN(nextYear)) {
                    visibleHijriYear = nextYear;
                }

                renderCalendar();
            });

            daysWrap?.addEventListener('click', (event) => {
                const button = event.target.closest('[data-day]');

                if (!button) {
                    return;
                }

                const nextDate = new Date(
                    Number(button.dataset.year),
                    Number(button.dataset.month),
                    Number(button.dataset.day),
                    selectedDate.getHours(),
                    selectedDate.getMinutes(),
                );

                setSelectedDate(nextDate);
                closeCalendar();
            });

            document.querySelector('[data-date-clear]')?.addEventListener('click', () => {
                if (dateInput) {
                    dateInput.value = '';
                }

                if (dateDisplay) {
                    dateDisplay.value = '';
                }

                closeCalendar();
            });

            document.querySelector('[data-date-today]')?.addEventListener('click', () => {
                setSelectedDate(new Date());
                closeCalendar();
            });

            document.addEventListener('click', (event) => {
                if (!datePicker?.contains(event.target)) {
                    closeCalendar();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeCalendar();
                }
            });

            updateTotal();
            setSelectedDate(selectedDate);
        })();
    </script>
@endsection
