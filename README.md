# MARIZ Patisserie — OpenCart 3.0.2.0

Source snapshot of **mariz.gr** (OpenCart 3.0.2.0), set up to run locally on
Windows + IIS + MySQL 8 + PHP 7.4.

## Repository layout

```
mariz/
├── www/              # web root (IIS site / application under /mariz/www)
│   ├── admin/        # OpenCart admin panel
│   ├── catalog/      # storefront code
│   ├── system/       # OpenCart framework
│   ├── image/        # product/media images (tracked)
│   └── config.php    # NOT in git - copy from config-dist.php
├── storage/          # OpenCart storage, outside the web root
│   ├── cache/ logs/ session/ modification/ upload/ download/  (runtime, ignored)
│   └── vendor/       # dependencies shipped with OC 3.0.2.0 (tracked)
└── MYSQL_DUMP_*.sql  # database dump (ignored - too large, contains PII)
```

## Setup on a fresh clone

1. **Database** — import the dump and create the app user:

   ```sql
   CREATE DATABASE mariz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE mariz;
   SOURCE path/to/MYSQL_DUMP_mariz_YYYY-MM-DD.sql;   -- ask a teammate for the dump
   CREATE USER 'mariz_USR'@'localhost' IDENTIFIED BY '<password>';
   GRANT ALL PRIVILEGES ON mariz.* TO 'mariz_USR'@'localhost';
   FLUSH PRIVILEGES;
   ```

2. **Config** — copy the templates and fill in the DB password:

   ```
   copy www\config-dist.php       www\config.php
   copy www\admin\config-dist.php www\admin\config.php
   ```
   Then set `DB_PASSWORD`. Paths and URLs in the templates are derived
   from the clone location; adjust `HTTP_SERVER` if your URL differs.

3. **Web server** — either:
   - **IIS**: map a site/app to `www/`, install *PHP 7.4 (FastCGI)* and the
     *URL Rewrite 2.1* module (`www/web.config` contains the SEO rewrite rule),
     give `IIS_IUSRS` read access to `www/` and modify access to `storage/`, or
   - **PHP built-in server** (quick, no admin rights needed):

     ```
     cd www
     php -S localhost:8000
     ```
     Note: SEO URLs (`/cakes`) need URL rewriting; with the built-in server
     use `index.php?_route_=cakes` instead.

4. **Write access** — the web-server user must be able to write to
   `storage/` (cache, logs, session, modification) and `www/image/cache/`.

## Requirements

| Component | Version |
|---|---|
| PHP | 7.4 (with mysqli, gd, mbstring, curl, intl, openssl, zip, fileinfo) |
| MySQL | 8.0 (dump originated from 8.0.34) |
| IIS | URL Rewrite 2.1 (only if serving through IIS) |

## Notes

- `www/install/` is intentionally not in the repo (installer is removed on
  live sites). The database dump is the install.
- Admin panel: `http://localhost/mariz/www/admin/` — credentials are the
  ones from the production site (see `oc_user` table; the password hash can
  be reset via SQL if lost).
- Custom code vs. stock OpenCart: the Alpha Bank payment module
  (`ocgrAlphaBank`, files under `catalog/` + `admin/` + templates) is
  site-specific. Everything else follows the 3.0.2.0 structure.
- `config.php` / `admin/config.php` are **git-ignored on purpose** — never
  commit real credentials.
