<?php
if ($amountRefunded > 0)
{
    ?>
    <p>The account has been successfully closed. Navigate to the link below to approve deposit refund which has been initiated for the tenant</p>
    <p>
        <?= \yii\helpers\Html::a('Deposit refunds for approval', ['/rental-account/deposit-refund-pending-approval']) ?>
    </p>
    <?php
}
 else {
     ?>
    <p>The account was successfully closed but there was no deposit to be refunded.</p>
<?php
}