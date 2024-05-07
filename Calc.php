<?php
session_start();

function calcular($numero1, $operacao, $numero2) {
    switch ($operacao) {
        case '+':
            return $numero1 + $numero2;
        case '-':
            return $numero1 - $numero2;
        case '*':
            return $numero1 * $numero2;
        case '/':
            return $numero2 != 0 ? $numero1 / $numero2 : "Erro: divisão por zero";
        case '^':
            return pow($numero1, $numero2);
        case '!':
            return gmp_fact($numero1);
        default:
            return "Operação inválida";
    }
}

function armazenarHistorico($texto) {
    if (!isset($_SESSION["historico"])) {
        $_SESSION["historico"] = [];
    }
    array_push($_SESSION["historico"], $texto);
}

function salvarNaMemoria($valor) {
    $_SESSION["memoria"] = $valor;
}

function pegarDaMemoria() {
    if (isset($_SESSION["memoria"])) {
        $_SESSION["tela"] = $_SESSION["memoria"];
        unset($_SESSION["memoria"]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["numero1"], $_POST["operacao"], $_POST["numero2"])) {
        $numero1 = $_POST["numero1"];
        $operacao = $_POST["operacao"];
        $numero2 = $_POST["numero2"];

        $resultado = calcular($numero1, $operacao, $numero2);
        $texto = "$numero1 $operacao $numero2 = $resultado";

        $_SESSION["tela"] = $texto;
        armazenarHistorico($texto);
    }

    if (isset($_POST["salvar"])) {
        salvarNaMemoria($_SESSION["tela"]);
    }

    if (isset($_POST["pegar_valores"])) {
        pegarDaMemoria();
    }

    if (isset($_POST["memoria"])) {
        if (isset($_SESSION["memoria"])) {
            pegarDaMemoria();
        } else {
            salvarNaMemoria($_SESSION["tela"]);
        }
    }

    if (isset($_POST["limpar_historico"])) {
        unset($_SESSION["historico"], $_SESSION["memoria"], $_SESSION["tela"]);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>Calculadora PHP</title>
</head>
<body>
    <h1>Calculadora PHP</h1>
    <form method="post">
        <label for="numero1">Número 1:</label>
        <input type="text" name="numero1" id="numero1" required><br><br>

        <label for="operacao">Operação:</label>
        <select name="operacao" id="operacao" required>
            <option value="+" selected>+</option>
            <option value="-">-</option>
            <option value="*"></option>
            <option value="/">/</option>
            <option value="^">^</option>
            <option value="!">n!</option>
        </select><br><br>

        <label for="numero2">Número 2:</label>
        <input type="text" name="numero2" id="numero2" required><br><br>

        <input type="submit" name="calcular" value="Calcular">
    </form>

    <br>
    <h2>Tela:</h2>
    <div id="tela">
        <?php
        if (isset($_SESSION["tela"])) {
            echo $_SESSION["tela"];
        }
        ?>
    </div>

    <br>
    <form method="post">
        <input type="submit" name="salvar" value="Salvar">
        <input type="submit" name="pegar_valores" value="Pegar Valores">
        <input type="submit" name="memoria" value="Memória">
        <input type="submit" name="limpar_historico" value="Apagar Histórico">
    </form>

    <br>
    <h2>Histórico:</h2>
    <div id="historico">
        <?php
        if (isset($_SESSION["historico"])) {
            foreach ($_SESSION["historico"] as $op) {
                echo $op . "<br>";
            }
        }
        ?>
    </div>
</body>
</html>
