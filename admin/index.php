<?php
// === admin/index.php ===
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect to main page if not admin
    header("Location: ../index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="dark-theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qingli Admin | Dashboard</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styles.css?v=2.0">
    <style>
        /* Professional Admin Dashboard Styles */
        body { 
            padding-top: 0; 
            display: flex; 
            min-height: 100vh; 
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-darker) 100%);
        }
        
        /* Elegant Sidebar */
        .sidebar { 
            width: 280px; 
            background: linear-gradient(180deg, rgba(11, 190, 214, 0.08) 0%, var(--bg-darker) 100%);
            border-right: 2px solid var(--border-glass-light);
            padding: 2.5rem 2rem; 
            display: flex; 
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(11, 190, 214, 0.05) 50%, transparent 70%);
            pointer-events: none;
        }
        
        .sidebar-link { 
            display: flex; 
            align-items: center; 
            gap: 1rem; 
            color: rgba(255, 255, 255, 0.7);
            padding: 1.25rem 1.5rem;
            border-radius: 1rem;
            transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
            margin-bottom: 0.75rem;
            position: relative;
            overflow: hidden;
            font-weight: 500;
        }
        
        .sidebar-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(11, 190, 214, 0.2), transparent);
            transition: left 0.4s ease;
        }
        
        .sidebar-link:hover, .sidebar-link.active { 
            background: linear-gradient(135deg, rgba(11, 190, 214, 0.15) 0%, rgba(6, 182, 212, 0.08) 100%);
            color: var(--accent-cyan);
            transform: translateX(8px);
            box-shadow: 0 8px 30px rgba(11, 190, 214, 0.2);
            border: 1px solid rgba(11, 190, 214, 0.3);
        }
        
        .sidebar-link:hover::before, .sidebar-link.active::before {
            left: 100%;
        }
        
        /* Professional Main Content */
        .main-content { 
            flex: 1; 
            padding: 3rem 3rem 3rem 3rem; 
            background: linear-gradient(135deg, var(--bg-dark) 0%, rgba(0, 20, 40, 0.9) 100%);
            overflow-y: auto;
            position: relative;
        }
        
        .main-content::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(11, 190, 214, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        
        /* Elegant Stat Cards */
        .stat-card { 
            padding: 2.5rem;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.05) 0%, rgba(0, 0, 0, 0.15) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: statShimmer 4s ease-in-out infinite;
        }
        
        @keyframes statShimmer {
            0%, 100% { left: -100%; }
            50% { left: 100%; }
        }
        
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: var(--accent-cyan);
            box-shadow: 0 20px 60px rgba(11, 190, 214, 0.3);
        }
        
        .stat-value { 
            font-size: 3rem; 
            font-weight: 900; 
            color: var(--accent-cyan);
            display: flex; 
            align-items: baseline; 
            gap: 0.5rem;
            text-shadow: 0 0 20px rgba(11, 190, 214, 0.5);
            font-family: 'Courier New', monospace;
        }
        
        .stat-label { 
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem; 
            text-transform: uppercase; 
            letter-spacing: 0.1em; 
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        /* Professional Tables */
        .glass-panel {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.05) 0%, rgba(0, 0, 0, 0.15) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .glass-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent-cyan), var(--accent-blue), transparent);
        }
        
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 1rem; 
            color: var(--text-main);
        }
        
        .table th, .table td { 
            padding: 1.25rem 1rem; 
            text-align: left; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .table th { 
            color: var(--accent-cyan);
            font-weight: 700; 
            font-size: 0.8rem; 
            text-transform: uppercase; 
            letter-spacing: 0.1em;
            background: rgba(11, 190, 214, 0.05);
        }
        
        .table tbody tr { 
            transition: all 0.3s ease;
            position: relative;
        }
        
        .table tbody tr:hover { 
            background: linear-gradient(90deg, rgba(11, 190, 214, 0.08) 0%, transparent 100%);
            transform: translateX(8px);
        }
        
        /* Professional Headers */
        .text-white {
            color: white !important;
            font-weight: 700;
            font-size: 2rem;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            margin-bottom: 2rem;
            position: relative;
        }
        
        .text-white::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-blue));
            border-radius: 2px;
        }
        
        /* Enhanced Buttons */
        .btn {
            padding: 1rem 2rem;
            border-radius: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.4s ease;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.4s ease;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-cyan) 0%, var(--accent-blue) 100%);
            color: var(--bg-darker);
            box-shadow: 0 8px 30px rgba(11, 190, 214, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 50px rgba(11, 190, 214, 0.4);
        }
        
        /* Modal Enhancements */
        #cartModal { 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0,0,0,0.9); 
            z-index: 1000; 
            display: none; 
            align-items: center; 
            justify-content: center; 
            backdrop-filter: blur(10px);
        }
        
        #cartModal.active { display: flex; }
        
        .cart-modal-content { 
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.05) 0%, rgba(0, 0, 0, 0.15) 100%);
            border: 2px solid var(--border-glass-light);
            border-radius: 2rem;
            width: 700px; 
            max-width: 90%; 
            max-height: 80vh; 
            overflow-y: auto; 
            padding: 2.5rem; 
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }
        
        /* Dashboard Header */
        .dashboard-header {
            position: relative;
        }
        
        .dashboard-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
            margin-top: 0.5rem;
            font-weight: 400;
        }
        
        /* Stat Icon Wrapper */
        .stat-icon-wrapper {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent-cyan) 0%, var(--accent-blue) 100%);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 30px rgba(11, 190, 214, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .stat-icon-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
            animation: iconPulse 3s ease-in-out infinite;
        }
        
        .stat-icon-wrapper i {
            font-size: 1.5rem;
            color: white;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 1;
            position: relative;
        }
        
        @keyframes iconPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        .stat-trend {
            color: rgba(34, 197, 94, 0.8);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .stat-trend::before {
            content: '↑';
            font-size: 1rem;
        }
        
        /* Panel Header */
        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .panel-title {
            color: white;
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0;
        }
        
        .panel-title i {
            color: var(--accent-cyan);
            font-size: 1.1rem;
        }
        
        .panel-badge {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.2) 0%, rgba(0, 0, 0, 0.1) 100%);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            color: rgba(34, 197, 94, 0.9);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .panel-badge::before {
            content: '';
            width: 8px;
            height: 8px;
            background: rgba(34, 197, 94, 0.9);
            border-radius: 50%;
            animation: livePulse 2s ease-in-out infinite;
        }
        
        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.2); }
        }
        
        /* Enhanced Table Headers */
        .table th i {
            margin-right: 0.5rem;
            font-size: 0.8rem;
        }
        
        /* View Header */
        .view-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .view-header h2 {
            margin: 0;
        }
        
        .view-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
            margin-top: 0.5rem;
            font-weight: 400;
        }
        
        .view-header .btn {
            margin-top: 0.5rem;
        }
        
        /* Professional Action Buttons */
        .action-btn {
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
            min-height: 36px;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.4s ease;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .action-btn-approve {
            background: linear-gradient(135deg, var(--accent-cyan) 0%, var(--accent-blue) 100%);
            color: var(--bg-darker);
            box-shadow: 
                0 4px 15px rgba(11, 190, 214, 0.3),
                inset 0 0 10px rgba(255, 255, 255, 0.1);
        }

        .action-btn-approve:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 
                0 8px 25px rgba(11, 190, 214, 0.4),
                inset 0 0 15px rgba(255, 255, 255, 0.2);
        }

        .action-btn-delete {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.9) 0%, rgba(220, 38, 38, 0.9) 100%);
            color: white;
            box-shadow: 
                0 4px 15px rgba(239, 68, 68, 0.3),
                inset 0 0 10px rgba(255, 255, 255, 0.1);
        }

        .action-btn-delete:hover {
            transform: translateY(-2px) scale(1.02);
            background: linear-gradient(135deg, rgba(239, 68, 68, 1) 0%, rgba(220, 38, 38, 1) 100%);
            box-shadow: 
                0 8px 25px rgba(239, 68, 68, 0.4),
                inset 0 0 15px rgba(255, 255, 255, 0.2);
        }

        /* Professional Icon Buttons */
        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
            position: relative;
            overflow: hidden;
            font-size: 0.9rem;
            margin: 0 0.25rem;
        }

        .icon-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.4s ease;
        }

        .icon-btn:hover::before {
            left: 100%;
        }

        .icon-btn.text-accent {
            background: linear-gradient(135deg, var(--accent-cyan) 0%, var(--accent-blue) 100%);
            color: var(--bg-darker);
            box-shadow: 0 4px 15px rgba(11, 190, 214, 0.3);
        }

        .icon-btn.text-accent:hover {
            transform: translateY(-2px) scale(1.1);
            box-shadow: 0 8px 25px rgba(11, 190, 214, 0.4);
        }

        .icon-btn.text-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.9) 0%, rgba(220, 38, 38, 0.9) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .icon-btn.text-danger:hover {
            transform: translateY(-2px) scale(1.1);
            background: linear-gradient(135deg, rgba(239, 68, 68, 1) 0%, rgba(220, 38, 38, 1) 100%);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        /* Enhanced Clear All Button */
        .text-xs.text-danger.hover\\:text-white {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .text-xs.text-danger.hover\\:text-white:hover {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.9) 0%, rgba(220, 38, 38, 0.9) 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        /* Content Manager Styles */
        .content-manager-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .content-editor {
            padding: 1.5rem;
        }

        .content-editor .input-group {
            margin-bottom: 1.5rem;
        }

        .content-editor label {
            display: block;
            color: var(--accent-cyan);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .content-editor .glass-input {
            width: 100%;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            color: white;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }

        .content-editor .glass-input:focus {
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 3px rgba(11, 190, 214, 0.2);
        }

        .content-editor textarea.glass-input {
            resize: vertical;
            min-height: 100px;
        }

        /* Communication Logs Styles */
        .communication-filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-group label {
            color: var(--accent-cyan);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .communication-timeline {
            max-height: 600px;
            overflow-y: auto;
            padding: 1rem;
        }

        .communication-entry {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .communication-entry:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .comm-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .comm-customer {
            font-weight: 600;
            color: var(--accent-cyan);
        }

        .comm-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .comm-content {
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .comm-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* CRM Pipeline Styles */
        .pipeline-stages {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .pipeline-stage {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            min-height: 400px;
        }

        .stage-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stage-header h4 {
            color: white;
            font-weight: 600;
            margin: 0;
        }

        .stage-count {
            background: var(--accent-cyan);
            color: var(--bg-darker);
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .stage-cards {
            min-height: 250px;
            max-height: 300px;
            overflow-y: auto;
        }

        .customer-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .customer-card:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-2px);
        }

        .customer-name {
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .customer-email {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 0.5rem;
        }

        .customer-value {
            font-size: 0.9rem;
            color: var(--accent-cyan);
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 240px;
                padding: 2rem 1.5rem;
            }
            
            .main-content {
                padding: 2rem;
            }
            
            .stat-value {
                font-size: 2rem;
            }
            
            .text-white {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar section-darker">
        <div class="logo mb-8" style="font-size: 1.25rem;">
            <img src="../Peptide image 2.0/Logo.jpeg" alt="Logo" class="logo-img" style="width:32px; height:32px;">
            <span class="logo-text">Qingli <span class="text-glow">Admin</span></span>
        </div>

        <nav style="flex:1;">
            <a href="#" class="sidebar-link active" onclick="showView('dashboard')"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            <a href="#" class="sidebar-link" onclick="showView('quotes')"><i class="fa-solid fa-list-check"></i> Quote Requests</a>
            <a href="#" class="sidebar-link" onclick="showView('reviews')"><i class="fa-solid fa-star"></i> Customer Reviews</a>
            <a href="#" class="sidebar-link" onclick="showView('users')"><i class="fa-solid fa-users"></i> Registered Clients</a>
            <a href="#" class="sidebar-link" onclick="showView('products')"><i class="fa-solid fa-flask"></i> Products Manager</a>
            <a href="#" class="sidebar-link" onclick="showView('reps')"><i class="fa-solid fa-id-badge"></i> Reps Verification</a>
            <a href="#" class="sidebar-link" onclick="showView('content')"><i class="fa-solid fa-pen-to-square"></i> Content Manager</a>
            <a href="#" class="sidebar-link" onclick="showView('communications')"><i class="fa-solid fa-comments"></i> Communication Logs</a>
            <a href="#" class="sidebar-link" onclick="showView('crm')"><i class="fa-solid fa-handshake"></i> CRM System</a>
            <a href="#" class="sidebar-link" onclick="showView('settings')"><i class="fa-solid fa-gear"></i> Site Settings</a>
            <a href="../index.html" class="sidebar-link"><i class="fa-solid fa-store"></i> View Storefront</a>
        </nav>

        <button onclick="logoutAdmin()" class="btn btn-outline glow-btn-outline w-full mt-auto" style="color: var(--danger-red); border-color: rgba(239, 68, 68, 0.3);"><i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Logout</button>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        
        <!-- Dashboard View -->
        <div id="view-dashboard" class="view-section">
            <div class="dashboard-header mb-8">
                <h2 class="text-white">Command Center</h2>
                <p class="dashboard-subtitle">Real-time oversight of laboratory operations and client management</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
                <div class="stat-card">
                    <div class="stat-icon-wrapper">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="stat-label">Total Registered Clients</div>
                    <div class="stat-value"><span id="statUsers">0</span></div>
                    <div class="stat-trend">+12% this month</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon-wrapper">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="stat-label">Items Currently in Carts</div>
                    <div class="stat-value"><span id="statItems">0</span></div>
                    <div class="stat-trend">Active sessions</div>
                </div>
            </div>

            <div class="glass-panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Recent Client Registrations
                    </h3>
                    <div class="panel-badge">Live Data</div>
                </div>
                <table class="table" id="recentUsersTable">
                    <thead>
                        <tr>
                            <th><i class="fa-solid fa-user"></i> Name</th>
                            <th><i class="fa-solid fa-envelope"></i> Email</th>
                            <th><i class="fa-solid fa-calendar"></i> Registered On</th>
                            <th><i class="fa-solid fa-circle-dot"></i> Last Active</th>
                        </tr>
                    </thead>
                    <tbody><!-- Filled via JS --></tbody>
                </table>
            </div>
        </div>

        <!-- Quotes View -->
        <div id="view-quotes" class="view-section" style="display: none;">
            <div class="view-header">
                <h2 class="text-white">Quote Requests Manager</h2>
                <p class="view-subtitle">Monitor and respond to client quote inquiries</p>
                <button class="btn btn-primary" onclick="loadQuotes()">
                    <i class="fa-solid fa-rotate-right"></i> 
                    Refresh Data
                </button>
            </div>
            
            <div class="glass-panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fa-solid fa-clipboard-list"></i>
                        Active Quote Requests
                    </h3>
                    <div class="panel-badge">Real-time</div>
                </div>
                <table class="table" id="quotesTable">
                    <thead>
                        <tr>
                            <th><i class="fa-solid fa-calendar"></i> Date</th>
                            <th><i class="fa-solid fa-hashtag"></i> ID</th>
                            <th><i class="fa-solid fa-user"></i> Customer</th>
                            <th><i class="fa-solid fa-box"></i> Items</th>
                            <th><i class="fa-solid fa-flag"></i> Status</th>
                            <th><i class="fa-solid fa-sticky-note"></i> Notes</th>
                            <th><i class="fa-solid fa-gear"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody><!-- Filled via JS --></tbody>
                </table>
            </div>
        </div>

        <!-- Reviews View -->
        <div id="view-reviews" class="view-section" style="display: none;">
            <div class="view-header">
                <h2 class="text-white">Customer Reviews</h2>
                <p class="view-subtitle">Manage and moderate client feedback</p>
                <button class="btn btn-primary" onclick="loadReviews()">
                    <i class="fa-solid fa-rotate-right"></i> 
                    Refresh Data
                </button>
            </div>

            <div class="glass-panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fa-solid fa-star"></i>
                        Client Testimonials
                    </h3>
                    <div class="panel-badge">Live Reviews</div>
                </div>
                <table class="table" id="reviewsTable">
                    <thead>
                        <tr>
                            <th><i class="fa-solid fa-calendar"></i> Date</th>
                            <th><i class="fa-solid fa-user"></i> Display Name</th>
                            <th><i class="fa-solid fa-star"></i> Overall Rating</th>
                            <th><i class="fa-solid fa-comments"></i> Communication</th>
                            <th><i class="fa-solid fa-truck"></i> Shipping</th>
                            <th><i class="fa-solid fa-flask"></i> Product Standards</th>
                            <th><i class="fa-solid fa-message"></i> Review</th>
                            <th><i class="fa-solid fa-flag"></i> Status</th>
                            <th><i class="fa-solid fa-gear"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody><!-- Filled via JS --></tbody>
                </table>
            </div>
        </div>

        <!-- Users/Clients View -->
        <div id="view-users" class="view-section" style="display: none;">
            <div class="flex-between mb-6">
                <h2 class="text-white m-0">Client Directory</h2>
                <button class="btn btn-primary glow-btn" onclick="loadUsers()"><i class="fa-solid fa-rotate-right mr-2"></i> Refresh</button>
            </div>
            
            <div class="glass-panel p-6">
                <table class="table" id="allUsersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Last Active</th>
                            <th>Items in Cart</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody><!-- Filled via JS --></tbody>
                </table>
            </div>
        </div>

        <!-- Products Manager View -->
        <div id="view-products" class="view-section" style="display: none;">
            <div class="flex-between mb-6">
                <h2 class="text-white m-0">Products Manager</h2>
                <div>
                    <button class="btn btn-outline glow-btn-outline mr-4" onclick="loadProducts()"><i class="fa-solid fa-rotate-right mr-2"></i> Refresh</button>
                    <button class="btn btn-primary glow-btn" onclick="openProductModal()"><i class="fa-solid fa-plus mr-2"></i> Add Product</button>
                </div>
            </div>
            
            <div class="glass-panel p-6">
                <table class="table" id="productsTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Purity</th>
                            <th>COA</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody><!-- Filled via JS --></tbody>
                </table>
            </div>
        </div>

        <!-- Content Manager View -->
        <div id="view-content" class="view-section" style="display: none;">
            <div class="view-header">
                <h2 class="text-white">Content Manager</h2>
                <p class="view-subtitle">Edit website content without coding</p>
                <button class="btn btn-primary" onclick="saveContentChanges()">
                    <i class="fa-solid fa-save"></i> 
                    Save All Changes
                </button>
            </div>
            
            <div class="content-manager-grid">
                <!-- Hero Section Editor -->
                <div class="glass-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <i class="fa-solid fa-house"></i>
                            Hero Section
                        </h3>
                        <div class="panel-badge">Live Preview</div>
                    </div>
                    <div class="content-editor">
                        <div class="input-group mb-4">
                            <label>Main Headline</label>
                            <input type="text" id="heroHeadline" class="glass-input" value="Premium Research Peptides" placeholder="Enter main headline">
                        </div>
                        <div class="input-group mb-4">
                            <label>Sub-headline</label>
                            <input type="text" id="heroSubheadline" class="glass-input" value="Global supplier of 98%+ purity research peptides" placeholder="Enter sub-headline">
                        </div>
                        <div class="input-group mb-4">
                            <label>Primary CTA Button Text</label>
                            <input type="text" id="heroCTA" class="glass-input" value="Get Started" placeholder="Enter button text">
                        </div>
                        <div class="input-group mb-4">
                            <label>Secondary CTA Button Text</label>
                            <input type="text" id="heroCTA2" class="glass-input" value="View Catalog" placeholder="Enter secondary button text">
                        </div>
                        <div class="input-group mb-4">
                            <label>Trust Badge Rating</label>
                            <input type="number" id="heroRating" class="glass-input" value="4.9" step="0.1" min="1" max="5" placeholder="Enter rating">
                        </div>
                    </div>
                </div>

                <!-- About Section Editor -->
                <div class="glass-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <i class="fa-solid fa-info-circle"></i>
                            About Section
                        </h3>
                        <div class="panel-badge">Rich Text</div>
                    </div>
                    <div class="content-editor">
                        <div class="input-group mb-4">
                            <label>About Us Title</label>
                            <input type="text" id="aboutTitle" class="glass-input" value="About Qingli Peptide" placeholder="Enter about title">
                        </div>
                        <div class="input-group mb-4">
                            <label>Company Description</label>
                            <textarea id="aboutDescription" class="glass-input w-full" rows="4" placeholder="Enter company description">Premium supplier of high-purity peptides and chemical reagents for global biotechnology sector. Empowering research excellence since 2019.</textarea>
                        </div>
                        <div class="input-group mb-4">
                            <label>Our Mission</label>
                            <textarea id="aboutMission" class="glass-input w-full" rows="3" placeholder="Enter mission statement">To provide researchers worldwide with the highest quality peptides, backed by comprehensive analytical verification and exceptional customer service.</textarea>
                        </div>
                    </div>
                </div>

                <!-- Calculator Section Editor -->
                <div class="glass-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <i class="fa-solid fa-calculator"></i>
                            Calculator Section
                        </h3>
                        <div class="panel-badge">Tool Settings</div>
                    </div>
                    <div class="content-editor">
                        <div class="input-group mb-4">
                            <label>Section Title</label>
                            <input type="text" id="calcTitle" class="glass-input" value="Peptide Reconstitution Calculator" placeholder="Enter calculator title">
                        </div>
                        <div class="input-group mb-4">
                            <label>Section Description</label>
                            <textarea id="calcDescription" class="glass-input w-full" rows="3" placeholder="Enter calculator description">Achieve 100% accurate research measurements with our clinical-grade reconstitution tool. Designed for precise laboratory calculations across all standard syringe specifications.</textarea>
                        </div>
                        <div class="input-group mb-4">
                            <label>Default Water Amount (mL)</label>
                            <input type="number" id="calcDefaultWater" class="glass-input" value="2" step="0.1" min="0.1" placeholder="Default water amount">
                        </div>
                    </div>
                </div>

                <!-- Quality Section Editor -->
                <div class="glass-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <i class="fa-solid fa-shield-virus"></i>
                            Quality Section
                        </h3>
                        <div class="panel-badge">Lab Standards</div>
                    </div>
                    <div class="content-editor">
                        <div class="input-group mb-4">
                            <label>Section Title</label>
                            <input type="text" id="qualityTitle" class="glass-input" value="Uncompromising Analytical Verification" placeholder="Enter quality title">
                        </div>
                        <div class="input-group mb-4">
                            <label>Quality Description</label>
                            <textarea id="qualityDescription" class="glass-input w-full" rows="3" placeholder="Enter quality description">In the research chemical sector, trust is built on verifiable data. Every single batch undergoes rigorous independent analysis via HPLC and Mass Spectrometry before entering our global inventory.</textarea>
                        </div>
                        <div class="input-group mb-4">
                            <label>Purity Percentage</label>
                            <input type="number" id="qualityPurity" class="glass-input" value="99.9" step="0.1" min="90" max="100" placeholder="Purity percentage">
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="glass-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <i class="fa-solid fa-search"></i>
                            SEO Settings
                        </h3>
                        <div class="panel-badge">Meta Tags</div>
                    </div>
                    <div class="content-editor">
                        <div class="input-group mb-4">
                            <label>Page Title</label>
                            <input type="text" id="seoTitle" class="glass-input" value="Qingli Peptide | Premium Research Peptides" placeholder="Enter page title">
                        </div>
                        <div class="input-group mb-4">
                            <label>Meta Description</label>
                            <textarea id="seoDescription" class="glass-input w-full" rows="3" placeholder="Enter meta description">Global supplier of 98%+ purity research peptides. Third-party tested, designed for clinical and laboratory research use only.</textarea>
                        </div>
                        <div class="input-group mb-4">
                            <label>Keywords (comma separated)</label>
                            <input type="text" id="seoKeywords" class="glass-input" value="research peptides, peptide synthesis, laboratory chemicals, HPLC tested, 99% purity" placeholder="Enter keywords">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Representatives Manager View -->
        <div id="view-reps" class="view-section" style="display: none;">
            <div class="flex-between mb-6">
                <h2 class="text-white m-0">Verified Representatives</h2>
                <div>
                    <button class="btn btn-outline glow-btn-outline mr-4" onclick="loadReps()"><i class="fa-solid fa-rotate-right mr-2"></i> Refresh</button>
                    <button class="btn btn-primary glow-btn" onclick="openRepModal()"><i class="fa-solid fa-plus mr-2"></i> Add Rep</button>
                </div>
            </div>
            
            <div class="glass-panel p-6 mb-8">
                <p class="text-muted text-sm mb-4">Manage the unique IDs that customers use to verify official sales reps.</p>
                <table class="table" id="repsTable">
                    <thead>
                        <tr>
                            <th>Rep ID</th>
                            <th>Name</th>
                            <th>Territory</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody><!-- Filled via JS --></tbody>
                </table>
            </div>
        </div>

        <!-- Communication Logs View -->
        <div id="view-communications" class="view-section" style="display: none;">
            <div class="view-header">
                <h2 class="text-white">Communication Logs</h2>
                <p class="view-subtitle">Track all customer interactions</p>
                <div class="flex gap-2">
                    <button class="btn btn-primary" onclick="addCommunication()">
                        <i class="fa-solid fa-plus"></i> 
                        Add Entry
                    </button>
                    <button class="btn btn-outline" onclick="loadCommunications()">
                        <i class="fa-solid fa-rotate-right"></i> 
                        Refresh
                    </button>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="glass-panel p-4 mb-6">
                <div class="communication-filters">
                    <div class="filter-group">
                        <label>Search Customer</label>
                        <input type="text" id="commSearch" class="glass-input" placeholder="Search by name, email, or phone">
                    </div>
                    <div class="filter-group">
                        <label>Communication Type</label>
                        <select id="commType" class="glass-input">
                            <option value="">All Types</option>
                            <option value="email">Email</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="phone">Phone Call</option>
                            <option value="support">Support Ticket</option>
                            <option value="meeting">In-Person Meeting</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Date Range</label>
                        <input type="date" id="commDateFrom" class="glass-input">
                        <input type="date" id="commDateTo" class="glass-input">
                    </div>
                    <div class="filter-group">
                        <label>Status</label>
                        <select id="commStatus" class="glass-input">
                            <option value="">All Status</option>
                            <option value="open">Open</option>
                            <option value="in-progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="follow-up">Follow-up Required</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Communication Timeline -->
            <div class="glass-panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Communication Timeline
                    </h3>
                    <div class="panel-badge">Live Tracking</div>
                </div>
                <div class="communication-timeline" id="communicationTimeline">
                    <!-- Communications will be loaded here via JS -->
                    <div class="text-center py-8 text-muted">
                        <i class="fa-solid fa-spinner fa-spin mr-2"></i> 
                        Loading communication history...
                    </div>
                </div>
            </div>
        </div>

        <!-- CRM System View -->
        <div id="view-crm" class="view-section" style="display: none;">
            <div class="view-header">
                <h2 class="text-white">CRM System</h2>
                <p class="view-subtitle">Complete customer relationship management</p>
                <div class="flex gap-2">
                    <button class="btn btn-primary" onclick="addCustomer()">
                        <i class="fa-solid fa-user-plus"></i> 
                        Add Customer
                    </button>
                    <button class="btn btn-outline" onclick="loadCRM()">
                        <i class="fa-solid fa-rotate-right"></i> 
                        Refresh
                    </button>
                </div>
            </div>
            
            <!-- CRM Dashboard Stats -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
                <div class="stat-card">
                    <div class="stat-icon-wrapper">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="stat-label">Total Customers</div>
                    <div class="stat-value" id="crmTotalCustomers">0</div>
                    <div class="stat-trend">+8% this month</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon-wrapper">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div class="stat-label">Active Deals</div>
                    <div class="stat-value" id="crmActiveDeals">0</div>
                    <div class="stat-trend">$45,000 value</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon-wrapper">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <div class="stat-label">Open Conversations</div>
                    <div class="stat-value" id="crmOpenConversations">0</div>
                    <div class="stat-trend">12 need follow-up</div>
                </div>
            </div>

            <!-- Customer Pipeline -->
            <div class="glass-panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fa-solid fa-filter"></i>
                        Customer Pipeline
                    </h3>
                    <div class="panel-badge">Sales Funnel</div>
                </div>
                
                <!-- Pipeline Stages -->
                <div class="pipeline-stages">
                    <div class="pipeline-stage">
                        <div class="stage-header">
                            <h4>New Leads</h4>
                            <span class="stage-count" id="stageNew">0</span>
                        </div>
                        <div class="stage-cards" id="stageNewCards">
                            <!-- Lead cards will be loaded here -->
                        </div>
                    </div>
                    
                    <div class="pipeline-stage">
                        <div class="stage-header">
                            <h4>Qualified</h4>
                            <span class="stage-count" id="stageQualified">0</span>
                        </div>
                        <div class="stage-cards" id="stageQualifiedCards">
                            <!-- Qualified lead cards -->
                        </div>
                    </div>
                    
                    <div class="pipeline-stage">
                        <div class="stage-header">
                            <h4>Quote Sent</h4>
                            <span class="stage-count" id="stageQuote">0</span>
                        </div>
                        <div class="stage-cards" id="stageQuoteCards">
                            <!-- Quote sent cards -->
                        </div>
                    </div>
                    
                    <div class="pipeline-stage">
                        <div class="stage-header">
                            <h4>Negotiation</h4>
                            <span class="stage-count" id="stageNegotiation">0</span>
                        </div>
                        <div class="stage-cards" id="stageNegotiationCards">
                            <!-- Negotiation cards -->
                        </div>
                    </div>
                    
                    <div class="pipeline-stage">
                        <div class="stage-header">
                            <h4>Closed Won</h4>
                            <span class="stage-count" id="stageWon">0</span>
                        </div>
                        <div class="stage-cards" id="stageWonCards">
                            <!-- Won deal cards -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Site Settings View -->
        <div id="view-settings" class="view-section" style="display: none;">
            <div class="flex-between mb-6">
                <h2 class="text-white m-0">Site Settings</h2>
            </div>
            
            <div class="glass-panel p-6 max-w-2xl mb-8">
                <h3 class="text-white mb-4">Admin Account Login</h3>
                <p class="text-muted text-sm mb-4">Update your dashboard login email and password.</p>
                <form onsubmit="updateAdminLogin(event)">
                    <div class="mb-4">
                        <label class="text-muted text-sm mb-2 block">New Login Email</label>
                        <input type="email" id="adminUpdateEmail" required class="glass-input w-full" placeholder="admin@qinglipeptide.com">
                    </div>
                    <div class="mb-6">
                        <label class="text-muted text-sm mb-2 block">New Password (leave blank to keep current)</label>
                        <input type="password" id="adminUpdatePassword" class="glass-input w-full" placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-primary glow-btn" id="adminUpdateBtn">
                        <i class="fa-solid fa-save mr-2"></i> Update Login
                    </button>
                </form>
            </div>

            <div class="glass-panel p-6 max-w-2xl mb-8">
                <h3 class="text-white mb-4">Contact Information</h3>
                <p class="text-muted text-sm mb-4">Update the details used for Customer Support across the entire storefront.</p>
                
                <div class="mb-4">
                    <label class="text-muted text-sm mb-2 block">Support Email</label>
                    <input type="email" id="settingContactEmail" class="glass-input w-full" placeholder="admin@qinglipeptide.com">
                </div>
                
                <div class="mb-6">
                    <label class="text-muted text-sm mb-2 block">WhatsApp Number (Include Country Code)</label>
                    <input type="text" id="settingContactWhatsapp" class="glass-input w-full" placeholder="+1234567890">
                </div>
                
                <button class="btn btn-primary glow-btn" onclick="saveContactSettings(this)">
                    <i class="fa-solid fa-save mr-2"></i> Save Contact Details
                </button>
            </div>
            
            <div class="glass-panel p-6 max-w-2xl mb-8">
                <h3 class="text-white mb-4 flex items-center gap-3">
                    <i class="fa-solid fa-bullhorn text-accent"></i> 
                    Professional Marquee Settings
                </h3>
                <p class="text-muted text-sm mb-6">Configure the announcement ticker with advanced options and real-time preview.</p>
                
                <!-- Quick Actions -->
                <div class="flex gap-2 mb-6">
                    <button onclick="previewMarquee()" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-eye mr-2"></i> Preview
                    </button>
                    <button onclick="resetMarqueeToDefault()" class="btn btn-outline btn-sm text-warning">
                        <i class="fa-solid fa-undo mr-2"></i> Reset to Default
                    </button>
                    <button onclick="toggleMarqueeStatus()" class="btn btn-outline btn-sm text-success">
                        <i class="fa-solid fa-power-off mr-2"></i> Toggle Status
                    </button>
                </div>
                
                <!-- Messages Section -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-muted text-sm font-semibold">Marquee Messages</label>
                        <span class="text-xs text-muted" id="messageCount">0 messages</span>
                    </div>
                    <textarea id="settingMarqueeText" class="glass-input w-full font-mono text-sm" rows="8" placeholder="• GLOBAL EXPORT QUALITY&#10;• GUARANTEED CUSTOMS CLEARANCE&#10;• 24/7 TECHNICAL SUPPORT&#10;• DISCREET WORLDWIDE SHIPPING" oninput="updateMessageCount()"></textarea>
                    <div class="mt-2 flex gap-2">
                        <button onclick="addTemplateMessages()" class="text-xs text-accent hover:text-white">
                            <i class="fa-solid fa-plus mr-1"></i> Add Templates
                        </button>
                        <button onclick="clearAllMessages()" class="text-xs text-danger hover:text-white">
                            <i class="fa-solid fa-trash mr-1"></i> Clear All
                        </button>
                    </div>
                </div>

                <!-- Advanced Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="text-muted text-sm font-semibold block mb-2">Animation Speed</label>
                        <div class="flex items-center gap-2">
                            <input type="range" id="settingMarqueeSpeed" class="flex-grow" min="5" max="120" value="30" oninput="updateSpeedDisplay(this.value)">
                            <span id="speedDisplay" class="text-sm text-white bg-glass px-2 py-1 rounded">30s</span>
                        </div>
                        <div class="flex gap-1 mt-2">
                            <button onclick="setSpeed(15)" class="text-xs px-2 py-1 bg-glass rounded hover:bg-accent">Fast</button>
                            <button onclick="setSpeed(30)" class="text-xs px-2 py-1 bg-glass rounded hover:bg-accent">Normal</button>
                            <button onclick="setSpeed(60)" class="text-xs px-2 py-1 bg-glass rounded hover:bg-accent">Slow</button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-muted text-sm font-semibold block mb-2">Visual Theme</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="setTheme('cyan')" class="theme-btn bg-cyan/20 border border-cyan/50 text-cyan rounded py-2 px-3 text-xs hover:bg-cyan/30">
                                <i class="fa-solid fa-circle mr-1"></i> Cyan
                            </button>
                            <button onclick="setTheme('gold')" class="theme-btn bg-yellow/20 border border-yellow/50 text-yellow rounded py-2 px-3 text-xs hover:bg-yellow/30">
                                <i class="fa-solid fa-circle mr-1"></i> Gold
                            </button>
                            <button onclick="setTheme('red')" class="theme-btn bg-red/20 border border-red/50 text-red rounded py-2 px-3 text-xs hover:bg-red/30">
                                <i class="fa-solid fa-circle mr-1"></i> Red
                            </button>
                            <button onclick="setTheme('green')" class="theme-btn bg-green/20 border border-green/50 text-green rounded py-2 px-3 text-xs hover:bg-green/30">
                                <i class="fa-solid fa-circle mr-1"></i> Green
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Behavior Options -->
                <div class="mb-6">
                    <label class="text-muted text-sm font-semibold block mb-3">Behavior Options</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" id="marqueePauseHover" checked class="accent-cyan">
                            <span>Pause on hover</span>
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" id="marqueeAutoRefresh" checked class="accent-cyan">
                            <span>Auto-refresh content (10s interval)</span>
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" id="marqueeShowIcons" checked class="accent-cyan">
                            <span>Show contextual icons</span>
                        </label>
                    </div>
                </div>

                <!-- Custom Color (Advanced) -->
                <div class="mb-6">
                    <label class="text-muted text-sm font-semibold block mb-2">Custom Color (Advanced)</label>
                    <div class="flex gap-2">
                        <input type="color" id="settingMarqueeColor" class="glass-input p-1" style="width: 50px; height: 38px; border-radius: 8px;" value="#0bbed6">
                        <input type="text" id="settingMarqueeColorText" class="glass-input flex-grow" value="#0bbed6" oninput="document.getElementById('settingMarqueeColor').value = this.value">
                    </div>
                </div>
                
                <!-- Save Button -->
                <button class="btn btn-primary glow-btn w-full" onclick="saveMarqueeSettings(this)">
                    <i class="fa-solid fa-save mr-2"></i> Save Marquee Configuration
                </button>
            </div>
        </div>

    </main>

    <!-- Inspect Cart Modal -->
    <div id="cartModal">
        <div class="cart-modal-content">
            <button class="modal-close" style="top: 1.5rem;" onclick="closeCartModal()"><i class="fa-solid fa-xmark"></i></button>
            <h3 class="text-white mb-2" id="modalUserName">Client's Cart</h3>
            <p class="text-muted text-sm mb-6" id="modalUserEmail">email@example.com</p>

            <div id="modalCartItems">
                <!-- Filled via JS -->
                <div class="text-center text-muted py-8"><i class="fa-solid fa-spinner fa-spin"></i> Loading cart data...</div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:1000; align-items:center; justify-content:center; backdrop-filter:blur(5px); overflow-y:auto; padding: 2rem;">
        <div class="cart-modal-content" style="max-width: 800px; width: 100%;">
            <button class="modal-close" style="top: 1.5rem;" onclick="closeProductModal()"><i class="fa-solid fa-xmark"></i></button>
            <h3 class="text-white mb-4" id="productModalTitle">Add/Edit Product</h3>
            
            <form id="productForm" onsubmit="submitProductForm(event)">
                <input type="hidden" id="prodAction" value="add_product">
                <input type="hidden" id="prodOldId" value="">
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label class="text-muted text-sm mb-2 block">Product ID (e.g. bpc-157)</label>
                        <input type="text" id="prodId" required class="glass-input w-full">
                    </div>
                    <div>
                        <label class="text-muted text-sm mb-2 block">Product Name</label>
                        <input type="text" id="prodName" required class="glass-input w-full">
                    </div>
                    <div>
                        <label class="text-muted text-sm mb-2 block">Category</label>
                        <input type="text" id="prodCategory" required class="glass-input w-full">
                    </div>
                    <div>
                        <label class="text-muted text-sm mb-2 block">Purity (e.g. >99%)</label>
                        <input type="text" id="prodPurity" class="glass-input w-full">
                    </div>
                    <div>
                        <label class="text-muted text-sm mb-2 block">Specification</label>
                        <input type="text" id="prodSpec" class="glass-input w-full" placeholder="e.g. 10mg/vial">
                    </div>
                    <div>
                        <label class="text-muted text-sm mb-2 block">Form</label>
                        <input type="text" id="prodForm" class="glass-input w-full" placeholder="e.g. Lyophilized powder">
                    </div>
                    <div>
                        <label class="text-muted text-sm mb-2 block">Storage</label>
                        <input type="text" id="prodStorage" class="glass-input w-full" placeholder="e.g. Store at -20°C">
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="text-muted text-sm mb-2 block">Short Description</label>
                    <textarea id="prodDesc" rows="2" class="glass-input w-full"></textarea>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label class="text-muted text-sm mb-2 block">Overview / Full Description</label>
                    <textarea id="prodOverview" rows="3" class="glass-input w-full"></textarea>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label class="text-muted text-sm mb-2 block">Typical Research Applications</label>
                    <textarea id="prodApps" rows="3" class="glass-input w-full" placeholder="Use • or new lines for lists"></textarea>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label class="text-muted text-sm mb-2 block">Target Users</label>
                    <textarea id="prodUsers" rows="3" class="glass-input w-full" placeholder="Use ✔️ or new lines for lists"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
                    <div>
                        <label class="text-muted text-sm mb-2 block">Product Image Upload (JPEG/PNG)</label>
                        <input type="file" id="prodImage" accept="image/*" style="width:100%; color:var(--text-muted);">
                    </div>
                    <div>
                        <label class="text-muted text-sm mb-2 block">COA Documents Upload (Multiple)</label>
                        <input type="file" id="prodCoa" name="coa[]" multiple accept=".pdf,image/*" style="width:100%; color:var(--text-muted);">
                        <div id="clearCoasContainer" style="display:none; margin-top: 0.5rem;">
                            <label class="text-sm text-accent"><input type="checkbox" id="clearCoas" value="true"> Clear existing COAs?</label>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary glow-btn w-full">Save Product</button>
            </form>
        </div>
    </div>

    <!-- Add/Edit Rep Modal -->
    <div id="repModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:1000; align-items:center; justify-content:center; backdrop-filter:blur(5px); padding: 2rem;">
        <div class="cart-modal-content" style="max-width: 500px; width: 100%;">
            <button class="modal-close" style="top: 1.5rem;" onclick="closeRepModal()"><i class="fa-solid fa-xmark"></i></button>
            <h3 class="text-white mb-4" id="repModalTitle">Add New Representative</h3>
            
            <form id="repForm" onsubmit="submitRepForm(event)">
                <input type="hidden" id="repAction" value="add">
                <input type="hidden" id="repDbId" value="0">
                
                <div class="mb-4">
                    <label class="text-muted text-sm mb-2 block">Unique Rep ID (e.g. QL-1045)</label>
                    <input type="text" id="repIdInput" required class="glass-input w-full">
                </div>
                <div class="mb-4">
                    <label class="text-muted text-sm mb-2 block">Full Name</label>
                    <input type="text" id="repNameInput" required class="glass-input w-full">
                </div>
                <div class="mb-4">
                    <label class="text-muted text-sm mb-2 block">Territory / Platform (Optional)</label>
                    <input type="text" id="repTerritoryInput" class="glass-input w-full" placeholder="e.g. Europe / WhatsApp">
                </div>
                <div class="mb-6">
                    <label class="text-muted text-sm mb-2 block">Status</label>
                    <select id="repStatusInput" class="glass-input w-full">
                        <option value="Active">Active</option>
                        <option value="Suspended">Suspended</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary glow-btn w-full">Save Representative</button>
            </form>
        </div>
    </div>

    <!-- Admin JS logic -->
    <script>
        // Tab routing
        function showView(viewName) {
            document.querySelectorAll('.view-section').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.sidebar-link').forEach(el => el.classList.remove('active'));
            
            document.getElementById(`view-${viewName}`).style.display = 'block';
            if (event) event.currentTarget.classList.add('active');

            if(viewName === 'dashboard') loadStats();
            if(viewName === 'users') loadUsers();
            if(viewName === 'products') loadProducts();
            if(viewName === 'reps') loadReps();
            if(viewName === 'settings') loadSettings();
            if(viewName === 'reviews') loadReviews();
        }

        // ==========================
        //  SETTINGS MANAGEMENT
        // ==========================
        async function loadSettings() {
            try {
                // Fetch contact / marquee settings
                const res = await fetch('../api/settings.php?action=fetch');
                const data = await res.json();
                if(data.status === 'success') {
                    document.getElementById('settingContactEmail').value = data.settings.contact_email || '';
                    document.getElementById('settingContactWhatsapp').value = data.settings.contact_whatsapp || '';
                    document.getElementById('settingMarqueeText').value = data.settings.contact_marquee || '• GLOBAL EXPORT QUALITY • GUARANTEED CUSTOMS CLEARANCE • UNMATCHED PURITY STANDARDS';
                    
                    // Style settings
                    if (data.settings.marquee_speed) document.getElementById('settingMarqueeSpeed').value = data.settings.marquee_speed;
                    if (data.settings.marquee_color) {
                        document.getElementById('settingMarqueeColor').value = data.settings.marquee_color;
                        document.getElementById('settingMarqueeColorText').value = data.settings.marquee_color;
                    }
                }
            } catch(e) { console.error('Error loading settings', e); }
        }

        async function updateAdminLogin(e) {
            e.preventDefault();
            const btnNode = document.getElementById('adminUpdateBtn');
            const originalHTML = btnNode.innerHTML;
            btnNode.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i> Updating...`;
            btnNode.disabled = true;

            const payload = {
                action: 'update_login',
                email: document.getElementById('adminUpdateEmail').value,
                password: document.getElementById('adminUpdatePassword').value
            };

            try {
                const res = await fetch('../api/admin_settings.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                
                if(data.status === 'success') {
                    btnNode.innerHTML = `<i class="fa-solid fa-check mr-2"></i> Details Updated!`;
                    btnNode.style.background = 'var(--success-green)';
                    document.getElementById('adminUpdatePassword').value = '';
                    setTimeout(() => {
                        alert("Login updated successfully. You will be logged out to re-authenticate.");
                        logoutAdmin();
                    }, 1500);
                } else {
                    alert('Error: ' + data.message);
                    btnNode.innerHTML = originalHTML;
                    btnNode.disabled = false;
                }
            } catch(error) {
                console.error('Update failed', error);
                alert('Connection error');
                btnNode.innerHTML = originalHTML;
                btnNode.disabled = false;
            }
        }

        async function saveContactSettings(btnNode) {
            const originalHTML = btnNode.innerHTML;
            btnNode.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving...`;
            btnNode.disabled = true;

            const payload = {
                action: 'update',
                email: document.getElementById('settingContactEmail').value,
                whatsapp: document.getElementById('settingContactWhatsapp').value
            };

            try {
                const res = await fetch('../api/settings.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                
                if(data.status === 'success') {
                    btnNode.innerHTML = `<i class="fa-solid fa-check mr-2"></i> Saved!`;
                    btnNode.style.background = 'var(--success-green)';
                    setTimeout(() => {
                        btnNode.innerHTML = originalHTML;
                        btnNode.style.background = '';
                        btnNode.disabled = false;
                    }, 2000);
                } else {
                    alert('Error: ' + data.message);
                    btnNode.innerHTML = originalHTML;
                    btnNode.disabled = false;
                }
            } catch(e) {
                console.error('Save failed', e);
                btnNode.innerHTML = originalHTML;
                btnNode.disabled = false;
            }
        }

        async function saveMarqueeSettings(btnNode) {
            const originalHTML = btnNode.innerHTML;
            btnNode.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving...`;
            btnNode.disabled = true;

            const payload = {
                action: 'update',
                marquee: document.getElementById('settingMarqueeText').value,
                marquee_speed: document.getElementById('settingMarqueeSpeed').value,
                marquee_color: document.getElementById('settingMarqueeColor').value
            };

            try {
                const res = await fetch('../api/settings.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                
                if(data.status === 'success') {
                    btnNode.innerHTML = `<i class="fa-solid fa-check mr-2"></i> Saved!`;
                    btnNode.style.background = 'var(--success-green)';
                    setTimeout(() => {
                        btnNode.innerHTML = originalHTML;
                        btnNode.style.background = '';
                        btnNode.disabled = false;
                    }, 1000); // Reduced feedback time to 1s for better UX
                } else {
                    alert('Error: ' + data.message);
                    btnNode.innerHTML = originalHTML;
                    btnNode.disabled = false;
                }
            } catch(e) {
                console.error('Save failed', e);
                btnNode.innerHTML = originalHTML;
                btnNode.disabled = false;
            }
        }

        async function loadStats() {
            try {
                const res = await fetch('../api/admin.php?action=stats');
                const data = await res.json();
                if(data.status === 'success') {
                    document.getElementById('statUsers').innerText = data.stats.total_users;
                    document.getElementById('statItems').innerText = data.stats.total_cart_items;
                    
                    const tbody = document.querySelector('#recentUsersTable tbody');
                    if (data.stats.recent_users.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="3" class="text-center text-muted">No clients yet.</td></tr>`;
                    } else {
                        tbody.innerHTML = data.stats.recent_users.map(u => `
                            <tr>
                                <td>${u.name}</td>
                                <td>${u.email}</td>
                                <td><span class="badge" style="background:rgba(255,255,255,0.1); padding:0.25rem 0.5rem; border-radius:1rem; font-size:0.75rem;">${new Date(u.created_at).toLocaleDateString()}</span></td>
                                <td><span class="badge" style="background:rgba(11,190,214,0.1); color:var(--accent-cyan); padding:0.25rem 0.5rem; border-radius:1rem; font-size:0.75rem;">${new Date(u.last_login).toLocaleString()}</span></td>
                            </tr>
                        `).join('');
                    }
                }
            } catch(e) { console.error(e); }
        }

        async function loadUsers() {
            try {
                const res = await fetch('../api/admin.php?action=users');
                const data = await res.json();
                if(data.status === 'success') {
                    const tbody = document.querySelector('#allUsersTable tbody');
                    if (data.users.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">No clients found.</td></tr>`;
                    } else {
                        tbody.innerHTML = data.users.map(u => `
                            <tr>
                                <td>#${u.id}</td>
                                <td style="font-weight:600;">${u.name}</td>
                                <td>${u.email}</td>
                                <td><span style="font-size: 0.85rem; color: var(--text-muted);"><i class="fa-solid fa-clock mr-1"></i> ${new Date(u.last_login).toLocaleString()}</span></td>
                                <td><span style="display:inline-block; padding:0.25rem 0.75rem; border-radius:2rem; background: ${u.cart_item_count > 0 ? 'rgba(11,190,214,0.1)' : 'rgba(255,255,255,0.05)'}; color: ${u.cart_item_count > 0 ? 'var(--accent-cyan)' : 'var(--text-muted)'}; font-weight:700;">${u.cart_item_count} Items</span></td>
                                <td>
                                    <button class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;" onclick="inspectCart(${u.id})"><i class="fa-solid fa-eye mr-2"></i> Inspect Cart</button>
                                </td>
                            </tr>
                        `).join('');
                    }
                }
            } catch(e) { console.error(e); }
        }

        async function inspectCart(userId) {
            document.getElementById('cartModal').classList.add('active');
            const container = document.getElementById('modalCartItems');
            container.innerHTML = `<div class="text-center text-muted py-8"><i class="fa-solid fa-spinner fa-spin"></i> Loading...</div>`;

            try {
                const res = await fetch(`../api/admin.php?action=user_cart&user_id=${userId}`);
                const data = await res.json();
                
                if (data.status === 'success') {
                    document.getElementById('modalUserName').innerText = data.user.name + "'s Cart";
                    document.getElementById('modalUserEmail').innerText = data.user.email;

                    if (data.cart.length === 0) {
                        container.innerHTML = `<div class="text-center text-muted py-6"><i class="fa-solid fa-cart-shopping fa-3x mb-4 opacity-50"></i><p>This cart is empty.</p></div>`;
                    } else {
                        container.innerHTML = data.cart.map(item => `
                            <div style="display:flex; justify-content:space-between; align-items:center; padding: 1rem; border-bottom: 1px solid var(--border-glass-light); background: rgba(0,0,0,0.2); margin-bottom: 0.5rem; border-radius: 0.5rem;">
                                <div>
                                    <strong class="text-white block">${item.product_name}</strong>
                                    <span class="text-muted text-sm">Product ID: ${item.product_id}</span>
                                </div>
                                <div style="text-align:right;">
                                    <span class="text-accent" style="font-weight:700; font-size:1.125rem;">x${item.quantity}</span>
                                    <div class="text-muted text-sm" style="font-size:0.7rem;">Added: ${new Date(item.added_at).toLocaleString()}</div>
                                </div>
                            </div>
                        `).join('');
                    }
                }
            } catch (e) { console.error(e); }
        }

        // --- Products Functions ---
        async function loadProducts() {
            try {
                const res = await fetch('../api/products.php?action=fetch_all');
                const data = await res.json();
                if(data.status === 'success') {
                    const tbody = document.querySelector('#productsTable tbody');
                    if (data.products.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No products found.</td></tr>`;
                    } else {
                        tbody.innerHTML = data.products.map(p => {
                            let coas = [];
                            if (p.coa_paths) {
                                coas = JSON.parse(p.coa_paths) || [];
                            } else if (p.coa_path) {
                                coas = [p.coa_path];
                            }
                            const coaCount = coas.length;
                            return `
                            <tr>
                                <td><img src="../${p.image_path || p.image}" alt="${p.name}" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;"></td>
                                <td><span class="text-muted" style="font-family: monospace;">${p.id}</span></td>
                                <td style="font-weight:600;">${p.name}</td>
                                <td><span class="badge" style="background:rgba(255,255,255,0.05);">${p.category}</span></td>
                                <td><span class="text-accent">${p.purity || 'N/A'}</span></td>
                                <td>
                                    ${coaCount > 0 ? `<span class="text-accent text-sm">${coaCount} File(s)</span>` : `<span class="text-muted text-sm">None</span>`}
                                </td>
                                <td>
                                    <button class="icon-btn text-accent" onclick="editProduct('${encodeURIComponent(JSON.stringify(p))}')" title="Edit Product"><i class="fa-solid fa-pen"></i></button>
                                    <button class="icon-btn text-danger" onclick="deleteProduct('${p.id}')" title="Delete Product"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            `;
                        }).join('');
                    }
                }
            } catch(e) { console.error(e); }
        }

        function openProductModal() {
            document.getElementById('productForm').reset();
            document.getElementById('productModalTitle').innerText = 'Add New Product';
            document.getElementById('prodAction').value = 'add_product';
            document.getElementById('clearCoasContainer').style.display = 'none';
            document.getElementById('productModal').style.display = 'flex';
        }

        function editProduct(pStr) {
            const p = JSON.parse(decodeURIComponent(pStr));
            document.getElementById('productForm').reset();
            document.getElementById('productModalTitle').innerText = 'Edit Product';
            document.getElementById('prodAction').value = 'edit_product';
            document.getElementById('prodOldId').value = p.id;
            
            document.getElementById('prodId').value = p.id;
            document.getElementById('prodName').value = p.name;
            document.getElementById('prodCategory').value = p.category;
            document.getElementById('prodPurity').value = p.purity || '';
            document.getElementById('prodSpec').value = p.specification || '';
            document.getElementById('prodForm').value = p.form || '';
            document.getElementById('prodStorage').value = p.storage || '';
            document.getElementById('prodDesc').value = p.description || '';
            document.getElementById('prodOverview').value = p.overview || '';
            document.getElementById('prodApps').value = p.applications || '';
            document.getElementById('prodUsers').value = p.target_users || '';
            
            document.getElementById('clearCoasContainer').style.display = 'block';
            document.getElementById('productModal').style.display = 'flex';
        }

        function closeProductModal() {
            document.getElementById('productModal').style.display = 'none';
        }

        async function submitProductForm(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('action', document.getElementById('prodAction').value);
            formData.append('old_id', document.getElementById('prodOldId').value);
            formData.append('id', document.getElementById('prodId').value);
            formData.append('name', document.getElementById('prodName').value);
            formData.append('category', document.getElementById('prodCategory').value);
            formData.append('description', document.getElementById('prodDesc').value);
            formData.append('purity', document.getElementById('prodPurity').value);
            
            formData.append('specification', document.getElementById('prodSpec').value);
            formData.append('form', document.getElementById('prodForm').value);
            formData.append('storage', document.getElementById('prodStorage').value);
            formData.append('overview', document.getElementById('prodOverview').value);
            formData.append('applications', document.getElementById('prodApps').value);
            formData.append('target_users', document.getElementById('prodUsers').value);
            
            if (document.getElementById('clearCoas') && document.getElementById('clearCoas').checked) {
                formData.append('clear_coas', 'true');
            }
            
            const imageFile = document.getElementById('prodImage').files[0];
            if(imageFile) formData.append('image', imageFile);
            
            const coaFiles = document.getElementById('prodCoa').files;
            for(let i=0; i<coaFiles.length; i++){
                formData.append('coa[]', coaFiles[i]);
            }

            try {
                const res = await fetch('../api/products.php', { method: 'POST', body: formData });
                const data = await res.json();
                if(data.status === 'success') {
                    closeProductModal();
                    loadProducts();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(e) {
                console.error(e);
                alert('Connection error');
            } finally {
                btn.innerHTML = 'Save Product';
                btn.disabled = false;
            }
        }

        // --- Content Manager Functions ---
        async function saveContentChanges() {
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving...';
            btn.disabled = true;
            
            const contentData = {
                heroHeadline: document.getElementById('heroHeadline').value,
                heroSubheadline: document.getElementById('heroSubheadline').value,
                heroCTA: document.getElementById('heroCTA').value,
                heroCTA2: document.getElementById('heroCTA2').value,
                heroRating: document.getElementById('heroRating').value,
                aboutTitle: document.getElementById('aboutTitle').value,
                aboutDescription: document.getElementById('aboutDescription').value,
                aboutMission: document.getElementById('aboutMission').value,
                calcTitle: document.getElementById('calcTitle').value,
                calcDescription: document.getElementById('calcDescription').value,
                calcDefaultWater: document.getElementById('calcDefaultWater').value,
                qualityTitle: document.getElementById('qualityTitle').value,
                qualityDescription: document.getElementById('qualityDescription').value,
                qualityPurity: document.getElementById('qualityPurity').value,
                seoTitle: document.getElementById('seoTitle').value,
                seoDescription: document.getElementById('seoDescription').value,
                seoKeywords: document.getElementById('seoKeywords').value
            };
            
            try {
                const res = await fetch('../api/admin_settings.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'update_content', data: contentData })
                });
                const data = await res.json();
                if(data.status === 'success') {
                    btn.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Saved Successfully!';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-success');
                    
                    // Update frontend dynamically
                    updateFrontendContent(contentData);
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-primary');
                        btn.disabled = false;
                    }, 2000);
                } else {
                    btn.innerHTML = '<i class="fa-solid fa-exclamation-triangle mr-2"></i>Error - Try Again';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-danger');
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.remove('btn-danger');
                        btn.classList.add('btn-primary');
                        btn.disabled = false;
                    }, 2000);
                }
            } catch(e) {
                console.error(e);
                btn.innerHTML = '<i class="fa-solid fa-exclamation-triangle mr-2"></i>Connection Error';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-danger');
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-primary');
                    btn.disabled = false;
                }, 2000);
            }
        }
        
        function updateFrontendContent(content) {
            // Update hero section
            const heroTitle = document.querySelector('.hero-title');
            if(heroTitle) heroTitle.textContent = content.heroHeadline;
            
            const heroSubtitle = document.querySelector('.hero-subtitle');
            if(heroSubtitle) heroSubtitle.textContent = content.heroSubheadline;
            
            const primaryCTA = document.querySelector('.hero-cta-primary');
            if(primaryCTA) primaryCTA.textContent = content.heroCTA;
            
            const secondaryCTA = document.querySelector('.hero-cta-secondary');
            if(secondaryCTA) secondaryCTA.textContent = content.heroCTA2;
            
            // Update rating badge
            const ratingElement = document.querySelector('.rating-number');
            if(ratingElement) ratingElement.textContent = content.heroRating;
        }
        
        // --- Communication Logs Functions ---
        async function loadCommunications() {
            try {
                const res = await fetch('../api/admin_settings.php?action=get_communications');
                const data = await res.json();
                displayCommunications(data.communications);
            } catch(e) {
                console.error(e);
                document.getElementById('communicationTimeline').innerHTML = '<div class="text-center py-8 text-danger">Error loading communications</div>';
            }
        }
        
        function displayCommunications(communications) {
            const timeline = document.getElementById('communicationTimeline');
            if(communications.length === 0) {
                timeline.innerHTML = '<div class="text-center py-8 text-muted">No communications found</div>';
                return;
            }
            
            timeline.innerHTML = communications.map(comm => `
                <div class="communication-entry">
                    <div class="comm-header">
                        <div class="comm-customer">${comm.customer_name}</div>
                        <div class="comm-meta">
                            <span><i class="fa-solid fa-envelope"></i> ${comm.type}</span>
                            <span><i class="fa-solid fa-calendar"></i> ${new Date(comm.date).toLocaleDateString()}</span>
                        </div>
                    </div>
                    <div class="comm-content">${comm.message}</div>
                    <div class="comm-footer">
                        <span class="comm-status status-${comm.status}">${comm.status.replace('-', ' ').toUpperCase()}</span>
                        <button class="btn btn-sm btn-outline" onclick="editCommunication(${comm.id})">Edit</button>
                    </div>
                </div>
            `).join('');
        }
        
        async function addCommunication() {
            const customerName = prompt('Enter customer name:');
            if(!customerName) return;
            
            const message = prompt('Enter communication details:');
            if(!message) return;
            
            try {
                const res = await fetch('../api/admin_settings.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        action: 'add_communication',
                        customer_name: customerName,
                        message: message,
                        type: 'manual',
                        status: 'open'
                    })
                });
                const data = await res.json();
                if(data.status === 'success') {
                    loadCommunications();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(e) {
                console.error(e);
                alert('Connection error');
            }
        }
        
        // --- CRM System Functions ---
        async function loadCRM() {
            try {
                const res = await fetch('../api/admin_settings.php?action=get_crm');
                const data = await res.json();
                displayCRMStats(data.stats);
                displayCRMPipeline(data.pipeline);
            } catch(e) {
                console.error(e);
            }
        }
        
        function displayCRMStats(stats) {
            document.getElementById('crmTotalCustomers').textContent = stats.total_customers || 0;
            document.getElementById('crmActiveDeals').textContent = stats.active_deals || 0;
            document.getElementById('crmOpenConversations').textContent = stats.open_conversations || 0;
        }
        
        function displayCRMPipeline(pipeline) {
            // Update stage counts
            document.getElementById('stageNew').textContent = pipeline.new?.length || 0;
            document.getElementById('stageQualified').textContent = pipeline.qualified?.length || 0;
            document.getElementById('stageQuote').textContent = pipeline.quote_sent?.length || 0;
            document.getElementById('stageNegotiation').textContent = pipeline.negotiation?.length || 0;
            document.getElementById('stageWon').textContent = pipeline.won?.length || 0;
            
            // Display customer cards in each stage
            displayStageCards('stageNewCards', pipeline.new || []);
            displayStageCards('stageQualifiedCards', pipeline.qualified || []);
            displayStageCards('stageQuoteCards', pipeline.quote_sent || []);
            displayStageCards('stageNegotiationCards', pipeline.negotiation || []);
            displayStageCards('stageWonCards', pipeline.won || []);
        }
        
        function displayStageCards(stageId, customers) {
            const stageElement = document.getElementById(stageId);
            if(customers.length === 0) {
                stageElement.innerHTML = '<div class="text-center text-muted">No customers</div>';
                return;
            }
            
            stageElement.innerHTML = customers.map(customer => `
                <div class="customer-card" onclick="viewCustomerDetails(${customer.id})">
                    <div class="customer-name">${customer.name}</div>
                    <div class="customer-email">${customer.email}</div>
                    <div class="customer-value">$${customer.value || '0'}</div>
                </div>
            `).join('');
        }
        
        async function addCustomer() {
            const name = prompt('Enter customer name:');
            if(!name) return;
            
            const email = prompt('Enter customer email:');
            if(!email) return;
            
            try {
                const res = await fetch('../api/admin_settings.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        action: 'add_customer',
                        name: name,
                        email: email,
                        stage: 'new'
                    })
                });
                const data = await res.json();
                if(data.status === 'success') {
                    loadCRM();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(e) {
                console.error(e);
                alert('Connection error');
            }
        }
        
        function viewCustomerDetails(customerId) {
            // Open customer detail modal or navigate to detail page
            alert(`Customer ID: ${customerId} - Details view would open here`);
        }
        
        // --- Enhanced Tab Routing ---
        function showView(viewName) {
            document.querySelectorAll('.view-section').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.sidebar-link').forEach(el => el.classList.remove('active'));
            
            document.getElementById(`view-${viewName}`).style.display = 'block';
            if (event) event.currentTarget.classList.add('active');
            
            // Load data for specific views
            if(viewName === 'content') {
                // Load current content settings
                loadContentSettings();
            } else if(viewName === 'communications') {
                loadCommunications();
            } else if(viewName === 'crm') {
                loadCRM();
            }
        }
        
        async function loadContentSettings() {
            try {
                const res = await fetch('../api/admin_settings.php?action=get_content');
                const data = await res.json();
                
                // Populate form fields
                if(data.content) {
                    document.getElementById('heroHeadline').value = data.content.hero_headline || 'Premium Research Peptides';
                    document.getElementById('heroSubheadline').value = data.content.hero_subheadline || 'Global supplier of 98%+ purity research peptides';
                    document.getElementById('heroCTA').value = data.content.hero_cta || 'Get Started';
                    document.getElementById('heroCTA2').value = data.content.hero_cta2 || 'View Catalog';
                    document.getElementById('heroRating').value = data.content.hero_rating || '4.9';
                    document.getElementById('aboutTitle').value = data.content.about_title || 'About Qingli Peptide';
                    document.getElementById('aboutDescription').value = data.content.about_description || 'Premium supplier of high-purity peptides...';
                    document.getElementById('aboutMission').value = data.content.about_mission || 'To provide researchers worldwide...';
                    document.getElementById('calcTitle').value = data.content.calc_title || 'Peptide Reconstitution Calculator';
                    document.getElementById('calcDescription').value = data.content.calc_description || 'Achieve 100% accurate research measurements...';
                    document.getElementById('calcDefaultWater').value = data.content.calc_default_water || '2';
                    document.getElementById('qualityTitle').value = data.content.quality_title || 'Uncompromising Analytical Verification';
                    document.getElementById('qualityDescription').value = data.content.quality_description || 'In the research chemical sector...';
                    document.getElementById('qualityPurity').value = data.content.quality_purity || '99.9';
                    document.getElementById('seoTitle').value = data.content.seo_title || 'Qingli Peptide | Premium Research Peptides';
                    document.getElementById('seoDescription').value = data.content.seo_description || 'Global supplier of 98%+ purity research peptides...';
                    document.getElementById('seoKeywords').value = data.content.seo_keywords || 'research peptides, peptide synthesis...';
                }
            } catch(e) {
                console.error(e);
            }
        }

        // --- Enhanced Load Functions ---
        window.addEventListener('DOMContentLoaded', function() {
            // Load initial data for all views
            loadStats();
            loadProducts();
            loadQuotes();
            loadReviews();
            loadUsers();
            loadReps();
            loadSettings();
        });

        async function deleteProduct(id) {
            if(!confirm(`Are you sure you want to delete product "${id}"?`)) return;
            try {
                const res = await fetch('../api/products.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ action: 'delete_product', id })
                });
                const data = await res.json();
                if(data.status === 'success') {
                    loadProducts();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(e) { console.error(e); }
        }

        function closeCartModal() {
            document.getElementById('cartModal').classList.remove('active');
        }

        // --- Quotes Manager Logic ---
        async function loadQuotes() {
            try {
                const res = await fetch('../api/admin_quotes.php?action=fetch_all');
                const data = await res.json();
                if(data.status === 'success') {
                    const tbody = document.querySelector('#quotesTable tbody');
                    if (data.quotes.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-12"><i class="fa-solid fa-folder-open fa-3x mb-4 opacity-30"></i><br>No quote requests found.</td></tr>`;
                    } else {
                        tbody.innerHTML = data.quotes.map(q => {
                            const itemsList = q.items.map(i => `<div class="text-xs mb-1">• ${i.product_name} <span class="text-accent">(x${i.quantity})</span></div>`).join('');
                            
                            // Map statuses to colors
                            let statusClass = 'status-pending';
                            if(q.status === 'Under Review') statusClass = 'status-pending';
                            if(q.status === 'Paid' || q.status === 'Shipped') statusClass = 'status-approved';
                            if(q.status === 'Cancelled') statusClass = 'status-rejected';
                            if(q.status === 'Contacted') statusClass = 'status-approved';

                            const contactIcon = q.contact_method === 'whatsapp' ? 'whatsapp text-success' : 'envelope';
                            
                            return `
                            <tr>
                                <td><span class="badge" style="background:rgba(255,255,255,0.05);">${new Date(q.created_at).toLocaleDateString()}</span></td>
                                <td style="font-family: monospace; font-weight: bold;">#${q.id}</td>
                                <td>
                                    <div style="font-weight: 600;">${q.customer_name}</div>
                                    <div class="flex items-center gap-2 mt-1 mb-1">
                                        <span class="text-muted text-xs"><i class="fa-solid fa-${contactIcon} mr-1"></i> ${q.contact_detail}</span>
                                        <button class="icon-btn text-muted p-1" style="font-size: 0.65rem" onclick="navigator.clipboard.writeText('${q.contact_detail}')" title="Copy"><i class="fa-solid fa-copy"></i></button>
                                    </div>
                                    <span class="text-muted text-xs" style="text-transform: uppercase; letter-spacing: 0.05em;"><i class="fa-solid fa-location-dot mr-1"></i> ${q.shipping_city}, ${q.shipping_country}</span>
                                </td>
                                <td>${itemsList}</td>
                                <td>
                                    <div class="flex flex-col gap-2">
                                        <select class="glass-input text-xs" id="statusSelect-${q.id}" style="padding: 0.4rem; font-weight: 700; width: 130px;" onchange="updateQuoteStatus(${q.id}, this.value)">
                                            <option value="Pending" ${q.status==='Pending'?'selected':''}>⏳ Pending</option>
                                            <option value="Under Review" ${q.status==='Under Review'?'selected':''}>🔍 Under Review</option>
                                            <option value="Contacted" ${q.status==='Contacted'?'selected':''}>💬 Contacted</option>
                                            <option value="Paid" ${q.status==='Paid'?'selected':''}>💰 Paid</option>
                                            <option value="Shipped" ${q.status==='Shipped'?'selected':''}>🚀 Shipped</option>
                                            <option value="Cancelled" ${q.status==='Cancelled'?'selected':''}>❌ Cancelled</option>
                                        </select>
                                        <div id="statusSaved-${q.id}" class="text-success text-xs" style="display:none;"><i class="fa-solid fa-check mr-1"></i> Sync Complete</div>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="glass-input" style="min-height: 60px; padding: 0.5rem; font-size: 0.8rem; width: 150px;" onblur="updateQuoteNotes(${q.id}, this.value)" placeholder="Add private notes...">${q.internal_notes || ''}</textarea>
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <button class="action-btn action-btn-approve" title="Direct Outreach" onclick="sendFinalQuote(${q.id}, '${q.customer_name.replace(/'/g, "\\'")}', '${q.contact_method}', '${q.contact_detail.replace(/'/g, "\\'")}')">
                                            <i class="fa-solid fa-paper-plane"></i> Reach Out
                                        </button>
                                        <button class="action-btn action-btn-delete" title="Delete Quote" onclick="deleteQuote(${q.id})">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            `;
                        }).join('');
                    }
                }
            } catch(e) { console.error(e); }
        }

        async function updateQuoteStatus(quoteId, status) {
            const savedIndicator = document.getElementById(`statusSaved-${quoteId}`);
            try {
                const res = await fetch('../api/admin_quotes.php?action=update_status', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ quote_id: quoteId, status: status })
                });
                const data = await res.json();
                if(data.status === 'success') {
                    if(savedIndicator) {
                        savedIndicator.style.display = 'block';
                        setTimeout(() => savedIndicator.style.display = 'none', 1500);
                    }
                }
            } catch(e) { console.error(e); }
        }

        async function deleteQuote(id) {
            if(!confirm(`PERMANENTLY DELETE Quote #${id}? This will remove all items and history for this request.`)) return;
            try {
                const res = await fetch('../api/admin_quotes.php?action=delete_quote', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ quote_id: id })
                });
                const data = await res.json();
                if(data.status === 'success') {
                    loadQuotes();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(e) { console.error(e); }
        }

        async function updateQuoteNotes(quoteId, notes) {
            try {
                await fetch('../api/admin_quotes.php?action=update_notes', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ quote_id: quoteId, notes: notes })
                });
            } catch(e) { console.error(e); }
        }

        function sendFinalQuote(quoteId, customerName, contactMethod, contactDetail) {
            // Because the server lacks an active WhatsApp API, the user asked for a pre-filled frontend redirect link.
            // If the user contact is WhatsApp, we open wa.me. Otherwise we use mailto if email.
            
            const message = `Hello ${customerName},\n\nThis is the Qingli Peptide Research team regarding your Quote Request #${quoteId}.\n\nYour quote has been reviewed and approved. The total inclusive of secure shipping is $____.\n\nYou can proceed with secure payment via the following BTC/USDT address:\n[Insert Address]\n\nPlease let us know when the transaction is complete so we can process your dispatch instantly.\n\nThank you,\nCEO, Qingli Peptide`;
            
            if (contactMethod === 'whatsapp') {
                // Formatting number for wa.me. Removing non-numeric characters just in case, though usually users input full intl formatted strings.
                let phoneFormatted = contactDetail.replace(/[^0-9\+]/g, '');
                if(phoneFormatted.startsWith('00')) phoneFormatted = '+' + phoneFormatted.substring(2);
                
                const waUrl = `https://wa.me/${phoneFormatted}?text=${encodeURIComponent(message)}`;
                window.open(waUrl, '_blank');
            } else {
                const mailtoUrl = `mailto:${contactDetail}?subject=Qingli Peptide Request %23${quoteId}&body=${encodeURIComponent(message)}`;
                window.location.href = mailtoUrl;
            }
        }

        async function logoutAdmin() {
            await fetch('../api/auth.php?action=logout');
            window.location.href = '../index.html';
        }

        // --- Settings Manager Logic ---
        async function loadSettings() {
            try {
                const res = await fetch('../api/settings.php?action=get');
                const data = await res.json();
                if (data.status === 'success' && data.settings.marquee_messages) {
                    document.getElementById('marqueeMessagesInput').value = data.settings.marquee_messages.join('\n');
                }
            } catch (e) { console.error(e); }
        }

        async function saveMarqueeSettings(btn) {
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving...';
            btn.disabled = true;

            const text = document.getElementById('marqueeMessagesInput').value;
            const messages = text.split('\n').filter(m => m.trim() !== '');

            try {
                const res = await fetch('../api/settings.php?action=update_marquee', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ messages })
                });
                const data = await res.json();
                
                if (data.status === 'success') {
                    btn.innerHTML = '<i class="fa-solid fa-check mr-2"></i> Saved';
                    btn.classList.add('btn-success');
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.remove('btn-success');
                        btn.disabled = false;
                    }, 2000);
                } else {
                    alert('Error: ' + data.message);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            } catch (e) {
                console.error(e);
                alert('Connection error');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        // --- Representatives Manager Logic ---
        async function loadReps() {
            try {
                const res = await fetch('../api/admin_reps.php');
                const data = await res.json();
                if (data.status === 'success') {
                    const tbody = document.querySelector('#repsTable tbody');
                    if (data.reps.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">No representatives found.</td></tr>`;
                    } else {
                        tbody.innerHTML = data.reps.map(r => `
                            <tr>
                                <td style="font-family: monospace; font-weight: bold; color: var(--accent-cyan);">${r.rep_id}</td>
                                <td style="font-weight: 600;">${r.name}</td>
                                <td class="text-muted">${r.territory || 'N/A'}</td>
                                <td>
                                    <span class="badge" style="background: ${r.status === 'Active' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)'}; color: ${r.status === 'Active' ? 'var(--success-green)' : 'var(--danger-red)'}; border: 1px solid ${r.status === 'Active' ? 'var(--success-green)' : 'var(--danger-red)'}; font-weight: bold;">${r.status}</span>
                                </td>
                                <td>
                                    <button class="icon-btn text-accent" onclick="editRep('${encodeURIComponent(JSON.stringify(r))}')" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                    <button class="icon-btn text-danger" onclick="deleteRep(${r.id})" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        `).join('');
                    }
                }
            } catch (e) { console.error('Error loading reps', e); }
        }

        function openRepModal() {
            document.getElementById('repForm').reset();
            document.getElementById('repModalTitle').innerText = 'Add New Representative';
            document.getElementById('repAction').value = 'add';
            document.getElementById('repDbId').value = '0';
            document.getElementById('repIdInput').readOnly = false;
            document.getElementById('repModal').style.display = 'flex';
        }

        function closeRepModal() {
            document.getElementById('repModal').style.display = 'none';
        }

        function editRep(rStr) {
            const r = JSON.parse(decodeURIComponent(rStr));
            document.getElementById('repForm').reset();
            document.getElementById('repModalTitle').innerText = 'Edit Representative';
            document.getElementById('repAction').value = 'update';
            document.getElementById('repDbId').value = r.id;
            
            document.getElementById('repIdInput').value = r.rep_id;
            document.getElementById('repIdInput').readOnly = true; // Don't allow changing ID after creation
            document.getElementById('repNameInput').value = r.name;
            document.getElementById('repTerritoryInput').value = r.territory || '';
            document.getElementById('repStatusInput').value = r.status;
            
            document.getElementById('repModal').style.display = 'flex';
        }

        async function submitRepForm(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
            btn.disabled = true;

            const payload = {
                action: document.getElementById('repAction').value,
                id: document.getElementById('repDbId').value,
                rep_id: document.getElementById('repIdInput').value,
                name: document.getElementById('repNameInput').value,
                territory: document.getElementById('repTerritoryInput').value,
                status: document.getElementById('repStatusInput').value
            };

            try {
                const res = await fetch('../api/admin_reps.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                
                if (data.status === 'success') {
                    closeRepModal();
                    loadReps();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(error) {
                console.error(error);
                alert('Connection error');
            } finally {
                btn.innerHTML = 'Save Representative';
                btn.disabled = false;
            }
        }

        async function deleteRep(id) {
            if (!confirm('Are you sure you want to delete this representative? This action cannot be undone.')) return;
            try {
                const res = await fetch('../api/admin_reps.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete', id: id })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    loadReps();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(e) { console.error(e); }
        }

        // --- Reviews Manager Logic ---
        async function loadReviews() {
            try {
                const res = await fetch('../api/admin_reviews.php?action=fetch_all');
                const data = await res.json();
                if (data.status === 'success') {
                    const tbody = document.querySelector('#reviewsTable tbody');
                    if (data.reviews.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted">No reviews found.</td></tr>`;
                    } else {
                        tbody.innerHTML = data.reviews.map(r => {
                            const stars = '★'.repeat(r.overall_rating) + '☆'.repeat(5 - r.overall_rating);
                            let statusColor = 'var(--text-muted)';
                            if (r.status === 'approved') statusColor = 'var(--success-green)';
                            if (r.status === 'rejected') statusColor = 'var(--danger-red)';
                            
                            return `
                            <tr>
                                <td><span class="badge" style="background:rgba(255,255,255,0.05);">${new Date(r.submitted_at).toLocaleDateString()}</span></td>
                                <td style="font-weight:600;">${r.display_name}</td>
                                <td><span style="color: #ffd700; font-size: 1.1rem;">${stars}</span></td>
                                <td>${r.communication_professional ? '<i class="fa-solid fa-circle-check text-success"></i>' : '<i class="fa-solid fa-circle-xmark text-muted"></i>'}</td>
                                <td>${r.shipping_discreet_timely ? '<i class="fa-solid fa-circle-check text-success"></i>' : '<i class="fa-solid fa-circle-xmark text-muted"></i>'}</td>
                                <td>${r.product_lab_standards ? '<i class="fa-solid fa-circle-check text-success"></i>' : '<i class="fa-solid fa-circle-xmark text-muted"></i>'}</td>
                                <td style="max-width: 250px; font-size: 0.9rem; line-height: 1.4; color: var(--text-muted);">${r.review_text}</td>
                                <td>
                                    <span class="status-pill status-${r.status}">${r.status}</span>
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        ${r.status === 'pending' ? `
                                            <button class="action-btn action-btn-approve" onclick="approveReview(${r.id})">
                                                <i class="fa-solid fa-check"></i> Approve
                                            </button>
                                            <button class="action-btn action-btn-reject" onclick="rejectReview(${r.id})">
                                                <i class="fa-solid fa-xmark"></i> Reject
                                            </button>
                                        ` : r.status === 'approved' ? `
                                            <button class="action-btn action-btn-reject" onclick="rejectReview(${r.id})">
                                                <i class="fa-solid fa-ban"></i> Revoke
                                            </button>
                                        ` : `
                                            <button class="action-btn action-btn-approve" onclick="approveReview(${r.id})">
                                                <i class="fa-solid fa-rotate-left"></i> Re-Approve
                                            </button>
                                        `}
                                        <button class="action-btn action-btn-delete" onclick="deleteReview(${r.id})">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            `;
                        }).join('');
                    }
                }
            } catch (e) { console.error('Error loading reviews', e); }
        }

        async function approveReview(id) {
            if (!confirm('Approve this review for storefront display?')) return;
            try {
                const res = await fetch('../api/admin_reviews.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'approve', id: id })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    loadReviews();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (e) { console.error(e); }
        }

        async function rejectReview(id) {
            if (!confirm('Reject this review? It will not be shown on the storefront.')) return;
            try {
                const res = await fetch('../api/admin_reviews.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'reject', id: id })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    loadReviews();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (e) { console.error(e); }
        }

        async function deleteReview(id) {
            if (!confirm('PERMANENTLY DELETE this review? This action cannot be undone.')) return;
            try {
                const res = await fetch('../api/admin_reviews.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete', id: id })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    loadReviews();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (e) { console.error(e); }
        }

        // Enhanced Marquee Functions
        function updateMessageCount() {
            const textarea = document.getElementById('settingMarqueeText');
            const count = textarea.value.split('\n').filter(line => line.trim().length > 0).length;
            document.getElementById('messageCount').textContent = `${count} message${count !== 1 ? 's' : ''}`;
        }

        function updateSpeedDisplay(value) {
            document.getElementById('speedDisplay').textContent = `${value}s`;
            document.getElementById('settingMarqueeSpeed').value = value;
        }

        function setSpeed(speed) {
            document.getElementById('settingMarqueeSpeed').value = speed;
            document.getElementById('speedDisplay').textContent = `${speed}s`;
        }

        function setTheme(theme) {
            const colors = {
                'cyan': '#0bbed6',
                'gold': '#ffd700',
                'red': '#ff4444',
                'green': '#44ff44'
            };
            document.getElementById('settingMarqueeColor').value = colors[theme];
            document.getElementById('settingMarqueeColorText').value = colors[theme];
            
            // Update theme buttons
            document.querySelectorAll('.theme-btn').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-white');
            });
            event.target.classList.add('ring-2', 'ring-white');
        }

        function addTemplateMessages() {
            const templates = [
                '• GLOBAL EXPORT QUALITY',
                '• GUARANTEED CUSTOMS CLEARANCE',
                '• UNMATCHED PURITY STANDARDS',
                '• 24/7 TECHNICAL SUPPORT',
                '• DISCREET WORLDWIDE SHIPPING',
                '• LAB-TESTED CERTIFIED',
                '• FAST SECURE DELIVERY'
            ];
            
            const textarea = document.getElementById('settingMarqueeText');
            const current = textarea.value.split('\n').filter(line => line.trim().length > 0);
            const combined = [...new Set([...current, ...templates])];
            textarea.value = combined.join('\n');
            updateMessageCount();
        }

        function clearAllMessages() {
            if (confirm('Clear all marquee messages?')) {
                document.getElementById('settingMarqueeText').value = '';
                updateMessageCount();
            }
        }

        function resetMarqueeToDefault() {
            if (confirm('Reset marquee to default settings?')) {
                const defaults = [
                    '• GLOBAL EXPORT QUALITY',
                    '• GUARANTEED CUSTOMS CLEARANCE',
                    '• UNMATCHED PURITY STANDARDS'
                ];
                
                document.getElementById('settingMarqueeText').value = defaults.join('\n');
                document.getElementById('settingMarqueeSpeed').value = 30;
                document.getElementById('speedDisplay').textContent = '30s';
                document.getElementById('settingMarqueeColor').value = '#0bbed6';
                document.getElementById('settingMarqueeColorText').value = '#0bbed6';
                document.getElementById('marqueePauseHover').checked = true;
                document.getElementById('marqueeAutoRefresh').checked = true;
                document.getElementById('marqueeShowIcons').checked = true;
                
                updateMessageCount();
            }
        }

        function toggleMarqueeStatus() {
            // This would toggle the marquee visibility
            alert('Marquee status toggled. (This would enable/disable the marquee on the frontend)');
        }

        function previewMarquee() {
            // Open a new window with marquee preview
            const messages = document.getElementById('settingMarqueeText').value;
            const speed = document.getElementById('settingMarqueeSpeed').value;
            const color = document.getElementById('settingMarqueeColor').value;
            
            const preview = window.open('', 'marqueePreview', 'width=800,height=200,scrollbars=no,resizable=no');
            preview.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Marquee Preview</title>
                    <style>
                        body { margin: 0; background: #050810; font-family: sans-serif; }
                        .preview-container { padding: 20px; text-align: center; }
                        .marquee-preview { 
                            width: 100%; 
                            overflow: hidden; 
                            background: linear-gradient(135deg, rgba(5, 8, 16, 0.95) 0%, rgba(10, 15, 26, 0.9) 50%, rgba(5, 8, 16, 0.95) 100%);
                            border: 1px solid rgba(11, 190, 214, 0.2);
                            padding: 1rem 0;
                        }
                        .marquee-inner {
                            display: flex; white-space: nowrap; animation: scroll ${speed}s linear infinite;
                            font-weight: 700; color: ${color}; letter-spacing: 0.15em; text-transform: uppercase;
                        }
                        @keyframes scroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
                        .message { margin: 0 3rem; padding: 0.5rem 1.5rem; background: rgba(11, 190, 214, 0.1); border: 1px solid rgba(11, 190, 214, 0.2); border-radius: 50px; }
                    </style>
                </head>
                <body>
                    <div class="preview-container">
                        <h3 style="color: white; margin-bottom: 20px;">Marquee Preview</h3>
                        <div class="marquee-preview">
                            <div class="marquee-inner">
                                ${messages.split('\n').filter(m => m.trim()).map(m => `<span class="message">${m.trim()}</span>`).join('<span style="margin: 0 1rem;">|</span>') + 
                                  messages.split('\n').filter(m => m.trim()).map(m => `<span class="message">${m.trim()}</span>`).join('<span style="margin: 0 1rem;">|</span>')}
                            </div>
                        </div>
                        <p style="color: #666; margin-top: 20px; font-size: 12px;">Speed: ${speed}s | Color: ${color}</p>
                    </div>
                </body>
                </html>
            `);
        }

        // Enhanced save function
        async function saveMarqueeSettings(btn) {
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Saving...';
            btn.disabled = true;

            try {
                const formData = new FormData();
                formData.append('action', 'save');
                formData.append('contact_marquee', document.getElementById('settingMarqueeText').value);
                formData.append('marquee_speed', document.getElementById('settingMarqueeSpeed').value);
                formData.append('marquee_color', document.getElementById('settingMarqueeColor').value);
                formData.append('marquee_pause_hover', document.getElementById('marqueePauseHover').checked ? '1' : '0');
                formData.append('marquee_auto_refresh', document.getElementById('marqueeAutoRefresh').checked ? '1' : '0');
                formData.append('marquee_show_icons', document.getElementById('marqueeShowIcons').checked ? '1' : '0');

                const res = await fetch('../api/settings.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();
                if (data.status === 'success') {
                    btn.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Saved Successfully!';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-success');
                    
                    // Refresh frontend marquee
                    if (window.opener && window.opener.professionalMarquee) {
                        window.opener.professionalMarquee.fetchMarqueeData();
                    }
                    
                    setTimeout(() => {
                        btn.innerHTML = original;
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-primary');
                        btn.disabled = false;
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Save failed');
                }
            } catch (e) {
                console.error(e);
                btn.innerHTML = '<i class="fa-solid fa-exclamation-triangle mr-2"></i>Error - Try Again';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-danger');
                setTimeout(() => {
                    btn.innerHTML = original;
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-primary');
                    btn.disabled = false;
                }, 2000);
            }
        }

        // Init on load
        document.addEventListener('DOMContentLoaded', () => {
            loadStats();
            updateMessageCount();
        });
    </script>
</body>
</html>
