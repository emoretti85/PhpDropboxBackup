[IT]
1. Ottieni una copia dell'sdk di dropbox (https://www.dropbox.com/developers/core/sdks/php).
	IMPORTANTE!!!!!
		Se usi composer ricordati di modificare la require dell'autoload nel file dbb/Dbb.php alla riga:11

2. Crea una dropbox app (https://www.dropbox.com/developers/apps)
	IMPORTANTE!!!
	     Il link di redirect che inserirete nella dropbox app, dovrà essere il link alla pagina "dbb/Dbb_landing.php"
	
3. Modifica le configurazioni nel file dbb/Dbb_config.php di conseguenza

4.Creare il db e\o la tabella users => script "dbb.sql" 
(se hai necessità di modificare i puntamenti al db, puoi modificare il codice della classe Dbb nei metodi : getUserDetail() e setUserDetail())

Lanciando l'index file per la prima volta, verrete reindirizzati sui server di dropbox per confermare l'uso dell'applicazione(*) 
in seguito i file presenti nella cartella Backup verranno caricati sui server di dropbox nella cartella "Applicazioni/<nomeApp>/<your Dropbox_backupPath>"

(*) una volta recuperato, il token verrà scaricato e salvato nel db e i successivi upload non richiederanno più la conferma. 




[EN]

1. Get a copy of the SDK dropbox (https //www.dropbox.com/developers/core/sdks/php). 
	IMPORTANT !!!!! If you are using Composer remember to change the require dell'autoload file dbb / Dbb.php at line 11 

2. Create a Dropbox app (https //www.dropbox.com/developers/apps) 
	IMPORTANT !!! The link redirects you input into the Dropbox app, will be the link to the page 'dbb / Dbb_landing.php' 

3. Edit the configuration file dbb / Dbb_config.php

4.Create the db and \ or the users table => script 'dbb.sql' 
(if you need to change the tracking points to the database, you can modify the code of the class Dbb methods: getUserDetail () and setUserDetail ())

Launching the index file for the first time, you will be redirected on the servers of dropbox to confirm the use dell'applicazione(*) 

following the files in the Backup folder will be uploaded on the servers of dropbox folder in 'Applications/ <nomeapp> / <your Dropbox_ Backuppath>' 

(*) once recovered, the token will be downloaded and saved in the db and the subsequent upload does not require further confirmation. 
