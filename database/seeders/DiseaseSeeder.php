<?php
namespace Database\Seeders;
use App\Models\Disease;
use Illuminate\Database\Seeder;
class DiseaseSeeder extends Seeder
{
    public function run(): void
    {
        $rows = json_decode(<<<'JSON'
[
  {
    "class_key": "Tomato___healthy",
    "crop_name": "Tomato",
    "disease_name": "Healthy",
    "description": "The uploaded leaf is classified as a healthy Tomato leaf among the supported classes.",
    "symptoms": "No disease-specific visual symptoms were identified among the supported classes. Continue normal crop monitoring.",
    "cause": "Not applicable.",
    "prevention": "Maintain good crop hygiene, balanced nutrition, suitable spacing and regular scouting.",
    "management": "Continue routine monitoring and locally recommended crop management practices."
  },
  {
    "class_key": "Tomato___Bacterial_spot",
    "crop_name": "Tomato",
    "disease_name": "Bacterial Spot",
    "description": "Bacterial Spot is a supported Tomato condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Tomato___Early_blight",
    "crop_name": "Tomato",
    "disease_name": "Early Blight",
    "description": "Early Blight is a supported Tomato condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Tomato___Late_blight",
    "crop_name": "Tomato",
    "disease_name": "Late Blight",
    "description": "Late Blight is a supported Tomato condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Tomato___Leaf_Mold",
    "crop_name": "Tomato",
    "disease_name": "Leaf Mold",
    "description": "Leaf Mold is a supported Tomato condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Tomato___Septoria_leaf_spot",
    "crop_name": "Tomato",
    "disease_name": "Septoria Leaf Spot",
    "description": "Septoria Leaf Spot is a supported Tomato condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Tomato___Spider_mites",
    "crop_name": "Tomato",
    "disease_name": "Spider Mites",
    "description": "Spider Mites is a supported Tomato condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Tomato___Tomato_mosaic_virus",
    "crop_name": "Tomato",
    "disease_name": "Tomato Mosaic Virus",
    "description": "Tomato Mosaic Virus is a supported Tomato condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Tomato___Tomato_Yellow_Leaf_Curl_Virus",
    "crop_name": "Tomato",
    "disease_name": "Tomato Yellow Leaf Curl Virus",
    "description": "Tomato Yellow Leaf Curl Virus is a supported Tomato condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Potato___healthy",
    "crop_name": "Potato",
    "disease_name": "Healthy",
    "description": "The uploaded leaf is classified as a healthy Potato leaf among the supported classes.",
    "symptoms": "No disease-specific visual symptoms were identified among the supported classes. Continue normal crop monitoring.",
    "cause": "Not applicable.",
    "prevention": "Maintain good crop hygiene, balanced nutrition, suitable spacing and regular scouting.",
    "management": "Continue routine monitoring and locally recommended crop management practices."
  },
  {
    "class_key": "Potato___Early_blight",
    "crop_name": "Potato",
    "disease_name": "Early Blight",
    "description": "Early Blight is a supported Potato condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Potato___Late_blight",
    "crop_name": "Potato",
    "disease_name": "Late Blight",
    "description": "Late Blight is a supported Potato condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Corn___healthy",
    "crop_name": "Maize",
    "disease_name": "Healthy",
    "description": "The uploaded leaf is classified as a healthy Maize leaf among the supported classes.",
    "symptoms": "No disease-specific visual symptoms were identified among the supported classes. Continue normal crop monitoring.",
    "cause": "Not applicable.",
    "prevention": "Maintain good crop hygiene, balanced nutrition, suitable spacing and regular scouting.",
    "management": "Continue routine monitoring and locally recommended crop management practices."
  },
  {
    "class_key": "Corn___Common_rust",
    "crop_name": "Maize",
    "disease_name": "Common Rust",
    "description": "Common Rust is a supported Maize condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Corn___Gray_leaf_spot",
    "crop_name": "Maize",
    "disease_name": "Gray Leaf Spot",
    "description": "Gray Leaf Spot is a supported Maize condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Corn___Northern_Leaf_Blight",
    "crop_name": "Maize",
    "disease_name": "Northern Leaf Blight",
    "description": "Northern Leaf Blight is a supported Maize condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  },
  {
    "class_key": "Pepper___healthy",
    "crop_name": "Bell Pepper",
    "disease_name": "Healthy",
    "description": "The uploaded leaf is classified as a healthy Bell Pepper leaf among the supported classes.",
    "symptoms": "No disease-specific visual symptoms were identified among the supported classes. Continue normal crop monitoring.",
    "cause": "Not applicable.",
    "prevention": "Maintain good crop hygiene, balanced nutrition, suitable spacing and regular scouting.",
    "management": "Continue routine monitoring and locally recommended crop management practices."
  },
  {
    "class_key": "Pepper___Bacterial_spot",
    "crop_name": "Bell Pepper",
    "disease_name": "Bacterial Spot",
    "description": "Bacterial Spot is a supported Bell Pepper condition recognized by the image-classification model. This result is a preliminary AI screening, not a laboratory diagnosis.",
    "symptoms": "Visual symptoms can vary with disease stage, cultivar, weather and image quality. Inspect both sides of several leaves and compare the AI result with field symptoms.",
    "cause": "Cause depends on the specific disease and may involve fungal, bacterial, viral or pest-related factors.",
    "prevention": "Use clean planting material, field sanitation, crop rotation where appropriate, good airflow, suitable irrigation practices and regular scouting.",
    "management": "Remove severely affected material when appropriate, avoid spreading contaminated plant material, improve field sanitation, and consult a qualified local agricultural professional for diagnosis confirmation and locally approved control options. The application intentionally does not generate pesticide dosages."
  }
]
JSON, true);
        foreach ($rows as $row) Disease::updateOrCreate(['class_key'=>$row['class_key']], $row + ['is_active'=>true]);
    }
}
