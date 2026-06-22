# Dashboard zeigt echte Daten

## Datum

2026-05-21

## Bereich

Dashboard, Frontend, Team-Scope

## Kurzbeschreibung

Das interne Dashboard (`/dashboard`) zeigt keine Platzhalter-Werte mehr,
sondern echte Zahlen und Listen aus den Daten des aktuellen Teams. Es ist
modular aufgebaut, damit weitere Widgets später einfach ergänzt werden
können.

## Was ist neu?

- **Begrüßung** zeigt den Vornamen des eingeloggten Nutzers und den Namen
  des aktuellen Teams.
- **Stats-Widget** mit 4 Live-Kacheln pro Team:
  - Aktive Lizenzkeys (+ Anzahl ausstehender Aktivierungen)
  - Lizenzkeys, die in den nächsten 30 Tagen auslaufen
  - Anzahl Kunden
  - Anzahl Produkte (+ Key Types)
- **Recent License Keys** zeigt die letzten 8 erstellten Keys des Teams mit
  Produkt, Kunde, Ablaufdatum und Status-Badge. Link auf jeden Eintrag.
- **Team Members** listet die Mitglieder des aktuellen Teams mit Initialen,
  Name, Email und Rolle.
- Alle Widgets werden über `Inertia::defer()` nachgeladen und zeigen
  während des Ladens passende Skeleton-Platzhalter.

## Warum wurde das geändert?

Das alte Dashboard lieferte hartkodierte Permit-Beispieldaten („Permit #PRM-1001"
etc.), die nichts mit dem Peermitly-Produkt zu tun hatten. Eingeloggte Nutzer
sollen ihre realen Lizenzdaten sehen und Routine-Aufgaben direkt vom Dashboard
aus starten können.

## Wie funktioniert es?

1. Nutzer öffnet `/dashboard` nach dem Login.
2. Der Controller setzt drei deferred Inertia-Props (`stats`,
   `recentLicenseKeys`, `teamMembers`).
3. Im Frontend rendert jedes Widget einen Skeleton, solange das jeweilige
   Prop noch nicht geladen ist. Sobald die Daten da sind, ersetzen die echten
   Inhalte den Platzhalter.
4. Alle Abfragen sind streng auf `current_team_id` des Nutzers begrenzt.

## Betroffene Bereiche

- `app/Http/Controllers/DashboardController.php` — neuer Aufbau, lädt drei
  deferred Props.
- `app/Actions/Dashboard/GetDashboardStatsAction.php` — eigene Action für
  die Zahlen, single-purpose.
- `app/Data/Dashboard/DashboardStats.php` — typisiertes DTO mit `toArray()`
  für den Inertia-Transport.
- `app/Http/Resources/UserResource.php` — neue Resource für Team-Mitglieder.
- `resources/js/pages/Dashboard.vue` — komponiert die Widgets.
- `resources/js/components/dashboard/` — neue Komponenten:
  - `StatCard.vue` (wiederverwendbare Kachel mit Icon + Tone)
  - `StatsOverview.vue`
  - `RecentLicenseKeys.vue`
  - `TeamMembers.vue`
- `resources/js/types/dashboard.ts` — Typen `DashboardStats`,
  `DashboardTeamMember`.
- `tests/Feature/Dashboard/DashboardPageTest.php` — erweiterter Pest-Test:
  Stats-Action zählt korrekt und respektiert Team-Scope.

## Wichtige Hinweise

- **Erweiterbarkeit**: Neue Widgets werden nach folgendem Muster ergänzt:
  1. Daten-Quelle als Action unter `app/Actions/Dashboard/` schreiben
  2. Bei Bedarf ein passendes DTO unter `app/Data/Dashboard/` anlegen
  3. Im `DashboardController` als weitere `Inertia::defer(...)`-Prop hängen
  4. Frontend-Komponente unter `resources/js/components/dashboard/` ablegen
  5. Typen in `resources/js/types/dashboard.ts` ergänzen + im Index
     exportieren
  6. In `Dashboard.vue` mit `<Deferred>` einbinden
- **Team-Scope**: Alle Counts und Listen kommen aus
  `auth()->user()->current_team_id`. Wer in mehreren Teams ist, sieht
  jeweils die Zahlen des aktuell ausgewählten Teams.
- **API-Calls 24h**: Wird im DTO mitgeführt, ist aktuell nicht in der
  4er-Kachel-Übersicht aktiv (lässt sich in `StatsOverview.vue` einfach
  einblenden, wenn benötigt).

## Beispiel

> Admin meldet sich an und sieht oben „Welcome back, Ada" + Hinweis
> „Team: Acme Co.". Darunter 4 Stat-Karten: 42 aktive Keys, 3 laufen in
> 30 Tagen ab, 12 Kunden, 4 Produkte (mit 5 Key Types). Rechts daneben
> die letzten 8 Keys mit Status-Badge und Email des Kunden. Im
> Team-Block stehen alle Kollegen mit Initialen-Avatar.

## Technische Notizen

- `GetDashboardStatsAction` benutzt eine einzige Builder-Klon-Kette für
  Lizenzkeys (`(clone $teamKeys)->...->count()`) statt mehrerer separater
  Queries — minimal weniger DB-Last.
- `expiring_soon` zählt nur **aktive** Keys mit `expires_at` zwischen
  jetzt und +30 Tagen.
- `api_calls_last_24h` joined `ApiRequestLog` über
  `license_key_id IN (team's keys)`, also alle API-Calls zu Keys dieses
  Teams in den letzten 24 Stunden.
- Frontend-Komponenten sind alle reine Präsentationskomponenten ohne
  eigene Datenholerei — alle Daten kommen über Props rein.
- `StatCard` ist generisch (Label, Value, Icon, Hint, Tone) und kann
  von künftigen Widgets wiederverwendet werden.
