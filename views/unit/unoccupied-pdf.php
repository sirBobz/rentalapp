<div style="margin-bottom: 5px;"><b>COBS rent collection</b></div>
<div style="margin-bottom: 10px;">List of unoccupied units as @ <?= date('Y-m-d H:i:s') ?></div>
<div style="float: left; width: 100%;">
<table style="width: 100%;">
    <tr>
        <th>Unit name</th>
        <th>Property</th>
        <th>Owner</th>
    </tr>
<?php
foreach ($data as $dataItem)
{
    ?><tr>
        <td><?= $dataItem['name'] ?></td>
        <td><?= $dataItem['propertyname'] ?></td>
        <td><?= $dataItem['propertyowner'] ?></td>
    </tr>  
<?php
}
?>
</table>
</div>    