<?php session_start();

include( 'db_connect.php' );
// Use main database $conn->select_db( 'if0_40284661_sprs_mainredo' );

// RECENT EVENTS
$recentEvents = $conn->query( "
    SELECT eventName, eventRewards, eventDate, eventImage 
    FROM schoolevents 
    WHERE eventDate <= CURDATE() 
    ORDER BY eventDate DESC 
    LIMIT 3
" );

// UPCOMING EVENTS
$upcomingEvents = $conn->query( "
    SELECT eventName, eventRewards, eventDate, eventImage 
    FROM schoolevents 
    WHERE eventDate > CURDATE() 
    ORDER BY eventDate ASC 
    LIMIT 3
" );

/* ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  = TOP STUDENTS ( Based on points ) FROM sprs_dummydb ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  = */
$conn->select_db( 'if0_40284661_sprs_dummydb' );

$topStudents = $conn->query( " SELECT name, points, department FROM users WHERE role = 'student' ORDER BY points DESC " );
?>

<!DOCTYPE html>
<html lang = 'en'>
<head> <meta charset = 'UTF-8'> <meta name = 'viewport' content = 'width=device-width, initial-scale=1.0'>
<title>Student Point-Reward System | SPRS</title>
<style>
:root {
    --accent-1: #60a5fa;
    --accent-2: #2563eb;
    --accent-3: #14b8a6;
    --dark-bg: #020617;
    --card-bg: rgba( 15, 23, 42, 0.90 );
    --text-muted: #cbd5f5;
    --transition: 250ms ease;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    scroll-behavior: smooth;
}

body {
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    background: #020617;
    color: #e5e7eb;
    line-height: 1.6;
}

.page-wrapper {
    min-height: 100vh;
    background: url( 'bg.jpg' ) no-repeat center center fixed;
    background-size: cover;
    position: relative;
}

.page-overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient( circle at top left, rgba( 37, 99, 235, 0.25 ), transparent 55% ),
    radial-gradient( circle at bottom right, rgba( 20, 184, 166, 0.18 ), transparent 55% ),
    rgba( 15, 23, 42, 0.70 );
    z-index: 0;
}

main {
    position: relative;
    z-index: 1;
}

section {
    padding: 80px 20px;
}

.container {
    max-width: 1100px;
    margin: 0 auto;
}

.section-title {
    font-size: 32px;
    margin-bottom: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
    background: linear-gradient( 120deg, #38bdf8, #6366f1, #22c55e );
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.section-subtitle {
    font-size: 15px;
    color: var( --text-muted );
    max-width: 720px;
    margin-bottom: 32px;
}

/* NAVBAR */
header {
    position: sticky;
    top: 0;
    z-index: 10;
    backdrop-filter: blur( 18px );
    background: linear-gradient( to right, rgba( 15, 23, 42, 0.85 ), rgba( 15, 23, 42, 0.75 ) );
    border-bottom: 1px solid rgba( 148, 163, 184, 0.2 );
}

.nav {
    max-width: 1100px;
    margin: 0 auto;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.logo {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 18px;
}

.logo-badge {
    width: 32px;
    height: 32px;
    border-radius: 999px;
    background: radial-gradient( circle at 30% 20%, #e0f2fe 0, #60a5fa 25%, #1d4ed8 60%, #0b1120 100% );
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: #0b1120;
    font-weight: 800;
    box-shadow: 0 0 16px rgba( 59, 130, 246, 0.8 );
}

.nav-links {
    display: flex;
    gap: 18px;
    font-size: 14px;
}

.nav-links a {
    color: #cbd5f5;
    text-decoration: none;
    padding: 6px 8px;
    border-radius: 999px;
    transition: background var( --transition ), color var( --transition ), transform var( --transition );
}

.nav-links a:hover {
    background: rgba( 37, 99, 235, 0.15 );
    color: #e5e7eb;
    transform: translateY( -1px );
}

.nav-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 999px;
    font-size: 14px;
    text-decoration: none;
    font-weight: 600;
    background: linear-gradient( 135deg, var( --accent-1 ), var( --accent-2 ) );
    color: white;
    box-shadow: 0 0 18px rgba( 37, 99, 235, 0.7 );
    transition: transform var( --transition ), box-shadow var( --transition ), background var( --transition );
}

.nav-cta span.icon {
    font-size: 16px;
}

.nav-cta:hover {
    transform: translateY( -2px ) scale( 1.02 );
    background: linear-gradient( 135deg, #3b82f6, #1d4ed8 );
    box-shadow: 0 0 24px rgba( 59, 130, 246, 0.9 );
}

/* HERO */
.hero {
    min-height: calc( 100vh - 60px );
    display: flex;
    align-items: center;
}

.hero-grid {
    display: grid;
    grid-template-columns: minmax( 0, 1.2fr ) minmax( 0, 1fr );
    gap: 40px;
    align-items: center;
}

@import url( 'https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap' );

.hero-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 36px;
    margin-bottom: 12px;
    letter-spacing: 0.5px;
    background: linear-gradient( 90deg, #4fc3f7, #6366f1, #22c55e, #6366f1, #4fc3f7 );
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-size: 220% auto;
    animation: shine 6s linear infinite;
    text-shadow: 2px 2px 6px rgba( 0, 0, 0, 0.35 );
}

@keyframes shine {
    0% {
        background-position: 0% center;
    }
    50% {
        background-position: 100% center;
    }
    100% {
        background-position: 0% center;
    }
}

.hero-sub {
    font-size: 15px;
    color: var( --text-muted );
    max-width: 550px;
    margin-bottom: 24px;
}

.hero-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 26px;
}

.hero-badge {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 6px 12px;
    border-radius: 999px;
    border: 1px solid rgba( 148, 163, 184, 0.45 );
    background: radial-gradient( circle at top left, rgba( 56, 189, 248, 0.22 ), transparent 60% );
    color: #e5e7eb;
}

/* HERO RIGHT – CARDS */
.hero-right {
    display: grid;
    gap: 16px;
}

.hero-card {
    background: var( --card-bg );
    border-radius: 18px;
    padding: 18px;
    border: 1px solid rgba( 148, 163, 184, 0.5 );
    box-shadow: 0 16px 45px rgba( 15, 23, 42, 0.9 );
}

.hero-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.hero-card-title {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #c7d2fe;
}

.hero-timeline {
    font-size: 11px;
    display: grid;
    gap: 7px;
}

.timeline-item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
}

.timeline-dot {
    margin-top: 4px;
    width: 7px;
    height: 7px;
    border-radius: 999px;
    background: radial-gradient( circle, #38bdf8, #0ea5e9 );
    box-shadow: 0 0 10px rgba( 56, 189, 248, 0.9 );
}

.timeline-text {
    color: var( --text-muted );
}

.timeline-text strong {
    color: #e5e7eb;
}

/* Scrollable list for top students */
.scroll-list {
    max-height: 180px;
    overflow-y: auto;
    display: grid;
    gap: 6px;
    font-size: 11px;
    color: var( --text-muted );
    margin-top: 6px;
}

.list-row {
    display: flex;
    justify-content: space-between;
    gap: 8px;
}

.list-label {
    font-weight: 500;
    color: #e5e7eb;
}

.badge-soft {
    font-size: 10px;
    padding: 3px 8px;
    border-radius: 999px;
    background: rgba( 37, 99, 235, 0.2 );
    border: 1px solid rgba( 129, 140, 248, 0.7 );
    color: #c7d2fe;
}

.pill-soft {
    font-size: 10px;
    padding: 3px 8px;
    border-radius: 999px;
    background: rgba( 22, 163, 74, 0.18 );
    border: 1px solid rgba( 74, 222, 128, 0.7 );
    color: #bbf7d0;
}

/*benefits*/
/* BENEFITS, HOW, FEATURES, WHO, CTA – SAME STRUCTURE */
.benefits-grid {
    display: grid;
    grid-template-columns: repeat( 3, minmax( 0, 1fr ) );
    gap: 18px;
    margin-top: 10px;
}

.benefit-card {
    background: var( --card-bg );
    border-radius: 18px;
    padding: 18px 18px 16px;
    border: 1px solid rgba( 148, 163, 184, 0.55 );
    box-shadow: 0 16px 45px rgba( 15, 23, 42, 0.9 );
    font-size: 14px;
}

.benefit-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.11em;
    color: #93c5fd;
    margin-bottom: 4px;
}

.benefit-title {
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 8px;
}

.benefit-text {
    font-size: 13px;
    color: var( --text-muted );
    margin-bottom: 8px;
}

.benefit-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: #a5b4fc;
}

.how-grid {
    display: grid;
    grid-template-columns: 1.1fr 1.3fr;
    gap: 24px;
    align-items: flex-start;
}

/*Modules*/
.modules-card {
    background: var( --card-bg );
    border-radius: 18px;
    padding: 18px;
    border: 1px solid rgba( 148, 163, 184, 0.5 );
    box-shadow: 0 16px 45px rgba( 15, 23, 42, 0.9 );
    font-size: 13px;
}

.modules-heading {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 10px;
}

.modules-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 10px;
}

.modules-pill {
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 999px;
    background: rgba( 15, 23, 42, 0.95 );
    border: 1px solid rgba( 148, 163, 184, 0.6 );
    color: #e5e7eb;
}

.modules-list {
    list-style: none;
    display: grid;
    gap: 6px;
    margin-top: 4px;
}

.modules-list li {
    display: flex;
    gap: 8px;
    align-items: flex-start;
    color: var( --text-muted );
}

.modules-bullet {
    margin-top: 4px;
    font-size: 10px;
}

.steps {
    display: grid;
    gap: 10px;
}

.step-item {
    border-left: 2px solid rgba( 148, 163, 184, 0.7 );
    padding-left: 12px;
    font-size: 13px;
    color: var( --text-muted );
    position: relative;
}

.step-item::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 4px;
    width: 10px;
    height: 10px;
    border-radius: 999px;
    background: #38bdf8;
    box-shadow: 0 0 10px rgba( 56, 189, 248, 0.9 );
}

.step-title {
    font-size: 14px;
    font-weight: 600;
    color: #e5e7eb;
    margin-bottom: 2px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat( 3, minmax( 0, 1fr ) );
    gap: 18px;
}

.feature-card {
    background: var( --card-bg );
    border-radius: 18px;
    padding: 16px 16px 14px;
    border: 1px solid rgba( 148, 163, 184, 0.6 );
    box-shadow: 0 16px 45px rgba( 15, 23, 42, 0.9 );
    font-size: 13px;
}

.feature-chip {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: #bae6fd;
    margin-bottom: 6px;
}

.feature-title {
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 6px;
}

.feature-text {
    font-size: 13px;
    color: var( --text-muted );
    margin-bottom: 8px;
}

.feature-meta {
    font-size: 11px;
    color: #a5b4fc;
}

.who-grid {
    display: grid;
    grid-template-columns: repeat( 3, minmax( 0, 1fr ) );
    gap: 18px;
}

.who-card {
    background: rgba( 15, 23, 42, 0.96 );
    border-radius: 18px;
    padding: 16px 16px 14px;
    border: 1px solid rgba( 148, 163, 184, 0.6 );
    box-shadow: 0 12px 35px rgba( 15, 23, 42, 0.9 );
    font-size: 13px;
}

.who-title {
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 6px;
}

.who-list {
    margin-left: 14px;
    color: var( --text-muted );
    font-size: 13px;
}

.who-list li {
    margin-bottom: 4px;
}

.cta {
    text-align: center;
    padding-bottom: 80px;
}

.cta-box {
    max-width: 700px;
    margin: 0 auto;
    background: radial-gradient( circle at top, rgba( 59, 130, 246, 0.35 ), rgba( 15, 23, 42, 0.98 ) );
    border-radius: 24px;
    border: 1px solid rgba( 129, 140, 248, 0.8 );
    padding: 32px 24px 26px;
    box-shadow: 0 24px 60px rgba( 15, 23, 42, 1 );
}

.cta-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 8px;
}

.cta-sub {
    font-size: 14px;
    color: var( --text-muted );
    margin-bottom: 18px;
}

.cta-stats {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 16px;
    font-size: 12px;
    color: #c7d2fe;
}

.cta-stat-item {
    padding: 6px 12px;
    border-radius: 999px;
    background: rgba( 15, 23, 42, 0.9 );
    border: 1px solid rgba( 129, 140, 248, 0.7 );
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 26px;
    border-radius: 999px;
    background: linear-gradient( 135deg, var( --accent-1 ), var( --accent-2 ) );
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0.02em;
    text-decoration: none;
    box-shadow: 0 0 20px rgba( 37, 99, 235, 0.75 );
    transition: transform var( --transition ), box-shadow var( --transition ), background var( --transition );
}

.btn-primary:hover {
    transform: translateY( -2px ) scale( 1.02 );
    background: linear-gradient( 135deg, #3b82f6, #1d4ed8 );
    box-shadow: 0 0 26px rgba( 59, 130, 246, 0.95 );
}

.btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 22px;
    border-radius: 999px;
    border: 1px solid rgba( 148, 163, 184, 0.6 );
    background: rgba( 15, 23, 42, 0.65 );
    color: #e5e7eb;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    transition: background var( --transition ), transform var( --transition ), border-color var( --transition );
}

.btn-ghost:hover {
    background: rgba( 30, 64, 175, 0.4 );
    border-color: rgba( 129, 140, 248, 0.9 );
    transform: translateY( -1px );
}
/* RESPONSIVE */
@media ( max-width: 920px ) {
    .hero-grid, .how-grid {
        grid-template-columns: minmax( 0, 1fr );
    }

    .hero {
        padding-top: 40px;
    }

    .hero-right {
        order: -1;
    }
}

@media ( max-width: 780px ) {
    .benefits-grid, .features-grid, .who-grid {
        grid-template-columns: repeat( 2, minmax( 0, 1fr ) );
    }
}

@media ( max-width: 640px ) {
    .nav-links {
        display: none;
    }
    .benefits-grid, .features-grid, .who-grid {
        grid-template-columns: minmax( 0, 1fr );
    }
    section {
        padding: 60px 18px;
    }
    .hero-title {
        font-size: 28px;
    }
}

@media ( max-width: 780px ) {
    .benefits-grid,
    .features-grid,
    .who-grid {
        grid-template-columns: repeat( 2, minmax( 0, 1fr ) );
    }
}

@media ( max-width: 640px ) {
    .nav-links {
        display: none;
    }

    .benefits-grid,
    .features-grid,
    .who-grid {
        grid-template-columns: minmax( 0, 1fr );
    }

    section {
        padding: 60px 18px;
    }

    .hero-title {
        font-size: 28px;
    }
}

</style>
</head>
<body>
<div class = 'page-wrapper'>
<div class = 'page-overlay'></div>

<!-- NAVBAR -->
<header>
<div class = 'nav'>
<div class = 'logo'>
<img src = 'RewardLogo.png' alt = 'Student Point Reward System Logo'
style = 'width: 40px; height: 40px; border-radius: 8px; box-shadow: 0 0 16px rgba(59, 130, 246, 0.8);'>
<div>
SPRS
<div style = 'font-size: 11px; color:#9ca3af; font-weight:400;'>
Student Point-Reward System
</div>
</div>
</div>
<nav class = 'nav-links'>
<a href = '#overview'>Overview</a>
<a href = '#benefits'>Why SPRS?</a>
<a href = '#how'>How It Works</a>
<a href = '#features'>Core Features</a>
<a href = '#who'>For Schools</a>
</nav>
<a href = 'login.php' class = 'nav-cta'>
<span class = 'icon'>▶</span>
<span>Open System</span>
</a>
</div>
</header>

<main>
<!-- HERO -->
<section class = 'hero' id = 'overview'>
<div class = 'container hero-grid'>
<!-- HERO LEFT -->
<div>
<div class = 'hero-badges'>
<div class = 'hero-badge'>Digital Reward Platform</div>
<div class = 'hero-badge'>QR · SMS · Data Visualization</div>
</div>
<h1 class = 'hero-title'>
Student Point-Reward System with Integrated QR Code Verification, SMS Authentication, and Data Visualization
</h1>
<p class = 'hero-sub'>
SPRS is a centralized web platform where schools can track student performance,
verify event participation with QR codes, send SMS-based authentication, and
visualize engagement data  — replacing scattered Google Forms and manual logs.
</p>
<div class = 'hero-cta-group'>
<a href = 'login.php' class = 'btn-primary'>
<span>Launch SPRS</span>
<span>→</span>
</a>
<a href = '#benefits' class = 'btn-ghost'>See how it improves your reward system</a>
</div>
<div class = 'hero-footnote'>
<span><span class = 'dot'></span> Fair, consistent, and transparent student rewards</span>
<span>• Supports academic performance, participation & positive behavior</span>
</div>
</div>

<!-- HERO RIGHT – EXTRA CARDS -->
<div class = 'hero-right'>

<!-- Recent Events -->
<div class = 'hero-card'>
<div class = 'hero-card-header'>
<div class = 'hero-card-title'>Recent Events inside SPRS</div>
</div>
<div class = 'scroll-list'>
<?php while( $e = $recentEvents->fetch_assoc() ): ?>
<div class = 'list-row' style = 'align-items:center; gap:8px;'>
<?php if ( !empty( $e[ 'eventImage' ] ) && file_exists( 'admin/eventImage/'.$e[ 'eventImage' ] ) ): ?>
<img src = "<?= 'admin/eventImage/' . htmlspecialchars($e['eventImage']) ?>"
alt = "<?= htmlspecialchars($e['eventName']) ?>"
style = 'width:40px; height:40px; border-radius:8px; object-fit:cover;'>
<?php else: ?>
<div style = 'width:40px; height:40px; border-radius:8px; background:#1f2937; display:flex;align-items:center;justify-content:center;color:#9ca3af;font-size:10px;'>
No Img
</div>
<?php endif;
?>
<span>
<span class = 'list-label'>< ?= htmlspecialchars( $e[ 'eventName' ] ) ?></span>
· +< ?= intval( $e[ 'eventRewards' ] ) ?> pts
</span>
<span>< ?= date( 'M d', strtotime( $e[ 'eventDate' ] ) ) ?></span>
</div>
<?php endwhile;
?>
</div>
</div>

<!-- Upcoming Events -->
<div class = 'hero-card'>
<div class = 'hero-card-header'>
<div class = 'hero-card-title'>Upcoming Events with Points</div>
<span class = 'badge-soft'>Auto-logged via QR</span>
</div>
<div class = 'scroll-list'>
<?php while( $u = $upcomingEvents->fetch_assoc() ): ?>
<div class = 'list-row' style = 'align-items:center; gap:8px;'>
<?php if ( !empty( $u[ 'eventImage' ] ) && file_exists( 'admin/eventImage/'.$u[ 'eventImage' ] ) ): ?>
<img src = "<?= 'admin/eventImage/' . htmlspecialchars($u['eventImage']) ?>"
alt = "<?= htmlspecialchars($u['eventName']) ?>"
style = 'width:40px; height:40px; border-radius:8px; object-fit:cover;'>
<?php else: ?>
<div style = 'width:40px; height:40px; border-radius:8px; background:#1f2937; display:flex;align-items:center;justify-content:center;color:#9ca3af;font-size:10px;'>
No Img
</div>
<?php endif;
?>
<span>
<span class = 'list-label'>< ?= htmlspecialchars( $u[ 'eventName' ] ) ?></span>
· +< ?= intval( $u[ 'eventRewards' ] ) ?> pts
</span>
<span>< ?= date( 'M d', strtotime( $u[ 'eventDate' ] ) ) ?></span>
</div>
<?php endwhile;
?>
</div>
</div>

<!-- Top Performing Students -->
<div class = 'hero-card'>
<div class = 'hero-card-header'>
<div class = 'hero-card-title'>Top performing students</div>
<span class = 'pill-soft'>Current ranking</span>
</div>
<div class = 'scroll-list'>
<?php $rank = 1;
while( $s = $topStudents->fetch_assoc() ): ?>
<div class = 'list-row'>
<span>
#< ?= $rank ?> <span class = 'list-label'>< ?= htmlspecialchars( $s[ 'name' ] ) ?></span>
<span class = 'dep-tag'>( < ?= htmlspecialchars( $s[ 'department' ] ) ?> )</span>
</span>
<span>< ?= number_format( $s[ 'points' ] ) ?> pts</span>
</div>
<?php $rank++;
endwhile;
?>
</div>
</div>

</div>

</section>

<!-- WHY SPRS -->
<section id = 'benefits'>
<div class = 'container'>
<h2 class = 'section-title'>Why use SPRS in your university or school?</h2>
<p class = 'section-subtitle'>
Traditional Google Forms and manual logs make it difficult to track rewards fairly and consistently.
SPRS turns your reward program into a clear, structured system that recognizes students for academics,
participation, and positive behavior — while giving admins clean data and automated verification.
</p>

<div class = 'benefits-grid'>
<div class = 'benefit-card'>
<div class = 'benefit-label'>Benefit #1</div>
<div class = 'benefit-title'>Organized and centralized reward tracking</div>
<p class = 'benefit-text'>
All points, events, and redemptions live in one platform. Staff can award points, students can view balances,
and admins can review logs without sorting through multiple spreadsheets or forms.
</p>
<div class = 'benefit-tag'>Replaces scattered Google Forms and paper records</div>
</div>

<div class = 'benefit-card'>
<div class = 'benefit-label'>Benefit #2</div>
<div class = 'benefit-title'>Fair and transparent points system</div>
<p class = 'benefit-text'>
QR code verification ensures that attendance and participation are tied to real students, while
point rules are clearly defined. This reduces bias and builds trust in the reward system.
</p>
<div class = 'benefit-tag'>Students see exactly how and why they earn points</div>
</div>

<div class = 'benefit-card'>
<div class = 'benefit-label'>Benefit #3</div>
<div class = 'benefit-title'>Stronger motivation and engagement</div>
<p class = 'benefit-text'>
SPs become digital “tokens” that students can redeem for certificates, vouchers, or privileges.
This gamified structure encourages better attendance, active participation, and positive behavior.
</p>
<div class = 'benefit-tag'>Supports academic and holistic student growth</div>
</div>

<div class = 'benefit-card'>
<div class = 'benefit-label'>Benefit #4</div>
<div class = 'benefit-title'>Less manual checking for teachers and staff</div>
<p class = 'benefit-text'>
With QR scanning and automated logs, staff don’t have to manually cross-check attendance
sheets or encode points one by one. Everyday tasks become faster and more accurate.
</p>
<div class = 'benefit-tag'>Reduces admin workload and human error</div>
</div>

<div class = 'benefit-card'>
<div class = 'benefit-label'>Benefit #5</div>
<div class = 'benefit-title'>Data-driven decision-making</div>
<p class = 'benefit-text'>
Built-in data visualization lets admins see which events work, who is highly engaged,
and where participation is low. These insights help improve programs and policies.
</p>
<div class = 'benefit-tag'>Charts, summaries, and reports in one view</div>
</div>

<div class = 'benefit-card'>
<div class = 'benefit-label'>Benefit #6</div>
<div class = 'benefit-title'>Secure and practical implementation</div>
<p class = 'benefit-text'>
SPRS uses a dummy portal for development and testing, avoiding restrictions of the real institutional database
while still simulating how integration would work in an actual university setup.
</p>
<div class = 'benefit-tag'>Safe for prototyping and future expansion</div>
</div>
</div>
</div>
</section>

<!-- HOW IT WORKS -->
<section id = 'how'>
<div class = 'container'>
<h2 class = 'section-title'>How the system flows from login to rewards</h2>
<p class = 'section-subtitle'>
SPRS connects student accounts, points, rewards, and reports into one structured flow — supported by QR code
verification, SMS authentication, and data visualization. Internally, the system was built using the Agile Scrum
framework to keep modules lightweight, testable, and easy to improve.
</p>

<div class = 'how-grid'>
<div>
<div class = 'modules-card'>
<div class = 'modules-heading'>Core modules working together</div>
<div class = 'modules-tags'>
<span class = 'modules-pill'>Student Accounts & Authentication</span>
<span class = 'modules-pill'>Point Management</span>
<span class = 'modules-pill'>Reward Redemption</span>
<span class = 'modules-pill'>Reports & Monitoring</span>
</div>
<ul class = 'modules-list'>
<li>
<span class = 'modules-bullet'>●</span>
<span>
<strong>Student Accounts & Authentication</strong> – manages logins and student profiles,
including OTP-based account recovery using SMS integration.
</span>
</li>
<li>
<span class = 'modules-bullet'>●</span>
<span>
<strong>Point Management</strong> – staff can award points for academics, attendance, and
behavior;
balances update in real time.
</span>
</li>
<li>
<span class = 'modules-bullet'>●</span>
<span>
<strong>Reward Redemption</strong> – students redeem points for items like certificates or vouchers,
while the system deducts points and records the transaction.
</span>
</li>
<li>
<span class = 'modules-bullet'>●</span>
<span>
<strong>Reports & Monitoring</strong> – logs, summaries, and charts show participation trends,
top performers, and redeemed rewards.
</span>
</li>
</ul>
</div>
</div>

<div>
<div class = 'steps'>
<div class = 'step-item'>
<div class = 'step-title'>1. Built with Agile Scrum</div>
Each sprint focused on a key module ( login, points, rewards, reporting ), allowing continuous testing,
feedback, and refinement instead of building everything at once.
</div>
<div class = 'step-item'>
<div class = 'step-title'>2. Student journey</div>
Students sign in, scan QR codes during events, accumulate points, and redeem rewards — all while
receiving confirmations via the system and SMS.
</div>
<div class = 'step-item'>
<div class = 'step-title'>3. Staff and admin journey</div>
Staff create events, manage reward catalogs, issue points, and validate QR scans. Admins view
dashboards and charts to see which programs drive engagement.
</div>
<div class = 'step-item'>
<div class = 'step-title'>4. Future-ready integration</div>
The current version uses a dummy student portal and simulated services, but the architecture
is designed so it can plug into real institutional databases and gateways later on.
</div>
</div>
</div>
</div>
</div>
</section>

<!-- FEATURES -->
<section id = 'features'>
<div class = 'container'>
<h2 class = 'section-title'>Core features that make SPRS more than just a point system</h2>
<p class = 'section-subtitle'>
The system doesn’t just record points. It verifies attendance through QR codes, secures accounts with SMS-based OTP,
and converts raw records into visual insights that administrators can use immediately.
</p>

<div class = 'features-grid'>
<div class = 'feature-card'>
<div class = 'feature-chip'>QR Code Verification</div>
<div class = 'feature-title'>Scan once, log automatically</div>
<p class = 'feature-text'>
Each event generates a unique QR code. Students simply scan to confirm their presence, and the system records
their participation without manual encoding — reducing ghost attendance and lost records.
</p>
<div class = 'feature-meta'>Backed by QR Client-Server and integration diagrams</div>
</div>

<div class = 'feature-card'>
<div class = 'feature-chip'>SMS Authentication</div>
<div class = 'feature-title'>Secure access with OTP</div>
<p class = 'feature-text'>
For sensitive actions like account recovery, SPRS sends a One-Time Password via SMS. This adds an extra layer of
security to student accounts and helps verify that the right person is using the system.
</p>
<div class = 'feature-meta'>Powered by SMS client-server flow and OTP validation</div>
</div>

<div class = 'feature-card'>
<div class = 'feature-chip'>Data Visualization</div>
<div class = 'feature-title'>Make decisions from real data</div>
<p class = 'feature-text'>
Charts and tables summarize event participation, points earned, and rewards claimed across time.
Admins can quickly see which activities are effective and which groups of students need more support.
</p>
<div class = 'feature-meta'>Uses DV server integration & visualization diagrams</div>
</div>
</div>
</div>
</section>

<!-- WHO -->
<section id = 'who'>
<div class = 'container'>
<h2 class = 'section-title'>Designed for schools that want a modern, fair reward system</h2>
<p class = 'section-subtitle'>
SPRS aligns with research on motivation and reward systems: students respond better when recognition is clear,
consistent, and visible. The platform helps institutions turn those insights into a concrete, working system.
</p>

<div class = 'who-grid'>
<div class = 'who-card'>
<div class = 'who-title'>For universities & colleges</div>
<ul class = 'who-list'>
<li>Implement institution-wide rewards that cut across departments and organizations.</li>
<li>Monitor engagement in extension programs, seminars, and co-curricular activities.</li>
<li>Use data to justify policies and recognize high-performing students or programs.</li>
</ul>
</div>

<div class = 'who-card'>
<div class = 'who-title'>For basic education schools</div>
<ul class = 'who-list'>
<li>Encourage attendance, good behavior, and academic effort using simple digital rewards.</li>
<li>Replace manual token systems or sticker charts with QR-based validation.</li>
<li>Provide parents and teachers with a clear picture of student participation.</li>
</ul>
</div>

<div class = 'who-card'>
<div class = 'who-title'>For student affairs & organizations</div>
<ul class = 'who-list'>
<li>Standardize how events award points and prevent duplicate or unfair credit.</li>
<li>Highlight students who are most active in organizations and leadership roles.</li>
<li>Promote a culture of recognition through consistent, system-backed rewards.</li>
</ul>
</div>
</div>
</div>
</section>

<!-- CTA -->
<section class = 'cta'>
<div class = 'container'>
<div class = 'cta-box'>
<div class = 'cta-title'>Ready to see SPRS in action?</div>
<div class = 'cta-sub'>
Launch the system interface to explore the student dashboard, staff tools, and data visualization modules.
No extra setup needed — just sign in and start awarding points.
</div>

<a href = 'login.php' class = 'btn-primary'>
<span>Open Student Point-Reward System</span>
<span>↗</span>
</a>

<div class = 'cta-stats'>
<div class = 'cta-stat-item'>✔ Replaces manual reward tracking</div>
<div class = 'cta-stat-item'>✔ Built with Agile Scrum methodology</div>
<div class = 'cta-stat-item'>✔ Uses QR, SMS, and data visualization</div>
</div>
</div>
</div>
</section>
</main>

<footer>
© <span>2025</span> Student Point-Reward System ( SPRS ) • Built for smarter, fairer student rewards.
</footer>
</div>
</body>
</html>