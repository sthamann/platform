## Allgemein

## Database
- es fehlt noch die reviews_attributes zum Hinzufügen von cutome attributes (wie z.B. externalReviewId) (ERLEDIGT)
    - Was meinst du damit genau? Die Tabelle product_review hat ja bereits ein JSON Field "attributes", das sollte auch bereits funktionieren
    - **Todo:** Attributes wurden in custom_fields umbenannt, beim nächsten Merge mit dem Master müssen wir das auch bei den Reviews glattziehen
    - Perfekt (SOMIT erstmal DONE)
    
## Developer
- Review API
    - **Todo:** In Product/SalesChannel/ habe ich schon einen SalesChannelProductController angelegt, der muss aber noch mit Leben gefüllt werden
    
- Review Events
    - Wir sollten Systemevents an den wichtigsten Stellen werfen
    - **Info:** Es gibt nun folgende System-/DAL-Events
        - product_review.loaded
        - product_review.written
        - product_review.deleted
        - product_review.search.result.loaded
        - product_review.aggregation.result.loaded
        - product_review.id.search.result.loaded
    - **Todo:** Neben dem Business-Event review.pagelet.loaded.event müssten noch weitere in der Code-Basis definiert werden (Neue Bewertung, Freigegebene Bewertung etc.)
    - **Vorschlag:**
        - product_review.published
        - product_review.unpublished
        - product_review.comment
                 
- Code-Qualität
    - **Info:** Ich habe die Service-Klasse nach Core/Product/SalesChannel verschoben
    - **Info:** Ich habe die Ermittlung des Bewertungsdurchschnitts in einen Indexer überführt (ProductRatingAverageIndexer), der den Schnitt ermittelt und in das Feld rating_average an das Produkt schreibt
    - **Todo:** Aktuell ist das so das der bei einem Varianten-Produkt den Durchschnitt immer über alle Varianten errechnet, das müsste man ggf. später konfigurierbar machen
    - **Info:** Die Bewertungsmatrix wird jetzt im ProductPageLoader ermittelt, an der selben Stelle könnte man z.b. auch nach Sprache oder Verified Status gruppieren
    - **Todo:** Josh, du müsstest mal schauen ob du mit der Matrix so klar kommst oder ob wir die php-seitig noch anders aufbauen müssen

- Mögliche spätere Erweiterung:
    - Da man den Inhalt der Bewertung nicht verändern kann, sollte man den Kunden kontaktieren können, also eine E-Mail schreiben, dass seine Bewertung nicht ganz korrekt ist und so nicht veröffentlicht werden kann. Man bräuchte dann nur je Review ein Button "Review by user" mit einer Angabe des Grundes (Shop bewertet, Beledigungen, etc.)
## View Daten

- Anzahl an Reviews in anderen Sprachen 
    - **Todo**  Siehe oben, das lässt sich relativ einfach lösen in dem man analog zur Bewertungsmatrix an der selben Stelle nach language_id gruppiert
    
- Info zur Review ob Kunde das Produkt gekauft hat
    - **Info** Das ist grundsätzlich drin im Code (RatingService) habe aber noch nicht getestet ob das funktioniert und es fehlt noch die passende Datenbank-Spalte (verified_buyer)

- Email und Name als Parameter bei der Abgabe einer Bewertung entfernen
    - Die Felder stehen weiterhin in der Datenbank (später konfigurierbar ob anonyme Bewertungen erlaubt sind)
    - In der Storefront sind die schon ausgebaut und nicht eingeloggte User können keine Bewertung schreiben

- Kundenname aus seinen Daten laden und zur Review ausgeben
    - Das müsste eigentlich schon gehen, da ja die Review mit der Customer-Tabelle verknüpft ist - falls nicht bitte melden

- Soll Anzahl an Bewertungen pro Page in der Liste konfigurierbar sein? Wenn ja, dann brauchen wir den Config-Wert. Mein Vorschlag wären einfach 10 pro Seite hart mit reinzunehmen.~~
    - Wurde mittels Konstante (10) gelöst
    - **Todo** Müssen wir später im Einstellungsdialog noch konfigurierbar machen  
    
## AJAX Actions

- Review-Login: Wird statt der Review-Form angezeigt wenn User nicht eingeloggt. Soll per AJAX den User einloggen und im Erfolgsfall im Anschluss dann die Review-Form anzeigen. 
    --> Kümmert sich @Uli drum
    
- FilterByPoints: Reviews sollen per AJAX nach Punkten gefiltert werden können. Sind keine Filter gesetzt, so werden alle Einträge angezeigt, sind ein oder mehrer Optionen gewählt, so werden nur die Einträge der gewählten Optionen angezeigt (ODER)
    --> Kümmert sich @Uli drum
    
- FilterByLanguage: Im Default möchten wir nur Reviews in der Sprache des Users anzeigen. D.h. wir müssen die Lokalisierung des Users übergeben um nur die Einträge in seiner Sprache zu erhalten. Um das Verhalten zu steuern benötigen wir die obigen Daten bezüglich Bewertungen gesamt und Bewertungen in anderen Sprachen. 
    --> Kümmert sich @Uli drum
    
- SortBy: Im Default sollen die Bewertungen nach Anzahl an Likes, Datum sortiert werden. Zusätzlich gibt es noch die Option nur nach Datum zu sortieren.
    --> Kümmert sich @Uli drum
    
## Admin
- **Info** Aaron hat von mir die aktuellen Admin-Sichten bekommen, wir sollten das Product-Design Feedback abwarten

- Generell fehlt noch die komplette Konfigurierbarkeit - das müssen wir implementieren sobald die Grundeinstellungen fertig drin sind, im Idealfall kann man dann pro
Saleschannel entscheiden ob man Reviews erlauben will und falls ja welche Detailkonfiguration man möchte
    - Spannend wären: Bewertungen Ja / Nein, Bewertungen nur für eingeloggte User, Anzahl Bewertungen pro Seite, Sprachabhängige Bewertunge ausblenden, Varianten Bewertungen zusammenfassen, ...
- Switch zum Aktivieren der Review weiter nach oben (gerne in Spalte neben dem  Status-Flag)
    --> Noch offen und sollte noch verschoben werden
- Bewertungen zum Produkt in der Detail des Produkts anzeigen (dachte ich hätte das bei Timo schon gesehen - evtl nicht committed)
    - Ist in irgendeiner kaputten form drin, ggf. nehmen wir Timo mit in die Whatsapp Gruppe ?
    --> Timo ist drin und wenn ich das richtig sehe, dann besteht das "Problem" weiterhin
    
