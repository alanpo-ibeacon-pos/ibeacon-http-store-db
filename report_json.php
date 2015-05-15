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
    if (empty($_POST['jsonData'])) throw new Exception('jsonContent is empty()');

    $jsonObj = json_decode($_POST['jsonData']);

    $db = new PDO('mysql:host=moodle-db.cndunymmm6cz.ap-southeast-1.rds.amazonaws.com:3306;dbname=2014fyp_ips', '2014fyp_ips', 'alanpo2593');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    if(!$db->beginTransaction()) throw new Exception('transcational operation is not supported');

    $stmt = $db->prepare("INSERT INTO traces(datetime, selfMac,`uuid`,major,minor,mac,txpower,rssi) VALUES(:datetime, :selfMac,:uuid,:major,:minor,:mac,:txpower,:rssi)");
    foreach ($jsonObj as $el) {
        $stmt->execute(array(
            ':datetime' => $el->datetime,
            ':selfMac' => hexdec($el->selfMac),
            ':uuid' => pack("H*", $el->uuid),
            ':major' => $el->major,
            ':minor' => $el->minor,
            ':mac' => hexdec($el->mac),
            ':txpower' => $el->txpower,
            ':rssi' => $el->rssi));
    }

    $db->commit();

    $affected_rows = $stmt->rowCount();
    if ($affected_rows != count($jsonObj)) http_response_code(500);
    else echo $affected_rows;
} catch (Exception $e) {
    http_response_code(500);
    echo $e;
    die(1);
}

