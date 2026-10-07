"""Evaluate the exported model on an independent folder-per-class test set."""
from pathlib import Path
import json, os, numpy as np
import tensorflow as tf
from tensorflow import keras
from sklearn.metrics import classification_report, confusion_matrix
BASE=Path(__file__).resolve().parent; CLASSES=json.loads((BASE/'classes.json').read_text())
TEST=Path(os.getenv('TEST_DIR',BASE/'test-dataset')); MODEL=Path(os.getenv('MODEL_PATH',BASE/'models/agrovision.keras'))
if not TEST.exists(): raise SystemExit(f'Test dataset not found: {TEST}')
model=keras.models.load_model(MODEL)
ds=keras.utils.image_dataset_from_directory(TEST,shuffle=False,image_size=(224,224),batch_size=32,label_mode='categorical',class_names=CLASSES)
y_true=np.concatenate([np.argmax(y.numpy(),axis=1) for _,y in ds]); probs=model.predict(ds,verbose=0); y_pred=np.argmax(probs,axis=1)
print(classification_report(y_true,y_pred,target_names=CLASSES,digits=4)); print('Confusion matrix:\n',confusion_matrix(y_true,y_pred))
