<div>List of late payment accounts as @ <?= date('Y-m-d H:i:s') ?></div>
<div style="float: left; width: 100%;">
<table style="width: 100%;">
    <tr>
        <th>Year</th>
        <th>Month</th>
        <th>Amount charged</th>
        <th>Tenant</th>
    </tr>
<?php
foreach ($data as $dataItem)
{
    ?><tr>
        <td><?= $dataItem['year'] ?></td>
        <td><?= $dataItem['month'] ?></td>
        <td><?= $dataItem['amountcharged'] ?></td>
        <td><?= $dataItem['tenantname'] ?></td>
    </tr>  
<?php
}
?>
</table>
</div>    