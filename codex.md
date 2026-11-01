# KOPI DRIP SIDIKALANG --- CODEX DEVELOPMENT GUIDE

## 1. PROJECT IDENTITY

Project: Official Website & Online Store Kopi Drip Sidikalang

Domain: - Production: https://kopidripsidikalang.com - Local
development: http://localhost/kopidrip (subject to local configuration)

Business: - Coffee Shop - Coffee Roastery - Online Coffee Store

Primary goal: Build a modern, fast, responsive, secure, SEO-friendly
official website with a powerful admin panel so website content can be
managed without editing source code.

------------------------------------------------------------------------

## 2. FIXED TECHNOLOGY STACK

Use these technologies unless the project owner explicitly changes them:

-   Laravel 12
-   PHP 8.2+
-   MariaDB provided by XAMPP
-   Windows development environment
-   XAMPP
-   Local project directory: `D:\xampp\htdocs`
-   Blade
-   Livewire
-   Tailwind CSS
-   Alpine.js
-   Vite
-   Filament for the admin panel
-   Git
-   GitHub
-   cPanel Git Version Control for deployment

Do NOT upgrade Laravel major versions automatically.

Do NOT replace the stack with React, Vue, Next.js, Inertia, or another
architecture unless explicitly requested by the project owner.

------------------------------------------------------------------------

# 3. CORE DEVELOPMENT PRINCIPLE

## ZERO-REGRESSION DEVELOPMENT

The project owner does not want to manually test every small change.

Therefore:

> Codex MUST verify its own work before considering a task complete.

Never intentionally leave known errors for the project owner to
discover.

Never say a feature is complete when: - migrations fail, - routes
fail, - views fail, - assets fail to compile, - tests fail, - PHP syntax
errors exist, - JavaScript build errors exist, - database queries
fail, - authentication is broken, - authorization is broken, - an
existing feature is broken by the change.

If an error is discovered, fix it before finishing the task whenever
technically possible.

If an issue genuinely cannot be resolved, clearly report: 1. the exact
error, 2. what was already tried, 3. the likely cause, 4. the smallest
required manual action.

Do NOT hide or ignore errors.

------------------------------------------------------------------------

# 4. CODEX WORKFLOW --- MANDATORY

Every implementation task must follow this sequence:

## Step 1 --- Understand

Before modifying code: - inspect the relevant existing files, - inspect
routes, - inspect models, - inspect migrations, - inspect related
components, - inspect configuration, - understand existing conventions.

Do not blindly overwrite existing files.

## Step 2 --- Plan

Briefly determine: - files that must change, - database changes, -
dependencies required, - possible side effects, - validation
requirements, - tests required.

Avoid unnecessary changes.

## Step 3 --- Implement

Implement the smallest complete solution that satisfies the requirement.

Follow Laravel conventions.

## Step 4 --- Verify

Run appropriate verification after implementation.

At minimum, when applicable:

``` bash
php artisan optimize:clear
php artisan route:list
php artisan migrate:status
php artisan test
npm run build
```

Also run:

``` bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

when the change involves configuration, cache, or views.

For PHP code, check syntax and use Laravel/PHP tooling available in the
project.

For frontend changes, verify the Vite production build.

## Step 5 --- Regression Check

Verify that the change did not break related existing functionality.

At minimum check: - application boots, - routes load, - database
connection works, - relevant page loads, - relevant form submits, -
relevant admin resource works, - relevant public page works.

## Step 6 --- Final Report

At the end of every task, report:

### Changed

List what was changed.

### Verified

List commands/tests that passed.

### Not Verified

List anything that could not be tested and explain why.

### Errors

If there are no known errors, explicitly state:

`No known errors found during verification.`

Never claim "100% error-free" unless that can actually be proven.

------------------------------------------------------------------------

# 5. IMPORTANT: DO NOT CREATE A TEST BURDEN FOR THE OWNER

The project owner wants Codex to perform as much testing as possible.

Therefore:

-   Do not ask the owner to manually test basic syntax.
-   Do not ask the owner to manually discover obvious route errors.
-   Do not ask the owner to manually discover migration errors.
-   Do not ask the owner to manually discover broken imports.
-   Do not ask the owner to manually discover failed builds.
-   Do not ask the owner to manually discover missing classes.
-   Do not ask the owner to manually discover SQL errors.

If Codex can test it locally, Codex should test it.

Manual testing by the owner should be reserved for: - visual approval, -
business workflow approval, - real payment/shipping integrations, -
production-only behavior, - credentials/API integrations, - real-device
UX validation.

------------------------------------------------------------------------

# 6. DATABASE RULES

Database: MariaDB through XAMPP.

All schema changes MUST use Laravel migrations.

Never manually edit production database structure as the normal
development workflow.

Every new table must have: - primary key, - timestamps where
appropriate, - appropriate indexes, - foreign keys where appropriate, -
sensible nullable/default rules.

Before creating a migration, inspect existing migrations to avoid
duplicate columns/tables.

Never delete or rename an existing database column without checking all
references first.

For destructive database operations: - explain the impact, - avoid data
loss, - use safe migrations, - never silently drop production data.

Use seeders/factories for repeatable test data where useful.

------------------------------------------------------------------------

# 7. CODE QUALITY

Follow Laravel conventions.

Prefer: - Form Request validation - Policies/Gates - Eloquent
relationships - Services for complex business logic - reusable Blade
components - Livewire components for interactive UI - named routes -
configuration files for configurable values - environment variables for
secrets

Avoid: - massive controllers, - massive Blade files, - duplicated
business logic, - raw SQL when Eloquent/query builder is sufficient, -
hardcoded URLs, - hardcoded business information, - hardcoded API
keys, - secrets in Git.

Do not place business logic directly in Blade templates.

------------------------------------------------------------------------

# 8. ADMIN PANEL

Use Filament for the admin panel.

Admin URL should be separated from the public website, for example:

`/admin`

Admin areas will eventually include:

-   Dashboard
-   Website Settings
-   Branding
-   Homepage
-   Pages
-   Products
-   Categories
-   Product Variants
-   Orders
-   Customers
-   Blog
-   Gallery
-   Promotions
-   SEO
-   Analytics
-   Social Media
-   Contact Settings
-   Users
-   Roles & Permissions
-   Audit Logs

The exact implementation can evolve, but the architecture must remain
modular.

------------------------------------------------------------------------

# 9. PUBLIC WEBSITE

The public website should include, as appropriate:

-   Home
-   About / Our Story
-   Coffee
-   Products
-   Product Detail
-   Menu
-   Gallery
-   Blog / Journal
-   Promotions
-   Location
-   Contact
-   Cart
-   Checkout
-   Customer order status

The homepage should be editable from the admin panel.

Do not hardcode homepage content if the content is intended to be
managed by administrators.

------------------------------------------------------------------------

# 10. E-COMMERCE

The architecture must support:

-   products
-   categories
-   product images
-   product variants
-   prices
-   stock
-   SKU
-   weight
-   cart
-   checkout
-   customers
-   orders
-   order items
-   shipping
-   payment status
-   order status

Initial checkout can support WhatsApp/manual confirmation if payment
gateway integration is not yet implemented.

Design the database so a payment gateway can be added later without
rewriting the order system.

------------------------------------------------------------------------

# 11. PRODUCT VARIANTS

Products should be designed to support variants.

Examples:

Size: - 100g - 200g - 500g - 1kg

Grind: - Whole Bean - Espresso - V60 - French Press - Tubruk

Roast: - Light - Medium - Medium Dark - Dark

Do not assume every product must use every variant.

------------------------------------------------------------------------

# 12. BRANDING

The website must support configurable branding:

-   Logo
-   Alternative/white logo
-   Favicon
-   Business name
-   Tagline
-   Primary color
-   Secondary color
-   Contact information
-   WhatsApp number
-   Social media
-   Address
-   Opening hours

The supplied Kopi Drip Sidikalang logo is the initial visual reference.

Design direction:

-   modern specialty coffee
-   premium but approachable
-   black
-   off-white
-   cream
-   coffee brown
-   subtle green accents
-   Indonesian/Sidikalang coffee identity
-   clean typography
-   strong photography

Do not overuse colors or decorative effects.

------------------------------------------------------------------------

# 13. RESPONSIVE DESIGN

Every public and admin interface must be responsive.

Target: - Desktop - Laptop - Tablet - Mobile

Check at minimum: - 1920px - 1440px - 1366px - 1024px - 768px - 430px -
414px - 390px - 375px

Do not create a desktop-only layout.

Images must not overflow containers.

Tables must remain usable on mobile.

Forms must remain usable on mobile.

Buttons must remain accessible on touch devices.

------------------------------------------------------------------------

# 14. SEO

SEO must be designed into the application from the beginning.

Support:

-   SEO title
-   meta description
-   canonical URL
-   robots directives
-   Open Graph title
-   Open Graph description
-   Open Graph image
-   Twitter/X card metadata where appropriate
-   sitemap.xml
-   robots.txt
-   breadcrumb structured data
-   organization/local business structured data
-   product structured data
-   clean URLs/slugs
-   semantic HTML
-   image alt text

SEO settings must be manageable through the admin panel where
appropriate.

Avoid duplicate meta titles and descriptions.

------------------------------------------------------------------------

# 15. PERFORMANCE

The website should be optimized for real-world hosting.

Use: - optimized images, - lazy loading where appropriate, - responsive
image sizes, - caching where appropriate, - eager loading to avoid N+1
queries, - pagination for large datasets, - optimized database
indexes, - Vite production builds.

Do not load large libraries when a small solution is sufficient.

Do not load unnecessary JavaScript on every page.

------------------------------------------------------------------------

# 16. SECURITY

Security is mandatory.

Use: - Laravel authentication - authorization - CSRF protection -
validation - escaping - secure password hashing - secure file upload
validation - MIME validation - file size validation - rate limiting
where appropriate - policies/permissions - secure session
configuration - environment variables for secrets

Never commit: - `.env` - API keys - passwords - database credentials -
payment secrets - private tokens

Production must use:

``` env
APP_ENV=production
APP_DEBUG=false
```

Never expose stack traces or sensitive configuration to public users.

------------------------------------------------------------------------

# 17. FILE UPLOADS

Admin uploads must validate: - file type, - MIME type, - extension, -
maximum file size, - image dimensions where appropriate.

Do not trust the filename supplied by the browser.

Use Laravel Storage.

Do not store arbitrary executable files in public upload directories.

------------------------------------------------------------------------

# 18. ANALYTICS

The project should support:

## Google Analytics 4

and optionally internal website analytics.

Potential internal metrics: - visitors - page views - popular pages -
device type - browser - operating system - referrer - landing page -
product views - add to cart - checkout - purchase/order events

Do not collect unnecessary personal information.

Use privacy-conscious data minimization.

------------------------------------------------------------------------

# 19. GITHUB

Git is mandatory.

Use meaningful commits.

Examples:

``` text
feat: add product management
fix: resolve checkout validation error
refactor: simplify product service
chore: update dependencies
docs: update deployment guide
```

Do not commit generated secrets.

Do not force-push shared branches without explicit approval.

Before pushing: - verify application, - run tests, - run build, -
inspect changed files.

------------------------------------------------------------------------

# 20. CPANEL DEPLOYMENT

Production target:

`kopidripsidikalang.com`

Deployment will use: - GitHub - cPanel Git Version Control -
`.cpanel.yml`

Production deployment must account for: - environment variables, -
database migration, - storage link, - cache, - configuration cache, -
route cache where appropriate, - frontend build artifacts, - file
permissions, - document root, - HTTPS.

Never expose the Laravel project root publicly if the hosting
configuration can instead point to Laravel's `public` directory.

Never overwrite production `.env` with a development `.env`.

------------------------------------------------------------------------

# 21. LOCAL DEVELOPMENT

Expected environment:

``` text
Windows
XAMPP
D:\xampp\htdocs
MariaDB
VS Code
Codex
```

Before development: - verify PHP version, - verify Composer, - verify
Node/npm, - verify MariaDB, - verify project environment.

Do not assume the developer's machine has a dependency installed.

If a dependency is required, document it.

------------------------------------------------------------------------

# 22. ERROR HANDLING PROTOCOL

If any command fails:

1.  Stop considering the task complete.
2.  Read the full error.
3.  Identify the root cause.
4.  Fix the root cause.
5.  Re-run the failed command.
6.  Re-run related tests.
7.  Check for regression.
8.  Only then continue.

Do not repeatedly run the same failed command without changing anything.

Do not suppress errors just to make a command appear successful.

Do not use `|| true`, silent failure, or equivalent techniques to hide a
real error.

------------------------------------------------------------------------

# 23. MIGRATION ERROR PROTOCOL

If a migration fails:

-   inspect the exact SQL error,
-   inspect current schema,
-   inspect migration order,
-   inspect foreign keys,
-   fix the migration,
-   reset/rebuild only in a safe local environment when appropriate,
-   rerun migrations,
-   run tests.

Never tell the owner to manually edit migration files or database tables
just to make an error disappear unless there is a documented reason.

------------------------------------------------------------------------

# 24. FRONTEND ERROR PROTOCOL

If:

``` bash
npm run build
```

fails:

-   inspect the first meaningful error,
-   fix the source,
-   rerun build,
-   verify generated assets.

Do not mark frontend work complete while the production build is
failing.

------------------------------------------------------------------------

# 25. ROUTE ERROR PROTOCOL

After adding/changing routes:

``` bash
php artisan route:list
```

Verify: - route exists, - route name is correct, - middleware is
correct, - controller/component exists, - model binding is valid.

------------------------------------------------------------------------

# 26. TESTING STRATEGY

Create automated tests for important business logic.

At minimum, cover:

### Authentication

-   login
-   logout
-   unauthorized access

### Authorization

-   admin access
-   non-admin access
-   permission checks

### Products

-   create
-   update
-   validation
-   publish/unpublish
-   variant handling

### Cart

-   add item
-   update quantity
-   remove item
-   price calculation

### Checkout

-   validation
-   order creation
-   order totals

### SEO

-   meta data rendering
-   canonical URL
-   sitemap response

### Public pages

-   homepage loads
-   product page loads
-   blog page loads

Do not create fake tests that merely assert `true`.

Tests must verify meaningful behavior.

------------------------------------------------------------------------

# 27. BEFORE EVERY MAJOR FEATURE IS DECLARED COMPLETE

Run the applicable verification checklist:

``` text
[ ] PHP syntax valid
[ ] Laravel boots
[ ] Database connection works
[ ] Migrations pass
[ ] Routes load
[ ] Relevant automated tests pass
[ ] Frontend build passes
[ ] Relevant public page works
[ ] Relevant admin page works
[ ] Validation works
[ ] Authorization works
[ ] Responsive layout considered
[ ] SEO considered
[ ] Security considered
[ ] No known regression
```

------------------------------------------------------------------------

# 28. DO NOT OVERENGINEER

Do not add packages just because they are popular.

Before adding a dependency: 1. determine whether Laravel already
provides the functionality, 2. determine whether Filament/Livewire
already provides it, 3. check maintenance/compatibility, 4. confirm it
solves a real requirement.

Prefer a smaller, maintainable codebase.

------------------------------------------------------------------------

# 29. BUSINESS CONTENT

Never invent business facts such as: - address, - phone number, -
opening hours, - prices, - product descriptions, - certifications, -
awards, - claims about coffee origin, - delivery promises.

If the information is not provided, use a clearly marked placeholder or
ask the project owner.

Do not publish placeholder business claims as real content.

------------------------------------------------------------------------

# 30. IMAGE AND MEDIA GUIDELINES

Use optimized images.

Every meaningful content image should have appropriate alt text.

Do not use copyrighted images without permission.

For temporary development images, clearly identify them as placeholders.

The Kopi Drip logo supplied by the project owner should be treated as
the project's initial brand asset.

------------------------------------------------------------------------

# 31. ADMIN UX

The admin panel should be simple enough for a non-developer
administrator.

Prefer: - clear labels, - search, - filters, - pagination, - image
previews, - helpful validation messages, - confirmation before
destructive actions, - status badges, - bulk actions only where safe, -
sensible defaults.

Do not expose technical database terminology unnecessarily.

------------------------------------------------------------------------

# 32. DEVELOPMENT PHASES

Build in controlled phases.

## Phase 1

Foundation: - Laravel project - environment - database - Git -
authentication - Filament - roles/permissions - settings

## Phase 2

Public website: - layout - homepage - about - products - gallery -
contact - location

## Phase 3

CMS: - pages - banners - products - categories - blog - gallery -
branding

## Phase 4

SEO: - metadata - sitemap - robots - structured data - canonical - Open
Graph

## Phase 5

E-commerce: - cart - checkout - customers - orders - shipping - payment
architecture

## Phase 6

Analytics: - GA4 - internal analytics - dashboard

## Phase 7

Production: - GitHub - cPanel - domain - SSL - database - storage -
cache - cron

## Phase 8

Final QA: - desktop - tablet - mobile - security - SEO - performance -
ecommerce - admin

------------------------------------------------------------------------

# 33. WORK IN SMALL, VERIFIED INCREMENTS

Do NOT implement the entire project in one giant operation.

For each phase: 1. implement a small logical group, 2. verify it, 3. fix
errors, 4. run regression checks, 5. continue.

A feature is complete only when it is implemented AND verified.

------------------------------------------------------------------------

# 34. WHEN THE OWNER REPORTS AN ERROR

When the project owner reports an error:

1.  Do not guess.
2.  Ask for the exact error only if it is not already available.
3.  Identify the affected component.
4.  Inspect the relevant code.
5.  Reproduce the issue when possible.
6.  Fix the root cause.
7.  Verify the fix.
8.  Check related functionality.
9.  Explain the cause in simple language.

Do not immediately rewrite unrelated code.

------------------------------------------------------------------------

# 35. COMMUNICATION STYLE

The project owner is technically capable but wants a controlled
development process.

When reporting progress: - be concise, - explain what changed, - explain
what was tested, - mention errors clearly, - do not overwhelm with
unnecessary implementation details.

Use Indonesian for explanations unless code/documentation requires
English.

------------------------------------------------------------------------

# 36. DEFINITION OF DONE

A task is DONE only when:

1.  Requirement implemented.
2.  Code follows project architecture.
3.  Database changes are migrated safely.
4.  Validation exists where needed.
5.  Authorization exists where needed.
6.  Automated tests exist for important behavior.
7.  Existing relevant functionality still works.
8.  Frontend production build passes when frontend changed.
9.  No known error remains.
10. The final verification result is reported.

------------------------------------------------------------------------

# 37. GOLDEN RULE

The most important rule of this project:

> DO NOT MOVE FORWARD WHILE A KNOWN ERROR FROM THE CURRENT TASK REMAINS
> UNRESOLVED.

The project owner should NOT have to discover basic development errors
by manually testing every step.

Codex is responsible for implementing, checking, fixing, and verifying
its work before declaring a task complete.

When uncertain, inspect first. Do not guess.

When an error appears, fix it first. Do not hide it.

When a change may affect existing functionality, test the affected
functionality before continuing.
