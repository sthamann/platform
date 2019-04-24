## Allgemein

~~- An fälschich verwendeten Stellen "Rating" auch intern in "Review" umbenennen - insbesondere Modul-Prefix. Das Rating ist nur ein Teil der Review und daher wollten wir da auch die Begriffe korrekt verwenden um nicht maximal zu verwirren. Das Modul heißt "Reviews" ("Kundenbewertungen" / "Rezensionen") und ein Element einer Review ist das Rating ("(Be-)Wertung") sowie der Title ("Titel") und der Content ("Inhalt)~~

## Developer
~~- Create Demo Reviews to Products~~ 
- Review API
    - Ich denke wir müssen die Rating Funktionalität auch in der Storefront API bereitstellen, habe aber noch keine Ahnung wo ich da ansetzen muss
- Review Events
    - Wir sollten Systemevents an den wichtigsten Stellen werfen
- Code-Qualität
    - Da sind noch ein paar ekelige Stellen drin, wo wir nochmal gucken müssen, das wir das sauberer machen, z.B. Ablageort des RatingService etc.

## View Daten

~~- Anzahl an Reviews mit 1, 2, 3, 4, 5 Punkten für das Produkt~~

~~- Average Rating des Produkts~~ 

~~- Anzahl an Reviews des Produkts~~ 

- Anzahl an Reviews in anderen Sprachen 
    - Für den ersten Wurf sollten wir ggf. eine Konfigurationsoption Bewertungen nur in User-Sprache anzeigen Ja / Nein nehmen und das später weiter ausbauen
- Info zur Review ob Kunde das Produkt gekauft hat
    - Jepp, baue ich noch ein
- Anzahl an Likes zur Review
    - Ich würde die Likes im DB-Schema drin lassen (weil coole erste Erweiterung) die aber aus dem "Prototypen" rauslassen
- Aktuelle Seite der Review-Liste (Paging) 
    - Paging, das müsste ja per Ajax laufen, da müssen wir gucken wie wir das machen - im Augenblick holt er ja immer alle Bewertungen eines Produkts immer wenn ein Produkt geladen wird,
    also z.B. auch im Listing - das ist aus Performance-Sicht wahrscheinlich noch nicht so ideal - müssen wir diskutieren, weiß auch nicht wo die Ajax Controller liegen

~~- Restriction der Review-Form Action auf eingeloggte User~~

- Email und Name als Parameter bei der Abgabe einer Bewertung entfernen
    - Kannst du aus der Storefront rausnehmen, das wird dann fix im Service gehandelt
- Kundenname aus seinen Daten laden und zur Review ausgeben
    - Ich würde das aus Performancegründen beim Speichern statisch in die Datenbank schreiben und nicht dynamisch laden
  
~~- Information ob Kunde das Produkt im Shop gekauft hat zuer Bewertung ausgeben~~ < DUPLIKAT
- Soll Anzahl an Bewertungen pro Page in der Liste konfigurierbar sein? Wenn ja, dann brauchen wir den Config-Wert. Mein Vorschlag wären einfach 10 pro Seite hart mit reinzunehmen. 
    - Erstmal statisch
## AJAX Actions

- Review-Login: Wird statt der Review-Form angezeigt wenn User nicht eingeloggt. Soll per AJAX den User einloggen und im Erfolgsfall im Anschluss dann die Review-Form anzeigen. 
- FilterByPoints: Reviews sollen per AJAX nach Punkten gefiltert werden können. Sind keine Filter gesetzt, so werden alle Einträge angezeigt, sind ein oder mehrer Optionen gewählt, so werden nur die Einträge der gewählten Optionen angezeigt (ODER)
- FilterByLanguage: Im Default möchten wir nur Reviews in der Sprache des Users anzeigen. D.h. wir müssen die Lokalisierung des Users übergeben um nur die Einträge in seiner Sprache zu erhalten. Um das Verhalten zu steuern benötigen wir die obigen Daten bezüglich Bewertungen gesamt und Bewertungen in anderen Sprachen. 
- SortBy: Im Default sollen die Bewertungen nach Anzahl an Likes, Datum sortiert werden. Zusätzlich gibt es noch die Option nur nach Datum zu sortieren.
- Paging: initial werden nur die ersten 10 Reviews und somit Seite 1 der Liste geladen. Die weiteren Seiten werden dann per AJAX nachgeladen. 

~~- Like: Eingeloggte User sollen die Möglichkeit haben Reviews zu liken. Ebenso wird dem User angezeigt welche Einträge er bereits geliked hat und hat dann entsprechend die Möglichkeit wieder zu entliken.~~ 

## Admin

~~- "Positive" + "Negative" Felder entfernen sofern nicht benötigt~~ Sollten wir erstmal rauslassen

- Switch zum Aktivieren der Review weiter nach oben (gerne in Spalte neben dem  Status-Flag)

~~- Anzahl an Likes ausgeben und/oder die Customer als Liste ausgeben die Review geliked haben~~ Sollten wir erstmal rauslassen

- Bewertungen zum Produkt in der Detail des Produkts anzeigen (dachte ich hätte das bei Timo schon gesehen - evtl nicht committed)
    - Ist in irgendeiner kaputten form drin, ggf. nehmen wir Timo mit in die Whatsapp Gruppe ?
    