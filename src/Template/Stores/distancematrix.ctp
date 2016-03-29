<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Store'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="stores index large-9 medium-8 columns content">
    <h3><?= __('Stores') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= __('id') ?></th>
                <th><?= __('name') ?></th>
                <th><?= __('lat_lng') ?></th>
                <th><?= __('distance') ?></th>
                <th><?= __('duration') ?></th>
                <th><?= __('created') ?></th>
                <th><?= __('modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stores as $store): ?>
            <tr>
                <td><?= $this->Number->format($store->id) ?></td>
                <td><?= h($store->name) ?></td>
                <td><?= h($store->lat_lng) ?></td>
                <td><?= h($store->distance['text']) ?></td>
                <td><?= h($store->duration['text']) ?></td>
                <td><?= h($store->created) ?></td>
                <td><?= h($store->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $store->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $store->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $store->id], ['confirm' => __('Are you sure you want to delete # {0}?', $store->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
