<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tailor Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --tailor-white: #ffffff;
            --tailor-gold: #c89b2c;
            --tailor-gold-soft: #e7d29a;
            --tailor-black: #111111;
            --tailor-muted: #696257;
        }

        body {
            min-height: 100vh;
            background: #ffffff;
            color: var(--tailor-black);
        }

        .hero-card {
            border: 1px solid rgba(200, 155, 44, 0.24);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 24px 60px rgba(17, 17, 17, 0.06);
        }

        .feature-pill {
            background: rgba(200, 155, 44, 0.12);
            color: var(--tailor-black);
            border: 1px solid rgba(200, 155, 44, 0.2);
        }

        .btn-tailor {
            background: linear-gradient(135deg, #111111, #2b2b2b);
            color: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.92);
            border-radius: 10px;
        }

        .btn-tailor:hover {
            color: #ffffff;
            opacity: 0.95;
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="hero-card rounded-4 p-4 p-lg-5">
                    <span class="feature-pill rounded-pill px-3 py-2 small fw-semibold d-inline-flex mb-3">Laravel + Bootstrap + MVC</span>
                    <h1 class="display-5 fw-bold mb-3">Tailor Management System with Strong Role-Based Access</h1>
                    <p class="lead mb-4">Admin, manager, aur user roles ke saath ek clean tailoring dashboard jahan admin thobe invoices add kar sakta hai, prices automatically calculate hoti hain, aur workflow secure rehta hai.</p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="bg-white rounded-4 p-3 h-100 shadow-sm">
                                <h2 class="h5 mb-2">Thobe Pricing</h2>
                                <p class="mb-1">Simple: 20 QAR</p>
                                <p class="mb-1">Double Stitch: 25 QAR</p>
                                <p class="mb-1">Embroidery: 25 QAR</p>
                                <p class="mb-0">Design: 30 QAR</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-white rounded-4 p-3 h-100 shadow-sm">
                                <h2 class="h5 mb-2">Ready Accounts</h2>
                                <p class="mb-1"><strong>Admin:</strong> admin@tailor.test</p>
                                <p class="mb-1"><strong>Manager:</strong> manager@tailor.test</p>
                                <p class="mb-0"><strong>User:</strong> user@tailor.test</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="btn btn-tailor btn-lg px-4">Login</a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-dark btn-lg px-4">Dashboard</a>
                    </div>
                    <p class="small text-muted mt-3 mb-0">Default password for all seeded accounts: <strong>password</strong></p>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-card rounded-4 p-4">
                    <h2 class="h4 fw-bold mb-3">Core Features</h2>
                    <div class="d-grid gap-3">
                        <div class="bg-white rounded-4 p-3 shadow-sm">
                            <h3 class="h6 fw-bold mb-2">Admin Control</h3>
                            <p class="mb-0 text-secondary">Admin dashboard se invoice form use karke tailor data add kar sakta hai.</p>
                        </div>
                        <div class="bg-white rounded-4 p-3 shadow-sm">
                            <h3 class="h6 fw-bold mb-2">Secure Roles</h3>
                            <p class="mb-0 text-secondary">Role middleware ensure karta hai ke admin-only screens sirf admin ko nazar aayen.</p>
                        </div>
                        <div class="bg-white rounded-4 p-3 shadow-sm">
                            <h3 class="h6 fw-bold mb-2">Bootstrap UI</h3>
                            <p class="mb-0 text-secondary">Warm cream, sand, aur brown palette tailoring brand feel ke liye use hui hai.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
