@php
    $currentPage = $page ?? 'home';
    $isHome = $currentPage === 'home';
    $isPage = fn (string $pageName): bool => $currentPage === $pageName;
    $isStandaloneDomain = request()->getHost() === config('medisana.domain');
    $medisanaRoute = $isStandaloneDomain ? 'medisana.standalone' : 'medisana.research-center';
    $medisanaUrl = fn (?string $pageName = null, ?string $fragment = null): string => route($medisanaRoute, $pageName ? ['page' => $pageName] : []) . ($fragment ? "#{$fragment}" : '');
    $synnexusPortalUrl = config('medisana.portal_url');
    $pageHeaders = [
        'patients' => [
            'eyebrow' => 'Patients and families',
            'title' => 'Understand research before sharing medical details.',
            'copy' => 'Plain-language study education, privacy guidance, visit expectations, support options, and a general information request pathway.',
        ],
        'sponsors' => [
            'eyebrow' => 'Sponsors and CROs',
            'title' => 'Enrollment, engagement, retention, startup, and execution in one clinic-embedded model.',
            'copy' => 'A focused view of sponsor fit, startup responsiveness, approved study execution, and secure workflow visibility.',
        ],
        'capabilities' => [
            'eyebrow' => 'Site capabilities',
            'title' => 'Clinic access, investigators, CNS support, diagnostics, and operational backbone.',
            'copy' => 'A focused view of the clinical network, investigator leadership, CNS capability, diagnostics, patient support, and operational backbone.',
        ],
        'readiness' => [
            'eyebrow' => 'Sponsor/CRO readiness',
            'title' => 'Compliance alignment for responsible research operations.',
            'copy' => 'A readiness view centered on FDA-regulated conduct, IRB oversight, GCP, HIPAA safeguards, source documentation, and inspection-ready records.',
        ],
        'careers' => [
            'eyebrow' => 'Careers',
            'title' => 'Build the research team before studies activate.',
            'copy' => 'Collect general professional interest for coordination, CNS rater, regulatory, and recruitment support roles.',
        ],
        'contact' => [
            'eyebrow' => 'Contact',
            'title' => 'Connect with Medisana Research Center.',
            'copy' => 'General research inquiries, sponsor/CRO conversations, and clinic-to-research coordination across the Medisana network.',
        ],
    ];
    $complianceBadges = [
        [
            'icon' => 'fda.svg',
            'alt' => 'FDA icon',
            'title' => 'FDA / 21 CFR',
            'text' => 'Regulated clinical trial conduct and inspection-ready records',
        ],
        [
            'icon' => 'ich-logo.png',
            'alt' => 'ICH logo',
            'title' => 'ICH Good Clinical Practice',
            'text' => 'Training, conduct, delegation, and documentation standards',
        ],
        [
            'icon' => 'hipaa.svg',
            'alt' => 'HIPAA icon',
            'title' => 'HIPAA Safeguards',
            'text' => 'Privacy, access control, and protected information handling',
        ],
        [
            'icon' => 'nih.svg',
            'alt' => 'NIH icon',
            'title' => 'NIH Research Guidance',
            'text' => 'Participant education and human-research awareness',
        ],
        [
            'icon' => 'ori-research-integrity.jpeg',
            'alt' => 'Office of Research Integrity logo',
            'title' => 'ORI Research Integrity',
            'text' => 'Responsible conduct, integrity awareness, and research accountability',
        ],
        [
            'icon' => 'gdpr.svg',
            'alt' => 'GDPR icon',
            'title' => 'GDPR Awareness',
            'text' => 'International privacy considerations when applicable',
        ],
        [
            'icon' => 'ccpa.svg',
            'alt' => 'CCPA icon',
            'title' => 'CCPA / Privacy Rights',
            'text' => 'Consumer privacy-rights awareness when applicable',
        ],
        [
            'icon' => 'pci.svg',
            'alt' => 'PCI icon',
            'title' => 'PCI Control Awareness',
            'text' => 'Payment-data controls when payment workflows apply',
        ],
        [
            'icon' => 'aicpa.svg',
            'alt' => 'AICPA icon',
            'title' => 'AICPA / SOC Controls',
            'text' => 'Operational control discipline for vendor and platform review',
        ],
    ];
    $brandVersion = '20260917-5';
    $brandKit = fn (string $file): string => asset("images/medisana/brand-kit/{$file}") . "?v={$brandVersion}";
    $activeHeader = $pageHeaders[$currentPage] ?? null;
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Medisana Research Center is the clinical research arm of Medisana Health Center, supporting feasibility, startup, activation, and approved study execution through a multi-location private and affiliated research center model.">
    <title>Medisana Research Center</title>
    <link rel="icon" href="{{ $brandKit('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $brandKit('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ $brandKit('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ $brandKit('site.webmanifest') }}">
    <meta name="theme-color" content="#071f4a">
    <style>
        :root {
            --ink: #071f4a;
            --deep: #0b2e62;
            --teal: #123b73;
            --teal-dark: #061838;
            --blue: #1b4e8f;
            --gold: #b60819;
            --mint: #edf3fb;
            --canvas: #f8fafc;
            --surface: #ffffff;
            --line: #d7deea;
            --muted: #5e6673;
            --text: #162033;
            --champagne: #fff1f2;
            --red: #b60819;
            --red-dark: #870713;
            --silver: #e7edf5;
            --glass: rgba(255, 255, 255, 0.12);
            --veil: rgba(255, 255, 255, 0.72);
            --shadow: rgba(7, 31, 74, 0.16);
            --premium-shadow: 0 24px 70px rgba(7, 31, 74, 0.18);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background:
                linear-gradient(125deg, rgba(237, 243, 251, 0.78) 0%, transparent 34%),
                linear-gradient(180deg, #ffffff 0%, #f5f7fb 48%, #eef3fa 100%);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.5;
            margin: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
        }

        .topbar {
            align-items: center;
            backdrop-filter: blur(22px);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.94));
            border: 0;
            border-bottom: 1px solid rgba(7, 31, 74, 0.1);
            border-radius: 0;
            box-shadow: 0 14px 42px rgba(7, 31, 74, 0.1);
            color: var(--ink);
            display: flex;
            gap: 28px;
            justify-content: space-between;
            left: 0;
            margin: 0;
            max-width: none;
            padding: 13px max(24px, calc((100vw - 1180px) / 2 + 24px));
            position: sticky;
            right: 0;
            top: 0;
            width: 100%;
            z-index: 30;
        }

        .topbar::after {
            background: linear-gradient(90deg, transparent 0%, rgba(7, 31, 74, 0.22) 18%, rgba(182, 8, 25, 0.72) 50%, rgba(7, 31, 74, 0.22) 82%, transparent 100%);
            bottom: 0;
            content: "";
            height: 1px;
            left: max(24px, calc((100vw - 1180px) / 2 + 24px));
            position: absolute;
            right: max(24px, calc((100vw - 1180px) / 2 + 24px));
        }

        .brand {
            align-items: center;
            display: inline-flex;
            gap: 13px;
            max-width: none;
            text-decoration: none;
            transition: transform 0.25s ease;
            white-space: nowrap;
        }

        .brand:hover {
            transform: translateY(-1px);
        }

        .brand-logo {
            background: #fff;
            border: 1px solid rgba(7, 31, 74, 0.08);
            border-radius: 50%;
            display: block;
            filter: drop-shadow(0 10px 18px rgba(7, 31, 74, 0.14));
            height: 54px;
            max-width: 54px;
            object-fit: contain;
            object-position: left center;
            padding: 5px;
            transition: filter 0.25s ease, transform 0.25s ease;
            width: 54px;
        }

        .brand:hover .brand-logo {
            filter: drop-shadow(0 14px 28px rgba(7, 31, 74, 0.22));
            transform: scale(1.012);
        }

        .brand-mark {
            align-items: center;
            background: linear-gradient(135deg, var(--teal), var(--red));
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 8px;
            color: #fff;
            display: inline-flex;
            font-size: 15px;
            font-weight: 800;
            height: 42px;
            justify-content: center;
            width: 50px;
        }

        .brand-wordmark {
            align-items: baseline;
            color: var(--ink);
            display: inline-flex;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 19px;
            font-weight: 700;
            gap: 9px;
            line-height: 1;
        }

        .brand-wordmark small {
            color: var(--red);
            font: inherit;
        }

        .brand-rule {
            background: rgba(182, 8, 25, 0.74);
            display: inline-block;
            height: 24px;
            transform: translateY(4px);
            width: 1px;
        }

        .nav {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
        }

        .nav a,
        .nav summary {
            border: 1px solid transparent;
            border-radius: 999px;
            color: rgba(7, 31, 74, 0.68);
            cursor: pointer;
            display: block;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0;
            padding: 8px 9px;
            position: relative;
            text-decoration: none;
            text-transform: uppercase;
            transition: color 0.2s ease;
        }

        .nav a::after,
        .nav summary::after {
            background: linear-gradient(90deg, transparent, var(--red), transparent);
            bottom: 0;
            content: "";
            height: 1px;
            left: 0;
            opacity: 0;
            position: absolute;
            transform: scaleX(0.4);
            transform-origin: center;
            transition: opacity 0.25s ease, transform 0.25s ease;
            width: 100%;
        }

        .nav a:hover,
        .nav summary:hover,
        .nav a.active,
        .nav details[open] summary {
            background: rgba(7, 31, 74, 0.06);
            border-color: rgba(7, 31, 74, 0.1);
            color: var(--ink);
        }

        .nav a:hover::after,
        .nav summary:hover::after,
        .nav a.active::after,
        .nav details[open] summary::after {
            opacity: 1;
            transform: scaleX(1);
        }

        .nav details {
            position: relative;
        }

        .nav summary {
            list-style: none;
        }

        .nav summary::-webkit-details-marker {
            display: none;
        }

        .nav-dropdown {
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid rgba(7, 31, 74, 0.12);
            border-radius: 12px;
            box-shadow: 0 24px 60px rgba(7, 31, 74, 0.18);
            display: grid;
            gap: 4px;
            min-width: 220px;
            padding: 10px;
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            z-index: 40;
        }

        .nav-dropdown a {
            border-radius: 8px;
            font-size: 13px;
            text-transform: none;
            white-space: nowrap;
        }

        .nav-dropdown a::after {
            display: none;
        }

        .button {
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 8px;
            display: inline-flex;
            font-weight: 800;
            isolation: isolate;
            justify-content: center;
            min-height: 44px;
            overflow: hidden;
            padding: 11px 17px;
            position: relative;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .button::after {
            background: linear-gradient(110deg, transparent 0%, rgba(255, 255, 255, 0.42) 42%, transparent 70%);
            content: "";
            height: 160%;
            left: -85%;
            opacity: 0;
            position: absolute;
            top: -30%;
            transform: rotate(8deg);
            transition: left 0.55s ease, opacity 0.35s ease;
            width: 62%;
            z-index: -1;
        }

        .button:hover {
            box-shadow: 0 18px 42px var(--shadow);
            transform: translateY(-2px);
        }

        .button:hover::after {
            left: 122%;
            opacity: 1;
        }

        .button.primary {
            background: linear-gradient(135deg, var(--red), #d7192a);
            border-color: rgba(255, 241, 242, 0.28);
            color: #fff;
            box-shadow: 0 14px 34px rgba(182, 8, 25, 0.28);
        }

        .button.secondary {
            background: var(--glass);
            color: #fff;
        }

        .button.dark {
            background: var(--ink);
            border-color: var(--ink);
            color: #fff;
        }

        .hero {
            background:
                linear-gradient(104deg, rgba(7, 31, 74, 0.98) 0%, rgba(7, 31, 74, 0.86) 44%, rgba(182, 8, 25, 0.16) 100%),
                linear-gradient(180deg, rgba(182, 8, 25, 0.12), transparent 48%),
                url("{{ asset('images/medisana/research-center-hero.png') }}") center / cover no-repeat;
            clip-path: polygon(0 0, 100% 0, 100% 92%, 58% 100%, 0 91%);
            color: #fff;
            min-height: calc(100vh - 82px);
            padding: 118px 38px 122px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            animation: heroSheen 9s ease-in-out infinite;
            background: linear-gradient(115deg, transparent 0%, rgba(255, 241, 242, 0.16) 46%, transparent 58%);
            content: "";
            height: 140%;
            left: -72%;
            pointer-events: none;
            position: absolute;
            top: -20%;
            transform: rotate(2deg);
            width: 58%;
        }

        .hero::after {
            background:
                linear-gradient(180deg, transparent 0%, rgba(7, 31, 74, 0.62) 100%),
                repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.045) 0 1px, transparent 1px 124px),
                repeating-linear-gradient(0deg, rgba(255, 255, 255, 0.035) 0 1px, transparent 1px 124px);
            bottom: 0;
            content: "";
            height: 58%;
            left: 0;
            pointer-events: none;
            position: absolute;
            right: 0;
        }

        .page-hero {
            background:
                linear-gradient(106deg, rgba(7, 31, 74, 0.98), rgba(11, 46, 98, 0.92), rgba(182, 8, 25, 0.24)),
                url("{{ asset('images/medisana/research-center-hero.png') }}") center / cover no-repeat;
            color: #fff;
            padding: 104px 38px 72px;
            position: relative;
            overflow: hidden;
        }

        .page-hero::after {
            background: linear-gradient(90deg, transparent, rgba(182, 8, 25, 0.72), transparent);
            bottom: 0;
            content: "";
            height: 1px;
            left: 38px;
            position: absolute;
            right: 38px;
        }

        .page-hero-grid {
            align-items: end;
            display: grid;
            gap: 42px;
            grid-template-columns: minmax(0, 0.85fr) minmax(300px, 0.42fr);
            position: relative;
            z-index: 1;
        }

        .page-hero h1 {
            font-size: clamp(36px, 5vw, 58px);
            line-height: 1.02;
            margin: 0;
            max-width: 840px;
        }

        .page-hero p {
            color: rgba(255, 255, 255, 0.78);
            font-size: 18px;
            margin: 18px 0 0;
            max-width: 760px;
        }

        .page-jump {
            align-items: stretch;
            display: grid;
            gap: 10px;
        }

        .page-jump a {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: rgba(255, 255, 255, 0.82);
            font-size: 13px;
            font-weight: 800;
            padding: 12px 14px;
            text-decoration: none;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .page-jump a:hover,
        .page-jump a.active {
            background: rgba(182, 8, 25, 0.24);
            color: #fff;
            transform: translateX(3px);
        }

        .wrap {
            margin: 0 auto;
            max-width: 1180px;
        }

        .hero-content {
            max-width: none;
            position: relative;
            z-index: 1;
        }

        .hero-stage {
            align-items: end;
            display: grid;
            gap: 46px;
            grid-template-columns: minmax(0, 0.86fr);
            min-height: 58vh;
            position: relative;
            z-index: 2;
        }

        .eyebrow {
            color: var(--gold);
            font-size: 13px;
            font-weight: 800;
            margin: 0 0 14px;
            text-transform: uppercase;
        }

        .hero .eyebrow {
            color: var(--champagne);
            position: relative;
        }

        .hero .eyebrow::before {
            background: var(--gold);
            content: "";
            display: inline-block;
            height: 1px;
            margin-right: 12px;
            vertical-align: middle;
            width: 42px;
        }

        h1 {
            font-size: clamp(42px, 7vw, 76px);
            line-height: 1;
            margin: 0;
            max-width: 780px;
        }

        .hero-title {
            align-items: baseline;
            display: flex;
            flex-wrap: nowrap;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(42px, 4.8vw, 72px);
            gap: clamp(10px, 1.4vw, 18px);
            letter-spacing: 0;
            line-height: 0.96;
            max-width: none;
            position: relative;
            text-shadow: 0 18px 48px rgba(0, 0, 0, 0.38);
            white-space: nowrap;
        }

        .hero-title-brand {
            color: #fff;
            font-weight: 700;
        }

        .hero-title-rule {
            background: linear-gradient(180deg, transparent, rgba(255, 241, 242, 0.62), var(--red), rgba(255, 241, 242, 0.62), transparent);
            display: inline-block;
            flex: 0 0 auto;
            height: clamp(36px, 4.8vw, 66px);
            transform: translateY(8px);
            width: 2px;
        }

        .hero-title-accent {
            color: #fff1f2;
            font-weight: 600;
        }

        .hero-title::after {
            background: linear-gradient(90deg, rgba(182, 8, 25, 0), rgba(255, 241, 242, 0.58), rgba(182, 8, 25, 0.86), rgba(255, 241, 242, 0.32), rgba(182, 8, 25, 0));
            bottom: -16px;
            content: "";
            height: 1px;
            left: 0;
            position: absolute;
            width: min(620px, 92%);
        }

        .hero p {
            color: rgba(255, 255, 255, 0.82);
            font-size: 19px;
            max-width: 650px;
        }

        .proof-strip {
            background: transparent;
            margin-top: -78px;
            padding: 0 38px;
            position: relative;
            z-index: 4;
        }

        .proof-grid {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.84);
            border: 1px solid rgba(6, 23, 34, 0.08);
            box-shadow: var(--premium-shadow);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }

        .proof-item {
            background: transparent;
            border-right: 1px solid rgba(6, 23, 34, 0.08);
            padding: 24px 22px;
            transition: background 0.25s ease, transform 0.25s ease;
        }

        .proof-item:last-child {
            border-right: 0;
        }

        .proof-item:hover {
            background: #fbfcfd;
            transform: translateY(-3px);
        }

        .proof-item strong {
            color: var(--ink);
            display: block;
            font-size: 15px;
        }

        .proof-item span {
            color: var(--muted);
            display: block;
            font-size: 14px;
            margin-top: 4px;
        }

        .band {
            padding: 82px 38px;
            position: relative;
            scroll-margin-top: 84px;
        }

        .band::before {
            background: linear-gradient(90deg, transparent, rgba(182, 8, 25, 0.32), transparent);
            content: "";
            height: 1px;
            left: 38px;
            opacity: 0.72;
            position: absolute;
            right: 38px;
            top: 0;
        }

        .band.white {
            background:
                linear-gradient(112deg, rgba(255, 255, 255, 0.98), rgba(243, 248, 247, 0.96));
        }

        .band.dark {
            background: var(--ink);
            color: #fff;
        }

        .section-head {
            align-items: end;
            display: grid;
            gap: 48px;
            grid-template-columns: minmax(0, 0.94fr) minmax(300px, 0.64fr);
            margin-bottom: 38px;
        }

        h2 {
            font-size: clamp(30px, 4vw, 44px);
            line-height: 1.1;
            margin: 0;
        }

        .section-head p,
        .lead,
        .card p,
        .card li,
        .timeline li {
            color: var(--muted);
        }

        .dark .section-head p,
        .dark .lead,
        .dark .card p,
        .dark .card li {
            color: rgba(255, 255, 255, 0.72);
        }

        .grid {
            display: grid;
            gap: 16px;
        }

        .grid.three {
            grid-template-columns: repeat(3, 1fr);
        }

        .grid.four {
            grid-template-columns: repeat(4, 1fr);
        }

        .grid.two {
            grid-template-columns: repeat(2, 1fr);
        }

        .audience-router {
            align-items: stretch;
            display: grid;
            gap: 18px;
            grid-template-columns: minmax(280px, 0.74fr) minmax(0, 1.26fr);
        }

        .audience-intro {
            background:
                linear-gradient(145deg, rgba(182, 8, 25, 0.16), transparent 56%),
                var(--ink);
            border: 1px solid rgba(182, 8, 25, 0.26);
            box-shadow: var(--premium-shadow);
            clip-path: polygon(0 0, calc(100% - 26px) 0, 100% 26px, 100% 100%, 0 100%);
            color: #fff;
            padding: 34px;
        }

        .audience-intro h2 {
            color: #fff;
            margin-bottom: 16px;
        }

        .audience-intro p {
            color: rgba(255, 255, 255, 0.76);
            margin: 0;
        }

        .audience-cards {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .audience-card {
            background: var(--surface);
            border: 1px solid rgba(6, 23, 34, 0.08);
            box-shadow: 0 18px 42px rgba(6, 23, 34, 0.06);
            display: grid;
            gap: 16px;
            padding: 28px;
        }

        .audience-card summary {
            cursor: pointer;
            display: grid;
            gap: 12px;
            list-style: none;
            padding-right: 42px;
            position: relative;
        }

        .audience-card summary::-webkit-details-marker {
            display: none;
        }

        .audience-card summary::after {
            align-items: center;
            background: var(--ink);
            border-radius: 50%;
            color: #fff;
            content: "+";
            display: inline-flex;
            font-size: 20px;
            font-weight: 400;
            height: 34px;
            justify-content: center;
            position: absolute;
            right: 0;
            top: 0;
            width: 34px;
        }

        .audience-card[open] summary::after {
            content: "-";
        }

        .audience-card-body {
            border-top: 1px solid rgba(6, 23, 34, 0.08);
            display: grid;
            gap: 16px;
            padding-top: 16px;
        }

        .audience-card.patient {
            border-top: 3px solid var(--teal);
        }

        .audience-card.sponsor {
            border-top: 3px solid var(--gold);
        }

        .audience-card.current {
            border-top: 3px solid var(--blue);
            grid-column: 1 / -1;
        }

        .audience-card span {
            color: var(--teal-dark);
            display: block;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .audience-card.sponsor span {
            color: var(--red-dark);
        }

        .audience-card.current span {
            color: var(--blue);
        }

        .section-visual {
            background: var(--surface);
            border: 1px solid rgba(6, 23, 34, 0.1);
            box-shadow: var(--premium-shadow);
            margin-top: 28px;
            overflow: hidden;
        }

        .section-visual img {
            display: block;
            height: auto;
            max-width: 100%;
            width: 100%;
        }

        .section-visual.banner img {
            aspect-ratio: 3 / 2;
            object-fit: cover;
            object-position: center;
        }

        .section-visual-link {
            color: inherit;
            display: block;
            text-decoration: none;
        }

        .section-visual-link:focus-visible {
            outline: 4px solid var(--gold);
            outline-offset: 4px;
        }

        .audience-card h3 {
            color: var(--ink);
            font-size: 27px;
            line-height: 1.05;
            margin: 0;
        }

        .audience-card p,
        .audience-card li {
            color: var(--muted);
        }

        .audience-card ul {
            margin: 0;
            padding-left: 20px;
        }

        .capability-system {
            display: grid;
            gap: 18px;
        }

        .site-capability-main {
            background:
                linear-gradient(118deg, rgba(7, 31, 74, 0.98), rgba(11, 46, 98, 0.94), rgba(182, 8, 25, 0.18));
            border: 1px solid rgba(182, 8, 25, 0.32);
            box-shadow: var(--premium-shadow);
            color: #fff;
            display: grid;
            gap: 22px;
            grid-template-columns: minmax(0, 1fr) minmax(280px, 0.52fr);
            padding: 34px;
        }

        .site-capability-main h3 {
            color: #fff;
            font-size: clamp(30px, 4vw, 48px);
            line-height: 1.02;
            margin: 0;
        }

        .site-capability-main p {
            color: rgba(255, 255, 255, 0.76);
            margin: 14px 0 0;
        }

        .capability-stats {
            display: grid;
            gap: 10px;
        }

        .capability-stat {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            padding: 16px;
        }

        .capability-stat strong {
            color: var(--champagne);
            display: block;
            font-size: 28px;
            line-height: 1;
        }

        .capability-stat span {
            color: rgba(255, 255, 255, 0.72);
            display: block;
            font-size: 12px;
            font-weight: 800;
            margin-top: 6px;
            text-transform: uppercase;
        }

        .capability-card-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .capability-card {
            background: var(--surface);
            border: 1px solid rgba(6, 23, 34, 0.08);
            box-shadow: 0 18px 42px rgba(6, 23, 34, 0.06);
            padding: 24px;
            transition: border-color 180ms ease, box-shadow 180ms ease, transform 180ms ease;
        }

        .capability-card[open] {
            border-color: rgba(182, 8, 25, 0.42);
            box-shadow: var(--premium-shadow);
            transform: translateY(-2px);
        }

        .capability-card summary {
            cursor: pointer;
            display: grid;
            gap: 8px;
            list-style: none;
            padding-right: 40px;
            position: relative;
        }

        .capability-card summary::-webkit-details-marker {
            display: none;
        }

        .capability-card summary::after {
            align-items: center;
            background: var(--ink);
            border-radius: 50%;
            color: #fff;
            content: "+";
            display: inline-flex;
            font-size: 20px;
            height: 32px;
            justify-content: center;
            position: absolute;
            right: 0;
            top: 0;
            width: 32px;
        }

        .capability-card[open] summary::after {
            background: var(--red);
            content: "-";
        }

        .capability-card[open] span {
            color: var(--red-dark);
        }

        .capability-card span {
            color: var(--teal-dark);
            display: block;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .capability-card h3 {
            color: var(--ink);
            font-size: 23px;
            line-height: 1.08;
            margin: 0;
        }

        .capability-card-body {
            border-top: 1px solid rgba(6, 23, 34, 0.08);
            margin-top: 16px;
            padding-top: 16px;
        }

        .capability-card-body p,
        .capability-card-body li {
            color: var(--muted);
        }

        .capability-card-body p {
            margin: 0 0 10px;
        }

        .capability-card-body ul {
            margin: 0;
            padding-left: 20px;
        }

        .current-studies {
            align-items: stretch;
            background:
                linear-gradient(135deg, rgba(7, 31, 74, 0.96), rgba(11, 46, 98, 0.92)),
                var(--ink);
            border: 1px solid rgba(182, 8, 25, 0.24);
            box-shadow: var(--premium-shadow);
            color: #fff;
            display: grid;
            gap: 24px;
            grid-template-columns: minmax(0, 0.78fr) minmax(320px, 0.72fr);
            margin-top: 28px;
            overflow: hidden;
            padding: 30px;
            position: relative;
        }

        .current-studies::after {
            background: linear-gradient(90deg, transparent, rgba(244, 216, 151, 0.5), transparent);
            bottom: 0;
            content: "";
            height: 1px;
            left: 30px;
            position: absolute;
            right: 30px;
        }

        .current-studies h3 {
            color: #fff;
            font-size: clamp(28px, 3vw, 42px);
            line-height: 1.05;
            margin: 0;
        }

        .current-studies p {
            color: rgba(255, 255, 255, 0.72);
            margin: 12px 0 0;
        }

        .study-listings {
            display: grid;
            gap: 12px;
        }

        .study-listing {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            padding: 20px;
        }

        .study-listing.muted {
            background: rgba(255, 255, 255, 0.045);
        }

        .study-listing span {
            color: var(--champagne);
            display: block;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .study-listing strong {
            color: #fff;
            display: block;
            font-size: 20px;
            line-height: 1.12;
        }

        .study-listing .button {
            margin-top: 16px;
        }

        .patient-experience {
            align-items: stretch;
            display: grid;
            gap: 20px;
            grid-template-columns: minmax(0, 0.96fr) minmax(320px, 0.72fr);
            margin-top: 30px;
        }

        .research-video {
            background: var(--ink);
            border: 1px solid rgba(182, 8, 25, 0.28);
            box-shadow: var(--premium-shadow);
            clip-path: polygon(0 0, calc(100% - 24px) 0, 100% 24px, 100% 100%, 0 100%);
            color: #fff;
            overflow: hidden;
        }

        .video-frame {
            aspect-ratio: 16 / 9;
            background:
                linear-gradient(135deg, rgba(18, 59, 115, 0.32), rgba(182, 8, 25, 0.2)),
                var(--deep);
            position: relative;
            width: 100%;
        }

        .video-frame iframe {
            border: 0;
            height: 100%;
            inset: 0;
            position: absolute;
            width: 100%;
        }

        .video-source {
            padding: 24px;
        }

        .video-source span {
            color: var(--champagne);
            display: block;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .video-source strong {
            display: block;
            font-size: 24px;
            line-height: 1.15;
        }

        .video-source p {
            color: rgba(255, 255, 255, 0.74);
            margin: 12px 0 18px;
        }

        .video-source a {
            color: var(--champagne);
            font-weight: 800;
            text-decoration-thickness: 1px;
            text-underline-offset: 4px;
        }

        .study-journey {
            background: rgba(255, 255, 255, 0.76);
            border: 1px solid rgba(18, 59, 115, 0.14);
            box-shadow: 0 18px 42px rgba(6, 23, 34, 0.08);
            padding: 28px;
        }

        .study-journey h3 {
            color: var(--ink);
            font-size: 30px;
            line-height: 1.05;
            margin: 0 0 20px;
        }

        .study-journey ol {
            counter-reset: studySteps;
            display: grid;
            gap: 12px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .study-journey li {
            align-items: start;
            border-top: 1px solid rgba(6, 23, 34, 0.08);
            counter-increment: studySteps;
            display: grid;
            gap: 14px;
            grid-template-columns: 34px 1fr;
            padding-top: 12px;
        }

        .study-journey li::before {
            align-items: center;
            background: linear-gradient(135deg, var(--teal), var(--blue));
            border-radius: 50%;
            color: #fff;
            content: counter(studySteps);
            display: inline-flex;
            font-size: 13px;
            font-weight: 800;
            height: 34px;
            justify-content: center;
            width: 34px;
        }

        .study-journey strong {
            color: var(--ink);
            display: block;
        }

        .study-journey p {
            color: var(--muted);
            margin: 4px 0 0;
        }

        .pathway-board {
            display: grid;
            gap: 18px;
            grid-template-columns: minmax(0, 0.86fr) minmax(320px, 0.72fr);
            margin-top: 34px;
        }

        .pathway-panel,
        .public-form {
            background: var(--surface);
            border: 1px solid rgba(6, 23, 34, 0.08);
            box-shadow: 0 18px 42px rgba(6, 23, 34, 0.06);
            padding: 28px;
        }

        .pathway-panel {
            display: grid;
            gap: 16px;
        }

        .pathway-panel h3,
        .public-form h3 {
            color: var(--ink);
            font-size: 28px;
            line-height: 1.08;
            margin: 0 0 8px;
        }

        .pathway-panel p,
        .public-form p {
            color: var(--muted);
            margin: 0;
        }

        .intake-blueprint {
            display: grid;
            gap: 12px;
            margin-top: 14px;
        }

        .intake-step {
            border-left: 3px solid var(--gold);
            padding-left: 14px;
        }

        .intake-step strong {
            color: var(--ink);
            display: block;
        }

        .intake-step span {
            color: var(--muted);
            display: block;
            font-size: 14px;
            margin-top: 3px;
        }

        .privacy-note {
            background: rgba(182, 8, 25, 0.08);
            border: 1px solid rgba(182, 8, 25, 0.24);
            color: var(--ink);
            font-size: 14px;
            font-weight: 700;
            margin-top: 16px;
            padding: 14px;
        }

        .portal-gateway {
            background:
                linear-gradient(118deg, rgba(7, 31, 74, 0.98) 0%, rgba(7, 31, 74, 0.94) 56%, rgba(182, 8, 25, 0.82) 100%);
            border: 1px solid rgba(182, 8, 25, 0.32);
            box-shadow: var(--premium-shadow);
            color: #fff;
            overflow: hidden;
            padding: 38px;
            position: relative;
        }

        .portal-gateway::before {
            animation: quietSweep 11s ease-in-out infinite;
            background: linear-gradient(110deg, transparent, rgba(255, 241, 242, 0.14), transparent);
            content: "";
            height: 180%;
            left: -65%;
            pointer-events: none;
            position: absolute;
            top: -40%;
            transform: rotate(8deg);
            width: 42%;
        }

        .portal-gateway > * {
            position: relative;
            z-index: 1;
        }

        .portal-gateway h2 {
            color: #fff;
            margin-bottom: 14px;
        }

        .portal-gateway p {
            color: rgba(255, 255, 255, 0.76);
            margin: 0;
        }

        .portal-grid {
            display: grid;
            gap: 24px;
            grid-template-columns: minmax(0, 0.94fr) minmax(360px, 0.76fr);
        }

        .portal-list {
            display: grid;
            gap: 10px;
            margin-top: 22px;
        }

        .portal-list span {
            border-top: 1px solid rgba(255, 255, 255, 0.14);
            color: rgba(255, 255, 255, 0.82);
            font-weight: 700;
            padding-top: 10px;
        }

        .portal-panel {
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.09), rgba(255, 255, 255, 0.045)),
                rgba(2, 9, 14, 0.32);
            border: 1px solid rgba(255, 241, 242, 0.22);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.12), 0 24px 70px rgba(0, 0, 0, 0.24);
            color: #fff;
            overflow: hidden;
            padding: 0;
            position: relative;
        }

        .portal-panel-content {
            padding: 26px;
            position: relative;
            z-index: 1;
        }

        .synnexus-lockup {
            background: rgba(2, 9, 14, 0.56);
            border: 1px solid rgba(255, 241, 242, 0.18);
            margin-bottom: 20px;
            padding: 14px;
        }

        .synnexus-lockup img {
            display: block;
            height: auto;
            margin: 0 auto;
            max-width: 190px;
            width: 100%;
        }

        .portal-panel-content strong {
            display: block;
            font-size: 24px;
            line-height: 1.1;
            margin-bottom: 12px;
        }

        .portal-panel-content p {
            margin-bottom: 18px;
        }

        .platform-metrics {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin: 22px 0;
        }

        .platform-metric {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 14px;
        }

        .platform-metric strong {
            color: var(--champagne);
            display: block;
            font-size: 22px;
            line-height: 1;
            margin: 0 0 6px;
        }

        .platform-metric span {
            color: rgba(255, 255, 255, 0.68);
            display: block;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .platform-rail {
            display: grid;
            gap: 10px;
            margin-top: 22px;
        }

        .platform-rail span {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(182, 8, 25, 0.16));
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.78);
            font-size: 13px;
            font-weight: 800;
            padding: 11px 12px;
        }

        .public-form {
            align-self: start;
        }

        .form-grid {
            display: grid;
            gap: 12px;
            margin-top: 18px;
        }

        .form-grid.two {
            grid-template-columns: repeat(2, 1fr);
        }

        .field {
            display: grid;
            gap: 6px;
        }

        .field label,
        .check-field {
            color: var(--ink);
            font-size: 13px;
            font-weight: 800;
        }

        .field input,
        .field select,
        .field textarea {
            background: #fbfcfd;
            border: 1px solid rgba(6, 23, 34, 0.16);
            border-radius: 8px;
            color: var(--text);
            font: inherit;
            min-height: 44px;
            padding: 10px 12px;
            width: 100%;
        }

        .field textarea {
            min-height: 108px;
            resize: vertical;
        }

        .check-field {
            align-items: start;
            display: grid;
            gap: 10px;
            grid-template-columns: 18px 1fr;
            line-height: 1.35;
        }

        .check-field input {
            margin-top: 2px;
        }

        .form-actions {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
        }

        .form-note {
            color: var(--muted);
            display: block;
            font-size: 12px;
            margin-top: 10px;
        }

        .compliance-carousel {
            display: grid;
            gap: 18px;
            margin-bottom: 24px;
        }

        .compliance-carousel-head {
            align-items: end;
            display: grid;
            gap: 20px;
            grid-template-columns: minmax(0, 0.45fr) minmax(300px, 0.55fr);
        }

        .compliance-carousel-head > div:last-child > p {
            color: rgba(255, 255, 255, 0.72);
            margin: 0;
        }

        .carousel-actions {
            align-items: center;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 16px;
        }

        .carousel-control {
            align-items: center;
            appearance: none;
            background: rgba(255, 255, 255, 0.09);
            border: 1px solid rgba(255, 241, 242, 0.28);
            color: var(--champagne);
            cursor: pointer;
            display: inline-flex;
            font-size: 28px;
            height: 42px;
            justify-content: center;
            line-height: 1;
            transition: background 0.2s ease, transform 0.2s ease;
            width: 42px;
        }

        .carousel-control:hover,
        .carousel-control:focus-visible {
            background: rgba(182, 8, 25, 0.24);
            transform: translateY(-1px);
        }

        .compliance-marquee {
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: 0 22px 60px rgba(0, 0, 0, 0.18);
            overflow: hidden;
            position: relative;
        }

        .compliance-marquee::before,
        .compliance-marquee::after {
            content: "";
            height: 100%;
            pointer-events: none;
            position: absolute;
            top: 0;
            width: 120px;
            z-index: 2;
        }

        .compliance-marquee::before {
            background: linear-gradient(90deg, var(--ink), rgba(6, 23, 34, 0));
            left: 0;
        }

        .compliance-marquee::after {
            background: linear-gradient(270deg, var(--ink), rgba(6, 23, 34, 0));
            right: 0;
        }

        .compliance-track {
            display: flex;
            gap: 12px;
            padding: 14px;
            transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform;
        }

        .compliance-badge {
            align-items: center;
            background: rgba(255, 255, 255, 0.075);
            border: 1px solid rgba(255, 241, 242, 0.16);
            color: #fff;
            display: grid;
            flex: 0 0 calc((100% - 24px) / 3);
            gap: 12px;
            grid-template-columns: 88px minmax(180px, 1fr);
            min-height: 92px;
            min-width: 0;
            padding: 16px;
        }

        .badge-mark {
            align-items: center;
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(255, 241, 242, 0.28);
            display: inline-flex;
            height: 64px;
            justify-content: center;
            padding: 10px;
            width: 88px;
        }

        .badge-mark img {
            display: block;
            max-height: 44px;
            max-width: 100%;
            object-fit: contain;
        }

        .compliance-badge strong {
            display: block;
            font-size: 16px;
            line-height: 1.1;
        }

        .compliance-badge span:last-child {
            color: rgba(255, 255, 255, 0.68);
            display: block;
            font-size: 13px;
            margin-top: 4px;
        }

        .carousel-dots {
            align-items: center;
            display: flex;
            gap: 8px;
            justify-content: center;
            padding: 0 0 18px;
        }

        .carousel-dot {
            appearance: none;
            background: rgba(255, 255, 255, 0.24);
            border: 0;
            cursor: pointer;
            height: 7px;
            padding: 0;
            transition: background 0.2s ease, transform 0.2s ease, width 0.2s ease;
            width: 18px;
        }

        .carousel-dot.is-active {
            background: var(--champagne);
            transform: scaleY(1.25);
            width: 34px;
        }

        .employment-roles {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-top: 28px;
        }

        .role-card {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #fff;
            padding: 22px;
        }

        .role-card span {
            color: var(--champagne);
            display: block;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .role-card strong {
            display: block;
            font-size: 20px;
            line-height: 1.12;
        }

        .role-card p {
            color: rgba(255, 255, 255, 0.72);
            margin: 10px 0 0;
        }

        .location-grid {
            display: grid;
            gap: 0;
            grid-template-columns: 0.9fr 1.15fr 0.95fr;
            margin-top: 42px;
            position: relative;
        }

        .location-grid::before {
            background: linear-gradient(90deg, rgba(18, 59, 115, 0.16), var(--red), rgba(18, 59, 115, 0.16));
            content: "";
            height: 1px;
            left: 0;
            position: absolute;
            right: 0;
            top: 38px;
        }

        .location-card {
            background: rgba(255, 255, 255, 0.64);
            border: 0;
            border-left: 1px solid rgba(18, 59, 115, 0.18);
            box-shadow: none;
            min-height: 220px;
            padding: 72px 28px 28px;
            position: relative;
            transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
        }

        .location-card:nth-child(2) {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 24px 70px rgba(6, 23, 34, 0.12);
            transform: translateY(26px);
            z-index: 2;
        }

        .location-card:hover {
            border-color: rgba(182, 8, 25, 0.46);
            box-shadow: 0 24px 70px rgba(6, 23, 34, 0.14);
            transform: translateY(-6px);
        }

        .location-card:nth-child(2):hover {
            transform: translateY(18px);
        }

        .location-card::before {
            background: var(--gold);
            border: 8px solid rgba(255, 255, 255, 0.84);
            border-radius: 50%;
            box-shadow: 0 0 0 1px rgba(182, 8, 25, 0.34);
            content: "";
            height: 13px;
            left: 28px;
            position: absolute;
            top: 27px;
            width: 13px;
        }

        .location-card span {
            color: var(--teal-dark);
            display: block;
            font-size: 12px;
            font-weight: 800;
            margin: 0 0 12px;
            text-transform: uppercase;
        }

        .location-card h3 {
            color: var(--ink);
            font-size: 28px;
            margin: 0 0 10px;
        }

        .location-card p {
            color: var(--muted);
            margin: 0;
        }

        .capability-strip {
            align-items: center;
            display: grid;
            gap: 14px;
            grid-template-columns: 44px minmax(0, 1fr) 44px;
            margin-top: 66px;
        }

        .capability-window {
            overflow: hidden;
        }

        .capability-track {
            display: flex;
            gap: 12px;
            transition: transform 0.55s ease;
            will-change: transform;
        }

        .capability-pill {
            background: rgba(255, 255, 255, 0.58);
            border: 1px solid rgba(18, 59, 115, 0.18);
            border-radius: 10px;
            color: var(--teal-dark);
            display: inline-flex;
            flex: 0 0 calc((100% - 24px) / 3);
            font-size: 14px;
            font-weight: 800;
            justify-content: center;
            min-height: 66px;
            padding: 12px 16px;
            text-align: center;
            transition: background 0.25s ease, border-color 0.25s ease, transform 0.25s ease;
        }

        .capability-pill:hover {
            background: rgba(182, 8, 25, 0.08);
            border-color: rgba(182, 8, 25, 0.32);
            transform: translateY(-3px);
        }

        .capability-control {
            align-items: center;
            background: var(--ink);
            border: 1px solid rgba(182, 8, 25, 0.28);
            border-radius: 50%;
            color: #fff;
            cursor: pointer;
            display: inline-flex;
            font-size: 24px;
            height: 44px;
            justify-content: center;
            line-height: 1;
            transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
            width: 44px;
        }

        .capability-control:hover,
        .capability-control:focus-visible {
            background: var(--teal-dark);
            border-color: var(--gold);
            transform: translateY(-2px);
        }

        .capability-status {
            color: var(--muted);
            font-size: 12px;
            font-weight: 800;
            grid-column: 2;
            letter-spacing: 0;
            text-align: center;
            text-transform: uppercase;
        }

        .specialty-highlight {
            align-items: center;
            background: linear-gradient(118deg, rgba(7, 31, 74, 0.98) 0%, rgba(7, 31, 74, 0.96) 55%, rgba(11, 46, 98, 0.96) 55%, rgba(182, 8, 25, 0.82) 100%);
            border: 1px solid rgba(182, 8, 25, 0.35);
            clip-path: polygon(0 0, calc(100% - 30px) 0, 100% 30px, 100% 100%, 30px 100%, 0 calc(100% - 30px));
            box-shadow: 0 18px 42px rgba(6, 23, 34, 0.16);
            color: #fff;
            display: grid;
            gap: 34px;
            grid-template-columns: minmax(0, 1fr) minmax(260px, 360px);
            margin-top: 34px;
            overflow: hidden;
            padding: 34px;
            position: relative;
        }

        .specialty-highlight::before {
            animation: quietSweep 10s ease-in-out infinite;
            background: linear-gradient(110deg, transparent, rgba(255, 241, 242, 0.16), transparent);
            content: "";
            height: 180%;
            left: -65%;
            pointer-events: none;
            position: absolute;
            top: -40%;
            transform: rotate(8deg);
            width: 42%;
        }

        .specialty-highlight h3 {
            color: #fff;
            font-size: clamp(30px, 4vw, 48px);
            line-height: 1;
            margin: 0 0 10px;
        }

        .specialty-highlight > * {
            position: relative;
            z-index: 1;
        }

        .specialty-highlight p {
            color: rgba(255, 255, 255, 0.78);
            margin: 0;
        }

        .specialty-badge {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-left: 4px solid var(--gold);
            padding: 22px;
        }

        .specialty-badge span {
            color: var(--gold);
            display: block;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .specialty-badge strong {
            color: #fff;
            display: block;
            font-size: 20px;
            line-height: 1.2;
        }

        .leadership-panel {
            align-items: center;
            display: grid;
            gap: 0;
            grid-template-columns: minmax(320px, 0.82fr) minmax(0, 1.18fr);
            margin-top: 76px;
        }

        .leadership-copy {
            background: linear-gradient(135deg, rgba(7, 31, 74, 0.98), rgba(11, 46, 98, 0.96));
            clip-path: polygon(0 0, 100% 0, calc(100% - 28px) 100%, 0 100%);
            box-shadow: 0 26px 80px rgba(6, 23, 34, 0.18);
            color: #fff;
            overflow: hidden;
            padding: 34px 44px 34px 34px;
            position: relative;
            z-index: 2;
        }

        .leadership-copy h3 {
            font-size: 34px;
            line-height: 1.15;
            margin: 0 0 12px;
        }

        .leadership-copy p {
            color: rgba(255, 255, 255, 0.72);
            margin: 0;
        }

        .leadership-focus {
            display: grid;
            gap: 10px;
            margin-top: 22px;
        }

        .leadership-focus span {
            align-items: center;
            background: transparent;
            border: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.14);
            color: rgba(255, 255, 255, 0.84);
            display: flex;
            font-size: 14px;
            font-weight: 800;
            gap: 10px;
            padding: 11px 12px;
        }

        .leadership-focus span::before {
            background: var(--gold);
            border-radius: 50%;
            box-shadow: 0 0 0 5px rgba(182, 8, 25, 0.12);
            content: "";
            flex: 0 0 auto;
            height: 7px;
            width: 7px;
        }

        .leadership-stats {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-top: 18px;
        }

        .leadership-stat {
            background: rgba(255, 241, 242, 0.09);
            border: 1px solid rgba(182, 8, 25, 0.2);
            padding: 12px;
        }

        .leadership-stat strong {
            color: #fff;
            display: block;
            font-size: 20px;
            line-height: 1;
        }

        .leadership-stat span {
            color: rgba(255, 255, 255, 0.64);
            display: block;
            font-size: 12px;
            font-weight: 800;
            margin-top: 6px;
            text-transform: uppercase;
        }

        .investigator-suite {
            display: grid;
            gap: 0;
        }

        .investigator-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(6, 23, 34, 0.08);
            box-shadow: 0 18px 52px rgba(6, 23, 34, 0.1);
            clip-path: polygon(0 0, calc(100% - 22px) 0, 100% 22px, 100% 100%, 0 100%);
            padding: 30px 30px 30px 92px;
            position: relative;
            transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
        }

        .investigator-card + .investigator-card {
            margin-left: 70px;
            margin-top: -18px;
        }

        .investigator-card::before {
            color: rgba(18, 59, 115, 0.13);
            content: attr(data-index);
            font-size: 58px;
            font-weight: 800;
            left: 22px;
            line-height: 1;
            position: absolute;
            top: 26px;
        }

        .investigator-card:hover {
            border-color: rgba(182, 8, 25, 0.46);
            box-shadow: var(--premium-shadow);
            transform: translateY(-5px);
        }

        .investigator-card span {
            color: var(--teal-dark);
            display: block;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .investigator-card strong {
            color: var(--ink);
            display: block;
            font-size: 25px;
            line-height: 1.15;
        }

        .investigator-card p {
            color: var(--muted);
            margin: 12px 0 0;
        }

        .investigator-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 18px;
        }

        .investigator-tags span {
            background: rgba(18, 59, 115, 0.08);
            border: 1px solid rgba(18, 59, 115, 0.18);
            border-radius: 999px;
            color: var(--teal-dark);
            font-size: 12px;
            font-weight: 800;
            margin: 0;
            padding: 7px 10px;
            text-transform: none;
        }

        .card {
            background: var(--surface);
            border: 1px solid rgba(6, 23, 34, 0.08);
            box-shadow: 0 18px 42px rgba(6, 23, 34, 0.06);
            clip-path: polygon(0 0, calc(100% - 18px) 0, 100% 18px, 100% 100%, 0 100%);
            overflow: hidden;
            padding: 26px;
            position: relative;
            transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
        }

        .card::before {
            background: linear-gradient(90deg, rgba(182, 8, 25, 0), rgba(182, 8, 25, 0.72), rgba(182, 8, 25, 0));
            content: "";
            height: 1px;
            left: -60%;
            opacity: 0;
            position: absolute;
            top: 0;
            transition: left 0.55s ease, opacity 0.35s ease;
            width: 60%;
        }

        .card:hover {
            border-color: rgba(182, 8, 25, 0.34);
            box-shadow: var(--premium-shadow);
            transform: translateY(-5px);
        }

        .card:hover::before {
            left: 100%;
            opacity: 1;
        }

        .dark .card {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.14);
            box-shadow: none;
        }

        .card h3 {
            color: var(--ink);
            font-size: 21px;
            margin: 0 0 10px;
        }

        .dark .card h3 {
            color: #fff;
        }

        .card ul {
            margin: 16px 0 0;
            padding-left: 20px;
        }

        .card.accent-teal {
            border-top: 2px solid var(--teal);
        }

        .card.accent-blue {
            border-top: 2px solid var(--blue);
        }

        .card.accent-gold {
            border-top: 2px solid var(--gold);
        }

        .split {
            align-items: start;
            display: grid;
            gap: 28px;
            grid-template-columns: minmax(0, 1fr) minmax(320px, 430px);
        }

        .sponsor-fit {
            border-bottom: 1px solid rgba(6, 23, 34, 0.1);
            border-top: 1px solid rgba(6, 23, 34, 0.1);
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-top: 34px;
        }

        .fit-item {
            border-left: 1px solid rgba(6, 23, 34, 0.08);
            padding: 20px 18px;
        }

        .fit-item:first-child {
            border-left: 0;
        }

        .fit-item summary {
            cursor: pointer;
            list-style: none;
            padding-right: 30px;
            position: relative;
        }

        .fit-item summary::-webkit-details-marker {
            display: none;
        }

        .fit-item summary::after {
            align-items: center;
            border: 1px solid rgba(18, 59, 115, 0.22);
            border-radius: 50%;
            color: var(--teal-dark);
            content: "+";
            display: inline-flex;
            font-size: 16px;
            height: 24px;
            justify-content: center;
            position: absolute;
            right: 0;
            top: 0;
            width: 24px;
        }

        .fit-item[open] summary::after {
            content: "-";
        }

        .fit-item span {
            color: var(--teal-dark);
            display: block;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .fit-item p {
            color: var(--muted);
            font-size: 14px;
            margin: 0;
        }

        .fit-item[open] p {
            margin-top: 10px;
        }

        .timeline {
            border-left: 3px solid var(--teal);
            display: grid;
            gap: 18px;
            list-style: none;
            margin: 0;
            padding: 0 0 0 24px;
        }

        .timeline li {
            position: relative;
        }

        .timeline li::before {
            background: var(--gold);
            border: 3px solid var(--canvas);
            border-radius: 50%;
            content: "";
            height: 12px;
            left: -32px;
            position: absolute;
            top: 5px;
            width: 12px;
        }

        .timeline strong {
            color: var(--ink);
            display: block;
            margin-bottom: 4px;
        }

        .notice {
            background: var(--mint);
            border: 1px solid rgba(18, 59, 115, 0.22);
            clip-path: polygon(0 0, calc(100% - 18px) 0, 100% 18px, 100% 100%, 0 100%);
            box-shadow: 0 18px 42px rgba(18, 59, 115, 0.11);
            padding: 22px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .notice:hover {
            box-shadow: 0 24px 62px rgba(18, 59, 115, 0.16);
            transform: translateY(-4px);
        }

        .notice strong {
            color: var(--teal-dark);
            display: block;
            margin-bottom: 6px;
        }

        .contact-panel {
            background: #fff;
            border: 1px solid var(--line);
            clip-path: polygon(0 0, calc(100% - 18px) 0, 100% 18px, 100% 100%, 0 100%);
            box-shadow: 0 18px 42px rgba(6, 23, 34, 0.1);
            color: var(--text);
            padding: 28px;
            transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
        }

        .contact-panel:hover {
            border-color: rgba(182, 8, 25, 0.34);
            box-shadow: var(--premium-shadow);
            transform: translateY(-4px);
        }

        .contact-panel h3 {
            color: var(--ink);
            font-size: 24px;
            margin: 0 0 12px;
        }

        .contact-list {
            display: grid;
            gap: 12px;
            margin: 18px 0 0;
        }

        .contact-list a,
        .contact-list span {
            color: var(--teal-dark);
            font-weight: 800;
            overflow-wrap: anywhere;
            text-decoration: none;
        }

        .contact-map-panel {
            background: #fff;
            border: 1px solid rgba(6, 23, 34, 0.08);
            box-shadow: var(--premium-shadow);
            display: grid;
            grid-template-columns: minmax(280px, 0.42fr) minmax(0, 1fr);
            margin-top: 30px;
            overflow: hidden;
        }

        .contact-map-copy {
            background:
                linear-gradient(145deg, rgba(7, 31, 74, 0.98), rgba(11, 46, 98, 0.95)),
                var(--ink);
            color: #fff;
            padding: 32px;
            position: relative;
        }

        .contact-map-copy::after {
            background: url("{{ $brandKit('logo-mark-512.png') }}") right -64px bottom -82px / 230px auto no-repeat;
            content: "";
            inset: 0;
            opacity: 0.08;
            pointer-events: none;
            position: absolute;
        }

        .contact-map-copy > * {
            position: relative;
            z-index: 1;
        }

        .contact-map-copy h3 {
            color: #fff;
            font-size: clamp(28px, 3vw, 42px);
            line-height: 1.04;
            margin: 0;
        }

        .contact-map-copy p {
            color: rgba(255, 255, 255, 0.72);
            margin: 14px 0 0;
        }

        .clinic-map {
            background:
                radial-gradient(circle at 35% 34%, rgba(182, 8, 25, 0.18), transparent 18%),
                radial-gradient(circle at 64% 71%, rgba(18, 59, 115, 0.18), transparent 21%),
                linear-gradient(135deg, #f7f9fc, #edf3fb);
            min-height: 430px;
            overflow: hidden;
            position: relative;
        }

        .clinic-map::before,
        .clinic-map::after {
            content: "";
            pointer-events: none;
            position: absolute;
        }

        .clinic-map::before {
            background:
                linear-gradient(26deg, transparent 0 46%, rgba(18, 59, 115, 0.18) 46% 47%, transparent 47% 100%),
                linear-gradient(112deg, transparent 0 48%, rgba(182, 8, 25, 0.18) 48% 49%, transparent 49% 100%),
                repeating-linear-gradient(90deg, rgba(7, 31, 74, 0.05) 0 1px, transparent 1px 72px),
                repeating-linear-gradient(0deg, rgba(7, 31, 74, 0.045) 0 1px, transparent 1px 72px);
            inset: -40px;
            transform: rotate(-2deg);
        }

        .clinic-map::after {
            background: linear-gradient(90deg, transparent, rgba(182, 8, 25, 0.44), transparent);
            bottom: 28px;
            height: 1px;
            left: 40px;
            right: 40px;
        }

        .map-pin {
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(7, 31, 74, 0.12);
            box-shadow: 0 18px 44px rgba(7, 31, 74, 0.16);
            color: var(--ink);
            min-width: 190px;
            padding: 14px 16px 14px 46px;
            position: absolute;
            text-decoration: none;
            z-index: 1;
        }

        .map-pin::before {
            background: var(--red);
            border: 6px solid #fff;
            border-radius: 50%;
            box-shadow: 0 0 0 1px rgba(182, 8, 25, 0.34), 0 14px 28px rgba(182, 8, 25, 0.24);
            content: "";
            height: 16px;
            left: 16px;
            position: absolute;
            top: 16px;
            width: 16px;
        }

        .map-pin strong {
            display: block;
            font-size: 15px;
            line-height: 1.05;
        }

        .map-pin span {
            color: var(--muted);
            display: block;
            font-size: 12px;
            margin-top: 4px;
        }

        .map-pin.miami-springs {
            left: 42%;
            top: 24%;
        }

        .map-pin.flagler {
            left: 24%;
            top: 47%;
        }

        .map-pin.homestead {
            left: 56%;
            top: 67%;
        }

        footer {
            background: #051431;
            color: rgba(255, 255, 255, 0.72);
            padding: 26px 38px;
        }

        footer .wrap {
            align-items: center;
            display: flex;
            gap: 16px;
            justify-content: space-between;
        }

        .lux-reveal {
            opacity: 0;
            transform: translateY(26px);
            transition: opacity 0.7s ease, transform 0.7s ease;
            transition-delay: var(--reveal-delay, 0ms);
        }

        .lux-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .hero .eyebrow,
        .hero h1,
        .hero p {
            animation: heroContentRise 0.82s ease both;
        }

        .hero h1 {
            animation-delay: 0.08s;
        }

        .hero p:nth-of-type(2) {
            animation-delay: 0.16s;
        }

        .hero p:nth-of-type(3) {
            animation-delay: 0.24s;
        }

        @keyframes heroContentRise {
            from {
                opacity: 0;
                transform: translateY(22px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes heroSheen {
            0%,
            22% {
                left: -72%;
                opacity: 0;
            }

            42% {
                opacity: 1;
            }

            64%,
            100% {
                left: 128%;
                opacity: 0;
            }
        }

        @keyframes quietSweep {
            0%,
            38% {
                left: -65%;
                opacity: 0;
            }

            48% {
                opacity: 1;
            }

            68%,
            100% {
                left: 120%;
                opacity: 0;
            }
        }

        @media (max-width: 920px) {
            .topbar {
                align-items: center;
                border-radius: 0;
                flex-direction: row;
                gap: 12px;
                left: 0;
                margin: 0;
                padding: 10px 14px;
                right: 0;
                top: 0;
                width: 100%;
            }

            .brand {
                flex: 0 0 auto;
                gap: 8px;
                max-width: none;
            }

            .brand-logo {
                height: 44px;
                max-width: 44px;
                width: 44px;
            }

            .brand-wordmark {
                font-size: 15px;
                gap: 6px;
            }

            .brand-rule {
                height: 19px;
                transform: translateY(3px);
            }

            .nav {
                flex: 1 1 auto;
                flex-wrap: nowrap;
                justify-content: flex-start;
                overflow-x: auto;
                padding-bottom: 2px;
                scrollbar-width: thin;
            }

            .nav details {
                flex: 0 0 auto;
                width: auto;
            }

            .nav-dropdown {
                margin-top: 6px;
                left: 14px;
                max-width: calc(100vw - 28px);
                position: fixed;
                right: 14px;
                top: 64px;
                width: auto;
            }

            .hero {
                background:
                    linear-gradient(90deg, rgba(7, 31, 74, 0.94) 0%, rgba(11, 46, 98, 0.82) 100%),
                    url("{{ asset('images/medisana/research-center-hero.png') }}") center / cover no-repeat;
                clip-path: none;
                min-height: 0;
                padding: 72px 22px 84px;
            }

            .page-hero {
                padding: 70px 22px 52px;
            }

            .hero-stage {
                gap: 28px;
                grid-template-columns: 1fr;
                min-height: 0;
            }

            .hero-title {
                font-size: clamp(38px, 8vw, 62px);
            }

            .proof-strip {
                margin-top: -44px;
                padding: 0 22px;
            }

            .band {
                padding: 48px 22px;
            }

            .proof-grid,
            .location-grid,
            .sponsor-fit,
            .capability-strip,
            .specialty-highlight,
            .audience-router,
            .audience-cards,
            .compliance-carousel-head,
            .page-hero-grid,
            .site-capability-main,
            .capability-card-grid,
            .current-studies,
            .patient-experience,
            .pathway-board,
            .portal-grid,
            .contact-map-panel,
            .form-grid.two,
            .employment-roles,
            .leadership-panel,
            .grid.three,
            .grid.four,
            .grid.two,
            .section-head,
            .split {
                grid-template-columns: 1fr;
            }

            .carousel-actions {
                justify-content: flex-start;
                margin-top: 14px;
            }

            .compliance-badge {
                flex-basis: calc((100% - 12px) / 2);
            }

            .location-grid::before {
                display: none;
            }

            .location-card,
            .location-card:nth-child(2),
            .location-card:nth-child(2):hover {
                transform: none;
            }

            .capability-strip {
                grid-template-columns: 40px minmax(0, 1fr) 40px;
                margin-top: 24px;
            }

            .capability-pill {
                flex-basis: calc((100% - 12px) / 2);
            }

            .leadership-copy {
                clip-path: none;
                padding: 28px;
            }

            .investigator-card,
            .investigator-card + .investigator-card {
                margin: 0;
            }

            .clinic-map {
                min-height: 380px;
            }

            .map-pin {
                min-width: 172px;
            }

            footer .wrap {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 560px) {
            .brand {
                max-width: 44px;
            }

            .brand-wordmark {
                display: none;
            }

            .button {
                width: 100%;
            }

            .hero-title {
                flex-wrap: wrap;
                font-size: clamp(38px, 13vw, 52px);
                white-space: normal;
            }

            .hero-title-rule {
                display: none;
            }

            .capability-pill {
                flex-basis: 100%;
            }

            .compliance-badge {
                flex-basis: 100%;
                grid-template-columns: 72px minmax(0, 1fr);
            }

            .badge-mark {
                height: 56px;
                width: 72px;
            }

            .clinic-map {
                min-height: 430px;
            }

            .map-pin {
                left: 22px !important;
                right: 22px;
                width: auto;
            }

            .map-pin.miami-springs {
                top: 48px;
            }

            .map-pin.flagler {
                top: 174px;
            }

            .map-pin.homestead {
                top: 300px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }

            .lux-reveal {
                opacity: 1;
                transform: none;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ $medisanaUrl() }}" aria-label="Medisana Research Center home">
            <img class="brand-logo" src="{{ $brandKit('logo-mark-128.png') }}" alt="Medisana Research Center">
            <span class="brand-wordmark">
                <span>Medisana</span>
                <span class="brand-rule" aria-hidden="true"></span>
                <small>Research Center</small>
            </span>
        </a>
        <nav class="nav" aria-label="Primary navigation">
            <a class="{{ $isHome ? 'active' : '' }}" href="{{ $medisanaUrl() }}">Overview</a>
            <details>
                <summary>Who We Serve</summary>
                <div class="nav-dropdown">
                    <a class="{{ $isPage('patients') ? 'active' : '' }}" href="{{ $medisanaUrl('patients') }}">Patients &amp; Families</a>
                    <a href="{{ $medisanaUrl('patients', 'currently-enrolling') }}">Currently Enrolling Studies</a>
                    <a class="{{ $isPage('sponsors') ? 'active' : '' }}" href="{{ $medisanaUrl('sponsors') }}">Sponsors &amp; CROs</a>
                </div>
            </details>
            <details>
                <summary>Sponsor/CRO Operations</summary>
                <div class="nav-dropdown">
                    <a class="{{ $isPage('sponsors') ? 'active' : '' }}" href="{{ $medisanaUrl('sponsors') }}">Sponsor Overview</a>
                    <a class="{{ $isPage('capabilities') ? 'active' : '' }}" href="{{ $medisanaUrl('capabilities') }}">Site Capabilities</a>
                    <a href="{{ $medisanaUrl('capabilities', 'investigators') }}">Investigator Leadership</a>
                    <a href="{{ $medisanaUrl('capabilities') }}">Diagnostics, CNS &amp; Raters</a>
                    <a href="{{ $medisanaUrl('sponsors', 'execution') }}">Execution</a>
                    <a class="{{ $isPage('readiness') ? 'active' : '' }}" href="{{ $medisanaUrl('readiness') }}">Readiness</a>
                    <a href="{{ $medisanaUrl('sponsors', 'portal') }}">Secure Portal</a>
                </div>
            </details>
            <a class="{{ $isPage('careers') ? 'active' : '' }}" href="{{ $medisanaUrl('careers') }}">Careers</a>
            <a class="{{ $isPage('contact') ? 'active' : '' }}" href="{{ $medisanaUrl('contact') }}">Contact</a>
        </nav>
    </header>

    <main>
        @unless ($isHome)
            <section class="page-hero">
                <div class="wrap page-hero-grid">
                    <div>
                        <p class="eyebrow">{{ $activeHeader['eyebrow'] }}</p>
                        <h1>{{ $activeHeader['title'] }}</h1>
                        <p>{{ $activeHeader['copy'] }}</p>
                    </div>
                    <nav class="page-jump" aria-label="Medisana Research Center pages">
                        <a class="{{ $isPage('patients') ? 'active' : '' }}" href="{{ $medisanaUrl('patients') }}">Patients &amp; Families</a>
                        <a href="{{ $medisanaUrl('patients', 'currently-enrolling') }}">Currently Enrolling</a>
                        <a class="{{ ($isPage('sponsors') || $isPage('capabilities')) ? 'active' : '' }}" href="{{ $medisanaUrl('sponsors') }}">Sponsor/CRO Operations</a>
                        <a class="{{ $isPage('readiness') ? 'active' : '' }}" href="{{ $medisanaUrl('readiness') }}">Readiness</a>
                        <a class="{{ $isPage('careers') ? 'active' : '' }}" href="{{ $medisanaUrl('careers') }}">Careers</a>
                        <a class="{{ $isPage('contact') ? 'active' : '' }}" href="{{ $medisanaUrl('contact') }}">Contact</a>
                    </nav>
                </div>
            </section>
        @endunless

        @if ($isHome)
        <section class="hero">
            <div class="wrap hero-stage">
                <div class="hero-content">
                    <p class="eyebrow">Clinical research in Miami Springs</p>
                    <h1 class="hero-title" aria-label="Medisana Research Center">
                        <span class="hero-title-brand">Medisana</span>
                        <span class="hero-title-rule" aria-hidden="true"></span>
                        <span class="hero-title-accent">Research Center</span>
                    </h1>
                    <p>A clinic-embedded research center aligning community patient access, investigator oversight, CNS capability, diagnostics, participant support, and secure operations into one sponsor-ready pathway for approved studies.</p>
                </div>
            </div>
        </section>
        @endif

        @if ($isHome)
        <section class="proof-strip" aria-label="Research center focus areas">
            <div class="wrap proof-grid">
                <div class="proof-item">
                    <strong>Clinic-Embedded Model</strong>
                    <span>Community access, investigator leadership, and disciplined study execution.</span>
                </div>
                <div class="proof-item">
                    <strong>Investigator + CNS Bench</strong>
                    <span>Physician oversight, psychiatry, psychoanalysis, and qualified raters.</span>
                </div>
                <div class="proof-item">
                    <strong>Startup Turnaround</strong>
                    <span>Regulatory, activation, and budget action items tracked in one place.</span>
                </div>
                <div class="proof-item">
                    <strong>Privacy First</strong>
                    <span>Protected information moves through the secure research portal, not general email.</span>
                </div>
            </div>
        </section>

        <section id="who-we-serve" class="band white">
            <div class="wrap audience-router">
                <div class="audience-intro">
                    <p class="eyebrow">Who we serve</p>
                    <h2>One research center. Two very different visitor needs.</h2>
                    <p>Patients need clarity and safety. Sponsors and CROs need capability, responsiveness, and operational confidence.</p>
                </div>
                <div class="audience-cards">
                    <details class="audience-card patient">
                        <summary>
                            <span>Patients and families</span>
                            <h3>Learn what participation means before sharing medical details.</h3>
                        </summary>
                        <div class="audience-card-body">
                            <p>Plain-language information about choice, consent, privacy, visits, and support.</p>
                            <ul>
                                <li>Understand research and informed consent</li>
                                <li>Request general study information</li>
                                <li>Use the secure research portal when protected prescreening is active</li>
                            </ul>
                            <a class="button dark" href="{{ $medisanaUrl('patients') }}">Go to Patient Information</a>
                        </div>
                    </details>
                    <details class="audience-card sponsor">
                        <summary>
                            <span>Sponsors and CROs</span>
                            <h3>Review whether the site can meet protocol and enrollment expectations.</h3>
                        </summary>
                        <div class="audience-card-body">
                            <p>A concise path to patient access, investigator oversight, startup readiness, and execution capability.</p>
                            <ul>
                                <li>Review site capabilities and protocol fit</li>
                                <li>Discuss enrollment, activation, budget, and study conduct</li>
                                <li>Move protected files and workflows through the secure research portal</li>
                            </ul>
                            <a class="button dark" href="{{ $medisanaUrl('sponsors') }}">Go to Sponsor Information</a>
                        </div>
                    </details>
                    <details class="audience-card current">
                        <summary>
                            <span>Currently enrolling studies</span>
                            <h3>See approved study opportunities when they are open for public outreach.</h3>
                        </summary>
                        <div class="audience-card-body">
                            <p>Public study listings appear only after participant-facing materials, privacy routing, and study workflows are ready.</p>
                            <ul>
                                <li>Review open study summaries</li>
                                <li>Request general updates without sending protected information</li>
                                <li>Move into the secure research portal for prescreening when active</li>
                            </ul>
                            <a class="button dark" href="{{ $medisanaUrl('patients', 'currently-enrolling') }}">View Current Studies</a>
                        </div>
                    </details>
                </div>
            </div>
        </section>
        @endif

        @if ($isPage('capabilities'))
        <section id="clinic-network" class="band">
            <div class="wrap section-head">
                <div>
                    <p class="eyebrow">Site capabilities</p>
                    <h2>Site capabilities, organized around how studies actually run.</h2>
                </div>
                <p>Scan the model first, then open each capability to review the detail behind oversight, support, and operations.</p>
            </div>
            <div class="wrap capability-system">
                <article class="site-capability-main">
                    <div>
                        <p class="eyebrow">Integrated site model</p>
                        <h3>Site Capabilities</h3>
                        <p>Clinical access, investigators, participant support, diagnostics, CNS capability, and secure workflow coordination unified in one operating model.</p>
                    </div>
                    <div class="capability-stats" aria-label="Site capability summary">
                        <div class="capability-stat">
                            <strong>3</strong>
                            <span>Clinic locations</span>
                        </div>
                        <div class="capability-stat">
                            <strong>3</strong>
                            <span>Principal Investigators</span>
                        </div>
                        <div class="capability-stat">
                            <strong>1</strong>
                            <span>Operational backbone</span>
                        </div>
                    </div>
                </article>
                <div class="capability-card-grid" aria-label="Expandable site capability areas">
                    <details class="capability-card" data-capability-detail>
                        <summary>
                            <span>Patient access</span>
                            <h3>Clinic Network</h3>
                        </summary>
                        <div class="capability-card-body">
                            <ul>
                                <li>Miami Springs, Flagler, and Homestead patient access</li>
                                <li>Care-team coordination across established clinic locations</li>
                                <li>Community relationships that support study awareness and follow-up</li>
                            </ul>
                        </div>
                    </details>
                    <details class="capability-card" id="investigators" data-capability-detail>
                        <summary>
                            <span>Oversight</span>
                            <h3>Investigator Leadership</h3>
                        </summary>
                        <div class="capability-card-body">
                            <ul>
                                <li>Pastor Torres, MD: Internal Medicine, General Surgery, Cosmetic Surgery</li>
                                <li>Vladimir A. Guevara Vazquez, MD: Psychiatry and Psychoanalysis</li>
                                <li>Jose Morales, MD: Psychiatry and CNS support</li>
                                <li>Protocol review, delegation, safety awareness, and approved study conduct</li>
                            </ul>
                        </div>
                    </details>
                    <details class="capability-card" data-capability-detail>
                        <summary>
                            <span>Study support</span>
                            <h3>Patient Support</h3>
                        </summary>
                        <div class="capability-card-body">
                            <ul>
                                <li>Transportation coordination when appropriate and study-allowed</li>
                                <li>HHA-supported services when available and approved</li>
                                <li>Visit reminders, navigation, and retention support</li>
                            </ul>
                        </div>
                    </details>
                    <details class="capability-card" data-capability-detail>
                        <summary>
                            <span>CNS readiness</span>
                            <h3>CNS &amp; Qualified Raters</h3>
                        </summary>
                        <div class="capability-card-body">
                            <ul>
                                <li>Psychiatry investigator bench for CNS-focused sponsor review</li>
                                <li>Qualified raters for sponsor-approved scales and investigator delegation</li>
                                <li>Brainwave / EEG assessment capability subject to protocol requirements</li>
                            </ul>
                        </div>
                    </details>
                    <details class="capability-card" data-capability-detail>
                        <summary>
                            <span>Infrastructure</span>
                            <h3>Diagnostics &amp; Dispensing</h3>
                        </summary>
                        <div class="capability-card-body">
                            <ul>
                                <li>Ultrasound and EKG diagnostics</li>
                                <li>Onsite pharmacy dispensing capability</li>
                                <li>Temperature-controlled NIST-certified monitoring</li>
                            </ul>
                        </div>
                    </details>
                    <details class="capability-card" data-capability-detail>
                        <summary>
                            <span>Backbone</span>
                            <h3>Operational Backbone</h3>
                        </summary>
                        <div class="capability-card-body">
                            <ul>
                                <li>Secure prescreening and protected study workflows</li>
                                <li>Feasibility packets, startup tasks, regulatory files, and budget action items</li>
                                <li>Operational visibility for sponsors, CROs, investigators, and the site team</li>
                            </ul>
                        </div>
                    </details>
                </div>
            </div>
        </section>
        @endif

        @if ($isPage('patients'))
        <section id="participants" class="band white">
            <div class="wrap section-head">
                <h2>For patients, families, and community members.</h2>
                <p>Clinical research participation is explained clearly, reviewed carefully, and handled through approved study processes.</p>
            </div>
            <figure class="wrap section-visual">
                <img
                    src="{{ asset('images/medisana/Medisana_Patient_Families_Community.png') }}"
                    alt="Medisana Research Center engaging patients, families, and community members in clinical research"
                    width="1536"
                    height="1024"
                    loading="eager"
                    decoding="async">
            </figure>
            <div class="wrap grid four">
                <article class="card accent-teal">
                    <h3>Learn About Research</h3>
                    <p>Study opportunities are shared only when appropriate approvals, study materials, and site workflows are ready.</p>
                </article>
                <article class="card accent-blue">
                    <h3>Understand Your Choice</h3>
                    <p>Participation is voluntary. Each study explains purpose, risks, benefits, visits, privacy, and alternatives before consent.</p>
                </article>
                <article class="card accent-gold">
                    <h3>Protect Your Privacy</h3>
                    <p>Protected details belong in secure study workflows, while general email remains reserved for basic questions.</p>
                </article>
                <article class="card accent-teal">
                    <h3>Support Around Visits</h3>
                    <p>Medisana may help coordinate transportation, reminders, and HHA-supported patient services when available, appropriate, and allowed by the study.</p>
                </article>
            </div>
            <div class="wrap current-studies" id="currently-enrolling" aria-label="Currently enrolling studies">
                <div class="current-studies-copy">
                    <p class="eyebrow">Currently enrolling studies</p>
                    <h3>Approved openings will appear here when studies are ready for public outreach.</h3>
                    <p>Medisana will list sponsor- or IRB-approved study summaries only after the required materials, privacy routing, and participant-facing language are ready.</p>
                </div>
                <div class="study-listings">
                    <article class="study-listing">
                        <span>Enrollment board</span>
                        <strong>No public studies are listed at this time.</strong>
                        <p>When a study opens, this area can show the condition area, basic age range, visit location, transportation availability, and how to request secure prescreening.</p>
                        <a class="button dark" href="{{ $medisanaUrl('patients', 'participant-interest') }}">Request study updates</a>
                    </article>
                    <article class="study-listing muted">
                        <span>Public listing approach</span>
                        <strong>Plain-language study summaries before secure prescreening.</strong>
                        <p>Public summaries stay high level, with protected details reserved for the secure research portal.</p>
                    </article>
                </div>
            </div>
            <div class="wrap patient-experience" aria-label="What happens during an approved study">
                <div class="research-video">
                    <div class="video-frame">
                        <iframe
                            title="NIMH video: What is Clinical Research?"
                            src="https://www.youtube-nocookie.com/embed/lJOQB_G15Jc"
                            loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>
                    <div class="video-source">
                        <span>Patient education video</span>
                        <strong>NIMH: What is Clinical Research?</strong>
                        <p>Clinical research helps advance care by answering questions carefully, with trained teams, approved plans, and participant safety checks built into the process.</p>
                        <a href="https://www.youtube.com/watch?v=lJOQB_G15Jc" target="_blank" rel="noopener">Open the video directly</a>
                        <br>
                        <a href="https://www.nimh.nih.gov/health/trials" target="_blank" rel="noopener">View NIMH participant resources</a>
                    </div>
                </div>
                <div class="study-journey">
                    <p class="eyebrow">During an approved study</p>
                    <h3>What participants can expect after a study is open.</h3>
                    <ol>
                        <li>
                            <div>
                                <strong>Pre-screening and eligibility</strong>
                                <p>The study team reviews basic criteria and answers questions before any research decision is made.</p>
                            </div>
                        </li>
                        <li>
                            <div>
                                <strong>Informed consent</strong>
                                <p>The visit explains purpose, risks, benefits, privacy, time commitment, alternatives, and the voluntary nature of participation.</p>
                            </div>
                        </li>
                        <li>
                            <div>
                                <strong>Study visits and assessments</strong>
                                <p>Approved protocol visits may include exams, questionnaires, labs, diagnostics, medication dispensing, or follow-up check-ins.</p>
                            </div>
                        </li>
                        <li>
                            <div>
                                <strong>Visit support when available</strong>
                                <p>Transportation coordination, reminders, and HHA-supported services may help reduce participation burden when appropriate and study-allowed.</p>
                            </div>
                        </li>
                        <li>
                            <div>
                                <strong>Safety monitoring</strong>
                                <p>The principal investigator and study team monitor safety, document concerns, and coordinate with the sponsor, CRO, and IRB when required.</p>
                            </div>
                        </li>
                        <li>
                            <div>
                                <strong>Completion or withdrawal</strong>
                                <p>Participants may complete scheduled visits or choose to leave the study, with guidance on how to stop safely.</p>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="wrap pathway-board" id="participant-interest" aria-label="Study participant information request and prescreening pathway">
                <div class="pathway-panel">
                    <p class="eyebrow">Study participant pathway</p>
                    <h3>Request information now. Complete secure prescreening only when a study is ready.</h3>
                    <p>Patients and families can ask to be contacted about future approved study opportunities. Detailed medical prescreening is handled through the secure research portal after privacy, consent, access control, and IRB or sponsor-approved materials are in place.</p>
                    <div class="intake-blueprint" aria-label="Secure prescreening questionnaire categories">
                        <div class="intake-step">
                            <strong>Demographic profile</strong>
                            <span>Age range, sex at birth, race, ethnicity, preferred language, location, and contact preferences when needed for a specific study.</span>
                        </div>
                        <div class="intake-step">
                            <strong>Current and past medical conditions</strong>
                            <span>Study-specific condition history, diagnosis timing, relevant procedures, and inclusion or exclusion criteria.</span>
                        </div>
                        <div class="intake-step">
                            <strong>Medication and treatment list</strong>
                            <span>Current medications, recent medication changes, allergies, prior therapies, and pharmacy-related requirements.</span>
                        </div>
                        <div class="intake-step">
                            <strong>Study fit and safety review</strong>
                            <span>Research staff review responses under the approved protocol before scheduling screening, consent, or study visits.</span>
                        </div>
                    </div>
                    <div class="privacy-note">Detailed medical questionnaires are reserved for the secure research portal once HIPAA safeguards, role-based access, consent language, and study-specific approvals are active.</div>
                </div>
                <form class="public-form" data-mailto-form data-recipient="info@medisana-health.com" data-subject="Study Participant Information Request">
                    <h3>Request Study Information</h3>
                    <p>For general study interest, share contact preferences and a high-level question. Protected health details are reserved for secure portal workflows.</p>
                    <div class="form-grid two">
                        <div class="field">
                            <label for="participant_name">Name</label>
                            <input id="participant_name" name="Name" type="text" autocomplete="name">
                        </div>
                        <div class="field">
                            <label for="participant_email">Email</label>
                            <input id="participant_email" name="Email" type="email" autocomplete="email">
                        </div>
                        <div class="field">
                            <label for="participant_phone">Phone</label>
                            <input id="participant_phone" name="Phone" type="tel" autocomplete="tel">
                        </div>
                        <div class="field">
                            <label for="participant_contact">Preferred contact</label>
                            <select id="participant_contact" name="Preferred contact">
                                <option value="">Select one</option>
                                <option>Email</option>
                                <option>Phone</option>
                                <option>Text message</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="participant_location">Closest location</label>
                            <select id="participant_location" name="Closest location">
                                <option value="">Select one</option>
                                <option>Miami Springs</option>
                                <option>Flagler</option>
                                <option>Homestead</option>
                                <option>Not sure</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="participant_interest">Research interest</label>
                            <select id="participant_interest" name="Research interest">
                                <option value="">Select one</option>
                                <option>General study updates</option>
                                <option>CNS or behavioral health studies</option>
                                <option>Wellness or primary care studies</option>
                                <option>Caregiver or family inquiry</option>
                                <option>Not sure yet</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="field">
                            <label for="participant_question">General question</label>
                            <textarea id="participant_question" name="General question" placeholder="Share a general question without medical details."></textarea>
                        </div>
                        <label class="check-field">
                            <input type="checkbox" name="Privacy acknowledgment" value="Acknowledged">
                            <span>I understand this form is for general information only, with private health information reserved for secure portal workflows.</span>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button class="button dark" type="submit">Email Research Team</button>
                        <a class="button" href="mailto:info@medisana-health.com?subject=Study%20Participant%20Information%20Request">Open Email</a>
                    </div>
                    <span class="form-note">This does not enroll anyone in a study. A research team member must confirm whether any approved opportunity is available.</span>
                </form>
            </div>
        </section>
        @endif

        @if ($isPage('sponsors'))
        <section id="sponsors" class="band">
            <div class="wrap split">
                <div>
                    <p class="eyebrow">Sponsor/CRO operations</p>
                    <h2>A clinic-embedded site built for enrollment, engagement, retention, oversight, responsive startup, and execution.</h2>
                    <p class="lead">Medisana's advantage is simple: research is connected to active community clinics, physician relationships, participant support, and a structured operating platform.</p>
                </div>
                <div class="notice">
                    <strong>Sponsor inquiry pathway</strong>
                    <p>Early conversations center on non-PHI study details, enrollment goals, therapeutic fit, startup needs, and timeline expectations. Protected documents and workflows move through the secure research portal.</p>
                    <a class="button dark" href="mailto:info@medisana-health.com?subject=Sponsor%20or%20CRO%20Research%20Inquiry">Start Sponsor Inquiry</a>
                </div>
            </div>
            <figure class="wrap section-visual">
                <img
                    src="{{ asset('images/medisana/Medisana_Sponsor_CRO_Operations.png') }}"
                    alt="Medisana Research Center sponsor and CRO clinical research operations"
                    width="1672"
                    height="941"
                    loading="eager"
                    decoding="async">
            </figure>
            <div class="wrap sponsor-fit" aria-label="Sponsor and CRO fit">
                <details class="fit-item">
                    <summary><span>Patient access and retention</span></summary>
                    <p>Miami Springs, Flagler, and Homestead clinic access supports feasibility, outreach, reminders, transportation coordination, and retention planning when study-allowed.</p>
                </details>
                <details class="fit-item">
                    <summary><span>Investigator oversight</span></summary>
                    <p>Principal Investigator leadership anchors protocol review, delegation, safety awareness, study conduct, and sponsor/CRO communication.</p>
                </details>
                <details class="fit-item">
                    <summary><span>CNS, diagnostics, and dispensing</span></summary>
                    <p>Psychiatry investigators, qualified CNS raters, Brainwave / EEG, ultrasound, EKG, onsite dispensing, and NIST-certified temperature monitoring support protocol-specific review.</p>
                </details>
                <details class="fit-item">
                    <summary><span>Startup, budget, and activation</span></summary>
                    <p>Regulatory documents, contracts, budgets, activation dependencies, and sponsor-response items can be tracked for quick turnaround visibility.</p>
                </details>
                <details class="fit-item">
                    <summary><span>Private clinic operating model</span></summary>
                    <p>Research is supported by real care environments, community relationships, and practical workflows instead of operating as a disconnected standalone site.</p>
                </details>
            </div>
        </section>

        <section id="execution" class="band white">
            <div class="wrap section-head">
                <div>
                    <p class="eyebrow">Sponsor/CRO execution</p>
                    <h2>After approval, the work becomes execution.</h2>
                </div>
                <p>Once IRB, contract, budget, regulatory, training, delegation, and activation requirements are complete, the site follows the approved protocol from recruitment through closeout.</p>
            </div>
            <div class="wrap grid two">
                <article class="card">
                    <h3>Site Activation</h3>
                    <ul>
                        <li>Essential documents, version control, and training logs</li>
                        <li>Delegation setup and investigator oversight</li>
                        <li>Quick turnaround visibility for open sponsor, CRO, IRB, contract, and budget dependencies</li>
                    </ul>
                </article>
                <article class="card">
                    <h3>Participant Workflow</h3>
                    <ul>
                        <li>Approved recruitment pathway and referral coordination</li>
                        <li>Screening, consent, enrollment, and visit scheduling</li>
                        <li>Clear separation between clinic care and research participation</li>
                    </ul>
                </article>
                <article class="card">
                    <h3>Study Conduct</h3>
                    <ul>
                        <li>Protocol visit coordination and follow-up tracking</li>
                        <li>Source documentation and query support</li>
                        <li>Sponsor, CRO, investigator, and site-team communication rhythm</li>
                    </ul>
                </article>
                <article class="card">
                    <h3>Closeout Readiness</h3>
                    <ul>
                        <li>Document reconciliation and missing-item follow-up</li>
                        <li>Milestone tracking through closeout</li>
                        <li>Regulatory binder structure for audit-ready site files</li>
                    </ul>
                </article>
            </div>
        </section>
        @endif

        @if ($isPage('readiness'))
        <section id="readiness" class="band dark">
            <div class="wrap section-head">
                <div>
                    <p class="eyebrow">Sponsor/CRO readiness</p>
                    <h2>Compliance foundation behind responsible research operations.</h2>
                </div>
                <p>Research operations are organized around FDA-regulated conduct, IRB oversight, informed consent, investigator accountability, privacy safeguards, source documentation, and inspection-ready records.</p>
            </div>
            <div class="wrap compliance-carousel" data-compliance-carousel aria-label="Compliance alignment carousel">
                <div class="compliance-carousel-head">
                    <div>
                        <p class="eyebrow">Compliance alignment</p>
                    </div>
                    <div>
                        <p>Icon-style badges highlight the agencies, standards, and review pathways sponsors expect to see before site activation.</p>
                        <div class="carousel-actions">
                            <button class="carousel-control" type="button" data-compliance-prev aria-label="Previous compliance badge">&lsaquo;</button>
                            <button class="carousel-control" type="button" data-compliance-next aria-label="Next compliance badge">&rsaquo;</button>
                        </div>
                    </div>
                </div>
                <div class="compliance-marquee" aria-roledescription="carousel" aria-label="Research compliance alignment areas">
                    <div class="compliance-track" data-compliance-track role="list">
                        @foreach ($complianceBadges as $badge)
                            <article class="compliance-badge" data-compliance-slide role="listitem">
                                <span class="badge-mark">
                                    <img src="{{ asset('images/medisana/compliance/' . $badge['icon']) }}" alt="{{ $badge['alt'] }}">
                                </span>
                                <div>
                                    <strong>{{ $badge['title'] }}</strong>
                                    <span>{{ $badge['text'] }}</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="carousel-dots" data-compliance-dots aria-label="Compliance carousel position"></div>
                </div>
            </div>
            <div class="wrap grid two">
                <article class="card">
                    <h3>Regulatory Governance</h3>
                    <ul>
                        <li>FDA Good Clinical Practice and applicable 21 CFR clinical trial requirements</li>
                        <li>IRB review, approval, continuing review, and informed consent oversight</li>
                        <li>Investigator CV, medical license, training, financial disclosure, and delegation documentation</li>
                        <li>Regulatory binder or eReg structure for audit-ready essential documents</li>
                        <li>SOP alignment for protocol conduct, safety reporting, documentation, and closeout</li>
                    </ul>
                </article>
                <article class="card">
                    <h3>Governing Agency Alignment</h3>
                    <p>Operations can be aligned with sponsor, CRO, IRB, FDA, HHS/OHRP, HIPAA, and study-specific expectations before study conduct begins.</p>
                    <p>Medisana's clinical foundation supports feasibility and execution when protocol fit, investigator oversight, privacy requirements, and study procedures are aligned.</p>
                </article>
            </div>
        </section>
        @endif

        @if ($isPage('sponsors'))
        <section id="portal" class="band white">
            <div class="wrap portal-gateway">
                <div class="portal-grid">
                    <div>
                        <p class="eyebrow">Separate secure portal</p>
                        <h2>Medisana's public website stands apart from protected research operations.</h2>
                        <p>Approved users can sign in to SynNexus as a separate secure portal for prescreening, feasibility files, regulatory tasks, budget items, participant workflows, delegation, and sponsor/CRO follow-up.</p>
                        <div class="platform-metrics" aria-label="SynNexus platform value metrics">
                            <div class="platform-metric">
                                <strong>1</strong>
                                <span>Source of truth</span>
                            </div>
                            <div class="platform-metric">
                                <strong>24/7</strong>
                                <span>Status visibility</span>
                            </div>
                            <div class="platform-metric">
                                <strong>PHI</strong>
                                <span>Portal workflow</span>
                            </div>
                        </div>
                        <div class="portal-list" aria-label="SynNexus protected workflow examples">
                            <span>Protected patient prescreening and eligibility notes</span>
                            <span>Feasibility, startup, regulatory, and study tracking</span>
                            <span>Budget negotiation and activation turnaround visibility</span>
                            <span>Role-based dashboards for approved users</span>
                        </div>
                    </div>
                    <aside class="portal-panel">
                        <div class="portal-panel-content">
                            <div class="synnexus-lockup" aria-label="SynNexus secure research portal">
                                <img src="{{ asset('images/brand/synnexus-logo.png') }}" alt="SynNexus secure research portal">
                            </div>
                            <strong>Protected workflow control center</strong>
                            <p>The portal centralizes protected study information, document exchange, action items, and operational visibility beyond public website email.</p>
                            <div class="platform-rail" aria-label="SynNexus workflow controls">
                                <span>Feasibility and sponsor submissions</span>
                                <span>Budget and startup action items</span>
                                <span>Pending-owner visibility</span>
                                <span>Participant prescreening and study workflow status</span>
                            </div>
                            <div class="form-actions">
                                <a class="button primary" href="{{ $synnexusPortalUrl }}">Access Secure Portal</a>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <section class="band white">
            <div class="wrap split">
                <div>
                    <p class="eyebrow">How inquiries move forward</p>
                    <h2>A simple path for study conversations.</h2>
                </div>
                <ol class="timeline">
                    <li>
                        <strong>Initial research inquiry</strong>
                        Sponsor, CRO, physician, or community contact shares high-level, non-PHI information.
                    </li>
                    <li>
                        <strong>Secure portal intake</strong>
                        Protected prescreening, feasibility files, regulatory documents, and study workflows move into the separate SynNexus portal.
                    </li>
                    <li>
                        <strong>Site activation and study execution</strong>
                        Once approvals are granted, recruitment, consent, visits, documentation, and closeout follow the approved protocol.
                    </li>
                </ol>
            </div>
        </section>
        @endif

        @if ($isPage('careers'))
        <section id="careers" class="band dark">
            <div class="wrap section-head">
                <h2>Employment and research team opportunities.</h2>
                <p>As approved studies open, Medisana Research Center can build a research-ready team around patient-facing coordination, CNS assessments, regulatory documentation, recruitment support, and protocol execution.</p>
            </div>
            <figure class="wrap section-visual banner">
                <a class="section-visual-link" href="#career-interest" aria-label="Explore Medisana Research Center career opportunities">
                    <img
                        src="{{ asset('images/medisana/wide_professional_recruitment_poster_for_medisana.png') }}"
                        alt="Join the Medisana Research Center team and help shape the future of clinical research"
                        width="1536"
                        height="1024"
                        loading="eager"
                        decoding="async">
                </a>
            </figure>
            <div class="wrap employment-roles" aria-label="Research employment opportunity areas">
                <article class="role-card">
                    <span>Coordination</span>
                    <strong>Clinical Research Coordinator</strong>
                    <p>Participant scheduling, visit workflows, source documentation, query support, and protocol task tracking.</p>
                </article>
                <article class="role-card">
                    <span>CNS studies</span>
                    <strong>Qualified Raters</strong>
                    <p>Study-specific scale administration, rater training, delegation support, and assessment consistency.</p>
                </article>
                <article class="role-card">
                    <span>Startup</span>
                    <strong>Regulatory Support</strong>
                    <p>CVs, licenses, GCP training, delegation logs, essential documents, and binder readiness.</p>
                </article>
                <article class="role-card">
                    <span>Community</span>
                    <strong>Recruitment Outreach</strong>
                    <p>Approved outreach support, patient education, referral coordination, and community study awareness.</p>
                </article>
            </div>
            <div class="wrap pathway-board" style="margin-top: 28px;">
                <div class="pathway-panel">
                    <p class="eyebrow">Hiring readiness</p>
                    <h3>Build a research talent bench before studies activate.</h3>
                    <p>Employment interest centers on professional background, availability, training readiness, and role fit before study-specific responsibilities, delegation, credentialing, and onboarding are finalized.</p>
                    <div class="intake-blueprint">
                        <div class="intake-step">
                            <strong>Core onboarding</strong>
                            <span>Resume/CV review, credentials, role fit, availability, and location preference.</span>
                        </div>
                        <div class="intake-step">
                            <strong>Training readiness</strong>
                            <span>GCP, human-subject protection, HIPAA, protocol-specific training, and documentation standards.</span>
                        </div>
                        <div class="intake-step">
                            <strong>Delegation alignment</strong>
                            <span>Investigator-approved tasks, delegation log assignment, sponsor/CRO expectations, and supervision pathway.</span>
                        </div>
                    </div>
                </div>
                <form id="career-interest" class="public-form" data-mailto-form data-recipient="info@medisana-health.com" data-subject="Research Employment Interest">
                    <h3>Employment Interest</h3>
                    <p>Share background, credentials, and area of interest for future research-team opportunities.</p>
                    <div class="form-grid two">
                        <div class="field">
                            <label for="career_name">Name</label>
                            <input id="career_name" name="Name" type="text" autocomplete="name">
                        </div>
                        <div class="field">
                            <label for="career_email">Email</label>
                            <input id="career_email" name="Email" type="email" autocomplete="email">
                        </div>
                        <div class="field">
                            <label for="career_phone">Phone</label>
                            <input id="career_phone" name="Phone" type="tel" autocomplete="tel">
                        </div>
                        <div class="field">
                            <label for="career_role">Area of interest</label>
                            <select id="career_role" name="Area of interest">
                                <option value="">Select one</option>
                                <option>Clinical Research Coordinator</option>
                                <option>Qualified CNS Rater</option>
                                <option>Regulatory Support</option>
                                <option>Recruitment Outreach</option>
                                <option>Other research support</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="field">
                            <label for="career_background">Relevant background</label>
                            <textarea id="career_background" name="Relevant background" placeholder="Share credentials, research experience, language skills, or availability."></textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button class="button primary" type="submit">Email Interest</button>
                        <a class="button dark" href="mailto:info@medisana-health.com?subject=Research%20Employment%20Interest">Open Email</a>
                    </div>
                    <span class="form-note">Submitting interest does not create employment, delegation, or study authorization.</span>
                </form>
            </div>
        </section>
        @endif

        @if ($isPage('contact'))
        <section id="contact" class="band">
            <div class="wrap split">
                <div>
                    <p class="eyebrow">Contact</p>
                    <h2>Connect with Medisana Research Center.</h2>
                    <p class="lead">For general research questions, sponsor/CRO conversations, and clinic-to-research coordination across the Medisana network.</p>
                </div>
                <aside class="contact-panel">
                    <h3>Medisana Research Center</h3>
                    <p>Owned and operated by Medisana Health Center</p>
                    <div class="contact-list">
                        <span>5391 NW 36 Street, Miami Springs, FL 33166</span>
                        <a href="tel:+17866361310">(786) 636-1310</a>
                        <a href="mailto:info@medisana-health.com">info@medisana-health.com</a>
                        <a href="https://medisana-health.com/">medisana-health.com</a>
                    </div>
                </aside>
            </div>
            <div class="wrap contact-map-panel" aria-label="Clinic network map">
                <div class="contact-map-copy">
                    <p class="eyebrow">Clinic network map</p>
                    <h3>Miami Springs, Flagler, and Homestead access points.</h3>
                    <p>Medisana Research Center coordinates sponsor, participant, and site conversations across clinic areas anchored by the Miami Springs headquarters.</p>
                </div>
                <div class="clinic-map" aria-label="Map-style view of the Medisana clinic network">
                    <a class="map-pin miami-springs" href="https://www.google.com/maps/search/?api=1&query=5391%20NW%2036%20Street%2C%20Miami%20Springs%2C%20FL%2033166" target="_blank" rel="noopener">
                        <strong>Miami Springs</strong>
                        <span>5391 NW 36 Street</span>
                    </a>
                    <a class="map-pin flagler" href="https://www.google.com/maps/search/?api=1&query=11200%20West%20Flagler%20Street%2C%20Miami%2C%20FL%2033174" target="_blank" rel="noopener">
                        <strong>Flagler</strong>
                        <span>11200 West Flagler Street</span>
                    </a>
                    <div class="map-pin homestead">
                        <strong>Homestead</strong>
                        <span>Clinic access point</span>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </main>

    <footer>
        <div class="wrap">
            <span>&copy; {{ date('Y') }} Medisana Research Center.</span>
            <span>General information only. Not medical advice or study enrollment confirmation.</span>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if ($isHome)
                const legacyHashRoutes = {
                    '#participants': @json($medisanaUrl('patients')),
                    '#currently-enrolling': @json($medisanaUrl('patients', 'currently-enrolling')),
                    '#participant-interest': @json($medisanaUrl('patients', 'participant-interest')),
                    '#sponsors': @json($medisanaUrl('sponsors')),
                    '#execution': @json($medisanaUrl('sponsors', 'execution')),
                    '#portal': @json($medisanaUrl('sponsors', 'portal')),
                    '#clinic-network': @json($medisanaUrl('capabilities')),
                    '#investigators': @json($medisanaUrl('capabilities', 'investigators')),
                    '#readiness': @json($medisanaUrl('readiness')),
                    '#careers': @json($medisanaUrl('careers')),
                    '#contact': @json($medisanaUrl('contact')),
                };
                const legacyTarget = legacyHashRoutes[window.location.hash];

                if (legacyTarget) {
                    window.location.replace(legacyTarget);
                    return;
                }
            @endif

            const revealItems = document.querySelectorAll([
                '.proof-item',
                '.section-head',
                '.audience-intro',
                '.audience-card',
                '.compliance-carousel',
                '.compliance-badge',
                '.site-capability-main',
                '.capability-card',
                '.specialty-highlight',
                '.location-card',
                '.capability-pill',
                '.leadership-panel',
                '.investigator-card',
                '.research-video',
                '.study-journey',
                '.pathway-panel',
                '.public-form',
                '.role-card',
                '.card',
                '.notice',
                '.timeline li',
                '.contact-panel',
                '.contact-map-panel',
                '.map-pin',
            ].join(','));

            if (!('IntersectionObserver' in window)) {
                revealItems.forEach((item) => item.classList.add('is-visible'));
            } else {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) {
                            return;
                        }

                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    });
                }, {
                    rootMargin: '0px 0px -10% 0px',
                    threshold: 0.12,
                });

                revealItems.forEach((item, index) => {
                    item.classList.add('lux-reveal');
                    item.style.setProperty('--reveal-delay', `${Math.min((index % 4) * 80, 240)}ms`);
                    observer.observe(item);
                });
            }

            const capabilityDetails = Array.from(document.querySelectorAll('[data-capability-detail]'));
            const openCapabilityById = (id) => {
                if (!id) {
                    return;
                }

                const target = document.getElementById(id);

                if (!target || !target.matches('[data-capability-detail]')) {
                    return;
                }

                capabilityDetails.forEach((detail) => {
                    detail.open = detail === target;
                });
            };

            capabilityDetails.forEach((detail) => {
                detail.addEventListener('toggle', () => {
                    if (!detail.open) {
                        return;
                    }

                    capabilityDetails.forEach((otherDetail) => {
                        if (otherDetail !== detail) {
                            otherDetail.open = false;
                        }
                    });
                });
            });

            openCapabilityById(decodeURIComponent(window.location.hash.slice(1)));
            window.addEventListener('hashchange', () => {
                openCapabilityById(decodeURIComponent(window.location.hash.slice(1)));
            });

            document.querySelectorAll('[data-compliance-carousel]').forEach((carousel) => {
                const track = carousel.querySelector('[data-compliance-track]');
                const slides = Array.from(carousel.querySelectorAll('[data-compliance-slide]'));
                const dots = carousel.querySelector('[data-compliance-dots]');
                const previous = carousel.querySelector('[data-compliance-prev]');
                const next = carousel.querySelector('[data-compliance-next]');
                const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
                let activeIndex = 0;
                let intervalId = null;

                if (!track || slides.length === 0) {
                    return;
                }

                const visibleCount = () => {
                    if (window.matchMedia('(max-width: 560px)').matches) {
                        return 1;
                    }

                    if (window.matchMedia('(max-width: 920px)').matches) {
                        return 2;
                    }

                    return 3;
                };

                const maxIndex = () => Math.max(slides.length - visibleCount(), 0);

                const stop = () => {
                    if (intervalId !== null) {
                        window.clearInterval(intervalId);
                        intervalId = null;
                    }
                };

                const start = () => {
                    stop();

                    if (reduceMotion.matches || maxIndex() === 0) {
                        return;
                    }

                    intervalId = window.setInterval(() => {
                        goTo(activeIndex + 1);
                    }, 3200);
                };

                const renderDots = () => {
                    if (!dots) {
                        return;
                    }

                    dots.replaceChildren();

                    for (let index = 0; index <= maxIndex(); index += 1) {
                        const dot = document.createElement('button');
                        dot.className = 'carousel-dot';
                        dot.type = 'button';
                        dot.setAttribute('aria-label', `Show compliance badge group ${index + 1}`);
                        dot.addEventListener('click', () => {
                            goTo(index);
                            start();
                        });
                        dots.appendChild(dot);
                    }
                };

                function goTo(index) {
                    const lastIndex = maxIndex();
                    activeIndex = index > lastIndex ? 0 : index < 0 ? lastIndex : index;
                    const offset = slides[activeIndex].offsetLeft - slides[0].offsetLeft;
                    track.style.transform = `translateX(-${offset}px)`;

                    if (dots) {
                        dots.querySelectorAll('.carousel-dot').forEach((dot, dotIndex) => {
                            dot.classList.toggle('is-active', dotIndex === activeIndex);
                            dot.setAttribute('aria-current', dotIndex === activeIndex ? 'true' : 'false');
                        });
                    }
                }

                previous?.addEventListener('click', () => {
                    goTo(activeIndex - 1);
                    start();
                });

                next?.addEventListener('click', () => {
                    goTo(activeIndex + 1);
                    start();
                });

                carousel.addEventListener('mouseenter', stop);
                carousel.addEventListener('mouseleave', start);
                carousel.addEventListener('focusin', stop);
                carousel.addEventListener('focusout', start);
                window.addEventListener('resize', () => {
                    renderDots();
                    goTo(Math.min(activeIndex, maxIndex()));
                    start();
                });
                reduceMotion.addEventListener?.('change', start);

                renderDots();
                goTo(0);
                start();
            });

            document.querySelectorAll('[data-mailto-form]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();

                    const recipient = form.dataset.recipient || 'info@medisana-health.com';
                    const subject = form.dataset.subject || 'Medisana Research Center Inquiry';
                    const formData = new FormData(form);
                    const lines = [];

                    formData.forEach((value, key) => {
                        const cleanValue = String(value).trim();

                        if (cleanValue === '') {
                            return;
                        }

                        lines.push(`${key}: ${cleanValue}`);
                    });

                    lines.push('');
                    lines.push('Please do not include private health information in general email.');

                    const mailto = `mailto:${recipient}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(lines.join('\n'))}`;
                    window.location.href = mailto;
                });
            });
        });
    </script>
</body>
</html>
