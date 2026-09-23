<style>
    :root {
        --ink: #0d1114;
        --ink-2: #182127;
        --text: #202a31;
        --muted: #667078;
        --line: #d7d9d2;
        --line-soft: #e8e5dc;
        --surface: #ffffff;
        --surface-2: #f7f4ed;
        --surface-3: #ebe7dc;
        --canvas: #f1eee7;
        --rail: #0b0f12;
        --rail-2: #11181d;
        --gold: #b9934b;
        --gold-soft: #f0dfb9;
        --teal: #0d7465;
        --blue: #315f8f;
        --rose: #9c3f59;
        --danger: #b42318;
        --shadow: 0 24px 70px rgba(13, 17, 20, .16);
        --shadow-soft: 0 14px 34px rgba(13, 17, 20, .1);
        --ring: 0 0 0 4px rgba(185, 147, 75, .18);
    }

    * {
        box-sizing: border-box;
    }

    html {
        background: var(--canvas);
    }

    body {
        max-width: none;
        min-height: 100vh;
        margin: 0;
        padding: 108px 36px 54px 310px;
        background:
            linear-gradient(90deg, rgba(13, 17, 20, .035) 1px, transparent 1px),
            linear-gradient(180deg, rgba(13, 17, 20, .03) 1px, transparent 1px),
            linear-gradient(135deg, rgba(185, 147, 75, .08), transparent 38%),
            var(--canvas);
        background-size: 34px 34px, 34px 34px, auto, auto;
        color: var(--text);
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
        line-height: 1.5;
    }

    body > h1,
    body > p,
    body > form,
    body > table,
    body > .grid,
    body > .topbar,
    body > main,
    body > aside:not(.sy-rail),
    body > h2,
    body > .card,
    body > pre {
        max-width: 1240px;
        margin-left: auto;
        margin-right: auto;
    }

    h1,
    h2,
    h3 {
        color: var(--ink);
        letter-spacing: 0;
    }

    h1 {
        margin: 0 0 10px;
        font-size: 38px;
        line-height: 1.05;
        font-weight: 850;
    }

    h2 {
        margin-top: 30px;
        font-size: 21px;
        font-weight: 820;
    }

    h3 {
        font-size: 16px;
        font-weight: 820;
    }

    p {
        color: var(--muted);
    }

    a {
        color: var(--teal);
        font-weight: 760;
        text-decoration-thickness: 1px;
        text-underline-offset: 3px;
    }

    pre {
        overflow-x: auto;
        padding: 16px;
        background: #11181d;
        border: 1px solid rgba(185, 147, 75, .32);
        border-radius: 8px;
        color: #f6efe0;
        box-shadow: var(--shadow-soft);
    }

    .sy-rail {
        position: fixed;
        inset: 0 auto 0 0;
        z-index: 30;
        width: 270px;
        padding: 22px 18px;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, .06), transparent 38%),
            linear-gradient(135deg, rgba(185, 147, 75, .12), transparent 46%),
            var(--rail);
        border-right: 1px solid rgba(240, 223, 185, .14);
        color: #f8fafc;
        box-shadow: 18px 0 48px rgba(13, 17, 20, .22);
    }

    .sy-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 58px;
        color: #fff;
        text-decoration: none;
    }

    .sy-brand-logo {
        display: block;
        max-height: 36px;
        width: auto;
        object-fit: contain;
        object-position: center top;
        filter: drop-shadow(0 8px 18px rgba(0, 0, 0, 0.2));
    }

    .sy-brand-logo--gsg {
        max-height: 32px;
        transform: translateY(1px);
    }

    .sy-brand-logo--synnexus {
        max-height: 30px;
    }

    .sy-brand strong,
    .sy-command-bar strong {
        display: block;
        letter-spacing: 0;
    }

    .sy-brand strong {
        font-size: 15px;
    }

    .sy-brand small {
        display: block;
        color: #b9c0c4;
        font-size: 12px;
        margin-top: 2px;
    }

    .sy-nav {
        margin-top: 32px;
        display: grid;
        gap: 9px;
    }

    .sy-nav a {
        position: relative;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 12px;
        align-items: center;
        min-height: 50px;
        padding: 11px 12px 11px 15px;
        border: 1px solid transparent;
        border-radius: 8px;
        color: #dce4e7;
        text-decoration: none;
        font-size: 14px;
        font-weight: 790;
        transition: background .16s ease, border-color .16s ease, color .16s ease, transform .16s ease;
    }

    .sy-nav a::before {
        content: "";
        position: absolute;
        inset: 11px auto 11px 0;
        width: 3px;
        border-radius: 999px;
        background: transparent;
    }

    .sy-nav a:hover,
    .sy-nav a.active {
        background: rgba(255, 255, 255, .075);
        border-color: rgba(240, 223, 185, .18);
        color: #fff;
        transform: translateX(2px);
    }

    .sy-nav a.active::before {
        background: var(--gold);
    }

    .sy-nav small {
        color: #a4afb4;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .sy-command-bar {
        position: fixed;
        top: 0;
        left: 270px;
        right: 0;
        z-index: 25;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        min-height: 78px;
        padding: 14px 36px;
        background: rgba(247, 244, 237, .86);
        border-bottom: 1px solid rgba(13, 17, 20, .12);
        backdrop-filter: blur(18px);
        box-shadow: 0 16px 36px rgba(13, 17, 20, .07);
    }

    .sy-kicker {
        display: block;
        color: var(--muted);
        font-size: 12px;
        font-weight: 850;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    .sy-command-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    .sy-command-actions a,
    .button,
    button,
    .nav a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        border: 1px solid rgba(13, 17, 20, .86);
        border-radius: 8px;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, .08), transparent),
            var(--ink);
        color: #fff;
        padding: 9px 14px;
        font: inherit;
        font-weight: 820;
        text-decoration: none;
        cursor: pointer;
        transition: background .15s ease, border-color .15s ease, transform .15s ease, box-shadow .15s ease;
    }

    .sy-command-actions a {
        min-height: 36px;
        background: rgba(255, 255, 255, .72);
        border-color: rgba(13, 17, 20, .16);
        color: var(--ink);
        font-size: 13px;
    }

    .button:hover,
    button:hover,
    .nav a:hover,
    .sy-command-actions a:hover,
    .actions a:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-soft);
    }

    .nav a.secondary,
    .tabs a,
    .actions a {
        background: rgba(255, 255, 255, .72);
        color: var(--ink);
        border-color: rgba(13, 17, 20, .14);
    }

    .topbar {
        display: grid;
        grid-template-columns: minmax(420px, 1fr) minmax(280px, 620px);
        gap: 24px;
        align-items: start;
        padding: 26px;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, .92), rgba(255, 255, 255, .78)),
            var(--surface);
        border: 1px solid rgba(185, 147, 75, .26);
        border-radius: 8px;
        box-shadow: var(--shadow);
    }

    .topbar p {
        max-width: 760px;
        margin-bottom: 0;
    }

    .topbar h1 {
        max-width: 760px;
    }

    .nav {
        min-width: 300px;
        text-align: right;
    }

    .topbar .nav {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
        min-width: 0;
        max-width: 620px;
    }

    .grid,
    .summary,
    .summary-grid {
        gap: 16px;
    }

    .card,
    .metric,
    .message,
    .queue-panel,
    .summary-card,
    .empty,
    .signature-panel,
    .signature-preview,
    .instructions {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, .96), rgba(255, 255, 255, .88)),
            var(--surface);
        border: 1px solid rgba(13, 17, 20, .12);
        border-radius: 8px;
        box-shadow: var(--shadow-soft);
    }

    .card::before,
    .metric::before,
    .summary-card::before,
    .message::before,
    .queue-panel::before,
    .signature-panel::before,
    .signature-preview::before,
    .instructions::before {
        content: "";
        position: absolute;
        inset: 0 0 auto;
        height: 3px;
        background: linear-gradient(90deg, var(--gold), var(--teal), var(--blue));
    }

    .card .label,
    .label,
    .muted {
        color: var(--muted);
    }

    .label {
        font-size: 12px;
        font-weight: 850;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    .value,
    .summary-card strong,
    .metric strong {
        color: var(--ink);
        font-variant-numeric: tabular-nums;
        font-weight: 880;
    }

    .card:nth-child(4n + 2)::before,
    .metric:nth-child(4n + 2)::before,
    .summary-card:nth-child(4n + 2)::before {
        background: var(--teal);
    }

    .card:nth-child(4n + 3)::before,
    .metric:nth-child(4n + 3)::before,
    .summary-card:nth-child(4n + 3)::before {
        background: var(--blue);
    }

    .card:nth-child(4n + 4)::before,
    .metric:nth-child(4n + 4)::before,
    .summary-card:nth-child(4n + 4)::before {
        background: var(--rose);
    }

    table {
        overflow: hidden;
        border: 1px solid rgba(13, 17, 20, .12);
        border-radius: 8px;
        box-shadow: var(--shadow-soft);
        background: var(--surface);
    }

    th {
        background: #f5f2ea;
        color: #4f5960;
        font-size: 12px;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    td {
        color: #263238;
    }

    th,
    td {
        border-color: var(--line-soft);
    }

    tr:hover td {
        background: #fbfaf7;
    }

    input,
    select,
    textarea {
        border: 1px solid #cfc9bb;
        border-radius: 8px;
        background: rgba(255, 255, 255, .92);
        color: var(--ink);
        font: inherit;
    }

    input:focus,
    select:focus,
    textarea:focus {
        outline: 0;
        border-color: var(--gold);
        box-shadow: var(--ring);
    }

    input[type="file"] {
        padding: 10px;
        background: #fff;
    }

    form {
        background:
            linear-gradient(180deg, rgba(255, 255, 255, .98), rgba(255, 255, 255, .9)),
            var(--surface);
        border: 1px solid rgba(13, 17, 20, .12);
        border-radius: 8px;
        box-shadow: var(--shadow-soft);
        padding: 22px;
    }

    td form,
    .card form,
    .message form,
    .queue-panel form {
        background: transparent;
        border: 0;
        border-radius: 0;
        box-shadow: none;
        padding: 0;
    }

    label {
        color: var(--ink-2);
        font-weight: 800;
    }

    .error {
        color: var(--danger);
    }

    .status,
    .priority {
        border: 1px solid rgba(13, 17, 20, .1);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .62);
    }

    .tabs a.active,
    .button.primary,
    button.primary {
        background:
            linear-gradient(180deg, rgba(255, 255, 255, .08), transparent),
            var(--ink);
        border-color: var(--ink);
        color: #fff;
    }

    dl {
        border-top: 1px solid var(--line-soft);
        padding-top: 14px;
    }

    dt {
        color: var(--muted);
        font-weight: 780;
    }

    dd {
        color: var(--ink-2);
    }

    @media (max-width: 1050px) {
        body {
            padding: 140px 18px 38px;
        }

        .sy-rail {
            position: fixed;
            inset: 0 0 auto;
            width: auto;
            min-height: 78px;
            padding: 12px 16px;
            display: flex;
            gap: 14px;
            align-items: center;
            overflow-x: auto;
            box-shadow: 0 14px 32px rgba(13, 17, 20, .2);
        }

        .sy-brand {
            min-width: 210px;
        }

        .sy-nav {
            display: flex;
            margin-top: 0;
            min-width: max-content;
        }

        .sy-nav a {
            min-width: 154px;
        }

        .sy-command-bar {
            top: 78px;
            left: 0;
            min-height: 62px;
            padding: 10px 18px;
        }
    }

    @media (max-width: 760px) {
        body {
            padding: 158px 14px 32px;
        }

        h1 {
            font-size: 29px;
        }

        .topbar {
            grid-template-columns: 1fr;
            padding: 18px;
        }

        .nav {
            min-width: 0;
            text-align: left;
        }

        .sy-brand {
            min-width: 184px;
        }

        .sy-brand-mark {
            width: 42px;
            height: 42px;
        }

        .sy-command-actions {
            display: none;
        }

        .summary,
        .summary-grid {
            grid-template-columns: 1fr !important;
        }

        table {
            display: block;
            overflow-x: auto;
        }
    }
</style>
