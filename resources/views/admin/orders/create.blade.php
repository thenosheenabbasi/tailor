@extends('layouts.app', ['title' => 'Add Invoice | Tailor'])

@section('content')
    <style>
        .entry-shell {
            max-width: 920px;
            margin: 0 auto;
        }

        .entry-form .form-control,
        .entry-form .form-select {
            min-height: 54px;
        }
    </style>

    @php
        $isEditMode = filled($order);
    @endphp

    <div class="entry-shell">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ $isEditMode ? 'Edit Tailor Invoice' : 'Add Tailor Invoice' }}</h2>
            <p class="text-secondary mb-0">{{ $isEditMode ? 'Update the selected tailor work record from this form.' : 'Add a new tailor work record with the new white and gold brand theme.' }}</p>
        </div>
        <a href="{{ route('admin.orders.index', ['view' => 'invoices']) }}" class="btn btn-outline-dark rounded-4 px-4">Back to Tailor Invoice Table</a>
    </div>

    <div class="card-tailor rounded-4 p-4 p-lg-5">
                <form action="{{ $isEditMode ? route('admin.orders.update', $order) : route('admin.orders.store') }}" method="POST" class="row g-4 entry-form" id="tailor-order-form">
                    @csrf
                    @if ($isEditMode)
                        @method('PATCH')
                    @endif

                     <div class="col-md-6">
                        <label for="invoice_number" class="form-label">Invoice Number</label>
                        <input type="text" id="invoice_number" value="{{ $nextInvoiceNumber }}" class="form-control rounded-4" readonly>
                        <div class="form-text">Invoice number automatically generate hoga.</div>
                    </div>
                     
                    <div class="col-md-6">
                        <label for="assigned_user_id" class="form-label">Select Tailor Name</label>
                        <select id="assigned_user_id" name="assigned_user_id" class="form-select rounded-4 @error('assigned_user_id') is-invalid @enderror" required>
                            <option value="">Select tailor name</option>
                            @foreach ($assignableUsers as $assignableUser)
                                <option value="{{ $assignableUser->id }}" @selected((int) old('assigned_user_id', $order?->assigned_user_id) === $assignableUser->id)>
                                    {{ $assignableUser->name }} ({{ $assignableUser->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="thobe_category" class="form-label">Thobe Category</label>
                        <select id="thobe_category" name="thobe_category" class="form-select rounded-4 @error('thobe_category') is-invalid @enderror" required>
                            <option value="">Select category</option>
                            @foreach ($categoryOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('thobe_category', $order?->thobe_category) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('thobe_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="fatora_number" class="form-label">Fatora Number</label>
                        <input type="text" id="fatora_number" name="fatora_number" value="{{ old('fatora_number', $order?->fatora_number) }}" class="form-control rounded-4 @error('fatora_number') is-invalid @enderror" required>
                        @error('fatora_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $order?->quantity ?? 1) }}" min="1" class="form-control rounded-4 @error('quantity') is-invalid @enderror" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="order_date" class="form-label">Date & Time</label>
                        <input type="datetime-local" id="order_date" name="order_date" step="60" value="{{ old('order_date', optional($order?->order_date)->format('Y-m-d\\TH:i') ?? now()->format('Y-m-d\\TH:i')) }}" class="form-control rounded-4 @error('order_date') is-invalid @enderror" required>
                        @error('order_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="note" class="form-label">Note</label>
                        <textarea id="note" name="note" rows="4" class="form-control rounded-4 @error('note') is-invalid @enderror" placeholder="Optional tailoring note">{{ old('note', $order?->note) }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="metric-card rounded-4 p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <div class="text-secondary text-uppercase small">Total Price</div>
                                <div class="display-6 fw-bold text-warning"><span id="total-price">0.00</span> QAR</div>
                            </div>
                            <button type="submit" class="btn btn-tailor btn-lg rounded-4 px-5">{{ $isEditMode ? 'Update Entry' : 'Add Entry' }}</button>
                        </div>
                    </div>
                </form>
            </div>
    </div>

    <script>
        (() => {
            const prices = @json($categoryPrices);
            const category = document.getElementById('thobe_category');
            const quantity = document.getElementById('quantity');
            const totalPrice = document.getElementById('total-price');

            const updateTotal = () => {
                const selectedPrice = Number(prices[category.value] || 0);
                const qty = Number(quantity.value || 0);
                totalPrice.textContent = (selectedPrice * qty).toFixed(2);
            };

            category.addEventListener('change', updateTotal);
            quantity.addEventListener('input', updateTotal);
            updateTotal();
        })();
    </script>
@endsection
