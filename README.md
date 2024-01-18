# EXT:static_export

Die Extension "static_export" ist für den Export der TYPO3 Webseite gedacht. Dabei werden Seiten aufrufe im Frontend aufgezeichnet, die Dokumente anhand des Pfades im Datei System gespeichert und einem Preprocessing unterzogen. Das Preproessing entfernt unnötige Teile des Markups, sodass die gespeicherte HTML Datei schin möglichst klein ist.

Über ein Backend Modul oder über einen Scheduler Task, kann dann ein Export und ein Publish Mechanismus angestoßen werden. Der Export Prozess packt alle gesammelten Seiten inkl. der dadrin verlinkten Assets zusammen in ein ZIP Archiv. Bei dem Publish Prozess wird dieses Archiv in einen anderen Storage verschoben (jeder FAL Trieber möglich: z.B. Azure BLOB Storage). Über das Backend Modul, kann das Export Archiv auch heruntergeladen werden, um dieses zu prüfen. Der Scheduler Task automatisiert den Proess "Export & Publish" und stellt sicher, dass die lokale TYPO3 Instanz nicht vollläuft indem ältere Exports entfernt und nur die neusten 14 behalten werden.

Damit die aufgezeichneten Seiten immer den aktuellen Stand der Webseite abbilden, wird die EXT:crawler Extension konfiguriert und benutzt. Hier kann ebenfalls ein Scheduler Task benutzt werden, um z.B. in der Nacht die Seiten Basis zu aktualisieren.
