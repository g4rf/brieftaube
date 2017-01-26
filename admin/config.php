<h1><?=_('Konfiguration')?></h1>

<p>
    <?=_('Zur Zeit wird nur SMTP über <b>SSL</b> unterstützt.')?>
</p>
<?php
$message = '';
switch(filter_input(INPUT_POST, 'action')) {
    case 'save':
        Config::set('dbServer', filter_input(INPUT_POST, 'dbServer'));
        Config::set('dbPort', filter_input(INPUT_POST, 'dbPort'));
        Config::set('dbUser', filter_input(INPUT_POST, 'dbUser'));
        Config::set('dbPassword', filter_input(INPUT_POST, 'dbPassword'));

        Config::set('smtpServer', filter_input(INPUT_POST, 'smtpServer'));
        Config::set('smtpPort', filter_input(INPUT_POST, 'smtpPort'));
        Config::set('smtpUser', filter_input(INPUT_POST, 'smtpUser'));
        Config::set('smtpPassword', filter_input(INPUT_POST, 'smtpPassword'));
        Config::set('smtpFrom', filter_input(INPUT_POST, 'smtpFrom'));

        Config::set('mailSignature', filter_input(INPUT_POST, 'mailSignature'));

        Config::set('webTitle', filter_input(INPUT_POST, 'webTitle'));
        Config::set('webText', filter_input(INPUT_POST, 'webText'));
        Config::set('webUrl', filter_input(INPUT_POST, 'webUrl'));
        
        $message = _('Daten gespeichert.');
        break;
    case 'testSmtp':
        if(Brieftaube::send(Config::get()['smtpFrom'],
                _('Brieftaube: Test der SMTP-Verbindung'),
                _('Wenn diese E-Mail angekommen ist, ist die SMTP-Verbindung'
                        . ' korrekt eingerichtet. \o/'),
                $error)) {
            $message = sprintf(_('Der Server meldete keine Fehlermeldung. Bitte'
                    . ' prüfe das Postfach <u>%s</u> auf den Eingang der'
                    . ' Testmail.'), Config::get()['smtpFrom']);
        } else {
            $message = _('Ein Fehler ist aufgetreten: ') . $error;
        }
        break;
}
if(strlen($message)) {
    ?><div class="message"><?=$message?></div><?php
}
?>

<form method="post">
    <?php foreach(Config::get() as $key => $value) {
        ?><label>
            <span><?=$key?></span>
            <?php if (preg_match('/text|signature/Ui', $key)) { ?>
                <textarea name="<?=$key?>"><?=$value?></textarea>
            <?php } elseif (preg_match('/password/Ui', $key)) { ?>
                <input type="password" name="<?=$key?>" value="<?=$value?>" />
            <?php } else { ?>
                <input type="text" name="<?=$key?>" value="<?=$value?>" />
            <?php } ?>
        </label><?php
    } ?>
    <button type="submit"><?=_('Speichern')?></button>
    
    <input type="hidden" name="action" value="save" />
</form>

<form method="post">
    <button type="submit"><?=_('SMTP testen')?></button>
    <span>&nbsp;&nbsp;<?=_('Geänderte Daten vorher bitte speichern.')?></span>
    <input type="hidden" name="action" value="testSmtp" />
</form>