<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class LearningResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('learning_resources')) {
            $sort_id = 0;

            $sourceImagesPath = database_path('seeders/data/learning_resources');

            $images = [
                [
                    'directory_id' => '1',
                    'gallery' => [
                        "01J3Z0TSZZK2XTHPGTDXBCVQM3.jpg",
                        "01J3Z0TT01TR1T5BBMQRQZB7JE.jpg",
                        "01J3Z0TT053XH9EFNRV2KPJ5SQ.jpg",
                    ],
                    'image_path' => null,
                ],
                [
                    'directory_id' => '6',
                    'gallery' => [
                        "01J3Z0JWF86EA1WCGXJD3RA0R6.jpg",
                        "01J3Z0JWFA51N77WH27GNP3HAE.jpg",
                        "01J3Z0JWFBGCHSCZHH55N7CE46.jpg",
                    ],
                    'image_path' => null,
                ],
                [
                    'directory_id' => '10',
                    'gallery' => [
                        "01J3Z3ZE6SQT5F7J2NG2PW526Q.jpg",
                        "01J3Z3ZE6WT0TNSRZYZ3GJMC3K.jpg",
                        "01J3Z3ZE6Z5AZG7PWAPPWCB3M9.jpg",
                    ],
                    'image_path' => null,
                ],
                [
                    'directory_id' => '19',
                    'gallery' => [
                        "01J4SBJ6M4FJG4WG9Y39QBYV1Z.png",
                        "01J4SBJ6M889TB7TMRPCFXSP7W.jpg",
                    ],
                    'image_path' => null,
                ]
            ];

            for ($i = 0; $i < count($images); $i++) {
                $directoryId = $images[$i]['directory_id'];
                $imagePaths = [];

                // Ensure the directory exists
                if (!Storage::disk('public')->exists("learning_resources/{$directoryId}")) {
                    Storage::disk('public')->makeDirectory("learning_resources/{$directoryId}");
                }

                $existingImages = Storage::disk('public')->files("learning_resources/{$directoryId}");

                if (count($existingImages) != count($images[$i]['gallery'])) {
                    // Handle each image in the gallery
                    foreach ($images[$i]['gallery'] as $imageName) {
                        // Extract the file extension
                        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);

                        // Generate a new unique name for the image
                        $newImageName = strtoupper(Str::random(26)) . '.' . $imageExtension;

                        $imagePath = "learning_resources/{$directoryId}/$newImageName";

                        // Only save the image if it doesn't already exist in the storage
                        if (!Storage::disk('public')->exists($imagePath)) {
                            // Read the content of the image file from the source
                            $image = File::get("$sourceImagesPath/{$imageName}");

                            // Store the image with the new name
                            Storage::disk('public')->put($imagePath, $image);
                        }

                        // Add new image path to the gallery array
                        $imagePaths[] = $imagePath;
                    }
                } else {
                    $imagePaths = $existingImages;
                }

                // Store the image paths in the resources array
                $images[$i]['image_path'] = $imagePaths;
            }

            $resources = [
                // category 1
                [
                    'name' => 'Augu izvēle',
                    'description' => '<p>Augu izvēle ir viens no pirmajiem un svarīgākajiem soļiem apzaļumošanas projektā. Pareiza augu izvēle nodrošina, ka augi labi augs un attīstīsies konkrētajā vidē, radot skaistu un harmonisku ainavu.</p><p><br></p><h3>Klimatiskie apstākļi</h3><p>Izvēloties augus, ņem vērā vietējos klimatiskos apstākļus, tostarp temperatūru, nokrišņu daudzumu un saules gaismas intensitāti. Izvēlies augus, kas ir pielāgoti konkrētajam klimatam un augsnes tipam.</p><p><br></p><h3>Augsnes veids</h3><p>Analizē augsnes veidu, kurā tiks stādīti augi. Dažādiem augiem ir nepieciešamas dažādas augsnes īpašības, piemēram, pH līmenis, mitruma daudzums un barības vielu saturs.</p><p><br></p><h3>Estētiskie un funkcionālie mērķi</h3><p>Izvēlies augus atbilstoši estētiskajiem un funkcionālajiem mērķiem. Piemēram, ziedi un krūmi var pievienot krāsu un tekstūru, bet koki var nodrošināt ēnu un privātumu.</p><p><br></p><p><strong>Papildu padomi augu izvēlei</strong></p><ul><li><strong>Vietējie augi:</strong> Izvēlies vietējos augus, jo tie ir pielāgoti vietējiem klimatiskajiem apstākļiem un prasa mazāk aprūpes.</li><li><strong>Dažādība:</strong> Veido dažādus augu slāņus (koki, krūmi, zālaugi) harmoniskai un dabiskai ainavai.</li><li><strong>Ilgtspējība:</strong> Izvēlies augus, kas neprasa daudz ūdens un ir izturīgi pret vietējiem kaitēkļiem un slimībām.</li></ul><p><br></p><p><strong>Augu piemēri dažādiem mērķiem</strong></p><ul><li><strong>Ēna:</strong> Ozols, Liepa, Bērzs</li><li><strong>Krāsa:</strong> Rododendrs, Roze, Lavanda</li><li><strong>Privātums:</strong> Bukss, Ligustrs, Tūja</li></ul><p><br></p><p>Pareizi izvēlēti augi ne tikai uzlabo ainavas estētiku, bet arī veicina ekoloģisko līdzsvaru un samazina uzturēšanas izmaksas. Rūpīgi plānojot un izvēloties piemērotākos augus, tu vari radīt skaistu un ilgtspējīgu apzaļumošanas projektu.</p>',
                    'video_url' => 'https://www.youtube.com/watch?v=67pVGwKrcBw&ab_channel=D%C4%81rzn%C4%ABcaD%C4%81rzn%C4%ABca',
                    'video_type' => 'video/youtube',
                    'is_active' => 1,
                    'sort_id' => ++$sort_id,
                    'category_id' => 1,
                    'gallery' => json_encode($images[0]['image_path']),
                ],
                [
                    'name' => 'Stādīšana',
                    'description' => '<h2>Stādīšanas process</h2><p>Stādīšanas process ir nākamais būtiskais solis pēc augu izvēles. Veicot pareizu stādīšanu, augi var veiksmīgi iesakņoties un attīstīties.</p><p><br></p><h3><strong>Augu sagatavošana</strong></h3><p>Pirms stādīšanas pārbaudi augu saknes un, ja nepieciešams, viegli atbrīvo tās, lai veicinātu labāku sakņu attīstību. Pārliecinies, ka augi ir pietiekami mitrināti.</p><p><br></p><h3><strong>Stādīšanas bedres</strong></h3><p>Izrok stādīšanas bedres pietiekami dziļas un platas, lai augi varētu brīvi izplest savas saknes. Parasti bedre jābūt divreiz platāka un tikpat dziļa, cik auga sakņu bumba.</p><p><br></p><h3><strong>Augsnes sagatavošana</strong></h3><p>Pirms augu ievietošanas bedrē, uzlabo augsnes kvalitāti, pievienojot organiskās vielas, piemēram, kompostu vai kūdru, kas bagātina augsni ar barības vielām un uzlabo tās struktūru.</p><p><br></p><h3><strong>Augu ievietošana</strong></h3><p>Ievieto augu bedrē, rūpīgi aizpildot to ar augsni. Pārliecinies, ka auga saknes ir pilnībā pārklātas ar augsni un augs ir stabili nostiprināts.</p><p><br></p><p><strong>Papildu padomi stādīšanai</strong></p><ul><li><strong>Laiks:</strong> Stādi augus agri no rīta vai vēlu vakarā, lai izvairītos no karstuma stresa.</li><li><strong>Laistīšana:</strong> Pēc stādīšanas rūpīgi aplaisti augus, lai nodrošinātu labu sakņu kontaktu ar augsni.</li><li><strong>Mulčēšana:</strong> Uzklāj mulču ap augiem, lai saglabātu mitrumu un samazinātu nezāļu augšanu.</li></ul><p><br></p><p><strong>Kļūdas, no kurām izvairīties</strong></p><ul><li><strong>Pārāk dziļa stādīšana:</strong> Neaudz augus pārāk dziļi, jo tas var ierobežot sakņu elpošanu un izraisīt puvi.</li><li><strong>Pārāk sekla stādīšana:</strong> Augu saknes nedrīkst būt pakļautas gaisam, jo tās var izžūt un bojāties.</li><li><strong>Nepietiekama laistīšana:</strong> Regulāra laistīšana ir būtiska, īpaši pirmajos mēnešos pēc stādīšanas, lai augi varētu veiksmīgi iesakņoties.</li></ul><p><br></p><p>Pareiza stādīšana ir būtisks solis ceļā uz veselīgu un skaistu dārzu. Rūpīgi sekojot šiem soļiem un padomiem, tu vari nodrošināt, ka tavi augi veiksmīgi aug un attīstās, radot estētiski pievilcīgu un ilgtspējīgu ainavu.</p>',
                    'is_active' => rand(0, 1),
                    'sort_id' => ++$sort_id,
                    'category_id' => 1,
                    'video_type' => 'video/youtube',
                    'video_url' => 'https://www.youtube.com/watch?v=iHUKPYW1lFk&ab_channel=R%C4%ABtaPanor%C4%81ma',
                    'gallery' => json_encode(null),
                ],
                [
                    'name' => 'Kopšana',
                    'description' => '<p>Augu kopšana ir svarīgs aspekts, lai nodrošinātu, ka augi aug veselīgi un attīstās saskaņā ar plānotajiem mērķiem. Regulāra kopšana palīdz novērst slimības un kaitēkļus, kā arī uztur augu estētisko izskatu.</p><p><br></p><h3>Laistīšana</h3><p>Nodrošini regulāru laistīšanu, īpaši pirmajā gadā pēc stādīšanas, kad augi vēl nav pilnībā iesakņojušies. Laistīšanas biežums un daudzums ir atkarīgs no augu veida, klimatiskajiem apstākļiem un augsnes mitruma.<br>&nbsp;<strong>Padoms:</strong> Laisti augus agri no rīta vai vēlu vakarā, lai samazinātu ūdens iztvaikošanu.</p><p><br></p><h3>Mēslošana</h3><p>Piemērotā mēslošana nodrošina augiem nepieciešamās barības vielas. Izvēlies mēslošanas līdzekļus, kas atbilst augu vajadzībām un augsnes īpašībām. Parasti mēslojumu lieto pavasarī un vasarā.<br>&nbsp;<strong>Padoms:</strong> Izmanto organiskos mēslošanas līdzekļus, lai veicinātu ilgtermiņa augsnes veselību.</p><p><br></p><h3>Atzarošana</h3><p>Regulāra atzarošana veicina augu veselību un uzlabo to formu. Noņem sausus, slimus vai bojātus zarus, lai veicinātu jaunu dzinumu augšanu un uzlabotu gaisa cirkulāciju starp zariem.<br>&nbsp;<strong>Padoms:</strong> Atzarošanu veic pavasarī vai vēlā rudenī, kad augi ir miera periodā.</p><p><br></p><h3>Aizsardzība pret kaitēkļiem un slimībām</h3><p>Sekojot līdzi augu veselībai, var savlaicīgi pamanīt kaitēkļus vai slimības. Izmanto bioloģiskās vai ķīmiskās aizsardzības līdzekļus atbilstoši situācijai.<br>&nbsp;<strong>Padoms:</strong> Regulāri pārbaudi augus un ievies profilaktiskus pasākumus, piemēram, stādījumu mulčēšanu un sabalansētu laistīšanu, lai mazinātu kaitēkļu un slimību risku.</p><p><br></p><p><strong>Papildu padomi kopšanai</strong></p><ul><li><strong>Mulčēšana:</strong> Uzklāj mulču ap augiem, lai saglabātu mitrumu un samazinātu nezāļu augšanu.</li><li><strong>Ziemas aizsardzība:</strong> Aizsargā augus pret salu, uzklājot tiem pārsegumus vai izmantojot citu aizsardzību.</li><li><strong>Komposta izmantošana:</strong> Izmanto kompostu, lai uzlabotu augsnes struktūru un nodrošinātu augiem dabīgās barības vielas.</li></ul><p><br></p><p>Regulāra un rūpīga augu kopšana nodrošina, ka tavi augi attīstās veselīgi un skaisti. Ievērojot šos padomus, tu vari izveidot ilgtspējīgu un estētiski pievilcīgu ainavu, kas priecēs gan tevi, gan apkārtējos.</p>',
                    'video_url' => 'https://vimeo.com/groups/2343/videos/983564783',
                    'video_type' => 'video/vimeo',
                    'is_active' => 1,
                    'sort_id' => ++$sort_id,
                    'category_id' => 1,
                    'gallery' => json_encode(null),
                ],
                [
                    'name' => 'Ilgtspējīga apzaļumošana',
                    'description' => '<p>Ilgtspējīga apzaļumošana ir pieeja, kas veicina ekoloģiski līdzsvarotu un videi draudzīgu ainavu veidošanu. Šī pieeja ne tikai uzlabo estētiku, bet arī veicina dabas resursu saglabāšanu un ekoloģisko līdzsvaru.</p><p><br></p><h3>Vietējie augi</h3><p>Izmanto vietējos augus, jo tie ir labāk pielāgoti vietējiem klimatiskajiem un augsnes apstākļiem, kā arī prasa mazāk resursu kopšanai.<br>&nbsp;<strong>Padoms:</strong> Vietējie augi parasti ir izturīgāki pret kaitēkļiem un slimībām, kas samazina nepieciešamību pēc ķīmiskajiem pesticīdiem un mēslošanas līdzekļiem.</p><p><br></p><h3>Ūdens resursu efektīva izmantošana</h3><p>Izmanto efektīvas laistīšanas sistēmas, piemēram, pilienveida laistīšanu, lai samazinātu ūdens patēriņu. Veido lietus dārzus un citas ūdens aiztures sistēmas, kas palīdz uzturēt ūdens līdzsvaru ainavā.<br>&nbsp;<strong>Padoms:</strong> Izvēlies augu veidus, kas ir izturīgi pret sausumu un prasa mazāk ūdens, lai papildus samazinātu ūdens patēriņu.</p><p><br></p><h3>Bioloģiskā daudzveidība</h3><p>Veido apstādījumus, kas veicina bioloģisko daudzveidību, piesaistot putnus, kukaiņus un citus savvaļas dzīvniekus. Dažādu augu sugu un veidu izmantošana rada veselīgāku un izturīgāku ekosistēmu.<br>&nbsp;<strong>Padoms:</strong> Izveido daudzslāņu apstādījumus, kas ietver ziedus, krūmus un kokus, lai nodrošinātu dažādus dzīvotņu veidus un pārtikas avotus vietējām dzīvnieku sugām.</p><p><br></p><ul><li><strong>Papildu padomi ilgtspējīgai apzaļumošanai</strong></li><li><strong>Kompostēšana:</strong> Izmanto kompostu, lai uzlabotu augsnes kvalitāti un samazinātu organisko atkritumu daudzumu.</li><li><strong>Vides aizsardzība:</strong> Samazini ķīmisko vielu lietošanu un izvēlies dabiskus aizsardzības līdzekļus pret kaitēkļiem un slimībām.</li><li><strong>Enerģijas efektivitāte:</strong> Izvēlies enerģiju taupošas apgaismojuma un ūdens sistēmas, lai samazinātu enerģijas patēriņu.</li></ul><p><br></p><p>Ilgtspējīga apzaļumošana palīdz radīt ne tikai estētiski pievilcīgas ainavas, bet arī veicina veselīgu ekosistēmu un saglabā dabas resursus nākamajām paaudzēm. Ievērojot šos principus, tu vari izveidot vidi, kas ir gan skaista, gan videi draudzīga.</p>',
                    'is_active' => rand(0, 1),
                    'sort_id' => ++$sort_id,
                    'category_id' => 1,
                    'video_type' => null,
                    'video_url' => null,
                    'gallery' => json_encode(null),
                ],

                // category 2
                [
                    'name' => 'Teritorijas plānošana',
                    'description' => '<p>Teritorijas plānošana ir pirmais solis efektīvā labiekārtošanas projektā. Tā ietver detalizētu teritorijas izpēti un plānošanu, lai nodrošinātu, ka visa teritorija tiek izmantota efektīvi un estētiski pievilcīgi.</p><p><br></p><h3>Plānošanas process</h3><p>1. <strong>Teritorijas analīze:</strong> Izpēti esošo teritoriju, tās topogrāfiju, augsni un esošos objektus.<br> 2. <strong>Vajadzību noteikšana:</strong> Identificē klienta vajadzības un vēlmes, piemēram, atpūtas zonas, spēļu laukumi vai ainavas elementi.<br> 3. <strong>Plāna izstrāde:</strong> Izveido teritorijas plānu, kas ietver visus nepieciešamos elementus, piemēram, takas, apstādījumus un apgaismojumu.</p><p><br></p><h3>Papildu padomi</h3><ul><li><strong>Izmanto profesionālus rīkus:</strong> Lieto teritorijas plānošanas programmatūru vai konsultējies ar ainavu arhitektu.</li><li><strong>Plāno ilgtspējīgi:</strong> Iekļauj elementus, kas veicina ilgtspējīgu attīstību, piemēram, ūdens uzkrāšanas sistēmas un vietējos augus.</li></ul>',
                    'is_active' => 1,
                    'sort_id' => ++$sort_id,
                    'category_id' => 2,
                    'video_type' => null,
                    'video_url' => null,
                    'gallery' => json_encode($images[1]['image_path']),
                ],
                [
                    'name' => 'Apgaismojuma risinājumi',
                    'description' => '<p>Apgaismojums ir svarīga labiekārtošanas sastāvdaļa, kas uzlabo teritorijas funkcionalitāti un estētiku. Pareizi izvēlēts un izvietots apgaismojums var radīt drošu un patīkamu vidi gan dienā, gan naktī.</p><p><br></p><h3>Apgaismojuma veidi</h3><p>1. <strong>Ceļu apgaismojums:</strong> Nodrošina drošību un orientāciju naktī, piemēram, ceļa lampas vai sienas prožektori.<br> 2. <strong>Dekoratīvais apgaismojums:</strong> Uzlabo teritorijas estētiku, izmantojot dekoratīvas gaismas instalācijas vai krāsainus LED apgaismojuma elementus.<br> 3. <strong>Uzsvēruma apgaismojums:</strong> Izceļ specifiskus ainavas elementus, piemēram, kokus, statujas vai ūdens elementus.</p><p><br></p><h3>Papildu padomi</h3><ul><li><strong>Izvēlies enerģiju taupošas lampas:</strong> LED lampas ir enerģijas efektīvas un ilgstošas.</li><li><strong>Regulējams apgaismojums:</strong> Izmanto apgaismojuma kontrolierus, lai regulētu gaismas intensitāti un krāsu temperatūru.</li></ul>',
                    'video_url' => null,
                    'video_type' => null,
                    'is_active' => 1,
                    'sort_id' => ++$sort_id,
                    'category_id' => 2,
                    'gallery' => json_encode(null),
                ],
                [
                    'name' => 'Ceļu un ietvju būvniecība',
                    'description' => '<p>Ceļu un ietvju būvniecība ir būtisks labiekārtošanas aspekts, kas nodrošina piekļuvi dažādām teritorijas daļām un uzlabo funkcionalitāti. Pareiza ceļu un ietvju izveide veicina ērtības un drošību.</p><p><br></p><h3>Būvniecības process</h3><p>1. <strong>Plānošana:</strong> Izstrādā ceļu un ietvju plānu, ņemot vērā teritorijas vajadzības un iespējamās slodzes.<br> 2. <strong>Materiālu izvēle:</strong> Izvēlies piemērotus materiālus, piemēram, bruģakmeni, asfaltu vai betonu.<br> 3. <strong>Izpilde:</strong> Veic nepieciešamos rakšanas darbus, sagatavo pamatni un uzklāj izvēlētos materiālus.</p><p><br></p><h3>Papildu padomi</h3><ul><li><strong>Novērst ūdens uzkrāšanos:</strong> Pārliecinies, ka ceļiem un ietvēm ir pareiza slīpuma leņķis, lai nodrošinātu ūdens noteci.</li><li><strong>Izvēlies augstas kvalitātes materiālus:</strong> Augstas kvalitātes materiāli nodrošinās ilgmūžību un minimizēs nepieciešamību pēc remontiem.</li></ul>',
                    'is_active' => rand(0, 1),
                    'sort_id' => ++$sort_id,
                    'category_id' => 2,
                    'video_type' => null,
                    'video_url' => null,
                    'gallery' => json_encode(null),
                ],
                [
                    'name' => 'Apstādījumu un zāliena uzturēšana',
                    'description' => '<p>Apstādījumu un zāliena uzturēšana ir svarīga, lai saglabātu teritorijas estētiku un veselību. Regulāra aprūpe palīdz saglabāt augus un zāli veseliem un pievilcīgiem.</p><p><br></p><h3>Uzturēšanas darbi</h3><p>1. <strong>Laistīšana:</strong> Nodrošini regulāru laistīšanu, lai augsne paliktu mitra un augi attīstītos labi.<br> 2. <strong>Atzarošana:</strong> Regulāri atzaro krūmus un kokus, lai veicinātu to veselīgu augšanu un saglabātu vēlamo formu.<br> 3. <strong>Ravēšana:</strong> Noņem nezāles un citu nevēlamu veģetāciju, lai novērstu konkurenci ar galvenajiem augiem.</p><p><br></p><h3>Papildu padomi</h3><ul><li><strong>Regulāra mēslošana:</strong> Lieto mēslošanas līdzekļus, kas ir piemēroti konkrētajiem augiem un zālei.</li><li><strong>Sezonāla apkope:</strong> Veic sezonālus darbus, piemēram, rudens lapu savākšanu un ziemas aizsardzības pasākumus.</li></ul>',
                    'video_url' => 'https://vimeo.com/150084090',
                    'video_type' => 'video/vimeo',
                    'is_active' => rand(0, 1),
                    'sort_id' => ++$sort_id,
                    'category_id' => 2,
                    'gallery' => json_encode(null),
                ],

                // category 3
                [
                    'name' => 'Bruģēšanas materiālu izvēle',
                    'description' => '<p>Bruģēšanas materiālu izvēle ir būtiska, lai nodrošinātu ilgmūžību, funkcionalitāti un estētiku. Atkarībā no projekta prasībām un apstākļiem, jāizvēlas piemēroti materiāli, kas atbilst jūsu vajadzībām.</p><p><br></p><h3>Populāri bruģēšanas materiāli</h3><p>1. <strong>Bruģakmens:</strong> Populārs materiāls, kas ir pieejams dažādās formās un izmēros. Bruģakmens ir izturīgs un ilgstošs, un tas piedāvā plašu krāsu un tekstūru izvēli.<br> 2. <strong>Betons:</strong> Betons tiek izmantots gan kā šķērsgriezuma materiāls, gan arī kā apdare. Tas ir izturīgs pret lielu slodzi un dažādiem laikapstākļiem, taču var prasīt papildu apstrādi, lai nodrošinātu estētisku izskatu.<br> 3. <strong>Akmeņi:</strong> Dabiski akmeņi, piemēram, granīts vai bazalts, piedāvā unikālu un elegantu izskatu. Tie ir ļoti izturīgi, bet bieži vien ir dārgāki un prasa profesionālu apstrādi.</p><p><br></p><h3>Materiālu izvēles kritēriji</h3><p>1. <strong>Izmantošanas intensitāte:</strong> Pārliecinies, ka materiāls ir piemērots paredzētajam slodzes līmenim. Piemēram, vietās ar lielu transportlīdzekļu satiksmi nepieciešami izturīgāki materiāli.<br> 2. <strong>Estētika:</strong> Izvēlies materiālus, kas atbilst teritorijas stilam un krāsu shēmai. Materiālu tekstūra un krāsa var ievērojami ietekmēt ainavas izskatu.<br> 3. <strong>Uzturēšana:</strong> Daži materiāli prasa biežāku apkopi nekā citi. Apsver, cik daudz laika un resursu esi gatavs ieguldīt materiālu uzturēšanā.</p>',
                    'video_url' => 'https://www.youtube.com/watch?v=9dMn4tVrYX4&ab_channel=MassHardscaper',
                    'video_type' => 'video/youtube',
                    'is_active' => 1,
                    'sort_id' => ++$sort_id,
                    'category_id' => 3,
                    'gallery' => json_encode(null),
                ],
                [
                    'name' => 'Bruģēšanas procesu soļi',
                    'description' => '<p>Būvniecības process bruģēšanai ietver vairākus svarīgus soļus, kas ir jāveic, lai nodrošinātu kvalitatīvu un ilgstošu rezultātu. Katrs solis ir būtisks, lai nodrošinātu, ka bruģis būs stabils un izturīgs.</p><p><br></p><h3>1. Sagatavošanās darbi</h3><p>Pirms sākt bruģēšanu, ir svarīgi veikt sagatavošanās darbus. Tie ietver:</p><ul><li><strong>Teritorijas sagatavošana:</strong> Noņem esošos segumus, nezāles un citus materiālus, lai sagatavotu pamatni bruģēšanai.</li><li><strong>Pamatnes izveide:</strong> Izraki pamatni, kas ir pietiekami dziļa un plaša, lai izturētu paredzēto slodzi. Pamatnei jābūt līdzsvarotai un stabilai.</li><li><strong>Grants vai šķembu slāņa uzklāšana:</strong> Uzklāj un izlīdzini grants vai šķembu slāni, kas kalpo kā drenāžas sistēma un nodrošina stabilitāti.</li></ul><p><br></p><h3>2. Bruģa uzklāšana</h3><p>1. <strong>Grīdmaiņa uzklāšana:</strong> Novieto grīdmaiņu, lai nodrošinātu vienmērīgu virsmu un pareizu bruģa izvietojumu.<br> 2. <strong>Bruģa uzklāšana:</strong> Novieto bruģakmeni vai citus bruģēšanas materiālus uz sagatavotās virsmas, izmantojot līmeni un lāzeri, lai nodrošinātu vienmērīgu izvietojumu.<br> 3. <strong>Fugas aizpildīšana:</strong> Aizpildi spraugas starp bruģakmeņiem ar speciālu fugas materiālu, lai nodrošinātu stabilitāti un novērstu augu izaugšanu.</p><p><br></p><h3>3. Apdare un apkope</h3><p>1. <strong>Apdare:</strong> Veic nepieciešamos apdares darbus, piemēram, saspiešanu un tīrīšanu, lai nodrošinātu gludu virsmu.<br> 2. <strong>Regulāra apkope:</strong> Veic regulāru apkopi, lai saglabātu bruģa izskatu un funkcionalitāti, ieskaitot tīrīšanu un šuvju pārbaudi.</p>',
                    'video_url' => null,
                    'video_type' => null,
                    'is_active' => 1,
                    'sort_id' => ++$sort_id,
                    'category_id' => 3,
                    'gallery' => json_encode($images[2]['image_path']),
                ],
                [
                    'name' => 'Izplatītākās kļūdas bruģēšanā un kā tās novērst',
                    'description' => '<p>Bieži vien bruģēšanas projektos tiek pieļautas kļūdas, kas var ietekmēt gala rezultātu. Izpratne par šīm kļūdām un to novēršana palīdzēs nodrošināt ilgstošu un kvalitatīvu bruģējumu.</p><p><br></p><h3>1. Nepietiekama pamatnes sagatavošana</h3><p>Viens no biežākajiem problēmu avotiem ir nepietiekama pamatnes sagatavošana. Ja pamatne nav pietiekami stabila, bruģis var sākt kustēties vai sabrukt.<br>&nbsp;<strong>Risinājums:</strong> Pārliecinies, ka pamatne ir pareizi sagatavota un kompakta, izmantojot piemērotu kompresoru un pārbaudot pamatnes līmeni.</p><p><br></p><h3>2. Nepareiza bruģa izvietošana</h3><p>Pareiza bruģa izvietošana ir svarīga, lai nodrošinātu vienmērīgu virsmu. Ja bruģakmeņi nav izvietoti pareizi, tas var novest pie nevienmērīga izlīdzinājuma un nepatīkama izskata.<br>&nbsp;<strong>Risinājums:</strong> Izmanto līmeni un lāzeri, lai pārbaudītu bruģa izlīdzinājumu, un pārliecinies, ka visi bruģakmeņi ir izvietoti vienādi.</p><p><br></p><h3>3. Nepareiza fugas aizpildīšana</h3><p>Ja fuga nav pareizi aizpildīta vai izmantots nepiemērots materiāls, tas var novest pie izskata problēmām un ūdens uzkrāšanās.<br>&nbsp;<strong>Risinājums:</strong> Izvēlies kvalitatīvu fugas materiālu un pārliecinies, ka tas ir vienmērīgi aizpildīts. Regulāri pārbaudi un, ja nepieciešams, veic papildus aizpildīšanu.</p><p><br></p><h3>Papildu padomi</h3><ul><li><strong>Izvēlies kvalitatīvus materiālus:</strong> Augstas kvalitātes materiāli nodrošinās ilgstošu un estētisku rezultātu.</li><li><strong>Regulāra apkope:</strong> Veic regulāru apkopi, lai uzturētu bruģi labā stāvoklī un novērstu problēmas.</li><li><strong>Profesionāla palīdzība:</strong> Ja nav pieredzes, apsver iespēju konsultēties ar profesionāļiem vai uzdot jautājumus ekspertiem.</li></ul>',
                    'video_url' => null,
                    'video_type' => null,
                    'is_active' => rand(0, 1),
                    'sort_id' => ++$sort_id,
                    'category_id' => 3,
                    'gallery' => json_encode(null),
                ],

                // category 4
                [
                    'name' => 'Projekta plānošana un sagatavošana',
                    'description' => '<p>Projekta plānošana un sagatavošana ir pirmais un viens no svarīgākajiem soļiem veiksmīga labiekārtošanas projekta īstenošanā. Laba plānošana nodrošina, ka projekts tiek veikts laikā, budžetā un atbilstoši noteiktajām prasībām.</p><p><br></p><h3>Projekta definēšana</h3><p>1. <strong>Mērķu noteikšana:</strong> Definē projekta mērķus un vēlamo gala rezultātu. Skaidri definēti mērķi palīdz koncentrēties uz galvenajām aktivitātēm un resursu izmantošanu.<br> 2. <strong>Projekta apjoma noteikšana:</strong> Precīzi nosaki projekta apjomu, iekļaujot visus nepieciešamos darbus un aktivitātes. Detalizēts apjoma apraksts palīdz novērst pārpratumus un nodrošina, ka visi iesaistītie saprot savus uzdevumus.<br> 3. <strong>Budžeta plānošana:</strong> Izveido detalizētu budžetu, iekļaujot visus iespējamos izdevumus, piemēram, materiālu, darba spēka un tehnikas izmaksas. Plānojot budžetu, ņem vērā arī neparedzētus izdevumus.</p><p><br></p><h3>Projekta komandas veidošana</h3><p>1. <strong>Komandas locekļu izvēle:</strong> Izvēlies kompetentus un pieredzējušus speciālistus, kuri būs atbildīgi par dažādām projekta daļām. Svarīgi ir nodrošināt, ka visi komandas locekļi saprot savus pienākumus un atbildību.<br> 2. <strong>Komandas apmācība:</strong> Nodrošini, ka visi komandas locekļi ir apmācīti un sagatavoti veikt savus uzdevumus efektīvi un droši. Apmācība var ietvert gan tehniskās zināšanas, gan drošības prasības.</p><p><br></p><h3>Projekta plāna izstrāde</h3><p>1. <strong>Laika grafika izveide:</strong> Izstrādā detalizētu laika grafiku, iekļaujot visus projekta posmus un uzdevumus. Precīzs laika grafiks palīdz nodrošināt, ka projekts tiek veikts laikā un visi darbi tiek veikti saskaņā ar plānu.<br> 2. <strong>Riska analīze:</strong> Veic riska analīzi, identificējot potenciālos riskus un izstrādājot riska vadības plānu. Riska analīze palīdz sagatavoties iespējamām problēmām un veikt preventīvus pasākumus.</p><p><br></p><h3>Papildu padomi</h3><ul><li><strong>Izmanto projekta vadības programmatūru:</strong> Projektu vadības rīki palīdz organizēt un sekot līdzi projekta progresam.</li><li><strong>Regulāra saziņa:</strong> Nodrošini regulāru saziņu ar visiem projekta dalībniekiem, lai nodrošinātu informācijas apmaiņu un problēmu savlaicīgu risināšanu.</li></ul>',
                    'video_url' => null,
                    'video_type' => null,
                    'is_active' => 1,
                    'sort_id' => ++$sort_id,
                    'category_id' => 4,
                    'gallery' => json_encode(null),
                ],
                [
                    'name' => 'Projekta izpilde un uzraudzība',
                    'description' => '<p>Projekta izpilde un uzraudzība ir būtiski soļi veiksmīga projekta vadībā. Šajā posmā tiek veikti praktiski darbi un nodrošināta nepārtraukta uzraudzība, lai projekts tiktu veikts saskaņā ar plānu.</p><p><br></p><h3>Darbu izpilde</h3><p>1. <strong>Darbu sadale:</strong> Precīzi sadali darbus starp komandas locekļiem, nodrošinot, ka visi saprot savus uzdevumus un atbildību. Darbu sadale palīdz nodrošināt efektīvu resursu izmantošanu un novērst pārklāšanos.<br> 2. <strong>Resursu nodrošināšana:</strong> Nodrošini, ka visi nepieciešamie resursi, piemēram, materiāli, tehnika un instrumenti, ir pieejami un savlaicīgi piegādāti. Pareiza resursu plānošana novērš darba pārtraukumus un aizkavēšanos.<br> 3. <strong>Izpildes kvalitātes nodrošināšana:</strong> Veic regulāras kvalitātes pārbaudes, lai pārliecinātos, ka visi darbi tiek veikti saskaņā ar noteiktajiem standartiem un prasībām.</p><p><br></p><h3>Projekta uzraudzība</h3><p>1. <strong>Progresijas uzraudzība:</strong> Regulāri seko līdzi projekta progresam, salīdzinot to ar sākotnējo plānu un laika grafiku. Progresijas uzraudzība palīdz savlaicīgi identificēt iespējamās problēmas un veikt korekcijas.<br> 2. <strong>Finanšu uzraudzība:</strong> Seko līdzi izdevumiem un salīdzini tos ar budžetu, lai novērstu pārtēriņus. Finanšu uzraudzība nodrošina, ka projekts tiek veikts budžetā.<br> 3. <strong>Riska vadība:</strong> Regulāri pārskati riska vadības plānu un veic nepieciešamos pasākumus, lai novērstu vai mazinātu riskus.</p><p><br></p><h3>Papildu padomi</h3><ul><li><strong>Regulāras tikšanās:</strong> Organizē regulāras komandas tikšanās, lai apspriestu progresu, risinātu problēmas un plānotu turpmākos darbus.</li><li><strong>Dokumentācija:</strong> Nodrošini detalizētu projekta dokumentāciju, kas ietver visus būtiskos datus, izmaiņas un atskaites.</li></ul>',
                    'video_url' => null,
                    'video_type' => null,
                    'is_active' => 1,
                    'sort_id' => ++$sort_id,
                    'category_id' => 4,
                    'gallery' => json_encode(null),
                ],
                [
                    'name' => 'Projekta noslēgšana un novērtēšana',
                    'description' => '<p>Projekta noslēgšana un novērtēšana ir pēdējais posms projekta vadībā, kas nodrošina, ka visi darbi ir veikti saskaņā ar plānu un rezultāti atbilst klienta prasībām. Šajā posmā tiek veikta arī projekta analīze, lai mācītos no pieredzes un uzlabotu nākotnes projektus.</p><p><br></p><h3>Darbu pabeigšana</h3><p>1. <strong>Darbu pārbaude:</strong> Veic gala pārbaudi, lai pārliecinātos, ka visi darbi ir veikti atbilstoši specifikācijām un kvalitātes standartiem. Pārbaude ietver arī klienta apmierinātības novērtēšanu.<br> 2. <strong>Dokumentācijas pabeigšana:</strong> Sagatavo un iesniedz visu nepieciešamo dokumentāciju, piemēram, atskaites, sertifikātus un garantijas dokumentus. Pareiza dokumentācija nodrošina, ka visi projekta aspekti ir dokumentēti un pārskatāmi.<br> 3. <strong>Teritorijas sakopšana:</strong> Pabeidzot projektu, nodrošini, ka teritorija ir sakopta un visi būvgruži un atkritumi ir savākti. Sakopta teritorija ir būtiska gan vizuāli, gan drošības apsvērumu dēļ.</p><p><br></p><h3>Projekta novērtēšana</h3><p>1. <strong>Projekta analīze:</strong> Veic detalizētu projekta analīzi, lai novērtētu, kas ir izdevies labi un kur ir iespējami uzlabojumi. Analīze palīdz izprast stiprās un vājās puses un uzlabot nākotnes projektu vadību.<br> 2. <strong>Komandas atsauksmes:</strong> Iegūst atsauksmes no visiem projekta dalībniekiem, lai izprastu viņu pieredzi un ieteikumus. Atsauksmes palīdz uzlabot komandas darbu un sadarbību.<br> 3. <strong>Klienta atsauksmes:</strong></p>',
                    'video_url' => null,
                    'video_type' => null,
                    'is_active' => rand(0, 1),
                    'sort_id' => ++$sort_id,
                    'category_id' => 4,
                    'gallery' => json_encode(null),
                ],

                // category 5
                [
                    'name' => 'Drošības noteikumi darbam ar tehniku',
                    'description' => '<p>Darba drošība un pareiza tehnikas izmantošana ir būtiski aspekti, lai nodrošinātu darbinieku drošību un efektīvu darba izpildi. Ievērojot drošības noteikumus, iespējams novērst negadījumus un samazināt darba traumu risku.</p><p><br></p><h3>Drošības noteikumu pamatprincipi</h3><p>1. <strong>Individuālie aizsardzības līdzekļi:</strong> Lieto atbilstošus individuālos aizsardzības līdzekļus (IAL), piemēram, ķiveres, aizsargbrilles, austiņas, cimdi un aizsargapavus. Šie līdzekļi palīdz aizsargāt pret fiziskiem un ķīmiskiem riskiem.<br> 2. <strong>Darba vietas sagatavošana:</strong> Nodrošini, ka darba vieta ir tīra un kārtīga, lai novērstu iespējamus riskus. Pārliecinies, ka visi darba instrumenti un tehnikas vienības ir pienācīgi izvietoti un viegli pieejami.</p><p><br></p><h3>Drošības pasākumi darbā ar tehniku</h3><p>1. <strong>Tehnikas pārbaude:</strong> Pirms darba uzsākšanas veic tehnikas pārbaudi, lai pārliecinātos, ka tā ir darba kārtībā. Pārbaude ietver arī degvielas līmeņa, eļļas un hidraulikas šķidruma pārbaudi.<br> 2. <strong>Darbinieku apmācība:</strong> Nodrošini, ka visi darbinieki ir apmācīti darbam ar konkrēto tehniku un zina, kā to droši izmantot. Apmācība ietver gan praktiskas, gan teorētiskas zināšanas.<br> 3. <strong>Drošības instrukcijas:</strong> Ievēro visas drošības instrukcijas un norādījumus, kas attiecas uz konkrēto tehniku. Instrukcijas palīdz izprast pareizu tehnikas izmantošanu un novērst iespējamus riskus.</p><p><br></p><h3>Papildu padomi</h3><ul><li><strong>Izmanto tehniskās apkopes plānu:</strong> Regulāra tehniskā apkope palīdz uzturēt tehniku labā darba kārtībā un novērst iespējamos bojājumus.</li><li><strong>Sadarbošanās ar kolēģiem:</strong> Veicot darbus ar tehniku, sadarbojies ar kolēģiem un ievēro kopējos drošības pasākumus, lai novērstu negadījumus.</li></ul>',
                    'video_url' => 'https://www.youtube.com/watch?v=EPBtmXsvWeY&ab_channel=WorkSafeVP',
                    'video_type' => 'video/youtube',
                    'is_active' => 1,
                    'sort_id' => ++$sort_id,
                    'category_id' => 5,
                    'gallery' => json_encode(null),
                ],
                [
                    'name' => 'Darba vietas sagatavošana un organizācija',
                    'description' => '<p>Darba vietas pareiza sagatavošana un organizācija ir būtiska, lai nodrošinātu drošu un efektīvu darba vidi. Labi organizēta darba vieta palīdz novērst negadījumus un uzlabo darba kvalitāti.</p><p><br></p><h3>Darba vietas sagatavošanas soļi</h3><p>1. <strong>Tīrība un kārtība:</strong> Uzturi darba vietu tīru un kārtīgu, lai novērstu slīdēšanas, klupšanas un kritiena riskus. Izmanto atkritumu tvertnes un konteinerus, lai savāktu būvgružus un atkritumus.<br> 2. <strong>Darba zonas nožogošana:</strong> Nožogo darba zonu un izvieto brīdinājuma zīmes, lai novērstu nepiederošu personu piekļuvi. Tas palīdz novērst negadījumus un nodrošina, ka darba zona ir droša.<br> 3. <strong>Tehnikas un instrumentu izvietošana:</strong> Izvieto tehniku un instrumentus tā, lai tie būtu viegli pieejami un droši lietojami. Pārliecinies, ka tehnikas vienības ir pareizi novietotas un stabilas.</p><p><br></p><h3>Darba vietas organizācija</h3><p>1. <strong>Darba vietas plānošana:</strong> Izstrādā detalizētu darba vietas plānu, iekļaujot visus nepieciešamos darbus un aktivitātes. Plāns palīdz nodrošināt, ka visi darbi tiek veikti saskaņā ar grafiku un kvalitātes standartiem.<br> 2. <strong>Darbinieku sadalījums:</strong> Precīzi sadali darbus starp darbiniekiem, lai nodrošinātu efektīvu darba izpildi. Katram darbiniekam jābūt skaidri definētām uzdevumam un atbildībai.<br> 3. <strong>Darba kontrole un uzraudzība:</strong> Regulāri kontrolē un uzraugi darba procesu, lai nodrošinātu, ka visi darbi tiek veikti atbilstoši plānam un drošības noteikumiem.</p><p><br></p><h3>Papildu padomi</h3><ul><li><strong>Izmanto drošības norobežojumus:</strong> Drošības norobežojumi palīdz novērst nepiederošu personu piekļuvi un nodrošina, ka darba zona ir droša.</li><li><strong>Regulāra komunikācija:</strong> Nodrošini regulāru komunikāciju ar darbiniekiem, lai informētu par iespējamiem riskiem un drošības pasākumiem.</li></ul>',
                    'video_url' => null,
                    'video_type' => null,
                    'is_active' => rand(0, 1),
                    'sort_id' => ++$sort_id,
                    'category_id' => 5,
                    'gallery' => json_encode(null),
                ],
                [
                    'name' => 'Riska vadība un nelaimes gadījumu novēršana',
                    'description' => '<p>Riska vadība un nelaimes gadījumu novēršana ir būtiski aspekti darba drošībā. Pareiza riska vadība palīdz identificēt un novērst potenciālos riskus, tādējādi nodrošinot drošu darba vidi.</p><p><br></p><h3>Riska vadības pamatprincipi</h3><p>1. <strong>Riska identifikācija:</strong> Identificē visus iespējamos riskus, kas var ietekmēt darba drošību. Riska identifikācija ietver gan fiziskus, gan ķīmiskus riskus, kā arī darba organizācijas aspektus.<br> 2. <strong>Riska novērtēšana:</strong> Veic detalizētu riska novērtēšanu, lai noteiktu katra riska nozīmīgumu un iespējamās sekas. Riska novērtēšana palīdz prioritizēt riska vadības pasākumus.<br> 3. <strong>Riska kontrole:</strong> Izstrādā un īsteno riska kontroles pasākumus, lai novērstu vai mazinātu identificētos riskus. Kontroles pasākumi var ietvert tehniskos risinājumus, darba procedūru izmaiņas un darbinieku apmācību.</p><p><br></p><h3>Nelaimes gadījumu novēršana</h3><p>1. <strong>Darbinieku apmācība:</strong> Nodrošini regulāru darbinieku apmācību par darba drošību un riska vadību. Apmācība palīdz darbiniekiem izprast riskus un veikt preventīvus pasākumus.<br> 2. <strong>Drošības kultūra:</strong> Veido drošības kultūru, kurā visi darbinieki ir atbildīgi par darba drošību un aktīvi iesaistās riska vadībā. Drošības kultūra veicina drošības apziņu un atbildību.<br> 3. <strong>Nelaimes gadījumu analīze:</strong> Veic detalizētu nelaimes gadījumu analīzi, lai izprastu to cēloņus un novērstu līdzīgu gadījumu atkārtošanos. Analīze palīdz uzlabot drošības pasākumus un darba procedūras.</p><p><br></p><h3>Papildu padomi</h3><ul><li><strong>Izmanto drošības rīkus:</strong> Drošības rīki, piemēram, riska novērtēšanas matricas un kontrolsaraksti, palīdz efektīvi vadīt riskus un novērst nelaimes gadījumus.</li><li><strong>Regulāra pārbaude:</strong> Regulāri ve&nbsp;</li></ul>',
                    'video_url' => null,
                    'video_type' => null,
                    'is_active' => rand(0, 1),
                    'sort_id' => ++$sort_id,
                    'category_id' => 5,
                    'gallery' => json_encode(null),
                ],
                [
                    'name' => 'Darba drošības likumdošana un atbilstība',
                    'description' => '<p>Darba drošības likumdošana un atbilstība ir būtiski aspekti, lai nodrošinātu, ka darba vide atbilst visiem normatīvajiem aktiem un standartiem. Zināšanas par darba drošības likumdošanu palīdz uzņēmumiem izvairīties no juridiskiem sodiem un veicina drošu darba vidi.</p><p><br></p><h3>Galvenie normatīvie akti</h3><p>1. <strong>Darba drošības un veselības aizsardzības likums:</strong> Šis likums nosaka vispārīgus drošības un veselības aizsardzības prasības, kas jāievēro visiem darba devējiem un darbiniekiem.<br> 2. <strong>Specifiskie normatīvie akti:</strong> Papildus vispārīgajam likumam ir specifiski normatīvie akti, kas attiecas uz dažādām nozarēm un darba veidiem, piemēram, būvniecību, rūpniecību un lauksaimniecību. Šie akti nosaka konkrētus drošības standartus un prasības.<br> 3. <strong>Eiropas Savienības direktīvas:</strong> Latvijā piemēro arī Eiropas Savienības darba drošības direktīvas, kas nosaka minimālos drošības standartus un prasības, kurām jāatbilst visiem dalībvalstu uzņēmumiem.</p><p><br></p><h3>Atbilstības nodrošināšana</h3><p>1. <strong>Regulāras pārbaudes un auditi:</strong> Veic regulāras darba vietas pārbaudes un drošības auditus, lai pārliecinātos, ka tiek ievērotas visas drošības prasības un standarti. Auditi palīdz identificēt neatbilstības un veikt nepieciešamos uzlabojumus.<br> 2. <strong>Dokumentācija:</strong> Nodrošini detalizētu dokumentāciju, kas ietver visus drošības pasākumus, apmācību programmas un risku novērtējumus. Dokumentācija ir būtiska gan iekšējai kontrolei, gan ārējām pārbaudēm.<br> 3. <strong>Darbinieku informēšana:</strong> Regulāri informē darbiniekus par aktuālajām drošības prasībām un normatīvajiem aktiem. Informēšana ietver gan apmācības, gan informatīvus materiālus, kas palīdz darbiniekiem izprast drošības prasības.</p><p><br></p><h3>Papildu padomi</h3><ul><li><strong>Izmanto juridisko konsultāciju pakalpojumus:</strong> Juridiskie konsultanti var palīdzēt interpretēt normatīvos aktus un nodrošināt, ka uzņēmums atbilst visām drošības prasībām.</li><li><strong>Sadarbība ar valsts institūcijām:</strong> Sadarbošanās ar valsts darba inspekciju un citām atbildīgajām iestādēm palīdz nodrošināt, ka uzņēmums atbilst visām drošības prasībām un standartiem.</li></ul>',
                    'video_url' => null,
                    'video_type' => null,
                    'is_active' => rand(0, 1),
                    'sort_id' => ++$sort_id,
                    'category_id' => 5,
                    'gallery' => json_encode(null),
                ],
                [
                    'name' => 'Paroles un autentifikācija: Drošība internetā',
                    'description' => '<p>Mūsdienu digitālajā pasaulē paroles un autentifikācija ir viens no galvenajiem veidiem, kā nodrošināt savu tiešsaistes kontu drošību. Diemžēl, ja paroles ir vājas vai tiek izmantotas neatbilstoši, tas var radīt nopietnus drošības riskus. Tāpēc ir svarīgi izprast, kā izveidot drošas paroles un kā izmantot divpakāpju autentifikāciju, lai maksimāli pasargātu savus kontus.</p><p><br></p><p><br></p><h2><strong>Kā izveidot drošas paroles?</strong></h2><p><br></p><p>Droša parole ir pirmā aizsardzības līnija pret nesankcionētu piekļuvi jūsu kontiem. Tomēr daudz cilvēku joprojām izmanto vājas paroles, piemēram, "123456" vai "password", kas ir viegli uzminamas. Šeit ir daži padomi, kā izveidot drošu paroli:</p><ol><li><strong>Izmantojiet garas paroles</strong>: Parolei jābūt vismaz 12-16 rakstzīmes garai. Jo garāka parole, jo grūtāk to ir uzminēt vai uzlauzt.</li><li><strong>Iekļaujiet dažādus rakstzīmju tipus</strong>: Lai palielinātu paroles sarežģītību, tajā jāiekļauj lielie un mazie burti, cipari un speciālās rakstzīmes (piemēram, @, #, $, %).</li><li><strong>Izvairieties no viegli uzminamiem vārdiem</strong>: Neizmantojiet viegli uzminamus vārdus, piemēram, savu vārdu, dzimšanas datumu vai vārdus, kas saistīti ar jūsu dzīvi. Tāpat izvairieties no vispārzināmiem frāzēm vai parolēm, kas ir bieži sastopamas.</li><li><strong>Izmantojiet unikālas paroles katram kontam</strong>: Neizmantojiet vienu un to pašu paroli vairākos kontos. Ja viena parole tiek uzlauzta, uzbrucējs var piekļūt visiem jūsu kontiem. Izmantojiet paroles pārvaldnieku, lai palīdzētu glabāt un ģenerēt unikālas paroles.</li><li><strong>Regulāri mainiet paroles</strong>: Lai samazinātu risku, regulāri nomainiet savas paroles, it īpaši, ja ir aizdomas, ka tās varētu būt apdraudētas.</li></ol><p><br></p><p><br></p><h2><strong>Divpakāpju autentifikācijas izmantošana</strong></h2><p><br></p><p>Papildus drošai parolei ir ļoti ieteicams izmantot divpakāpju autentifikāciju (2FA), kas nodrošina papildu drošības slāni. Divpakāpju autentifikācija pieprasa lietotājam ievadīt ne tikai paroli, bet arī citu autentifikācijas elementu, kas parasti tiek nosūtīts uz lietotāja mobilo ierīci vai e-pastu.</p><p><br></p><p><br></p><h3><strong>Kā tas darbojas?</strong></h3><p><br></p><ol><li><strong>Pieslēgšanās ar paroli</strong>: Pirmkārt, lietotājs ievada savu paroli, kā parasti. Ja parole ir pareiza, sistēma pieprasa otro autentifikācijas posmu.</li><li><strong>Papildu verifikācija</strong>: Lietotājs saņem vienreizējo kodu uz savu mobilo tālruni, e-pastu vai izmanto autentifikācijas lietotni (piemēram, Google Authenticator). Šis kods ir derīgs tikai īsu laiku, kas vēl vairāk samazina iespēju to uzminēt vai nozagt.</li><li><strong>Piekļuve kontam</strong>: Tikai pēc tam, kad lietotājs ievada šo kodu, viņš iegūst piekļuvi savam kontam.</li></ol><p><br></p><p><br></p><h3><strong>Kāpēc divpakāpju autentifikācija ir svarīga?</strong></h3><p><br></p><p>Divpakāpju autentifikācija būtiski palielina drošību, jo pat ja uzbrucējs iegūst jūsu paroli, viņš nevarēs piekļūt jūsu kontam bez otra autentifikācijas soļa. Tas padara kiberuzbrukumus daudz sarežģītākus un mazāk izdevīgus uzbrucējiem.</p><p><br></p><p><br></p><h3><strong>Noslēgumā</strong></h3><p><br></p><p>Drošu paroļu izveidošana un divpakāpju autentifikācijas izmantošana ir būtiski soļi, lai aizsargātu savus tiešsaistes kontus no uzbrukumiem. Ievērojot šos drošības pasākumus, jūs būtiski samazināsiet risku tikt apdraudētam un varēsiet izmantot internetu ar lielāku drošību un pārliecību.</p>',
                    'video_url' => 'https://www.youtube.com/watch?v=qZE45J-MIUg&ab_channel=Wolfgang%27sChannel',
                    'video_type' => 'video/youtube',
                    'is_active' => 1,
                    'sort_id' => ++$sort_id,
                    'category_id' => 6,
                    'gallery' => json_encode($images[3]['image_path']),
                ],
                [
                    'name' => 'Privātums',
                    'description' => '<h3><strong>Kā aizsargāt savus datus sociālajos tīklos</strong></h3><p>Sociālie tīkli ir lielisks veids, kā sazināties ar draugiem un ģimeni, taču tie var arī radīt riskus jūsu privātumam. Lai pasargātu savus datus, ievērojiet šādus padomus:</p><p><br></p><ol><li><strong>Iestatiet privātuma opcijas</strong>: Pārbaudiet un pielāgojiet savas konta privātuma opcijas, lai kontrolētu, kurš var redzēt jūsu publikācijas un personisko informāciju.</li><li><strong>Neizpaudiet pārāk daudz informācijas</strong>: Ierobežojiet informāciju, kuru publicējat, piemēram, savu adresi, tālruņa numuru un citus sensitīvus datus.</li><li><strong>Esiet uzmanīgi ar draugu pieprasījumiem</strong>: Pieņemiet tikai tos draugu pieprasījumus, kurus pazīstat, lai izvairītos no potenciāli bīstamiem kontaktiem.</li><li><strong>Pārskatiet savus vecos ierakstus</strong>: Regulāri pārskatiet un dzēsiet vecos ierakstus, kas var saturēt sensitīvu informāciju.</li></ol><p><br></p><ol><li>test</li><li>test2</li></ol><p><br></p><h3><strong>Ko darīt, lai pasargātu savu personisko informāciju tiešsaistē</strong></h3><p>Internets ir pilns ar iespējām, taču arī ar riskiem jūsu privātumam. Šeit ir daži soļi, kā pasargāt savu personisko informāciju tiešsaistē:</p><p><br></p><ul><li><strong>Izmantojiet drošus savienojumus</strong>: Pārliecinieties, ka izmantojat tikai drošus (HTTPS) savienojumus, īpaši ievadot personisku informāciju.</li><li><strong>Būt uzmanīgiem ar publiskiem Wi-Fi</strong>: Izvairieties no personiskās informācijas ievadīšanas, izmantojot publiskus Wi-Fi tīklus, vai izmantojiet VPN, lai nodrošinātu savienojumu.</li><li><strong>Neklikšķiniet uz aizdomīgiem saitēm</strong>: Izvairieties no nezināmu e-pastu vai ziņojumu saitēm, kas var novest pie pikšķerēšanas uzbrukumiem.</li><li><strong>Regulāri atjauniniet programmatūru</strong>: Pārliecinieties, ka jūsu ierīces un programmatūra ir atjaunināta ar jaunākajiem drošības ielāpiem.</li></ul>',
                    'video_url' => null,
                    'video_type' => null,
                    'is_active' => 1,
                    'sort_id' => ++$sort_id,
                    'category_id' => 6,
                    'gallery' => json_encode(null),
                ],
                [
                    'name' => 'Kiberdrošības draudi',
                    'description' => '<h3><strong>Ļaunatūra (malware) un kā no tās izvairīties</strong></h3><p>Ļaunatūra jeb malware ir kaitīga programmatūra, kas var bojāt vai nozagt jūsu datus, kā arī traucēt ierīces darbību. Lai no tās izvairītos, ievērojiet šādus padomus:</p><p><br></p><ul><li><strong>Instalējiet antivīrusu programmatūru</strong>: Izmantojiet uzticamu antivīrusu programmu, kas regulāri pārbauda jūsu ierīci un atklāj ļaunatūru.</li><li><strong>Esiet uzmanīgi ar lejupielādēm</strong>: Lejupielādējiet programmas tikai no oficiāliem avotiem un izvairieties no apšaubāmiem failiem vai pielikumiem e-pastos.</li><li><strong>Regulāri atjauniniet programmatūru</strong>: Programmatūras atjauninājumi bieži ietver drošības ielāpus, kas pasargā no jaunākajiem draudiem.</li><li><strong>Izvairieties no aizdomīgām saitēm</strong>: Neklikšķiniet uz nezināmām saitēm vai reklāmām, kas var novirzīt uz inficētām vietnēm.</li></ul><p><br></p><p><br></p><h3><strong>Pikšķerēšana (phishing) un kā to atpazīt</strong></h3><p>Pikšķerēšana ir kiberuzbrukuma veids, kurā krāpnieki mēģina iegūt jūsu personisko informāciju, uzdodoties par uzticamām organizācijām vai personām. Lai to atpazītu un no tā izvairītos, ievērojiet šādus ieteikumus:</p><p><br></p><ul><li><strong>Pārbaudiet e-pasta sūtītāju</strong>: Vienmēr pārbaudiet e-pasta adresi, īpaši, ja saņemat negaidītu ziņu, kas pieprasa personisko informāciju.</li><li><strong>Neklikšķiniet uz aizdomīgiem saitēm</strong>: Esiet uzmanīgi ar saitēm e-pastos vai ziņojumos, kas izskatās aizdomīgi, un labāk ievadiet tīmekļa vietnes adresi manuāli.</li><li><strong>Meklējiet kļūdas tekstā</strong>: Pikšķerēšanas e-pastos bieži ir kļūdas gramatikā vai zīmējumos, kas var liecināt par krāpšanu.</li><li><strong>Nepievienojiet personisko informāciju e-pastos</strong>: Uzticamas organizācijas nekad nelūgs nosūtīt konfidenciālu informāciju pa e-pastu.</li></ul>',
                    'video_url' => 'https://www.youtube.com/watch?v=inWWhr5tnEA&t=71s&ab_channel=Simplilearn',
                    'video_type' => 'video/youtube',
                    'is_active' => 1,
                    'sort_id' => ++$sort_id,
                    'category_id' => 6,
                    'gallery' => json_encode(null),
                ],
            ];

            DB::table('learning_resources')->insert($resources);
        } else {
            // Handle the case where the table does not exist
            $this->command->info('Table "learning_resources" does not exist.');
        }
    }
}
