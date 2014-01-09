<?PHP
//Auto loader (hogy elég legyen a use-t használni, és ne kelljen includeolni midnig hogy include "Diagram/Graph.php", ez megcsinálja.)
spl_autoload_register(function ($class) {
    include str_replace("\\", "/", $class) . '.php';
});

//Diagram rajzoláshoz szükséges osztályok
use Diagram\Graph; //Diagram lap
use Diagram\Adapters; //Diagram adapterek (ImagickAdapter, GDAdapter)
use Diagram\DrawingArea; //Rajzolási terület (Blokk)
use Diagram\DiagramData; //Diagram adatok 
use Diagram\DiagramType; //Diagram Típusok
use Diagram\Color; //Diagram szín generáló
use Diagram\Axle; //Diagram tengelyek

//Új diagramháttér létrehozása - adapter: imagick, width: 760, height: 720, pad-top: 50, pad-right: 30, pad-bottom: 70, pad-left: 30
$d = new Graph(new Adapters\GDAdapter(), 760, 720, 50, 30, 70, 30);
// $d = new Graph(new Adapters\ImagickAdapter(), 760, 720, 50, 30, 70, 30);
$d->setImageType("png"); //Formátum: jpg
//$d->setBackgroundImage("images/graph0.png"); //Háttérkép: images/graph0.png
///////////////////////////////////////////////////////////////////////////////////////////////////////
#Felhőzet
//Diagram háttér - width: auto, height: auto, háttérszín: #f5f5f5
$cloudDiagram = new DrawingArea("auto", 74, new Color("#f5f5f5"));
$cloudDiagram->setMargin(0,0,1,0);//Alsó margin: 1px

//Diagram tengely - pozíció: alul, típus: fő tegnely, min érték: 1, max érték: 31
$dayAxle = new Axle(Axle::POSITION_BOTTOM, Axle::TYPE_MASTER, 1,31);
$dayAxle->setcolor(new Color("#dedede")) //Tengely színe: #dedede
		->setTextColor(new Color("#333")) //Szöveg Színe: #333
		->setStepVisibility(Axle::COLUMN_SHOW_NONE) //Tengely láthatósága: nincs
		->setTextVisibility(Axle::TEXT_SHOW_NONE); //Szöveg láthatósága: nincs
$cloudDiagram->addAxle($dayAxle); //Tengely hozzáadása a rainSumDiagramhoz

//Diagram tengely - pozíció: bal, típus: adat
$cloudAxle = new Axle(Axle::POSITION_LEFT, Axle::TYPE_DATA);
$cloudAxle->addValueRange(1,3) //Max érték: 3, Min érték: 1
		  ->setColor(new Color("#dedede")) //Tengely színe: #dedede
		  ->setTextVisibility(Axle::TEXT_SHOW_NONE) //Szöveg láthatósága: nincs
		  ->setStepVisibility(Axle::COLUMN_SHOW_NONE) //Tengely láthatósága: nincs
		  ->setTextColor(new Color("#333")); //Szöveg színe: #333 
$cloudDiagram->addAxle($cloudAxle); //Tengely hozzáadása a maxTemperatureDiagramhoz

//Diagram adatok - Típus: DiagramType\Line, szín: kék 
$cloudData = new DiagramData(new DiagramType\Image());
$cloudData->setMasterAxle($dayAxle); //fő tengely beállítása (lenti)
$cloudData->setDataAxle($cloudAxle); //adat tengely beállítása (bal)

for($i=1;$i<=31;$i++)
{
	$cloudData->addValue($i, 2); //Feltöltés random adattal
}

$cloudDiagram->addData($cloudData); //Adatok hozzárendelése a rainSumDiagramhoz
///////////////////////////////////////////////////////////////////////////////////////////////////////
#Max hőmérséklet
//Diagram háttér - width: auto, height: auto, háttérszín: #f1f1f1
$maxTemperatureDiagram = new DrawingArea("auto", "auto", new Color("#f1f1f1"));
$maxTemperatureDiagram->setMargin(0,0,1,0);//Alsó margin: 1px

//Diagram tengely - pozíció: alul, típus: fő tegnely, min érték: 1, max érték: 31
$dayAxle = new Axle(Axle::POSITION_BOTTOM, Axle::TYPE_MASTER, 1,31);
$dayAxle->setcolor(new Color("#dedede")) //Tengely színe: #dedede
		->setTextColor(new Color("#333")) //Szöveg Színe: #333
		->setStepVisibility(Axle::COLUMN_SHOW_NONE) //Tengely láthatósága: nincs
		->setTextVisibility(Axle::TEXT_SHOW_NONE); //Szöveg láthatósága: nincs
$maxTemperatureDiagram->addAxle($dayAxle); //Tengely hozzáadása a rainSumDiagramhoz

//Diagram tengely - pozíció: bal, típus: adat
$temperatureAxle = new Axle(Axle::POSITION_LEFT, Axle::TYPE_DATA);
$temperatureAxle->setStep(5) //Lépték: 5
		  		->addValueRange(5,-30) //Max érték: 5, Min érték: -30
		  		->addSpecialValue(0, new Color("#c22436")) //Speciális sor: 0 -> piros
		 		->setColor(new Color("#dedede")) //Tengely színe: #dedede
		  		->setTextColor(new Color("#333")); //Szöveg színe: #333 
$maxTemperatureDiagram->addAxle($temperatureAxle); //Tengely hozzáadása a maxTemperatureDiagramhoz

//Diagram adatok - Típus: DiagramType\Line, szín: kék 
$maxTemperatureData = new DiagramData(new DiagramType\Line(), new Color("#189c95"), 3);
$maxTemperatureData->setMasterAxle($dayAxle); //fő tengely beállítása (lenti)
$maxTemperatureData->setDataAxle($temperatureAxle); //adat tengely beállítása (bal)

for($i=1;$i<=31;$i++)
{
	$maxTemperatureData->addValue($i, rand(0,-5)); //Feltöltés random adattal
}

$maxTemperatureDiagram->addData($maxTemperatureData); //Adatok hozzárendelése a rainSumDiagramhoz
///////////////////////////////////////////////////////////////////////////////////////////////////////
#Szélsebesség
//Diagram háttér - width: auto, height: auto, háttérszín: #f5f5f5
$windSpeedDiagram = new DrawingArea("auto", "auto", new Color("#f5f5f5"));
// $windSpeedDiagram->setMargin(0,0,1,0);//Alsó margin: 1px

//Diagram tengely - pozíció: bal, típus: adat tegnely
$windSpeedAxle = new Axle(Axle::POSITION_LEFT, Axle::TYPE_DATA);
$windSpeedAxle->setColor(new Color("#dedede")) //Tengely színe: #dedede
		   ->setStep(5) //Lépték: 5
		   ->addValueRange(30,5) //Max érték: 30, Min érték: 0
		   ->setBigStep(10) //Nagy lépték: 10
		   ->setStepVisibility(Axle::COLUMN_SHOW_BIG) //Látható sorok: Csak a nagy léptéknél
		   ->setTextColor(new Color("#333")); //Szöveg Színe: #333
$windSpeedDiagram->addAxle($windSpeedAxle); //Tengely hozzáadása a windSpeedDiagramhoz

//Diagram tengely - pozíció: alul, típus: fő tegnely, min érték: 1, max érték: 31
$dayAxle = new Axle(Axle::POSITION_BOTTOM, Axle::TYPE_MASTER, 1,31);
$dayAxle->setcolor(new Color("#dedede")) //Tengely színe: #dedede
		->setStepVisibility(Axle::COLUMN_SHOW_NONE) //Látható oszlopok: nincs
		->setTextVisibility(Axle::TEXT_SHOW_NONE); //Látható szöveg: nincs
$windSpeedDiagram->addAxle($dayAxle); //Tengely hozzáadása a windSpeedDiagramhoz

//Diagram adatok - Típus: DiagramType\Column, szín: kék
$windSpeedData = new DiagramData(new DiagramType\Column(), new Color("#ffda5b"), 4.5);
$windSpeedData->setBorder(1, new Color("#f0ac2c")); //Border szín beállítása
$windSpeedData->setMasterAxle($dayAxle); //fő tengely beállítása (lenti)
$windSpeedData->setDataAxle($windSpeedAxle); //adat tengely beállítása (bal)

$windSpeedData2 = new DiagramData(new DiagramType\Column(), new Color("#f0ac2c"), 4.5);
$windSpeedData2->setMasterAxle($dayAxle); //fő tengely beállítása (lenti)
$windSpeedData2->setDataAxle($windSpeedAxle); //adat tengely beállítása (bal)

for($i=1;$i<=31;$i++)
{
	$currRand = rand(0,20);
	$windSpeedData2->addValue($i, $currRand); //Feltöltés random adattal
	$windSpeedData->addValue($i, rand($currRand, 25)); //Feltöltés random adattal
}

$windSpeedDiagram->addData($windSpeedData); //Adatok hozzárendelése a rainSumDiagramhoz
$windSpeedDiagram->addData($windSpeedData2); //Adatok hozzárendelése a rainSumDiagramhoz
///////////////////////////////////////////////////////////////////////////////////////////////////////
#Szélirány
//Diagram háttér - width: auto, height: 30px, háttérszín: #f5f5f5
$windDirDiagram = new DrawingArea("auto", 30, new Color("#f5f5f5"));
$windDirDiagram->setMargin(0,0,1,0);//Alsó margin: 1px

//Diagram tengely - pozíció: alul, típus: fő tegnely, min érték: 1, max érték: 31
$dayAxle = new Axle(Axle::POSITION_BOTTOM, Axle::TYPE_MASTER, 1,31);
$dayAxle->setcolor(new Color("#dedede")) //Tengely színe: #dedede
		->setStepVisibility(Axle::COLUMN_SHOW_NONE) //Látható oszlopok: nincs
		->setTextVisibility(Axle::TEXT_SHOW_NONE); //Látható szöveg: nincs
$windDirDiagram->addAxle($dayAxle); //Tengely hozzáadása a windSpeedDiagramhoz

//Diagram tengely - pozíció: bal, típus: adat
$windDirAxle = new Axle(Axle::POSITION_LEFT, Axle::TYPE_DATA);
$windDirAxle->addValueRange(1,3) //Max érték: 5, Min érték: -30
		  ->setColor(new Color("#dedede")) //Tengely színe: #dedede
		  ->setTextVisibility(Axle::TEXT_SHOW_NONE) //Szöveg láthatósága: nincs
		  ->setTextColor(new Color("#333")); //Szöveg színe: #333 
$cloudDiagram->addAxle($windDirAxle); //Tengely hozzáadása a maxTemperatureDiagramhoz

//Diagram adatok - Típus: DiagramType\Line, szín: kék 
$windDirData = new DiagramData(new DiagramType\Image());
$windDirData->setMasterAxle($dayAxle); //fő tengely beállítása (lenti)
$windDirData->setDataAxle($windDirAxle); //adat tengely beállítása (bal)

for($i=1;$i<=31;$i++)
{
	$windDirData->addValue($i, 2); //Feltöltés random adattal
}

$windDirDiagram->addData($windDirData); //Adatok hozzárendelése a rainSumDiagramhoz
///////////////////////////////////////////////////////////////////////////////////////////////////////
#Csapadék összeg
//Diagram háttér - width: auto, height: auto, háttérszín: #f1f1f1
$rainSumDiagram = new DrawingArea("auto", "auto", new Color("#f1f1f1"));

//Diagram tengely - pozíció: bal, típus: adat, min érték: 1, max érték: 7
$rainSumAxle = new Axle(Axle::POSITION_LEFT, Axle::TYPE_DATA, 1, 7);
$rainSumAxle->setColor(new Color("#dedede")) //Tengely színe: #dedede
		    ->setTextColor(new Color("#333")); //SZöveg színe: #333
$rainSumDiagram->addAxle($rainSumAxle); //Tengely hozzáadása a rainSumDiagramhoz

//Diagram tengely - pozíció: alul, típus: fő tegnely, min érték: 1, max érték: 31
$dayAxle = new Axle(Axle::POSITION_BOTTOM, Axle::TYPE_MASTER, 1,31);
$dayAxle->setcolor(new Color("#dedede")) //Tengely színe: #dedede
		->setTextColor(new Color("#333")) //Szöveg Színe: #333
		->setStepVisibility(Axle::COLUMN_SHOW_NONE); //Tengely láthatósága: nincs
$rainSumDiagram->addAxle($dayAxle); //Tengely hozzáadása a rainSumDiagramhoz

//Diagram adatok - Típus: DiagramyType\Line, szín: piros, 
$rainSumData = new DiagramData(new DiagramType\Line(), new Color("red"));
$rainSumData->setMasterAxle($dayAxle); //fő tengely beállítása (lenti)
$rainSumData->setDataAxle($rainSumAxle); //adat tengely beállítása (bal)

for($i=1;$i<=31;$i++)
{
	$rainSumData->addValue($i, mt_rand(1,6)); //Feltöltés random adattal
}

$rainSumDiagram->addData($rainSumData); //Adatok hozzárendelése a rainSumDiagramhoz

/**
 * Rajzolások
 **/
//felhő diagram csatolása a laphoz, olyan sorrendben lesznek, ahogy itt van
$d->addDiagramArea($cloudDiagram);
$d->addDiagramArea($maxTemperatureDiagram);
$d->addDiagramArea($windSpeedDiagram);
$d->addDiagramArea($windDirDiagram);
$d->addDiagramArea($rainSumDiagram);

//Kép renderelése, majd a renderelő lekérése(ImagickAdapter), aztán a kész képre szöveg írása.
$d->Render()->GetRenderer(false)->DrawText("Felcsút - [Magyarország]", 7, 17, "AvantGarde-Book", 15, new Color("#333"));
//kép kiechozása
$d->Draw();
?>