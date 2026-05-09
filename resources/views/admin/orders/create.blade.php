@extends('layouts.app', ['title' => 'New Tailor Invoice | Elite Tailoring'])

@section('content')
    @php
        $isEditMode = filled($order);
        $currentCategory = old('thobe_category', $order?->thobe_category);
        $currentQuantity = (int) old('quantity', $order?->quantity ?? 1);
        $initialFatora = old('fatora_number', $order?->fatora_number ?? 'F-1007');
        $initialDate = old('order_date', optional($order?->order_date)->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i'));
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
                                    <input type="datetime-local" id="order_date" name="order_date" step="60" value="{{ $initialDate }}" class="form-control @error('order_date') is-invalid @enderror" required>
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

            const updateTotal = () => {
                const selectedPrice = Number(prices[categorySelect.value] || 0);
                const qty = Math.max(Number(quantityInput.value || 1), 1);
                totalPrice.textContent = (selectedPrice * qty).toFixed(0);
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
            updateTotal();
        })();
    </script>
@endsection
