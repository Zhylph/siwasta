<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> RSUD H. Abdul Aziz Marabahan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1a8e83;
            --primary-dark: #0e6b62;
            --secondary: #34a853;
            --accent: #00bcd4;
            --light: #f8f9fa;
            --dark: #2c3e50;
            --danger: #ea4335;
            --warning: #fbbc05;
            --success: #34a853;
            --info: #4285f4;
            --gray: #6c757d;
            --teal: #1a8e83;
            --teal-light: #e0f2f1;
            --teal-dark: #00695c;
        }

        body {
            background-color: #f0f4f8;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        /* Header & Navigation */
        .navbar {
            background-color: var(--teal);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.7rem 1rem;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.4rem;
            color: white !important;
            display: flex;
            align-items: center;
        }

        .navbar-brand i {
            margin-right: 10px;
            font-size: 1.8rem;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .navbar-dark .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .navbar-dark .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Content */
        .content-wrapper {
            padding: 40px 0;
        }

        .page-header {
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }

        .page-header:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary);
        }

        /* Cards */
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card-header {
            background-color: var(--teal);
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px 20px;
            display: flex;
            align-items: center;
        }

        .card-header i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .card-body {
            padding: 20px;
        }

        .unit-card {
            transition: all 0.3s ease;
            border-radius: 8px;
            overflow: hidden;
            background-color: white;
        }

        .unit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .unit-card .card-header {
            background-color: var(--teal);
            color: white;
            border-bottom: none;
        }

        .unit-card .card-header i {
            color: white;
        }

        /* User Info */
        .user-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--success);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .user-photo-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: var(--gray);
            border: 3px solid var(--gray);
        }

        .user-info {
            display: flex;
            align-items: center;
            background-color: rgba(248, 249, 250, 0.7);
            padding: 15px;
            border-radius: 10px;
        }

        .user-details {
            margin-left: 20px;
        }

        .user-details h5 {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--teal-dark);
        }

        .contact-info {
            font-size: 0.9rem;
            color: var(--gray);
            display: flex;
            align-items: center;
            margin-top: 5px;
        }

        .contact-info i {
            margin-right: 5px;
            color: var(--teal);
        }

        .timestamp {
            font-size: 0.8rem;
            color: var(--gray);
            display: flex;
            align-items: center;
            margin-top: 5px;
        }

        .timestamp i {
            margin-right: 5px;
            color: var(--teal);
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .unit-empty {
            padding: 30px;
            text-align: center;
            color: var(--gray);
            background-color: rgba(248, 249, 250, 0.7);
            border-radius: 10px;
        }

        .unit-empty i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--gray);
            opacity: 0.5;
        }

        /* Refresh Button */
        .refresh-btn {
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .refresh-btn:hover {
            transform: rotate(15deg);
        }

        /* Animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .user-info {
                flex-direction: column;
                text-align: center;
            }

            .user-details {
                margin-left: 0;
                margin-top: 15px;
            }

            .contact-info, .timestamp {
                justify-content: center;
            }
        }
    </style>
    <meta http-equiv="refresh" content="30">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('public_view'); ?>">
                <i class="fas fa-hospital"></i> SIMRS Monarch
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('public_view'); ?>">
                            <i class="fas fa-home"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('auth'); ?>">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container content-wrapper">
