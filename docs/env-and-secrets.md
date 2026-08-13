# Environment and Secrets

## The short version

**`APP_KEY` must be rotated. It has been published in a public repository since January
2024 and is still the key in use.** Everything else in this document is housekeeping;
that one item is not.

---

## What was wrong

`.env` was tracked in git from the first commit until v3.2.0. The repository is
**public** and has **2 forks**.

Four commits touched the file. Checking every version for non-empty, non-placeholder
values:

| Key | Exposed | Assessment |
|---|---|---|
| `APP_KEY` | all 4 commits, one single value | **Live. Still in use. Rotate.** |
| `DB_PASSWORD` | 3 commits (2024) | Value is `password`, the same one in `docker-compose.yml`. A local dev credential, already public by design. |
| `MAIL_USERNAME`, `MAIL_PASSWORD`, `REDIS_PASSWORD` | — | Literal `null`, Laravel's placeholder. Never real. |
| `AWS_*`, `PUSHER_*` | — | Empty throughout. |

So the exposure is narrow, but the one item in it is the worst one to lose.

### Why `APP_KEY` matters more than it looks

It is not just a session secret. With a known `APP_KEY` an attacker can:

- forge encrypted cookies, and therefore forge sessions;
- forge signed URLs, defeating `signed` route middleware;
- craft encrypted payloads that Laravel will decrypt and unserialize — a documented path
  from leaked `APP_KEY` to remote code execution via deserialization gadget chains.

The committed file also carried `APP_ENV=local` and `APP_DEBUG=true`, and there was no
`.dockerignore`, so `COPY ./ ./` baked that file into the production image. Laravel's
dotenv is immutable — real environment variables win over the file — but any key the
environment did **not** set fell through to those values. In production that means debug
stack traces on any error.

### Why rewriting history is not the answer

The usual advice is `git filter-repo` and a force push. It does not work here:

- the repository is public, so the key has been readable by anyone for over two years;
- there are 2 forks, and forks retain the objects independently of the upstream rewrite;
- GitHub keeps unreferenced objects reachable via commit SHA for a period regardless.

A rewrite would also break every existing clone and branch for no security benefit.
**Rotate the key. Do not rewrite history.**

---

## What this change does

Non-breaking, all of it:

1. **`.env` untracked** (`git rm --cached`) and added to `.gitignore`. The file stays on
   your disk — local development is unaffected, and `docker-compose.yml` bind-mounts your
   working directory, so the container still reads it.
2. **`.dockerignore` added**, excluding `.env` so the image can never carry one. Also
   excludes `vendor` — worth noting on its own, because `COPY ./ ./` was overwriting the
   container's freshly installed `vendor/` with whatever the developer had on macOS.
3. **`.env.example` completed** — it was missing `OCTANE_SERVER`, `OCTANE_HTTPS` and
   `SANCTUM_STATEFUL_DOMAINS`, and now states which values must change for production.

---

## Rotating `APP_KEY`

### Blast radius — verified, not assumed

| Concern | Effect | Why |
|---|---|---|
| Encrypted database columns | **None** | No `encrypted` casts and no `Crypt::` calls anywhere in `app/` |
| Sanctum API tokens | **None** | Stored as `hash('sha256', $token)`, not encrypted. Mobile users stay logged in |
| Admin sessions | Logged out once | `SESSION_DRIVER=file`; sessions are encrypted-cookie based |
| Password reset tokens | Invalidated | They are hashed and short-lived anyway |
| Queued jobs | Only if payloads are in flight | `QUEUE_CONNECTION=sync`, so nothing is persisted |

**Net effect: administrators sign in again. Nothing else.** This is about as cheap as an
`APP_KEY` rotation gets — the good time to do it is now.

### Procedure

Generate the new key **without writing it into a tracked file**:

```bash
php artisan key:generate --show
```

That prints `base64:...` and changes nothing. Then:

1. Set `APP_KEY` to the new value **in production's environment** — the host panel,
   orchestrator, docker-compose `environment:`, or `--env-file`. Not in git.
2. Restart the container. Octane is long-lived, so a config change needs a restart, not
   just a cache clear: `php artisan octane:reload` is not sufficient for env changes.
3. Update your local `.env` with a **different** key. Local and production should not
   share one.
4. Confirm: sign in to `/admin`, and confirm the mobile app's existing token still works
   (`GET /api/auth/me`) — it should, which is the check that Sanctum was unaffected.

### While you are in there

- `GOOGLE_MAPS_KEY` is **absent from `.env`** although `config/services.php` reads it and
  `GET /api/geocode` depends on it. Geocoding is running without a key. Set it, and
  restrict it by IP in the Google Cloud console — this is the same key that still needs
  rotating from the frontend bundle.
- Set `APP_DEBUG=false` and `APP_ENV=production` explicitly in production's environment.
  Do not rely on them being absent.

---

## Settling how production gets its configuration

I could not determine this from the repository — the only compose file is a local
development one. Run these against the server to find out.

**1. Does the container have a `.env` at all?**

```bash
docker exec <container> ls -la /home/swoole/.env
```

**2. What does the application actually think its config is?**

```bash
docker exec <container> php artisan tinker --execute="
  echo 'env:   ' . config('app.env') . PHP_EOL;
  echo 'debug: ' . var_export(config('app.debug'), true) . PHP_EOL;
  echo 'url:   ' . config('app.url') . PHP_EOL;
  echo 'db:    ' . config('database.connections.mysql.host') . PHP_EOL;
"
```

**3. Are the values arriving as real environment variables?**

```bash
docker exec <container> printenv | grep -E '^(APP_|DB_|OCTANE_)' | sed -E 's/=.*/=<set>/'
```

### Interpreting the result

- **Values present in `printenv`** → production injects environment variables. This change
  is a no-op for you; the image simply stops carrying a file it was ignoring anyway.
- **No env vars, and a `.env` exists in the container** → production has its own file. Also
  fine, but make sure your deploy does not copy the repository's example over it.
- **No env vars and no `.env`** → production was relying on the baked-in file. In that case
  supply the configuration as environment variables before the next image build, because
  `.dockerignore` now keeps `.env` out of the image.

The third case is unlikely: the committed `.env` had `DB_HOST=127.0.0.1` with an empty
password, which cannot reach the compose `db` service or any production database. If the
app has been serving traffic, something must already be overriding it.

---

## Checklist

- [ ] Rotate `APP_KEY` in production — **the one that matters**
- [ ] Use a different `APP_KEY` locally
- [ ] Confirm `APP_DEBUG=false` and `APP_ENV=production` are set explicitly
- [ ] Set `GOOGLE_MAPS_KEY`, IP-restricted
- [ ] Rotate the frontend Google Maps key (still outstanding from earlier work)
- [ ] Run the three commands above and record which config mechanism production uses
- [ ] Confirm `/admin` login and `GET /api/auth/me` after the restart
