<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizAnswerExplanationSeeder extends Seeder
{
    public function run(): void
    {
        // card_id => answer_id => explanation
        $explanations = [
            1 => [
                '1' => "Juist: brandnetels hebben brandhaartjes die bij aanraking een prikkelende stof afgeven, daardoor krijg je een prikkend/brandend gevoel.",
                '2' => "Fout: brandnetels staan niet bekend om een zoete geur; het opvallende kenmerk zijn juist de brandhaartjes.",
                '3' => "Fout: brandnetels groeien niet alleen in de winter; je ziet ze vooral in lente, zomer en herfst.",
            ],
            2 => [
                '1' => "Juist: tulpen bloeien meestal in de lente. De bol gebruikt in het voorjaar opgeslagen energie om te bloeien.",
                '2' => "Fout: in de zomer zijn tulpen meestal al uitgebloeid en trekt de plant zich terug in de bol.",
                '3' => "Fout: in de winter bloeien tulpen niet; dan is het meestal te koud en zit de energie in de bol opgeslagen.",
            ],
            3 => [
                '1' => "Juist: klaprozen herken je aan de dunne rode bloemblaadjes (bijna als papier).",
                '2' => "Fout: klaprozen groeien niet alleen op water; je ziet ze juist vaak op akkers/bermen en open velden.",
                '3' => "Fout: een sterke geur is niet het typische kenmerk; de opvallende rode, dunne blaadjes zijn dat wel.",
            ],
            4 => [
                '1' => "Juist: vlinders komen graag op de nectar in de bloemen af—daarom heet het een vlinderstruik.",
                '2' => "Fout: de naam gaat niet om de vorm van de bladeren, maar om de vlinders die de bloemen bezoeken.",
                '3' => "Fout: de struik “beweegt” niet als een vlinder; de bloemen trekken juist vlinders aan.",
            ],
            5 => [
                '1' => "Juist: de kleur hangt samen met de zuurgraad (pH) van de bodem; dat beïnvloedt welke stoffen de plant opneemt.",
                '2' => "Fout: zonuren kunnen groei beïnvloeden, maar veranderen niet direct de typische kleurwisseling van hortensia’s.",
                '3' => "Fout: de kleur van de bladeren bepaalt de bloemkleur niet; de bodem/pH is hier de oorzaak.",
            ],
            6 => [
                '1' => "Juist: waterlelies groeien in stilstaand water (zoals vijvers). Ze wortelen in de bodem en drijven bovenop.",
                '2' => "Fout: in het bos (op droge grond) kunnen waterlelies niet groeien; ze hebben water nodig om te drijven.",
                '3' => "Fout: droge zandgrond heeft geen stilstaand water; waterlelies kunnen daar niet wortelen en drijven.",
            ],
            7 => [
                '1' => "Juist: narcissen herken je aan de trompetvorm in het midden van de bloem.",
                '2' => "Fout: paarse blaadjes zijn niet typisch voor de narcis; narcissen zijn vaak geel/wit met een trompet.",
                '3' => "Fout: narcissen hebben geen scherpe stekels; het herkenningspunt is de trompetvorm.",
            ],
            8 => [
                '1' => "Juist: boterbloemen zijn glanzend en geel; die glans weerkaatst veel licht.",
                '2' => "Fout: boterbloemen zijn niet donkerpaars en groot; ze zijn juist klein en felgeel.",
                '3' => "Fout: boterbloemen zijn niet wit en pluizig; het zijn gladde, glanzende gele bloemetjes.",
            ],
            9 => [
                '1' => "Juist: hyacinten staan bekend om hun sterke geur.",
                '2' => "Fout: een hyacint heeft juist veel kleine bloemetjes bij elkaar, niet maar één bloem.",
                '3' => "Fout: hyacinten groeien niet onder water; het zijn bolplanten voor grond/tuin.",
            ],
            10 => [
                '1' => "Juist: de bloemen zijn buisvormig, als kleine vingerhoedjes (en de plant is giftig).",
                '2' => "Fout: platte schoteltjes passen bij andere bloemen; vingerhoedskruid heeft juist buisjes.",
                '3' => "Fout: sterrenvormig is een andere bloemvorm; vingerhoedskruid heeft hangende buisvormige bloemen.",
            ],
            11 => [
                '1' => "Juist: in de herfst zijn de appels rijp en kun je ze plukken.",
                '2' => "Fout: de boom bloeit in de lente, niet in de herfst; in de herfst gaat het om rijpe vruchten.",
                '3' => "Fout: bladeren worden in de herfst eerder geel/bruin, niet roze; de verandering gaat om rijping/oogst.",
            ],
            12 => [
                '1' => "Juist: kastanjes zitten in een stekelige bolster die de vrucht beschermt.",
                '2' => "Fout: een gladde groene bol hoort niet bij tamme kastanjes; die zitten juist in een stekelige bolster.",
                '3' => "Fout: kastanjes hangen niet “los” zonder bescherming; de bolster is juist typisch.",
            ],
            13 => [
                '1' => "Juist: kersenbloesem is beroemd om de enorme hoeveelheid roze/witte bloemetjes.",
                '2' => "Fout: de bekendheid komt niet vooral door geur; het gaat om de massale bloei.",
                '3' => "Fout: kersenbloesem gaat juist om bloemen (bloesems), niet om grote vruchten.",
            ],
            14 => [
                '1' => "Juist: de vrucht van een eik heet een eikel.",
                '2' => "Fout: kastanjes horen bij kastanjebomen, niet bij eiken.",
                '3' => "Fout: pruimen groeien aan pruimenbomen; eiken maken eikels.",
            ],
            15 => [
                '1' => "Juist: de stam van een beuk is glad en grijzig (heel herkenbaar).",
                '2' => "Fout: beukenstammen zijn niet behaard; ze zijn juist opvallend glad.",
                '3' => "Fout: beukenstammen zijn niet knalrood; ze zijn meestal grijs.",
            ],
            16 => [
                '1' => "Juist: dennenappels helpen zaden verspreiden; ze openen of vallen zodat zaden vrijkomen.",
                '2' => "Fout: eikels komen van eiken, niet van dennen.",
                '3' => "Fout: appels groeien aan appelbomen, niet aan dennen.",
            ],
            17 => [
                '1' => "Juist: de vliegenzwam heeft een rode hoed met witte stippen.",
                '2' => "Fout: bruin met gele stippen is niet het klassieke uiterlijk van de vliegenzwam.",
                '3' => "Fout: de vliegenzwam heeft stippen, geen strepen; dat maakt ‘m herkenbaar.",
            ],
            18 => [
                '1' => "Juist: als een bovist openbarst komt er een wolkje sporen vrij; daarmee verspreidt hij zich.",
                '2' => "Fout: er komt geen water uit; bovisten verspreiden sporen (een soort poeder).",
                '3' => "Fout: er komt geen melksap uit; dat zie je bij andere paddenstoelen/ planten, niet bij bovisten.",
            ],
            19 => [
                '1' => "Juist: de hoed lijkt op een parasol en klapt steeds verder open.",
                '2' => "Fout: de naam komt niet doordat hij naast ‘parasolplanten’ groeit, maar door de vorm van de hoed.",
                '3' => "Fout: hij beschermt dieren niet tegen regen; het gaat om hoe de paddenstoel eruitziet.",
            ],
            20 => [
                '1' => "Juist: tonderzwammen groeien vooral op boomstammen (vast aan hout).",
                '2' => "Fout: weilanden hebben geen boomstammen om op te groeien; tonderzwam is een houtzwam.",
                '3' => "Fout: onder water groeit hij niet; hij leeft op hout van bomen.",
            ],
            21 => [
                '1' => "Juist: eekhoorntjesbrood is stevig: bruine hoed, dikke steel.",
                '2' => "Fout: het is niet dun/doorschijnend; juist een stevige paddenstoel.",
                '3' => "Fout: felblauw en heel klein past niet; eekhoorntjesbrood is bruin en fors.",
            ],
            22 => [
                '1' => "Juist: de kleur lijkt op rode kool (paars/paarsrood).",
                '2' => "Fout: sinaasappel is oranje; deze zwam is paars/paarsrood.",
                '3' => "Fout: rode biet is meer donkerrood; ‘rodekoolzwam’ verwijst juist naar paars zoals rode kool.",
            ],
            23 => [
                '1' => "Juist: de reuzenbovist kan enorm groot worden, soms zo groot als een voetbal.",
                '2' => "Fout: geur naar chocola is niet kenmerkend; het bijzondere is vooral het formaat.",
                '3' => "Fout: hij is niet knikker-klein; juist de grootte maakt hem speciaal.",
            ],
            24 => [
                '1' => "Juist: winterjasmijn bloeit in de winter, wanneer veel planten geen bloemen hebben.",
                '2' => "Fout: winterjasmijn heeft geen stekels zoals een roos; hij heeft lange, dunne takken.",
                '3' => "Fout: een sterke citroengeur is niet het typische kenmerk; de winterbloei is dat wel.",
            ],
            25 => [
                '1' => "Juist: hij lijkt op brandnetel, maar prikt niet—daarom ‘doof’.",
                '2' => "Fout: ‘doof’ betekent hier niet dat je oren doof worden; het betekent dat hij niet prikt.",
                '3' => "Fout: hij groeit niet alleen naast echte brandnetels; hij kan op veel plekken groeien.",
            ],
            26 => [
                '1' => "Juist: sneeuwklokjes zie je vaak aan het einde van de winter/begin van de lente.",
                '2' => "Fout: in de zomer zijn sneeuwklokjes meestal al lang verdwenen/uitgebloeid.",
                '3' => "Fout: niet alleen in de herfst; juist vroeg in het jaar (winter/lente) zijn ze typisch.",
            ],
            27 => [
                '1' => "Juist: lavendel staat bekend om de paarse kleur én de sterke, lekkere geur.",
                '2' => "Fout: snel groeien is niet waar lavendel om bekend staat; hij groeit vaak juist rustig/compact.",
                '3' => "Fout: lavendel groeit niet onder water; hij houdt van droge, zonnige plekken.",
            ],
            28 => [
                '1' => "Juist: hij bloeit in de herfst, maar krijgt zijn bladeren pas in de lente.",
                '2' => "Fout: het opvallende is niet kleurverandering bij aanraken, maar het verschil tussen bloei en bladseizoen.",
                '3' => "Fout: hij groeit niet in sneeuw; hij komt vooral voor in graslanden/vochtige plekken afhankelijk van soort.",
            ],
            29 => [
                '1' => "Juist: munt gebruik je vaak in thee en kauwgom voor een frisse smaak.",
                '2' => "Fout: munt wordt niet ‘meestal’ gebruikt om meubels schoon te maken; het is vooral een geur- en smaakplant.",
                '3' => "Fout: munt is niet bedoeld als dieren-wegjager; het wordt vooral gebruikt voor eten/drinken en geur.",
            ],
        ];

        foreach ($explanations as $cardId => $perAnswer) {
            $row = DB::table('quiz')->where('card_id', $cardId)->first();
            if (!$row) continue;

            $answers = json_decode($row->answers ?? '[]', true) ?: [];

            foreach ($answers as &$a) {
                $id = (string)($a['id'] ?? '');
                $a['explanation'] = $perAnswer[$id] ?? 'Geen uitleg beschikbaar.';
            }
            unset($a);

            DB::table('quiz')->where('card_id', $cardId)->update([
                'answers' => json_encode($answers, JSON_UNESCAPED_UNICODE),
            ]);
        }
    }
}
