<?php
// Fichier temporaire pour générer un hash bcrypt de mot de passe.
// À SUPPRIMER une fois utilisé : ne pas laisser ce fichier en ligne.

$hash = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['pwd'])) {
    $hash = password_hash($_POST['pwd'], PASSWORD_BCRYPT);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Générateur de hash</title>
</head>
<body style="font-family: sans-serif; max-width: 600px; margin: 3rem auto;">
    <h1>Générateur de hash bcrypt</h1>
    <form method="POST">
        <label>Mot de passe : <input type="text" name="pwd" required></label>
        <button type="submit">Générer</button>
    </form>
    <?php if ($hash): ?>
        <p><strong>Hash à coller dans <code>userpwd</code> :</strong></p>
        <pre style="background:#eee; padding:1rem; word-break:break-all;"><?= htmlspecialchars($hash) ?></pre>
    <?php endif; ?>
    <p style="color:#b00; margin-top:2rem;">⚠️ Supprime ce fichier (generate-hash.php) une fois le hash récupéré.</p>
</body>
</html>
