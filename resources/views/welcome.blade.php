<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Global Synergia Group turns ideas, workflow problems, compliance gaps, and growth goals into SynNexus-powered operating systems across construction, finance, healthcare, clinical research, surety, and logistics.">
    <title>Global Synergia Group | SynNexus Command Center</title>
    <style>
        :root {
            --ink: #111820;
            --text: #27323d;
            --muted: #64717d;
            --canvas: #f4f6f8;
            --surface: #ffffff;
            --surface-muted: #eef3f6;
            --line: #d9e0e7;
            --line-strong: #bfcad4;
            --gold: #b9934b;
            --gold-soft: #f6ecd2;
            --teal: #0d7465;
            --rose: #9c3f59;
            --blue: #315f8f;
            --shadow: rgba(20, 32, 43, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: var(--canvas);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.5;
        }

        a {
            color: inherit;
        }

        .topbar {
            align-items: center;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(17, 24, 32, 0.1);
            box-shadow: 0 12px 30px rgba(20, 32, 43, 0.08);
            display: flex;
            gap: 24px;
            justify-content: space-between;
            left: 0;
            padding: 18px 40px;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 20;
        }

        .brand {
            align-items: center;
            display: inline-flex;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo {
            display: block;
            height: 46px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 10px 28px rgba(0, 0, 0, 0.18));
        }

        .brand-logo--synnexus {
            height: 28px;
        }

        .nav {
            align-items: center;
            display: flex;
            gap: 18px;
        }

        .nav a {
            color: var(--muted);
            font-size: 14px;
            font-weight: 760;
            text-decoration: none;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .button {
            align-items: center;
            border: 1px solid var(--line-strong);
            border-radius: 8px;
            display: inline-flex;
            font-weight: 700;
            justify-content: center;
            min-height: 44px;
            padding: 11px 18px;
            text-decoration: none;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .button:hover {
            transform: translateY(-1px);
        }

        .button.primary {
            background: var(--ink);
            border-color: var(--ink);
            box-shadow: 0 12px 24px rgba(20, 32, 43, 0.16);
            color: #fff;
        }

        .button.gold {
            background: var(--gold);
            border-color: var(--gold);
            color: #fff;
            box-shadow: 0 14px 28px rgba(185, 147, 75, 0.24);
        }

        .hero {
            background:
                linear-gradient(180deg, #ffffff 0%, #f7f9fb 54%, var(--canvas) 100%);
            color: var(--ink);
            min-height: 76vh;
            overflow: hidden;
            padding: 130px 40px 52px;
        }

        .hero-inner {
            margin: 0 auto;
            max-width: 1180px;
        }

        .hero-preview {
            aspect-ratio: 16 / 5;
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 24px 70px rgba(20, 32, 43, 0.16);
            margin-top: 34px;
            max-height: 340px;
            overflow: hidden;
        }

        .hero-preview img {
            display: block;
            height: 100%;
            object-fit: cover;
            object-position: center;
            width: 100%;
        }

        .hero-content {
            max-width: 820px;
        }

        .eyebrow {
            color: var(--gold);
            font-size: 13px;
            font-weight: 800;
            margin: 0 0 12px;
            text-transform: uppercase;
        }

        h1 {
            font-size: 56px;
            line-height: 1.03;
            margin: 0;
            max-width: 760px;
            overflow-wrap: break-word;
        }

        .hero p {
            color: var(--muted);
            font-size: 19px;
            max-width: 680px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }

        .band {
            padding: 58px 40px;
            scroll-margin-top: 96px;
        }

        .band.white {
            background: var(--surface);
        }

        .wrap {
            margin: 0 auto;
            max-width: 1180px;
        }

        .section-head {
            align-items: end;
            display: grid;
            gap: 20px;
            grid-template-columns: 1fr 420px;
            margin-bottom: 28px;
        }

        h2 {
            font-size: 34px;
            line-height: 1.14;
            margin: 0;
        }

        .section-head p,
        .lead {
            color: var(--muted);
            margin: 0;
        }

        .grid {
            display: grid;
            gap: 16px;
        }

        .grid.three {
            grid-template-columns: repeat(3, 1fr);
        }

        .grid.two {
            grid-template-columns: repeat(2, 1fr);
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 14px 34px rgba(20, 32, 43, 0.07);
            padding: 22px;
        }

        .card.accent-teal {
            border-top: 4px solid var(--teal);
        }

        .card.accent-gold {
            border-top: 4px solid var(--gold);
        }

        .card.accent-rose {
            border-top: 4px solid var(--rose);
        }

        .card h3 {
            font-size: 20px;
            margin: 0 0 10px;
        }

        .card p,
        .card li {
            color: var(--muted);
        }

        .card ul {
            margin: 14px 0 0;
            padding-left: 20px;
        }

        .product {
            align-items: center;
            display: grid;
            gap: 28px;
            grid-template-columns: 1fr 1fr;
        }

        .product-panel {
            background: var(--ink);
            border-radius: 8px;
            color: #fff;
            padding: 28px;
        }

        .product-panel dl {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(2, 1fr);
            margin: 24px 0 0;
        }

        .product-panel dt {
            color: var(--gold);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .product-panel dd {
            margin: 4px 0 0;
        }

        .logos {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            margin-top: 18px;
        }

        .logos img {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            max-height: 72px;
            max-width: 210px;
            padding: 12px;
        }

        .contact {
            align-items: center;
            display: grid;
            gap: 24px;
            grid-template-columns: 1fr 360px;
        }

        .contact-panel {
            background: var(--ink);
            border-radius: 8px;
            color: #fff;
            padding: 24px;
        }

        .contact-panel a {
            color: var(--gold);
            display: block;
            margin-top: 8px;
            overflow-wrap: anywhere;
        }

        footer {
            background: var(--ink);
            color: rgba(255, 255, 255, .72);
            padding: 26px 40px;
        }

        footer .wrap {
            align-items: center;
            display: flex;
            gap: 16px;
            justify-content: space-between;
        }

        @media (max-width: 920px) {
            .topbar {
                align-items: flex-start;
                flex-direction: column;
                padding: 14px 20px;
                position: static;
            }

            .nav {
                flex-wrap: wrap;
                gap: 12px;
            }

            .hero {
                min-height: 0;
                padding: 40px 22px;
            }

            h1 {
                font-size: 40px;
            }

            .hero p {
                font-size: 17px;
            }

            .hero-preview {
                aspect-ratio: 16 / 8;
                max-height: none;
            }

            .band {
                padding: 42px 22px;
            }

            .section-head,
            .product,
            .contact {
                grid-template-columns: 1fr;
            }

            .grid.three,
            .grid.two {
                grid-template-columns: 1fr;
            }

            footer .wrap {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 520px) {
            .hero-actions,
            .nav {
                align-items: stretch;
                flex-direction: column;
            }

            .button {
                width: 100%;
            }

            h1 {
                font-size: 31px;
                max-width: 340px;
            }

            .hero-content {
                max-width: 100%;
                width: 100%;
            }

            .hero p {
                font-size: 16px;
                max-width: 340px;
                overflow-wrap: break-word;
            }

            .hero-preview {
                aspect-ratio: 4 / 3;
            }

            h2 {
                font-size: 28px;
            }

            .product-panel dl {
                grid-template-columns: 1fr;
            }
        }
        /* Premium industry-navigation homepage */
        :root {
            --ink: #080a0f;
            --charcoal: #12161d;
            --text: #1b232d;
            --muted: #66717d;
            --canvas: #f6f5f2;
            --surface: #ffffff;
            --surface-muted: #eceff2;
            --line: #d9dde2;
            --line-dark: rgba(255, 255, 255, 0.16);
            --gold: #caa45a;
            --gold-soft: #f4e3bd;
            --teal: #13796d;
            --wine: #8c3550;
            --steel: #50677c;
            --shadow: rgba(8, 10, 15, 0.18);
        }

        .topbar {
            background: rgba(8, 10, 15, 0.94);
            border-bottom: 1px solid rgba(202, 164, 90, 0.28);
            box-shadow: none;
            gap: 28px;
            padding: 16px 40px;
            position: sticky;
            z-index: 30;
        }

        .brand {
            min-width: 265px;
        }

        .brand-image {
            background: #04070d;
            border: 1px solid rgba(202, 164, 90, 0.32);
            border-radius: 8px;
            display: block;
            height: 42px;
            overflow: hidden;
        }

        .brand-image img {
            display: block;
            height: 100%;
            object-fit: cover;
            width: 100%;
        }

        .brand-image--gsg {
            width: 46px;
        }

        .brand-image--gsg img {
            object-fit: contain;
            object-position: center;
        }

        .brand-image--synnexus {
            width: 42px;
        }

        .brand-image--synnexus img {
            object-fit: contain;
            object-position: center;
        }

        .nav {
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .nav-item {
            position: relative;
        }

        .nav a:not(.button) {
            color: rgba(255, 255, 255, 0.74);
            font-size: 13px;
            font-weight: 800;
            padding: 6px 0;
            position: relative;
            transition: color 0.24s ease;
        }

        .nav a:not(.button):hover {
            color: #fff;
        }

        .nav > a:not(.button)::after,
        .nav-trigger::after {
            background: var(--gold);
            bottom: 0;
            content: "";
            height: 1px;
            left: 0;
            position: absolute;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.24s ease;
            width: 100%;
        }

        .nav > a:not(.button):hover::after,
        .nav-trigger:hover::after,
        .industry-nav:focus-within .nav-trigger::after {
            transform: scaleX(1);
        }

        .nav-trigger {
            align-items: center;
            display: inline-flex;
            min-height: 44px;
        }

        .industry-menu {
            background: rgba(8, 10, 15, 0.96);
            border: 1px solid rgba(202, 164, 90, 0.28);
            border-radius: 8px;
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.32);
            display: grid;
            gap: 4px;
            min-width: 310px;
            opacity: 0;
            padding: 8px;
            pointer-events: none;
            position: absolute;
            right: 0;
            top: calc(100% + 14px);
            transform: translateY(8px);
            transition: opacity 0.28s ease, transform 0.28s ease, visibility 0.28s ease;
            visibility: hidden;
            z-index: 40;
        }

        .industry-nav:hover .industry-menu,
        .industry-nav:focus-within .industry-menu {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
            visibility: visible;
        }

        .industry-menu a {
            border-radius: 6px;
            color: rgba(255, 255, 255, 0.72);
            display: grid;
            gap: 3px;
            padding: 12px 14px;
            text-transform: none;
        }

        .industry-menu a:hover {
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
        }

        .industry-menu strong {
            color: #fff;
            font-size: 14px;
        }

        .industry-menu span {
            color: rgba(244, 227, 189, 0.76);
            font-size: 12px;
            font-weight: 700;
        }

        .button {
            border: 1px solid var(--line-dark);
            font-weight: 800;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .button:hover {
            box-shadow: 0 14px 30px var(--shadow);
        }

        .button.primary {
            background: var(--gold);
            border-color: var(--gold);
            color: #101216;
        }

        .button.dark {
            background: var(--ink);
            border-color: var(--ink);
            color: #fff;
        }

        .button.light {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.28);
            color: #fff;
        }

        .hero {
            background: var(--ink);
            color: #fff;
            min-height: calc(88vh - 75px);
            padding: 82px 40px 58px;
            position: relative;
        }

        .hero::before {
            background:
                linear-gradient(90deg, rgba(4, 6, 10, 0.94) 0%, rgba(4, 6, 10, 0.72) 45%, rgba(4, 6, 10, 0.56) 100%),
                linear-gradient(180deg, rgba(4, 6, 10, 0.2) 0%, rgba(4, 6, 10, 0.8) 100%);
            content: "";
            inset: 0;
            position: absolute;
            z-index: 1;
        }

        .hero-bg {
            height: 100%;
            inset: 0;
            object-fit: cover;
            object-position: center;
            position: absolute;
            width: 100%;
        }

        .hero-inner {
            display: grid;
            gap: 36px;
            grid-template-columns: minmax(0, 820px);
            position: relative;
            z-index: 2;
        }

        .hero-lockup {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .hero-synnexus {
            background: #04070d;
            border: 1px solid rgba(202, 164, 90, 0.42);
            border-radius: 8px;
            box-shadow: 0 22px 50px rgba(0, 0, 0, 0.42);
            display: block;
            height: 86px;
            overflow: hidden;
            width: 300px;
        }

        .hero-synnexus img {
            display: block;
            height: 100%;
            object-fit: contain;
            object-position: center;
            width: 100%;
        }

        .hero-kicker {
            color: rgba(255, 255, 255, 0.74);
            font-size: 13px;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
        }

        h1 {
            font-size: 64px;
            line-height: 1;
            max-width: 830px;
        }

        .hero p {
            color: rgba(255, 255, 255, 0.82);
            font-size: 20px;
            max-width: 730px;
        }

        .hero-proof {
            border-top: 1px solid rgba(255, 255, 255, 0.18);
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            list-style: none;
            margin: 22px 0 0;
            max-width: 880px;
            padding: 20px 0 0;
        }

        .hero-proof strong {
            color: #fff;
            display: block;
            font-size: 15px;
        }

        .hero-proof span {
            color: rgba(255, 255, 255, 0.7);
            display: block;
            font-size: 14px;
            margin-top: 4px;
        }

        .band {
            padding: 64px 40px;
            scroll-margin-top: 92px;
        }

        .band.dark {
            background: var(--ink);
            color: #fff;
        }

        .section-head {
            gap: 24px;
            grid-template-columns: minmax(0, 1fr) minmax(280px, 440px);
            margin-bottom: 30px;
        }

        h2 {
            font-size: 40px;
            line-height: 1.1;
        }

        .dark .section-head p,
        .dark .lead {
            color: rgba(255, 255, 255, 0.72);
        }

        .industry-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .industry-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 18px 42px rgba(8, 10, 15, 0.08);
            min-height: 250px;
            padding: 22px;
            text-decoration: none;
            transition: transform 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .industry-card:hover {
            border-color: rgba(202, 164, 90, 0.72);
            box-shadow: 0 24px 55px rgba(8, 10, 15, 0.13);
            transform: translateY(-3px);
        }

        .industry-card span {
            color: var(--gold);
            display: block;
            font-size: 12px;
            font-weight: 900;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .industry-card h3 {
            font-size: 21px;
            line-height: 1.15;
            margin: 0 0 12px;
        }

        .industry-card p {
            color: var(--muted);
            margin: 0;
        }

        .platform-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

        .method-band {
            background:
                linear-gradient(135deg, #05070b 0%, #10141b 52%, #080a0f 100%);
            color: #fff;
            position: relative;
        }

        .method-band::before {
            background: linear-gradient(90deg, rgba(202, 164, 90, 0), rgba(202, 164, 90, 0.62), rgba(202, 164, 90, 0));
            content: "";
            height: 1px;
            left: 40px;
            position: absolute;
            right: 40px;
            top: 0;
        }

        .method-shell {
            display: grid;
            gap: 46px;
            grid-template-columns: minmax(280px, 0.62fr) minmax(0, 1fr);
        }

        .method-intro {
            align-self: start;
            position: sticky;
            top: 112px;
        }

        .method-intro .eyebrow {
            color: var(--gold-soft);
        }

        .method-intro h2 {
            color: #fff;
            max-width: 460px;
        }

        .method-intro p {
            color: rgba(255, 255, 255, 0.68);
            font-size: 18px;
            margin: 18px 0 0;
            max-width: 470px;
        }

        .method-signature {
            border-top: 1px solid rgba(202, 164, 90, 0.34);
            color: rgba(244, 227, 189, 0.76);
            font-size: 13px;
            font-weight: 800;
            margin-top: 34px;
            padding-top: 18px;
            text-transform: uppercase;
        }

        .method-grid {
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            display: grid;
        }

        .method-card {
            background: transparent;
            border: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.14);
            color: #fff;
            display: grid;
            gap: 20px;
            grid-template-columns: 68px minmax(190px, 0.46fr) minmax(0, 1fr);
            overflow: hidden;
            padding: 28px 0;
            position: relative;
        }

        .method-card::before {
            background: linear-gradient(90deg, rgba(202, 164, 90, 0.86), rgba(202, 164, 90, 0));
            bottom: -1px;
            content: "";
            height: 1px;
            left: 0;
            opacity: 0;
            position: absolute;
            transform: scaleX(0.18);
            transform-origin: left;
            transition: opacity 0.38s ease, transform 0.38s ease;
            width: 100%;
        }

        .method-card::after {
            background: rgba(255, 255, 255, 0.035);
            content: "";
            inset: 0 -20px;
            opacity: 0;
            position: absolute;
            transition: opacity 0.34s ease;
        }

        .method-card span,
        .method-card h3,
        .method-card p,
        .method-copy {
            position: relative;
            z-index: 1;
        }

        .method-card span {
            color: var(--gold);
            display: block;
            font-size: 12px;
            font-weight: 900;
            margin: 5px 0 0;
            text-transform: uppercase;
        }

        .method-card h3 {
            color: #fff;
            font-size: 24px;
            line-height: 1.12;
            margin: 0;
        }

        .method-copy {
            display: grid;
            gap: 10px;
        }

        .method-tease {
            color: rgba(255, 255, 255, 0.72);
            font-size: 16px;
            margin: 0;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .method-response {
            color: rgba(244, 227, 189, 0.82);
            font-size: 14px;
            line-height: 1.55;
            margin: 0;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transform: translateY(8px);
            transition: max-height 0.42s ease, opacity 0.34s ease, transform 0.34s ease;
        }

        .method-card:hover,
        .method-card:focus {
            outline: none;
        }

        .method-card:hover::before,
        .method-card:focus::before {
            opacity: 1;
            transform: scaleX(1);
        }

        .method-card:hover::after,
        .method-card:focus::after {
            opacity: 1;
        }

        .method-card:hover .method-response,
        .method-card:focus .method-response {
            max-height: 110px;
            opacity: 1;
            transform: translateY(0);
        }

        .method-card:hover .method-tease,
        .method-card:focus .method-tease {
            color: #fff;
            transform: translateX(6px);
        }

        .method-card:focus-visible {
            outline: 2px solid rgba(202, 164, 90, 0.82);
            outline-offset: 5px;
        }

        .platform-item {
            border-top: 1px solid rgba(255, 255, 255, 0.16);
            padding-top: 18px;
        }

        .platform-item h3 {
            color: #fff;
            font-size: 18px;
            margin: 0 0 8px;
        }

        .platform-item p {
            color: rgba(255, 255, 255, 0.72);
            margin: 0;
        }

        .lane-stack {
            display: grid;
            gap: 18px;
        }

        .lane {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 18px 44px rgba(8, 10, 15, 0.07);
            display: grid;
            gap: 26px;
            grid-template-columns: minmax(240px, 0.55fr) minmax(0, 1fr);
            padding: 28px;
            scroll-margin-top: 92px;
        }

        .lane-label {
            color: var(--gold);
            display: block;
            font-size: 12px;
            font-weight: 900;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .lane h3 {
            font-size: 27px;
            line-height: 1.16;
            margin: 0 0 12px;
        }

        .lane p {
            color: var(--muted);
            margin: 0;
        }

        .capability-list {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .capability-list li {
            background: var(--surface-muted);
            border-left: 4px solid var(--steel);
            border-radius: 8px;
            color: #34404c;
            font-weight: 700;
            padding: 12px 14px;
        }

        .lane.healthcare .capability-list li {
            border-left-color: var(--teal);
        }

        .lane.finance .capability-list li,
        .lane.construction .capability-list li {
            border-left-color: var(--gold);
        }

        .lane.surety .capability-list li {
            border-left-color: var(--wine);
        }

        .contact {
            gap: 34px;
        }

        .contact-panel {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #fff;
            padding: 26px;
        }

        .contact-panel h3 {
            margin: 0 0 6px;
        }

        .contact-panel p {
            color: rgba(255, 255, 255, 0.72);
            margin: 0 0 14px;
        }

        .contact-panel a {
            color: var(--gold-soft);
        }

        .founder-grid {
            align-items: stretch;
            display: grid;
            gap: 28px;
            grid-template-columns: minmax(0, 1fr) 360px;
        }

        .founder-story {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 18px 44px rgba(8, 10, 15, 0.07);
            padding: 34px;
        }

        .founder-story h2 {
            max-width: 780px;
        }

        .founder-story p {
            color: var(--muted);
            font-size: 18px;
            margin: 18px 0 0;
            max-width: 860px;
        }

        .founder-portrait {
            background:
                linear-gradient(145deg, rgba(8, 10, 15, 0.92), rgba(18, 22, 29, 0.98)),
                url("{{ asset('images/brand/global-synergia-group-logo.png') }}");
            background-position: center;
            background-size: cover;
            border: 1px solid rgba(202, 164, 90, 0.32);
            border-radius: 8px;
            color: #fff;
            min-height: 390px;
            overflow: hidden;
            padding: 28px;
            position: relative;
        }

        .founder-portrait img {
            height: 100%;
            inset: 0;
            object-fit: cover;
            object-position: center;
            position: absolute;
            width: 100%;
        }

        .founder-monogram {
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            height: 100%;
            justify-content: flex-end;
            min-height: 330px;
            padding: 24px;
            position: relative;
            text-align: center;
            z-index: 1;
        }

        .founder-monogram strong {
            color: var(--gold);
            display: block;
            font-size: 64px;
            line-height: 1;
        }

        .founder-monogram span {
            color: rgba(255, 255, 255, 0.72);
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .founder-note {
            background: rgba(8, 10, 15, 0.86);
            border: 1px solid rgba(202, 164, 90, 0.28);
            border-radius: 8px;
            bottom: 22px;
            color: #fff;
            left: 22px;
            margin: 0;
            padding: 16px;
            position: absolute;
            right: 22px;
            z-index: 2;
        }

        .proof-list {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            list-style: none;
            margin: 26px 0 0;
            padding: 0;
        }

        .proof-list li {
            background: var(--surface-muted);
            border-left: 4px solid var(--gold);
            border-radius: 8px;
            color: #34404c;
            font-weight: 700;
            padding: 14px 16px;
        }

        .solution-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .solution-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 8px;
            padding: 24px;
        }

        .solution-card h3 {
            color: #fff;
            font-size: 20px;
            margin: 0 0 10px;
        }

        .solution-card p {
            color: rgba(255, 255, 255, 0.72);
            margin: 0;
        }

        .solution-cta {
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.16);
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 24px;
        }

        .solution-cta p {
            color: rgba(255, 255, 255, 0.72);
            margin: 0;
            max-width: 720px;
        }

        @media (prefers-reduced-motion: no-preference) {
            .hero-inner {
                animation: reveal-up 0.9s cubic-bezier(0.2, 0.7, 0.2, 1) both;
            }

            .industry-card,
            .card,
            .lane,
            .solution-card,
            .founder-story,
            .founder-portrait {
                transition: border-color 0.34s ease, box-shadow 0.34s ease, transform 0.34s ease;
            }

            .industry-card:hover,
            .card:hover,
            .lane:hover,
            .solution-card:hover {
                border-color: rgba(202, 164, 90, 0.62);
                box-shadow: 0 26px 58px rgba(8, 10, 15, 0.13);
                transform: translateY(-3px);
            }

            @keyframes reveal-up {
                from {
                    opacity: 0;
                    transform: translateY(18px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }

        .lane.healthcare .capability-list li,
        .lane.research .capability-list li {
            border-left-color: var(--teal);
        }

        .lane.logistics .capability-list li {
            border-left-color: var(--steel);
        }

        footer {
            background: #05070b;
            color: rgba(255, 255, 255, 0.66);
        }

        @media (max-width: 1080px) {
            .industry-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .platform-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .solution-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 920px) {
            .topbar {
                position: sticky;
            }

            .brand {
                min-width: 0;
            }

            .nav {
                justify-content: flex-start;
            }

            .hero {
                min-height: 66vh;
                padding: 54px 22px 46px;
            }

            .hero-synnexus {
                height: 74px;
                width: 260px;
            }

            h1 {
                font-size: 44px;
            }

            .hero p {
                font-size: 18px;
            }

            .hero-proof,
            .method-shell,
            .section-head,
            .lane,
            .founder-grid,
            .contact {
                grid-template-columns: 1fr;
            }

            .method-intro {
                position: static;
            }

            .method-card {
                grid-template-columns: 54px minmax(170px, 0.42fr) minmax(0, 1fr);
                min-height: 0;
            }

            .method-response {
                max-height: none;
                opacity: 1;
                transform: none;
            }

            .method-tease {
                opacity: 0.62;
            }
        }

        @media (max-width: 620px) {
            .brand {
                align-items: flex-start;
                flex-direction: column;
            }

            .brand-image--gsg,
            .brand-image--synnexus {
                width: 178px;
            }

            .nav {
                align-items: stretch;
                flex-direction: column;
                width: 100%;
            }

            .industry-menu {
                min-width: 0;
                opacity: 1;
                pointer-events: auto;
                position: static;
                transform: none;
                visibility: visible;
                width: 100%;
            }

            .nav .button {
                width: 100%;
            }

            .hero {
                min-height: 0;
            }

            .hero-synnexus {
                height: 66px;
                width: 230px;
            }

            h1 {
                font-size: 34px;
            }

            h2 {
                font-size: 30px;
            }

            .industry-grid,
            .platform-grid,
            .capability-list,
            .proof-list,
            .solution-grid {
                grid-template-columns: 1fr;
            }

            .method-band::before {
                left: 22px;
                right: 22px;
            }

            .method-shell {
                gap: 28px;
            }

            .method-card {
                gap: 10px;
                grid-template-columns: 1fr;
                padding: 24px 0;
            }

            .method-card span {
                margin-top: 0;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="/">
            <span class="brand-image brand-image--gsg">
                <img src="{{ asset('images/brand/GSG%20ICON%20LOGO.png') }}" alt="Global Synergia Group">
            </span>
            <span class="brand-image brand-image--synnexus">
                <img src="{{ asset('images/brand/SynNexus%20Icon%20Logo.png') }}" alt="SynNexus">
            </span>
        </a>
        <nav class="nav" aria-label="Primary navigation">
            <a href="#gsg-method">Method</a>
            <div class="nav-item industry-nav">
                <a class="nav-trigger" href="#industries">Industries</a>
                <div class="industry-menu" aria-label="Industry navigation">
                    <a href="#healthcare-services">
                        <strong>Healthcare</strong>
                        <span>Operations, patients, compliance, and workforce support</span>
                    </a>
                    <a href="#clinical-research">
                        <strong>Clinical Research</strong>
                        <span>Regulatory, source docs, SOPs, budgets, studies, and recruitment</span>
                    </a>
                    <a href="#construction-services">
                        <strong>Construction</strong>
                        <span>Projects, estimates, CNC workflow, and service lead generation</span>
                    </a>
                    <a href="#routepilot-ai">
                        <strong>Logistics</strong>
                        <span>RoutePilot AI, dispatch, routing, and field movement</span>
                    </a>
                    <a href="#finance-capital">
                        <strong>Finance</strong>
                        <span>Funding intake, document readiness, offers, and follow-up</span>
                    </a>
                    <a href="#surety-services">
                        <strong>Surety</strong>
                        <span>Agency prospecting, licensing, outreach, and opportunity tracking</span>
                    </a>
                </div>
            </div>
            <a href="#solution-studio">Solutions</a>
            <a href="#contact">Contact</a>
            @auth
                <a class="button primary" href="{{ route('dashboard') }}">Open Dashboard</a>
            @else
                <a class="button primary" href="{{ route('login') }}">Sign In</a>
            @endauth
        </nav>
    </header>

    <section class="hero">
        <img class="hero-bg" src="{{ asset('images/brand/synergia-group-command-center.png') }}" alt="SynNexus command center dashboard">
        <div class="hero-inner">
            <div class="hero-lockup">
                <span class="hero-synnexus">
                    <img src="{{ asset('images/brand/synnexus-wordmark.png') }}" alt="SynNexus AI-powered CRM and operations platform">
                </span>
                <div>
                    <p class="hero-kicker">Global Synergia Group</p>
                    <p class="eyebrow">Business mapping. SynNexus systems. Operating execution.</p>
                </div>
            </div>

            <div>
                <h1>From idea to operating system.</h1>
                <p>Global Synergia Group turns business ideas, workflow problems, compliance gaps, and growth goals into SynNexus operating systems designed per user, per industry, and to specification.</p>
                <div class="hero-actions">
                    <a class="button primary" href="#solution-studio">Start With the Problem</a>
                    <a class="button light" href="#industries">Explore Industry Lanes</a>
                </div>
                <ul class="hero-proof">
                    <li>
                        <strong>Map the problem</strong>
                        <span>Ideas, bottlenecks, compliance needs, and scattered operations.</span>
                    </li>
                    <li>
                        <strong>Build the system</strong>
                        <span>Workflows, databases, documents, lead pipelines, and dashboards.</span>
                    </li>
                    <li>
                        <strong>Operate and grow</strong>
                        <span>Lead generation, task control, accounting support, and cloud interfaces.</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section id="gsg-method" class="band method-band">
        <div class="wrap method-shell">
            <div class="method-intro">
                <p class="eyebrow">The GSG Method</p>
                <h2>What Global Synergia Group actually does.</h2>
                <p>GSG is the bridge between a real-world business problem and a working operating system. SynNexus is the engine, but the value starts with mapping the work correctly before anything is built.</p>
                <div class="method-signature">Strategy, systems, demand, and operations under one structure.</div>
            </div>
            <div class="method-grid">
                <article class="method-card" tabindex="0">
                    <span>01</span>
                    <h3>Map the operation</h3>
                    <div class="method-copy">
                        <p class="method-tease">Start with the real work, not a generic template.</p>
                        <p class="method-response">Clarify the idea, issue, roles, documents, compliance needs, growth target, and the real steps required to move the work.</p>
                    </div>
                </article>
                <article class="method-card" tabindex="0">
                    <span>02</span>
                    <h3>Build the SynNexus structure</h3>
                    <div class="method-copy">
                        <p class="method-tease">Turn the map into a system people can actually use.</p>
                        <p class="method-response">Create intake paths, stages, records, automations, dashboards, permissions, and accountable next actions inside SynNexus.</p>
                    </div>
                </article>
                <article class="method-card" tabindex="0">
                    <span>03</span>
                    <h3>Generate and manage demand</h3>
                    <div class="method-copy">
                        <p class="method-tease">Make growth visible, trackable, and easier to follow through.</p>
                        <p class="method-response">Connect lead capture, outreach, qualification, follow-up, campaigns, and industry databases to real execution.</p>
                    </div>
                </article>
                <article class="method-card" tabindex="0">
                    <span>04</span>
                    <h3>Support the back office</h3>
                    <div class="method-copy">
                        <p class="method-tease">Keep the operational details tied to the same command center.</p>
                        <p class="method-response">Thread accounting, payroll, employees, licenses, permits, training, cloud workspaces, and documents into the operating model.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section id="industries" class="band white">
        <div class="wrap">
            <div class="section-head">
                <h2>Choose the industry. GSG builds the operating lane behind it.</h2>
                <p>Each lane can present the public offer, generate leads, manage the workflow, use the shared accounting module, and route the work into SynNexus for execution.</p>
            </div>
            <div class="industry-grid">
                <a class="industry-card" href="#healthcare-services">
                    <span>01</span>
                    <h3>Healthcare</h3>
                    <p>Patient outreach, referral tracking, credentialing, compliance tasks, staff records, training, and operational databases.</p>
                </a>
                <a class="industry-card" href="#clinical-research">
                    <span>02</span>
                    <h3>Clinical Research</h3>
                    <p>Regulatory activity, source documents, SOPs, study tracking, physician-site matching, recruitment, and trial workflows.</p>
                </a>
                <a class="industry-card" href="#construction-services">
                    <span>03</span>
                    <h3>Construction</h3>
                    <p>Project intake, estimating, remodeling workflows, CNC quote intake, and lead generation for services.</p>
                </a>
                <a class="industry-card" href="#routepilot-ai">
                    <span>04</span>
                    <h3>Logistics</h3>
                    <p>RoutePilot AI, route planning, dispatch workflows, delivery windows, field activity, and productivity visibility.</p>
                </a>
                <a class="industry-card" href="#finance-capital">
                    <span>05</span>
                    <h3>Finance</h3>
                    <p>Funding lead generation, borrower intake, document readiness, offer tracking, pipeline control, and deal follow-up.</p>
                </a>
                <a class="industry-card" href="#surety-services">
                    <span>06</span>
                    <h3>Surety</h3>
                    <p>Agency prospecting, licensing records, outreach queues, bond opportunity tracking, renewals, and follow-up workflows.</p>
                </a>
            </div>
        </div>
    </section>

    <section id="accounting-operations" class="band">
        <div class="wrap">
            <div class="section-head">
                <h2>Every industry lane can use the accounting module.</h2>
                <p>The same operating system can support payroll, automated itemization of expenses, automated bookkeeping services, employee tracking, licenses, permitting, training, and cloud-connected financial records.</p>
            </div>
            <div class="grid three">
                <article class="card accent-gold">
                    <h3>Automated Expense Itemization</h3>
                    <p>Structure receipts, vendor charges, project costs, reimbursements, and recurring expenses into clean review categories.</p>
                </article>
                <article class="card accent-teal">
                    <h3>Automated Bookkeeping Services</h3>
                    <p>Keep operating records organized by industry, project, study, route, agency, employee, or department for cleaner reporting.</p>
                </article>
                <article class="card accent-rose">
                    <h3>Payroll Services</h3>
                    <p>Support payroll workflows, worker assignments, time and activity tracking, pay-cycle readiness, and operating reports.</p>
                </article>
                <article class="card accent-teal">
                    <h3>Employee Tracking</h3>
                    <p>Maintain team records, role assignments, training status, onboarding tasks, credentials, and active work responsibilities.</p>
                </article>
                <article class="card accent-gold">
                    <h3>Licenses, Permits, and Training</h3>
                    <p>Track licensing, permitting, renewals, certifications, required training, and compliance follow-up across each business line.</p>
                </article>
                <article class="card accent-rose">
                    <h3>Cloud Interfaces</h3>
                    <p>Connect operating records with cloud storage, documents, dashboards, financial exports, and shared team workspaces.</p>
                </article>
            </div>
        </div>
    </section>

    <section id="synnexus-platform" class="band dark">
        <div class="wrap">
            <div class="section-head">
                <h2>SynNexus is the operating engine behind every lane.</h2>
                <p>SynNexus handles the work behind the offer: capturing leads, managing activities, tracking documents, supporting accounting workflows, and adapting the operating model to each user specification.</p>
            </div>
            <div class="platform-grid">
                <div class="platform-item">
                    <h3>Lead Capture</h3>
                    <p>Forms, imports, public lists, partner data, and campaign sources become structured lead records.</p>
                </div>
                <div class="platform-item">
                    <h3>Workflow Management</h3>
                    <p>Each industry gets its own stages, assignments, documents, statuses, and next actions.</p>
                </div>
                <div class="platform-item">
                    <h3>Productivity Control</h3>
                    <p>Teams can see priority work, overdue items, conversion activity, and operational bottlenecks.</p>
                </div>
                <div class="platform-item">
                    <h3>Database Intelligence</h3>
                    <p>Every lane can build searchable records, history, documents, metrics, and reporting over time.</p>
                </div>
                <div class="platform-item">
                    <h3>Accounting Operations</h3>
                    <p>Expenses, itemization, payroll, employee records, licenses, permits, training, and cloud interfaces connect to the same operating layer.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="founder" class="band white">
        <div class="wrap founder-grid">
            <div class="founder-story">
                <p class="eyebrow">Founder-led systems</p>
                <h2>Built by Jessica Palacio for people turning ideas into working operations.</h2>
                <p>Global Synergia Group and SynNexus were built through a year of mapping real business pressure: scattered leads, unfinished workflows, compliance demands, documents, accounting needs, people management, and the constant chase to make operations stronger.</p>
                <p>This is not only software. It is the ability to listen to an idea or problem, break it down, organize the moving parts, and turn it into a system that helps another person or company become more productive, visible, and prepared to grow.</p>
                <ul class="proof-list">
                    <li>Business process mapping from messy operations</li>
                    <li>Workflow design across multiple industries</li>
                    <li>Lead generation tied to real follow-up action</li>
                    <li>Documents, compliance, accounting, and databases in one operating model</li>
                </ul>
            </div>
            <aside class="founder-portrait" aria-label="Jessica Palacio founder profile">
                @if (file_exists(public_path('images/brand/jessica-palacio.jpg')))
                    <img src="{{ asset('images/brand/jessica-palacio.jpg') }}" alt="Jessica Palacio">
                @else
                    <div class="founder-monogram">
                        <strong>JP</strong>
                        <span>Founder and systems builder</span>
                    </div>
                @endif
                <p class="founder-note">Jessica Palacio, Founder &amp; Director of Operations. Built for operators who need ideas, issues, and ambition translated into systems that work.</p>
            </aside>
        </div>
    </section>

    <section id="industry-lanes" class="band">
        <div class="wrap">
            <div class="section-head">
                <h2>What each organization can provide.</h2>
                <p>Each business line has its own offer, but the same system discipline applies: map the work, capture the lead, track the stage, manage the back office, connect accounting, and keep the next action visible.</p>
            </div>

            <div class="lane-stack">
                <article id="healthcare-services" class="lane healthcare">
                    <div>
                        <span class="lane-label">Healthcare operations</span>
                        <h3>Healthcare workflows for patients, providers, compliance, and staff.</h3>
                        <p>Use this lane to organize the operational side of healthcare: patient outreach, referrals, provider onboarding, credentialing, employee records, training, documents, and healthcare databases.</p>
                    </div>
                    <ul class="capability-list">
                        <li>Patient outreach and referral tracking</li>
                        <li>Provider and practice onboarding workflows</li>
                        <li>Credentialing, licenses, training, and employee tracking</li>
                        <li>Healthcare document and cloud workspace coordination</li>
                        <li>Patient, provider, vendor, and workforce databases</li>
                        <li>Healthcare accounting, payroll, and bookkeeping support</li>
                    </ul>
                </article>

                <article id="clinical-research" class="lane research">
                    <div>
                        <span class="lane-label">Clinical research</span>
                        <h3>Research operations from startup through study activity tracking.</h3>
                        <p>Support sites, physicians, sponsors, and research teams with regulatory activity, customized documents, SOPs, budgets, recruitment, study tracking, and databases built around the actual trial workflow.</p>
                    </div>
                    <ul class="capability-list">
                        <li>Automated regulatory activities</li>
                        <li>Investigator and coordinator CV templates with credential packet readiness</li>
                        <li>Customized source document creation and implementation</li>
                        <li>Customized SOP creation and implementation</li>
                        <li>Contract and budget negotiation tracking</li>
                        <li>Ongoing study activities and milestone tracking</li>
                        <li>Physician matching with sites entering clinical research</li>
                        <li>Patient recruitment and lead generation</li>
                        <li>Feasibility, startup, sponsor, vendor, and site communication tracking</li>
                        <li>Clinical research database workflows and reporting</li>
                        <li>Study expense, payroll, license, permit, and training tracking</li>
                    </ul>
                </article>

                <article id="construction-services" class="lane construction">
                    <div>
                        <span class="lane-label">Construction services</span>
                        <h3>Construction, remodeling, CNC production, and service demand.</h3>
                        <p>Use this lane for project inquiries, customer intake, estimating, site visits, remodeling workflows, and K &amp; G Art Designs CNC cutting requests. It also demonstrates how SynNexus can promote services and generate leads for the work already being built.</p>
                        <div class="hero-actions">
                            <a class="button dark" href="{{ route('intake.cnc.create') }}">Start CNC Quote Intake</a>
                        </div>
                    </div>
                    <ul class="capability-list">
                        <li>Project, customer, and lead intake</li>
                        <li>Scope capture, estimates, site visits, and production stages</li>
                        <li>CNC quote workflow for material, finish, tolerance, and delivery details</li>
                        <li>Campaign and follow-up pipelines for service promotion</li>
                        <li>Project documents, vendors, crews, and delivery visibility</li>
                        <li>Job-cost accounting, permits, payroll, bookkeeping, and crew tracking</li>
                    </ul>
                </article>

                <article id="routepilot-ai" class="lane logistics">
                    <div>
                        <span class="lane-label">Logistics and RoutePilot AI</span>
                        <h3>Route intelligence for dispatch, delivery, and field movement.</h3>
                        <p>RoutePilot AI supports organizations that need routing, dispatch workflows, scheduling control, field coordination, delivery visibility, and productivity insight from movement data.</p>
                    </div>
                    <ul class="capability-list">
                        <li>Route planning and optimization</li>
                        <li>Dispatch queues, delivery windows, and field assignments</li>
                        <li>Driver, vehicle, route, customer, and vendor activity tracking</li>
                        <li>Route productivity dashboards and customer updates</li>
                        <li>Delivery issue, exception, and follow-up workflows</li>
                        <li>Route expenses, payroll, licenses, permits, bookkeeping, and driver training</li>
                    </ul>
                </article>

                <article id="finance-capital" class="lane finance">
                    <div>
                        <span class="lane-label">Finance and capital</span>
                        <h3>Funding workflows from lead intake to offer progression.</h3>
                        <p>This lane supports funding opportunities, borrower or business intake, qualification, document readiness, offer comparison, and follow-up across the capital workflow.</p>
                    </div>
                    <ul class="capability-list">
                        <li>Funding lead generation and intake forms</li>
                        <li>Borrower, business, broker, and partner records</li>
                        <li>Document readiness, underwriting stage, and qualification tracking</li>
                        <li>Offer comparison, terms review, and follow-up calendars</li>
                        <li>Pipeline visibility for approvals, denials, and next actions</li>
                        <li>Expense itemization, bookkeeping, payroll support, and employee records</li>
                    </ul>
                </article>

                <article id="surety-services" class="lane surety">
                    <div>
                        <span class="lane-label">Surety and bond services</span>
                        <h3>Agency growth workflows with structured outreach control.</h3>
                        <p>SynNexus can support surety and bond agency lead generation, prospect records, licensing visibility, reviewed outreach, opportunity tracking, renewals, and agency follow-up workflows.</p>
                    </div>
                    <ul class="capability-list">
                        <li>Surety and bond agency prospecting</li>
                        <li>License, agency, contact, and producer records</li>
                        <li>Reviewed outreach queues and follow-up calendars</li>
                        <li>Bond opportunity, renewal, document, and compliance tracking</li>
                        <li>Pipeline visibility for referrals, agencies, clients, and carriers</li>
                        <li>Agency expenses, payroll, bookkeeping, licensing, permits, and training records</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section id="solution-studio" class="band dark">
        <div class="wrap">
            <div class="section-head">
                <h2>Have an idea, issue, or business problem? Bring it here.</h2>
                <p>Some clients know the service they need. Others only know the process is broken, the records are scattered, the team is overwhelmed, or the idea needs structure before it can grow.</p>
            </div>
            <div class="solution-grid">
                <article class="solution-card">
                    <h3>Bring the Idea</h3>
                    <p>New service lines, internal tools, workflows, databases, client portals, or AI-assisted operating models can be mapped into a build plan.</p>
                </article>
                <article class="solution-card">
                    <h3>Bring the Bottleneck</h3>
                    <p>Manual tracking, missed follow-up, document delays, disconnected teams, and messy handoffs can become structured workflows.</p>
                </article>
                <article class="solution-card">
                    <h3>Bring the Compliance Gap</h3>
                    <p>Licenses, permits, training, regulatory tasks, SOPs, study documents, and renewal requirements can be tracked and managed.</p>
                </article>
                <article class="solution-card">
                    <h3>Bring the Growth Goal</h3>
                    <p>Lead generation, outreach, qualification, reporting, accounting visibility, and cloud collaboration can be built around the outcome.</p>
                </article>
            </div>
            <div class="solution-cta">
                <p><strong>Designed per user. Built to specification.</strong> The value is not only having a platform. The value is having someone who can map the work, organize the chaos, and build the structure that lets people move.</p>
                <a class="button primary" href="mailto:jessica@globalsynergiagroup.com?subject=I%20have%20an%20idea%20or%20business%20problem">Bring Me the Problem</a>
            </div>
        </div>
    </section>

    <section id="contact" class="band dark">
        <div class="wrap contact">
            <div>
                <p class="eyebrow">Build the next operating lane</p>
                <h2>Bring the idea or issue. Build the operating system around it.</h2>
                <p class="lead">For a new industry lane, lead generation workflow, accounting operation, research buildout, logistics workflow, finance pipeline, surety process, or SynNexus backend structure, start with the problem and the system can be shaped to specification.</p>
            </div>
            <div class="contact-panel">
                <h3>Jessica Palacio</h3>
                <p>Founder &amp; Director of Operations</p>
                <a href="mailto:jessica@globalsynergiagroup.com">jessica@globalsynergiagroup.com</a>
                @auth
                    <a href="{{ route('dashboard') }}">Open SynNexus Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Sign In to SynNexus</a>
                @endauth
            </div>
        </div>
    </section>

    <footer>
        <div class="wrap">
            <span>&copy; {{ date('Y') }} Global Synergia Group. SynNexus is a Global Synergia Group platform.</span>
            <span>globalsynergiagroup.com</span>
        </div>
    </footer>
</body>
</html>
