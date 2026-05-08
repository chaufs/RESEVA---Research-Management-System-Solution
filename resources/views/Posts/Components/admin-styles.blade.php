<style>
body {
    background: radial-gradient(circle at top left, #f1f6ff 100%);
    color: #1f2a4a;
}
.navbar-custom {
    background:  #28305e;
    box-shadow: 0 12px 30px rgba(10, 29, 81, 0.18);
    padding: 0.85rem 0;
}
.navbar-custom .container-fluid {
    max-width: 1320px;
    padding-left: 2rem;
    padding-right: 2rem;
}
.navbar-brand {
    font-weight: 700;
    letter-spacing: 0.03em;
    color: #ffffff !important;
}
.navbar-brand-logo {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border-radius: 0.75rem;
    background: rgba(255, 255, 255, 0.14);
    padding: 0.3rem;
}
.navbar-logout-form {
    margin-left: 0.75rem;
}
.navbar-logout-btn {
    border-radius: 999px;
    padding: 0.45rem 1rem;
    border-color: rgba(255, 255, 255, 0.35);
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.navbar-logout-icon {
    width: 16px;
    height: 16px;
    object-fit: contain;
}
.navbar-logout-btn:hover {
    background-color: rgba(255, 255, 255, 0.14);
    border-color: rgba(255, 255, 255, 0.5);
    color: #ffffff;
}
.navbar-text {
    color: rgba(255, 255, 255, 0.82);
    
}
.navbar-nav .nav-link {
    color: rgba(255, 255, 255, 0.82);
    font-weight: 500;
    transition: all 0.15s ease-in-out;
}
.navbar-nav .nav-link.active,
.navbar-nav .nav-link:hover {
    color: #ffffff;
    background-color: rgba(255, 255, 255, 0.14);
    border-radius: 0.8rem;
}
.page-header {
    background: #ffffff;
    border-radius: 1.5rem;
    box-shadow: 0 24px 60px rgba(15, 47, 130, 0.1);
    padding: 2rem;
    margin-bottom: 1.5rem;
}
.card, .table-responsive {
    border: none;
    border-radius: 1.25rem;
}
.card {
    box-shadow: 0 18px 50px rgba(15, 47, 130, 0.06);
}
.table thead th {
    background-color: rgba(52, 88, 255, 0.09);
    border-bottom: 1px solid rgba(52, 88, 255, 0.18);
}
.table tbody tr:hover {
    background-color: rgba(52, 88, 255, 0.04);
}
.btn-primary {
    background-color: #3458ff;
    border-color: #3458ff;
}
.btn-primary:hover, .btn-primary:focus {
    background-color: #243de9;
    border-color: #243de9;
}
.btn-outline-primary {
    color: #3458ff;
    border-color: #3458ff;
}
.btn-outline-primary:hover {
    background-color: rgba(52, 88, 255, 0.1);
}
.badge-soft-primary {
    color: #3458ff;
    background-color: rgba(52, 88, 255, 0.12);
}

/* Teacher: Student submission cards should be horizontal rectangles */
.student-card {
    overflow: hidden;
    min-height: 140px;
    display: block;
    transition: transform .18s ease, box-shadow .18s ease;
    cursor: pointer;
}

.student-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 24px 45px rgba(15, 47, 130, 0.08);
}

.student-card .file-meta,
.student-card .card-body h6,
.student-card .card-body small {
    word-break: break-word;
}

.task-modal-trigger {
    min-height: auto;
}

.student-card .card-body {
    height: 100%;
    display: flex;
    flex-direction: column;
}

@media (max-width: 991.98px) {
    .student-card {
        min-height: 170px;
    }
}
</style>
