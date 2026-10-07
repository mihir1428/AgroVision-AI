"""Train AgroVision AI with a folder-per-class dataset.

Expected structure:
  dataset/
    Tomato___healthy/*.jpg
    Tomato___Early_blight/*.jpg
    ... all class folders matching classes.json

This script creates train/validation datasets and saves a MobileNetV3Small transfer-learning model.
Keep a truly independent test set for final reported evaluation; do not report training accuracy as project accuracy.
"""
from pathlib import Path
import json
import tensorflow as tf
from tensorflow import keras
from tensorflow.keras import layers

BASE = Path(__file__).resolve().parent
DATASET = Path(__import__('os').getenv('DATASET_DIR', BASE / 'dataset'))
OUT = BASE / 'models' / 'agrovision.keras'
CLASSES = json.loads((BASE / 'classes.json').read_text())
IMG_SIZE=(224,224); BATCH=32; SEED=331

if not DATASET.exists(): raise SystemExit(f'Dataset folder not found: {DATASET}')
missing=[c for c in CLASSES if not (DATASET/c).exists()]
if missing: raise SystemExit('Missing class folders: '+', '.join(missing))

train_ds = keras.utils.image_dataset_from_directory(DATASET, validation_split=.20, subset='training', seed=SEED, image_size=IMG_SIZE, batch_size=BATCH, label_mode='categorical', class_names=CLASSES)
val_ds = keras.utils.image_dataset_from_directory(DATASET, validation_split=.20, subset='validation', seed=SEED, image_size=IMG_SIZE, batch_size=BATCH, label_mode='categorical', class_names=CLASSES)
AUTOTUNE=tf.data.AUTOTUNE
train_ds=train_ds.cache().shuffle(1000).prefetch(AUTOTUNE); val_ds=val_ds.cache().prefetch(AUTOTUNE)
augment=keras.Sequential([layers.RandomFlip('horizontal'),layers.RandomRotation(.08),layers.RandomZoom(.1),layers.RandomContrast(.1)], name='augmentation')
base=keras.applications.MobileNetV3Small(input_shape=IMG_SIZE+(3,),include_top=False,weights='imagenet',include_preprocessing=True)
base.trainable=False
inputs=keras.Input(shape=IMG_SIZE+(3,)); x=augment(inputs); x=base(x,training=False); x=layers.GlobalAveragePooling2D()(x); x=layers.Dropout(.25)(x); outputs=layers.Dense(len(CLASSES),activation='softmax')(x)
model=keras.Model(inputs,outputs)
model.compile(optimizer=keras.optimizers.Adam(1e-3),loss='categorical_crossentropy',metrics=['accuracy'])
callbacks=[keras.callbacks.EarlyStopping(patience=4,restore_best_weights=True,monitor='val_loss'),keras.callbacks.ReduceLROnPlateau(patience=2,factor=.3)]
model.fit(train_ds,validation_data=val_ds,epochs=20,callbacks=callbacks)
base.trainable=True
for layer in base.layers[:-30]: layer.trainable=False
model.compile(optimizer=keras.optimizers.Adam(1e-5),loss='categorical_crossentropy',metrics=['accuracy'])
model.fit(train_ds,validation_data=val_ds,epochs=10,callbacks=callbacks)
OUT.parent.mkdir(exist_ok=True); model.save(OUT); print(f'Saved: {OUT}')
