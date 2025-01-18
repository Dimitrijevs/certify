<?php

namespace Database\Seeders;

use App\Models\PdfTemplate;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class LearningTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('learning_tests')) {
            $faker = Faker::create();
            $sort_id = 0;
            $defaultTemplate = PdfTemplate::where('pdf_type', 'certificate')->where('is_default', true)->first()->id;

            $sourceImagesPath = database_path('seeders/data/learning_qualifications');

            $images = [
                [
                    'directory_id' => '1',
                    'image_name' => '01J3Z72H7CXGC3HPXK0R8WWZA4.jpg',
                    'image_path' => null,
                ],
                [
                    'directory_id' => '3',
                    'image_name' => '01J3Z7553QR8XXC0GRT82SFRRR.jpg',
                    'image_path' => null,
                ],
                [
                    'directory_id' => '4',
                    'image_name' => '01J3Z7645G51G12EP58D0YAR2Q.jpg',
                    'image_path' => null,
                ],
                [
                    'directory_id' => '5',
                    'image_name' => '01J4VAB11AN0E3XK807AEQBVNB.jpg',
                    'image_path' => null,
                ],
                [
                    'directory_id' => '6',
                    'image_name' => '01J5ZGMGTGX6VR9XJMVRF4Q3SR.jpg',
                    'image_path' => null,
                ],
            ];

            for ($i = 0; $i < count($images); $i++) {
                if (!Storage::disk('public')->exists("learning_qualifications/{$images[$i]['directory_id']}")) {
                    Storage::disk('public')->makeDirectory("learning_qualifications/{$images[$i]['directory_id']}");
                }

                $existingImages = Storage::disk('public')->files("learning_qualifications/{$images[$i]['directory_id']}");
                if (count($existingImages) < 1) {
                    // Read the content of the image file
                    $image = File::get("$sourceImagesPath/{$images[$i]['image_name']}");

                    $imageExtension = pathinfo("$sourceImagesPath/{$images[$i]['image_name']}", PATHINFO_EXTENSION);

                    $imageExtension = !empty($imageExtension) ? $imageExtension : 'png';

                    $imageName = strtoupper(Str::random(26)) . '.' . $imageExtension;

                    $imagePath = "learning_qualifications/{$images[$i]['directory_id']}/$imageName";
                    $images[$i]['image_path'] = $imagePath;

                    // Store the content in the correct folder
                    Storage::disk('public')->put($imagePath, $image);
                } else {
                    $images[$i]['image_path'] = $existingImages[0];
                }
            }

            $tests = [
                [
                    'name' => 'Apzaļumošanas zināšanu pārbaude',
                    'sort_id' => ++$sort_id,
                    'category_id' => json_encode(["1", "2"]),
                    'is_active' => 1,
                    'thumbnail' => $images[0]['image_path'],
                    'description' => 'Šis tests ir izstrādāts, lai pārbaudītu jūsu zināšanas par augu izvēli, stādīšanu, sezonālajiem apzaļumošanas darbiem un augu kopšanu. Atbildiet uz jautājumiem, lai novērtētu savu izpratni par apzaļumošanu.',
                    'min_score' => rand(3, 10),
                    'time_limit' => rand(20, 60),
                    'layout_id' => null,
                    'cooldown' => 60,
                    'requirement_type' => 'test', // test, certificate
                    'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                ],
                [
                    'name' => 'Labiekārtošanas zināšanu pārbaude',
                    'sort_id' => ++$sort_id,
                    'category_id' => json_encode(["2", "4"]),
                    'is_active' => 1,
                    'thumbnail' => null,
                    'description' => 'Šis tests ir izstrādāts, lai pārbaudītu jūsu zināšanas par dārza dizaina plānošanu, āra apgaismojuma izvietošanu un ūdens elementu ierīkošanu dārzā. Atbildiet uz jautājumiem, lai novērtētu savu izpratni par labiekārtošanu.',
                    'min_score' => rand(3, 10),
                    'time_limit' => rand(20, 60),
                    'layout_id' => $defaultTemplate,
                    'requirement_type' => 'test', // test, certificate
                    'cooldown' => null,
                    'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                ],
                [
                    'name' => 'Projekta vadības zināšanu pārbaude',
                    'sort_id' => ++$sort_id,
                    'category_id' => json_encode(["4", "5"]),
                    'is_active' => 1,
                    'thumbnail' => $images[1]['image_path'],
                    'description' => 'Šis tests ir izstrādāts, lai pārbaudītu jūsu zināšanas par efektīvu projektu vadību apzaļumošanas un labiekārtošanas darbos. Atbildiet uz jautājumiem, lai novērtētu savu izpratni par plānošanu, komunikāciju un ilgtspējīgiem risinājumiem ainavu būvniecībā.',
                    'min_score' => rand(3, 10),
                    'time_limit' => rand(20, 60),
                    'layout_id' => null,
                    'requirement_type' => 'test', // test, certificate
                    'cooldown' => null,
                    'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                ],
                [
                    'name' => 'Darba drošības un tehnikas zināšanu pārbaude',
                    'sort_id' => ++$sort_id,
                    'category_id' => json_encode(["5"]),
                    'is_active' => 1,
                    'thumbnail' => $images[2]['image_path'],
                    'description' => 'Šis tests ir izstrādāts, lai pārbaudītu jūsu zināšanas par drošības prasībām, piemērotu instrumentu izvēli un tehnikas apkopi būvniecības darbos. Atbildiet uz jautājumiem, lai novērtētu savu izpratni par darba drošību un tehnikas izmantošanu.',
                    'min_score' => rand(3, 10),
                    'time_limit' => rand(20, 60),
                    'layout_id' => $defaultTemplate,
                    'requirement_type' => 'test', // test, certificate
                    'cooldown' => null,
                    'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                ],
                [
                    'name' => 'Interneta drošības zināšanu pārbaude',
                    'sort_id' => ++$sort_id,
                    'category_id' => json_encode(["6"]),
                    'is_active' => 1,
                    'thumbnail' => $images[3]['image_path'],
                    'description' => 'Šī zināšanu pārbaude ir izstrādāta, lai novērtētu lietotāju izpratni par drošību internetā. Testā iekļauti jautājumi par drošu paroļu izveidi, privātuma aizsardzību, interneta draudiem un drošu pārlūkošanu. Tā mērķis ir palīdzēt lietotājiem labāk izprast un pielietot drošības principus, lai pasargātu savus datus un izvairītos no interneta riskiem. Testa izpildes laiks ir ierobežots, un veiksmīgai nokārtošanai nepieciešams sasniegt minimālo punktu skaitu.',
                    'min_score' => rand(3, 10),
                    'time_limit' => rand(20, 60),
                    'layout_id' => $defaultTemplate,
                    'requirement_type' => 'test', // test, certificate
                    'cooldown' => null,
                    'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                ],
                [
                    'name' => 'D kategorijas transportlīdzekļa vadītāja tiesību iegūšana',
                    'sort_id' => ++$sort_id,
                    'category_id' => json_encode(["6", "2"]),
                    'is_active' => 1,
                    'thumbnail' => $images[4]['image_path'],
                    'description' => 'Šis eksāmens ir paredzēts, lai iegūtu D kategorijas transportlīdzekļa vadītāja tiesības. Tas ietver teorētiskās un praktiskās apmācības, kas nepieciešamas profesionālas autobusa vadīšanas iemaņu apgūšanai. Pēc veiksmīgas eksāmena nokārtošanas jūs saņemsiet nepieciešamo sertifikātu un licenci.',
                    'min_score' => null,
                    'time_limit' => null,
                    'layout_id' => null,
                    'requirement_type' => 'certificate', // test, certificate
                    'cooldown' => null,
                    'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                ],
            ];

            DB::table('learning_tests')->insert($tests);
        } else {
            // Handle the case where the table does not exist
            $this->command->info('Table "learning_tests" does not exist.');
        }
    }
}
