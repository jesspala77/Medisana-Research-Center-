<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Command Center | SynNexus</title>

    <style>
        :root {
            --bg: #06101d;
            --bg-strong: #030914;
            --panel: rgba(12, 25, 43, 0.92);
            --panel-soft: rgba(255, 255, 255, 0.045);
            --border: rgba(189, 213, 255, 0.13);
            --border-strong: rgba(122, 165, 255, 0.24);
            --text: #f6f8fc;
            --muted: #8fa2bb;
            --blue: #2f82ff;
            --purple: #7c4dff;
            --teal: #16c7c9;
            --green: #2fd08e;
            --yellow: #f0bd42;
            --orange: #f68a31;
            --pink: #ec3f82;
            --red: #f25467;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at 20% 8%, rgba(47, 130, 255, 0.17), transparent 22rem),
                radial-gradient(circle at 78% 0%, rgba(22, 199, 201, 0.12), transparent 20rem),
                linear-gradient(135deg, #04101f 0%, #06101d 46%, #100b22 100%);
            color: var(--text);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select {
            font: inherit;
        }

        .app-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 272px minmax(0, 1fr);
        }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            gap: 18px;
            padding: 22px 18px;
            border-right: 1px solid var(--border);
            background: rgba(3, 9, 20, 0.93);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 4px 16px;
        }

        .brand-mark {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: linear-gradient(135deg, #18d0d1, #2f82ff 48%, #7c4dff);
            color: #ffffff;
            font-size: 24px;
            font-weight: 900;
        }

        .brand-name {
            font-size: 24px;
            font-weight: 850;
            line-height: 1;
        }

        .brand-company {
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
        }

        .nav-group {
            display: grid;
            gap: 5px;
        }

        .nav-label {
            margin: 8px 8px 4px;
            color: #6e7f95;
            font-size: 10px;
            font-weight: 850;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .nav-item {
            min-height: 40px;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 12px;
            border: 1px solid transparent;
            border-radius: 8px;
            color: #bdc9da;
            font-size: 14px;
            font-weight: 650;
        }

        .nav-item:hover,
        .nav-item.active {
            border-color: rgba(122, 165, 255, 0.22);
            background: linear-gradient(90deg, rgba(47, 130, 255, 0.22), rgba(124, 77, 255, 0.12));
            color: #ffffff;
        }

        .nav-icon {
            width: 24px;
            height: 24px;
            display: grid;
            place-items: center;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.055);
            color: #a9c1ff;
            font-size: 12px;
            font-weight: 850;
        }

        .new-pill {
            margin-left: auto;
            padding: 3px 8px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--purple), #b35cff);
            color: #ffffff;
            font-size: 10px;
            font-weight: 850;
        }

        .sidebar-card {
            margin-top: auto;
            padding: 18px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.035);
            text-align: center;
        }

        .sidebar-card .brand-mark {
            width: 38px;
            height: 38px;
            margin: 0 auto 10px;
            font-size: 18px;
        }

        .sidebar-card-title {
            font-size: 18px;
            font-weight: 850;
        }

        .sidebar-card-subtitle {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .content {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .command-bar {
            display: grid;
            grid-template-columns: minmax(270px, 400px) minmax(320px, 1fr) auto;
            align-items: center;
            gap: 22px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            background: rgba(4, 13, 27, 0.84);
            backdrop-filter: blur(18px);
        }

        .group-name {
            color: #ffffff;
            font-size: 13px;
            font-weight: 750;
        }

        h1 {
            margin-top: 3px;
            font-size: 25px;
            line-height: 1.08;
            font-weight: 900;
            letter-spacing: 0;
        }

        .subtitle {
            margin-top: 6px;
            color: var(--muted);
            font-size: 12px;
        }

        .enterprise-search {
            min-width: 0;
            height: 52px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 16px;
            border: 1px solid var(--border-strong);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.035);
        }

        .search-icon {
            color: #b4c6dc;
            font-size: 19px;
        }

        .enterprise-search input {
            width: 100%;
            border: 0;
            outline: 0;
            background: transparent;
            color: #ffffff;
            font-size: 13px;
        }

        .enterprise-search input::placeholder {
            color: #8fa2bb;
        }

        .shortcut {
            display: inline-flex;
            gap: 4px;
            color: #d8e4f4;
            font-size: 11px;
        }

        .shortcut span {
            min-width: 22px;
            display: inline-grid;
            place-items: center;
            padding: 2px 5px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.04);
        }

        .top-actions {
            display: flex;
            align-items: stretch;
            gap: 12px;
        }

        .top-tool {
            position: relative;
            min-width: 64px;
            display: grid;
            place-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-left: 1px solid rgba(255, 255, 255, 0.09);
            color: #dce7f5;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
        }

        .top-tool-icon {
            width: 26px;
            height: 26px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.045);
            font-size: 14px;
        }

        .badge {
            position: absolute;
            top: -5px;
            right: 12px;
            min-width: 17px;
            height: 17px;
            display: grid;
            place-items: center;
            padding: 0 4px;
            border-radius: 999px;
            background: #fa315c;
            color: #ffffff;
            font-size: 10px;
            font-weight: 850;
        }

        .profile {
            min-width: 214px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 12px;
            border-left: 1px solid rgba(255, 255, 255, 0.09);
        }

        .avatar {
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            border: 2px solid rgba(255, 255, 255, 0.19);
            border-radius: 50%;
            background: linear-gradient(135deg, #f2b18e, #7146b5);
            font-weight: 900;
        }

        .profile-name {
            font-size: 14px;
            font-weight: 850;
        }

        .profile-role {
            margin-top: 3px;
            color: var(--muted);
            font-size: 11px;
        }

        .workspace {
            padding: 16px 18px 18px;
        }

        .quick-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .quick-title {
            margin-right: 2px;
            color: #aab9cf;
            font-size: 10px;
            font-weight: 850;
            text-transform: uppercase;
        }

        .action-button {
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 14px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 7px;
            color: #ffffff;
            font-size: 12px;
            font-weight: 850;
        }

        .action-button.primary { background: linear-gradient(135deg, #2377ff, #2f82ff); }
        .action-button.success { background: linear-gradient(135deg, #16a75f, #2fd08e); }
        .action-button.orange { background: linear-gradient(135deg, #cf6221, #f68a31); }
        .action-button.purple { background: linear-gradient(135deg, #6540e8, #8b61ff); }
        .action-button.pink { background: linear-gradient(135deg, #c92268, #ec3f82); }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(150px, 1fr));
            gap: 12px;
        }

        .panel,
        .kpi-card {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: linear-gradient(180deg, rgba(13, 29, 50, 0.96), rgba(8, 19, 34, 0.96));
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.22);
        }

        .kpi-card {
            min-height: 126px;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 14px;
            padding: 16px;
            overflow: hidden;
        }

        .metric-icon {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            color: #ffffff;
            font-weight: 900;
        }

        .tone-green { background: linear-gradient(135deg, #1daf76, var(--green)); }
        .tone-purple { background: linear-gradient(135deg, #6b3cff, #a05bff); }
        .tone-blue { background: linear-gradient(135deg, #1d68da, var(--blue)); }
        .tone-orange { background: linear-gradient(135deg, #da641b, var(--orange)); }
        .tone-teal { background: linear-gradient(135deg, #0b9ca6, var(--teal)); }
        .tone-red { background: linear-gradient(135deg, #d92454, var(--pink)); }
        .tone-pink { background: linear-gradient(135deg, #c92268, var(--pink)); }
        .tone-yellow { background: linear-gradient(135deg, #ca9b22, var(--yellow)); }

        .metric-label {
            color: #c4d0df;
            font-size: 12px;
            font-weight: 700;
        }

        .metric-value {
            margin-top: 6px;
            font-size: 28px;
            line-height: 1;
            font-weight: 900;
        }

        .metric-change {
            margin-top: 10px;
            color: #50e7a3;
            font-size: 11px;
            font-weight: 800;
        }

        .metric-change.warning,
        .metric-change.danger {
            color: #ff5974;
        }

        .sparkline {
            grid-column: 1 / -1;
            height: 24px;
            display: flex;
            align-items: end;
            justify-content: flex-end;
            gap: 4px;
            opacity: 0.9;
        }

        .sparkline span {
            width: 5px;
            border-radius: 999px 999px 0 0;
            background: currentColor;
        }

        .sparkline.green { color: var(--green); }
        .sparkline.purple { color: var(--purple); }
        .sparkline.blue { color: var(--blue); }
        .sparkline.orange { color: var(--orange); }
        .sparkline.teal { color: var(--teal); }
        .sparkline.red { color: var(--pink); }

        .primary-grid {
            display: grid;
            grid-template-columns: minmax(420px, 1.18fr) minmax(520px, 1fr);
            gap: 12px;
            margin-top: 12px;
        }

        .panel {
            padding: 16px;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
        }

        .panel-title {
            font-size: 16px;
            font-weight: 900;
        }

        .panel-subtitle {
            margin-top: 3px;
            color: var(--muted);
            font-size: 11px;
        }

        .panel-link {
            color: #b695ff;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .brief-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 292px;
            gap: 16px;
        }

        .brief-greeting {
            margin-bottom: 14px;
        }

        .brief-greeting h2 {
            font-size: 18px;
            font-weight: 850;
        }

        .brief-greeting p {
            margin-top: 4px;
            color: #aebbd0;
            font-size: 13px;
        }

        .brief-list {
            display: grid;
            gap: 13px;
        }

        .brief-item {
            display: grid;
            grid-template-columns: 32px minmax(0, 1fr);
            gap: 12px;
            align-items: start;
        }

        .brief-icon,
        .activity-icon,
        .schedule-icon {
            width: 28px;
            height: 28px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            color: #ffffff;
            font-size: 12px;
            font-weight: 900;
        }

        .brief-title {
            font-size: 13px;
            font-weight: 750;
        }

        .brief-detail {
            margin-top: 3px;
            color: var(--muted);
            font-size: 12px;
        }

        .snapshot {
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.035);
        }

        .snapshot-title {
            margin-bottom: 11px;
            color: #ffffff;
            font-size: 12px;
            font-weight: 900;
        }

        .snapshot-row {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.065);
        }

        .snapshot-row:last-child {
            border-bottom: 0;
        }

        .snapshot-label {
            color: #a7b8cb;
            font-size: 11px;
        }

        .snapshot-value {
            text-align: right;
            font-size: 16px;
            font-weight: 900;
        }

        .positive {
            margin-top: 3px;
            color: #50e7a3;
            font-size: 11px;
            font-weight: 800;
        }

        .ai-search-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 16px;
            padding: 8px;
            border-radius: 8px;
            background: rgba(124, 77, 255, 0.11);
        }

        .ask-button {
            min-height: 32px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 13px;
            border-radius: 7px;
            background: linear-gradient(135deg, #6540e8, #8b61ff);
            color: #ffffff;
            font-size: 12px;
            font-weight: 850;
            white-space: nowrap;
        }

        .search-chip {
            color: #cbbdff;
            font-size: 12px;
            font-weight: 650;
            white-space: nowrap;
        }

        .company-table {
            display: grid;
            gap: 6px;
        }

        .company-head,
        .health-row {
            display: grid;
            grid-template-columns: minmax(220px, 1.35fr) 108px 92px 116px 70px;
            align-items: center;
            gap: 12px;
        }

        .company-head {
            padding: 0 10px 4px;
            color: #8fa2bb;
            font-size: 10px;
            font-weight: 800;
        }

        .health-row {
            min-height: 56px;
            padding: 8px 10px;
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.027);
        }

        .health-row:hover {
            border-color: rgba(122, 165, 255, 0.26);
            background: rgba(255, 255, 255, 0.05);
        }

        .company-cell {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .company-avatar {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #f3f7ff;
            color: #152139;
            font-size: 11px;
            font-weight: 900;
        }

        .company-name {
            overflow: hidden;
            text-overflow: ellipsis;
            color: #ffffff;
            font-size: 13px;
            font-weight: 850;
            white-space: nowrap;
        }

        .company-division,
        .cell-detail {
            margin-top: 2px;
            color: var(--muted);
            font-size: 11px;
        }

        .status-pill {
            display: inline-flex;
            justify-content: center;
            min-width: 78px;
            padding: 4px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 850;
        }

        .status-healthy {
            background: rgba(47, 208, 142, 0.13);
            color: #72e8b3;
        }

        .status-attention {
            background: rgba(240, 189, 66, 0.14);
            color: #ffd46b;
        }

        .status-warning {
            background: rgba(246, 138, 49, 0.14);
            color: #ffad66;
        }

        .cell-value {
            font-size: 14px;
            font-weight: 850;
        }

        .health-score {
            --score: 70%;
            --health-color: var(--green);
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: conic-gradient(var(--health-color) var(--score), rgba(255, 255, 255, 0.09) 0);
            color: #ffffff;
            font-size: 11px;
            font-weight: 900;
        }

        .health-score::before {
            content: "";
            position: absolute;
        }

        .health-score span {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #0b1729;
        }

        .health-healthy { --health-color: var(--green); }
        .health-attention { --health-color: var(--yellow); }
        .health-warning { --health-color: var(--orange); }

        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1.15fr 1.35fr;
            gap: 12px;
            margin-top: 12px;
        }

        .activity-list,
        .schedule-list {
            display: grid;
            gap: 10px;
        }

        .activity-item {
            display: grid;
            grid-template-columns: 42px minmax(0, 1fr);
            gap: 10px;
            align-items: start;
        }

        .activity-time {
            color: var(--muted);
            font-size: 11px;
            white-space: nowrap;
        }

        .activity-title {
            color: #ffffff;
            font-size: 12px;
            font-weight: 800;
        }

        .activity-context {
            margin-top: 2px;
            color: var(--muted);
            font-size: 11px;
        }

        .schedule-item {
            display: grid;
            grid-template-columns: 74px 26px minmax(0, 1fr);
            gap: 10px;
            align-items: center;
            padding: 8px;
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.027);
        }

        .schedule-time {
            color: #ffffff;
            font-size: 12px;
            font-weight: 850;
        }

        .schedule-title {
            font-size: 12px;
            font-weight: 850;
        }

        .schedule-context {
            margin-top: 2px;
            color: var(--muted);
            font-size: 11px;
        }

        .revenue-body {
            display: grid;
            grid-template-columns: 160px minmax(0, 1fr);
            align-items: center;
            gap: 18px;
            margin-top: 10px;
        }

        .donut {
            width: 150px;
            height: 150px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: conic-gradient(var(--blue) 0 36%, var(--teal) 36% 62%, var(--purple) 62% 92%, var(--green) 92% 100%);
        }

        .donut-center {
            width: 92px;
            height: 92px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #0b1729;
            text-align: center;
        }

        .donut-value {
            font-size: 22px;
            font-weight: 900;
        }

        .donut-label {
            margin-top: 2px;
            color: var(--muted);
            font-size: 11px;
        }

        .legend {
            display: grid;
            gap: 12px;
        }

        .legend-item {
            display: grid;
            grid-template-columns: 12px 1fr;
            gap: 9px;
            align-items: start;
        }

        .legend-dot {
            width: 9px;
            height: 9px;
            margin-top: 4px;
            border-radius: 50%;
        }

        .legend-label {
            font-size: 12px;
            font-weight: 750;
        }

        .legend-value {
            margin-top: 2px;
            color: var(--muted);
            font-size: 11px;
        }

        .funnel {
            display: grid;
            gap: 8px;
            margin-top: 10px;
        }

        .stage {
            display: grid;
            grid-template-columns: minmax(130px, 1fr) 210px;
            gap: 12px;
            align-items: center;
        }

        .stage-bar {
            height: 26px;
            display: flex;
            justify-content: center;
        }

        .stage-fill {
            width: var(--stage-width);
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(90deg, var(--purple), var(--teal));
        }

        .stage-detail {
            min-height: 34px;
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 10px;
            align-items: center;
            padding: 8px 10px;
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.027);
        }

        .stage-label,
        .stage-value {
            font-size: 12px;
            font-weight: 850;
        }

        .stage-change {
            color: #50e7a3;
            font-size: 11px;
            font-weight: 800;
        }

        .footer-status {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr auto;
            gap: 18px;
            align-items: center;
            margin-top: 12px;
            padding: 14px 18px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: rgba(3, 9, 20, 0.72);
            color: #d7e3f0;
            font-size: 12px;
        }

        .footer-label {
            color: var(--muted);
            font-size: 11px;
        }

        .footer-value {
            margin-top: 4px;
            font-weight: 800;
        }

        .footer-brand {
            color: #c9d5e8;
            font-size: 18px;
        }

        @media (max-width: 1500px) {
            .command-bar {
                grid-template-columns: minmax(260px, 360px) minmax(280px, 1fr);
            }

            .top-actions {
                grid-column: 1 / -1;
                justify-content: flex-end;
            }

            .kpi-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .bottom-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 1180px) {
            .app-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: relative;
                height: auto;
            }

            .command-bar,
            .primary-grid,
            .brief-layout {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .company-head {
                display: none;
            }

            .health-row {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 760px) {
            .workspace {
                padding: 14px 12px;
            }

            .command-bar {
                padding: 16px 14px;
            }

            .top-actions {
                flex-wrap: wrap;
                justify-content: flex-start;
            }

            .profile {
                min-width: 100%;
            }

            .kpi-grid,
            .bottom-grid,
            .health-row,
            .revenue-body,
            .stage {
                grid-template-columns: 1fr;
            }

            .ai-search-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .search-chip {
                white-space: normal;
            }

            .footer-status {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

@php
    $currentUser = auth()->user();
    $displayName = $currentUser?->isPlatformAdmin() ? 'Jessica M Palacio' : ($currentUser?->name ?: 'Jessica M Palacio');
    $displayRole = $currentUser?->isPlatformAdmin() ? 'Executive' : 'Module User';
    $canAccess = fn (string $module): bool => (bool) $currentUser?->canAccessModule($module);
    $canOpenProgram = fn (array $modules): bool => collect($modules)->contains(fn (string $module): bool => $canAccess($module));
@endphp

<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-mark">S</div>
            <div>
                <div class="brand-name">SYNNEXUS</div>
                <div class="brand-company">Global Synergia Group</div>
            </div>
        </div>

        <nav class="nav-group" aria-label="Enterprise navigation">
            <div class="nav-label">Enterprise</div>
            <a href="{{ route('dashboard') }}" class="nav-item active">
                <span class="nav-icon">⌂</span>
                <span>Command Center</span>
            </a>

            <div class="nav-label">Business Divisions</div>
            <a href="{{ route('capital-funding.dashboard') }}" class="nav-item">
                <span class="nav-icon">$</span>
                <span>Finance &amp; Surety</span>
            </a>
            <a href="{{ route('clinical-recruitment.dashboard') }}" class="nav-item">
                <span class="nav-icon">+</span>
                <span>Healthcare</span>
            </a>
            <a href="{{ route('remodeling.dashboard') }}" class="nav-item">
                <span class="nav-icon">□</span>
                <span>Construction &amp; Design</span>
            </a>
            <a href="#ai-search" class="nav-item">
                <span class="nav-icon">AI</span>
                <span>Technology &amp; AI</span>
            </a>

            <div class="nav-label">Management</div>
            <a href="#revenue-overview" class="nav-item">
                <span class="nav-icon">↗</span>
                <span>Executive Analytics</span>
            </a>
            <a href="#ai-briefing" class="nav-item">
                <span class="nav-icon">✦</span>
                <span>AI Insights</span>
                <span class="new-pill">NEW</span>
            </a>
            <a href="#pipeline" class="nav-item">
                <span class="nav-icon">▤</span>
                <span>Reports &amp; BI</span>
            </a>
            <a href="#activity" class="nav-item">
                <span class="nav-icon">✓</span>
                <span>Tasks &amp; Workflow</span>
            </a>
            <a href="#activity" class="nav-item">
                <span class="nav-icon">≡</span>
                <span>Documents</span>
            </a>
            <a href="{{ $canAccess('bond-agency') ? route('bond-agency.outreach.index') : '#activity' }}" class="nav-item">
                <span class="nav-icon">@</span>
                <span>Communications</span>
            </a>

            <div class="nav-label">Administration</div>
            <a href="{{ route('admin.user-access.index') }}" class="nav-item">
                <span class="nav-icon">👤</span>
                <span>Users &amp; Teams</span>
            </a>
            <a href="#system-status" class="nav-item">
                <span class="nav-icon">⚙</span>
                <span>System Settings</span>
            </a>
            <a href="#system-status" class="nav-item">
                <span class="nav-icon">⛓</span>
                <span>Integrations</span>
            </a>
        </nav>

        <div class="sidebar-card">
            <div class="brand-mark">S</div>
            <div class="sidebar-card-title">SYNNEXUS</div>
            <div class="sidebar-card-subtitle">Enterprise OS<br>v1.0.0</div>
        </div>
    </aside>

    <div class="content">
        <header class="command-bar">
            <div>
                <div class="group-name">Global Synergia Group</div>
                <h1>Enterprise Command Center</h1>
                <p class="subtitle">Executive visibility across every company, opportunity and operating division.</p>
            </div>

            <form id="ai-search" class="enterprise-search" action="{{ route('dashboard') }}" method="get">
                <span class="search-icon">⌕</span>
                <input
                    type="search"
                    name="q"
                    placeholder='Search the enterprise... (e.g., "overdue tasks", "funding leads", "K&G proposals")'
                    aria-label="Search the Enterprise"
                >
                <span class="shortcut" aria-hidden="true"><span>Ctrl</span><span>/</span></span>
            </form>

            <div class="top-actions">
                <a href="#activity" class="top-tool">
                    <span class="badge">6</span>
                    <span class="top-tool-icon">🔔</span>
                    <span>Notifications</span>
                </a>
                <a href="#schedule" class="top-tool">
                    <span class="top-tool-icon">▣</span>
                    <span>Calendar</span>
                </a>
                <a href="{{ $canAccess('bond-agency') ? route('bond-agency.outreach.index') : '#activity' }}" class="top-tool">
                    <span class="badge">12</span>
                    <span class="top-tool-icon">✉</span>
                    <span>Email</span>
                </a>
                <a href="#ai-briefing" class="top-tool">
                    <span class="top-tool-icon">AI</span>
                    <span>Assistant</span>
                </a>
                <div class="profile">
                    <div class="avatar">JP</div>
                    <div>
                        <div class="profile-name">{{ $displayName }}</div>
                        <div class="profile-role">{{ $displayRole }}</div>
                    </div>
                </div>
            </div>
        </header>

        <main class="workspace">
            @if (session('success'))
                <div class="panel" style="margin-bottom: 12px; border-color: rgba(47, 208, 142, 0.32); color: #bff4dc;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="quick-actions" aria-label="Quick actions">
                <span class="quick-title">Quick Actions</span>
                @foreach ($quickActions as $action)
                    <a href="{{ $action['route'] }}" class="action-button {{ $action['type'] }}">
                        <span>+</span>
                        <span>{{ $action['label'] }}</span>
                    </a>
                @endforeach
            </div>

            <section class="kpi-grid" aria-label="Enterprise KPIs">
                @foreach ($scorecards as $scorecard)
                    <article class="kpi-card">
                        <div class="metric-icon tone-{{ $scorecard['tone'] }}">{{ $scorecard['icon'] }}</div>
                        <div>
                            <div class="metric-label">{{ $scorecard['label'] }}</div>
                            <div class="metric-value">{{ $scorecard['value'] }}</div>
                            <div class="metric-change {{ $scorecard['status'] }}">
                                ▲ {{ $scorecard['change'] }} {{ $scorecard['detail'] }}
                            </div>
                        </div>
                        <div class="sparkline {{ $scorecard['tone'] }}" aria-hidden="true">
                            <span style="height: 24%;"></span>
                            <span style="height: 44%;"></span>
                            <span style="height: 32%;"></span>
                            <span style="height: 60%;"></span>
                            <span style="height: 48%;"></span>
                            <span style="height: 72%;"></span>
                            <span style="height: 58%;"></span>
                        </div>
                    </article>
                @endforeach
            </section>

            <section class="primary-grid">
                <article id="ai-briefing" class="panel">
                    <div class="panel-header">
                        <div>
                            <div class="panel-title">AI Executive Briefing</div>
                            <div class="panel-subtitle">Today's priorities</div>
                        </div>
                        <a href="#ai-search" class="panel-link">View AI Insights →</a>
                    </div>

                    <div class="brief-layout">
                        <div>
                            <div class="brief-greeting">
                                <h2>Good morning, Jessica</h2>
                                <p>Here's what's most important today.</p>
                            </div>

                            <div class="brief-list">
                                @foreach ($executiveBriefing as $item)
                                    <div class="brief-item">
                                        <div class="brief-icon tone-{{ $item['severity'] }}">!</div>
                                        <div>
                                            <div class="brief-title">{{ $item['title'] }}</div>
                                            <div class="brief-detail">{{ $item['detail'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <aside class="snapshot">
                            <div class="snapshot-title">Enterprise Snapshot</div>
                            @foreach ($snapshot as $row)
                                <div class="snapshot-row">
                                    <div>
                                        <div class="snapshot-label">{{ $row['label'] }}</div>
                                        <div class="positive">▲ {{ $row['change'] }}</div>
                                    </div>
                                    <div class="snapshot-value">{{ $row['value'] }}</div>
                                </div>
                            @endforeach
                        </aside>
                    </div>

                    <div class="ai-search-row">
                        <a href="#ai-search" class="ask-button">Ask AI Anything</a>
                        <span class="search-chip">"Show me all projects over $50,000"</span>
                        <span class="search-chip">"Which leads haven't been contacted in 7 days?"</span>
                        <span class="search-chip">"Studies at risk this month"</span>
                    </div>
                </article>

                <article class="panel">
                    <div class="panel-header">
                        <div>
                            <div class="panel-title">Company Health Overview</div>
                            <div class="panel-subtitle">All companies at a glance</div>
                        </div>
                        <a href="#company-health" class="panel-link">View All Companies →</a>
                    </div>

                    <div id="company-health" class="company-table">
                        <div class="company-head">
                            <div></div>
                            <div>Revenue</div>
                            <div>Tasks</div>
                            <div>Pipeline</div>
                            <div>Health</div>
                        </div>

                        @foreach ($companies as $company)
                            @php
                                $statusClass = strtolower($company['status']);
                            @endphp

                            @if ($canOpenProgram($company['modules']))
                                <a href="{{ route('programs.show', $company['program']) }}" class="health-row">
                                    @include('components.dashboard-company-row', [
                                        'company' => $company,
                                        'statusClass' => $statusClass,
                                    ])
                                </a>
                            @else
                                <div class="health-row" aria-label="{{ $company['name'] }}">
                                    @include('components.dashboard-company-row', [
                                        'company' => $company,
                                        'statusClass' => $statusClass,
                                    ])
                                </div>
                            @endif
                        @endforeach
                    </div>
                </article>
            </section>

            <section class="bottom-grid">
                <article id="activity" class="panel">
                    <div class="panel-header">
                        <div>
                            <div class="panel-title">Recent Activity</div>
                            <div class="panel-subtitle">Live feed across the enterprise</div>
                        </div>
                        <a href="#activity" class="panel-link">View All →</a>
                    </div>

                    <div class="activity-list">
                        @foreach ($recentActivity as $activity)
                            <div class="activity-item">
                                <div class="activity-time">{{ $activity['time'] }}</div>
                                <div style="display: flex; gap: 10px;">
                                    <div class="activity-icon tone-{{ $activity['tone'] }}">•</div>
                                    <div>
                                        <div class="activity-title">{{ $activity['title'] }}</div>
                                        <div class="activity-context">{{ $activity['context'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article id="schedule" class="panel">
                    <div class="panel-header">
                        <div>
                            <div class="panel-title">Today's Schedule</div>
                            <div class="panel-subtitle">{{ now()->format('l, F j, Y') }}</div>
                        </div>
                        <a href="#schedule" class="panel-link">View Calendar →</a>
                    </div>

                    <div class="schedule-list">
                        @foreach ($todaySchedule as $event)
                            <div class="schedule-item">
                                <div class="schedule-time">{{ $event['time'] }}</div>
                                <div class="schedule-icon tone-{{ $event['tone'] }}">▣</div>
                                <div>
                                    <div class="schedule-title">{{ $event['title'] }}</div>
                                    <div class="schedule-context">{{ $event['context'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article id="revenue-overview" class="panel">
                    <div class="panel-header">
                        <div>
                            <div class="panel-title">Revenue Overview</div>
                            <div class="panel-subtitle">This month vs last month</div>
                        </div>
                        <select aria-label="Revenue period" style="border: 1px solid var(--border); border-radius: 7px; background: rgba(255, 255, 255, 0.045); color: #ffffff; padding: 8px 10px; font-size: 12px;">
                            <option>This Month</option>
                        </select>
                    </div>

                    <div class="revenue-body">
                        <div class="donut">
                            <div class="donut-center">
                                <div>
                                    <div class="donut-value">$1.27M</div>
                                    <div class="donut-label">Total Revenue</div>
                                    <div class="positive">▲ 17.6%</div>
                                </div>
                            </div>
                        </div>

                        <div class="legend">
                            @foreach ($revenueBreakdown as $item)
                                <div class="legend-item">
                                    <span class="legend-dot tone-{{ $item['tone'] }}"></span>
                                    <div>
                                        <div class="legend-label">{{ $item['label'] }}</div>
                                        <div class="legend-value">{{ $item['value'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>

                <article id="pipeline" class="panel">
                    <div class="panel-header">
                        <div>
                            <div class="panel-title">Opportunities Pipeline</div>
                            <div class="panel-subtitle">By stage</div>
                        </div>
                        <select aria-label="Pipeline division" style="border: 1px solid var(--border); border-radius: 7px; background: rgba(255, 255, 255, 0.045); color: #ffffff; padding: 8px 10px; font-size: 12px;">
                            <option>All Divisions</option>
                        </select>
                    </div>

                    <div class="funnel">
                        @foreach ($pipelineStages as $stage)
                            <div class="stage">
                                <div class="stage-bar">
                                    <div class="stage-fill" style="--stage-width: {{ $stage['width'] }}%;"></div>
                                </div>
                                <div class="stage-detail">
                                    <div class="stage-label">{{ $stage['label'] }}</div>
                                    <div class="stage-value">{{ $stage['value'] }}</div>
                                    <div class="stage-change">▲ {{ $stage['change'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            </section>

            <footer id="system-status" class="footer-status">
                <div>
                    <div class="footer-label">SynNexus Enterprise OS</div>
                    <div class="footer-value">© {{ now()->year }} Global Synergia Group. All rights reserved.</div>
                </div>
                <div>
                    <div class="footer-label">System Status</div>
                    <div class="footer-value" style="color: #50e7a3;">All Systems Operational</div>
                </div>
                <div>
                    <div class="footer-label">Last Data Sync</div>
                    <div class="footer-value">2 minutes ago</div>
                </div>
                <div>
                    <div class="footer-label">AI Models Active</div>
                    <div class="footer-value">8 / 8</div>
                </div>
                <div class="footer-brand">One Enterprise. Unlimited Synergy.</div>
            </footer>
        </main>
    </div>
</div>
</body>
</html>
