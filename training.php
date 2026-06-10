<!DOCTYPE html>
<html>
<body>
<?php
$message = "Hello, World!";

echo "<h1>$message</h1>";

echo "<script>console.log('$message');</script>";
?>

<?php
// ============================================================
// Secvența: Verificare numere pare și impare dintr-un array
// Flow-control utilizate: for și if
// ============================================================

$numere = [4, 7, 12, 3, 8, 15, 22, 9, 6, 11];

$pare   = 0;
$impare = 0;

echo "<h2>Verificare numere din array:</h2>";
echo "<hr>";

for ($i = 0; $i < count($numere); $i++) {
    if ($numere[$i] % 2 == 0) {
        echo "Numărul " . $numere[$i] . " este <strong>PAR</strong><br>";
        $pare++;
    } else {
        echo "Numărul " . $numere[$i] . " este <em>IMPAR</em><br>";
        $impare++;
    }
}

echo "<hr>";
echo "<p>Total numere <strong>PARE</strong>: $pare</p>";
echo "<p>Total numere <em>IMPARE</em>: $impare</p>";
?>

</body>
</html>