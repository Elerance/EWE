<h1><?=__('title')?></h1>
<br>
<p><?=__('subtitle')?></p>
<div class="block">
    Проверка подключения к БД: <b><?=\App\Models\User::First()->Count() ? 'Подключение установлено' : 'Подключение не установлено'?></b>
    <code>
        \App\Models\User::First()
    </code>
</div>