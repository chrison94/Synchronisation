<?php

$content = '';
$buttons = '';

$csrfToken = rex_csrf_token::factory('MocoTrello');

// Einstellungen speichern
if (rex_post('formsubmit', 'string') == '1' && !$csrfToken->isValid()) {
    echo rex_view::error(rex_i18n::msg('csrf_token_invalid'));
} elseif (rex_post('formsubmit', 'string') == '1') {
    $this->setConfig(rex_post('config', [
        ['trello_key', 'string'],
    ]));
    $this->setConfig(rex_post('config', [
        ['trello_token', 'string'],
    ]));
    $this->setConfig(rex_post('config', [
        ['trello_key_co', 'string'],
    ]));
    $this->setConfig(rex_post('config', [
        ['trello_token_co', 'string'],
    ]));

    $this->setConfig(rex_post('config', [
        ['esperantoBoard', 'string'],
    ]));
    $this->setConfig(rex_post('config', [
        ['regelBoard', 'string'],
    ]));
    $this->setConfig(rex_post('config', [
        ['syncOK', 'string'],
    ]));
}

$content .= '<fieldset><legend>Trello-Konfiguration</legend>';

// Trello-Key
$formElements = [];
$n = [];
$n['label'] = '<label for="trello_key">Trello-API-Key</label>';
$n['field'] = '<input class="form-control" type="text" id="trello_key" name="config[trello_key]" value="' . $this->getConfig('trello_key') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// Trello-Token
$formElements = [];
$n = [];
$n['label'] = '<label for="trello_token">Trello-API-Token</label>';
$n['field'] = '<input class="form-control" type="text" id="trello_token" name="config[trello_token]" value="' . $this->getConfig('trello_token') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// Trello-Key-co
$formElements = [];
$n = [];
$n['label'] = '<label for="trello_key_co">Trello-API-Key-Co-Worker</label>';
$n['field'] = '<input class="form-control" type="text" id="trello_key_co" name="config[trello_key_co]" value="' . $this->getConfig('trello_key_co') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// Trello-Token-co
$formElements = [];
$n = [];
$n['label'] = '<label for="trello_token_co">Trello-API-Token-Co-Worker</label>';
$n['field'] = '<input class="form-control" type="text" id="trello_token_co" name="config[trello_token_co]" value="' . $this->getConfig('trello_token_co') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// Esperanto-Board
$formElements = [];
$n = [];
$n['label'] = '<label for="esperantoBoard">Esperanto-Board-ID</label>';
$n['field'] = '<input class="form-control" type="text" id="esperantoBoard" name="config[esperantoBoard]" value="' . $this->getConfig('esperantoBoard') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// Regel-Board
$formElements = [];
$n = [];
$n['label'] = '<label for="regelBoard">Kunden-Board-ID</label>';
$n['field'] = '<input class="form-control" type="text" id="regelBoard" name="config[regelBoard]" value="' . $this->getConfig('regelBoard') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

/* SyncOK-ID
$formElements = [];
$n = [];
$n['label'] = '<label for="syncOK">SyncOK-Label-ID</label>';
$n['field'] = '<input class="form-control" type="text" id="syncOK" name="config[syncOK]" value="' . $this->getConfig('syncOK') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php'); */
//save
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
</fieldset>
';

// Ausgabe Formular
$fragment = new rex_fragment();
$fragment->setVar('class', 'edit');
$fragment->setVar('title', 'Konfiguration');
$fragment->setVar('body', $content, false);
$fragment->setVar('buttons', $buttons, false);
$output = $fragment->parse('core/page/section.php');$output = '
<form action="' . rex_url::currentBackendPage() . '" method="post">
<input type="hidden" name="formsubmit" value="1" />
    ' . $csrfToken->getHiddenField() . '
    ' . $output . '
</form>
';

echo $output;

