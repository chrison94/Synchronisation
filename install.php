<?php
rex_sql_table::get(rex::getTable('synchronisation'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('identifier', 'VARCHAR(255)', true))
    ->ensureColumn(new rex_sql_column('name', 'VARCHAR(255)', true))
    ->ensureColumn(new rex_sql_column('info', 'TEXT', true))
    ->ensureColumn(new rex_sql_column('active', 'tinyint(1)', true))
    ->ensureColumn(new rex_sql_column('Status', 'VARCHAR(255)', true))
    ->ensureColumn(new rex_sql_column('customer', 'VARCHAR(255)', true))
    ->ensureColumn(new rex_sql_column('user_id', 'INTEGER', true))
    ->ensureColumn(new rex_sql_column('moco_id', 'INTEGER', true))
    ->ensureColumn(new rex_sql_column('Intern', 'TEXT', true))
    ->ensure();

if (!$this->hasConfig()) {

}


$somethingIsWrong = false;
if ($somethingIsWrong) {
    throw new rex_functional_exception('Something is wrong');
}

if ($somethingIsWrong) {
    $this->setProperty('installmsg', 'Something is wrong');
    $this->setProperty('install', false);
}
