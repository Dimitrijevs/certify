<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\School;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = School::all();

        $teamTypes = [
            'IT nodaļa' => 'Informācijas tehnoloģiju komanda sistēmu uzturēšanai un attīstībai.',
            'Vadības komanda' => 'Uzņēmuma stratēģiskās vadības un lēmumu pieņemšanas grupa.',
            'Ražošanas darbinieki' => 'Ražošanas procesa nodrošināšanas un izpildes komanda.',
            'Pārdošanas speciālisti' => 'Produktu un pakalpojumu pārdošanas un klientu apkalpošanas grupa.',
            'Mārketinga vienība' => 'Zīmola attīstības un tirgus veicināšanas speciālistu komanda.',
            'Finanšu grupa' => 'Budžeta plānošanas un finanšu pārvaldības eksperti.',
            'Loģistikas komanda' => 'Piegādes ķēdes un transporta koordinācijas darbinieki.',
            'Personāla nodaļa' => 'Darbinieku atlases un attīstības atbalsta komanda.',
            'Kvalitātes kontrole' => 'Produktu un pakalpojumu kvalitātes uzraudzības un testēšanas grupa.',
            'Pētniecības un attīstības nodaļa' => 'Jaunu produktu un inovāciju izstrādes speciālisti.',
            'Klientu atbalsta vienība' => 'Klientu jautājumu risināšanas un tehniskā atbalsta komanda.',
            'Juridiskā grupa' => 'Tiesisko jautājumu un līgumu pārvaldības eksperti.',
            'Drošības komanda' => 'Uzņēmuma fiziskās un digitālās drošības nodrošināšanas darbinieki.',
            'Projektu vadības birojs' => 'Projektu plānošanas un īstenošanas koordinācijas grupa.',
            'Saimniecības nodaļa' => 'Biroja un infrastruktūras uzturēšanas un apsaimniekošanas komanda.',
            'Sabiedrisko attiecību vienība' => 'Uzņēmuma tēla veidošanas un komunikācijas speciālisti.',
            'Vides pārvaldības grupa' => 'Vides aizsardzības prasību ievērošanas un ilgtspējīgas attīstības veicināšanas speciālisti.',
            'Apmācību un attīstības nodaļa' => 'Darbinieku prasmju pilnveidošanas un profesionālās izaugsmes programmu īstenošanas komanda.',
            'Produktu dizaina vienība' => 'Produktu vizuālā un funkcionālā dizaina izstrādes un pilnveidošanas speciālisti.',
            'Satura veidošanas grupa' => 'Mārketinga un komunikācijas materiālu izstrādes un radošā satura veidošanas profesionāļi.',
            'Datu analīzes komanda' => 'Biznesa datu apkopošanas, analīzes un lēmumu pieņemšanas atbalsta speciālisti.',
            'Korporatīvās sociālās atbildības nodaļa' => 'Sociālo projektu un sabiedrības atbalsta iniciatīvu īstenošanas komanda.',
            'Riska pārvaldības vienība' => 'Biznesa risku identificēšanas, novērtēšanas un mazināšanas stratēģiju izstrādes eksperti.',
            'Veselības un labklājības komanda' => 'Darbinieku labsajūtas un veselīgas darba vides nodrošināšanas speciālisti.',
            'Piegādes ķēdes pārvaldības grupa' => 'Materiālu un produktu iepirkumu un plūsmas optimizācijas eksperti.',
            'Biroja administrācijas nodaļa' => 'Ikdienas administratīvo funkciju nodrošināšanas un biroja darba koordinācijas komanda.',
        ];

        $groups = [];

        foreach ($companies as $company) {
            $usedTeams = [];

            for ($i = 0; $i < rand(4, 7); $i++) {

                $team = array_rand($teamTypes);

                if (in_array($team, $usedTeams)) {
                    continue;
                }

                $usedTeams[] = $team;

                $teamName = $team;
                $teamDescription = $teamTypes[$team];

                $groups[] = [
                    'name' => $teamName,
                    'description' => $teamDescription,
                    'school_id' => $company->id,
                    'created_at' => now()->subDays(rand(0, 180)),
                    'updated_at' => now()->subDays(rand(0, 90)),
                ];
            }
        }

        Group::insert($groups);
    }
}
