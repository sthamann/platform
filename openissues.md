## Allgemein

- An fälschich verwendeten Stellen "Rating" auch intern in "Review" umbenennen - insbesondere Modul-Prefix. Das Rating ist nur ein Teil der Review und daher wollten wir da auch die Begriffe korrekt verwenden um nicht maximal zu verwirren. Das Modul heißt "Reviews" ("Kundenbewertungen" / "Rezensionen") und ein Element einer Review ist das Rating ("(Be-)Wertung") sowie der Title ("Titel") und der Content ("Inhalt)

## Developer
- Create Demo Reviews to Products 

## View Daten

- Anzahl an Reviews mit 1, 2, 3, 4, 5 Punkten für das Produkt
- Average Rating des Produkts
- Anzahl an Reviews des Produkts 
- Anzahl an Reviews in anderen Sprachen
- Info zur Review ob Kunde das Produkt gekauft hat
- Anzahl an Likes zur Review
- Aktuelle Seite der Review-Liste (Paging) 
- Restriction der Review-Form Action auf eingeloggte User
- Email und Name als Parameter bei der Abgabe einer Bewertung entfernen
- Kundenname aus seinen Daten laden und zur Review ausgeben
- Information ob Kunde das Produkt im Shop gekauft hat zuer Bewertung ausgeben
- Soll Anzahl an Bewertungen pro Page in der Liste konfigurierbar sein? Wenn ja, dann brauchen wir den Config-Wert. Mein Vorschlag wären einfach 10 pro Seite hart mit reinzunehmen. 

## AJAX Actions

- Review-Login: Wird statt der Review-Form angezeigt wenn User nicht eingeloggt. Soll per AJAX den User einloggen und im Erfolgsfall im Anschluss dann die Review-Form anzeigen. 
- FilterByPoints: Reviews sollen per AJAX nach Punkten gefiltert werden können. Sind keine Filter gesetzt, so werden alle Einträge angezeigt, sind ein oder mehrer Optionen gewählt, so werden nur die Einträge der gewählten Optionen angezeigt (ODER)
- FilterByLanguage: Im Default möchten wir nur Reviews in der Sprache des Users anzeigen. D.h. wir müssen die Lokalisierung des Users übergeben um nur die Einträge in seiner Sprache zu erhalten. Um das Verhalten zu steuern benötigen wir die obigen Daten bezüglich Bewertungen gesamt und Bewertungen in anderen Sprachen. 
- SortBy: Im Default sollen die Bewertungen nach Anzahl an Likes, Datum sortiert werden. Zusätzlich gibt es noch die Option nur nach Datum zu sortieren.
- Paging: initial werden nur die ersten 10 Reviews und somit Seite 1 der Liste geladen. Die weiteren Seiten werden dann per AJAX nachgeladen. 
- Like: Eingeloggte User sollen die Möglichkeit haben Reviews zu liken. Ebenso wird dem User angezeigt welche Einträge er bereits geliked hat und hat dann entsprechend die Möglichkeit wieder zu entliken. 

## Admin

- "Positive" + "Negative" Felder entfernen sofern nicht benötigt
- Switch zum Aktivieren der Review weiter nach oben (gerne in Spalte neben dem  Status-Flag)
- Anzahl an Likes ausgeben und/oder die Customer als Liste ausgeben die Review geliked haben 
- Bewertungen zum Produkt in der Detail des Produkts anzeigen (dachte ich hätte das bei Timo schon gesehen - evtl nicht committed)
