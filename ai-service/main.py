from __future__ import annotations
import io, json, os
from pathlib import Path
import numpy as np
from PIL import Image, UnidentifiedImageError
from fastapi import FastAPI, File, HTTPException, UploadFile

BASE = Path(__file__).resolve().parent
CLASSES = json.loads((BASE / 'classes.json').read_text())
MODEL_PATH = Path(os.getenv('MODEL_PATH', BASE / 'models' / 'agrovision.keras'))
MODEL_VERSION = os.getenv('MODEL_VERSION', 'agrovision-mobilenetv3-v1')
IMAGE_SIZE = (224, 224)
_model = None

app = FastAPI(title='AgroVision AI Inference Service', version='1.0.0')

def load_model():
    global _model
    if _model is None:
        if not MODEL_PATH.exists():
            raise RuntimeError(f'Model not found: {MODEL_PATH}. Train/export a model first.')
        from tensorflow import keras
        _model = keras.models.load_model(MODEL_PATH)
    return _model

def preprocess(raw: bytes) -> np.ndarray:
    try:
        image = Image.open(io.BytesIO(raw)).convert('RGB').resize(IMAGE_SIZE)
    except UnidentifiedImageError as exc:
        raise HTTPException(422, 'Uploaded file is not a readable image.') from exc
    arr = np.asarray(image, dtype=np.float32)
    # MobileNetV3 model below includes its own rescaling/preprocessing in Keras.
    return np.expand_dims(arr, axis=0)

@app.get('/health')
def health():
    return {'status': 'ok', 'model_exists': MODEL_PATH.exists(), 'model_version': MODEL_VERSION, 'classes': len(CLASSES)}

@app.post('/predict')
async def predict(file: UploadFile = File(...)):
    if file.content_type not in {'image/jpeg','image/png','image/webp'}:
        raise HTTPException(415, 'Only JPEG, PNG and WebP are supported.')
    raw = await file.read()
    if len(raw) > 8 * 1024 * 1024:
        raise HTTPException(413, 'Image is larger than 8 MB.')
    try:
        model = load_model()
    except RuntimeError as exc:
        raise HTTPException(503, str(exc)) from exc
    x = preprocess(raw)
    probs = np.asarray(model.predict(x, verbose=0)[0], dtype=float)
    if len(probs) != len(CLASSES):
        raise HTTPException(500, f'Model output has {len(probs)} classes but classes.json has {len(CLASSES)}.')
    top_idx = probs.argsort()[-3:][::-1]
    top = [{'class': CLASSES[int(i)], 'confidence': float(probs[int(i)])} for i in top_idx]
    return {'predicted_class': top[0]['class'], 'confidence': top[0]['confidence'], 'top_predictions': top, 'model_version': MODEL_VERSION, 'is_demo': False}
