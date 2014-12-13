<?php
?>
<div>
    Hey, we want to verify that you are indeed <?= $emailaddress ?>. If you wish to continue, please follow the link below:<br />
    <a href="http://localhost/yii2-rentalapp/web/index.php/site/verify-email?hash=<?= $hash ?>&email=<?= $emailaddress ?>">confirm verification</a>
</div>