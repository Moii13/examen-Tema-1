<?php
//abrimos la sesion y le ponemos nombre
session_start();
session_name("ud1_23");


//boton de limpiar
if(isset($_POST['limpiar'])){
    session_destroy();
    header("Location:".$_SERVER['PHP_SELF']);
}
//version
if(!isset($_SESSION['version'])){
    $_SESSION['version']=0;
}
$version=$_SESSION['version'];
$version++;
$_SESSION['version']=$version;

//se carga la lista si no esta iniciada
if(!isset($_SESSION['lista'])){
    $_SESSION['lista'] = [];
}
$list=$_SESSION['lista'];

//cargamos los valores a la lista
if(isset($_POST['referencia']) && isset($_POST['concepto']) && isset($_POST['unidades']) && isset($_POST['precio_unidad'])) {
    $ref = $_POST['referencia'];
    $con = $_POST['concepto'];
    $ud =(int) $_POST['unidades'];
    $pre =(double) $_POST['precio_unidad'];
    //manejamos lo posibles errores de los valores
    if (!empty($ref) && !empty($con) && $ud > 0 && $pre >= 0) {
        $list[] = [
            "referencia" => $ref,
            "unidades" => $ud,
            "precio_unidad" => $pre,
            "concepto" => $con
        ];


    }else $error=true;
}else $noHayvalores=true;

//Botones - y +
if (isset($_POST['mas']) || isset($_POST['menos'])) {
    $error=false;
    $noHayvalores=false;

    $refRecibida = isset($_POST['mas']) ? $_POST['mas'] : $_POST['menos'];
    if (isset($_POST['mas'])) {
        $boton = "mas";
    } else $boton = "menos";
    for ($i = 0; $i < count($list); $i++) {
        if ($list[$i]['referencia'] == $refRecibida) {
            if ($boton == "mas") {
                $list[$i]['unidades']++;
            }
            if ($boton == "menos" && $list[$i]['unidades'] > 1) {
                $list[$i]['unidades']--;
            }
        }
    }
}

//Eliminar concepto
if(isset($_POST['eliminarC'])){
    $error=false;
    $noHayvalores=false;

    $refRecibida = $_POST['eliminarC'];
    for ($i=0; $i < count($list); $i++) {
        if ($list[$i]['referencia'] == $refRecibida) {
            unset($list[$i]);
            break;
        }

    }
}


//se carga al final toda la lista
$_SESSION['lista']=$list;




?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>generar.php</title>
</head>
<body>
<h1>Albaran</h1>
<h2>Version: <?php echo $version  ?></h2>
<table border="1">
    <tr>
        <th></th>
        <th>Uds.</th>
        <th>Referencia</th>
        <th>Concepto</th>
        <th>Precio ud.</th>
        <th>Subtotal</th>
    </tr>
    <?php
    //generacion de la tabla
    if(count($list)>0) {
        $bruto = 0;
        for ($i = 0; $i < count($list); $i++) {
            print "<tr>";
            print "<td>" . ($i + 1) . "</td>";
            print "<td>";
            print '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
            print '<button type="submit" name="mas" value="'.$list[$i]['referencia'] .'">+</button>';
            print  "" . $list[$i]['unidades'];
            print '<button type="submit" name="menos" value="'.$list[$i]['referencia'] .'">-</button></td>';
            print '</form>';
            print "<td>" . $list[$i]['referencia'] . "</td>";
            print "<td>" . $list[$i]['concepto'] . "</td>";
            print "<td>" . $list[$i]['precio_unidad'] . "</td>";
            $subtotal = $list[$i]['precio_unidad'] * $list[$i]['unidades'];
            print "<td>" . $subtotal . "</td>";
            print "<td>";
            print '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
            print '<button type="submit" name="eliminarC" value="'.$list[$i]['referencia'] .'">ELIMINAR CONCEPTO</button></td>';

            print "</tr>";
            $bruto += $subtotal;
        }
        $totalUnidades = 0;
        foreach ($list as $i) {
            $totalUnidades += $i['unidades'];
        }
        print "<tr>";
        print "<td></td>";
        print "<td>$totalUnidades</td>";
        print "<td colspan='3'>Bruto:</td>";
        print "<td>$bruto</td>";
        print "</tr>";

        if ($bruto >= 3000) {
            $descuento = $bruto * 10 / 100;
            print "<td colspan='5'>Descuento (20%)</td>";
        } else if ($bruto < 3000 && $bruto >= 2000) {
            $descuento = $bruto * 10 / 100;
            print "<td colspan='5'>Descuento (10%)</td>";
        } else {
            $descuento = 0;
            print "<td colspan='5'>No hay descuento</td>";
        }
        print "<td>-" . $descuento . " €</td>";
        print "</tr>";

        $iva=($bruto-$descuento)*21/100;
        print "<tr>";
        print "<td colspan='5'>IVA:</td>";
        print "<td>".$iva." €</td>";
        print "</tr>";

        $neto=$bruto-$descuento+$iva;
        print "<tr>";
        print "<td colspan='5'>Neto:</td>";
        print "<td>$neto €</td>";
        print "</tr>";

    }
    ?>


</table>
<br>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    Referencia: <input type="text" name="referencia">
    Concepto: <input type="text" name="concepto">
    Unidades: <input type="number" name="unidades" step="1">
    Precio unidad: <input type="number" name="precio_unidad" step="0.01">
    <input type="submit" value="Nuevo Concepto">
    <input type="submit" value="Limpiar albaran" name="limpiar">

</form>
<?php
//control de errores
if ($noHayvalores) {
    print ("<h2>Introduce todos los valores</h2>");
}
if ($error) {
    print "<h2>Error no se han introducido los valores de forma correcta</h2>";
}

?>

</body>
</html>
