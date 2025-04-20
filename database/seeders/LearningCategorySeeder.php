<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LearningCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        $images = [
            [
                'directory_id' => '1',
                'image_name' => '01J3YRVQJ4WVPR6KBBPMRCY2TJ.jpg',
                'image_path' => null,
            ],
            [
                'directory_id' => '2',
                'image_name' => '01J3YS1Q7QVZPRN87X5KP0PW20.jpg',
                'image_path' => null,
            ],
            [
                'directory_id' => '3',
                'image_name' => '01J3YS3TMQQFTXMBFGJ65SPS7J.jpg',
                'image_path' => null,
            ],
            [
                'directory_id' => '5',
                'image_name' => '01J3YS6893EKZC3VQJPK1X5W0M.jpg',
                'image_path' => null,
            ],
            [
                'directory_id' => '6',
                'image_name' => '01J4V3NGZZJJF93KAK21D64C5B.jpg',
                'image_path' => null,
            ],
        ];

        $users = User::all();

        $sourceImagesPath = database_path('seeders/data/learning_categories');

        for ($i = 0; $i < count($images); $i++) {
            if (!Storage::disk('public')->exists("learning_category/{$images[$i]['directory_id']}")) {
                Storage::disk('public')->makeDirectory("learning_category/{$images[$i]['directory_id']}");
            }

            $existingImages = Storage::disk('public')->files("learning_category/{$images[$i]['directory_id']}");
            if (count($existingImages) < 1) {
                // Read the content of the image file
                $image = File::get("$sourceImagesPath/{$images[$i]['image_name']}");

                $imageExtension = pathinfo("$sourceImagesPath/{$images[$i]['image_name']}", PATHINFO_EXTENSION);

                $imageExtension = !empty($imageExtension) ? $imageExtension : 'png';

                $imageName = strtoupper(Str::random(26)) . '.' . $imageExtension;

                $imagePath = "learning_category/{$images[$i]['directory_id']}/$imageName";
                $images[$i]['image_path'] = $imagePath;

                // Store the content in the correct folder
                Storage::disk('public')->put($imagePath, $image);
            } else {
                $images[$i]['image_path'] = $existingImages[0];
            }
        }

        $categories = [
            [
                'name' => 'Dabas zinības',
                'language_id' => 1,
                'categories' => json_encode(["1", "10"]),
                'thumbnail' => $images[0]['image_path'],
                'description' => 'Apzaļumošanas darbu veikšana nodrošina skaistu un dzīvīgu vidi. No augu izvēles līdz stādīšanai un kopšanai, uzzini labākās prakses, lai veidotu ilgtspējīgus apstādījumus.',
                'is_active' => 1,
                'is_public' => 1,
                'aproved_by' => 1,
                'price' => 0,
                'discount' => 0,
                'currency_id' => 38,
                'available_for_everyone' => 1,
                'created_by' => $faker->randomElement($users)->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Labiekārtošana',
                'language_id' => 1,
                'categories' => json_encode(["5", "6"]),
                'thumbnail' => $images[1]['image_path'],
                'description' => 'Labiekārtošanas projekti padara teritorijas funkcionālas un estētiski pievilcīgas. Apgūsti dārza dizaina pamatus, apgaismojuma izvietošanu un ūdens elementu integrāciju.',
                'is_active' => 1,
                'is_public' => 1,
                'aproved_by' => 1,
                'price' => 5,
                'discount' => 21,
                'currency_id' => 38,
                'available_for_everyone' => rand(0, 1),
                'created_by' => $faker->randomElement($users)->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Urbanistika',
                'language_id' => 1,
                'categories' => json_encode(["5", "6"]),
                'thumbnail' => $images[2]['image_path'],
                'description' => 'Bruģēšanas darbi ietver dažādu bruģakmens veidu izvēli un uzstādīšanu. Iepazīsties ar bruģēšanas procesu un uzzini, kā uzturēt bruģētās virsmas labā stāvoklī.',
                'is_active' => 1,
                'is_public' => 1,
                'aproved_by' => 1,
                'price' => 10,
                'discount' => 0,
                'currency_id' => 38,
                'created_by' => $faker->randomElement($users)->id,
                'created_at' => now(),
                'available_for_everyone' => rand(0, 1),
                'updated_at' => now(),
            ],
            [
                'name' => 'Projekta vadība',
                'language_id' => 1,
                'categories' => json_encode(["4", "22", "33", "41", "42"]),
                'thumbnail' => null,
                'description' => 'Efektīva projektu vadība ir būtiska veiksmīgai apzaļumošanas un labiekārtošanas darbu realizācijai. Uzzini par plānošanu, komunikāciju un ilgtspējīgiem risinājumiem ainavu būvniecībā.',
                'is_active' => 1,
                'is_public' => 1,
                'aproved_by' => 1,
                'price' => 4,
                'discount' => 50,
                'currency_id' => 38,
                'available_for_everyone' => rand(0, 1),
                'created_by' => $faker->randomElement($users)->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Darba drošība',
                'language_id' => 1,
                'categories' => json_encode(["3"]),
                'thumbnail' => $images[3]['image_path'],
                'description' => 'Darba drošība un pareiza tehnikas izmantošana ir kritiski svarīga būvniecības darbos. Uzzini par drošības prasībām, piemērotu instrumentu izvēli un tehnikas apkopi.',
                'is_active' => 1,
                'is_public' => 1,
                'aproved_by' => 1,
                'price' => 0,
                'available_for_everyone' => rand(0, 1),
                'discount' => 0,
                'currency_id' => 38,
                'created_by' => $faker->randomElement($users)->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Drošība internetā',
                'language_id' => 1,
                'categories' => json_encode(["2", "54"]),
                'thumbnail' => $images[4]['image_path'],
                'description' => '<p>Drošība internetā ir būtiska, lai pasargātu sevi un savus datus no dažādiem draudiem tiešsaistē. Tā ietver gan tehniskus, gan uzvedības aspektus, kas palīdz izvairīties no kaitējuma un saglabāt privātumu.</p><h3>1. Paroles un autentifikācija</h3><ol><li>Kā izveidot drošas paroles.</li><li>Divpakāpju autentifikācijas izmantošana.</li></ol><h3>2. Privātums</h3><ol><li>Kā aizsargāt savus datus sociālajos tīklos.</li><li>Ko darīt, lai pasargātu savu personisko informāciju tiešsaistē.</li></ol><h3>3. Kiberdrošības draudi</h3><ol><li>Ļaunatūra (malware) un kā no tās izvairīties.</li><li>Pikšķerēšana (phishing) un kā to atpazīt.</li></ol><p><br>Šie ir tikai daži no svarīgākajiem tematiem, kas palīdzēs labāk saprast, kā pasargāt sevi un citus, lietojot internetu.</p>',
                'is_active' => 1,
                'is_public' => 1,
                'aproved_by' => 1,
                'price' => 3,
                'available_for_everyone' => rand(0, 1),
                'discount' => 0,
                'currency_id' => 38,
                'created_by' => $faker->randomElement($users)->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Matemātika',
                'language_id' => 1,
                'categories' => json_encode(["1", "15"]),
                'thumbnail' => null,
                'description' => 'Matemātika ir zinātne, kas pēta skaitļus, to attiecības un īpašības. Tā ir būtiska daudzās dzīves jomās, piemēram, dabaszinātnēs, inženierijā, ekonomikā un informātikā.',
                'is_active' => 1,
                'is_public' => 1,
                'aproved_by' => 1,
                'price' => 0,
                'discount' => 0,
                'available_for_everyone' => rand(0, 1),
                'currency_id' => 38,
                'created_by' => $faker->randomElement($users)->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('learning_categories')->insert($categories);
    }
}