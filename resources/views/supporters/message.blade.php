<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }} - Wir gegen Papier</title>
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
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: var(--paper);
      color: var(--ink);
      line-height: 1.55;
    }
    main {
      width: min(100% - 32px, 760px);
      min-height: 100vh;
      margin: 0 auto;
      display: grid;
      align-content: center;
      padding: 48px 0;
    }
    .panel {
      background: var(--panel);
      border: 1px solid var(--line);
      border-left: 8px solid var(--green);
      border-radius: 8px;
      padding: clamp(24px, 5vw, 42px);
    }
    h1 {
      margin: 0 0 16px;
      font-size: clamp(2rem, 5vw, 4rem);
      line-height: 1;
    }
    p { color: var(--muted); font-size: 1.08rem; }
    .count {
      display: inline-flex;
      margin-top: 14px;
      padding: 8px 12px;
      border: 1px solid var(--line);
      border-radius: 6px;
      background: color-mix(in srgb, var(--panel) 78%, var(--paper));
      font-weight: 900;
    }
    form {
      display: grid;
      gap: 10px;
      margin-top: 22px;
    }
    label { font-weight: 850; }
    input {
      min-height: 46px;
      border: 1px solid var(--line);
      border-radius: 6px;
      background: var(--panel);
      color: var(--ink);
      padding: 0 12px;
      font: inherit;
    }
    input::placeholder { color: var(--muted); }
    button,
    .button {
      width: fit-content;
      min-height: 46px;
      padding: 0 18px;
      border: 1px solid var(--ink);
      border-radius: 6px;
      background: var(--ink);
      color: var(--paper);
      font: inherit;
      font-weight: 850;
      text-decoration: none;
      cursor: pointer;
    }
    .secondary {
      background: transparent;
      color: var(--ink);
    }
    .error { color: var(--red); }
    .actions {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 24px;
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
  <main>
    <div class="panel">
      <h1>{{ $title }}</h1>
      <p>{{ $message }}</p>

      <span class="count">{{ $supporterCount ?? 0 }} bestätigte Unterstützer</span>

      @if ($errors->any())
        <p class="error">{{ $errors->first() }}</p>
      @endif

      @if (! empty($showResendForm))
        <form method="post" action="{{ route('supporters.resend') }}">
          @csrf
          <label for="resend-email">Bestätigungs-Mail erneut senden</label>
          <input id="resend-email" type="email" name="email" required autocomplete="email" placeholder="deine@email.de">
          <button type="submit">Neue Bestätigungs-Mail senden</button>
        </form>
      @endif

      @if (! empty($showUnsubscribeForm))
        <form method="post" action="{{ route('supporters.unsubscribe.request') }}">
          @csrf
          <label for="unsubscribe-email">Unterstützung widerrufen</label>
          <input id="unsubscribe-email" type="email" name="email" required autocomplete="email" placeholder="deine@email.de">
          <button type="submit">Widerrufs-Mail senden</button>
        </form>
      @endif

      <div class="actions">
        <a class="button secondary" href="{{ route('home') }}">Zurück zur Kampagne</a>
      </div>
    </div>
  </main>
</body>
</html>
