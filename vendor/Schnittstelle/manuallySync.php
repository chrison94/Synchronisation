<?php
if(isset($_POST['kunden'])) {
    include_once 'Schnittstelle/createUser.php';
}
if(isset($_POST['projects'])) {
    include_once 'Schnittstelle/synchronisation.php';
}
if(isset($_POST['check'])) {
    include_once 'Schnittstelle/checkProjects.php';
}
if(isset($_POST['webhook'])) {
    include_once 'Schnittstelle/createWebhook.php';
}

?>
<html>

<head>
    <link rel="stylesheet" href="/css/hind/hind.css">
    <link rel="stylesheet" href="/css/style.css">
    <title>36Pixel</title>
</head>

<body>
    <div class="sitewrapper">
        <div class="trenner"></div>
        <div class="contentwrapper">
            <h1> Synchronisation </h1>
            <div class="formwrapper">
                <form action="index.php?secret=<?=$secret?>" method="POST">
                    <input type="hidden" name="act" value="kunden">
                    <input class="synch_button" type="submit" name="kunden" value="Kunden">
                </form>
            </div>
            <div class="formwrapper">
                <form action="index.php?secret=<?=$secret?>" method="POST">
                    <input type="hidden" name="act" value="projects">
                    <input class="synch_button" type="submit" name="projects" value="Projekte">
                </form>
            </div>
        </div>
        <div class="trenner"></div>
        <div class="contentwrapper">
            <h1> Überprüfen der Trello-Projektkarten </h1>
            <div class="formwrapper">
                <form action="index.php?secret=<?=$secret?>" method="POST">
                    <input type="hidden" name="act" value="check">
                    <input class="synch_button" type="submit" name="check" value="Überprüfen">
                </form>
            </div>
        </div>
        <div class="trenner"></div>
        <div class="contentwrapper">
            <h1> Webhook erstellen </h1>
            <form action="index.php?secret=<?=$secret?>" method="post">
                <input type="hidden" name="act" value="run">
                <div class="inputFieldWrapper">
                    <label class="inputName" for="modelID">Trello Board-ID</label>
                    <input class="inputField" type="text" name="modelID">
                </div>
                <div class="inputFieldWrapper">
                    <label class="inputName" for="adresse">Webseite</label>
                    <input class="inputField" type="text" name="adresse">
                </div>
                <div class="inputFieldWrapper">
                    <label class="inputName" for="trelloKey">Trello API-Key</label>
                    <input class="inputField" type="text" name="trelloKey">
                </div>
                <div class="inputFieldWrapper">
                    <label class="inputName" for="trelloToken">Trello API-Token</label>
                    <input class="inputField" type="text" name="trelloToken">
                </div>
                <input class="submit_button" type="submit" name="webhook" value="Erstellen">
            </form>
        </div>
        <div class="trenner2"></div>
    </div>

</body>

</html>