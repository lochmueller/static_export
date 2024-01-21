# EXT:static_export

Die Extension "static_export" ist für den Export der TYPO3 Webseite gedacht. Dabei werden Seitenaufrufe im Frontend aufgezeichnet, die Dokumente anhand des Pfades im Dateisystem gespeichert und einem Preprocessing unterzogen. Das Preprocessing entfernt unnötige Teile des Markups, sodass die gespeicherten HTML-Dateien möglichst klein sind und keine unnötigen Informationen enthalten.

Über ein Backend Modul oder über einen Scheduler Task, kann dann ein Export und ein Publish Mechanismus angestoßen werden. Der Export-Prozess packt alle gesammelten Seiten inkl. der dadrin verlinkten Assets (Dokumente, CSS, JS etc.) zusammen in ein ZIP Archiv. Bei dem Publish Prozess wird dieses Archiv in einen anderen Storage verschoben (jeder FAL Treiber möglich: z.B. Azure BLOB Storage). Über das Backend Modul, kann das Export-Archiv auch heruntergeladen werden, um dieses zu prüfen. Der Scheduler Task automatisiert den Prozess "Export & Publish" und stellt sicher, dass die lokale TYPO3 Instanz nicht vollläuft, indem ältere Exports entfernt und nur die neusten 14 behalten werden.

Damit die aufgezeichneten Seiten immer den aktuellen Stand der Webseite abbilden, wird die EXT:crawler Extension konfiguriert und benutzt. Hier kann ebenfalls ein Scheduler Task benutzt werden, um z.B. in der Nacht die Seiten-Basis zu aktualisieren. 
