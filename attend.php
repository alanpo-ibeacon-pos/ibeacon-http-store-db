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

    if ($rssi < -40) die(0);

    $db = new PDO('mysql:host=localhost;dbname=attendance', 'ib_attendance', '你就想呀');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $q_dev = $db->prepare("SELECT pid FROM participant_devices WHERE uuid = :uuid && major = :major && minor = :minor");
    $q_dev->execute(array(
        ':uuid' => pack("H*", $uuid),
        ':major' => $major,
        ':minor' => $minor
    ));
    if($q_dev->rowCount() != 1) die(0);
    $dev_p_link_result = $q_dev->fetch(PDO::FETCH_NUM);
    if (!$dev_p_link_result) die(0);
    $this_pid = $dev_p_link_result[0];

    $q_event = $db->prepare("SELECT eid FROM events RIGHT JOIN location_detector ON events.locid = location_detector.locid WHERE startDateTime <= now() && endDateTime >= now() && detectorMac = :selfMac");
    $q_event->execute(array(
        ':selfMac' => hexdec($selfMac)
    ));
    if ($q_event->rowCount() != 1) die(0);
    $events_result = $q_event->fetch(PDO::FETCH_NUM);
    if (!$q_event) die(0);
    $this_eid = $events_result[0];

    $new_attended = $db->prepare("INSERT INTO attended(eid, pid) VALUES (:eid, :pid) ON DUPLICATE KEY UPDATE last_detected = NOW()");
    $new_attended->execute(array(
        ':eid' => $this_eid,
        ':pid' => $this_pid
    ));
    $affected_rows = $new_attended->rowCount();
    if ($affected_rows < 1) http_response_code(500);
    else echo $affected_rows;
} catch (Exception $e) {
    http_response_code(500);
    echo $e;
    die(1);
}