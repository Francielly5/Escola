<?php
session_start();

// inicia lista na sessão
if (!isset($_SESSION['alunos'])) {
    $_SESSION['alunos'] = [];
}

$alunos = &$_SESSION['alunos'];

// ================= FUNÇÕES =================

function media($n1, $n2) {
    return ($n1 + $n2) / 2;
}

function frequencia($faltas) {
    return 100 - $faltas;
}

function situacao($media, $freq) {
    if ($media >= 7 && $freq >= 75) {
        return "Aprovado";
    }
    return "Reprovado";
}

// ================= CADASTRAR =================
if (isset($_POST['cadastrar'])) {
    $alunos[] = [
        "mat" => $_POST['mat'],
        "nome" => $_POST['nome'],
        "n1" => floatval($_POST['n1']),
        "n2" => floatval($_POST['n2']),
        "faltas" => intval($_POST['faltas'])
    ];
}

// ================= EXCLUIR =================
if (isset($_GET['del'])) {
    foreach ($alunos as $i => $a) {
        if ($a['mat'] == $_GET['del']) {
            unset($alunos[$i]);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Alunos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Cadastro de Alunos</h2>

<form method="POST">
    Matrícula: <input name="mat" required><br>
    Nome: <input name="nome" required><br>
    Nota 1: <input name="n1" type="number" step="0.1" required><br>
    Nota 2: <input name="n2" type="number" step="0.1" required><br>
    Faltas: <input name="faltas" type="number" required><br><br>

    <button type="submit" name="cadastrar">Cadastrar</button>
</form>

<hr>

<h2>Lista de Alunos</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Matrícula</th>
    <th>Nome</th>
    <th>Média</th>
    <th>Frequência</th>
    <th>Situação</th>
    <th>Ação</th>
</tr>

<?php foreach ($alunos as $a): 
    $m = media($a['n1'], $a['n2']);
    $f = frequencia($a['faltas']);
    $s = situacao($m, $f);
?>
<tr>
    <td><?= $a['mat'] ?></td>
    <td><?= $a['nome'] ?></td>
    <td><?= number_format($m,1) ?></td>
    <td><?= $f ?>%</td>
    <td><?= $s ?></td>
    <td>
        <a href="?del=<?= $a['mat'] ?>">Excluir</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<hr>

<?php
// ================= ESTATÍSTICAS =================
$total = count($alunos);
$soma = 0;
$maior = null;
$menor = null;

foreach ($alunos as $a) {
    $m = media($a['n1'], $a['n2']);
    $soma += $m;

    if ($maior == null || $m > media($maior['n1'], $maior['n2'])) {
        $maior = $a;
    }

    if ($menor == null || $m < media($menor['n1'], $menor['n2'])) {
        $menor = $a;
    }
}

$mediaTurma = $total ? $soma / $total : 0;
?>

<h3>Estatísticas</h3>

<p>Total de alunos: <?= $total ?></p>
<p>Média da turma: <?= number_format($mediaTurma,1) ?></p>

<?php if ($maior): ?>
<p>Maior média: <?= $maior['nome'] ?></p>
<?php endif; ?>

<?php if ($menor): ?>
<p>Menor média: <?= $menor['nome'] ?></p>
<?php endif; ?>

</body>
</html>