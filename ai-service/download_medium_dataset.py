from datasets import load_dataset
from pathlib import Path
import shutil

BASE = Path(__file__).resolve().parent
OUTPUT = BASE / "dataset_medium"

TRAIN_PER_CLASS = 250
TEST_PER_CLASS = 50

CLASS_MAP = {
    "Tomato___healthy": "Tomato___healthy",
    "Tomato___Bacterial_spot": "Tomato___Bacterial_spot",
    "Tomato___Early_blight": "Tomato___Early_blight",
    "Tomato___Late_blight": "Tomato___Late_blight",
    "Tomato___Leaf_Mold": "Tomato___Leaf_Mold",
    "Tomato___Septoria_leaf_spot": "Tomato___Septoria_leaf_spot",
    "Tomato___Spider_mites Two-spotted_spider_mite":
        "Tomato___Spider_mites",
    "Tomato___Tomato_mosaic_virus":
        "Tomato___Tomato_mosaic_virus",
    "Tomato___Tomato_Yellow_Leaf_Curl_Virus":
        "Tomato___Tomato_Yellow_Leaf_Curl_Virus",

    "Potato___healthy": "Potato___healthy",
    "Potato___Early_blight": "Potato___Early_blight",
    "Potato___Late_blight": "Potato___Late_blight",

    "Corn_(maize)___healthy": "Corn___healthy",
    "Corn_(maize)___Common_rust_": "Corn___Common_rust",
    "Corn_(maize)___Cercospora_leaf_spot Gray_leaf_spot":
        "Corn___Gray_leaf_spot",
    "Corn_(maize)___Northern_Leaf_Blight":
        "Corn___Northern_Leaf_Blight",

    "Pepper,_bell___healthy": "Pepper___healthy",
    "Pepper,_bell___Bacterial_spot":
        "Pepper___Bacterial_spot",
}

print("Preparing AgroVision medium dataset...")

if OUTPUT.exists():
    shutil.rmtree(OUTPUT)

train_root = OUTPUT / "train"
test_root = OUTPUT / "test"

for target in CLASS_MAP.values():
    (train_root / target).mkdir(parents=True, exist_ok=True)
    (test_root / target).mkdir(parents=True, exist_ok=True)

train_counts = {
    name: 0 for name in CLASS_MAP.values()
}

test_counts = {
    name: 0 for name in CLASS_MAP.values()
}

print("Connecting to PlantVillage full dataset...")

dataset = load_dataset(
    "geraldmc/plantvillage-full",
    revision="v0.1.0",
    split="train",
    streaming=True
)

# Mix the stream so we do not simply take consecutive images.
dataset = dataset.shuffle(
    seed=42,
    buffer_size=5000
)

def all_complete():
    for name in CLASS_MAP.values():
        if train_counts[name] < TRAIN_PER_CLASS:
            return False

        if test_counts[name] < TEST_PER_CLASS:
            return False

    return True

for row in dataset:

    source_class = row["class_label"]

    if source_class not in CLASS_MAP:
        continue

    target_class = CLASS_MAP[source_class]
    split_name = row.get("split", "train")

    if split_name == "train":

        if train_counts[target_class] >= TRAIN_PER_CLASS:
            continue

        train_counts[target_class] += 1

        output_file = (
            train_root
            / target_class
            / f"{train_counts[target_class]:04d}.jpg"
        )

    elif split_name == "test":

        if test_counts[target_class] >= TEST_PER_CLASS:
            continue

        test_counts[target_class] += 1

        output_file = (
            test_root
            / target_class
            / f"{test_counts[target_class]:04d}.jpg"
        )

    else:
        continue

    image = row["image"].convert("RGB")

    image.save(
        output_file,
        "JPEG",
        quality=95
    )

    total_train = sum(train_counts.values())
    total_test = sum(test_counts.values())

    if (total_train + total_test) % 100 == 0:
        print(
            f"Downloaded: "
            f"{total_train} train + "
            f"{total_test} test"
        )

    if all_complete():
        break


print("\n" + "=" * 60)
print("DATASET DOWNLOAD COMPLETE")
print("=" * 60)

grand_train = 0
grand_test = 0

for class_name in CLASS_MAP.values():

    train_n = train_counts[class_name]
    test_n = test_counts[class_name]

    grand_train += train_n
    grand_test += test_n

    print(
        f"{class_name}: "
        f"Train={train_n}, Test={test_n}"
    )

print("=" * 60)

print(f"Total training images: {grand_train}")
print(f"Total test images: {grand_test}")
print(f"Total images: {grand_train + grand_test}")
print(f"Saved to: {OUTPUT}")