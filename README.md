# Ugye lángolt a szívünk!?
http://lelkinapok.jezsu.hu

## html szövegforrások tulajdonságai
A .html fájlok adják a kiadvány egy-egy fejezetét. Az index.php megjelenítéskor feldolgozza ezeket és a speciáls html tag jelöléseket megfelelőre alakítja.

Általában megengedett:
* `<h1><h2><h3><h4><h5><p>`
* `<a><strong><ul><li>`
* `<div>` - amennyiben becsukós rész jelöl közvetlenül valamilyen címsor után.
De nem használunk semmilyen `class` vagy `style` megjelölést. (Ahol ilyen lenne, azt ki kell szedni.)

Extra / sajátos jelölések:
* __`<quote>`__ és `</quote>`: Idézőjel nyitása illetve zárása. Nyomtatásban: » ill. «. Olykor szabálytalanul: más html tag-eken átívelve. 
* __`<otlet>`__: A lelkinapok szervezőinek szóló kiszólások és ötletek. (Legtöbb helyen helytelenül: `class="organizerTip"` vagy `<organizerTip>` vagy `<szervezonek>`. Ezeket le kell cserélni.)
* __`<tanacs>`__: A csoportvezetőknek szóló gondolatok. (Legtöbb helyen helytelenül: `<leaderTip>` vagy `<csopvez>` vagy `<organizerTip>` vagy `class="leaderTip"`. Ezeket le kell cserélni.)
* __`<jatek id=jatek_cimenek_neve />`__: Játékokat így kell megjelölni. Ezek alapján a jatekok.html-ből megszerzi a szkript a megfelelő játékokat és formába önti.
* __`<ido>`__: Valamilyen egység hosszára vonatkozó utalás. Címek végén nyomtatásban `|` vonal után valahány perc. A `</h1-5>` címsort lezáró tag előtt még. (Sokszor hibásan `<duration>` és akkor cserélni kell.)
* __`<cimkieg>`__: Címekhez tartozó megjegyzéseket jelöljük így. Egyféle alcím. Nyomtatásban a címsor után jön `‹` és `›` jelek között. Jelenleg a `</h1-5>` címsort lezáró tag elé kerül. Ez valószínűleg nem lesz jó, de maradjon még így.

Egyebek:
* Mindig törlendő tartalmával együtt: `<span class="time">8:25</span>` 
* Lelkinapok elején a kellékek puccos megjelenítését le kell takarítani egyszerű listákká, mint nyomtatásban.
* A nyomtatásban más betűtípussal szedett részek a html kódban komplex `<div class="card">` cuccban van. Ezt majd egyszerűsíteni kell, de még át kell gondolni hogyan. 
* Vannak `<footer>` és `<source>` elemek. Ezeket leegyszerűsíteni, hogy a nyomtatásos változattal egyezzen (+ link).
* Kb. minden mardék class definíció törlendő.


### További html tennivaló
* Mindegyik html fájlban legyen az elején `<title>` mező, ami a végső címnek felel majd meg. Egyéb iránt a `<head>`-ben bármi lehet, mert azt a szkript simán kitörli. (Tehát bele lehet tenni pl a bootstrap vagy a lelkinapok.css-t, hogy szépen nézzen ki szerkesztés közben is.)
* A `<h1-5>` címsorokba a nyomtatásból áthozni a sorszámozást.
* Az összes `<h1-5>` címsorba `id=` azonosítót kell berakni. Az azonosító lehet a cím számozásából megalkotva. Egyedi legyen az adott html-en belül.
* A __tartalomjegyzek.html__-t is htmlesíteni kell több szintű `<ul><li>`-vel. De oldalszámok helyett mindegyik legyen linkké és mutasson a `href="/fajl_neve_html_kiterjesztesnelekul#cimsor_azonositoja"` oldalra. Ez azért durva meló lesz. Nem a legsürgősebb.
