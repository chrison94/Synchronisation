
## Konfiguration der WebHooks

### MOCO-Software

Webhook erstellen für:
    - Create Project
    - Update Project
    
Die URL entspricht dem Modul/Template, worin sich folgender Code befindet: 

```php
<?php
header("HTTP/1.1 200 OK");
$addon = rex_addon::get('MocoTrello');
$addonsPath = rex_path::src('addons');

include_once $addonsPath.'/MocoTrello/vendor/Schnittstelle/webhook_moco.php';
```


in dem REDAXO hier wäre dies das Template webhook_moco und die URL https://ch-g.net/index.php?article_id=1

### Modul/Template für Trello
Die URL für den Trello-WebHook entspricht dem Modul/Template, worin sich folgender Code befindet: 

```php
<?php
header("HTTP/1.1 200 OK");
$addon = rex_addon::get('MocoTrello');
$addonsPath = rex_path::src('addons');

include_once $addonsPath.'/MocoTrello/vendor/Schnittstelle/webhook.php';
```

in dem REDAXO hier wäre dies das Template webhook und die URL https://ch-g.net/index.php?article_id=3

### Konfiguration des Add-On

#### MOCO

    - Der MOCO-Api-Key ist der Schlüssel, den du unter deinem Profil bei Integrationen findest
    - In dem Feld MOCO-Adresse kommt der Name deiner Organisation hin.. also einfach nur 36pixel
    - in das Feld Trello-Mitarbeiter-ID trägst du dann deine persönliche ID von Trello ein - meine ist 5a68ac26ee06670bc5e51f70 - du findest die, indem du an die URL des Boards die Endung .json dran hängst. Da solltest du dann, wenn du nach idMemberCreator suchst, deine und die deiner Mitarbeiterin finden
    - in das Feld MOCO-Mitarbeiter-Name trägst du einfach den Vornamen aus MOCO von dir und deiner Mitarbeiterin dann in das zweite Feld ein

#### Trello

    - Trello-Api-Key und Token erklärt sich denke ich von selbst
    - Esperanto-Board-ID ist halt die id deines Esperanto-Boards, die findest du genau wie zuvor, indem du .json an die Webseite hängst. Direkt am anfang steht dann z.B. ID: 5b7bdb71dba2ab0edbac9b41 -> diese da eintragen
    - gleiches gilt für Kunden-Board-ID
    
#### Synchronisation
    - Die Knöpfe erklären sich eigentlich von selbst, Kunden synchronisiert die Kunden von Moco->Trello (mit meinem API-Key nicht, da ich kein Zugriff drauf habe, musst du deinen eintragen :-)), Webhook erstellt den Webhook
    - Unten in dem Block kannst du die WebHooks konfigurieren. Da trägst du die ID des Board ein (Esperanto/Normales) und dann die Adresse, an die der Webhook gesendet werden soll (siehe Modul/Template für Trello). Daraufhin klickst du auf Speichern und erst danach auf den Knopf Webhook

