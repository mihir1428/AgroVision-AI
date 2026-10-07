<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(DiseaseSeeder::class);
        User::updateOrCreate(['email'=>'admin@agrovision.test'], ['name'=>'AgroVision Admin','password'=>'Admin12345!','is_admin'=>true,'language'=>'en']);
        User::updateOrCreate(['email'=>'user@agrovision.test'], ['name'=>'Demo User','password'=>'User12345!','is_admin'=>false,'language'=>'en']);
    }
}
