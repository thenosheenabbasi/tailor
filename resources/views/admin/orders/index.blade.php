@extends('layouts.app', ['title' => ($pageMode === 'report' ? 'Report' : 'Tailor Invoice') . ' | Tailor'])

@section('content')
    @php
        $visibleOrderCount = $orders->count();
        $visibleOrderAmount = $orders->sum('total_price');
    @endphp

    <style>
        .orders-view {
            display: grid;
            gap: 1rem;
            min-width: 0;
        }

        .orders-content {
            display: grid;
            gap: 1rem;
            min-width: 0;
        }

        .orders-top-action {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 0.2rem;
        }

        .create-tailor-btn {
            border-color: #111111 !important;
            background: #111111 !important;
            color: #ffffff !important;
            border-radius: 10px !important;
        }

        .create-tailor-btn:hover,
        .create-tailor-btn:focus {
            border-color: #111111 !important;
            background: #111111 !important;
            color: #ffffff !important;
        }

        .legacy-copy {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .hero-panel,
        .table-card,
        .stat-card,
        .report-toolbar,
        .category-summary-panel {
            border-radius: 1rem;
            background: #1b1b1b;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
        }

        .hero-panel {
            padding: 1.15rem 1.2rem;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            color: #8f897f;
            font-size: 0.72rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin-bottom: 0.45rem;
        }

        .hero-kicker::before {
            content: "";
            width: 2rem;
            height: 1px;
            background: linear-gradient(90deg, #d2b26d, transparent);
        }

        .hero-title {
            font-size: clamp(1.45rem, 2vw, 1.8rem);
            line-height: 1.1;
            margin-bottom: 0.3rem;
        }

        .hero-copy {
            color: var(--tailor-muted);
            font-size: 0.9rem;
            max-width: 700px;
        }

        .hero-action .btn-tailor,
        .table-toolbar .btn-tailor {
            min-width: 190px;
            min-height: 2.5rem;
            font-size: 0.88rem;
        }

        .table-toolbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .report-toolbar,
        .category-summary-panel {
            padding: 1rem;
        }

        .report-workspace {
            display: grid;
            grid-template-columns: minmax(280px, 320px) minmax(0, 1fr);
            gap: 1rem;
            align-items: start;
        }

        .report-sidebar {
            display: grid;
            gap: 1rem;
            position: sticky;
            top: 96px;
            align-self: start;
        }

        .report-results-card {
            min-width: 0;
        }

        .report-toolbar {
            padding: 1rem 1.05rem;
        }

        .report-toolbar-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.85rem;
            margin-bottom: 0.9rem;
        }

        .report-toolbar-head .btn {
            min-width: 0;
            flex-shrink: 0;
        }

        .report-toolbar-actions {
            display: flex;
            justify-content: flex-start;
        }

        .report-download-icon {
            width: 2.6rem;
            height: 2.6rem;
            min-height: 2.6rem !important;
            min-width: 2.6rem !important;
            padding: 0 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: transparent !important;
            border: 1px solid rgba(190, 129, 16, 0.34) !important;
            box-shadow: none !important;
            color: #b67c12 !important;
            border-radius: 0.7rem !important;
            flex: 0 0 auto;
        }

        .report-download-icon svg {
            width: 1.1rem !important;
            height: 1.1rem !important;
            display: block;
            stroke: currentColor;
            stroke-width: 2.3;
            flex: 0 0 auto;
        }

        .report-download-icon:hover,
        .report-download-icon:focus {
            background: rgba(215, 154, 30, 0.12) !important;
            border: 1px solid rgba(190, 129, 16, 0.42) !important;
            box-shadow: none !important;
            color: #8f620d !important;
            opacity: 1;
        }

        .report-toolbar-title {
            color: var(--tailor-white);
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 0.18rem;
        }

        .report-toolbar-copy {
            color: var(--tailor-muted);
            font-size: 0.6rem;
            margin: 0;
            line-height: 1.25;
        }

        .report-filter-form {
            --bs-gutter-x: 0;
            --bs-gutter-y: 0;
            display: grid;
            gap: 0.8rem;
        }

        .report-filter-col {
            display: grid;
            gap: 0.42rem;
            width: 100%;
        }

        .report-toolbar .report-filter-form > [class*="col-"] {
            width: 100%;
            max-width: 100%;
            flex: 0 0 100%;
            padding-left: 0;
            padding-right: 0;
        }

        .report-filter-col .form-label {
            margin-bottom: 0;
        }

        .report-toolbar .form-control,
        .report-toolbar .form-select,
        .report-toolbar .btn {
            min-height: 2.7rem;
        }

        .report-toolbar .form-control,
        .report-toolbar .form-select {
            border-radius: 0.9rem !important;
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.08);
            padding-inline: 0.9rem;
        }

        .report-filter-actions {
            display: flex;
            align-items: stretch;
            justify-content: stretch;
            flex-wrap: nowrap;
            gap: 0.65rem;
        }

        .report-filter-actions .btn {
            flex: 1 1 50%;
            min-width: 0;
        }

        .report-filter-actions .btn-outline-secondary {
            background: transparent;
        }

        .report-summary-section {
            display: grid;
            gap: 1rem;
        }

        .category-summary-toggle {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0;
            border: 0;
            background: transparent;
            text-align: left;
        }

        .category-summary-toggle-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.07);
            color: var(--tailor-white);
            background: #232323;
            flex-shrink: 0;
            transition: transform 0.18s ease;
        }

        .category-summary-toggle[aria-expanded="true"] .category-summary-toggle-icon {
            transform: rotate(180deg);
        }

        .category-summary-body {
            margin-top: 0.8rem;
        }

        .category-summary-title {
            color: var(--tailor-white);
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 0.12rem;
        }

        .category-summary-copy {
            color: var(--tailor-muted);
            font-size: 0.86rem;
        }

        .category-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.7rem;
            margin-top: 0.8rem;
        }

        .category-summary-card {
            border-radius: 1.1rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: #222222;
            padding: 1rem;
            min-height: 96px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .category-summary-label {
            color: var(--tailor-white);
            font-size: 0.84rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .category-summary-price {
            color: var(--tailor-muted);
            font-size: 0.72rem;
            margin-top: 0.28rem;
            font-weight: 600;
        }

        .category-summary-qty {
            color: var(--tailor-gold);
            font-size: 1.28rem;
            line-height: 1;
            margin-top: 0.45rem;
            font-family: Georgia, "Times New Roman", serif;
        }

        .category-summary-footer {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 0.6rem;
            margin-top: 0.55rem;
        }

        .category-summary-meta {
            color: var(--tailor-muted);
            font-size: 0.54rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        .category-summary-amount {
            color: var(--tailor-white);
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
        }

        .stat-card {
            border-radius: 1.55rem;
            padding: 1rem 1.05rem;
            min-height: 122px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: auto -10% -50% auto;
            width: 170px;
            height: 170px;
            border-radius: 50%;
            filter: blur(10px);
            opacity: 0.18;
            background: var(--stat-glow, rgba(215, 167, 44, 0.22));
            pointer-events: none;
        }

        .stat-card.orders { --stat-glow: rgba(210, 178, 109, 0.28); }
        .stat-card.thobes { --stat-glow: rgba(181, 139, 59, 0.22); }
        .stat-card.revenue { --stat-glow: rgba(13, 13, 13, 0.16); }

        .stat-label {
            color: #8f897f;
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            font-weight: 700;
        }

        .stat-value {
            color: var(--tailor-white);
            font-size: clamp(1.55rem, 2.8vw, 2.1rem);
            line-height: 1;
            margin-top: 0.35rem;
            font-family: Georgia, "Times New Roman", serif;
        }

        .stat-value.revenue {
            color: var(--tailor-gold);
            font-size: clamp(1.35rem, 2.6vw, 1.8rem);
        }

        .table-card {
            border-radius: 1rem;
            padding: 1rem;
            background: #1b1b1b;
            border: 1px solid rgba(255, 255, 255, 0.08);
            max-width: 100%;
            overflow: hidden;
        }

        .table-card .table-responsive {
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 0.2rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(197, 150, 47, 0.65) rgba(197, 150, 47, 0.08);
            max-width: 100%;
        }

        .table-card .table-responsive::-webkit-scrollbar {
            height: 10px;
        }

        .table-card .table-responsive::-webkit-scrollbar-track {
            background: rgba(197, 150, 47, 0.08);
            border-radius: 999px;
        }

        .table-card .table-responsive::-webkit-scrollbar-thumb {
            background: linear-gradient(90deg, rgba(215, 167, 44, 0.95), rgba(184, 135, 25, 0.95));
            border-radius: 999px;
        }

        .table-card .table {
            color: var(--tailor-text);
            margin-bottom: 0;
            min-width: 1260px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-card .table thead th {
            color: #a79e8f;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.015);
            border-bottom: 1px solid rgba(201, 168, 76, 0.14);
            white-space: nowrap;
            padding: 0.9rem 0.85rem;
            vertical-align: middle;
        }

        .table-card .table thead th:first-child {
            border-top-left-radius: 0.85rem;
        }

        .table-card .table thead th:last-child {
            border-top-right-radius: 0.85rem;
        }

        .table-card .table.report-table thead th,
        .table-card .table.invoice-table thead th {
            color: #d4c7b2;
            background:
                linear-gradient(180deg, rgba(201, 168, 76, 0.08), rgba(255, 255, 255, 0.015));
            border-bottom: 1px solid rgba(201, 168, 76, 0.2);
            box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.02);
        }

        .table-card .table.report-table thead th:first-child,
        .table-card .table.invoice-table thead th:first-child {
            box-shadow: inset 3px 0 0 rgba(201, 168, 76, 0.9), inset 0 -1px 0 rgba(255, 255, 255, 0.02);
        }

        .table-card .table tbody td {
            color: var(--tailor-text);
            padding: 0.82rem 0.85rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            vertical-align: middle;
            background: #1e1d1a;
            font-size: 0.86rem;
            line-height: 1.35;
        }

        .table-card .table tbody tr:nth-child(even) td {
            background: #1a1916;
        }

        .table-card .table tbody tr:hover td {
            background: rgba(201, 168, 76, 0.04);
        }

        .table-card .table tbody tr:hover td:first-child {
            box-shadow: inset 3px 0 0 #c9a84c;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 96px;
            padding: 0.36rem 0.8rem;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 500;
            border: 1px solid transparent;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
        }

        .status-pill.pending {
            color: #8d5a12;
            background: rgba(215, 154, 30, 0.12);
            border-color: rgba(215, 154, 30, 0.28);
            box-shadow: none;
        }

        .status-pill.in-progress {
            color: #7a5c18;
            background: rgba(178, 142, 52, 0.13);
            border-color: rgba(178, 142, 52, 0.26);
            box-shadow: none;
        }

        .status-pill.completed {
            color: #1f7a4f;
            background: rgba(33, 145, 89, 0.12);
            border-color: rgba(33, 145, 89, 0.28);
            box-shadow: none;
        }

        .amount-cell {
            color: var(--tailor-gold);
            font-weight: 800;
            font-size: 0.88rem;
            white-space: nowrap;
        }

        .date-cell {
            white-space: nowrap;
            min-width: 178px;
            font-size: 0.8rem;
        }

        .tailor-cell {
            min-width: 150px;
            color: var(--tailor-white);
            font-weight: 700;
            white-space: nowrap;
        }

        .note-col {
            min-width: 240px;
        }

        .note-cell {
            min-width: 240px;
            max-width: 320px;
            white-space: normal;
            line-height: 1.35;
            font-size: 0.8rem;
        }

        .subdued {
            color: var(--tailor-muted) !important;
        }

        .details-cell {
            min-width: 96px;
        }

        .details-btn {
            min-height: auto;
            padding: 0;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
            color: var(--tailor-gold) !important;
            background: transparent !important;
            border: 0 !important;
            border-radius: 0 !important;
            text-decoration: underline;
            text-underline-offset: 0.18rem;
            box-shadow: none !important;
        }

        .details-btn:hover,
        .details-btn:focus {
            color: #8f682c !important;
            text-decoration-thickness: 2px;
        }

        .actions-cell {
            min-width: 190px;
        }

        .actions-wrap {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            flex-wrap: nowrap;
        }

        .action-btn {
            width: 44px;
            height: 44px;
            border-radius: 0.95rem;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            box-shadow: 0 10px 20px rgba(197, 150, 47, 0.08);
        }

        .action-btn.edit-btn {
            background: #232323;
            color: var(--tailor-white);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .action-btn.edit-btn:hover {
            background: #2a2a2a;
            color: #ffffff;
        }

        .action-btn.hide-btn {
            background: rgba(255, 255, 255, 0.03);
            color: var(--tailor-white);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .action-btn.hide-btn:hover {
            background: #2a2a2a;
            color: #ffffff;
        }

        .action-btn.print-btn {
            background: rgba(201, 168, 76, 0.06);
            color: var(--tailor-gold);
            border: 1px solid rgba(201, 168, 76, 0.22);
        }

        .action-btn.print-btn:hover {
            background: var(--tailor-gold);
            color: #111111;
        }

        .action-btn svg {
            width: 16px;
            height: 16px;
        }

        .table-card .table tbody td.actions-cell {
            padding-top: 0.85rem;
            padding-bottom: 0.85rem;
        }

        .table-card .table tbody td:first-child,
        .table-card .table tbody td:nth-child(2),
        .table-card .table tbody td:nth-child(3),
        .table-card .table tbody td:nth-child(4) {
            color: var(--tailor-white);
        }

        .invoice-search {
            margin-bottom: 1rem !important;
            justify-content: flex-end;
        }

        .invoice-search .search-shell {
            position: relative;
            max-width: 420px;
            margin-left: auto;
        }

        .invoice-search .search-shell svg {
            position: absolute;
            top: 50%;
            left: 0.9rem;
            width: 1rem;
            height: 1rem;
            transform: translateY(-50%);
            color: var(--tailor-muted);
            pointer-events: none;
        }

        .invoice-search .form-control,
        .invoice-search .form-select {
            min-height: 2.5rem;
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
            background: #171614;
            border: 1px solid rgba(201, 168, 76, 0.1);
            color: var(--tailor-white);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.015);
        }

        .invoice-search .form-control {
            padding-left: 2.7rem;
        }

        .invoice-search .form-control::placeholder {
            color: var(--tailor-muted);
        }

        .invoice-search .form-control:focus,
        .invoice-search .form-select:focus {
            background: #171614;
            color: var(--tailor-white);
            border-color: rgba(201, 168, 76, 0.24);
            box-shadow: 0 0 0 0.18rem rgba(201, 168, 76, 0.08);
        }

        .table-summary-row td {
            background:
                linear-gradient(180deg, #f7f7f7 0%, #efefef 100%) !important;
            border-top: 1px solid rgba(201, 168, 76, 0.22);
            border-bottom: 0 !important;
            border-left: 0 !important;
            border-right: 0 !important;
            font-weight: 700;
            padding: 1rem 1.15rem !important;
            vertical-align: middle;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.85);
        }

        .table-summary-row td:first-child {
            border-radius: 1rem 0 0 1rem;
            padding-left: 1.35rem !important;
            box-shadow: inset 3px 0 0 #111111 !important;
        }

        .table-summary-row td:last-child {
            border-radius: 0 1rem 1rem 0;
            padding-right: 1.35rem !important;
        }

        .table-summary-row .summary-label {
            color: rgba(17, 17, 17, 0.56);
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-size: 0.62rem;
            display: inline-block;
            margin-bottom: 0.22rem;
            font-weight: 700;
        }

        .table-summary-meta {
            display: block;
            color: rgba(17, 17, 17, 0.62);
            font-size: 0.76rem;
            font-weight: 500;
            line-height: 1.45;
        }

        .table-summary-value {
            color: #111111;
            font-size: 1.08rem;
            font-weight: 700;
            line-height: 1.15;
        }

        .table-summary-row .amount-cell,
        .table-summary-row td[colspan="2"] {
            min-width: 140px;
        }

        .table-card .table tbody tr.table-summary-row:hover td {
            background: linear-gradient(180deg, #f9f9f9 0%, #f1f1f1 100%) !important;
        }

        .table-card .table tbody tr.table-summary-row:hover td:first-child {
            box-shadow: inset 3px 0 0 #111111 !important;
        }

        .details-modal {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 1.25rem 2rem;
            z-index: 1070;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.18s ease;
        }

        .details-modal.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        .details-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15, 15, 15, 0.32);
            backdrop-filter: blur(2px);
        }

        .details-modal-dialog {
            position: relative;
            display: flex;
            flex-direction: column;
            width: min(1140px, 100%);
            max-height: calc(100vh - 3.25rem);
            overflow: hidden;
            border-radius: 1rem;
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.08);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.16);
        }

        .details-modal-header {
            position: relative;
            padding: 1.15rem 1.25rem 0.95rem;
            border-bottom: 1px solid rgba(17, 17, 17, 0.08);
        }

        .details-modal-title {
            margin: 0;
            color: #111111;
            font-size: clamp(1.25rem, 1.9vw, 1.7rem);
            text-align: center;
        }

        .details-modal-close {
            position: absolute;
            top: 1.2rem;
            right: 1.25rem;
            width: 2.5rem;
            height: 2.5rem;
            border: 1px solid rgba(17, 17, 17, 0.12);
            border-radius: 999px;
            background: #ffffff;
            color: #111111;
            font-size: 1.15rem;
            line-height: 1;
            flex-shrink: 0;
        }

        .details-modal-close:hover {
            background: #f6f6f6;
            color: #111111;
        }

        .details-modal-body {
            flex: 1 1 auto;
            min-height: 0;
            padding: 1rem 1.25rem 1.75rem;
            overflow-y: auto;
        }

        .details-hero {
            display: grid;
            grid-template-columns: minmax(180px, 1fr) minmax(0, 1fr) minmax(180px, 1fr);
            gap: 0.9rem;
            align-items: center;
            margin-bottom: 0;
            width: 100%;
        }

        .details-hero-side {
            min-width: 0;
            padding: 0;
            border-radius: 0;
            background: transparent;
            border: 0;
        }

        .details-hero-label {
            display: block;
            color: #8c857b;
            font-size: 0.68rem;
            font-weight: 700;
            margin-bottom: 0.12rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
        }

        .details-hero-value {
            color: #111111;
            font-size: 0.86rem;
        }

        .details-hero-center {
            text-align: center;
            min-width: 0;
            align-self: center;
            grid-column: 2;
            padding-inline: 0;
        }

        .details-hero-spacer {
            min-width: 0;
        }

        .details-grid {
            display: grid;
            gap: 1rem;
        }

        .details-section {
            border: 1px solid rgba(17, 17, 17, 0.08);
            border-radius: 0.95rem;
            overflow: hidden;
            background: #ffffff;
        }

        .details-section-title {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid rgba(17, 17, 17, 0.08);
            font-size: 0.78rem;
            font-weight: 700;
            color: #111111;
            background: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.18em;
        }

        .details-section-body {
            padding: 0.9rem;
        }

        .details-info-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .details-info-grid.two-col {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .details-info-grid > div {
            padding: 0.8rem 0.9rem;
            border-radius: 0;
            background: transparent;
            border: 0;
        }

        .details-item-label {
            display: block;
            color: #8f897f;
            font-size: 0.72rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        .details-item-value {
            color: var(--tailor-text);
            font-size: 0.92rem;
            line-height: 1.45;
            font-weight: 600;
            word-break: break-word;
        }

        .details-note {
            padding: 0.2rem 0;
            color: var(--tailor-text);
            font-size: 0.92rem;
            line-height: 1.7;
            white-space: pre-wrap;
            word-break: break-word;
            border-radius: 0;
            background: transparent;
            border: 0;
        }

        .details-status-form {
            padding: 0;
        }

        .details-status-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.55rem;
            margin-top: 0.75rem;
            flex-wrap: wrap;
        }

        .details-status-form .form-select {
            min-height: 40px;
            border-radius: 0 !important;
            font-size: 0.84rem;
        }

        .details-update-btn {
            min-width: 120px;
            min-height: 38px;
            padding: 0.45rem 0.9rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #ffffff !important;
        }

        .details-close-btn {
            min-width: 88px;
            min-height: 38px;
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #111111 !important;
            background: #ffffff !important;
            border: 1px solid rgba(17, 17, 17, 0.14) !important;
        }

        .details-close-btn:hover,
        .details-close-btn:focus {
            color: #111111 !important;
            background: #f5f5f5 !important;
            border-color: rgba(17, 17, 17, 0.18) !important;
            box-shadow: none !important;
        }

        body.modal-open {
            overflow: hidden;
        }

        @media (max-width: 1199.98px) {
            .report-workspace {
                grid-template-columns: 1fr;
            }

            .report-sidebar {
                position: static;
            }
        }

        @media (max-width: 991.98px) {
            .actions-wrap {
                flex-wrap: wrap;
            }

            .actions-cell {
                min-width: 240px;
            }

            .details-hero-center {
                text-align: center;
            }

            .report-toolbar-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .details-info-grid,
            .details-info-grid.two-col {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .invoice-search .search-shell {
                max-width: none;
            }

            .details-hero {
                grid-template-columns: 1fr;
            }

            .details-hero-spacer {
                display: none;
            }

            .details-hero-center {
                padding-inline: 0;
            }

            .details-modal-header,
            .details-modal-body {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .details-modal {
                padding-bottom: 1.5rem;
            }

            .details-modal-dialog {
                max-height: calc(100vh - 2.5rem);
            }

            .details-modal-close {
                right: 1rem;
            }

            .details-info-grid,
            .details-info-grid.two-col {
                grid-template-columns: 1fr;
            }

            .details-status-actions {
                justify-content: stretch;
            }

            .details-status-actions .btn {
                width: 100%;
            }

            .table-toolbar {
                justify-content: stretch;
            }

            .table-toolbar .btn {
                width: 100%;
            }
        }

        .hero-panel,
        .table-card,
        .stat-card,
        .report-toolbar,
        .category-summary-panel,
        .category-summary-card {
            background: #ffffff;
            border-color: rgba(17, 17, 17, 0.08);
            box-shadow: 0 10px 24px rgba(17, 17, 17, 0.08);
        }

        .category-summary-toggle-icon,
        .action-btn.edit-btn,
        .action-btn.hide-btn {
            background: #f5f5f5;
            color: #111111;
            border-color: rgba(17, 17, 17, 0.1);
        }

        .report-toolbar {
            background: linear-gradient(180deg, #ffffff 0%, #fcfaf6 100%);
            border-color: rgba(17, 17, 17, 0.07);
            box-shadow: 0 12px 28px rgba(17, 17, 17, 0.06);
        }

        .report-toolbar-head {
            padding-bottom: 0.8rem;
            border-bottom: 1px solid rgba(17, 17, 17, 0.06);
        }

        .report-toolbar-title {
            color: #111111;
        }

        .report-toolbar-copy {
            color: rgba(17, 17, 17, 0.58);
        }

        .report-filter-col .form-label {
            color: rgba(17, 17, 17, 0.58);
            font-size: 0.64rem;
            letter-spacing: 0.14em;
        }

        .report-toolbar .form-control,
        .report-toolbar .form-select {
            min-height: 2.85rem;
            background: #fbfaf7;
            border-color: rgba(17, 17, 17, 0.1);
            border-radius: 0.95rem !important;
            color: #111111;
        }

        .report-toolbar .form-control::placeholder {
            color: rgba(17, 17, 17, 0.42);
        }

        .report-toolbar .form-control:focus,
        .report-toolbar .form-select:focus {
            background: #ffffff;
            border-color: rgba(215, 154, 30, 0.24);
            box-shadow: 0 0 0 0.18rem rgba(215, 154, 30, 0.08);
        }

        .report-filter-actions {
            gap: 0.55rem;
        }

        .report-filter-actions .btn {
            min-height: 2.85rem;
            border-radius: 0.95rem !important;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .report-filter-actions .btn-tailor {
            background: #111111;
            border-color: #111111;
        }

        .report-filter-actions .btn-tailor:hover,
        .report-filter-actions .btn-tailor:focus {
            background: #111111;
            border-color: #111111;
        }

        .report-filter-actions .btn-outline-secondary {
            background: #ffffff;
            color: #111111;
            border-color: rgba(17, 17, 17, 0.12);
        }

        .category-summary-title,
        .category-summary-label,
        .category-summary-amount,
        .stat-value,
        .table-summary-row .summary-label,
        .table-summary-value,
        .details-modal-title,
        .details-item-value,
        .details-section-title,
        .details-hero-value,
        .details-note,
        .details-hero-side,
        .table-card .table tbody td:first-child,
        .table-card .table tbody td:nth-child(2),
        .table-card .table tbody td:nth-child(3),
        .table-card .table tbody td:nth-child(4) {
            color: #111111;
        }

        .stat-value.revenue,
        .amount-cell,
        .details-btn,
        .action-btn.print-btn,
        .details-modal-close:hover {
            color: #111111 !important;
        }

        .table-card .table thead th,
        .table-card .table.report-table thead th,
        .table-card .table.invoice-table thead th {
            color: #ffffff;
            background: #111111;
            border-bottom-color: rgba(255, 255, 255, 0.06);
            box-shadow: none;
        }

        .table-card .table.report-table thead th:first-child,
        .table-card .table.invoice-table thead th:first-child,
        .table-card .table tbody tr:hover td:first-child {
            box-shadow: inset 3px 0 0 #111111;
        }

        .table-card .table tbody td,
        .table-card .table tbody tr:nth-child(even) td {
            color: var(--tailor-text);
            background: #ffffff;
            border-bottom-color: rgba(17, 17, 17, 0.08);
        }

        .table-card .table tbody tr:nth-child(even) td {
            background: #fafafa;
        }

        .table-card .table tbody tr:hover td {
            background: #f3f3f3;
        }

        .status-pill {
            box-shadow: none;
        }

        .status-pill.pending,
        .status-pill.in-progress {
            background: #f8f3e8;
        }

        .status-pill.pending {
            color: #8d5a12;
            border-color: rgba(215, 154, 30, 0.2);
        }

        .status-pill.in-progress {
            color: #7a5c18;
            border-color: rgba(178, 142, 52, 0.22);
        }

        .status-pill.completed {
            color: #1f7a4f;
            background: #ebf7f0;
            border-color: rgba(33, 145, 89, 0.22);
        }

        .invoice-search .form-control,
        .invoice-search .form-select,
        .details-modal-close,
        .details-item,
        .details-note,
        .details-section-body {
            background: #ffffff;
            color: #111111;
            border-color: rgba(17, 17, 17, 0.1);
            box-shadow: none;
        }

        .invoice-search .form-control:focus,
        .invoice-search .form-select:focus {
            background: #f3f3f3;
        }

        .table-summary-row td {
            background:
                linear-gradient(180deg, #f9f9f9 0%, #f1f1f1 100%) !important;
            border-top-color: rgba(17, 17, 17, 0.08);
        }

        .details-modal-dialog {
            background: #ffffff;
            border-color: rgba(17, 17, 17, 0.12);
            box-shadow: 0 30px 90px rgba(17, 17, 17, 0.16);
        }

        .details-modal-header,
        .details-section,
        .details-item {
            border-bottom-color: rgba(17, 17, 17, 0.08);
            border-color: rgba(17, 17, 17, 0.08);
        }

        .details-modal-close {
            color: #111111;
        }

        .details-status-actions .btn-outline-secondary {
            color: #111111;
            border-color: rgba(17, 17, 17, 0.2);
        }
    </style>

    <div class="orders-view">
        <div class="orders-content">
            @if ($pageMode === 'report')
                @if ($canFilterTailors)
                    <span class="legacy-copy">Tailor Wise</span>
                @endif
            @else
                <div class="orders-top-action">
                    <span class="legacy-copy">Create New Invoice</span>
                    <a href="{{ route('admin.orders.create') }}" class="btn btn-tailor create-tailor-btn px-4">Create New Tailor</a>
                </div>
            @endif

            @if ($pageMode === 'report')
                <div class="report-workspace">
                    <div class="report-sidebar">
                        <div class="report-toolbar">
                            <div class="report-toolbar-head">
                                <div>
                                    <h3 class="report-toolbar-title">Refine Report</h3>
                                    <p class="report-toolbar-copy">Filter by category, invoice, fatora, and date.</p>
                                </div>
                                <div class="report-toolbar-actions">
                                    <a
                                        href="{{ route('admin.orders.index', array_merge($filters, ['export' => 'pdf'])) }}"
                                        class="btn btn-tailor report-download-icon"
                                        aria-label="Download PDF"
                                        title="Download PDF"
                                    >
                                        <span class="legacy-copy">Download PDF</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M12 3v11"/>
                                            <path d="M7 10l5 5 5-5"/>
                                            <path d="M5 19h14"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <form method="GET" action="{{ route('admin.orders.index') }}" class="row align-items-end report-filter-form">
                        <input type="hidden" name="view" value="report">
                        @if ($canFilterTailors)
                            <div class="col-12 col-md-6 col-xl report-filter-col">
                                <label for="assigned_user_id" class="form-label">Tailor</label>
                                <select id="assigned_user_id" name="assigned_user_id" class="form-select rounded-4">
                                    <option value="">All Tailors</option>
                                    @foreach ($assignableUsers as $assignableUser)
                                        <option value="{{ $assignableUser->id }}" @selected($filters['assigned_user_id'] !== '' && (int) $filters['assigned_user_id'] === $assignableUser->id)>
                                            {{ $assignableUser->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="assigned_user_id" value="{{ $filters['assigned_user_id'] }}">
                        @endif

                        <div class="col-12 col-md-6 col-xl report-filter-col">
                            <label for="thobe_category" class="form-label">Category</label>
                            <select id="thobe_category" name="thobe_category" class="form-select rounded-4">
                                <option value="">All Categories</option>
                                @foreach ($categories as $categoryValue => $category)
                                    <option value="{{ $categoryValue }}" @selected($filters['thobe_category'] === $categoryValue)>
                                        {{ $category['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6 col-xl report-filter-col">
                            <label for="invoice_number" class="form-label">Invoice #</label>
                            <input type="text" id="invoice_number" name="invoice_number" value="{{ $filters['invoice_number'] }}" class="form-control rounded-4" placeholder="Invoice number">
                        </div>

                        <div class="col-12 col-md-6 col-xl report-filter-col">
                            <label for="fatora_number" class="form-label">Fatora Number</label>
                            <input type="text" id="fatora_number" name="fatora_number" value="{{ $filters['fatora_number'] }}" class="form-control rounded-4" placeholder="Fatora number">
                        </div>

                        <div class="col-12 col-md-6 col-xl report-filter-col">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] }}" class="form-control rounded-4">
                        </div>

                        <div class="col-12 col-md-6 col-xl report-filter-col">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] }}" class="form-control rounded-4">
                        </div>

                        <div class="col-12 col-md-6 col-xl-auto report-filter-actions">
                            <button type="submit" class="btn btn-tailor rounded-4">Filter</button>
                            <a href="{{ route('admin.orders.index', ['view' => 'report']) }}" class="btn btn-outline-secondary rounded-4">Reset</a>
                        </div>
                            </form>
                        </div>

                        @if ($hasActiveReportFilters)
                            <div class="report-summary-section">
                                <div class="category-summary-panel">
                                    <div class="category-summary-body" id="category-summary-body">
                                        <div class="mb-3">
                                            <h3 class="category-summary-title mb-1">
                                                {{ $filters['thobe_category'] === '' ? 'All Categories Summary' : 'Selected Category Summary' }}
                                            </h3>
                                            <p class="category-summary-copy mb-0">
                                                Quantity and amount are calculated based on the current report filters.
                                            </p>
                                        </div>
                                        <div class="category-summary-grid">
                                            @foreach ($reportCategorySummaries as $summary)
                                                <div class="category-summary-card" data-category-summary="{{ $summary['key'] }}">
                                                    <div class="category-summary-label">{{ $summary['label'] }}</div>
                                                    <div class="category-summary-price">Single Price: {{ number_format($summary['unit_price'], 2) }} QAR</div>
                                                    <div class="category-summary-qty" data-category-quantity="{{ $summary['quantity'] }}">{{ $summary['quantity'] }}</div>
                                                    <div class="category-summary-footer">
                                                        <div class="category-summary-meta">Total Thobes</div>
                                                        <div class="category-summary-amount" data-category-amount="{{ number_format($summary['amount'], 2, '.', '') }}">{{ number_format($summary['amount'], 2) }} QAR</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                

            @endif

                <div class="table-card {{ $pageMode === 'report' ? 'report-results-card' : '' }}">
                @if ($pageMode !== 'report')
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end invoice-search" id="invoice-search-form">
                        <input type="hidden" name="view" value="invoices">
                        <div class="col-12 col-xl-5 ms-xl-auto">
                            <div class="search-shell">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <path d="m20 20-3.5-3.5"></path>
                                </svg>
                                <input
                                    type="text"
                                    id="search"
                                    name="search"
                                    value="{{ $filters['search'] }}"
                                    class="form-control rounded-4"
                                    placeholder="Search invoice, fatora, or tailor">
                            </div>
                        </div>
                    </form>
                @endif

                <div class="table-responsive">
                    <table class="table align-middle {{ $pageMode === 'report' ? 'report-table' : 'invoice-table' }}">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Fatora #</th>
                                <th>Tailor Name</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Qty</th>
                                <th class="note-col">Note</th>
                                <th>Status</th>
                                <th>Total Amount</th>
                                <th>View</th>
                                @if ($canManageSettings && $pageMode !== 'report')
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                @php
                                    $displayTailorName = $order->assignedUser?->name ?? $order->tailor_name ?? 'Not assigned';
                                    $detailPayload = [
                                        'invoice_number' => $order->invoice_number,
                                        'fatora_number' => $order->fatora_number ?: 'N/A',
                                        'tailor_name' => $displayTailorName,
                                        'category' => $order->category_label,
                                        'quantity' => (string) $order->quantity,
                                        'order_date' => $order->order_date->format('d M Y h:i A'),
                                        'unit_price' => number_format((float) $order->unit_price, 2) . ' QAR',
                                        'total_amount' => number_format((float) $order->total_price, 2) . ' QAR',
                                        'status' => $order->status_label,
                                        'status_value' => $order->status,
                                        'completed_at' => optional($order->completed_at)->format('d M Y h:i A') ?: 'Not completed yet',
                                        'assigned_tailor' => $displayTailorName,
                                        'added_by' => $order->creator?->name ?? 'N/A',
                                        'note' => $order->note ?: 'No note added',
                                        'update_status_url' => route('admin.orders.update-status', $order),
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ $order->invoice_number }}</td>
                                    <td>{{ $order->fatora_number ?: 'N/A' }}</td>
                                    <td class="tailor-cell">{{ $displayTailorName }}</td>
                                    <td class="date-cell">{{ $order->order_date->format('d M Y h:i A') }}</td>
                                    <td>{{ $order->category_label }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td class="note-cell" title="{{ $order->note ?: 'No note added' }}">{{ \Illuminate\Support\Str::limit($order->note ?: 'No note added', 40) }}</td>
                                    <td>
                                        <span class="status-pill {{ $order->status === \App\Models\TailorOrder::STATUS_COMPLETED ? 'completed' : ($order->status === \App\Models\TailorOrder::STATUS_IN_PROGRESS ? 'in-progress' : 'pending') }}">
                                            {{ $order->status_label }}
                                        </span>
                                        @if ($order->completed_at)
                                            <div class="small subdued mt-2">{{ $order->completed_at->format('d M Y h:i A') }}</div>
                                        @endif
                                    </td>
                                    <td class="amount-cell">{{ number_format($order->total_price, 2) }} QAR</td>
                                    <td class="details-cell">
                                        <button
                                            type="button"
                                            class="btn btn-outline-dark details-btn view-details-btn"
                                            data-order="{{ json_encode($detailPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}">
                                            View Details
                                        </button>
                                    </td>
                                    @if ($canManageSettings && $pageMode !== 'report')
                                        <td class="actions-cell">
                                            <div class="actions-wrap">
                                                <a href="{{ route('admin.orders.edit', $order) }}" class="btn action-btn edit-btn" title="Edit Invoice" aria-label="Edit Invoice">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path d="M12 20h9"/>
                                                        <path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4Z"/>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="delete-order-form" data-invoice-number="{{ $order->invoice_number }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn action-btn hide-btn" title="Delete Invoice" aria-label="Delete Invoice">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path d="M3 6h18"/>
                                                            <path d="M8 6V4h8v2"/>
                                                            <path d="M19 6l-1 14H6L5 6"/>
                                                            <path d="M10 11v6"/>
                                                            <path d="M14 11v6"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <a href="{{ route('admin.orders.receipt', $order) }}" class="btn action-btn print-btn" target="_blank" rel="noopener" aria-label="Print Receipt" title="Print Receipt">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path d="M6 9V4h12v5"/>
                                                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                                        <path d="M6 14h12v6H6z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $canManageSettings && $pageMode !== 'report' ? 11 : 10 }}" class="text-center py-5 subdued">No invoices have been added yet.</td>
                                </tr>
                            @endforelse
                            @if ($pageMode === 'report' && $orders->count())
                                <tr class="table-summary-row">
                                    <td colspan="6">
                                        <span class="summary-label">Report Summary</span>
                                        <span class="table-summary-meta">Filtered totals for the current report view.</span>
                                    </td>
                                    <td colspan="2">
                                        <span class="summary-label">Records</span>
                                        <span class="table-summary-value">{{ $visibleOrderCount }}</span>
                                    </td>
                                    <td class="amount-cell">
                                        <span class="summary-label">Amount</span>
                                        <span class="table-summary-value">{{ number_format($visibleOrderAmount, 2) }} QAR</span>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
            @if ($pageMode === 'report')
                </div>
            @endif
        </div>
    </div>

    <div class="details-modal" id="order-details-modal" hidden>
        <div class="details-modal-backdrop" data-close-details-modal></div>
        <div class="details-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="order-details-title">
            <div class="details-modal-header">
                <div class="details-hero">
                    <div class="details-hero-side">
                        <span class="details-hero-label">Start Date & Time:</span>
                        <div class="details-hero-value" data-detail="order_date"></div>
                    </div>
                    <div class="details-hero-center">
                        <h3 class="details-modal-title" id="order-details-title">Invoice Details</h3>
                    </div>
                    <div class="details-hero-spacer" aria-hidden="true"></div>
                </div>
                <button type="button" class="details-modal-close" aria-label="Close details modal" data-close-details-modal>&times;</button>
            </div>
            <div class="details-modal-body">
                <div class="details-grid">
                    <section class="details-section">
                        <div class="details-section-title">Invoice Details</div>
                        <div class="details-section-body">
                            <div class="details-info-grid">
                                <div>
                                    <span class="details-item-label">Invoice #:</span>
                                    <div class="details-item-value" data-detail="invoice_number"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Fatora #:</span>
                                    <div class="details-item-value" data-detail="fatora_number"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Category:</span>
                                    <div class="details-item-value" data-detail="category"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Quantity:</span>
                                    <div class="details-item-value" data-detail="quantity"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Unit Price:</span>
                                    <div class="details-item-value" data-detail="unit_price"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Total Amount:</span>
                                    <div class="details-item-value" data-detail="total_amount"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="details-section">
                        <div class="details-section-title">People Info</div>
                        <div class="details-section-body">
                            <div class="details-info-grid">
                                <div>
                                    <span class="details-item-label">Assigned Tailor:</span>
                                    <div class="details-item-value" data-detail="assigned_tailor"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Added By:</span>
                                    <div class="details-item-value" data-detail="added_by"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Completed At:</span>
                                    <div class="details-item-value" data-detail="completed_at"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="details-section">
                        <div class="details-section-title">Other Info</div>
                        <div class="details-section-body">
                            <div class="details-info-grid two-col">
                                <div>
                                    <span class="details-item-label">Current Status:</span>
                                    <div class="details-item-value" data-detail="status"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Order Date & Time:</span>
                                    <div class="details-item-value" data-detail="order_date"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="details-section">
                        <div class="details-section-title">Main Note</div>
                        <div class="details-section-body">
                            <div class="details-note" data-detail="note"></div>
                        </div>
                    </section>

                    @if ($canManageSettings)
                        <section class="details-section">
                            <div class="details-section-title">Status Update</div>
                            <div class="details-section-body">
                                <form method="POST" class="details-status-form" id="modal-status-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" id="modal-status-select" class="form-select" aria-label="Status">
                                        @foreach (\App\Models\TailorOrder::statuses() as $statusValue => $statusLabel)
                                            <option value="{{ $statusValue }}">{{ $statusLabel }}</option>
                                        @endforeach
                                    </select>

                                    <div class="details-status-actions">
                                        <button type="submit" class="btn btn-tailor details-update-btn">Update Status</button>
                                        <button type="button" class="btn btn-outline-secondary details-close-btn" data-close-details-modal>Close</button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    @else
                        <div class="details-status-actions">
                            <button type="button" class="btn btn-outline-secondary details-close-btn" data-close-details-modal>Close</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const detailsModal = document.getElementById('order-details-modal');
            const detailButtons = document.querySelectorAll('.view-details-btn');
            const closeDetailsButtons = document.querySelectorAll('[data-close-details-modal]');
            const detailsTitle = document.getElementById('order-details-title');
            const modalStatusForm = document.getElementById('modal-status-form');
            const modalStatusSelect = document.getElementById('modal-status-select');
            const detailFields = detailsModal
                ? Array.from(detailsModal.querySelectorAll('[data-detail]')).reduce((fields, field) => {
                    const key = field.dataset.detail;

                    fields[key] = fields[key] || [];
                    fields[key].push(field);

                    return fields;
                }, {})
                : {};

            const closeDetailsModal = () => {
                if (!detailsModal) {
                    return;
                }

                detailsModal.classList.remove('is-open');
                document.body.classList.remove('modal-open');
                window.setTimeout(() => {
                    detailsModal.hidden = true;
                }, 180);
            };

            const openDetailsModal = (order) => {
                if (!detailsModal) {
                    return;
                }

                if (detailsTitle) {
                    detailsTitle.textContent = `Invoice Details - ${order.invoice_number ?? ''}`;
                }

                Object.entries(detailFields).forEach(([key, fields]) => {
                    fields.forEach((field) => {
                        field.textContent = order[key] ?? 'N/A';
                    });
                });

                if (modalStatusForm && order.update_status_url) {
                    modalStatusForm.action = order.update_status_url;
                }

                if (modalStatusSelect && order.status_value) {
                    modalStatusSelect.value = order.status_value;
                }

                detailsModal.hidden = false;
                document.body.classList.add('modal-open');
                window.requestAnimationFrame(() => detailsModal.classList.add('is-open'));
            };

            detailButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    openDetailsModal(JSON.parse(button.dataset.order || '{}'));
                });
            });

            closeDetailsButtons.forEach((button) => {
                button.addEventListener('click', closeDetailsModal);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && detailsModal && !detailsModal.hidden) {
                    closeDetailsModal();
                }
            });
        })();
    </script>

    @if ($pageMode !== 'report')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            (() => {
                const form = document.getElementById('invoice-search-form');
                const searchInput = document.getElementById('search');
                const deleteForms = document.querySelectorAll('.delete-order-form');

                if (form && searchInput) {
                    let timeoutId;

                    searchInput.addEventListener('input', () => {
                        window.clearTimeout(timeoutId);
                        timeoutId = window.setTimeout(() => form.submit(), 350);
                    });
                }

                deleteForms.forEach((deleteForm) => {
                    deleteForm.addEventListener('submit', (event) => {
                        event.preventDefault();

                        const invoiceNumber = deleteForm.dataset.invoiceNumber || 'this invoice';

                        Swal.fire({
                            title: 'Delete invoice?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#c5962f',
                            cancelButtonColor: '#6c757d',
                            background: '#fffdfa',
                            color: '#1f1a17',
                            text: invoiceNumber,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                deleteForm.submit();
                            }
                        });
                    });
                });
            })();
        </script>
    @endif
@endsection
