<div class="box">
    <h2>O statystykach</h2>
    <p>Dane na stronie zostały opracowane na podstawie zrzutu z policyjnego Systemu Ewidencji Wypadków i Kolizji z okresu 2007-2016 udostępnionego przez Komendę Główną Policji dla sieci Miasta dla Rowerów.</p>
    <p>Interpretując zestawienia należy wziąć pod uwagę zdarzające się błędy w Systemie oraz fakt, że część zdarzeń nie jest zgłaszana.</p>
    <p>W przypadku znalezienia zdarzenia w którym brałeś/aś udział prosimy o <a href="mailto:karol@mocniak.pl">informację</a> o poprawności/błędach w statystykach.</p>
    Strona powstała dzięki współpracy z:
    <ul>
        <li><a href="http://miastadlarowerow.pl">Miasta dla rowerów</a></li>
        <li><a href="http://www.rowerowy.bialystok.pl">Rowerowy Białystok</a></li>
        <li><a href="http://www.fundacjafenomen.pl/">Fenomen - Fundacja Normalne Miasto</a></li>
    </ul>
</div>
<div class="box">
    <h2>Uwaga!</h2>
    <p>Wyszukiwarka jest w fazie testów</p>
    <h2>Opracowania i analizy</h2>
    <p>Niektóre z materiałów, które powstały na podstawie udostępnionych przez nas danych.</p>
    <ul>
        <li><a href="http://ibikekrakow.com/2012/03/31/pijani-sprawcy-wypadkow-kto-szkodzi-glownie-sobie-a-kto-innym-pijani-rowerzysci-jak-pijani-piesi/">Pijani sprawcy wypadków – kto szkodzi głównie sobie, a kto innym?</a></li>
        <li><a href="http://www.rowerowy.bialystok.pl/index.php?art=1241">Raport o bezpieczeństwie ruchu rowerowego w Białymstoku 2007-2010</a></li>
        <li><a href="http://polskanarowery.sport.pl/msrowery/1,105126,9232106,Bezpieczenstwo_rowerzystow_a_mitologia.html">Bezpieczeństwo rowerzystów a mitologia</a></li>
        <li><a href="http://rowerowalodz.pl/aktualnosci/482-lodzka-infrastruktura-i-polityka-rowerowa-raport-o-tym-co-jest-a-co-powinno-byc">Łódzka infrastruktura i polityka rowerowa: raport o tym, co jest, a co powinno być</a></li>
        <li><a href="http://www.rowerowe-leszno.pl/infrastruktura/raporty/71-raport-sewik-2007-2010">Zdarzenia drogowe z udziałem rowerzystów w latach 2007-2010 na terenie miasta Leszna</a></li>
    </ul>
</div>
<div class="box">
    <h2>Wyszukiwanie zdarzeń <sup><a href="?id=pomoc#wyszukiwanie_podstawy" title="pomoc">[?]</a></sup></h2>
    <form action="index.php?id=szukaj" method="post">
    <table class="szukaj">
    <tr><td>baza danych</td><td>
            <select name="baza">
        <option value="sewik_rowery">rowery</option>
        <option value="sewik_bialystok">bialystok</option>
        <option value="sewik_brzeg">brzeg</option>
        <option value="sewik_katowice">katowice</option>
        <option value="sewik_krakow">krakow</option>
        <option value="sewik_lodz">lodz</option>
        <option value="sewik_lublin">lublin</option>
        <option value="sewik_olsztyn">olsztyn</option>
        <option value="sewik_poznan">poznan</option>
        <option value="sewik_radom">radom</option>
        <option value="sewik_rzeszow">rzeszow</option>
        <option value="sewik_szczecin">szczecin</option>
        <option value="sewik_torun">torun</option>
        <option value="sewik_trojmiasto">trojmiasto</option>
        <option value="sewik_warszawa">warszawa</option>
        <option value="sewik_wroclaw">wroclaw</option>
    </select>
    </td></tr>
    <tr><td>ID zdarzenia:</td><td><input type="text" name="zdarzenie"></td></tr>
    <? //opt_in_tab ('dzielnica:','gmina'); ?>
    <? opt_in_tab ('województwo:','woj'); ?>
    <tr><td>miejscowość:</td><td><input type="text" name="miejscowosc"></td></tr>
    <tr><td>ulica (bez ul.)<sup><a href="?id=pomoc#wyszukiwanie_ulice" title="pomoc">[?]</a></sup>:</td><td><input type="text" name="ulica"></td></tr>
    <tr><td>od (yyyy-mm-dd):  <input type="text" name="data_od"></td><td>do (yyyy-mm-dd): <input type="text" name="data_do"></td></tr>
    <tr><td><p style="text-align: right;"><a href="index.php?id=zaaw">zaawansowane</a></p></td><td><input type="submit"></td></tr>
    </table>

    </form>
    <!-- <h2>Wyszukiwanie uczestników</h2>
    <form action="" method="post">
    <table class="szukaj">
    <tr><td>ID uczestnika:</td><td><input type="text" name="id_ucz"></td></tr>
    <tr><td>data urodzenia (yyyy-mm-dd):</td><td><input type="text" name="data_ur"></td></tr>
    <tr><td>płeć (K/M):</td><td><input type="text" name="plec"></td></tr>
    <tr><td></td><td><input type="submit" disabled="disabled"></td></tr>
    </table>

    </form> -->
    <p>Wyniki wyszukiwania są ograniczane do 10000 rekordów.</p>
</div>
