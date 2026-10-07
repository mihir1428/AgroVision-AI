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
    "scientific_name": null,
    "description": "The leaf is classified as healthy among the supported Tomato classes. The model did not find a stronger match for the disease classes it knows.",
    "symptoms": "No supported disease pattern was strongly detected. A healthy leaf is usually evenly green, with no expanding spots, mosaic pattern, major curling, or visible mite damage.",
    "cause": "Not applicable. A healthy classification does not rule out nutrition problems, physical injury, or diseases outside the supported classes.",
    "prevention": "Maintain clean tools, balanced nutrition, suitable spacing, good airflow, careful irrigation, and regular scouting.",
    "management": "Continue routine crop monitoring. If the plant is declining despite a healthy result, inspect the whole plant and seek local agricultural advice."
  },
  {
    "class_key": "Tomato___Bacterial_spot",
    "crop_name": "Tomato",
    "disease_name": "Bacterial Spot",
    "scientific_name": "Xanthomonas spp.",
    "description": "Bacterial spot affects tomato leaves, stems, and fruit and can spread quickly during warm, wet conditions.",
    "symptoms": "Leaves may develop small dark or water-soaked spots, sometimes with yellow halos. Spots can merge, damaged leaves may yellow, and fruit may develop raised or scabby lesions.",
    "cause": "Caused by plant-pathogenic Xanthomonas bacteria. Splashing water, infected seed or transplants, tools, and plant contact can help spread the bacteria.",
    "prevention": "Use clean seed or transplants, avoid working with wet plants, reduce overhead watering, disinfect tools, remove crop debris, and rotate away from susceptible hosts where practical.",
    "management": "Remove heavily infected material when practical, reduce leaf wetness and splash, improve sanitation, and follow locally approved bacterial-disease management guidance."
  },
  {
    "class_key": "Tomato___Early_blight",
    "crop_name": "Tomato",
    "disease_name": "Early Blight",
    "scientific_name": "Alternaria solani",
    "description": "Early blight is a common fungal disease that often begins on older tomato leaves and can reduce leaf area and plant vigor.",
    "symptoms": "Brown spots often show concentric target-like rings with surrounding yellowing. Lower leaves are commonly affected first and may dry and drop as disease develops.",
    "cause": "Primarily caused by the fungus Alternaria solani, which can survive in infected crop residue and spread by wind, rain splash, equipment, and contact.",
    "prevention": "Rotate crops, remove infected debris, keep foliage off the soil when possible, improve spacing and airflow, avoid prolonged leaf wetness, and use healthy planting material.",
    "management": "Remove badly affected leaves when practical, reduce plant stress and leaf wetness, improve sanitation, and use locally recommended disease-control options when needed."
  },
  {
    "class_key": "Tomato___Late_blight",
    "crop_name": "Tomato",
    "disease_name": "Late Blight",
    "scientific_name": "Phytophthora infestans",
    "description": "Late blight is a destructive tomato and potato disease that can progress rapidly during cool, humid, or wet weather.",
    "symptoms": "Leaves may show irregular water-soaked areas that turn brown or black. Under humid conditions, pale or white growth can appear near lesion edges on the underside of leaves.",
    "cause": "Caused by the oomycete Phytophthora infestans. It spreads through infected plant material and wind- or rain-carried spores under favorable weather.",
    "prevention": "Use healthy planting material, remove volunteer or infected plants, improve airflow, avoid unnecessary leaf wetness, and monitor closely during cool wet periods.",
    "management": "Isolate and remove severely affected tissue or plants where appropriate, avoid moving contaminated material, and follow urgent local late-blight management guidance."
  },
  {
    "class_key": "Tomato___Leaf_Mold",
    "crop_name": "Tomato",
    "disease_name": "Leaf Mold",
    "scientific_name": "Passalora fulva",
    "description": "Tomato leaf mold is favored by high humidity and is especially important in dense plantings and protected growing environments.",
    "symptoms": "Pale green to yellow patches appear on upper leaf surfaces while olive-green to brown velvety growth may develop on the underside. Severe infection can cause leaf drying.",
    "cause": "Caused by the fungus Passalora fulva. Spores spread through air, water, tools, and plant handling, especially when humidity remains high.",
    "prevention": "Improve ventilation and plant spacing, reduce humidity, water at the soil level when possible, remove infected debris, and sanitize tools.",
    "management": "Remove heavily affected leaves, lower humidity and leaf wetness, improve airflow, and use locally recommended control measures if disease continues to spread."
  },
  {
    "class_key": "Tomato___Septoria_leaf_spot",
    "crop_name": "Tomato",
    "disease_name": "Septoria Leaf Spot",
    "scientific_name": "Septoria lycopersici",
    "description": "Septoria leaf spot is a fungal leaf disease of tomato that commonly starts on lower foliage and can cause severe defoliation.",
    "symptoms": "Numerous small circular spots with dark margins and pale or gray centers develop on leaves. Tiny black fruiting bodies may be visible within older spots.",
    "cause": "Caused by the fungus Septoria lycopersici. It survives in infected residue and spreads mainly through splashing water and contaminated material.",
    "prevention": "Remove infected debris, rotate crops, mulch to reduce soil splash, avoid overhead irrigation when possible, and provide good airflow.",
    "management": "Remove heavily infected lower leaves, keep foliage dry, improve sanitation, and follow locally approved fungal-disease management recommendations."
  },
  {
    "class_key": "Tomato___Spider_mites",
    "crop_name": "Tomato",
    "disease_name": "Spider Mites",
    "scientific_name": "Tetranychus urticae and related mites",
    "description": "Spider mites are tiny sap-feeding pests that can damage tomato leaves, particularly in hot and dry conditions.",
    "symptoms": "Leaves may show fine pale stippling, yellowing, bronzing, or drying. Fine webbing and tiny moving mites may be visible, especially on the underside of leaves.",
    "cause": "Damage is caused by spider mites feeding on plant cells. Populations can increase quickly during hot, dry weather and on stressed plants.",
    "prevention": "Inspect leaf undersides regularly, reduce plant stress, control weeds around production areas, and avoid unnecessary practices that disrupt natural enemies.",
    "management": "Confirm mites on the underside of leaves, remove heavily damaged material when practical, improve plant care, and use locally appropriate mite-management methods."
  },
  {
    "class_key": "Tomato___Tomato_mosaic_virus",
    "crop_name": "Tomato",
    "disease_name": "Tomato Mosaic Virus",
    "scientific_name": "Tomato mosaic virus (ToMV)",
    "description": "Tomato mosaic virus is a highly stable virus that can affect foliage, plant growth, and fruit quality.",
    "symptoms": "Leaves may develop light-and-dark green mosaic patterns, mottling, narrowing, blistering, or distortion. Plants can become stunted and fruit may develop uneven color.",
    "cause": "Caused by Tomato mosaic virus. The virus is commonly spread mechanically through infected plant material, hands, tools, and contaminated surfaces.",
    "prevention": "Use clean seed and transplants, wash hands, disinfect tools and work surfaces, avoid handling healthy plants after infected plants, and remove suspicious volunteer plants.",
    "management": "There is no curative treatment for an infected plant. Remove confirmed infected plants carefully, sanitize equipment and hands, and prevent mechanical spread to healthy plants."
  },
  {
    "class_key": "Tomato___Tomato_Yellow_Leaf_Curl_Virus",
    "crop_name": "Tomato",
    "disease_name": "Tomato Yellow Leaf Curl Virus",
    "scientific_name": "Tomato yellow leaf curl virus (TYLCV)",
    "description": "Tomato yellow leaf curl virus can cause serious stunting and yield loss, especially when plants are infected early.",
    "symptoms": "Young leaves may curl upward, become smaller and yellow at the margins, while plants may be stunted with shortened internodes and reduced flowering or fruit set.",
    "cause": "Caused by Tomato yellow leaf curl virus, a begomovirus transmitted mainly by whiteflies.",
    "prevention": "Use healthy transplants, manage whitefly populations and weed hosts, use physical exclusion where practical, and remove infected plants and volunteers promptly.",
    "management": "Remove strongly symptomatic plants when appropriate, reduce whitefly pressure using locally recommended integrated pest management, and protect new plantings from infected sources."
  },
  {
    "class_key": "Potato___healthy",
    "crop_name": "Potato",
    "disease_name": "Healthy",
    "scientific_name": null,
    "description": "The leaf is classified as healthy among the supported Potato classes.",
    "symptoms": "No strong Early Blight or Late Blight pattern was detected. Healthy leaves are generally evenly green without expanding necrotic lesions.",
    "cause": "Not applicable. Other disorders not included in the model may still be present.",
    "prevention": "Use clean seed tubers, balanced nutrition, good field sanitation, suitable irrigation, and regular scouting.",
    "management": "Continue routine monitoring. Investigate the full plant if unusual symptoms persist despite a healthy AI result."
  },
  {
    "class_key": "Potato___Early_blight",
    "crop_name": "Potato",
    "disease_name": "Early Blight",
    "scientific_name": "Alternaria solani",
    "description": "Potato early blight is a fungal disease that often becomes more noticeable on older or stressed foliage.",
    "symptoms": "Dark brown leaf spots may develop concentric target-like rings and surrounding yellowing. Lesions can enlarge and cause premature leaf death.",
    "cause": "Commonly caused by Alternaria solani, which survives in infected residue and can spread through spores carried by wind and rain splash.",
    "prevention": "Use healthy seed tubers, rotate crops, remove infected residue, maintain balanced plant nutrition, improve airflow, and limit long periods of leaf wetness.",
    "management": "Remove or manage infected debris, reduce crop stress and leaf wetness, and follow locally recommended early-blight control practices."
  },
  {
    "class_key": "Potato___Late_blight",
    "crop_name": "Potato",
    "disease_name": "Late Blight",
    "scientific_name": "Phytophthora infestans",
    "description": "Potato late blight is a fast-moving disease capable of damaging leaves, stems, and tubers under favorable weather.",
    "symptoms": "Irregular water-soaked green to brown lesions can expand quickly. White sporulation may be visible near lesion margins on humid leaf undersides.",
    "cause": "Caused by the oomycete Phytophthora infestans and spread by infected plants, tubers, volunteers, and airborne spores.",
    "prevention": "Use clean seed tubers, remove volunteers and cull piles, avoid prolonged foliage wetness, and scout frequently during cool and humid weather.",
    "management": "Act quickly on suspected outbreaks, remove heavily affected material according to local guidance, avoid moving contaminated material, and follow regional late-blight recommendations."
  },
  {
    "class_key": "Corn___healthy",
    "crop_name": "Maize",
    "disease_name": "Healthy",
    "scientific_name": null,
    "description": "The leaf is classified as healthy among the supported Maize classes.",
    "symptoms": "No strong Common Rust, Gray Leaf Spot, or Northern Leaf Blight pattern was detected among the supported classes.",
    "cause": "Not applicable. Nutrient, insect, environmental, or unsupported disease problems may still occur.",
    "prevention": "Maintain balanced fertility, suitable plant density, residue and weed management, and regular scouting.",
    "management": "Continue routine crop monitoring and investigate any field symptoms that do not match the supported classes."
  },
  {
    "class_key": "Corn___Common_rust",
    "crop_name": "Maize",
    "disease_name": "Common Rust",
    "scientific_name": "Puccinia sorghi",
    "description": "Common rust is a fungal foliar disease of maize that often appears as scattered pustules on leaves.",
    "symptoms": "Small oval to elongated cinnamon-brown pustules can occur on both leaf surfaces. Older pustules may darken, and heavy infection can reduce green leaf area.",
    "cause": "Caused by the rust fungus Puccinia sorghi. Airborne spores infect maize when temperature and moisture conditions are favorable.",
    "prevention": "Use resistant hybrids where available, scout susceptible crops, maintain balanced crop nutrition, and avoid unnecessary stress.",
    "management": "Confirm rust pustules before acting. Resistant hybrids and normal crop vigor are important; use locally recommended disease management only when field risk justifies it."
  },
  {
    "class_key": "Corn___Gray_leaf_spot",
    "crop_name": "Maize",
    "disease_name": "Gray Leaf Spot",
    "scientific_name": "Cercospora zeae-maydis",
    "description": "Gray leaf spot is a residue-associated fungal disease of maize that can become severe in warm, humid environments.",
    "symptoms": "Lesions are often narrow and rectangular because they are limited by leaf veins. They may begin tan or brown and become gray as they enlarge.",
    "cause": "Mainly caused by Cercospora zeae-maydis. The fungus survives in infected maize residue and produces spores during humid conditions.",
    "prevention": "Use resistant hybrids, rotate crops, manage infected residue where suitable, improve field airflow, and scout fields with a history of the disease.",
    "management": "Base action on disease severity, hybrid susceptibility, crop stage, and local risk. Follow regional recommendations for gray leaf spot management."
  },
  {
    "class_key": "Corn___Northern_Leaf_Blight",
    "crop_name": "Maize",
    "disease_name": "Northern Leaf Blight",
    "scientific_name": "Exserohilum turcicum",
    "description": "Northern leaf blight is a fungal disease of maize that can reduce photosynthetic leaf area when susceptible plants are infected early.",
    "symptoms": "Large elongated gray-green to tan lesions often have a cigar-shaped appearance and can expand along the leaf.",
    "cause": "Caused by Exserohilum turcicum. The fungus can survive in infected crop residue and spread by wind-carried spores.",
    "prevention": "Use resistant hybrids, rotate crops, manage infected residue where appropriate, and scout fields early when weather favors disease.",
    "management": "Consider crop stage, disease severity, hybrid susceptibility, and local recommendations before choosing control actions."
  },
  {
    "class_key": "Pepper___healthy",
    "crop_name": "Bell Pepper",
    "disease_name": "Healthy",
    "scientific_name": null,
    "description": "The leaf is classified as healthy among the supported Bell Pepper classes.",
    "symptoms": "No strong Bacterial Spot pattern was detected. Healthy leaves are normally evenly colored and free of expanding water-soaked or necrotic spots.",
    "cause": "Not applicable. Other pests, nutrient disorders, or unsupported diseases may still be present.",
    "prevention": "Use clean transplants, good sanitation, balanced nutrition, careful irrigation, and regular scouting.",
    "management": "Continue normal monitoring and inspect the whole plant if symptoms develop."
  },
  {
    "class_key": "Pepper___Bacterial_spot",
    "crop_name": "Bell Pepper",
    "disease_name": "Bacterial Spot",
    "scientific_name": "Xanthomonas spp.",
    "description": "Bacterial spot can affect pepper leaves and fruit and is favored by warm, wet conditions and frequent splash.",
    "symptoms": "Leaves may develop small water-soaked spots that become brown or black, sometimes with yellowing around lesions. Fruit can develop rough, raised, or scabby spots.",
    "cause": "Caused by plant-pathogenic Xanthomonas bacteria that can be introduced on seed or transplants and spread by splash, handling, and contaminated tools.",
    "prevention": "Start with clean seed or transplants, avoid overhead watering where possible, do not handle wet plants, sanitize tools, and remove infected debris.",
    "management": "Reduce splash and leaf wetness, remove heavily infected material when practical, improve sanitation, and follow locally approved bacterial-disease management advice."
  }
]
JSON, true);

        foreach ($rows as $row) {
            Disease::updateOrCreate(
                ['class_key' => $row['class_key']],
                $row + ['is_active' => true]
            );
        }
    }
}
