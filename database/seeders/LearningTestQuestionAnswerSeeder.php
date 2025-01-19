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

            // Question 19
            [
                'question_id' => 13,
                'order_id' => 1,
                'answer' => 'Mērķu definēšana, resursu plānošana, laika grafiki, budžeta noteikšana',
                'is_correct' => 1
            ],
            [
                'question_id' => 13,
                'order_id' => 2,
                'answer' => 'Vienkārši plāni, brīvprātīgo darbu pieņemšana, tikai ģimenes dalībnieki',
                'is_correct' => 0
            ],
            [
                'question_id' => 13,
                'order_id' => 3,
                'answer' => 'Veikt sākotnējus aprēķinus, izvēlēties materiālus, pieņemt darbiniekus bez atlases',
                'is_correct' => 0
            ],
            [
                'question_id' => 13,
                'order_id' => 4,
                'answer' => 'Gaidīt uz ideālajiem apstākļiem, veikt tikai zemākās cenas izvēli',
                'is_correct' => 0
            ],

            // Question 20
            [
                'question_id' => 14,
                'order_id' => 1,
                'answer' => 'Regulāri organizēt sapulces, izmantot efektīvas komunikācijas rīkus, nodrošināt atklātu informācijas apmaiņu',
                'is_correct' => 1
            ],

            // Question 21
            [
                'question_id' => 15,
                'order_id' => 1,
                'answer' => 'Izvēloties tikai importētus materiālus, ignorējot vietējos apstākļus, koncentrējoties uz estētiku, nevis funkcionalitāti',
                'is_correct' => 0
            ],
            [
                'question_id' => 15,
                'order_id' => 2,
                'answer' => 'Izmantojot lētākos pieejamos materiālus, koncentrējoties tikai uz īstermiņa rezultātiem',
                'is_correct' => 0
            ],
            [
                'question_id' => 15,
                'order_id' => 3,
                'answer' => 'Izmantojot vietējos materiālus, ūdens resursu efektīvu izmantošanu, bioloģisko daudzveidību veicinošas prakses',
                'is_correct' => 1
            ],
            [
                'question_id' => 15,
                'order_id' => 4,
                'answer' => 'Izmantojot lielus daudzumus ķīmisko vielu, lai uzlabotu augšanu',
                'is_correct' => 0
            ],

            // Question 22
            [
                'question_id' => 16,
                'order_id' => 1,
                'answer' => 'Identificēt potenciālos riskus, izstrādāt riska mazināšanas plānus, regulāri pārskatīt un atjaunināt riska plānus',
                'is_correct' => 1
            ],

            // Question 23
            [
                'question_id' => 17,
                'order_id' => 1,
                'answer' => 'Izstrādāt detalizētu budžeta plānu, regulāri uzraudzīt izdevumus un ienākumus, pielāgot budžetu atbilstoši izmaiņām projektā',
                'is_correct' => 1
            ],

            // Question 24
            [
                'question_id' => 18,
                'order_id' => 1,
                'answer' => 'Izveidot kvalitātes kontroles plānus, veikt regulāras pārbaudes un auditus, ievērot noteiktos kvalitātes standartus',
                'is_correct' => 1
            ],

            // Question 25
            [
                'question_id' => 19,
                'order_id' => 1,
                'answer' => 'Aizsargķiveres, drošības jostas, aizsargbrilles, darba cimdi, darba apavi',
                'is_correct' => 1
            ],
            [
                'question_id' => 19,
                'order_id' => 2,
                'answer' => 'Brilles, kas nav piemērotas darbam, personīgās mantas, standarta apavi',
                'is_correct' => 0
            ],
            [
                'question_id' => 19,
                'order_id' => 3,
                'answer' => 'Zābaki, kas nav pretslīdes, darbinieku individuālās izvēles priekšmeti',
                'is_correct' => 0
            ],
            [
                'question_id' => 19,
                'order_id' => 4,
                'answer' => 'Vienkārši apģērbi, bez speciāla aprīkojuma',
                'is_correct' => 0
            ],

            // Question 26
            [
                'question_id' => 20,
                'order_id' => 1,
                'answer' => 'Uzglabāt atsevišķi, labi ventilētās telpās, izmantojot speciālus konteinerus un norādes zīmes',
                'is_correct' => 1
            ],

            // Question 27
            [
                'question_id' => 21,
                'order_id' => 1,
                'answer' => 'Tehnikas cena, krāsa, brīvo vietu daudzums',
                'is_correct' => 0
            ],
            [
                'question_id' => 21,
                'order_id' => 2,
                'answer' => 'Vienkāršība, augsta cena, tehniskie parametri',
                'is_correct' => 0
            ],
            [
                'question_id' => 21,
                'order_id' => 3,
                'answer' => 'Tehnikas stāvoklis, darba veids, nepieciešamā jauda, drošības prasības',
                'is_correct' => 1
            ],
            [
                'question_id' => 21,
                'order_id' => 4,
                'answer' => 'Tehnikas izskats, zīmols, iepriekšējās īpašnieku atsauksmes',
                'is_correct' => 0
            ],

            // Question 28
            [
                'question_id' => 22,
                'order_id' => 1,
                'answer' => 'Veikt eļļošanu, pārbaudīt un nomainīt filtrus, regulāri pārbaudīt tehnikas stāvokli',
                'is_correct' => 1
            ],

            // Question 29
            [
                'question_id' => 23,
                'order_id' => 1,
                'answer' => 'Identificēt potenciālos riskus un pieņemt pasākumus to novēršanai, lai nodrošinātu darbinieku drošību un novērstu negadījumus',
                'is_correct' => 1
            ],

            // Question 30
            [
                'question_id' => 24,
                'order_id' => 1,
                'answer' => 'Aizsargķiveres, darba cimdi, aizsargbrilles, drošības jostas, darba apavi',
                'is_correct' => 1
            ],
            [
                'question_id' => 24,
                'order_id' => 2,
                'answer' => 'Parasti apģērbi, bez speciālas aizsardzības',
                'is_correct' => 0
            ],
            [
                'question_id' => 24,
                'order_id' => 3,
                'answer' => 'Parastie zābaki, kas nav izturīgi pret triecieniem',
                'is_correct' => 0
            ],
            [
                'question_id' => 24,
                'order_id' => 4,
                'answer' => 'Nekvalitatīvi aizsardzības līdzekļi, kas neatbilst standartiem',
                'is_correct' => 0
            ],

            // Question 31
            [
                'question_id' => 25,
                'order_id' => 1,
                'answer' => 'Garums',
                'is_correct' => 0
            ],
            [
                'question_id' => 25,
                'order_id' => 2,
                'answer' => 'Viegli atcerēties',
                'is_correct' => 0
            ],
            [
                'question_id' => 25,
                'order_id' => 3,
                'answer' => 'Dažādu rakstzīmju lietošana',
                'is_correct' => 1
            ],
            [
                'question_id' => 25,
                'order_id' => 4,
                'answer' => 'Vienkāršs vārds',
                'is_correct' => 0
            ],

            // Question 32
            [
                'question_id' => 26,
                'order_id' => 1,
                'answer' => 'krāpšana',
                'is_correct' => 1
            ],

            // Question 33
            [
                'question_id' => 27,
                'order_id' => 1,
                'answer' => 'Lai uzlabotu ātrumu',
                'is_correct' => 0
            ],
            [
                'question_id' => 27,
                'order_id' => 2,
                'answer' => 'Lai iegūtu jaunas funkcijas',
                'is_correct' => 0
            ],
            [
                'question_id' => 27,
                'order_id' => 3,
                'answer' => 'Lai novērstu drošības riskus',
                'is_correct' => 1
            ],
            [
                'question_id' => 27,
                'order_id' => 4,
                'answer' => 'Lai atbrīvotu vietu diskā',
                'is_correct' => 0
            ],

            // Question 34
            [
                'question_id' => 28,
                'order_id' => 1,
                'answer' => 'Jā',
                'is_correct' => 1
            ],

            // Question 35
            [
                'question_id' => 29,
                'order_id' => 1,
                'answer' => 'Kredītkartes numuru',
                'is_correct' => 1
            ],
            [
                'question_id' => 29,
                'order_id' => 2,
                'answer' => 'Jūsu mājas adresi',
                'is_correct' => 0
            ],
            [
                'question_id' => 29,
                'order_id' => 3,
                'answer' => 'Mīļākās filmas',
                'is_correct' => 0
            ],
            [
                'question_id' => 29,
                'order_id' => 4,
                'answer' => 'Ceļojumu fotogrāfijas',
                'is_correct' => 0
            ],

            // Question 36
            [
                'question_id' => 30,
                'order_id' => 1,
                'answer' => 'Savienojums ar HTTP',
                'is_correct' => 0
            ],
            [
                'question_id' => 30,
                'order_id' => 2,
                'answer' => 'Savienojums ar HTTPS',
                'is_correct' => 1
            ],
            [
                'question_id' => 30,
                'order_id' => 3,
                'answer' => 'Publiskais Wi-Fi',
                'is_correct' => 0
            ],
            [
                'question_id' => 30,
                'order_id' => 4,
                'answer' => 'Savienojums bez paroles',
                'is_correct' => 0
            ],

            // Question 37
            [
                'question_id' => 31,
                'order_id' => 1,
                'answer' => 'Dzēst e-pastu',
                'is_correct' => 0
            ],
            [
                'question_id' => 31,
                'order_id' => 2,
                'answer' => 'Atbildēt uz e-pastu',
                'is_correct' => 0
            ],
            [
                'question_id' => 31,
                'order_id' => 3,
                'answer' => 'Ziņot par to IT atbalstam',
                'is_correct' => 0
            ],
            [
                'question_id' => 31,
                'order_id' => 4,
                'answer' => 'Noklikšķināt uz saitēm',
                'is_correct' => 1
            ],
        ];

        DB::table('learning_test_question_answers')->insert($answers);
    }
}
