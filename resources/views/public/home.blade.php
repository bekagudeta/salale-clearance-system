<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Salale University — Clearance Management System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* ── DESIGN TOKENS ─────────────────────────────── */
    :root {
      /* Logo-derived palette */
      --teal-deep:    #0E7490;   /* shield border deep teal */
      --teal-mid:     #1BA3C6;   /* mid teal of shield */
      --teal-light:   #38C9EB;   /* bright teal accent */
      --green-deep:   #14532D;   /* deep forest green (wheat stems) */
      --green-mid:    #166534;   /* mid green  */
      --green-light:  #22C55E;   /* grass/wheat tip */
      --gold:         #F59E0B;   /* warm amber accent */
      --white:        #FFFFFF;
      --off-white:    #F0FAFB;
      --ink:          #0B1F2A;   /* near-black */
      --muted:        #64748B;

      --f-display:  'Cormorant Garamond', Georgia, serif;
      --f-body:     'Plus Jakarta Sans', system-ui, sans-serif;

      --r-sm: 10px;
      --r-md: 16px;
      --r-lg: 24px;
      --r-xl: 36px;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: var(--f-body);
      color: var(--ink);
      background: #fff;
      overflow-x: hidden;
      line-height: 1.65;
    }

    /* ── NAV ──────────────────────────────────────── */
    #nav {
      position: fixed; top: 0; left: 0; right: 0; z-index: 200;
      transition: all .3s;
      padding: 0;
    }
    #nav.solid {
      background: rgba(255,255,255,.97);
      box-shadow: 0 2px 32px rgba(14,116,144,.12);
      backdrop-filter: blur(20px);
    }
    .nav-bar {
      max-width: 1260px; margin: 0 auto;
      padding: 0 2rem;
      height: 76px;
      display: flex; align-items: center; justify-content: space-between;
    }
    .brand {
      display: flex; align-items: center; gap: 14px;
      text-decoration: none;
    }
    .brand-logo {
      width: 52px; height: 52px; border-radius: 50%;
      box-shadow: 0 0 0 2.5px var(--teal-deep), 0 0 0 5px rgba(27,163,198,.25), 0 4px 18px rgba(14,116,144,.3);
      object-fit: cover;
      background: #fff;
    }
    .brand-text .uni { 
      font-family: var(--f-display);
      font-size: 17px; font-weight: 700; color: var(--ink); line-height: 1.2; letter-spacing: -.01em;
    }
    .brand-text .sub {
      font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: .12em;
      color: var(--teal-deep);
    }
    nav ul { display: flex; gap: 36px; list-style: none; align-items: center; }
    nav ul a {
      font-size: 13.5px; font-weight: 500; color: #3d5560;
      text-decoration: none; letter-spacing: .02em;
      transition: color .2s;
      position: relative; padding-bottom: 3px;
    }
    nav ul a::after {
      content: ''; position: absolute; bottom: -2px; left: 0; width: 0; height: 1.5px;
      background: var(--teal-mid); transition: width .22s;
    }
    nav ul a:hover { color: var(--teal-deep); }
    nav ul a:hover::after { width: 100%; }
    .btn-nav {
      background: var(--teal-deep); color: #fff;
      font-size: 13.5px; font-weight: 600; letter-spacing: .03em;
      padding: 10px 24px; border-radius: var(--r-sm);
      text-decoration: none;
      transition: background .2s, transform .18s, box-shadow .2s;
      display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-nav:hover { background: #0c617d; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(14,116,144,.35); }

    /* Mobile menu toggle */
    .mobile-toggle {
      display: none;
      flex-direction: column;
      gap: 5px;
      cursor: pointer;
      padding: 8px;
      z-index: 300;
    }
    .mobile-toggle span {
      width: 24px;
      height: 2px;
      background: var(--ink);
      border-radius: 2px;
      transition: all .3s ease;
    }
    .mobile-toggle.active span:nth-child(1) {
      transform: rotate(45deg) translate(5px, 5px);
    }
    .mobile-toggle.active span:nth-child(2) {
      opacity: 0;
    }
    .mobile-toggle.active span:nth-child(3) {
      transform: rotate(-45deg) translate(5px, -5px);
    }

    /* Mobile menu */
    .mobile-menu {
      position: fixed;
      top: 0;
      right: -100%;
      width: 280px;
      height: 100vh;
      background: #fff;
      z-index: 250;
      padding: 100px 32px 40px;
      box-shadow: -5px 0 30px rgba(0,0,0,.15);
      transition: right .35s cubic-bezier(.4,0,.2,1);
    }
    .mobile-menu.active {
      right: 0;
    }
    .mobile-menu ul {
      display: flex;
      flex-direction: column;
      gap: 0;
    }
    .mobile-menu li {
      border-bottom: 1px solid #f0f0f0;
    }
    .mobile-menu a {
      display: block;
      padding: 18px 0;
      font-size: 15px;
      color: var(--ink);
      text-decoration: none;
      transition: color .2s;
    }
    .mobile-menu a:hover {
      color: var(--teal-deep);
    }
    .mobile-menu .btn-nav {
      margin-top: 24px;
      width: 100%;
      justify-content: center;
    }
    .mobile-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.5);
      z-index: 240;
      opacity: 0;
      visibility: hidden;
      transition: all .3s ease;
    }
    .mobile-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* ── HERO ─────────────────────────────────────── */
    .hero {
      min-height: 100vh;
      background: linear-gradient(145deg, var(--ink) 0%, #0d2a3a 40%, #0e3d50 100%);
      position: relative; overflow: hidden;
      display: flex; align-items: center;
      padding: 110px 0 80px;
    }

    /* Decorative radial glows */
    .hero-glow-1 {
      position: absolute; border-radius: 50%; pointer-events: none;
      width: 700px; height: 700px;
      background: radial-gradient(circle, rgba(27,163,198,.22) 0%, transparent 65%);
      top: -200px; right: -150px;
    }
    .hero-glow-2 {
      position: absolute; border-radius: 50%; pointer-events: none;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(20,83,45,.50) 0%, transparent 70%);
      bottom: -100px; left: -80px;
    }
    .hero-glow-3 {
      position: absolute; border-radius: 50%; pointer-events: none;
      width: 300px; height: 300px;
      background: radial-gradient(circle, rgba(245,158,11,.08) 0%, transparent 70%);
      bottom: 120px; right: 200px;
    }

    /* Subtle grid lines */
    .hero-grid {
      position: absolute; inset: 0; pointer-events: none;
      background-image:
        linear-gradient(rgba(27,163,198,.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(27,163,198,.03) 1px, transparent 1px);
      background-size: 60px 60px;
    }

    .hero-inner {
      position: relative; z-index: 2;
      max-width: 1260px; margin: 0 auto; padding: 0 2rem;
      display: grid; grid-template-columns: 1fr 1fr; gap: 72px; align-items: center;
    }

    /* Badge */
    .hero-badge {
      display: inline-flex; align-items: center; gap: 9px;
      padding: 7px 16px 7px 10px; border-radius: 100px; margin-bottom: 28px;
      background: rgba(27,163,198,.10); border: 1px solid rgba(27,163,198,.22);
      color: var(--teal-light); font-size: 11px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
    }
    .badge-dot {
      width: 7px; height: 7px; border-radius: 50%;
      background: var(--teal-light); box-shadow: 0 0 10px var(--teal-light);
      animation: pulse 2.2s ease-in-out infinite;
    }
    @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(1.6)} }

    .hero-title {
      font-family: var(--f-display);
      font-size: clamp(2.6rem, 4.5vw, 4rem);
      font-weight: 700; color: #fff;
      letter-spacing: -.02em; line-height: 1.08; margin-bottom: 24px;
    }
    .hero-title .teal { color: var(--teal-light); }
    .hero-title .green { color: #4ADE80; }

    .hero-lead {
      font-size: 16px; font-weight: 300; line-height: 1.75;
      color: rgba(224,244,248,.72); max-width: 460px; margin-bottom: 40px;
    }

    .hero-btns { display: flex; gap: 14px; flex-wrap: wrap; }
    .btn-primary {
      background: linear-gradient(135deg, var(--teal-mid), var(--teal-deep));
      color: #fff; font-size: 15px; font-weight: 600; letter-spacing: .025em;
      padding: 15px 32px; border-radius: var(--r-sm);
      text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
      transition: transform .18s, box-shadow .22s;
      box-shadow: 0 6px 24px rgba(27,163,198,.35);
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 36px rgba(27,163,198,.50); }
    .btn-outline {
      border: 1.5px solid rgba(56,201,235,.35); color: var(--teal-light);
      font-size: 15px; font-weight: 500; padding: 14px 28px; border-radius: var(--r-sm);
      text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
      transition: background .2s, border-color .2s, transform .18s;
    }
    .btn-outline:hover { background: rgba(56,201,235,.10); border-color: var(--teal-light); transform: translateY(-2px); }

    /* Hero right — dashboard panel */
    .hero-panel { display: flex; flex-direction: column; gap: 14px; }

    .card-glass {
      background: rgba(255,255,255,.055);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,.09);
      border-radius: var(--r-lg);
      padding: 24px 26px;
      transition: border-color .25s;
    }
    .card-glass:hover { border-color: rgba(27,163,198,.25); }

    .g-label { font-size: 10.5px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--teal-light); margin-bottom: 4px; }
    .g-title { font-family: var(--f-display); font-size: 17px; font-weight: 700; color: #fff; margin-bottom: 14px; }
    .g-number { font-size: 34px; font-weight: 800; letter-spacing: -.03em; color: #fff; }
    .g-sub { font-size: 12.5px; color: rgba(200,240,248,.5); }

    .progress-bar { height: 5px; border-radius: 99px; background: rgba(255,255,255,.08); overflow: hidden; margin-top: 10px; }
    .progress-fill {
      height: 100%; border-radius: 99px;
      background: linear-gradient(to right, #22C55E, var(--teal-light));
      animation: grow .1s 0s both;
    }
    @keyframes grow { from{width:0} }

    .mini-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .mini-card {
      background: rgba(255,255,255,.045);
      border: 1px solid rgba(255,255,255,.07);
      border-radius: var(--r-md); padding: 16px;
      transition: background .22s, border-color .22s;
    }
    .mini-card:hover { background: rgba(255,255,255,.08); border-color: rgba(27,163,198,.18); }
    .mini-icon {
      width: 34px; height: 34px; border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; margin-bottom: 10px;
    }
    .mini-label { font-size: 10.5px; color: rgba(200,240,248,.45); margin-bottom: 2px; }
    .mini-val { font-size: 22px; font-weight: 800; letter-spacing: -.03em; color: #fff; }
    .mini-val span { font-size: 13px; font-weight: 400; color: rgba(200,240,248,.45); }

    .benefit { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 13px; }
    .benefit:last-child { margin-bottom: 0; }
    .bcheck { width: 22px; height: 22px; border-radius: 6px; flex-shrink: 0; margin-top: 2px; display: flex; align-items: center; justify-content: center; font-size: 9px; }
    .bstrong { font-size: 13px; font-weight: 600; color: #fff; display: block; line-height: 1.3; }
    .bsub { font-size: 11.5px; color: rgba(200,240,248,.45); }

    /* ── STATS BAND ───────────────────────────────── */
    .stats-band {
      background: linear-gradient(135deg, var(--teal-deep) 0%, #0d6580 100%);
      padding: 52px 0;
    }
    .stats-inner { max-width: 1260px; margin: 0 auto; padding: 0 2rem; }
    .stats-row { display: grid; grid-template-columns: repeat(5, 1fr); }
    .stat-item {
      text-align: center; padding: 14px 16px;
      border-right: 1px solid rgba(255,255,255,.12);
    }
    .stat-item:last-child { border-right: none; }
    .stat-num { font-size: 38px; font-weight: 800; letter-spacing: -.03em; color: #fff; line-height: 1; margin-bottom: 6px; }
    .stat-num em { color: var(--teal-light); font-style: normal; }
    .stat-lbl { font-size: 12.5px; color: rgba(200,240,248,.55); }

    /* ── SECTIONS ─────────────────────────────────── */
    .section { padding: 96px 0; }
    .section-inner { max-width: 1260px; margin: 0 auto; padding: 0 2rem; }
    .section-alt { background: var(--off-white); }

    .sec-tag { font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--teal-deep); margin-bottom: 14px; display: inline-block; }
    .sec-h { font-family: var(--f-display); font-size: clamp(1.9rem, 3vw, 2.7rem); font-weight: 700; color: var(--ink); line-height: 1.12; letter-spacing: -.02em; margin-bottom: 16px; }
    .sec-lead { font-size: 16px; font-weight: 300; color: var(--muted); line-height: 1.72; max-width: 540px; }

    /* ── FEATURE CARDS ────────────────────────────── */
    .feat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 22px; margin-top: 52px; }
    .feat-card {
      background: #fff; border: 1.5px solid #e2eff2;
      border-radius: var(--r-lg); padding: 34px;
      position: relative; overflow: hidden;
      transition: transform .28s cubic-bezier(.4,0,.2,1), box-shadow .28s, border-color .28s;
    }
    .feat-card::after {
      content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
      background: linear-gradient(90deg, var(--teal-deep), var(--green-light));
      opacity: 0; transition: opacity .25s;
    }
    .feat-card:hover { transform: translateY(-7px); box-shadow: 0 24px 56px rgba(14,116,144,.11); border-color: rgba(27,163,198,.3); }
    .feat-card:hover::after { opacity: 1; }

    .feat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 20px; }
    .feat-card h3 { font-family: var(--f-display); font-size: 19px; font-weight: 700; color: var(--ink); margin-bottom: 10px; }
    .feat-card p { font-size: 14.5px; line-height: 1.65; color: var(--muted); }

    /* ── ROLES ────────────────────────────────────── */
    .roles-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 22px; margin-top: 52px; }
    .role-card {
      background: #fff; border: 1.5px solid #e2eff2; border-radius: var(--r-lg);
      padding: 34px 28px; text-align: center;
      transition: transform .28s, box-shadow .28s, border-color .28s;
    }
    .role-card:hover { transform: translateY(-6px); box-shadow: 0 18px 48px rgba(14,116,144,.10); border-color: rgba(27,163,198,.28); }
    .role-avatar { width: 70px; height: 70px; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; font-size: 26px; }
    .role-card h3 { font-family: var(--f-display); font-size: 19px; font-weight: 700; color: var(--ink); margin-bottom: 10px; }
    .role-card p { font-size: 14px; line-height: 1.65; color: var(--muted); }
    .role-tag { display: inline-block; margin-top: 16px; padding: 5px 14px; border-radius: 100px; font-size: 10.5px; font-weight: 700; letter-spacing: .09em; text-transform: uppercase; }

    /* ── WORKFLOW ─────────────────────────────────── */
    .wf-wrap { display: grid; grid-template-columns: repeat(4, 1fr); position: relative; margin-top: 56px; }
    .wf-wrap::before {
      content: ''; position: absolute;
      top: 35px; left: 12.5%; right: 12.5%; height: 2px;
      background: linear-gradient(90deg, var(--teal-deep), var(--teal-light), #22C55E, var(--ink));
    }
    .wf-step { text-align: center; padding: 0 16px; position: relative; z-index: 1; }
    .wf-circle { width: 70px; height: 70px; border-radius: 50%; margin: 0 auto 22px; display: flex; align-items: center; justify-content: center; font-size: 23px; font-weight: 800; color: #fff; box-shadow: 0 8px 22px rgba(0,0,0,.18); }
    .wf-step h3 { font-family: var(--f-display); font-size: 17px; font-weight: 700; color: var(--ink); margin-bottom: 8px; }
    .wf-step p { font-size: 13.5px; line-height: 1.62; color: var(--muted); }

    /* ── CTA ──────────────────────────────────────── */
    .cta-sec {
      background: linear-gradient(145deg, var(--green-deep) 0%, #0c3d28 40%, var(--ink) 100%);
      padding: 96px 0; position: relative; overflow: hidden;
    }
    .cta-sec::before {
      content: ''; position: absolute; inset: 0; pointer-events: none;
      background-image: linear-gradient(rgba(27,163,198,.04) 1px, transparent 1px), linear-gradient(90deg, rgba(27,163,198,.04) 1px, transparent 1px);
      background-size: 52px 52px;
    }
    .cta-inner { max-width: 680px; margin: 0 auto; padding: 0 2rem; text-align: center; position: relative; z-index: 1; }
    .cta-tag { color: #4ADE80; font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; margin-bottom: 18px; display: block; }
    .cta-h { font-family: var(--f-display); font-size: clamp(2rem, 3.5vw, 2.8rem); font-weight: 700; color: #fff; letter-spacing: -.02em; margin-bottom: 18px; }
    .cta-p { font-size: 16px; font-weight: 300; color: rgba(200,240,248,.68); line-height: 1.72; margin-bottom: 40px; }
    .btn-cta {
      background: linear-gradient(135deg, #22C55E, #16a34a);
      color: #fff; font-size: 16px; font-weight: 600; letter-spacing: .02em;
      padding: 17px 42px; border-radius: var(--r-sm);
      text-decoration: none; display: inline-flex; align-items: center; gap: 10px;
      transition: transform .18s, box-shadow .22s;
      box-shadow: 0 6px 24px rgba(34,197,94,.3);
    }
    .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 14px 38px rgba(34,197,94,.48); }

    /* ── FOOTER ───────────────────────────────────── */
    footer { background: var(--ink); padding: 72px 0 0; }
    .ft-inner { max-width: 1260px; margin: 0 auto; padding: 0 2rem; }
    .ft-top {
      display: grid; grid-template-columns: 2fr 1fr 1fr 1.2fr; gap: 48px;
      padding-bottom: 52px; border-bottom: 1px solid rgba(255,255,255,.07);
    }
    .ft-brand { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; text-decoration: none; }
    .ft-logo { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; box-shadow: 0 0 0 2px var(--teal-deep), 0 0 0 4.5px rgba(27,163,198,.28); }
    .ft-name { font-family: var(--f-display); font-size: 16px; font-weight: 700; color: #fff; }
    .ft-sub { font-size: 10px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(56,201,235,.55); }
    .ft-desc { font-size: 13.5px; line-height: 1.72; color: rgba(255,255,255,.38); max-width: 280px; }
    .ft-col-ttl { font-size: 10.5px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.82); margin-bottom: 20px; }
    .ft-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
    .ft-links a { font-size: 13.5px; color: rgba(255,255,255,.38); text-decoration: none; transition: color .2s; }
    .ft-links a:hover { color: var(--teal-light); }
    .ft-contact-row { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 9px; }
    .ft-c-icon { color: var(--teal-light); font-size: 12px; margin-top: 2px; }
    .ft-c-txt { font-size: 13px; color: rgba(255,255,255,.38); }
    .ft-status-pill {
      display: inline-flex; align-items: center; gap: 6px; margin-top: 6px;
      padding: 4px 11px; border-radius: 100px;
      background: rgba(27,163,198,.10); border: 1px solid rgba(27,163,198,.2);
    }
    .ft-status-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--teal-light); animation: pulse 2.2s infinite; }
    .ft-status-txt { font-size: 11.5px; font-weight: 600; color: var(--teal-light); }
    .ft-socials { display: flex; gap: 8px; margin-top: 20px; }
    .ft-soc {
      width: 34px; height: 34px; border-radius: 8px;
      background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
      display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,.35); font-size: 13px; text-decoration: none;
      transition: background .2s, color .2s, border-color .2s;
    }
    .ft-soc:hover { background: rgba(27,163,198,.15); color: var(--teal-light); border-color: rgba(27,163,198,.3); }
    .ft-row-info { display: flex; align-items: center; justify-content: space-between; margin-bottom: 7px; }
    .ft-row-lbl { font-size: 12.5px; color: rgba(255,255,255,.28); }
    .ft-badge { font-family: 'Courier New', monospace; font-size: 10.5px; padding: 3px 8px; border-radius: 5px; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08); color: rgba(255,255,255,.28); }
    .ft-bottom { padding: 20px 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; }
    .ft-bottom-txt { font-size: 12.5px; color: rgba(255,255,255,.25); }

    /* ── REVEAL ANIMATION ─────────────────────────── */
    .reveal { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
    .reveal.in { opacity: 1; transform: none; }
    .d1 { transition-delay: .1s; } .d2 { transition-delay: .2s; } .d3 { transition-delay: .3s; } .d4 { transition-delay: .4s; }

    /* ── RESPONSIVE ───────────────────────────────── */
    @media (max-width: 1024px) { .hero-inner { grid-template-columns: 1fr; gap: 52px; } }
    @media (max-width: 768px) {
      .mobile-toggle { display: flex; }
      nav ul, .btn-nav { display: none; }
      .stats-row { grid-template-columns: repeat(2,1fr); gap: 1px; background: rgba(255,255,255,.1); }
      .wf-wrap { grid-template-columns: 1fr 1fr; row-gap: 40px; }
      .wf-wrap::before { display: none; }
      .ft-top { grid-template-columns: 1fr 1fr; gap: 32px; }
    }
    @media (max-width: 520px) {
      .ft-top { grid-template-columns: 1fr; }
      .wf-wrap { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<!-- ────────────────────── NAV ────────────────────── -->
<nav id="nav">
  <div class="nav-bar">
    <a href="{{ route('home') }}" class="brand">
      <img src="{{ asset('uploads/logos/logo.png') }}" class="brand-logo" alt="Salale University Logo">
      <div class="brand-text">
        <div class="uni">Salale University</div>
        <div class="sub">Clearance Management System</div>
      </div>
    </a>
    <ul>
      <li><a href="{{ route('home') }}">Home</a></li>
      <li><a href="#features">Features</a></li>
      <li><a href="#workflow">Workflow</a></li>
      <li><a href="#roles">Roles</a></li>
      <li><a href="{{ route('verify') }}">Verify Certificate</a></li>
    </ul>
    <a href="{{ route('login') }}" class="btn-nav">Sign In &nbsp;<i class="fas fa-arrow-right" style="font-size:11px;"></i></a>
    <div class="mobile-toggle" id="mobileToggle">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-overlay" id="mobileOverlay"></div>
<div class="mobile-menu" id="mobileMenu">
  <ul>
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="#features">Features</a></li>
    <li><a href="#workflow">Workflow</a></li>
    <li><a href="#roles">Roles</a></li>
    <li><a href="{{ route('verify') }}">Verify Certificate</a></li>
  </ul>
  <a href="{{ route('login') }}" class="btn-nav">Sign In &nbsp;<i class="fas fa-arrow-right" style="font-size:11px;"></i></a>
</div>

<!-- ────────────────────── HERO ────────────────────── -->
<section class="hero">
  <div class="hero-glow-1"></div>
  <div class="hero-glow-2"></div>
  <div class="hero-glow-3"></div>
  <div class="hero-grid"></div>

  <div class="hero-inner">
    <!-- Left -->
    <div>
      <div class="hero-badge">
        <div class="badge-dot"></div>
        Digital Clearance Portal · Salale University
      </div>

      <h1 class="hero-title">
        Intelligent<br>Clearance for<br><span class="teal">Salale</span> <span class="green">University</span>
      </h1>

      <p class="hero-lead">
        A secure, unified portal for student clearance requests, sequential department approvals, and QR-verifiable certificate delivery — fully digital and paperless across 16 university departments.
      </p>

      <div class="hero-btns">
        <a href="{{ route('login') }}" class="btn-primary">
          Sign In &ensp;<i class="fas fa-arrow-right" style="font-size:13px;"></i>
        </a>
        <a href="{{ route('verify') }}" class="btn-outline">
          Verify a Certificate <i class="fas fa-qrcode" style="font-size:12px;"></i>
        </a>
      </div>
    </div>

    <!-- Right — glass dashboard -->
    <div class="hero-panel">
      <!-- Sequential approval flow -->
      <div class="card-glass">
        <div class="g-label">Sequential Approval Flow</div>
        <div class="g-title">Academic gate, then service units</div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
          <div>
            <div class="g-sub" style="margin-bottom:3px;">Departments in the clearance chain</div>
            <div class="g-number">16</div>
          </div>
          <div style="width:50px;height:50px;border-radius:14px;background:linear-gradient(135deg,var(--teal-mid),#22C55E);display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;">
            <i class="fas fa-sitemap"></i>
          </div>
        </div>
        <div class="progress-bar"><div class="progress-fill" style="width:60%;"></div></div>
        <div style="display:flex;justify-content:space-between;margin-top:7px;">
          <span class="g-sub">Academic head approves first</span>
          <span style="font-size:11.5px;color:var(--teal-light);font-weight:700;">Then service units</span>
        </div>
      </div>

      <!-- Mini stats -->
      <div class="mini-stats">
        <div class="mini-card">
          <div class="mini-icon" style="background:rgba(27,163,198,.18);"><i class="fas fa-building" style="color:var(--teal-light);"></i></div>
          <div class="mini-label">Departments</div>
          <div class="mini-val">16</div>
        </div>
        <div class="mini-card">
          <div class="mini-icon" style="background:rgba(34,197,94,.18);"><i class="fas fa-graduation-cap" style="color:#4ADE80;"></i></div>
          <div class="mini-label">Academic Units</div>
          <div class="mini-val">6</div>
        </div>
        <div class="mini-card">
          <div class="mini-icon" style="background:rgba(245,158,11,.18);"><i class="fas fa-concierge-bell" style="color:#FCD34D;"></i></div>
          <div class="mini-label">Service Units</div>
          <div class="mini-val">10</div>
        </div>
        <div class="mini-card">
          <div class="mini-icon" style="background:rgba(27,163,198,.15);"><i class="fas fa-users" style="color:var(--teal-light);"></i></div>
          <div class="mini-label">User Roles</div>
          <div class="mini-val">4</div>
        </div>
      </div>

      <!-- Key benefits -->
      <div class="card-glass">
        <div class="g-title" style="margin-bottom:16px;">Key benefits</div>
        <div class="benefit">
          <div class="bcheck" style="background:rgba(27,163,198,.2);"><i class="fas fa-check" style="color:var(--teal-light);font-size:10px;"></i></div>
          <div><span class="bstrong">Instant approvals</span><span class="bsub">Real-time processing across all departments</span></div>
        </div>
        <div class="benefit">
          <div class="bcheck" style="background:rgba(34,197,94,.2);"><i class="fas fa-check" style="color:#4ADE80;font-size:10px;"></i></div>
          <div><span class="bstrong">Digital records</span><span class="bsub">Fully secure, traceable, and auditable</span></div>
        </div>
        <div class="benefit">
          <div class="bcheck" style="background:rgba(245,158,11,.2);"><i class="fas fa-check" style="color:#FCD34D;font-size:10px;"></i></div>
          <div><span class="bstrong">24 / 7 access</span><span class="bsub">Available anytime, from any device</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ────────────────── STATS BAND ────────────────── -->
<div class="stats-band">
  <div class="stats-inner">
    <div class="stats-row">
      <div class="stat-item"><div class="stat-num"><em>16</em></div><div class="stat-lbl">Departments</div></div>
      <div class="stat-item"><div class="stat-num"><em>6</em></div><div class="stat-lbl">Academic units</div></div>
      <div class="stat-item"><div class="stat-num"><em>10</em></div><div class="stat-lbl">Service units</div></div>
      <div class="stat-item"><div class="stat-num"><em>4</em></div><div class="stat-lbl">User roles</div></div>
      <div class="stat-item"><div class="stat-num">QR<em>✓</em></div><div class="stat-lbl">Verifiable certificates</div></div>
    </div>
  </div>
</div>

<!-- ─────────────────── FEATURES ─────────────────── -->
<section class="section" id="features">
  <div class="section-inner">
    <div style="margin-bottom:4px;" class="reveal">
      <span class="sec-tag">Platform capabilities</span>
      <h2 class="sec-h">Everything you need,<br>in one place</h2>
      <p class="sec-lead">A modern clearance solution designed for student efficiency, department collaboration, and administration control.</p>
    </div>
    <div class="feat-grid">
      <div class="feat-card reveal">
        <div class="feat-icon" style="background:rgba(14,116,144,.09);"><i class="fas fa-file-alt" style="color:var(--teal-deep);"></i></div>
        <h3>Online Clearance Requests</h3>
        <p>Submit clearance requests entirely online — no paper forms, no manual follow-up, no queues.</p>
      </div>
      <div class="feat-card reveal d1">
        <div class="feat-icon" style="background:rgba(27,163,198,.12);"><i class="fas fa-tachometer-alt" style="color:var(--teal-mid);"></i></div>
        <h3>Live Progress Tracking</h3>
        <p>Monitor request status and approval stages with real-time visibility across every department.</p>
      </div>
      <div class="feat-card reveal d2">
        <div class="feat-icon" style="background:rgba(34,197,94,.12);"><i class="fas fa-bell" style="color:var(--green-mid);"></i></div>
        <h3>Automated Notifications</h3>
        <p>Receive timely email and in-app alerts for approvals, rejections, and status changes instantly.</p>
      </div>
      <div class="feat-card reveal d3">
        <div class="feat-icon" style="background:rgba(14,116,144,.09);"><i class="fas fa-chart-line" style="color:var(--teal-deep);"></i></div>
        <h3>Advanced Reporting</h3>
        <p>Generate actionable reports on clearance activity and departmental performance in seconds.</p>
      </div>
      <div class="feat-card reveal d1">
        <div class="feat-icon" style="background:rgba(27,163,198,.12);"><i class="fas fa-qrcode" style="color:var(--teal-mid);"></i></div>
        <h3>Certificate Verification</h3>
        <p>Securely verify clearance certificates using QR-enabled authentication — instant and tamper-proof.</p>
      </div>
      <div class="feat-card reveal d2">
        <div class="feat-icon" style="background:rgba(20,83,45,.08);"><i class="fas fa-shield-alt" style="color:var(--green-deep);"></i></div>
        <h3>Secure Data Management</h3>
        <p>Protect all student records with encrypted storage, role-based access control, and full audit trails.</p>
      </div>
    </div>
  </div>
</section>

<!-- ──────────────────── ROLES ──────────────────── -->
<section class="section section-alt" id="roles">
  <div class="section-inner">
    <div class="reveal" style="margin-bottom:4px;">
      <span class="sec-tag">Access levels</span>
      <h2 class="sec-h">Built for every<br>stakeholder</h2>
      <p class="sec-lead">Four distinct roles collaborate seamlessly to make clearance fast, accurate, and accountable.</p>
    </div>
    <div class="roles-grid">
      <div class="role-card reveal">
        <div class="role-avatar" style="background:rgba(27,163,198,.12);"><i class="fas fa-user-graduate" style="color:var(--teal-deep);"></i></div>
        <h3>Student</h3>
        <p>Create requests, track approval stages in real-time, and download official certificates from a single portal.</p>
        <div class="role-tag" style="background:rgba(27,163,198,.12);color:var(--teal-deep);">Self-service</div>
      </div>
      <div class="role-card reveal d1">
        <div class="role-avatar" style="background:rgba(14,116,144,.10);"><i class="fas fa-building" style="color:var(--teal-deep);"></i></div>
        <h3>Department Officer</h3>
        <p>Manage approvals, validate requirements, and communicate status updates to students efficiently on record.</p>
        <div class="role-tag" style="background:rgba(14,116,144,.10);color:var(--teal-deep);">Approver</div>
      </div>
      <div class="role-card reveal d2">
        <div class="role-avatar" style="background:rgba(34,197,94,.12);"><i class="fas fa-certificate" style="color:var(--green-mid);"></i></div>
        <h3>Registrar</h3>
        <p>Perform final clearance approvals and publish official, cryptographically signed certificates securely.</p>
        <div class="role-tag" style="background:rgba(34,197,94,.12);color:var(--green-mid);">Finalizer</div>
      </div>
      <div class="role-card reveal d3">
        <div class="role-avatar" style="background:rgba(245,158,11,.12);"><i class="fas fa-user-shield" style="color:#D97706;"></i></div>
        <h3>Administrator</h3>
        <p>Configure system settings, manage all user accounts, and oversee the entire clearance workflow.</p>
        <div class="role-tag" style="background:rgba(245,158,11,.12);color:#D97706;">Full control</div>
      </div>
    </div>
  </div>
</section>

<!-- ─────────────────── WORKFLOW ─────────────────── -->
<section class="section" id="workflow">
  <div class="section-inner">
    <div class="reveal" style="margin-bottom:4px;">
      <span class="sec-tag">How it works</span>
      <h2 class="sec-h">Clearance in<br>4 clear steps</h2>
      <p class="sec-lead">A streamlined workflow built for speed, transparency, and full compliance — from first request to final certificate.</p>
    </div>
    <div class="wf-wrap">
      <div class="wf-step reveal">
        <div class="wf-circle" style="background:var(--teal-deep);">1</div>
        <h3>Student Submits</h3>
        <p>Students file clearance requests securely through the portal with all required documentation.</p>
      </div>
      <div class="wf-step reveal d1">
        <div class="wf-circle" style="background:var(--teal-mid);">2</div>
        <h3>Departments Approve</h3>
        <p>The academic head clears first; service units — library, finance, housing and more — then approve in turn.</p>
      </div>
      <div class="wf-step reveal d2">
        <div class="wf-circle" style="background:var(--green-mid);">3</div>
        <h3>Registrar Finalizes</h3>
        <p>The Registrar performs a final review and formally authorizes the clearance certificate.</p>
      </div>
      <div class="wf-step reveal d3">
        <div class="wf-circle" style="background:var(--ink);">4</div>
        <h3>Certificate Delivered</h3>
        <p>The official certificate is generated digitally and available for secure, verified download.</p>
      </div>
    </div>
  </div>
</section>

<!-- ──────────────────── CTA ──────────────────── -->
<section class="cta-sec">
  <div class="cta-inner">
    <span class="cta-tag">Ready to begin?</span>
    <h2 class="cta-h">Start managing clearances<br>with confidence</h2>
    <p class="cta-p">Request access from your institution administrator and use the portal to initiate, track, and complete student clearances in minutes.</p>
    <a href="#" class="btn-cta">
      Sign In Now &ensp;<i class="fas fa-arrow-right" style="font-size:14px;"></i>
    </a>
  </div>
</section>

<!-- ─────────────────── FOOTER ─────────────────── -->
<footer>
  <div class="ft-inner">
    <div class="ft-top">
      <!-- Brand col -->
      <div>
        <a href="{{ route('home') }}" class="ft-brand">
          <img src="{{ asset('uploads/logos/logo.png') }}" class="ft-logo" alt="Salale University">
          <div>
            <div class="ft-name">Salale University</div>
            <div class="ft-sub">Clearance System</div>
          </div>
        </a>
        <p class="ft-desc">A modern digital clearance portal streamlining student graduation and departure processes through secure, transparent workflows.</p>
        <div class="ft-socials">
          <a href="#" class="ft-soc"><i class="fab fa-facebook"></i></a>
          <a href="#" class="ft-soc"><i class="fab fa-twitter"></i></a>
          <a href="#" class="ft-soc"><i class="fab fa-linkedin"></i></a>
          <a href="#" class="ft-soc"><i class="fab fa-telegram"></i></a>
        </div>
      </div>
      <!-- Quick links -->
      <div>
        <div class="ft-col-ttl">Quick Links</div>
        <ul class="ft-links">
          <li><a href="#">About Us</a></li>
          <li><a href="#features">Features</a></li>
          <li><a href="#workflow">Workflow</a></li>
          <li><a href="#roles">User Roles</a></li>
        </ul>
      </div>
      <!-- Contact -->
      <div>
        <div class="ft-col-ttl">Contact</div>
        <div class="ft-contact-row"><i class="fas fa-envelope ft-c-icon"></i><span class="ft-c-txt">info@salale.edu.et</span></div>
        <div class="ft-contact-row"><i class="fas fa-phone ft-c-icon"></i><span class="ft-c-txt">+251-XXX-XXXX</span></div>
        <div class="ft-contact-row"><i class="fas fa-map-marker-alt ft-c-icon"></i><span class="ft-c-txt">Salale University, Ethiopia</span></div>
      </div>
      <!-- System info -->
      <div>
        <div class="ft-col-ttl">System Info</div>
        <div class="ft-row-info"><span class="ft-row-lbl">Version</span><span class="ft-badge">v1.0.0</span></div>
        <div class="ft-row-info"><span class="ft-row-lbl">Platform</span><span style="font-size:12.5px;color:rgba(255,255,255,.35);">Web · Mobile</span></div>
        <div class="ft-row-info" style="margin-bottom:0;">
          <span class="ft-row-lbl">Status</span>
          <div class="ft-status-pill"><div class="ft-status-dot"></div><span class="ft-status-txt">Active</span></div>
        </div>
      </div>
    </div>
    <div class="ft-bottom">
      <span class="ft-bottom-txt">© 2025 Salale University. All rights reserved.</span>
      <span class="ft-bottom-txt">Clearance Management System &nbsp;<span class="ft-badge">v1.0.0</span></span>
    </div>
  </div>
</footer>

<script>
  // Scroll to top on load
  window.scrollTo(0, 0);

  // Navbar gets solid on scroll
  const nav = document.getElementById('nav');
  window.addEventListener('scroll', () => {
    nav.classList.toggle('solid', scrollY > 40);
  }, {passive: true});

  // Scroll reveal
  const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('in'); observer.unobserve(e.target); }
    });
  }, {threshold: 0.12});
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const t = a.getAttribute('href');
      if (t !== '#' && document.querySelector(t)) {
        e.preventDefault();
        document.querySelector(t).scrollIntoView({behavior: 'smooth'});
      }
    });
  });

  // Animate progress fill on load
  window.addEventListener('load', () => {
    window.scrollTo(0, 0);
    document.querySelectorAll('.progress-fill').forEach(el => {
      const w = el.style.width;
      el.style.width = '0';
      setTimeout(() => { el.style.transition = 'width 1.8s cubic-bezier(.4,0,.2,1) .5s'; el.style.width = w; }, 100);
    });
  });

  // Mobile menu toggle
  const mobileToggle = document.getElementById('mobileToggle');
  const mobileMenu = document.getElementById('mobileMenu');
  const mobileOverlay = document.getElementById('mobileOverlay');

  if (mobileToggle && mobileMenu && mobileOverlay) {
    mobileToggle.addEventListener('click', () => {
      mobileToggle.classList.toggle('active');
      mobileMenu.classList.toggle('active');
      mobileOverlay.classList.toggle('active');
      document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
    });

    mobileOverlay.addEventListener('click', () => {
      mobileToggle.classList.remove('active');
      mobileMenu.classList.remove('active');
      mobileOverlay.classList.remove('active');
      document.body.style.overflow = '';
    });

    // Close menu when clicking a link
    mobileMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        mobileToggle.classList.remove('active');
        mobileMenu.classList.remove('active');
        mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';
      });
    });
  }
</script>
</body>
</html>