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

## Advanced realtime workflow

This source package includes a persistent notification system and a clearer prediction lifecycle:

- `queued` -> `processing` -> `completed` / `failed`
- PostgreSQL database queue for AI jobs
- FastAPI + MobileNetV3 inference
- Socket.IO realtime updates with a polling fallback
- database-backed notification bell with unread count
- retry support for failed AI jobs
- confidence labels and low-confidence warning
- disease cause, symptoms, prevention and recommended actions
- feedback only after a completed prediction

After updating an existing installation, run:

```bash
php artisan migrate
php artisan db:seed --class=DiseaseSeeder
php artisan optimize:clear
```

Recommended local `.env` values:

```env
CACHE_STORE=file
QUEUE_CONNECTION=database
AI_SERVICE_URL=http://127.0.0.1:8001
AI_ALLOW_DEMO_FALLBACK=false
REALTIME_SERVICE_URL=http://127.0.0.1:3001
REALTIME_CLIENT_URL=http://127.0.0.1:3001
```

Start the four development processes in separate terminals:

```bash
# FastAPI
cd ai-service
.venv\Scripts\activate.bat
python -m uvicorn main:app --reload --host 127.0.0.1 --port 8001

# Socket.IO
cd realtime-service
node server.js

# Laravel queue worker
php artisan queue:work database --tries=2 --timeout=120 -vvv

# Laravel web app
php artisan serve
```

For final model evaluation, run `python evaluate.py` with an independent test set. The script writes Accuracy, Precision, Recall, F1-score, a classification report and a confusion matrix to `ai-service/evaluation/`.


## Confidence interpretation

AgroVision treats model confidence as a screening signal rather than a diagnosis. The default bands are:

- **High:** 85% or above
- **Moderate:** 65% to 84.9%
- **Low:** below 65%

If the top two model predictions are within 15 percentage points, the result page also shows a close-alternative warning. These values can be changed through `AI_CONFIDENCE_HIGH`, `AI_CONFIDENCE_LOW`, and `AI_AMBIGUITY_MARGIN`.
