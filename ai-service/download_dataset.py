from datasets import load_dataset
from pathlib import Path
import shutil

BASE = Path(__file__).resolve().parent
OUTPUT = BASE / "dataset"

# PlantVillage original class name -> our AgroVision class name
CLASS_MAP = {
    "Tomato___healthy": "Tomato___healthy",
    "Tomato___Bacterial_spot": "Tomato___Bacterial_spot",
    "Tomato___Early_blight": "Tomato___Early_blight",
    "Tomato___Late_blight": "Tomato___Late_blight",
    "Tomato___Leaf_Mold": "Tomato___Leaf_Mold",
    "Tomato___Septoria_leaf_spot": "Tomato___Septoria_leaf_spot",
    "Tomato___Spider_mites Two-spotted_spider_mite": "Tomato___Spider_mites",
    "Tomato___Tomato_mosaic_virus": "Tomato___Tomato_mosaic_virus",
    "Tomato___Tomato_Yellow_Leaf_Curl_Virus": "Tomato___Tomato_Yellow_Leaf_Curl_Virus",

    "Potato___healthy": "Potato___healthy",
    "Potato___Early_blight": "Potato___Early_blight",
    "Potato___Late_blight": "Potato___Late_blight",

    "Corn_(maize)___healthy": "Corn___healthy",
    "Corn_(maize)___Common_rust_": "Corn___Common_rust",
    "Corn_(maize)___Cercospora_leaf_spot Gray_leaf_spot": "Corn___Gray_leaf_spot",
    "Corn_(maize)___Northern_Leaf_Blight": "Corn___Northern_Leaf_Blight",

    "Pepper,_bell___healthy": "Pepper___healthy",
    "Pepper,_bell___Bacterial_spot": "Pepper___Bacterial_spot",
}

print("Downloading small PlantVillage dataset...")

ds = load_dataset(
    "geraldmc/plantvillage-tiny",
    revision="v0.1.0",
    split="train"
)

# Clean/create our 18 class folders
OUTPUT.mkdir(exist_ok=True)

for target_name in set(CLASS_MAP.values()):
    folder = OUTPUT / target_name

    if folder.exists():
        shutil.rmtree(folder)

    folder.mkdir(parents=True, exist_ok=True)

counts = {name: 0 for name in CLASS_MAP.values()}

for row in ds:
    source_class = row["class_label"]

    if source_class not in CLASS_MAP:
        continue

    target_class = CLASS_MAP[source_class]
    counts[target_class] += 1

    image = row["image"].convert("RGB")

    filename = f"{counts[target_class]:04d}.jpg"
    image.save(
        OUTPUT / target_class / filename,
        "JPEG",
        quality=95
    )

print("\nDownload complete.")
print("-" * 50)

total = 0

for class_name in CLASS_MAP.values():
    count = counts[class_name]
    total += count
    print(f"{class_name}: {count} images")

print("-" * 50)
print(f"Total selected images: {total}")
print(f"Saved to: {OUTPUT}")