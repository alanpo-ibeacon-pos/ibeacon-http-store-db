<?php

if (count($_POST) == 0) {
    http_response_code(500);
    echo '
　　　　　　　　　 ／　.:.:.:.:.:／:.:.:.:＼:.:.:.:.:.:{:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:{　　 ヽ　　 　 ｝                    <br/>
　　　　　　--=彡 .:.:.:.:.:.／:.:.:.:.:.:.:.:.: ＼:.:.:., :.:.:.:.:.:.:.:.:.:.:.:.:.: { 　 ＼ ｀¨¨¨¨ヽ                    <br/>
　　　　　 　 ／.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:{　　　 ＼　 　 ﾉ      <br/>
　　　　　　　.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.ﾊ　　　　 ヽ　/     <br/>
　　　　　/ ｲ:.:.:.:.:.:/:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.:.|､:.:.:.:.:.:.:.:.:.:.:.:.:.ﾊ　　　　　ｲ          <br/>
　　　　　ﾘ ′ .:.:.:′:.:.:.:.:.:.:.:.:ハ:.:.:.:.:.:.:.:.:.:.:.:.|斗:ぃ.jﾄＶ:.:.:.:.:.:.:.＼___／:.: ＼                     <br/>
　　　　　　}/:.:.:.:j:.:.:.:.:.:.:.:.:.:.:/⌒!:.:.:.:.:ハ:.:.:.:jlﾆリﾆリﾆＶ:.:.:.:.:.:.:.:.:.:.:.、:.:.:.:.　:,           <br/>
　　　　　　|:.:.:.:.:.|:.:.:.:.:|:.:.:.:./ﾆﾆj八:.:j二∨:.j| .ィ示㍉,_ :.:.:.:.:.:.:.:.:.:.:. }＼:.:. : }ﾊ                  <br/>
　　　　　　|:.ﾊ :.:|:.:.:.:.:|:.:.{/ﾆﾆﾆﾆﾆリﾆ／ゞﾘ　{イh}::} ’∨:.:.:.:.リ:. : }　　v:. }:ﾊ                                  <br/>
　　　　　　リ.ｲ:.:ﾊ:八:|:.:.|=ﾆﾆﾆﾆニ／　 　′.乂いﾉ 　 }:.:.:.:.:.:{:.:.:.ﾘ　　 }:. } ﾘ                                      <br/>
　　　　　　 / {:.:.:.:.:.|:.:.Ｗ二ﾆﾆﾆ／ 　 ,　　 /::/::/::/::　/、:.:.:.:.:.:.:.:{　 　 }:. }                              <br/>
　　　 　 　 |　 〉 :.:.:|:.:.:.|､_｀¨¨¨´　　　　　　　　　　 　/ィ:.:}＼:.:.:.:.:}　　,ﾉイ                                   <br/>
　　　 　 　 |　{:.:.ﾊ:.:＼j|ﾊﾍ　　　　　　，､　　　　　　ｲﾊ:.(｀　 ヾ:.:.:j                                                  <br/>
.　　　　 　 　 乂{ ＼:.:{＼ ゞ＞　　　　　　　　　 ィf/ ﾘ 　＿_____}ノ                                                       <br/>
　　　　　　　　　　＿ヾ〉_　　　 r‐}＞　__　＜ｲハ____,／　　　　　＼                                                          <br/>
　　 　 　 　 　 ／　 　 　 ｀ヽ_/: : ＼,.. ＜´: : : :./ ／:i:i:i:i:i:i:i:i:i:i:i:i:　 ＼                                   <br/>
　　　　　　　 / : : : : : : : ／￣〉γ:ぃ ､ : : : : ｨ　/⌒ヽ:i:i:i:i:i:i:i:i:i:i:i:i:　　ゝ,，― ､                            <br/>
　　 　 　 　 / : : : : : : :./イ 　 ノ{:::::}.}　＼／:.|　j⌒!　}:i:i:i:i:i:i:i:i:i:i:i:i:i:i:i:／.....--..}               <br/>
.　 　 　 　 /: : : : : :{: :∧　　　　 ｰ彳 　　ヽ: :l　乂ﾉ .ﾉ:i:i:i:i:i:i:i:i:i:i:i:ｉ:／..／.........ﾉ                      <br/>


NONONO 㗎';
    die(1);
}

try {
    $selfMac = $_POST["selfMac"];
    $uuid = $_POST["uuid"];
    $major = (int)$_POST["major"];
    $minor = (int)$_POST["minor"];
    $mac = $_POST["mac"];
    $txpower = (int)$_POST["txpower"];
    $rssi = (int)$_POST["rssi"];

    $db = new PDO('mysql:host=localhost;dbname=ibeacon_traces', 'ibeacon', '1Beac0n');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $stmt = $db->prepare("INSERT INTO traces(selfMac,`uuid`,major,minor,mac,txpower,rssi) VALUES(:selfMac,:uuid,:major,:minor,:mac,:txpower,:rssi)");
    $stmt->execute(array(
        ':selfMac' => hexdec($selfMac),
        ':uuid' => pack("H*", $uuid),
        ':major' => $major,
        ':minor' => $minor,
        ':mac' => hexdec($mac),
        ':txpower' => $txpower,
        ':rssi' => $rssi));

    $affected_rows = $stmt->rowCount();
    if ($affected_rows < 1) http_response_code(500);
    else echo $affected_rows;
} catch (Exception $e) {
    http_response_code(500);
    echo $e;
    die(1);
}

