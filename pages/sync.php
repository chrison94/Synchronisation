<?php
$content = '';
$buttons = '';

$csrfToken = rex_csrf_token::factory('MocoTrello');
$addon = rex_addon::get('MocoTrello');

// Einstellungen speichern
if (rex_post('formsubmit', 'string') == '1' && !$csrfToken->isValid()) {
    echo rex_view::error(rex_i18n::msg('csrf_token_invalid'));
} elseif (rex_post('formsubmit', 'string') == '1') {
    $addon->setConfig(rex_post('config', [
        ['modelID', 'string'],
    ]));
    $addon->setConfig(rex_post('config', [
        ['webhookLink', 'string'],
    ]));

    echo rex_view::success($this->i18n('config_saved'));
}
            // PROJECTS BUTTON
                $output = '';
                $formElements = [];
                $n = [];
                $n['field'] = '<button class="btn rex-form-aligned" type="submit" name="projects" value="projekte">Projekte</button>';
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
                $fragment->setVar('title', $this->i18n('config'));
                $fragment->setVar('buttons', $buttons, false);
                $output = $fragment->parse('core/page/section.php');

                $output = '
                <form action="' . rex_url::currentBackendPage() . '" method="post">
                <input type="hidden" name="formsubmit" value="1" />
                ' . $csrfToken->getHiddenField() . '
                ' . $output . '
                </form>';

                echo $output;

            // CUSTOMER BUTTON
                $output = '';
                $formElements = [];
                $n = [];
                $n['field'] = '<button class="btn rex-form-aligned" type="submit" name="kunden" value="kunden">Kunden</button>';
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
                $fragment->setVar('title', $this->i18n('config'));
                $fragment->setVar('buttons', $buttons, false);
                $output = $fragment->parse('core/page/section.php');

                $output = '
                <form action="' . rex_url::currentBackendPage() . '" method="post">
                <input type="hidden" name="formsubmit" value="1" />
                ' . $csrfToken->getHiddenField() . '
                ' . $output . '
                </form>';

                echo $output;

            // CHECK ID-BUTTON
                $output = '';
                $formElements = [];
                $n = [];
                $n['field'] = '<button class="btn rex-form-aligned" type="submit" name="check" value="check">Überprüfe Projekte</button>';
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
                $fragment->setVar('title', $this->i18n('config'));
                $fragment->setVar('buttons', $buttons, false);
                $output = $fragment->parse('core/page/section.php');

                $output = '
                <form action="' . rex_url::currentBackendPage() . '" method="post">
                <input type="hidden" name="formsubmit" value="1" />
                ' . $csrfToken->getHiddenField() . '
                ' . $output . '
                </form>';

                echo $output;

            // CREATE WEBHOOK-BUTTON
                $fragment = '';
                $output = '';
                $formElements = [];
                $n = [];
                $n['field'] = '<button class="btn rex-form-aligned" type="submit" name="webhook" value="webhook">Webhook</button>';
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
                $fragment->setVar('title', $this->i18n('config'));
                $fragment->setVar('buttons', $buttons, false);
                $output = $fragment->parse('core/page/section.php');

                $output = '
                <form action="' . rex_url::currentBackendPage() . '" method="post">
                <input type="hidden" name="formsubmit" value="1" />
                ' . $csrfToken->getHiddenField() . '
                ' . $output . '
                </form>';

                echo $output;
            // WEBHOOK CONFIGURATION

                // Trello-Board-ID
                $output = '';
                $formElements = [];
                $n = [];
                $n['label'] = '<label for="modelID">Board-ID</label>';
                $n['field'] = '<input class="form-control" type="text" id="modelID" name="config[modelID]" value="' . $this->getConfig('modelID') . '"/>';
                $formElements[] = $n;

                $fragment = new rex_fragment();
                $fragment->setVar('elements', $formElements, false);
                $content .= $fragment->parse('core/form/container.php');

                $formElements = [];
                $n = [];
                $n['label'] = '<label for="webhookLink">Link für Webhooks</label>';
                $n['field'] = '<input class="form-control" type="text" id="webhookLink" name="config[webhookLink]" value="' . $this->getConfig('webhookLink') . '"/>';
                $formElements[] = $n;

                $fragment = new rex_fragment();
                $fragment->setVar('elements', $formElements, false);
                $content .= $fragment->parse('core/form/container.php');


              // Save-Button
                $formElements = [];
                $n = [];
                $n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="save" value="' . $this->i18n('config_save') . '">' . $this->i18n('config_save') . '</button>';
                $formElements[] = $n;

               $fragment = new rex_fragment();
                $fragment->setVar('elements', $formElements, false);
                $buttons = $fragment->parse('core/form/submit.php');


                // Ausgabe Formular
                $fragment = new rex_fragment();
                $fragment->setVar('class', 'edit');
                $fragment->setVar('title', $this->i18n('config'));
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



            ?>