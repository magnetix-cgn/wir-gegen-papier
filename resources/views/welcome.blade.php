<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Wir gegen Papier fordert ein Wahlrecht: Rechnungen und vergleichbare Dokumente digital erhalten, wenn Empfänger das wollen.">
  <title>Wir gegen Papier - Digital, wenn ich will.</title>
  <style>
    :root {
      color-scheme: light;
      --ink: #151d20;
      --muted: #56656b;
      --paper: #f8f5ef;
      --panel: #ffffff;
      --line: #d8dfdc;
      --green: #168a5b;
      --red: #c43a31;
      --blue: #2464a8;
      --shadow: rgba(21, 29, 32, .09);
    }
    :root[data-theme="dark"] {
      color-scheme: dark;
      --ink: #edf7f2;
      --muted: #a9bbb4;
      --paper: #101816;
      --panel: #17231f;
      --line: #2d4139;
      --green: #59d19b;
      --red: #ff796f;
      --blue: #7fb6ee;
      --shadow: rgba(0, 0, 0, .35);
    }
    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
      margin: 0;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: var(--paper);
      color: var(--ink);
      line-height: 1.55;
    }
    .topbar {
      position: sticky;
      top: 0;
      z-index: 20;
      background: color-mix(in srgb, var(--paper) 88%, transparent);
      border-bottom: 1px solid var(--line);
      backdrop-filter: blur(16px);
    }
    .topbar-inner {
      width: min(100% - 32px, 1120px);
      min-height: 64px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 18px;
    }
    .brand {
      color: var(--ink);
      text-decoration: none;
      font-weight: 900;
      letter-spacing: 0;
    }
    nav {
      display: flex;
      align-items: center;
      gap: 12px;
      color: var(--muted);
      font-size: .95rem;
    }
    nav a {
      color: inherit;
      text-decoration: none;
      font-weight: 750;
    }
    .theme-toggle {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      min-height: 38px;
      padding: 0 12px;
      border: 1px solid var(--line);
      border-radius: 999px;
      background: var(--panel);
      color: var(--ink);
      cursor: pointer;
      font: inherit;
      font-weight: 800;
    }
    main {
      width: min(100% - 32px, 1120px);
      margin: 0 auto;
    }
    .hero {
      min-height: calc(100vh - 64px);
      display: grid;
      grid-template-columns: 1.1fr .9fr;
      gap: 46px;
      align-items: center;
      padding: 58px 0;
    }
    .eyebrow {
      color: var(--green);
      font-weight: 900;
      letter-spacing: .08em;
      text-transform: uppercase;
      font-size: .82rem;
    }
    h1 {
      margin: 12px 0 16px;
      font-size: clamp(3.25rem, 8.6vw, 7.2rem);
      line-height: .9;
      letter-spacing: 0;
    }
    .claim {
      display: inline-flex;
      margin: 0 0 24px;
      padding: 9px 13px;
      background: var(--ink);
      color: var(--paper);
      border-radius: 6px;
      font-size: clamp(1.2rem, 2.4vw, 1.7rem);
      font-weight: 900;
    }
    .lead {
      max-width: 700px;
      color: var(--muted);
      font-size: clamp(1.12rem, 2vw, 1.42rem);
    }
    .punch {
      margin-top: 22px;
      color: var(--red);
      font-size: clamp(1.45rem, 4vw, 3rem);
      font-weight: 950;
      line-height: 1;
    }
    .actions {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 30px;
    }
    .button {
      display: inline-flex;
      align-items: center;
      min-height: 46px;
      padding: 0 18px;
      border: 1px solid var(--ink);
      border-radius: 6px;
      color: var(--ink);
      text-decoration: none;
      font-weight: 850;
    }
    .button.primary {
      background: var(--ink);
      color: var(--paper);
    }
    .supporter-form {
      display: grid;
      grid-template-columns: minmax(220px, 1fr) auto;
      gap: 10px;
      margin-top: 26px;
      max-width: 680px;
    }
    .supporter-form label {
      grid-column: 1 / -1;
      font-weight: 900;
    }
    .supporter-form input {
      min-height: 48px;
      border: 1px solid var(--line);
      border-radius: 6px;
      background: var(--panel);
      color: var(--ink);
      padding: 0 13px;
      font: inherit;
    }
    .supporter-form button {
      min-height: 48px;
      border: 1px solid var(--ink);
      border-radius: 6px;
      background: var(--ink);
      color: var(--paper);
      padding: 0 18px;
      font: inherit;
      font-weight: 850;
      cursor: pointer;
    }
    .form-note {
      grid-column: 1 / -1;
      margin: 0;
      color: var(--muted);
      font-size: .95rem;
    }
    .supporter-count {
      display: inline-flex;
      width: fit-content;
      margin-top: 18px;
      padding: 8px 12px;
      border: 1px solid var(--line);
      border-radius: 6px;
      background: var(--panel);
      font-weight: 900;
    }
    .validation-error {
      grid-column: 1 / -1;
      margin: 0;
      color: var(--red);
      font-weight: 800;
    }
    .poster {
      min-height: 460px;
      border: 1px solid var(--line);
      background: var(--panel);
      box-shadow: 0 22px 70px var(--shadow);
      display: grid;
      place-items: center;
      padding: 30px;
    }
    .poster-card {
      width: min(100%, 360px);
      aspect-ratio: 4 / 5;
      border: 2px solid var(--ink);
      display: grid;
      align-content: center;
      gap: 16px;
      padding: 34px;
      transform: rotate(-2deg);
    }
    .poster-card b {
      font-size: clamp(2rem, 5vw, 4rem);
      line-height: .9;
      text-transform: uppercase;
    }
    .poster-card span {
      width: fit-content;
      color: var(--green);
      font-weight: 950;
      font-size: 1.2rem;
    }
    section {
      padding: 58px 0;
      border-top: 1px solid var(--line);
    }
    h2 {
      margin: 0 0 24px;
      font-size: clamp(2rem, 4.6vw, 4rem);
      line-height: .98;
    }
    .section-lead {
      max-width: 780px;
      color: var(--muted);
      font-size: 1.15rem;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-top: 26px;
    }
    article {
      min-height: 170px;
      padding: 22px;
      background: var(--panel);
      border: 1px solid var(--line);
      border-radius: 8px;
    }
    article h3 {
      margin: 0 0 10px;
      font-size: 1.12rem;
    }
    article p,
    li {
      color: var(--muted);
    }
    .statement {
      display: grid;
      grid-template-columns: .8fr 1.2fr;
      gap: 20px;
      align-items: start;
      padding: 26px;
      background: var(--panel);
      border: 1px solid var(--line);
      border-left: 8px solid var(--green);
      border-radius: 8px;
      margin-top: 26px;
    }
    .statement strong {
      font-size: 1.35rem;
      line-height: 1.1;
    }
    .not-demanded {
      border-left-color: var(--red);
    }
    ul {
      margin: 18px 0 0;
      padding-left: 20px;
    }
    li + li { margin-top: 8px; }
    footer {
      width: min(100% - 32px, 1120px);
      margin: 0 auto;
      padding: 28px 0 42px;
      border-top: 1px solid var(--line);
      color: var(--muted);
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 16px;
    }
    footer a {
      color: var(--ink);
      font-weight: 800;
    }
    @media (max-width: 860px) {
      nav a:not(:last-child) { display: none; }
      .hero,
      .grid,
      .statement { grid-template-columns: 1fr; }
      .hero { min-height: auto; padding: 38px 0 48px; }
      .poster { min-height: 320px; }
      .supporter-form { grid-template-columns: 1fr; }
      section { padding: 42px 0; }
    }
  </style>
  <script>
    (() => {
      const saved = localStorage.getItem('theme');
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      document.documentElement.dataset.theme = saved || (prefersDark ? 'dark' : 'light');
    })();
  </script>
</head>
<body>
  <div class="topbar">
    <div class="topbar-inner">
      <a class="brand" href="/">Wir gegen Papier</a>
      <nav aria-label="Seitennavigation">
        <a href="#problem">Problem</a>
        <a href="#forderung">Forderung</a>
        <a href="#fakten">Fakten</a>
        <a href="#unterstuetzen">Unterstützen</a>
        <a href="#datenschutz">Datenschutz</a>
        <button class="theme-toggle" type="button" aria-label="Darstellung umschalten" aria-pressed="false">Dark Mode</button>
      </nav>
    </div>
  </div>

  <main>
    <header class="hero">
      <div>
        <p class="eyebrow">Empfängerrecht statt Papierzwang</p>
        <h1>Wir gegen Papier.</h1>
        <p class="claim">Digital, wenn ich will.</p>
        <p class="lead">Rechnungen, Bescheide und vergleichbare Dokumente sollen digital zugestellt werden können, wenn Empfänger das wollen. Papier darf möglich bleiben, aber nicht die einzige Option sein.</p>
        <p class="punch">Ihr hattet genug Zeit.</p>
        <div class="actions">
          <a class="button primary" href="#unterstuetzen">Jetzt unterstützen</a>
          <a class="button" href="#forderung">Forderung lesen</a>
        </div>
      </div>
      <div class="poster" aria-label="Kampagnenposter">
        <div class="poster-card">
          <span>Kein Papierzwang</span>
          <b>Digital, wenn ich will.</b>
          <p>Ein klares Wahlrecht für Empfänger.</p>
        </div>
      </div>
    </header>

    <section id="problem">
      <h2>Das Problem ist nicht Papier. Das Problem ist Zwang.</h2>
      <p class="section-lead">Viele Unternehmen und Verwaltungen verschicken weiterhin Papier, obwohl digitale Zustellung technisch längst Alltag sein könnte. Das kostet Zeit, Porto, Material, Lagerfläche und Aufmerksamkeit.</p>
      <div class="grid">
        <article>
          <h3>Mehr Aufwand</h3>
          <p>Briefe müssen geöffnet, sortiert, gescannt, abgelegt und wiedergefunden werden.</p>
        </article>
        <article>
          <h3>Weniger Kontrolle</h3>
          <p>Empfänger können oft nicht selbst entscheiden, wie sie wichtige Dokumente erhalten.</p>
        </article>
        <article>
          <h3>Veraltete Defaults</h3>
          <p>Digitalisierung bleibt freiwilliger Service, obwohl sie für viele Menschen längst Standard ist.</p>
        </article>
      </div>
    </section>

    <section id="forderung">
      <h2>Unsere Forderung</h2>
      <p class="section-lead">Empfänger sollen einen gesetzlichen Anspruch bekommen, Rechnungen und vergleichbare Dokumente digital erhalten zu können. Der digitale Empfang muss einfach, dokumentiert und alltagstauglich sein.</p>
      <div class="statement">
        <strong>Wahlrecht für Empfänger.</strong>
        <p>Wer digitale Zustellung möchte, soll sie bekommen. Unternehmen und öffentliche Stellen sollen dafür einen verlässlichen Weg anbieten müssen.</p>
      </div>
      <div class="statement not-demanded">
        <strong>Was wir nicht fordern</strong>
        <p>Wir fordern kein pauschales Papierverbot. Wer Papier braucht oder möchte, soll weiterhin Papier erhalten können. Es geht um Wahlfreiheit, nicht um Ausschluss.</p>
      </div>
    </section>

    <section id="fakten">
      <h2>Fakten und Rechtslage</h2>
      <p class="section-lead">Die E-Rechnung kommt in Deutschland schrittweise im B2B-Bereich. Trotzdem bleiben viele alltägliche Dokumente, Kundenkommunikation und Empfängerwünsche ungeregelt oder zu kompliziert.</p>
      <ul>
        <li>Digitale Rechnungsprozesse sind technisch etabliert.</li>
        <li>Viele Empfänger haben bereits sichere digitale Postfächer oder E-Mail-Prozesse.</li>
        <li>Ein klares Empfängerwahlrecht würde Umsetzung und Erwartung vereinfachen.</li>
      </ul>
    </section>

    <section id="unterstuetzen">
      <h2>Jetzt unterstützen</h2>
      <p class="section-lead">Trag deine E-Mail-Adresse ein und bestätige den Link in der Mail. Erst danach zählt deine Unterstützung öffentlich mit.</p>
      <span class="supporter-count">{{ $supporterCount ?? 0 }} bestätigte Unterstützer</span>
      <form class="supporter-form" method="post" action="{{ route('supporters.store') }}">
        @csrf
        <label for="supporter-email">E-Mail-Adresse</label>
        <input id="supporter-email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="deine@email.de">
        <button type="submit">Jetzt unterstützen</button>
        @error('email')
          <p class="validation-error">{{ $message }}</p>
        @enderror
        <p class="form-note">Wir verwenden die Adresse nur für diese Unterstützung und die Double-Opt-in-Bestätigung. Das ist keine Newsletter- oder Werbeeinwilligung. Du kannst deine Unterstützung später widerrufen.</p>
      </form>
    </section>

    <section id="datenschutz">
      <h2>Datenschutz und Widerruf</h2>
      <p class="section-lead">Die Unterstützung der Kampagne ist keine Newsletter-Einwilligung. Wir speichern deine E-Mail-Adresse, den Bestätigungsstatus, den Zeitpunkt der Bestätigung und die Version des Zustimmungstextes. Öffentlicht sichtbar ist nur die Zahl bestätigter Unterstützer.</p>
      <form class="supporter-form" method="post" action="{{ route('supporters.unsubscribe.request') }}">
        @csrf
        <label for="unsubscribe-email">Unterstützung widerrufen</label>
        <input id="unsubscribe-email" type="email" name="email" required autocomplete="email" placeholder="deine@email.de">
        <button type="submit">Widerrufs-Mail senden</button>
        <p class="form-note">Der Widerruf wird ebenfalls per E-Mail-Link bestätigt, damit keine fremden Adressen ausgetragen werden können.</p>
      </form>
    </section>
  </main>

  <footer>
    <span>wir-gegen-papier.de</span>
    <a href="#datenschutz">Datenschutz</a>
    <a href="https://magnetix.cologne/">Impressum</a>
  </footer>

  <script>
    (() => {
      const button = document.querySelector('.theme-toggle');

      function setTheme(theme) {
        document.documentElement.dataset.theme = theme;
        localStorage.setItem('theme', theme);
        const isDark = theme === 'dark';
        button.setAttribute('aria-pressed', String(isDark));
        button.textContent = isDark ? 'Light Mode' : 'Dark Mode';
      }

      button.addEventListener('click', () => {
        const current = document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light';
        setTheme(current === 'dark' ? 'light' : 'dark');
      });
      setTheme(document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light');
    })();
  </script>
</body>
</html>
