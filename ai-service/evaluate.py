"""Evaluate the exported AgroVision model on an independent test set.

Expected test structure:
  dataset_medium/test/<class_name>/*.jpg
or set TEST_DIR to another folder-per-class test directory.

Outputs are saved to ai-service/evaluation/:
  metrics.json
  classification_report.csv
  confusion_matrix.csv
  confusion_matrix.png

Use an independent test set for final project metrics. Do not report training or
validation accuracy as final test accuracy.
"""

from __future__ import annotations

import csv
import json
import os
from pathlib import Path

import matplotlib.pyplot as plt
import numpy as np
from sklearn.metrics import (
    ConfusionMatrixDisplay,
    accuracy_score,
    classification_report,
    confusion_matrix,
    f1_score,
    precision_score,
    recall_score,
)
from tensorflow import keras

BASE = Path(__file__).resolve().parent
CLASSES = json.loads((BASE / "classes.json").read_text())
DEFAULT_TEST = BASE / "dataset_medium" / "test"
if not DEFAULT_TEST.exists():
    DEFAULT_TEST = BASE / "test-dataset"

TEST = Path(os.getenv("TEST_DIR", DEFAULT_TEST))
MODEL = Path(os.getenv("MODEL_PATH", BASE / "models" / "agrovision.keras"))
OUT = Path(os.getenv("EVAL_DIR", BASE / "evaluation"))
OUT.mkdir(parents=True, exist_ok=True)

if not TEST.exists():
    raise SystemExit(
        f"Test dataset not found: {TEST}. Set TEST_DIR to an independent folder-per-class test set."
    )
if not MODEL.exists():
    raise SystemExit(f"Model not found: {MODEL}")

model = keras.models.load_model(MODEL)
ds = keras.utils.image_dataset_from_directory(
    TEST,
    shuffle=False,
    image_size=(224, 224),
    batch_size=32,
    label_mode="categorical",
    class_names=CLASSES,
)

y_true = np.concatenate([np.argmax(y.numpy(), axis=1) for _, y in ds])
probs = model.predict(ds, verbose=0)
y_pred = np.argmax(probs, axis=1)

metrics = {
    "test_images": int(len(y_true)),
    "classes": len(CLASSES),
    "accuracy": float(accuracy_score(y_true, y_pred)),
    "precision_macro": float(precision_score(y_true, y_pred, average="macro", zero_division=0)),
    "recall_macro": float(recall_score(y_true, y_pred, average="macro", zero_division=0)),
    "f1_macro": float(f1_score(y_true, y_pred, average="macro", zero_division=0)),
    "precision_weighted": float(precision_score(y_true, y_pred, average="weighted", zero_division=0)),
    "recall_weighted": float(recall_score(y_true, y_pred, average="weighted", zero_division=0)),
    "f1_weighted": float(f1_score(y_true, y_pred, average="weighted", zero_division=0)),
}

(OUT / "metrics.json").write_text(json.dumps(metrics, indent=2))

report = classification_report(
    y_true,
    y_pred,
    target_names=CLASSES,
    output_dict=True,
    zero_division=0,
)
with (OUT / "classification_report.csv").open("w", newline="", encoding="utf-8") as handle:
    writer = csv.writer(handle)
    writer.writerow(["class", "precision", "recall", "f1-score", "support"])
    for name in CLASSES:
        row = report[name]
        writer.writerow([name, row["precision"], row["recall"], row["f1-score"], row["support"]])

cm = confusion_matrix(y_true, y_pred, labels=range(len(CLASSES)))
np.savetxt(OUT / "confusion_matrix.csv", cm, delimiter=",", fmt="%d")

fig, ax = plt.subplots(figsize=(14, 12))
ConfusionMatrixDisplay(confusion_matrix=cm, display_labels=CLASSES).plot(
    ax=ax,
    xticks_rotation=90,
    values_format="d",
    colorbar=False,
)
ax.set_title("AgroVision AI - Test Set Confusion Matrix")
fig.tight_layout()
fig.savefig(OUT / "confusion_matrix.png", dpi=180)
plt.close(fig)

print(json.dumps(metrics, indent=2))
print(f"Evaluation files saved to: {OUT}")
