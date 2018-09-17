<?php

$content = '';
$buttons = '';

$csrfToken = rex_csrf_token::factory('MocoTrello');

// Einstellungen speichern
if (rex_post('formsubmit', 'string') == '1' && !$csrfToken->isValid()) {
    echo rex_view::error(rex_i18n::msg('csrf_token_invalid'));
} elseif (rex_post('formsubmit', 'string') == '1') {
    $this->setConfig(rex_post('config', [
        ['moco_key', 'string'],
    ]));
    $this->setConfig(rex_post('config', [
        ['moco_workspace', 'string'],
    ]));
    $this->setConfig(rex_post('config', [
        ['moco_worker_moco_id', 'string'],
    ]));
    $this->setConfig(rex_post('config', [
        ['moco_worker_moco_name', 'string'],
    ]));
    $this->setConfig(rex_post('config', [
        ['moco_co_worker_moco_id', 'string'],
    ]));
    $this->setConfig(rex_post('config', [
        ['moco_co_worker_moco_name', 'string'],
    ]));
}

$content .= '<fieldset><legend>MOCO Konfiguration</legend>';

// MOCO-Key
$formElements = [];
$n = [];
$n['label'] = '<label for="moco_key">MOCO-API-Key</label>';
$n['field'] = '<input class="form-control" type="text" id="moco_key" name="config[moco_key]" value="' . $this->getConfig('moco_key') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');
// MOCO-workspace
$formElements = [];
$n = [];
$n['label'] = '<label for="moco_workspace">MOCO-Adresse</label>';
$n['field'] = '<input class="form-control" type="text" id="moco_workspace" name="config[moco_workspace]" value="' . $this->getConfig('moco_workspace') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');


// MOCO-Worker
    // Trello ID //
$formElements = [];
$n = [];
$n['label'] = '<label for="moco_worker_moco_id">Trello-Mitarbeiter-ID</label>';
$n['field'] = '<input class="form-control" type="text" id="moco_worker_moco_id" name="config[moco_worker_moco_id]" value="' . $this->getConfig('moco_worker_moco_id') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');
    // MOCO name //
$formElements = [];
$n = [];
$n['label'] = '<label for="moco_worker_moco_name">MOCO-Mitarbeiter-Name</label>';
$n['field'] = '<input class="form-control" type="text" id="moco_worker_moco_name" name="config[moco_worker_moco_name]" value="' . $this->getConfig('moco_worker_moco_name') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');


// MOCO-Worker
$formElements = [];
$n = [];
$n['label'] = '<label for="moco_co_worker_moco_id">Zweite-Trello-Mitarbeiter-ID</label>';
$n['field'] = '<input class="form-control" type="text" id="moco_co_worker_moco_id" name="config[moco_co_worker_moco_id]" value="' . $this->getConfig('moco_co_worker_moco_id') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');
    // MOCO name //
$formElements = [];
$n = [];
$n['label'] = '<label for="moco_co_worker_moco_name">Zweiter-MOCO-Mitarbeiter-Name</label>';
$n['field'] = '<input class="form-control" type="text" id="moco_co_worker_moco_name" name="config[moco_co_worker_moco_name]" value="' . $this->getConfig('moco_co_worker_moco_name') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

$formElements = [];
$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="save" value="' . $this->i18n('config_save') . '">' . $this->i18n('config_save') . '</button>';
$formElements[] = $n;
$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');
$buttons = '
<fieldset class="rex-form-action">
' . $buttons . '
</fieldset>';
// Ausgabe Formular
$fragment = new rex_fragment();
$fragment->setVar('class', 'edit');
$fragment->setVar('title', 'Konfiguration');
$fragment->setVar('body', $content, false);
$fragment->setVar('buttons', $buttons, false);
$output = $fragment->parse('core/page/section.php');

$output = '
<form action="' . rex_url::currentBackendPage() . '" method="post">
<input type="hidden" name="formsubmit" value="1" />
' . $csrfToken->getHiddenField() . '
' . $output . '
</form>';

echo $output;