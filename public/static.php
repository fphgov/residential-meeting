<?php

declare(strict_types=1);

class OpenGraph {
    private string $title;
    private string $description;

    public string $creator;
    public string $image;

    public function __construct(
        string $title,
        string $description,
        string $creator,
        string $image
    ) {
        $this->title       = $title;
        $this->description = $description;
        $this->creator     = $creator;
        $this->image       = $image;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $content): void
    {
        $description = strip_tags($content);

        $descriptions = explode(" ", $description);
        $descriptions = array_slice($descriptions, 0, min(18, count($descriptions) - 1));

        $description  = implode(" ", $descriptions);
        $description .= ' ...';

        $this->description = $description;
    }

    public function getTags(string $uri)
    {
        return $this->getContent();
    }

    public function getContent()
    {
        return implode("\n", [
            '<title>'. $this->title .'</title>',
            '<link rel="icon" href="/szavazas/icon_96x96.png" type="image/png">',
            '<meta charset="utf-8">',
            '<meta name="description" content="'. $this->description .'">',
            '<meta property="og:type" content="website">',
            '<meta name="og:title" content="'. $this->title .'">',
            '<meta name="og:description" content="'. $this->description .'">',
            '<meta name="og:image" content="'. $this->image .'">',
            '<meta name="twitter:card" content="summary">',
            '<meta name="twitter:creator" content="'. $this->creator .'">',
            '<meta name="twitter:title" content="'. $this->title .'">',
            '<meta name="twitter:description" content="'. $this->description .'">',
        ]);
    }
}

$og = new OpenGraph(
    "Részvételi költségvetés 2021",
    "Mire költsön 1 milliárd forintot Budapest?",
    "Fővárosi Önkormányzat",
    "https://otlet.budapest.hu:443/pb/images/og_image.png"
);

$ogMetas = [
    '/szavazas/projektek/1' => [
        'title'       => 'Bokrok telepítése fű helyett a zöldsávokba',
        'description' => 'Fű helyett bokrokat telepítsenek a járdaszigetekre, vagy a járda és az úttest között lévő zöldsávokba külső kerületekben, ahol ezek a felületek a legsérülékenyebbek.',
    ],
    '/szavazas/projektek/2' => [
        'title'       => 'Fluoreszkáló anyaggal bevont útburkolat tesztje',
        'description' => 'Egy sötét (kerékpár)útszakaszon kísérletezni a fluoreszkáló útbevonattal, sövénnyel, hogy lássuk, az mennyiben alkalmas a probléma orvoslására.',
    ],
    '/szavazas/projektek/3' => [
        'title'       => 'Kerékpártámaszok létesítése városszerte',
        'description' => 'Létesüljenek kerékpártámaszok azokon a helyeken, ahol a leginkább szükség van rá: forgalmas belvárosi helyeken, közlekedési csomópontokban, közintézmények, boltok előtt.',
    ],
    '/szavazas/projektek/4' => [
        'title'       => 'Közösségi kertek és komposztáló létrehozása',
        'description' => 'Közösségi kertek kialakítása, ahol a helyiek lehetőséget kapnak saját parcellák gondozására, közösségi programokra és közösségi komposztálásra és az ezzel kapcsolatos edukációra.',
    ],
    '/szavazas/projektek/5' => [
        'title'       => 'Közösségi komposztálók létesítése közparkokban, zöld területeken',
        'description' => 'Közösségi komposztálók telepítése a fővárosi fenntartású közparkokba és zöld területekre. Használatukat és hasznukat bemutató edukációs programmal, komposztgazdák képzésével kiegészítve. A programhoz óvodák is csatlakozhatnak, amennyiben komposztálójukat a környék lakossága előtt is megnyitják.',
    ],
    '/szavazas/projektek/6' => [
        'title'       => 'Közösségi parkgondozás',
        'description' => 'A parkokban folyó fenntartási munkák bemutatása, azokba a helyi közösség bevonása speciális kertész-koordinátor munkatárs alkalmazásával.',
    ],
    '/szavazas/projektek/7' => [
        'title'       => 'Meglévő busz- és villamosmegállók körbefuttatása növényekkel',
        'description' => 'Meglévő busz- és villamosmegállók utasvárói fölé, illetve köré épített, futónövényekkel felfuttatott árnyékoló szerkezetek telepítése, különösen olyan helyekre, ahol semmilyen árnyék nem éri a megállót, figyelembe véve, hogy a Nap a melegebb évszakokban milyen szögekben éri a megállót.',
    ],
    '/szavazas/projektek/8' => [
        'title'       => 'Több ivókutat a városba',
        'description' => 'Telepítsenek forgalmasabb városi csomópontokra, parkokba ivókutakat, melyekből az emberek ingyenesen fogyaszthatnak ivóvizet. A keretösszegből nagyjából 25 ivókút telepítése lehetséges.',
    ],
    '/szavazas/projektek/9' => [
        'title'       => 'Városi erdők',
        'description' => 'Fásítatlan erdő besorolású területekre, összesen 330 parkfa telepítése, ezzel városi kiserdők létrehozása nem használt, például rozsdaövezeti telkeken, 3 év gondozással.',
    ],
    '/szavazas/projektek/10' => [
        'title'       => 'Zsebparkok létrehozása a városban',
        'description' => 'A Fővárosi Önkormányzat támogassa zsebparkok kialakítását a város különböző pontjain, akár saját tervei alapján, akár lakossági, lakóközösségi pályázatok útján. A parkok kialakításakor legyen figyelembe véve, hogy az jól szolgáljon találkozóhelyül, üdítően hasson a környék hangulatára és fenntartható módon legyen működtetve (akár a lakosság, helyi vállalkozások bevonásával).',
    ],
    '/szavazas/projektek/11' => [
        'title'       => 'A Gőtés-tó revitalizációja',
        'description' => 'A Gőtés-tó környezetének javítása az itt található természeti értékek védelme érdekében.',
    ],
    '/szavazas/projektek/12' => [
        'title'       => 'Hollós Korvin Lajos utcától délre eső fővárosi terület legyen rendezett park',
        'description' => 'Hollós Korvin Lajos utcától délre eső fővárosi terület legyen rendezett park, legyen társadalmi egyeztetés a helyiekkel arról, hogy ki mit szeretne ebben a parkban.',
    ],
    '/szavazas/projektek/13' => [
        'title'       => 'Közösségi kertek Kaszásdűlőn',
        'description' => 'A kaszásdűlői Gyógyszergyár utcai lakótelep mellett, illetve a Batthyány-Strattmann László parkban közösségi kertek létrehozása.',
    ],
    '/szavazas/projektek/14' => [
        'title'       => '„Andrássy úti fordított Rambla” - kísérleti forgalomcsillapítás',
        'description' => 'Az Andrássy út szervizútjai kerüljenek lezárásra az autós forgalom elől kísérleti jelleggel. Kapjanak itt helyet gyalogosok, esetleg kerékpárosok, teraszok, több zöld.',
    ],
    '/szavazas/projektek/15' => [
        'title'       => 'A Rottenbiller utca zöldítése az úttest és a járda között',
        'description' => 'A Rottenbiller utca Rákóczi út és a Damjanich utca közti szakaszán legyen zöldsáv rehabilitáció, új zöldfelület létesítés, ott ahol a közmű adottságok lehetővé teszik hogy talajkapcsolatos (nem ládás) zöldfelületeket hozzunk lére. A fenntartás a környék lakóközösségének bevonásával történik majd.',
    ],
    '/szavazas/projektek/16' => [
        'title'       => 'Közösségi kert a VIII. kerületi Kőris utcában',
        'description' => 'A megjelölt területen közösségi kert létrehozása üvegházzal és saját esővízgyűjtő rendszerrel.',
    ],
    '/szavazas/projektek/17' => [
        'title'       => 'Zöldebb és élhetőbb Mester utca',
        'description' => 'A 9. kerületi Mester utca adottságai miatt gyönyörű sétáló / pihenő utcává alakulhatna. Ennek első lépéseként szeretném ha egy kivitelezhető méretű sáv szélességében a beton helyén ládás, vagy a földbe ültetett növényzet lenne, praktikusan a járda és az autós sáv találkozásánál, a platán fák között.',
    ],
    '/szavazas/projektek/18' => [
        'title'       => 'Fák ültetése forgalmas újbudai utak mellé',
        'description' => 'Nagyra növő fák telepítése az olyan forgalmas utak mentén, mint pl. a Budaörsi út vagy a Nagyszőlős utca, zaj- és porcsökkentési, nyáron hőmérséklet csökkentő, illetve esztétikai céllal.',
    ],
    '/szavazas/projektek/19' => [
        'title'       => 'Zöldsávok fejlesztése a XI. kerületben',
        'description' => 'Zöldsáv fejlesztés a XI. kerület forgalmasabb útszakaszain a járdát az úttesttől elválasztó sávon a fák között, a lakosság és az üzletek bevonásával.',
    ],
    '/szavazas/projektek/20' => [
        'title'       => 'Közösségi funkciók a Szent István parkba',
        'description' => 'A Szent István park kisparki elkerített részeit közösségi kertté alakítani, a nagyparki részen a kosárlabdapálya biztonságos felülettel való burkolása.',
    ],
    '/szavazas/projektek/21' => [
        'title'       => 'Faültetés a Nagy Lajos király útján',
        'description' => 'Faültetés és zöldítés a Nagy Lajos király útján: a páratlan oldalon a parkoló és sínek között cserjék és fák telepítése, a házsor előtt 2-3 méter széles ágyás kialakítása cserjéknek, fáknak, színes évelőknek, a páros oldalon a járdában zöld szigetek kiképzése.',
    ],
    '/szavazas/projektek/22' => [
        'title'       => 'Közösségi kert Zuglóban',
        'description' => 'Egy olyan új városi zöldfelület létrehozása, amelyet minden korosztály használhat, olyan célokra, mint saját élelmiszer megtermelése, rekreáció, természethez való kapcsolódás, közösségi komposztálás, családi programok, edukáció.',
    ],
    '/szavazas/projektek/23' => [
        'title'       => 'Zuglói permakultúrás közösségi kert',
        'description' => 'A permakultúra alapelvei mentén működő ökologikus kertet és közösségi teret hozunk létre, amelyet minden korosztály használ.',
    ],
    '/szavazas/projektek/24' => [
        'title'       => 'Csepeli közösségi kert létrehozása',
        'description' => 'Közösségi kert kialakítása a megjelölt helyszínek egyikén, ahol a helyiek lehetőséget kapnak saját parcellák gondozására, közösségi programokra és közösségi komposztálásra.',
    ],
    '/szavazas/projektek/25' => [
        'title'       => 'Állatterápiás programok idősotthonokban',
        'description' => 'Legyenek állatterápiás programok az idősotthonokban. A programokba bekapcsolódhatnak óvodák is.',
    ],
    '/szavazas/projektek/26' => [
        'title'       => 'Fővárosi diákszervezet létrehozása',
        'description' => 'Egy rendszeres fővárosi szintű fórum, ahol diákönkormányzatok képviselői egyeztethetik problémáikat, álláspontjaikat, tapasztalataikat, képviseltethetik a diákok érdekeit.',
    ],
    '/szavazas/projektek/27' => [
        'title'       => 'Ingyenes sportolási lehetőség biztosítása',
        'description' => 'Ingyenes sportlehetőség biztosítása. Előzetes regisztrációval lehet jelentkezni ezekre a sportoktatók, sport szakirányos hallgatók, önkéntesek által tartott programokra.',
    ],
    '/szavazas/projektek/28' => [
        'title'       => 'Kísérleti okospad közösségi közlekedési megállóba',
        'description' => 'Kísérleti jelleggel okospad telepítése egy megállóba, amely akár napelemes is lehet, ingyenes wifit bocsát ki, lehetőséget biztosít telefontöltésre.',
    ],
    '/szavazas/projektek/29' => [
        'title'       => 'Közösségi tér létrehozása mozgássérülteknek és épeknek',
        'description' => 'Egy minimum 300 négyzetméteres közösségi és sport tér létrehozása, ahol mozgássérültek és demenciában szenvedők találkozhatnak és sportolhatnak együtt épekkel. Elsősorban egy pétanque pálya létrehozása lenne célszerű, amit a legtöbb mozgásában korlátozott ember is tud játszani, fontos, hogy a téren legyenek formájukban, hangulatukban elkülönülő pontok, mezítlábas ösvények, az egész legyen zöld és üdítő hangulatú.',
    ],
    '/szavazas/projektek/30' => [
        'title'       => 'Nemzetközi Roma Nap ismertebbé tétele és szervezésének támogatása',
        'description' => 'A Nemzetközi Roma Nap (április 8. ) ismertségének növelése és szervezésének támogatása, egy szervezőbizottság felállítása civil partnerek, pro-roma szervezetek és a Fővárosi Önkormányzat együttműködésével.',
    ],
    '/szavazas/projektek/31' => [
        'title'       => 'Példamutató közvécék Budapesten',
        'description' => 'Példamutató, a meglévőknél magasabb komfortot és újszerű vizuális minőséget kínáló nyilvános illemhelyek létesítése Budapest két pontján. Extrák: Elektronikus, okos fizetési lehetőség vagy ingyenesség; újszerű fenntartási konstrukció kidolgozása; egyéb kapcsolt szolgáltatások (pl. ivókút, telefontöltés).',
    ],
    '/szavazas/projektek/32' => [
        'title'       => 'Ülőfelületek a Duna-parti rézsűn',
        'description' => 'A belvárosi Duna-parti rézsűkre olyan, árvíztűrő betonból készült geometrikus elemek kihelyezése, amelyek ülőfelületként, asztalként és lépcsőként is – valamint néhány esetben extra funkcióval (kutyaitató, grill) – használhatók. Civilek bevonása a fenntartásba.',
    ],
    '/szavazas/projektek/33' => [
        'title'       => 'Üres lakások felújítása és bérbeadása hajléktalansággal küzdőknek',
        'description' => 'Fővárosi vagy kerületi tulajdonú, üresen álló lakások vagy lakhatásra használható ingatlanok felújítása civil szervezeti segítséggel és az érintettek önkéntes munkájával, majd a kialakított lakások, lakóegységek bérbeadása rászorulók számára.',
    ],
    '/szavazas/projektek/34' => [
        'title'       => 'Kerékpáros / gördeszkás akrobatapálya létesítése Óbudán',
        'description' => 'Egy kerékpáros / gördeszkás akrobatapálya (skatepark) létesítése Óbudán. Ha az ötlet elég támogatást kap, akkor együtt véglegesítjük a legalkalmasabb helyszínt a pálya leendő használóival és a kerülettel.',
    ],
    '/szavazas/projektek/35' => [
        'title'       => 'Legális graffitifal létrehozása',
        'description' => 'Egy legális graffitifelület kijelölése Budapesten.',
    ],
    '/szavazas/projektek/36' => [
        'title'       => 'Gyalogosátkelő az Astoriánál',
        'description' => 'Létesüljön az Astoriánál a Rákóczi utat keresztező gyalogosátkelő, mert nagy igény van rá.',
    ],
    '/szavazas/projektek/37' => [
        'title'       => 'Legyen gyalogosátkelőhely a Kerepesi úton a buszpályaudvar és az Árkád között',
        'description' => 'Létesüljön gyalogosátkelőhely a kerékpárút mellett a Kerepesi úton a buszpályaudvar és az Árkád között.',
    ],
    '/szavazas/projektek/38' => [
        'title'       => '„Budapest Peremén” rehabilitációs otthon',
        'description' => 'Személyes krízisbe került emberek – például állami gondozásból kikerülő fiatalok, hajléktalan emberek, lakásukból kilakoltatottak, szenvedélybetegségükből kijönni szándékozók – számára rehabilitációs otthon megteremtése Budapest valamely peremkerületén, civil/szakmai szervezeti háttérrel. A program a közvetlen segítségen, biztonságnyújtáson kívül gazdálkodásba is bevonja az ott lévő személyeket, és egyben a környezettudatos és fenntartható élettel kapcsolatos szemléletformálást is céljának tekinti.',
    ],
    '/szavazas/projektek/39' => [
        'title'       => 'Budapest Földhajó - ökoépítészeti és fenntarthatósági központ',
        'description' => 'Önfenntartó ökoépület és kert létrehozása Michael Reynolds Earthship-elgondolása alapján, amely fenntarthatósággal kapcsolatos oktatási, közösségi központként szolgálna.',
    ],
    '/szavazas/projektek/40' => [
        'title'       => 'Felnőtt Autista Szabadidős és Kulturális Központ',
        'description' => 'Az első hazai Felnőtt Autista Szabadidős és Kulturális Központ létrehozása a fővárosban, ahol az alapszintű oktatásból, továbbképzésből és a felsőoktatásból kikerülő autista fiatalok élethosszig tartó támogatásra és közösségekre találhatnak.',
    ],
    '/szavazas/projektek/41' => [
        'title'       => 'Gyalogosátkelők biztonságosabbá tétele',
        'description' => 'Gyalogosátkelőhelyek, különös tekintettel az iskolák közelében lévő átkelőkre, biztonságosabbá tétele különböző megoldásokkal, pl: lámpák, fényvisszaverő elemek, éjszakai kivilágítás, a látássérült gyalogosok biztonsága érdekében hangjelző berendezésekkel.',
    ],
    '/szavazas/projektek/42' => [
        'title'       => 'Kevesebb eldobott szemét',
        'description' => 'Az eldobált szemét mennyiségének csökkentése érdekében az elavult vagy rossz helyen lévő szemétgyűjtők helyett korszerű fedett, illetve szelektív gyűjtést is lehetővé tévő szemetesek kihelyezése a város azon pontjain, ahol a szemetelés problémája kiemelkedő. Emellett ötletes kampánnyal is fel lehet hívni a figyelmet a szemetelés problémakörére.',
    ],
    '/szavazas/projektek/43' => [
        'title'       => 'Legyenek közérthetőek a hivatali levelek, dokumentumok',
        'description' => 'A legfontosabb hivatali levelek, tájékoztatók és formanyomtatványok legyenek átírva közérthetőre, hogy a lakosság könnyebben megértse azokat.',
    ],
    '/szavazas/projektek/44' => [
        'title'       => 'Megfizethető lakhatási lehetőség teremtése',
        'description' => 'Fővárosi ingatlanok felújítása és bérbeadása non-profit alapon, megvalósító-közvetítő szervezetek bevonásával.',
    ],
    '/szavazas/projektek/45' => [
        'title'       => 'Mozgásérzékelős okos közvilágítás kiépítése kísérleti jelleggel',
        'description' => 'Egy adott területen olyan mozgásérzékelőkkel ellátott közvilágítási rendszer kiépítése kísérleti jelleggel, amely csak akkor világít maximális fényerővel, ha arra jár valaki.',
    ],
    '/szavazas/projektek/46' => [
        'title'       => 'Mozgólépcsők aluljárókba',
        'description' => 'Mozgólépcsők létesítése forgalmasabb aluljárókba.',
    ],
    '/szavazas/projektek/47' => [
        'title'       => 'Napelemes lámpák telepítése rosszul bevilágított közterületekre',
        'description' => 'A lakosság segítségével felmérni Budapest rosszul megvilágított területeit. A legkritikusabb helyeken, amelyek nem feltétlenül igényelnek szabványos fényerejű közvilágítást, napelemes - hálózati bekötés nélküli - lámpákkal javítani a helyzetet.',
    ],
    '/szavazas/projektek/48' => [
        'title'       => 'Önkéntes csoportos szemétszedések támogatása',
        'description' => 'A Fővárosi Önkormányzat eszközöket és logisztikai segítséget biztosít önkéntes szemétszedő csoportok számára egy tisztább környezetért. Ezzel párhuzamosan felhívást tesz közzé óvodákban, iskolákban, hogy néhány alkalommal vegyenek részt szemétszedésen, ezzel kapcsolatos edukációval összekapcsolva.',
    ],
    '/szavazas/projektek/49' => [
        'title'       => 'Rehabilitációs központ ráktúlélőknek és időseknek',
        'description' => 'Egy ráktúlélők és idősek számára létrehozott, megelőzést, sportot és egészséges életmódot népszerűsítő központ, ahol van teázó/kávézó és egészséges, alkoholmentes ital- és ételkínálat, a fő program a rehabilitációs sport és az egészséges életmóddal kapcsolatos programok. A központ működését az ezeken a szakterületeken tevékenykedő civil szervezetek bevonásával látnák el.',
    ],
    '/szavazas/projektek/50' => [
        'title'       => 'Veszélyes kerékpárút szakaszok kivilágítása',
        'description' => 'Olyan kerékpárút szakaszok kivilágítása, amelyek sötétben veszélyesek lehetnek. A kivilágítást LED vagy napelemes lámpákkal lehetne megoldani.',
    ],
    '/szavazas/projektek/51' => [
        'title'       => 'Fák ültetése a Rákóczi útra',
        'description' => 'Valódi talajkapcsolattal rendelkező fák ültetése a Rákóczi út egy próbaszakaszára, ahol ezt a közművek megengedik.',
    ],
    '/szavazas/projektek/52' => [
        'title'       => 'Népliget megújítása',
        'description' => 'A Népliget park egyes funkcióinak fejlesztése. Érintett témák: közbiztonság javítása, kamaszok és fogyatékkal élő gyerekek számára is használható játszótér kialakítása, görkorcsolyázásra, gördeszkázásra, rollerezésre alkalmas burkolatok kialakítása, padok számának növelése. A rendelkezésre álló keret legjobb felhasználása, a pontos műszaki tartalom meghatározása közösségi tervezéssel történik.',
    ],
    '/szavazas/projektek/53' => [
        'title'       => 'Sportinfrastruktúra fejlesztése a Margitszigeten',
        'description' => 'Sportinfrastruktúra fejlesztése a Margitszigeten, a sportolni vágyók igényei szerint. Legyenek kialakítva értékmegőrző pontok, ahol a futni, sportolni vágyók otthagyhatják értékeiket.',
    ],
];

if (array_key_exists($_SERVER['REQUEST_URI'], $ogMetas)) {
    $og->setTitle($ogMetas[$_SERVER['REQUEST_URI']]['title']);
    $og->setDescription($ogMetas[$_SERVER['REQUEST_URI']]['description']);
}

$doc = file_get_contents('index.html');

echo preg_replace('/\<meta charset="utf-8"\/>/', $og->getTags($_SERVER['REQUEST_URI']), $doc);
