<?php
// Shared admin frontend assets: Bootstrap + inline CSS (no external admin.css request)
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  :root {
    --fp-admin-bg: #f4f6fb;
    --fp-admin-card: #ffffff;
    --fp-admin-border: #e5e7eb;
    --fp-admin-text: #1f2937;
    --fp-admin-muted: #6b7280;
    --fp-admin-primary: #f77b0f;
    --fp-admin-primary-2: #ffaf32;
    --fp-admin-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
  }

  body {
    background: var(--fp-admin-bg);
    color: var(--fp-admin-text);
  }

  .admin-page {
    min-height: 100dvh;
    padding: 14px;
  }

  .container-admin {
    max-width: 1200px;
    margin: 0 auto;
  }

  .admin-topbar {
    display: grid;
    gap: 12px;
    margin-bottom: 14px;
  }

  .admin-brand {
    margin: 0;
    font-size: 1.1rem;
    line-height: 1.2;
    letter-spacing: 0.2px;
  }

  .admin-brand strong {
    color: var(--fp-admin-primary);
  }

  .admin-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  .admin-tabs {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    margin: 0 0 14px;
    padding: 2px 2px 8px;
    border-bottom: 1px solid var(--fp-admin-border);
  }

  .admin-tabs a {
    text-decoration: none;
    white-space: nowrap;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 0.9rem;
    color: #4b5563;
    border: 1px solid transparent;
  }

  .admin-tabs a:hover {
    background: #eef2f7;
  }

  .admin-tabs a.active {
    background: #111827;
    color: #fff;
  }

  .panel,
  .stat-card {
    background: var(--fp-admin-card);
    border: 1px solid var(--fp-admin-border);
    border-radius: 12px;
    box-shadow: var(--fp-admin-shadow);
    padding: 14px;
  }

  .panel {
    margin-bottom: 14px;
  }

  .panel-title,
  .group-title {
    margin: 0 0 12px;
    font-size: 1rem;
    font-weight: 700;
  }

  .group-title {
    color: var(--fp-admin-primary);
  }

  .btn-primary {
    color: #111;
    border: 0;
    background: linear-gradient(135deg, var(--fp-admin-primary), var(--fp-admin-primary-2));
  }

  .btn-dark {
    background: #111827;
    border-color: #111827;
  }

  .btn-secondary {
    color: #111827;
    background: #f3f4f6;
    border-color: #d1d5db;
  }

  .btn-danger {
    background: #ef4444;
    border-color: #ef4444;
  }

  .btn-block {
    width: 100%;
  }

  .alert {
    border-radius: 10px;
    font-size: 0.92rem;
  }

  .alert-success {
    background: #d1fae5;
    color: #065f46;
    border-color: #a7f3d0;
  }

  .alert-error {
    background: #fee2e2;
    color: #991b1b;
    border-color: #fecaca;
  }

  .field {
    margin-bottom: 12px;
  }

  .field label {
    display: block;
    margin-bottom: 6px;
    font-size: 0.82rem;
    font-weight: 700;
    color: #374151;
  }

  .field input[type="text"],
  .field input[type="password"],
  .field input[type="datetime-local"],
  .field input[type="file"],
  .field input[type="number"],
  .field textarea {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 0.92rem;
    background: #fff;
  }

  .field textarea {
    min-height: 110px;
    resize: vertical;
  }

  .muted {
    font-size: 0.8rem;
    color: var(--fp-admin-muted);
  }

  .form-grid {
    display: grid;
    gap: 12px;
  }

  .stats-grid {
    display: grid;
    gap: 10px;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    margin-bottom: 14px;
  }

  .stat-label {
    color: var(--fp-admin-muted);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 6px;
  }

  .stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1.1;
  }

  .table-wrap {
    overflow-x: auto;
    border: 1px solid var(--fp-admin-border);
    border-radius: 12px;
  }

  .table {
    margin-bottom: 0;
  }

  .table th {
    color: #6b7280;
    background: #f8fafc;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: .4px;
  }

  .table td:last-child,
  .table th:last-child {
    text-align: right;
  }

  .empty-state {
    padding: 16px;
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
    color: #6b7280;
    background: #fff;
  }

  .setting-row {
    display: grid;
    gap: 12px;
    padding: 12px 0;
    border-top: 1px dashed #e5e7eb;
  }

  .setting-row:first-of-type {
    border-top: 0;
    padding-top: 0;
  }

  .setting-label {
    font-weight: 600;
    color: #1f2937;
  }

  .setting-key {
    display: block;
    margin-top: 4px;
    font-size: 0.78rem;
    color: #6b7280;
  }

  .upload-group {
    display: grid;
    gap: 10px;
  }

  .img-preview {
    width: 120px;
    max-width: 100%;
    aspect-ratio: 2 / 3;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--fp-admin-border);
    background: #f8fafc;
  }

  .img-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 8px;
    color: #6b7280;
    font-size: 0.75rem;
  }

  .admin-savebar {
    position: sticky;
    bottom: 10px;
    z-index: 10;
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid var(--fp-admin-border);
    border-radius: 12px;
    padding: 10px;
    backdrop-filter: blur(6px);
  }

  .admin-savebar .btn {
    width: 100%;
  }

  .inline-actions {
    display: inline-flex;
    gap: 6px;
    flex-wrap: wrap;
    justify-content: flex-end;
  }

  .inline-actions form {
    margin: 0;
  }

  .badge {
    border-radius: 999px;
    padding: 3px 8px;
    font-size: 0.72rem;
    font-weight: 700;
  }

  .badge-pub {
    background: #d1fae5;
    color: #065f46;
  }

  .badge-draft {
    background: #fee2e2;
    color: #991b1b;
  }

  .admin-login-page {
    min-height: 100dvh;
    background: radial-gradient(circle at top right, rgba(247, 123, 15, 0.22), transparent 44%), #10131a;
    color: #fff;
  }

  .auth-shell {
    min-height: 100dvh;
    display: grid;
    place-items: center;
    padding: 16px;
  }

  .auth-card {
    width: 100%;
    max-width: 420px;
    background: #181c25;
    border: 1px solid #2a3140;
    border-radius: 14px;
    padding: 18px;
    box-shadow: 0 20px 48px rgba(0, 0, 0, 0.35);
  }

  .auth-title {
    margin: 0 0 14px;
    text-align: center;
    font-size: 1.25rem;
  }

  .auth-title strong {
    color: var(--fp-admin-primary-2);
  }

  .auth-note {
    margin-top: 12px;
    color: #9ca3af;
    text-align: center;
    font-size: 0.78rem;
  }

  .mt-10 {
    margin-top: 10px;
  }

  .textarea-sm {
    min-height: 90px;
  }

  .checkbox-inline {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  @media (min-width: 768px) {
    .admin-page {
      padding: 20px;
    }

    .admin-topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .admin-brand {
      font-size: 1.35rem;
    }

    .panel,
    .stat-card {
      padding: 16px;
    }

    .stats-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .form-grid.cols-2 {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .setting-row {
      grid-template-columns: 270px minmax(0, 1fr);
      align-items: start;
    }

    .upload-group {
      grid-template-columns: 120px minmax(0, 1fr);
      align-items: start;
    }

    .admin-savebar {
      display: flex;
      justify-content: flex-end;
    }

    .admin-savebar .btn {
      width: auto;
      min-width: 220px;
    }
  }

  @media (min-width: 1024px) {
    .stats-grid {
      grid-template-columns: repeat(6, minmax(0, 1fr));
    }
  }
</style>
