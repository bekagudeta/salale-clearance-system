<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salale University — Clearance Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ─── DESIGN TOKENS ──────────────────────────────────────── */
        :root {
            --ink:        #001722;
            --forest:     #084A48;
            --aqua:       #6BCFCB;
            --ember:      #FE580B;

            /* TYPOGRAPHY — two fonts, used nowhere else */
            --f-display: 'Playfair Display', Georgia, serif;
            --f-body:    'Outfit', system-ui, sans-serif;

            /* FONT SIZES — one scale, everywhere */
            --fs-xs:   11px;
            --fs-sm:   13px;
            --fs-base: 15px;
            --fs-md:   17px;
            --fs-lg:   20px;
            --fs-xl:   24px;
            --fs-2xl:  32px;
            --fs-3xl:  clamp(2rem,  3.5vw, 2.8rem);
            --fs-4xl:  clamp(2.4rem, 4.5vw, 3.6rem);

            /* FONT WEIGHTS */
            --fw-light:  300;
            --fw-reg:    400;
            --fw-med:    500;
            --fw-semi:   600;
            --fw-bold:   700;
            --fw-black:  800;

            /* LETTER-SPACING */
            --ls-tight:  -0.02em;
            --ls-normal:  0;
            --ls-wide:    0.06em;
            --ls-wider:   0.12em;

            /* SPACING */
            --section-pad: 96px;
            --inner-max:   1200px;
        }

        /* ─── RESET ──────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        /* ─── BASE ───────────────────────────────────────────────── */
        body {
            font-family: var(--f-body);
            font-size: var(--fs-base);
            font-weight: var(--fw-reg);
            line-height: 1.65;
            color: #1a2e35;
            background: #fff;
            overflow-x: hidden;
        }

        /* DISPLAY FONT — applied only to headings */
        h1, h2, h3 {
            font-family: var(--f-display);
            font-weight: var(--fw-bold);
            letter-spacing: var(--ls-tight);
            line-height: 1.15;
        }

        /* UTILITY */
        .label-caps {
            font-family: var(--f-body);
            font-size: var(--fs-xs);
            font-weight: var(--fw-semi);
            letter-spacing: var(--ls-wider);
            text-transform: uppercase;
        }
        .num-display {
            font-family: var(--f-body);
            font-weight: var(--fw-black);
            letter-spacing: var(--ls-tight);
        }

        /* ═══════════════════════════════════════════════════════════
           NAV
        ═══════════════════════════════════════════════════════════ */
        #navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: rgba(255,255,255,0.93);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(8,74,72,0.09);
            transition: box-shadow .3s, background .3s;
        }
        #navbar.scrolled {
            background: rgba(255,255,255,0.98);
            box-shadow: 0 4px 28px rgba(8,74,72,0.09);
        }
        .nav-inner {
            max-width: var(--inner-max); margin: 0 auto;
            padding: 0 1.75rem;
            display: flex; align-items: center; justify-content: space-between;
            height: 78px;
        }

        /* ── Logo lockup ── */
        .nav-brand { display: flex; align-items: center; gap: 14px; text-decoration: none; }

        .logo-mark {
            position: relative;
            width: 52px; height: 52px; flex-shrink: 0;
            border-radius: 50%;
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            box-shadow:
                0 0 0 2px var(--forest),
                0 0 0 4px rgba(107,207,203,0.40),
                0 4px 16px rgba(8,74,72,0.22);
        }
        .logo-mark img {
            width: 46px; height: 46px; object-fit: contain;
            border-radius: 50%;
            position: relative; z-index: 1;
        }

        .logo-text { display: flex; flex-direction: column; }
        .logo-text .name {
            font-family: var(--f-display);
            font-size: 17px;
            font-weight: var(--fw-bold);
            color: var(--ink);
            line-height: 1.2;
            letter-spacing: -0.01em;
        }
        .logo-text .tagline {
            font-family: var(--f-body);
            font-size: var(--fs-xs);
            font-weight: var(--fw-med);
            letter-spacing: 0.10em;
            text-transform: uppercase;
            color: var(--forest);
            margin-top: 1px;
        }

        /* Divider between name + tagline */
        .logo-divider {
            width: 1px; height: 32px; background: rgba(8,74,72,0.15);
            margin: 0 2px;
        }

        /* ── Nav links ── */
        .nav-links { display: flex; align-items: center; gap: 34px; list-style: none; }
        .nav-links a {
            font-family: var(--f-body);
            font-size: var(--fs-sm);
            font-weight: var(--fw-med);
            color: #3d5158;
            text-decoration: none;
            letter-spacing: 0.02em;
            position: relative; padding-bottom: 2px;
            transition: color .22s;
        }
        .nav-links a::after {
            content: ''; position: absolute;
            bottom: -3px; left: 0; right: 0;
            height: 1.5px; background: var(--ember); border-radius: 2px;
            transform: scaleX(0); transform-origin: left;
            transition: transform .22s cubic-bezier(.4,0,.2,1);
        }
        .nav-links a:hover { color: var(--ember); }
        .nav-links a:hover::after { transform: scaleX(1); }

        /* ── CTA button ── */
        .nav-cta {
            font-family: var(--f-body);
            font-size: var(--fs-sm);
            font-weight: var(--fw-semi);
            letter-spacing: 0.03em;
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px; border-radius: 10px;
            background: var(--forest); color: #fff;
            text-decoration: none;
            transition: background .22s, transform .18s, box-shadow .22s;
        }
        .nav-cta:hover {
            background: var(--ember);
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(254,88,11,.32);
        }

        /* ── Hamburger ── */
        .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 4px; }
        .hamburger span { display: block; width: 22px; height: 2px; background: var(--forest); border-radius: 2px; transition: all .28s; }
        .hamburger.open span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: rotate(-45deg) translate(5px,-5px); }

        .mobile-menu {
            display: none; flex-direction: column;
            position: absolute; top: 100%; left: 0; right: 0;
            background: #fff; border-top: 1px solid #e8eef0;
            box-shadow: 0 16px 40px rgba(0,0,0,0.10);
        }
        .mobile-menu.open { display: flex; animation: mslide .22s ease; }
        @keyframes mslide { from { opacity:0; transform:translateY(-6px);} to { opacity:1; transform:translateY(0);} }
        .mobile-menu a {
            font-family: var(--f-body); font-size: var(--fs-base); font-weight: var(--fw-med);
            padding: 15px 22px; color: #3d5158;
            border-bottom: 1px solid #f3f5f6; text-decoration: none;
            transition: background .18s, color .18s;
        }
        .mobile-menu a:hover { background: #f7fafa; color: var(--forest); }
        .mobile-menu a.mob-cta {
            background: var(--forest); color: #fff; font-weight: var(--fw-semi);
            text-align: center; border: none;
        }
        .mobile-menu a.mob-cta:hover { background: var(--ember); }

        @media (max-width: 768px) {
            .hamburger { display: flex; }
            .nav-links, .nav-cta { display: none !important; }
        }

        /* ═══════════════════════════════════════════════════════════
           HERO
        ═══════════════════════════════════════════════════════════ */
        .hero {
            min-height: 100vh;
            background: var(--ink);
            position: relative; overflow: hidden;
            display: flex; align-items: center;
            padding: 120px 0 88px;
        }
        .hero-glow-tl {
            position: absolute; width: 750px; height: 750px; border-radius: 50%;
            background: radial-gradient(circle, rgba(8,74,72,.60) 0%, transparent 68%);
            top: -260px; right: -180px; pointer-events: none;
        }
        .hero-glow-bl {
            position: absolute; width: 520px; height: 520px; border-radius: 50%;
            background: radial-gradient(circle, rgba(254,88,11,.13) 0%, transparent 70%);
            bottom: -160px; left: -80px; pointer-events: none;
        }
        .hero-grid {
            position: absolute; inset: 0; pointer-events: none;
            background-image:
                linear-gradient(rgba(107,207,203,.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(107,207,203,.035) 1px, transparent 1px);
            background-size: 64px 64px;
        }

        .hero-inner {
            max-width: var(--inner-max); margin: 0 auto; padding: 0 1.75rem;
            position: relative; z-index: 1;
            display: grid; grid-template-columns: 1fr 1fr; gap: 68px; align-items: center;
        }
        @media (max-width: 1024px) { .hero-inner { grid-template-columns: 1fr; gap: 52px; } }

        /* badge */
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 14px 6px 10px; border-radius: 100px; margin-bottom: 26px;
            background: rgba(107,207,203,.10); border: 1px solid rgba(107,207,203,.22);
            color: var(--aqua);
        }
        .hero-badge-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--aqua); box-shadow: 0 0 9px var(--aqua);
            animation: blink 2.2s ease-in-out infinite;
        }
        @keyframes blink { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.45;transform:scale(1.5)} }

        .hero-title {
            font-family: var(--f-display);
            font-size: var(--fs-4xl);
            font-weight: var(--fw-black);
            color: #fff; margin-bottom: 22px;
            letter-spacing: var(--ls-tight); line-height: 1.08;
        }
        .hero-title .accent { color: var(--aqua); }

        .hero-lead {
            font-family: var(--f-body);
            font-size: var(--fs-md);
            font-weight: var(--fw-light);
            line-height: 1.72;
            color: rgba(217,244,242,.78);
            max-width: 480px; margin-bottom: 38px;
        }

        .hero-btns { display: flex; gap: 12px; flex-wrap: wrap; }

        /* Shared button base */
        .btn {
            font-family: var(--f-body);
            font-size: var(--fs-base);
            font-weight: var(--fw-semi);
            letter-spacing: 0.025em;
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 28px; border-radius: 10px;
            text-decoration: none;
            transition: transform .18s, box-shadow .22s, background .2s, border-color .2s, color .2s;
        }
        .btn:hover { transform: translateY(-2px); }

        .btn-primary { background: var(--ember); color: #fff; }
        .btn-primary:hover { background: #e74d07; box-shadow: 0 12px 30px rgba(254,88,11,.42); }

        .btn-outline-light {
            border: 1.5px solid rgba(107,207,203,.40); color: var(--aqua);
        }
        .btn-outline-light:hover { background: rgba(107,207,203,.10); border-color: var(--aqua); color: #fff; }

        .btn-forest { background: var(--forest); color: #fff; }
        .btn-forest:hover { background: #063d3b; box-shadow: 0 12px 30px rgba(8,74,72,.38); }

        /* ── Hero right panel ── */
        .hero-panel { display: flex; flex-direction: column; gap: 13px; }

        .glass {
            background: rgba(255,255,255,.055);
            backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 18px; padding: 22px 24px;
            transition: border-color .28s;
        }
        .glass:hover { border-color: rgba(107,207,203,.25); }

        .g-label {
            font-family: var(--f-body);
            font-size: var(--fs-xs);
            font-weight: var(--fw-semi);
            letter-spacing: var(--ls-wider);
            text-transform: uppercase;
            color: var(--aqua); margin-bottom: 4px;
        }
        .g-title {
            font-family: var(--f-display);
            font-size: 17px; font-weight: var(--fw-bold);
            color: #fff; margin-bottom: 14px;
        }
        .g-big-num {
            font-family: var(--f-body);
            font-size: 32px; font-weight: var(--fw-black);
            letter-spacing: var(--ls-tight); color: #fff;
        }
        .g-sub {
            font-family: var(--f-body);
            font-size: var(--fs-sm); color: rgba(217,244,242,.60);
        }
        .prog-bar { height: 6px; border-radius: 99px; background: rgba(255,255,255,.09); overflow: hidden; margin-top: 10px; }
        .prog-fill {
            height: 100%; border-radius: 99px;
            background: linear-gradient(to right, var(--ember), var(--aqua));
            width: 78%; animation: growBar 1.9s cubic-bezier(.4,0,.2,1) .5s both;
        }
        @keyframes growBar { from { width: 0; } }

        .mini-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .mini {
            background: rgba(255,255,255,.045);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 14px; padding: 16px;
            transition: background .25s, border-color .25s;
        }
        .mini:hover { background: rgba(255,255,255,.08); border-color: rgba(107,207,203,.18); }
        .mini-icon {
            width: 36px; height: 36px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 10px; font-size: 15px;
        }
        .mini-lbl {
            font-family: var(--f-body); font-size: 11px;
            color: rgba(217,244,242,.52); margin-bottom: 2px;
        }
        .mini-val {
            font-family: var(--f-body);
            font-size: 21px; font-weight: var(--fw-black);
            letter-spacing: var(--ls-tight); color: #fff;
        }
        .mini-val span { font-size: 13px; font-weight: var(--fw-reg); color: rgba(217,244,242,.45); }

        .benefit-row { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; }
        .benefit-row:last-child { margin-bottom: 0; }
        .benefit-check {
            width: 22px; height: 22px; border-radius: 6px; flex-shrink: 0; margin-top: 2px;
            display: flex; align-items: center; justify-content: center; font-size: 10px;
        }
        .benefit-strong {
            font-family: var(--f-body);
            font-size: var(--fs-sm); font-weight: var(--fw-semi);
            color: #fff; display: block; line-height: 1.35;
        }
        .benefit-sub {
            font-family: var(--f-body);
            font-size: 12px; color: rgba(217,244,242,.50);
        }

        /* ═══════════════════════════════════════════════════════════
           STATS BAND
        ═══════════════════════════════════════════════════════════ */
        .stats-band { background: var(--forest); padding: 52px 0; }
        .stats-band-inner { max-width: var(--inner-max); margin: 0 auto; padding: 0 1.75rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(5,1fr); }
        @media (max-width: 768px) { .stats-grid { grid-template-columns: repeat(2,1fr); gap: 1px; background: rgba(255,255,255,.08); } }
        .stat {
            text-align: center; padding: 16px 20px;
            border-right: 1px solid rgba(255,255,255,.11);
        }
        .stat:last-child { border-right: none; }
        .stat-num {
            font-family: var(--f-body);
            font-size: 38px; font-weight: var(--fw-black);
            letter-spacing: var(--ls-tight);
            color: #fff; line-height: 1; margin-bottom: 6px;
        }
        .stat-num em { color: var(--aqua); font-style: normal; }
        .stat-lbl {
            font-family: var(--f-body);
            font-size: var(--fs-sm); font-weight: var(--fw-reg);
            color: rgba(217,244,242,.58); letter-spacing: 0.02em;
        }

        /* ═══════════════════════════════════════════════════════════
           SECTION TEMPLATE
        ═══════════════════════════════════════════════════════════ */
        .section { padding: var(--section-pad) 0; }
        .section-inner { max-width: var(--inner-max); margin: 0 auto; padding: 0 1.75rem; }
        .sec-hd { margin-bottom: 56px; }

        .eyebrow {
            font-family: var(--f-body);
            font-size: var(--fs-xs);
            font-weight: var(--fw-semi);
            letter-spacing: var(--ls-wider);
            text-transform: uppercase;
            color: var(--forest);
            display: inline-block; margin-bottom: 12px;
        }
        .sec-title {
            font-family: var(--f-display);
            font-size: var(--fs-3xl);
            font-weight: var(--fw-black);
            color: var(--ink);
            letter-spacing: var(--ls-tight);
            margin-bottom: 16px; line-height: 1.12;
        }
        .sec-lead {
            font-family: var(--f-body);
            font-size: var(--fs-md);
            font-weight: var(--fw-light);
            line-height: 1.70;
            color: #526370; max-width: 560px;
        }

        /* ═══════════════════════════════════════════════════════════
           FEATURE CARDS
        ═══════════════════════════════════════════════════════════ */
        .feat-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(300px,1fr)); gap: 20px; }
        .feat-card {
            background: #fff; border: 1px solid #e4eeec;
            border-radius: 20px; padding: 32px;
            position: relative; overflow: hidden;
            transition: transform .28s cubic-bezier(.4,0,.2,1), box-shadow .28s, border-color .28s;
        }
        .feat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--forest), var(--aqua));
            opacity: 0; transition: opacity .28s;
        }
        .feat-card:hover { transform: translateY(-8px); box-shadow: 0 22px 52px rgba(8,74,72,.11); border-color: rgba(107,207,203,.32); }
        .feat-card:hover::before { opacity: 1; }
        .feat-icon {
            width: 54px; height: 54px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 21px; margin-bottom: 20px;
        }
        .feat-card h3 {
            font-family: var(--f-display);
            font-size: 18px; font-weight: var(--fw-bold);
            color: var(--ink); margin-bottom: 10px; line-height: 1.3;
        }
        .feat-card p {
            font-family: var(--f-body);
            font-size: var(--fs-base); line-height: 1.65; color: #526370;
        }

        /* ═══════════════════════════════════════════════════════════
           ROLES
        ═══════════════════════════════════════════════════════════ */
        .bg-soft { background: #f5f9f8; }
        .roles-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(240px,1fr)); gap: 20px; }
        .role-card {
            background: #fff; border: 1px solid #e4eeec;
            border-radius: 20px; padding: 32px 26px; text-align: center;
            transition: transform .28s, box-shadow .28s, border-color .28s;
        }
        .role-card:hover { transform: translateY(-6px); box-shadow: 0 18px 44px rgba(8,74,72,.10); border-color: rgba(107,207,203,.28); }
        .role-avatar {
            width: 70px; height: 70px; border-radius: 50%; margin: 0 auto 20px;
            display: flex; align-items: center; justify-content: center; font-size: 26px;
        }
        .role-card h3 {
            font-family: var(--f-display);
            font-size: 18px; font-weight: var(--fw-bold);
            color: var(--ink); margin-bottom: 10px;
        }
        .role-card p {
            font-family: var(--f-body);
            font-size: 14px; line-height: 1.65; color: #526370;
        }
        .role-tag {
            font-family: var(--f-body);
            display: inline-block; margin-top: 16px;
            padding: 4px 12px; border-radius: 100px;
            font-size: var(--fs-xs); font-weight: var(--fw-semi); letter-spacing: 0.08em; text-transform: uppercase;
        }

        /* ═══════════════════════════════════════════════════════════
           WORKFLOW
        ═══════════════════════════════════════════════════════════ */
        .wf-track { display: grid; grid-template-columns: repeat(4,1fr); position: relative; }
        @media (max-width: 768px) { .wf-track { grid-template-columns: 1fr 1fr; row-gap: 40px; } }
        @media (max-width: 480px) { .wf-track { grid-template-columns: 1fr; } }
        .wf-track::before {
            content: ''; position: absolute;
            top: 35px; left: 12.5%; right: 12.5%; height: 2px;
            background: linear-gradient(90deg, var(--forest), var(--aqua), var(--ember), var(--ink));
        }
        @media (max-width: 768px) { .wf-track::before { display: none; } }
        .wf-step { text-align: center; padding: 0 18px; position: relative; z-index: 1; }
        .wf-num {
            font-family: var(--f-body);
            width: 70px; height: 70px; border-radius: 50%; margin: 0 auto 22px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: var(--fw-black); color: #fff;
            box-shadow: 0 8px 22px rgba(0,0,0,.17);
        }
        .wf-step h3 {
            font-family: var(--f-display);
            font-size: 16px; font-weight: var(--fw-bold);
            color: var(--ink); margin-bottom: 8px;
        }
        .wf-step p {
            font-family: var(--f-body);
            font-size: 14px; line-height: 1.62; color: #526370;
        }

        /* ═══════════════════════════════════════════════════════════
           CTA
        ═══════════════════════════════════════════════════════════ */
        .cta-sec {
            background: linear-gradient(140deg, var(--forest) 0%, var(--ink) 100%);
            padding: var(--section-pad) 0; position: relative; overflow: hidden;
        }
        .cta-sec::before {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background-image:
                linear-gradient(rgba(107,207,203,.045) 1px, transparent 1px),
                linear-gradient(90deg, rgba(107,207,203,.045) 1px, transparent 1px);
            background-size: 52px 52px;
        }
        .cta-inner { max-width: 680px; margin: 0 auto; padding: 0 1.75rem; text-align: center; position: relative; z-index: 1; }
        .cta-eyebrow { display: block; color: var(--aqua); margin-bottom: 16px; }
        .cta-title {
            font-family: var(--f-display);
            font-size: var(--fs-3xl); font-weight: var(--fw-black);
            color: #fff; letter-spacing: var(--ls-tight); margin-bottom: 18px;
        }
        .cta-lead {
            font-family: var(--f-body);
            font-size: var(--fs-md); font-weight: var(--fw-light);
            line-height: 1.68; color: rgba(217,244,242,.72); margin-bottom: 38px;
        }
        .btn-cta {
            font-family: var(--f-body);
            font-size: var(--fs-md); font-weight: var(--fw-semi);
            letter-spacing: 0.02em;
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 38px; border-radius: 12px;
            background: var(--ember); color: #fff; text-decoration: none;
            transition: transform .18s, box-shadow .22s, background .2s;
        }
        .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 14px 36px rgba(254,88,11,.46); background: #e74d07; }

        /* ═══════════════════════════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════════════════════════ */
        footer { background: var(--ink); padding: 76px 0 0; }
        .ft-inner { max-width: var(--inner-max); margin: 0 auto; padding: 0 1.75rem; }
        .ft-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr 1.2fr; gap: 52px;
            padding-bottom: 56px; border-bottom: 1px solid rgba(255,255,255,.07);
        }
        @media (max-width: 900px) { .ft-grid { grid-template-columns: 1fr 1fr; gap: 36px; } }
        @media (max-width: 520px) { .ft-grid { grid-template-columns: 1fr; gap: 28px; } }

        /* Footer logo */
        .ft-logo { display: flex; align-items: center; gap: 14px; text-decoration: none; margin-bottom: 18px; }
        .ft-logo-mark {
            width: 52px; height: 52px; border-radius: 50%;
            background: #fff;
            border: none;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            box-shadow:
                0 0 0 2px var(--forest),
                0 0 0 4px rgba(107,207,203,0.35),
                0 4px 16px rgba(8,74,72,0.30);
        }
        .ft-logo-mark img { width: 46px; height: 46px; object-fit: contain; border-radius: 50%; }
        .ft-logo-name {
            font-family: var(--f-display);
            font-size: 17px; font-weight: var(--fw-bold);
            color: #fff; line-height: 1.25; letter-spacing: -0.01em;
        }
        .ft-logo-sub {
            font-family: var(--f-body);
            font-size: var(--fs-xs); font-weight: var(--fw-med);
            letter-spacing: 0.09em; text-transform: uppercase;
            color: rgba(107,207,203,.60);
        }
        .ft-desc {
            font-family: var(--f-body);
            font-size: 14px; line-height: 1.70; color: rgba(255,255,255,.40);
        }

        .ft-col-title {
            font-family: var(--f-body);
            font-size: var(--fs-xs); font-weight: var(--fw-bold);
            letter-spacing: 0.12em; text-transform: uppercase;
            color: rgba(255,255,255,.85); margin-bottom: 20px;
        }
        .ft-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .ft-links a {
            font-family: var(--f-body);
            font-size: 14px; color: rgba(255,255,255,.40); text-decoration: none;
            transition: color .2s;
        }
        .ft-links a:hover { color: var(--aqua); }

        .ft-contact-row { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px; }
        .ft-c-icon { color: var(--aqua); font-size: 13px; margin-top: 2px; flex-shrink: 0; }
        .ft-c-text { font-family: var(--f-body); font-size: 13.5px; color: rgba(255,255,255,.40); }

        .ft-status {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 10px; border-radius: 100px; margin-top: 4px;
            background: rgba(107,207,203,.10); border: 1px solid rgba(107,207,203,.18);
        }
        .ft-status-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--aqua); animation: blink 2.2s ease-in-out infinite; }
        .ft-status-txt { font-family: var(--f-body); font-size: 12px; font-weight: var(--fw-med); color: var(--aqua); }

        .ft-ver {
            font-family: 'Courier New', monospace;
            font-size: 11px; padding: 3px 8px; border-radius: 5px;
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.07);
            color: rgba(255,255,255,.28);
        }
        .ft-row { display: flex; align-items: center; justify-content: space-between; font-size: 13.5px; margin-bottom: 8px; }
        .ft-row-lbl { font-family: var(--f-body); color: rgba(255,255,255,.30); }

        .socials { display: flex; gap: 9px; margin-top: 22px; }
        .soc {
            width: 36px; height: 36px; border-radius: 9px;
            background: rgba(255,255,255,.055); border: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.38); font-size: 14px; text-decoration: none;
            transition: background .2s, color .2s, border-color .2s;
        }
        .soc:hover { background: rgba(107,207,203,.14); color: var(--aqua); border-color: rgba(107,207,203,.28); }

        .ft-bottom {
            padding: 20px 0;
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
        }
        .ft-bottom-txt { font-family: var(--f-body); font-size: 13px; color: rgba(255,255,255,.26); }

        /* ═══════════════════════════════════════════════════════════
           REVEAL ANIMATIONS
        ═══════════════════════════════════════════════════════════ */
        .reveal { opacity: 0; transform: translateY(22px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.in { opacity: 1; transform: translateY(0); }
        .d1 { transition-delay: .10s; }
        .d2 { transition-delay: .20s; }
        .d3 { transition-delay: .30s; }
        .d4 { transition-delay: .40s; }
    </style>
</head>
<body>

<!-- ████████████████████████  NAV  ████████████████████████ -->
<nav id="navbar">
    <div class="nav-inner">

        <!-- Brand / Logo lockup -->
        <a href="#" class="nav-brand" aria-label="Salale University Clearance System">
            <div class="logo-mark">
                <img src="{{ asset('uploads/logos/logo.png') }}" alt="">
            </div>
            <div class="logo-text">
                <span class="name">Salale University</span>
                <span class="tagline">Clearance Management System</span>
            </div>
        </a>

        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#workflow">Workflow</a></li>
            <li><a href="#roles">Roles</a></li>
        </ul>

        <a href="{{ route('login') }}" class="nav-cta">
            Sign In <i class="fas fa-arrow-right" style="font-size:12px;"></i>
        </a>

        <div class="hamburger" id="hamburger" role="button" tabindex="0" aria-label="Open menu">
            <span></span><span></span><span></span>
        </div>
    </div>

    <div class="mobile-menu" id="mobileMenu">
        <a href="#">Home</a>
        <a href="#features">Features</a>
        <a href="#workflow">Workflow</a>
        <a href="#roles">Roles</a>
        <a href="{{ route('login') }}" class="mob-cta">Sign In →</a>
    </div>
</nav>


<!-- ████████████████████████  HERO  ████████████████████████ -->
<section class="hero">
    <div class="hero-glow-tl"></div>
    <div class="hero-glow-bl"></div>
    <div class="hero-grid"></div>

    <div class="hero-inner">
        <!-- Left: headline -->
        <div>
            <div class="hero-badge label-caps">
                <div class="hero-badge-dot"></div>
                Digital Clearance Portal
            </div>

            <h1 class="hero-title">
                Intelligent<br>Clearance for<br><span class="accent">Salale University</span>
            </h1>

            <p class="hero-lead">
                A secure, unified platform for student clearance requests, multi-department approvals, and official certificate delivery — all in one place.
            </p>

            <div class="hero-btns">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    Sign In &ensp;<i class="fas fa-arrow-right" style="font-size:13px;"></i>
                </a>
                <a href="#features" class="btn btn-outline-light">
                    Explore Features
                </a>
            </div>
        </div>

        <!-- Right: glass dashboard panel -->
        <div class="hero-panel">
            <!-- Activity overview -->
            <div class="glass">
                <div class="g-label">Operational Overview</div>
                <div class="g-title">Current clearance activity</div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                    <div>
                        <div class="g-sub" style="margin-bottom:3px;">Requests in progress</div>
                        <div class="g-big-num">1,247</div>
                    </div>
                    <div style="width:50px;height:50px;border-radius:14px;background:linear-gradient(135deg,var(--ember),var(--aqua));display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
                <div class="prog-bar"><div class="prog-fill"></div></div>
                <div style="display:flex;justify-content:space-between;margin-top:6px;">
                    <span class="g-sub">78% capacity utilised</span>
                    <span style="font-family:var(--f-body);font-size:12px;color:var(--aqua);font-weight:600;">Live</span>
                </div>
            </div>

            <!-- Mini stats -->
            <div class="mini-grid">
                <div class="mini">
                    <div class="mini-icon" style="background:rgba(254,88,11,.16);"><i class="fas fa-bolt" style="color:var(--ember);"></i></div>
                    <div class="mini-lbl">Avg. Speed</div>
                    <div class="mini-val">3.2<span> min</span></div>
                </div>
                <div class="mini">
                    <div class="mini-icon" style="background:rgba(107,207,203,.16);"><i class="fas fa-shield-alt" style="color:var(--aqua);"></i></div>
                    <div class="mini-lbl">Security</div>
                    <div class="mini-val">100<span>%</span></div>
                </div>
                <div class="mini">
                    <div class="mini-icon" style="background:rgba(8,74,72,.40);"><i class="fas fa-check-circle" style="color:var(--aqua);"></i></div>
                    <div class="mini-lbl">Success Rate</div>
                    <div class="mini-val">99.8<span>%</span></div>
                </div>
                <div class="mini">
                    <div class="mini-icon" style="background:rgba(254,88,11,.16);"><i class="fas fa-building" style="color:var(--ember);"></i></div>
                    <div class="mini-lbl">Departments</div>
                    <div class="mini-val">18<span>+</span></div>
                </div>
            </div>

            <!-- Benefits -->
            <div class="glass">
                <div class="g-title" style="margin-bottom:16px;">Key benefits</div>
                <div class="benefit-row">
                    <div class="benefit-check" style="background:rgba(254,88,11,.18);"><i class="fas fa-check" style="color:var(--ember);"></i></div>
                    <div><span class="benefit-strong">Instant approvals</span><span class="benefit-sub">Real-time processing across all departments</span></div>
                </div>
                <div class="benefit-row">
                    <div class="benefit-check" style="background:rgba(107,207,203,.18);"><i class="fas fa-check" style="color:var(--aqua);"></i></div>
                    <div><span class="benefit-strong">Digital records</span><span class="benefit-sub">Fully secure, traceable, and auditable</span></div>
                </div>
                <div class="benefit-row">
                    <div class="benefit-check" style="background:rgba(8,74,72,.40);"><i class="fas fa-check" style="color:var(--aqua);"></i></div>
                    <div><span class="benefit-strong">24/7 access</span><span class="benefit-sub">Available anytime, from any device</span></div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ████████████████████  STATS BAND  ████████████████████ -->
<div class="stats-band">
    <div class="stats-band-inner">
        <div class="stats-grid">
            <div class="stat">
                <div class="stat-num">1,<em>247</em></div>
                <div class="stat-lbl">Active requests</div>
            </div>
            <div class="stat">
                <div class="stat-num"><em>18</em>+</div>
                <div class="stat-lbl">Departments</div>
            </div>
            <div class="stat">
                <div class="stat-num">99.<em>8</em>%</div>
                <div class="stat-lbl">Success rate</div>
            </div>
            <div class="stat">
                <div class="stat-num"><em>3.2</em></div>
                <div class="stat-lbl">Min avg. speed</div>
            </div>
            <div class="stat">
                <div class="stat-num">100<em>%</em></div>
                <div class="stat-lbl">Secure platform</div>
            </div>
        </div>
    </div>
</div>


<!-- ████████████████████  FEATURES  ████████████████████ -->
<section class="section" id="features">
    <div class="section-inner">
        <div class="sec-hd">
            <span class="eyebrow">Platform capabilities</span>
            <h2 class="sec-title">Everything you need,<br>in one place</h2>
            <p class="sec-lead">A modern clearance solution designed for student efficiency, department collaboration, and administration control.</p>
        </div>
        <div class="feat-grid">
            <div class="feat-card reveal">
                <div class="feat-icon" style="background:rgba(8,74,72,.09);"><i class="fas fa-file-alt" style="color:var(--forest);"></i></div>
                <h3>Online Clearance Requests</h3>
                <p>Submit clearance requests entirely online — no paper forms, no manual follow-up, no queues.</p>
            </div>
            <div class="feat-card reveal d1">
                <div class="feat-icon" style="background:rgba(107,207,203,.14);"><i class="fas fa-tachometer-alt" style="color:var(--forest);"></i></div>
                <h3>Live Progress Tracking</h3>
                <p>Monitor request status and approval stages with real-time visibility across every department.</p>
            </div>
            <div class="feat-card reveal d2">
                <div class="feat-icon" style="background:rgba(254,88,11,.11);"><i class="fas fa-bell" style="color:var(--ember);"></i></div>
                <h3>Automated Notifications</h3>
                <p>Receive timely email and in-app alerts for approvals, rejections, and status changes instantly.</p>
            </div>
            <div class="feat-card reveal d3">
                <div class="feat-icon" style="background:rgba(8,74,72,.09);"><i class="fas fa-chart-line" style="color:var(--forest);"></i></div>
                <h3>Advanced Reporting</h3>
                <p>Generate actionable reports on clearance activity and departmental performance in seconds.</p>
            </div>
            <div class="feat-card reveal d1">
                <div class="feat-icon" style="background:rgba(107,207,203,.14);"><i class="fas fa-qrcode" style="color:var(--forest);"></i></div>
                <h3>Certificate Verification</h3>
                <p>Securely verify clearance certificates using QR-enabled authentication — instant and tamper-proof.</p>
            </div>
            <div class="feat-card reveal d2">
                <div class="feat-icon" style="background:rgba(0,23,34,.07);"><i class="fas fa-shield-alt" style="color:var(--ink);"></i></div>
                <h3>Secure Data Management</h3>
                <p>Protect all student records with encrypted storage, role-based access control, and full audit trails.</p>
            </div>
        </div>
    </div>
</section>


<!-- ████████████████████  ROLES  ████████████████████ -->
<section class="section bg-soft" id="roles">
    <div class="section-inner">
        <div class="sec-hd">
            <span class="eyebrow">Access levels</span>
            <h2 class="sec-title">Built for every<br>stakeholder</h2>
            <p class="sec-lead">Four distinct roles collaborate seamlessly to make clearance fast, accurate, and accountable.</p>
        </div>
        <div class="roles-grid">
            <div class="role-card reveal">
                <div class="role-avatar" style="background:rgba(107,207,203,.13);"><i class="fas fa-user-graduate" style="color:var(--forest);"></i></div>
                <h3>Student</h3>
                <p>Create requests, track approval stages in real-time, and download official certificates from a single portal.</p>
                <div class="role-tag" style="background:rgba(107,207,203,.12);color:var(--forest);">Self-service</div>
            </div>
            <div class="role-card reveal d1">
                <div class="role-avatar" style="background:rgba(8,74,72,.09);"><i class="fas fa-building" style="color:var(--forest);"></i></div>
                <h3>Department Officer</h3>
                <p>Manage approvals, validate requirements, and communicate status updates to students efficiently on record.</p>
                <div class="role-tag" style="background:rgba(8,74,72,.09);color:var(--forest);">Approver</div>
            </div>
            <div class="role-card reveal d2">
                <div class="role-avatar" style="background:rgba(254,88,11,.11);"><i class="fas fa-certificate" style="color:var(--ember);"></i></div>
                <h3>Registrar</h3>
                <p>Perform final clearance approvals and publish official, cryptographically signed certificates securely.</p>
                <div class="role-tag" style="background:rgba(254,88,11,.11);color:var(--ember);">Finalizer</div>
            </div>
            <div class="role-card reveal d3">
                <div class="role-avatar" style="background:rgba(0,23,34,.07);"><i class="fas fa-user-shield" style="color:var(--ink);"></i></div>
                <h3>Administrator</h3>
                <p>Configure system settings, manage all user accounts, and oversee the entire clearance workflow.</p>
                <div class="role-tag" style="background:rgba(0,23,34,.07);color:var(--ink);">Full control</div>
            </div>
        </div>
    </div>
</section>


<!-- ████████████████████  WORKFLOW  ████████████████████ -->
<section class="section" id="workflow">
    <div class="section-inner">
        <div class="sec-hd">
            <span class="eyebrow">How it works</span>
            <h2 class="sec-title">Clearance in<br>4 clear steps</h2>
            <p class="sec-lead">A streamlined workflow built for speed, transparency, and full compliance — from first request to final certificate.</p>
        </div>
        <div class="wf-track">
            <div class="wf-step reveal">
                <div class="wf-num" style="background:var(--forest);">1</div>
                <h3>Student Submits</h3>
                <p>Students file clearance requests securely through the portal with all required documentation.</p>
            </div>
            <div class="wf-step reveal d1">
                <div class="wf-num" style="background:var(--aqua);color:var(--ink);">2</div>
                <h3>Departments Review</h3>
                <p>Officers validate requirements and approve or return requests with clear, on-record feedback.</p>
            </div>
            <div class="wf-step reveal d2">
                <div class="wf-num" style="background:var(--ember);">3</div>
                <h3>Registrar Finalizes</h3>
                <p>The Registrar performs a final review and formally authorizes the clearance certificate.</p>
            </div>
            <div class="wf-step reveal d3">
                <div class="wf-num" style="background:var(--ink);">4</div>
                <h3>Certificate Delivered</h3>
                <p>The official certificate is generated digitally and available for secure, verified download.</p>
            </div>
        </div>
    </div>
</section>


<!-- ████████████████████  CTA  ████████████████████ -->
<section class="cta-sec">
    <div class="cta-inner">
        <span class="eyebrow cta-eyebrow" style="color:var(--aqua);">Ready to begin?</span>
        <h2 class="cta-title">Start managing clearances with confidence</h2>
        <p class="cta-lead">Request access from your institution administrator and use the portal to initiate, track, and complete student clearances in minutes.</p>
        <a href="{{ route('login') }}" class="btn-cta">
            Sign In Now &ensp;<i class="fas fa-arrow-right" style="font-size:14px;"></i>
        </a>
    </div>
</section>


<!-- ████████████████████  FOOTER  ████████████████████ -->
<footer>
    <div class="ft-inner">
        <div class="ft-grid">
            <!-- Brand -->
            <div>
                <a href="#" class="ft-logo" aria-label="Salale University">
                    <div class="ft-logo-mark">
                        <img src="{{ asset('uploads/logos/logo.png') }}" alt="">
                    </div>
                    <div>
                        <div class="ft-logo-name">Salale University</div>
                        <div class="ft-logo-sub">Clearance System</div>
                    </div>
                </a>
                <p class="ft-desc">A modern digital clearance portal streamlining student graduation and departure processes through secure, transparent workflows.</p>
                <div class="socials">
                    <a href="#" class="soc" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="soc" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="soc" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="soc" aria-label="Telegram"><i class="fab fa-telegram"></i></a>
                </div>
            </div>

            <!-- Links -->
            <div>
                <div class="ft-col-title">Quick Links</div>
                <ul class="ft-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#workflow">Workflow</a></li>
                    <li><a href="#roles">User Roles</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <div class="ft-col-title">Contact</div>
                <div class="ft-contact-row"><i class="fas fa-envelope ft-c-icon"></i><span class="ft-c-text">info@salale.edu.et</span></div>
                <div class="ft-contact-row"><i class="fas fa-phone ft-c-icon"></i><span class="ft-c-text">+251-XXX-XXXX</span></div>
                <div class="ft-contact-row"><i class="fas fa-map-marker-alt ft-c-icon"></i><span class="ft-c-text">Salale University, Ethiopia</span></div>
            </div>

            <!-- System info -->
            <div>
                <div class="ft-col-title">System Info</div>
                <div class="ft-row"><span class="ft-row-lbl">Version</span><span class="ft-ver">v1.0.0</span></div>
                <div class="ft-row"><span class="ft-row-lbl">Platform</span><span style="font-family:var(--f-body);font-size:13px;color:rgba(255,255,255,.38);">Web · Mobile</span></div>
                <div class="ft-row" style="margin-bottom:0;">
                    <span class="ft-row-lbl">Status</span>
                    <div class="ft-status">
                        <div class="ft-status-dot"></div>
                        <span class="ft-status-txt">Active</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="ft-bottom">
            <span class="ft-bottom-txt">&copy; 2024 Salale University. All rights reserved.</span>
            <span class="ft-bottom-txt">Clearance Management System &nbsp;<span class="ft-ver">v1.0.0</span></span>
        </div>
    </div>
</footer>


<script>
    window.scrollTo(0,0);

    /* Navbar scroll */
    const nb = document.getElementById('navbar');
    window.addEventListener('scroll', () => nb.classList.toggle('scrolled', scrollY > 40), {passive:true});

    /* Hamburger */
    const hb = document.getElementById('hamburger');
    const mm = document.getElementById('mobileMenu');
    hb.addEventListener('click', () => { hb.classList.toggle('open'); mm.classList.toggle('open'); });
    mm.querySelectorAll('a').forEach(a => a.addEventListener('click', () => { hb.classList.remove('open'); mm.classList.remove('open'); }));

    /* Smooth scroll */
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const t = a.getAttribute('href');
            if(t !== '#' && document.querySelector(t)) { e.preventDefault(); document.querySelector(t).scrollIntoView({behavior:'smooth',block:'start'}); }
        });
    });

    /* Scroll reveal */
    const ro = new IntersectionObserver(entries => {
        entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); ro.unobserve(e.target); } });
    }, {threshold: 0.12});
    document.querySelectorAll('.reveal').forEach(el => ro.observe(el));

    window.addEventListener('load', () => window.scrollTo(0,0));
</script>
</body>
</html>