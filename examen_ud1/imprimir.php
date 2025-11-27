<?php
//incluyo el php de datos y utilizo la variable global para cargar el array que hay en datos
include("datos.php");
global $conceptos;

?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Imprimir.php</title>
</head>
<body>
<table border="1">
    <tr>
        <th></th>
        <th>Uds</th>
        <th>Referencia</th>
        <th>Concepto</th>
        <th>Precio ud.</th>
        <th>Subtotal</th>
    </tr>
    <?php
    //for each para cargar la primera parte de la pagina
    $cont=0;
    $totalUnidades=0;
    $totalBruto=0;
    foreach ($conceptos as $i) {
        $cont++;
        print "<tr>";
        print "<td>".$cont."</td>";
        print "<td>".$i['unidades']."</td>";
        print "<td>".$i['referencia']."</td>";
        print "<td>".$i['concepto']."</td>";
        print "<td>".$i['precio_unidad']."</td>";
        $subtotal=$i['precio_unidad']*$i['unidades'];
        print "<td>".$subtotal." €</td>";
        print "</tr>";
        $totalUnidades+=$i['unidades'];
        $totalBruto+=$subtotal;

    }
    //Fila de el total de unidades y del bruto
    print "<tr>";
    print "<td></td>";
    print "<td>$totalUnidades </td>";
    print "<td colspan='3'>Bruto:</td>";
    print "<td>".$totalBruto." €</td>";
    print "</tr>";
    //Fila del descuento
    print "<tr>";
    //Condicion para determinar el descuento
    if($totalBruto>3000){
        $descuento=$totalBruto*10/100;
        print "<td colspan='5'>Descuento (20%)</td>";
    }else if($totalBruto<3000 && $totalBruto>2000){
        $descuento=$totalBruto*10/100;
        print "<td colspan='5'>Descuento (10%)</td>";
    }else{
        $descuento=0;
        print "<td colspan='5'>No hay descuento</td>";
    }
    print "<td>-".$descuento." €</td>";
    print "</tr>";

    //Fila del Iva
    $iva=($totalBruto-$descuento)*21/100;
    print "<tr>";
    print "<td colspan='5'>IVA:</td>";
    print "<td>".$iva." €</td>";
    print "</tr>";

    //Fila del neto
    $neto=$totalBruto-$descuento+$iva;
    print "<tr>";
    print "<td colspan='5'>Neto:</td>";
    print "<td>$neto</td>";
    print "</tr>";
    ?>

</table>

</body>
</html>

