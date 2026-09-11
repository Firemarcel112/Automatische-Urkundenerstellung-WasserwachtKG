<img src="public/images/logo.png">

<h1>Urkundenportal Wasserwacht</h1>

<h2>Technische Vorraussetzungen</h2>
<ul>
<li>PHP 8.4</li>
<li>PHP Extensions
    <ul>
    <li>curl</li>
    <li>fileinfo</li>
    <li>mbstring</li>
    <li>openssl</li>
    <li>pdo_sqlite</li>
    <li>zip</li>
    </ul>
</li>
</ul>

<h2>Einrichtung</h2>
<p>innerhalb des Ordner Verzeichnis <code>php /resources/bin/composer.phar install --no-dev</code> [Wenn Composer global installiert dann aufruf global ohne Pfadangabe]</p>

<p>Sobald alle Pakete installiert sind</p>
<code>php artisan app:install</code>
<p>Wenn der Prozess abgeschlossen ist startet die Anwendung automatisch auf <a href="http://localhost:8000">http://localhost:8000</a></p>
