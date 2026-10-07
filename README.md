# AgroVision AI — Laravel 13 Full Project

AgroVision AI is the implementation companion to the SE-331 proposal: a Laravel web application that accepts crop-leaf images, calls a CNN inference service, displays disease/confidence/top-3 results, retrieves curated disease-management information, stores scan history, accepts feedback, and provides an administrator dashboard.

## Architecture

```text
Browser (Blade/CSS)
       |
       v
Laravel 13 / PHP 8.3+
  |            |
  |            +--> SQLite / PostgreSQL / MySQL
  |
  +-- HTTP multipart --> FastAPI AI service --> MobileNetV3 .keras model
```

Laravel remains the primary application. Python is isolated to the model training/inference layer because the project requires a real computer-vision model.

## Included features

- Registration, login, logout (Laravel session authentication)
- Responsive leaf image upload / mobile camera capture
- Image validation
- AI service integration
- Top-3 predictions and confidence status
- Explicit development-only demo fallback
- 18 proposal classes across tomato, potato, maize and bell pepper
- Curated disease knowledge base
- Scan history and delete
- User correctness feedback
- Admin dashboard
- Disease CRUD/editing (delete disables the class)
- All-predictions admin view
- JSON `/api/predict` endpoint
- Model training/evaluation scripts
- Feature tests

## Requirements

- PHP 8.3+
- Composer
- PHP extensions required by Laravel, plus SQLite or your database driver
- Python environment compatible with your selected TensorFlow build for the AI service

## Quick setup — Laravel

```bash
composer install
cp .env.example .env
php artisan key:generate
```

For SQLite:

```bash
# Windows PowerShell: New-Item database/database.sqlite -ItemType File
# macOS/Linux:
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Open `http://127.0.0.1:8000`.

### Demo accounts created by seeder

- Admin: `admin@agrovision.test` / `Admin12345!`
- User: `user@agrovision.test` / `User12345!`

Change these credentials before deployment.

## AI service

See `ai-service/README.md`.

Development can start with:

```env
AI_ALLOW_DEMO_FALLBACK=true
```

The fallback is deterministic and clearly labeled on-screen. **It is not AI and must not be reported as model performance.**

For the final project/demo, train the model, start FastAPI, and set:

```env
AI_ALLOW_DEMO_FALLBACK=false
AI_SERVICE_URL=http://127.0.0.1:8001
```

## PostgreSQL instead of SQLite

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=agrovision
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

Then run `php artisan migrate --seed`.

## Production notes

- Set `APP_DEBUG=false`.
- Use HTTPS.
- Disable demo fallback.
- Use a real trained model and report metrics only from a held-out test set.
- Replace demo admin credentials.
- Review disease-management content with a qualified agricultural source/expert before public deployment.
- Uploaded images are stored under `storage/app/public/leaf-scans`; define a retention policy for real users.

## Main URLs

- `/` home
- `/register`, `/login`
- `/dashboard`
- `/scan`
- `/history`
- `/admin`
- `/api/health`
- `POST /api/predict` with multipart field `leaf_image`

## Scope alignment

The first version recognizes only the configured 18 classes. It does not claim to diagnose every plant disease, perform laboratory confirmation, determine pesticide dosage, or replace agricultural experts.
