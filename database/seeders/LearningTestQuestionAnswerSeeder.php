<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LearningTestQuestionAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('learning_test_question_answers')) {
            $answers = [
                // Question 1
                [
                    'question_id' => 1,
                    'order_id' => 1,
                    'answer' => 'Augšanas ātrums',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 1,
                    'order_id' => 2,
                    'answer' => 'Ziedēšanas ilgums',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 1,
                    'order_id' => 3,
                    'answer' => 'Augsnes pH līmenis',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 1,
                    'order_id' => 4,
                    'answer' => 'Augu forma',
                    'is_correct' => 0
                ],

                // Question 2
                [
                    'question_id' => 2,
                    'order_id' => 1,
                    'answer' => 'Izvēlieties augus pēc to lapu krāsas',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 2,
                    'order_id' => 2,
                    'answer' => 'Izvēlieties augus, kas zied sezonas laikā',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 2,
                    'order_id' => 3,
                    'answer' => 'Izvēlieties augus pēc to augšanas ātruma',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 2,
                    'order_id' => 4,
                    'answer' => 'Izvēlieties augus pēc to kātiem',
                    'is_correct' => 0
                ],

                // Question 3
                [
                    'question_id' => 3,
                    'order_id' => 1,
                    'answer' => 'Rudenī',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 3,
                    'order_id' => 2,
                    'answer' => 'Pavasarī',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 3,
                    'order_id' => 3,
                    'answer' => 'Vasarā',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 3,
                    'order_id' => 4,
                    'answer' => 'Ziemā',
                    'is_correct' => 0
                ],

                // Question 4
                [
                    'question_id' => 4,
                    'order_id' => 1,
                    'answer' => 'Pavasarī',
                    'is_correct' => 1
                ],

                // Question 5
                [
                    'question_id' => 5,
                    'order_id' => 1,
                    'answer' => 'Nepietiekama laistīšana',
                    'is_correct' => 1
                ],
                // Question 6
                [
                    'question_id' => 6,
                    'order_id' => 1,
                    'answer' => 'Dzeltenīgas lapas',
                    'is_correct' => 1
                ],

                // Question 7
                [
                    'question_id' => 7,
                    'order_id' => 1,
                    'answer' => 'Augsnes kvalitāte',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 7,
                    'order_id' => 2,
                    'answer' => 'Augu krāsa',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 7,
                    'order_id' => 3,
                    'answer' => 'Ziedēšanas laiks',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 7,
                    'order_id' => 4,
                    'answer' => 'Dārza mēbeļu stils',
                    'is_correct' => 0
                ],

                // Question 8
                [
                    'question_id' => 8,
                    'order_id' => 1,
                    'answer' => 'Izvietojiet apgaismojumu zem augu lapām',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 8,
                    'order_id' => 2,
                    'answer' => 'Izvietojiet apgaismojumu virs dārza elementiem',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 8,
                    'order_id' => 3,
                    'answer' => 'Izvietojiet apgaismojumu pie dārza ceļiem',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 8,
                    'order_id' => 4,
                    'answer' => 'Izvietojiet apgaismojumu auga pamatnē',
                    'is_correct' => 0
                ],

                // Question 9
                [
                    'question_id' => 9,
                    'order_id' => 1,
                    'answer' => 'Dīķi',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 9,
                    'order_id' => 2,
                    'answer' => 'Dārza soli',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 9,
                    'order_id' => 3,
                    'answer' => 'Skrūves',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 9,
                    'order_id' => 4,
                    'answer' => 'Aizsargtīkli',
                    'is_correct' => 0
                ],

                // Question 10
                [
                    'question_id' => 10,
                    'order_id' => 1,
                    'answer' => 'Izveidot dabīgus ūdens ceļus un akas',
                    'is_correct' => 1
                ],

                // Question 11
                [
                    'question_id' => 11,
                    'order_id' => 1,
                    'answer' => 'Izvēlieties apgaismojumu, kas rada mīkstu un izkliedētu gaismu',
                    'is_correct' => 1
                ],

                // Question 12
                [
                    'question_id' => 12,
                    'order_id' => 1,
                    'answer' => 'Funkcionālā plānošana, kas ņem vērā izmantošanas mērķus un praktiskumu',
                    'is_correct' => 1
                ],

                // Question 13
                [
                    'question_id' => 13,
                    'order_id' => 1,
                    'answer' => 'Saknes, koka dēļi, metāla plāksnes, plāksnes ar mozaīku',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 13,
                    'order_id' => 2,
                    'answer' => 'Rokas instrumenti, elektriskie instrumenti, būvgružu konteineri, cementa maisītāji',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 13,
                    'order_id' => 3,
                    'answer' => 'Gaismas lampas, apmales, betona bloki, koka paletes',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 13,
                    'order_id' => 4,
                    'answer' => 'Betona bruģis, akmens bruģis, keramiskais bruģis, granīta bruģis',
                    'is_correct' => 1
                ],

                // Question 14
                [
                    'question_id' => 14,
                    'order_id' => 1,
                    'answer' => 'Sagatavot pamatus, iekļaujot teritorijas izraku, apmales uzstādīšanu un pamatnes izlīdzināšanu',
                    'is_correct' => 1
                ],

                // Question 15
                [
                    'question_id' => 15,
                    'order_id' => 1,
                    'answer' => 'Koka plāksnes, metāla stieples, plastmasas plēves, ķieģeļi',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 15,
                    'order_id' => 2,
                    'answer' => 'Smilts, šķembas, grants, betona bloki',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 15,
                    'order_id' => 3,
                    'answer' => 'Dārza zāles, augsnes komposti, dekoratīvi akmeņi, sūnas',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 15,
                    'order_id' => 4,
                    'answer' => 'Augu substrāti, augsnes uzlabotāji, minerālmēsli, cements',
                    'is_correct' => 0
                ],

                // Question 16
                [
                    'question_id' => 16,
                    'order_id' => 1,
                    'answer' => 'Slikta ūdens novadīšana, mehāniska slodze vai nepareiza pamatu sagatavošana',
                    'is_correct' => 1
                ],

                // Question 17
                [
                    'question_id' => 17,
                    'order_id' => 1,
                    'answer' => 'Izmantojot augstspiediena mazgātāju un tīrīšanas līdzekļus, lai noņemtu netīrumus un sūnas',
                    'is_correct' => 1
                ],

                // Question 18
                [
                    'question_id' => 18,
                    'order_id' => 1,
                    'answer' => 'Veikt virsmas kompakciju, aizpildīt spraugas ar šuvju materiālu un veikt galīgo tīrīšanu',
                    'is_correct' => 1
                ],

                // Question 19
                [
                    'question_id' => 19,
                    'order_id' => 1,
                    'answer' => 'Mērķu definēšana, resursu plānošana, laika grafiki, budžeta noteikšana',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 19,
                    'order_id' => 2,
                    'answer' => 'Vienkārši plāni, brīvprātīgo darbu pieņemšana, tikai ģimenes dalībnieki',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 19,
                    'order_id' => 3,
                    'answer' => 'Veikt sākotnējus aprēķinus, izvēlēties materiālus, pieņemt darbiniekus bez atlases',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 19,
                    'order_id' => 4,
                    'answer' => 'Gaidīt uz ideālajiem apstākļiem, veikt tikai zemākās cenas izvēli',
                    'is_correct' => 0
                ],

                // Question 20
                [
                    'question_id' => 20,
                    'order_id' => 1,
                    'answer' => 'Regulāri organizēt sapulces, izmantot efektīvas komunikācijas rīkus, nodrošināt atklātu informācijas apmaiņu',
                    'is_correct' => 1
                ],

                // Question 21
                [
                    'question_id' => 21,
                    'order_id' => 1,
                    'answer' => 'Izvēloties tikai importētus materiālus, ignorējot vietējos apstākļus, koncentrējoties uz estētiku, nevis funkcionalitāti',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 21,
                    'order_id' => 2,
                    'answer' => 'Izmantojot lētākos pieejamos materiālus, koncentrējoties tikai uz īstermiņa rezultātiem',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 21,
                    'order_id' => 3,
                    'answer' => 'Izmantojot vietējos materiālus, ūdens resursu efektīvu izmantošanu, bioloģisko daudzveidību veicinošas prakses',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 21,
                    'order_id' => 4,
                    'answer' => 'Izmantojot lielus daudzumus ķīmisko vielu, lai uzlabotu augšanu',
                    'is_correct' => 0
                ],

                // Question 22
                [
                    'question_id' => 22,
                    'order_id' => 1,
                    'answer' => 'Identificēt potenciālos riskus, izstrādāt riska mazināšanas plānus, regulāri pārskatīt un atjaunināt riska plānus',
                    'is_correct' => 1
                ],

                // Question 23
                [
                    'question_id' => 23,
                    'order_id' => 1,
                    'answer' => 'Izstrādāt detalizētu budžeta plānu, regulāri uzraudzīt izdevumus un ienākumus, pielāgot budžetu atbilstoši izmaiņām projektā',
                    'is_correct' => 1
                ],

                // Question 24
                [
                    'question_id' => 24,
                    'order_id' => 1,
                    'answer' => 'Izveidot kvalitātes kontroles plānus, veikt regulāras pārbaudes un auditus, ievērot noteiktos kvalitātes standartus',
                    'is_correct' => 1
                ],

                // Question 25
                [
                    'question_id' => 25,
                    'order_id' => 1,
                    'answer' => 'Aizsargķiveres, drošības jostas, aizsargbrilles, darba cimdi, darba apavi',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 25,
                    'order_id' => 2,
                    'answer' => 'Brilles, kas nav piemērotas darbam, personīgās mantas, standarta apavi',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 25,
                    'order_id' => 3,
                    'answer' => 'Zābaki, kas nav pretslīdes, darbinieku individuālās izvēles priekšmeti',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 25,
                    'order_id' => 4,
                    'answer' => 'Vienkārši apģērbi, bez speciāla aprīkojuma',
                    'is_correct' => 0
                ],

                // Question 26
                [
                    'question_id' => 26,
                    'order_id' => 1,
                    'answer' => 'Uzglabāt atsevišķi, labi ventilētās telpās, izmantojot speciālus konteinerus un norādes zīmes',
                    'is_correct' => 1
                ],

                // Question 27
                [
                    'question_id' => 27,
                    'order_id' => 1,
                    'answer' => 'Tehnikas cena, krāsa, brīvo vietu daudzums',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 27,
                    'order_id' => 2,
                    'answer' => 'Vienkāršība, augsta cena, tehniskie parametri',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 27,
                    'order_id' => 3,
                    'answer' => 'Tehnikas stāvoklis, darba veids, nepieciešamā jauda, drošības prasības',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 27,
                    'order_id' => 4,
                    'answer' => 'Tehnikas izskats, zīmols, iepriekšējās īpašnieku atsauksmes',
                    'is_correct' => 0
                ],

                // Question 28
                [
                    'question_id' => 28,
                    'order_id' => 1,
                    'answer' => 'Veikt eļļošanu, pārbaudīt un nomainīt filtrus, regulāri pārbaudīt tehnikas stāvokli',
                    'is_correct' => 1
                ],

                // Question 29
                [
                    'question_id' => 29,
                    'order_id' => 1,
                    'answer' => 'Identificēt potenciālos riskus un pieņemt pasākumus to novēršanai, lai nodrošinātu darbinieku drošību un novērstu negadījumus',
                    'is_correct' => 1
                ],

                // Question 30
                [
                    'question_id' => 30,
                    'order_id' => 1,
                    'answer' => 'Aizsargķiveres, darba cimdi, aizsargbrilles, drošības jostas, darba apavi',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 30,
                    'order_id' => 2,
                    'answer' => 'Parasti apģērbi, bez speciālas aizsardzības',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 30,
                    'order_id' => 3,
                    'answer' => 'Parastie zābaki, kas nav izturīgi pret triecieniem',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 30,
                    'order_id' => 4,
                    'answer' => 'Nekvalitatīvi aizsardzības līdzekļi, kas neatbilst standartiem',
                    'is_correct' => 0
                ],

                // Question 31
                [
                    'question_id' => 31,
                    'order_id' => 1,
                    'answer' => 'Garums',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 31,
                    'order_id' => 2,
                    'answer' => 'Viegli atcerēties',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 31,
                    'order_id' => 3,
                    'answer' => 'Dažādu rakstzīmju lietošana',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 31,
                    'order_id' => 4,
                    'answer' => 'Vienkāršs vārds',
                    'is_correct' => 0
                ],

                // Question 32
                [
                    'question_id' => 32,
                    'order_id' => 1,
                    'answer' => 'krāpšana',
                    'is_correct' => 1
                ],

                // Question 33
                [
                    'question_id' => 33,
                    'order_id' => 1,
                    'answer' => 'Lai uzlabotu ātrumu',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 33,
                    'order_id' => 2,
                    'answer' => 'Lai iegūtu jaunas funkcijas',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 33,
                    'order_id' => 3,
                    'answer' => 'Lai novērstu drošības riskus',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 33,
                    'order_id' => 4,
                    'answer' => 'Lai atbrīvotu vietu diskā',
                    'is_correct' => 0
                ],

                // Question 34
                [
                    'question_id' => 34,
                    'order_id' => 1,
                    'answer' => 'Jā',
                    'is_correct' => 1
                ],

                // Question 35
                [
                    'question_id' => 35,
                    'order_id' => 1,
                    'answer' => 'Kredītkartes numuru',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 35,
                    'order_id' => 2,
                    'answer' => 'Jūsu mājas adresi',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 35,
                    'order_id' => 3,
                    'answer' => 'Mīļākās filmas',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 35,
                    'order_id' => 4,
                    'answer' => 'Ceļojumu fotogrāfijas',
                    'is_correct' => 0
                ],

                // Question 36
                [
                    'question_id' => 36,
                    'order_id' => 1,
                    'answer' => 'Savienojums ar HTTP',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 36,
                    'order_id' => 2,
                    'answer' => 'Savienojums ar HTTPS',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 36,
                    'order_id' => 3,
                    'answer' => 'Publiskais Wi-Fi',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 36,
                    'order_id' => 4,
                    'answer' => 'Savienojums bez paroles',
                    'is_correct' => 0
                ],

                // Question 37
                [
                    'question_id' => 37,
                    'order_id' => 1,
                    'answer' => 'Dzēst e-pastu',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 37,
                    'order_id' => 2,
                    'answer' => 'Atbildēt uz e-pastu',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 37,
                    'order_id' => 3,
                    'answer' => 'Ziņot par to IT atbalstam',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 37,
                    'order_id' => 4,
                    'answer' => 'Noklikšķināt uz saitēm',
                    'is_correct' => 1
                ],

                // filament
                // Question 38
                [
                    'question_id' => 38,
                    'order_id' => 1,
                    'answer' => 'A tool for building modern front-ends for Laravel applications.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 38,
                    'order_id' => 2,
                    'answer' => 'A panel builder for Laravel applications.',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 38,
                    'order_id' => 3,
                    'answer' => 'A package for testing Laravel applications.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 38,
                    'order_id' => 4,
                    'answer' => 'A queue management tool in Laravel.',
                    'is_correct' => 0
                ],

                // Question 39
                [
                    'question_id' => 39,
                    'order_id' => 1,
                    'answer' => 'composer require filament/testing',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 39,
                    'order_id' => 2,
                    'answer' => 'composer require filament/filament',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 39,
                    'order_id' => 3,
                    'answer' => 'composer install filament',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 39,
                    'order_id' => 4,
                    'answer' => 'php artisan make:filament',
                    'is_correct' => 0
                ],

                // Question 40
                [
                    'question_id' => 40,
                    'order_id' => 1,
                    'answer' => 'php artisan make:widget-filament',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 40,
                    'order_id' => 2,
                    'answer' => 'php artisan filament-widget',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 40,
                    'order_id' => 3,
                    'answer' => 'php artisan make:widget',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 40,
                    'order_id' => 4,
                    'answer' => 'php artisan make:filament-widget',
                    'is_correct' => 1
                ],

                // Question 41
                [
                    'question_id' => 41,
                    'order_id' => 1,
                    'answer' => 'Add the `Actions` class to the Resource ListRecords page getActions() method.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 41,
                    'order_id' => 2,
                    'answer' => 'Add the `Actions` class to the Resource ListRecords page getHeaderButtons() method.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 41,
                    'order_id' => 3,
                    'answer' => 'Add the `Actions` class to the Resource ListRecords page getHeaderActions() method.',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 41,
                    'order_id' => 4,
                    'answer' => 'Add the `Actions` class to the Resource $table ->actions() method.',
                    'is_correct' => 0
                ],

                // Question 42
                [
                    'question_id' => 42,
                    'order_id' => 1,
                    'answer' => 'Create a view inside the resources/views directory.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 42,
                    'order_id' => 2,
                    'answer' => 'Run the command `php artisan make:filament-page`.',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 42,
                    'order_id' => 3,
                    'answer' => 'Create a view inside the resources/filament/views directory.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 42,
                    'order_id' => 4,
                    'answer' => 'Run the command `php artisan filament:make-page`.',
                    'is_correct' => 0
                ],

                // Question 43
                [
                    'question_id' => 43,
                    'order_id' => 1,
                    'answer' => 'Use the `UploadFile::make()` method provided by Filament.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 43,
                    'order_id' => 2,
                    'answer' => 'Use the `FileUpload::make()` method provided by Filament.',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 43,
                    'order_id' => 3,
                    'answer' => 'Use the `FileUpload::upload()` method provided by Filament.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 43,
                    'order_id' => 4,
                    'answer' => 'Use the `File::make()` method provided by Filament.',
                    'is_correct' => 0
                ],

                // Question 44
                [
                    'question_id' => 44,
                    'order_id' => 1,
                    'answer' => 'Add the --view at the end of the terminal command `php artisan make:filament-resource --view`',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 44,
                    'order_id' => 2,
                    'answer' => 'Run the command `php artisan create:view ResourceName`',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 44,
                    'order_id' => 3,
                    'answer' => 'Add the --view at the end of the terminal command `php artisan filament:make-resource --view`',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 44,
                    'order_id' => 4,
                    'answer' => 'Run the command `php artisan filament:create-view ResourceName`',
                    'is_correct' => 0
                ],

                // Question 45
                [
                    'question_id' => 45,
                    'order_id' => 1,
                    'answer' => 'TextArea',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 45,
                    'order_id' => 2,
                    'answer' => 'TextInput',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 45,
                    'order_id' => 3,
                    'answer' => 'RichInput',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 45,
                    'order_id' => 4,
                    'answer' => 'TextEditor',
                    'is_correct' => 0
                ],

                // change to q with image
                // Question 46
                [
                    'question_id' => 46,
                    'order_id' => 1,
                    'answer' => 'Add ->visible(false) method',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 46,
                    'order_id' => 2,
                    'answer' => 'Add ->hidden() method',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 46,
                    'order_id' => 3,
                    'answer' => 'Use Hidden::make() class',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 46,
                    'order_id' => 4,
                    'answer' => 'Use Invisible::make() class',
                    'is_correct' => 0
                ],

                // Question 47
                [
                    'question_id' => 47,
                    'order_id' => 1,
                    'answer' => 'Using ->extraAttributes method.',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 47,
                    'order_id' => 2,
                    'answer' => 'Using ->additionalAttributes method.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 47,
                    'order_id' => 3,
                    'answer' => 'Using ->setAttributes method.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 47,
                    'order_id' => 4,
                    'answer' => 'Using ->renderAttributes method.',
                    'is_correct' => 0
                ],

                // Question 48
                [
                    'question_id' => 48,
                    'order_id' => 1,
                    'answer' => 'Create a translation file in the lang folder and use `__()` in a route file.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 48,
                    'order_id' => 2,
                    'answer' => 'Use the `trans()` function in the routes file.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 48,
                    'order_id' => 3,
                    'answer' => 'Create a translation file in the lang folder and use `__()` in resource fields.',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 48,
                    'order_id' => 4,
                    'answer' => 'Declare translations in the FilamentServiceProvider.',
                    'is_correct' => 0
                ],

                // Question 49
                [
                    'question_id' => 49,
                    'order_id' => 1,
                    'answer' => 'It triggers a callback after the form is submitted.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 49,
                    'order_id' => 2,
                    'answer' => 'It executes a callback when the form is initially loaded.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 49,
                    'order_id' => 3,
                    'answer' => 'It applies a callback after the entire form state is validated.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 49,
                    'order_id' => 4,
                    'answer' => 'It runs a callback after a form field state is updated.',
                    'is_correct' => 1
                ],

                // Question 50
                [
                    'question_id' => 50,
                    'order_id' => 1,
                    'answer' => 'Define validation rules in a dedicated FormRequest class and reference it in the resource.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 50,
                    'order_id' => 42,
                    'answer' => 'Use the ->validate([]) method in the form field definition.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 50,
                    'order_id' => 3,
                    'answer' => 'Use the ->rules([]) method in the form schema.',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 50,
                    'order_id' => 4,
                    'answer' => 'Use the ->validationRules([]) method in the form schema.',
                    'is_correct' => 0
                ],

                // Question 51
                [
                    'question_id' => 51,
                    'order_id' => 1,
                    'answer' => 'The total possible points for the question.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 51,
                    'order_id' => 2,
                    'answer' => 'The points entered by the user, followed by the maximum possible points.',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 51,
                    'order_id' => 3,
                    'answer' => 'The percentage of points received based on the total points available.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 51,
                    'order_id' => 4,
                    'answer' => 'A dynamic label showing whether the entered points are valid or not.',
                    'is_correct' => 0
                ],

                // Question 52
                [
                    'question_id' => 52,
                    'order_id' => 1,
                    'answer' => 'Enums are a way to define a set of related constants in an object-oriented manner.',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 52,
                    'order_id' => 2,
                    'answer' => 'Enums are a type of database table used to store serialized data in Laravel.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 52,
                    'order_id' => 3,
                    'answer' => 'Enums allow for defining custom validation rules for form requests in Laravel.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 52,
                    'order_id' => 4,
                    'answer' => 'Enums are used to manage database migrations and version control.',
                    'is_correct' => 0
                ],


                // Question 53
                [
                    'question_id' => 53,
                    'order_id' => 1,
                    'answer' => 'Filter records directly in the model class.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 53,
                    'order_id' => 2,
                    'answer' => 'Use the ->filters method provided by Filament tables.',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 53,
                    'order_id' => 3,
                    'answer' => 'Add a scope in the table controller.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 53,
                    'order_id' => 4,
                    'answer' => 'Use the ->filter method provided by Filament resource.',
                    'is_correct' => 0
                ],

                // Question 54
                [
                    'question_id' => 54,
                    'order_id' => 1,
                    'answer' => 'Use the `live()` method on the independent field.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 54,
                    'order_id' => 2,
                    'answer' => 'Use the `afterStateChanged()` method on the dependent field.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 54,
                    'order_id' => 3,
                    'answer' => 'Apply a real-time `watch()` method within the form schema.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 54,
                    'order_id' => 4,
                    'answer' => 'Use the `afterStateUpdated()` method on the dependent field.',
                    'is_correct' => 1
                ],

                // change to q with image
                // Question 55
                [
                    'question_id' => 55,
                    'order_id' => 1,
                    'answer' => 'Password confirmation is required on an edit page',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 55,
                    'order_id' => 2,
                    'answer' => 'Password confirmation is shown on a create page',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 55,
                    'order_id' => 3,
                    'answer' => 'Password confirmation is shown on an edit page',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 55,
                    'order_id' => 4,
                    'answer' => 'Password confirmation is required on a create page',
                    'is_correct' => 1
                ],

                // Question 56
                [
                    'question_id' => 56,
                    'order_id' => 1,
                    'answer' => 'Override the colors in the Bootstrap CSS configuration file',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 56,
                    'order_id' => 2,
                    'answer' => 'Use the ->theme() method in the panel configuration to define new colors.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 56,
                    'order_id' => 3,
                    'answer' => 'Override the colors in the Tailwind CSS configuration file',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 56,
                    'order_id' => 4,
                    'answer' => 'Use the ->colors() method in the panel configuration to define new colors.',
                    'is_correct' => 1
                ],

                // Question 57
                [
                    'question_id' => 57,
                    'order_id' => 1,
                    'answer' => 'By defining custom routes in the `getPages` method, using custom page classes.',
                    'is_correct' => 1
                ],
                [
                    'question_id' => 57,
                    'order_id' => 2,
                    'answer' => 'By defining a custom controller method that returns the correct view and URL for the resource.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 57,
                    'order_id' => 3,
                    'answer' => 'By adding a custom route directly to the `web.php` routes file with manual route handling.',
                    'is_correct' => 0
                ],
                [
                    'question_id' => 57,
                    'order_id' => 4,
                    'answer' => 'By extending the resource class to override the default URL generation behavior.',
                    'is_correct' => 0
                ],
            ];

            DB::table('learning_test_question_answers')->insert($answers);
        } else {
            // Handle the case where the table does not exist
            $this->command->info('Table "learning_test_question_answers" does not exist.');
        }
    }
}
