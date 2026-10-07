# AgroVision AI service

The Laravel app sends uploaded leaf images to this FastAPI service. The service loads `models/agrovision.keras`, returns the highest probability class, top 3 classes, confidence, and model version.

## Setup

```bash
cd ai-service
python -m venv .venv
# Windows: .venv\Scripts\activate
# macOS/Linux: source .venv/bin/activate
pip install -r requirements.txt
```

Put your folder-per-class data in `dataset/` and train:

```bash
python train.py
```

For a defensible final report, keep a separate independent test dataset and run:

```bash
python evaluate.py
```

Run inference:

```bash
uvicorn main:app --reload --port 8001
```

Check `http://127.0.0.1:8001/health`.
