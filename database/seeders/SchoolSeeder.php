<?php

namespace Database\Seeders;

use App\Models\School;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'Rīga',
            'Daugavpils',
            'Liepāja',
            'Jelgava',
            'Jūrmala',
            'Ventspils',
            'Rēzekne',
            'Valmiera'
        ];

        // Get the files from the directory
        $sourceImagesPath = database_path('seeders/data/schools_images');
        $images = collect(File::files($sourceImagesPath));

        $targetPath = 'schools';

        foreach ($images as $index => $image) {
            $imageNumber = $index + 1;
            $sourceImagePath = $image->getPathname();
            $filename = $image->getFilename();

            // Create directory for each school
            $schoolDirectory = "{$targetPath}/{$imageNumber}";
            if (!Storage::disk('public')->exists($schoolDirectory)) {
                Storage::disk('public')->makeDirectory($schoolDirectory);
            }

            // Set target path including directory and filename
            $targetImagePath = "{$schoolDirectory}/{$filename}";

            if (File::exists($sourceImagePath) && !Storage::disk('public')->exists($targetImagePath)) {
                $fileContent = File::get($sourceImagePath);
                Storage::disk('public')->put($targetImagePath, $fileContent);
            }
        }

        $companies = [
            [
                'name' => 'Baltijas Kokapstrāde',
                'address' => 'Meža iela 12',
                'city' => $cities[array_rand($cities)],
                'country' => 'Latvija',
                'postal_code' => 'LV-' . rand(1000, 9999),
                'phone' => '2' . rand(6000000, 7999999),
                'email' => 'info@baltijaskoka.lv',
                'avatar' => 'schools/1/1.jpeg',
                'website' => 'https://baltijaskoki.lv',
                'description' => 'Baltijas Kokapstrāde ir premium klases koka mēbeļu ražotājs, kas lepojas ar ilgtspējīgu pieeju un augstas kvalitātes amatniecību. Uzņēmums specializējas unikālu, roku darba mēbeļu izgatavošanā, izmantojot vietējos Latvijas mežos iegūtos kokmateriālus. Katrs izstrādājums ir rūpīgi izstrādāts, lai apvienotu tradicionālo dizainu ar modernām vajadzībām. Viņu misija ir saglabāt dabas resursus, vienlaikus piedāvājot klientiem izturīgas un estētiski pievilcīgas mēbeles. Darbnīcas atrodas gleznainā Latvijas laukside vidē, kur daba iedvesmo katru jaunu kolekciju. Uzņēmums aktīvi sadarbojas ar vietējiem amatniekiem, veicinot reģiona ekonomiku.',
                'created_at' => now()->subDays(rand(0, 180)),
                'updated_at' => now()->subDays(rand(0, 90)),
            ],
            [
                'name' => 'Dzintara Tehnoloģijas',
                'address' => 'Dzintara prospekts 8',
                'city' => $cities[array_rand($cities)],
                'country' => 'Latvija',
                'avatar' => 'schools/2/2.jpeg',
                'postal_code' => 'LV-' . rand(1000, 9999),
                'phone' => '2' . rand(6000000, 7999999),
                'email' => 'kontakti@dzintaratech.lv',
                'website' => 'https://dzintaratech.lv',
                'description' => 'Dzintara Tehnoloģijas ir inovatīvs programmatūras izstrādes uzņēmums, kas fokusējas uz Baltijas uzņēmumu digitālajām vajadzībām un tehnoloģisko attīstību. Specializējoties pielāgotu risinājumu izveidē, viņi piedāvā modernus rīkus, kas palīdz uzņēmumiem optimizēt procesus un palielināt efektivitāti. Komanda apvieno jaunākās tehnoloģijas ar dziļu izpratni par vietējo tirgu, nodrošinot konkurences priekšrocības klientiem. Uzņēmuma nosaukums godina Latvijas dzintaru, simbolizējot kvalitāti un unikālu pieeju ikvienā projektā. Viņu biroji ir aprīkoti ar jaunākajām tehnoloģijām, radot dinamisku vidi izstrādātājiem. Dzintara Tehnoloģijas aktīvi sadarbojas ar reģiona start-up uzņēmumiem, veicinot inovācijas un izaugsmi.',
                'created_at' => now()->subDays(rand(0, 180)),
                'updated_at' => now()->subDays(rand(0, 90)),
            ],
            [
                'name' => 'Latgales Alus Darītava',
                'address' => 'Alus iela 25',
                'city' => $cities[array_rand($cities)],
                'country' => 'Latvija',
                'avatar' => 'schools/3/3.jpeg',
                'postal_code' => 'LV-' . rand(1000, 9999),
                'phone' => '2' . rand(6000000, 7999999),
                'email' => 'parskats@latgalesalus.lv',
                'website' => 'https://latgalesalus.lv',
                'description' => 'Latgales Alus Darītava ir amatniecības alus darītava, kas lepojas ar tradicionālu latviešu recepšu saglabāšanu un pielietošanu mūsdienu alus ražošanā. Uzņēmums izmanto vietēji audzētus miežus un apiņus, lai radītu unikālas garšas, kas atspoguļo Latgales reģiona bagāto kultūras mantojumu. Katrs alus veids tiek rūpīgi brūvēts nelielās partijās, nodrošinot izcilu kvalitāti un autentisku baudījumu. Darītava piedāvā gan klasiskus, gan eksperimentālus alus veidus, kas piesaista gan vietējos iedzīvotājus, gan tūristus. Tā ir kļuvusi par iecienītu vietu alus entuziastiem, kuri vēlas izbaudīt īstu Latvijas garšu. Darbnīca atrodas Latgales sirdī, kur senās tradīcijas satiekas ar mūsdienīgu pieeju.',
                'created_at' => now()->subDays(rand(0, 180)),
                'updated_at' => now()->subDays(rand(0, 90)),
            ],
            [
                'name' => 'Rīgas Metāls',
                'address' => 'Tērauda ceļš 15',
                'city' => $cities[array_rand($cities)],
                'country' => 'Latvija',
                'postal_code' => 'LV-' . rand(1000, 9999),
                'phone' => '2' . rand(6000000, 7999999),
                'email' => 'pasutijumi@rigasmetals.lv',
                'avatar' => 'schools/4/4.jpeg',
                'website' => 'https://rigasmetals.lv',
                'description' => 'Rīgas Metāls ir vadošais tērauda izstrādājumu un rūpniecisko iekārtu piegādātājs, kas apkalpo plašu klientu loku visā Latvijā un ārpus tās. Uzņēmums specializējas augstas kvalitātes tērauda konstrukciju, detaļu un aprīkojuma ražošanā, pielāgojot risinājumus katra klienta vajadzībām. Ar modernām tehnoloģijām un pieredzējušu komandu viņi nodrošina izturīgus un precīzus produktus, kas atbilst stingriem rūpniecības standartiem. Rīgas Metāls lepojas ar savu atrašanās vietu galvaspilsētā, kur tā noliktavas un darbnīcas ir kļuvušas par svarīgu industriālās infrastruktūras daļu. Viņu mērķis ir atbalstīt būvniecības, ražošanas un inženierijas nozares ar uzticamiem materiāliem. Uzņēmums aktīvi sadarbojas ar vietējiem un starptautiskiem partneriem, stiprinot Latvijas pozīcijas metālapstrādes jomā.',
                'created_at' => now()->subDays(rand(0, 180)),
                'updated_at' => now()->subDays(rand(0, 90)),
            ],
            [
                'name' => 'Zemgales Raža',
                'address' => 'Lauku iela 33',
                'city' => $cities[array_rand($cities)],
                'country' => 'Latvija',
                'postal_code' => 'LV-' . rand(1000, 9999),
                'avatar' => 'schools/5/5.jpeg',
                'phone' => '2' . rand(6000000, 7999999),
                'email' => 'info@zemgalesraza.lv',
                'website' => 'https://zemgalesraza.lv',
                'description' => 'Zemgales Raža ir bioloģisko graudu un dārzeņu ražotājs, kas veltīts veselīgas un videi draudzīgas pārtikas audzēšanai plašajā Zemgales līdzenumā. Uzņēmums izmanto tikai dabiskas metodes, atsakoties no ķīmiskiem mēslojumiem un pesticīdiem, lai nodrošinātu tīrus un uzturvielām bagātus produktus. Viņu saimniecības specializējas kviešu, miežu, auzu un dažādu dārzeņu, piemēram, burkānu un kartupeļu, audzēšanā, kas tiek novākti ar rūpību un mīlestību. Zemgales Raža lepojas ar savu ieguldījumu vietējā ekonomikā, sadarbojoties ar zemniekiem un piegādājot produkciju gan vietējiem veikaliem, gan eksportam. Tā mērķis ir popularizēt ilgtspējīgu lauksaimniecību, saglabājot Latvijas auglīgo augsni nākamajām paaudzēm. Saimniecības atrodas gleznainā lauku vidē, kur plašie lauki un zeltainās ražas atspoguļo reģiona lauksaimniecības bagātību.',
                'created_at' => now()->subDays(rand(0, 180)),
                'updated_at' => now()->subDays(rand(0, 90)),
            ],
            [
                'name' => 'Kurzemes Jūrniecība',
                'address' => 'Ostas bulvāris 7',
                'city' => $cities[array_rand($cities)],
                'country' => 'Latvija',
                'postal_code' => 'LV-' . rand(1000, 9999),
                'phone' => '2' . rand(6000000, 7999999),
                'email' => 'atbalsts@kurzemesjura.lv',
                'avatar' => 'schools/6/6.jpeg',
                'website' => 'https://kurzemesjura.lv',
                'description' => 'Kurzemes Jūrniecība ir uzticams kuģniecības un loģistikas pakalpojumu sniedzējs, kas specializējas kravu pārvadājumos pāri Baltijas jūrai un tālāk. Uzņēmums piedāvā pilna cikla risinājumus, sākot no kravu iekraušanas ostās līdz piegādei galamērķos, nodrošinot efektivitāti un precizitāti katrā posmā. Ar modernu kuģu floti un pieredzējušu komandu viņi apkalpo gan vietējos uzņēmumus, gan starptautiskus partnerus, stiprinot Latvijas kā jūras transporta mezgla lomu. Darbība koncentrējas uz Kurzemi, kur ostas, piemēram, Ventspils un Liepāja, kalpo kā uzņēmuma darbības pamats. Kurzemes Jūrniecība lepojas ar spēju pielāgoties mainīgiem laikapstākļiem un klientu vajadzībām, piedāvājot drošus un ilgtspējīgus risinājumus. Viņu misija ir veicināt Baltijas reģiona tirdzniecību, saglabājot augstus kvalitātes standartus.',
                'created_at' => now()->subDays(rand(0, 180)),
                'updated_at' => now()->subDays(rand(0, 90)),
            ],
            [
                'name' => 'Vidzemes Enerģija',
                'address' => 'Elektrības iela 1',
                'city' => $cities[array_rand($cities)],
                'country' => 'Latvija',
                'postal_code' => 'LV-' . rand(1000, 9999),
                'phone' => '2' . rand(6000000, 7999999),
                'email' => 'kontakti@vidzemeenergy.lv',
                'avatar' => 'schools/7/7.jpeg',
                'website' => 'https://vidzemeenergy.lv',
                'description' => 'Vidzemes Enerģija ir atjaunojamās enerģijas risinājumu nodrošinātājs, kas veltīts tīras un ilgtspējīgas enerģijas attīstībai Latvijā. Uzņēmums specializējas vēja turbīnu, saules paneļu un citu zaļo tehnoloģiju uzstādīšanā, piedāvājot risinājumus gan privātpersonām, gan uzņēmumiem. Viņu mērķis ir samazināt oglekļa emisijas, izmantojot Vidzemes plašos dabas resursus, piemēram, vējainos līdzenumus un saulainās dienas. Komanda cieši sadarbojas ar vietējām pašvaldībām un zemniekiem, lai integrētu atjaunojamo enerģiju reģiona infrastruktūrā. Vidzemes Enerģija lepojas ar inovatīvu pieeju, apvienojot modernas tehnoloģijas ar rūpēm par apkārtējo vidi. Uzņēmuma projekti ne vien uzlabo energoapgādi, bet arī veicina izpratni par ilgtspējīgu dzīvesveidu Latvijā.',
                'created_at' => now()->subDays(rand(0, 180)),
                'updated_at' => now()->subDays(rand(0, 90)),
            ],
            [
                'name' => 'Pērle Juvelieri',
                'address' => 'Dārgakmeņu ceļš 4',
                'city' => $cities[array_rand($cities)],
                'country' => 'Latvija',
                'postal_code' => 'LV-' . rand(1000, 9999),
                'phone' => '2' . rand(6000000, 7999999),
                'email' => 'veikals@perle.lv',
                'avatar' => 'schools/8/8.jpeg',
                'website' => 'https://perle.lv',
                'description' => 'Pērle Juvelieri ir uzņēmums, kas specializējas roku darba rotaslietu izgatavošanā, izmantojot Baltijas dzintaru un izmeklētus dārgakmeņus. Katrs izstrādājums ir unikāls, radīts ar rūpību un meistarību, lai izceltu dzintara silto mirdzumu un akmeņu dabisko skaistumu. Uzņēmums lepojas ar Latvijas dabas dārgumu – dzintara – izmantošanu, kas ir kļuvis par tā preču zīmes simbolu un saista rotas ar Baltijas kultūru. Viņu kolekcijās ietilpst kaklarotas, auskari un gredzeni, kas piemēroti gan ikdienai, gan īpašiem gadījumiem. Darbnīca atrodas klusā Latvijas nostūrī, kur juvelieri iedvesmojas no apkārtējās dabas un senajām tradīcijām. Pērle Juvelieri piedāvā personalizētus pasūtījumus, ļaujot klientiem piedalīties sava sapņu rotaslietas radīšanā.',
                'created_at' => now()->subDays(rand(0, 180)),
                'updated_at' => now()->subDays(rand(0, 90)),
            ],
        ];

        DB::table('schools')->insert($companies);
    }
}
