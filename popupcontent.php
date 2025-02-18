<?php
include './database/dbh.php';
$coded = $_GET['id'];
$theitems = "SELECT * FROM `parts` WHERE taken = '$coded'";
$theitemsresult = $conn->query($theitems);
$itemcount = 0;
while ($rowitems = $theitemsresult->fetch_assoc()) {
    $itemcount = $itemcount + 1;
?>
    <span class="itemno">
        &nbsp; <span class="badge badge-pill badge-info"><?php echo $itemcount; ?></span>
    </span>
    <span class="itemsku"><?php echo $rowitems['online_code']; ?></span>
    <span class="itemcode"><?php echo $rowitems['acc_code']; ?></span>
    <span class="cancelitem"><i class="bi bi-x" onclick='cancelitem(<?php echo $rowitems['id'] . ',' . $coded; ?>)'></i></span>


    <br>
<?php
}
$thecancelleditems = "SELECT * FROM `cancel` WHERE order_number = '$coded'";
$cancelleditemsresult = $conn->query($thecancelleditems);
$cancount = 0;
while ($carowitems = $cancelleditemsresult->fetch_assoc()) {
    $cancount = $cancount + 1;
    if ($cancount == 1 && $itemcount > 0) {
        echo '<hr>';
    }
?>
    <span class="itemno">
        &nbsp; <span class="badge badge-pill badge-danger"><?php echo $cancount; ?></span>
    </span>
    <span class="itemsku" style="width: 200px;text-align:left;">&nbsp;<?php echo $carowitems['online_code']; ?><span class="cancelledbadge">(cancelled)</span>
    </span>
    <br>
<?php } ?>