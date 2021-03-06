<?php
use Cake\Core\Configure;
echo $this->Html->script(['storeLocation.autocomplete'], ['block' => true]);
echo $this->Html->script(['https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&language=pt-BR&key=' . Configure::read('Gmaps.API')], ['block' => true]);
$this->Html->scriptStart(['block' => true]);
echo <<<EOF
showPreview('{$store->lat_lng}', '{$store->lat_lng}');
EOF;
$this->Html->scriptEnd();

?><nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $store->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $store->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="stores form large-9 medium-8 columns content">
    <?= $this->Form->create($store) ?>
    <fieldset>
        <legend><?= __('Edit Store') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('address', ['type' => 'text']);
            echo $this->Form->input('lat', ['readonly']);
            echo $this->Form->input('lng', ['readonly']);
        ?>
        <img id="preview">
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
