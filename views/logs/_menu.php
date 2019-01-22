<?php
use yii\helpers\Url;

$action = $this->context->action->id;
?>
<ul class="nav nav-tabs">
    <li <?= ($action==='index') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/logs']) ?>"><?= Yii::t('easyii', 'Logs') ?></a></li>
</ul>
<br/>
