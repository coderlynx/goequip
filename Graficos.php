<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Graficos</title>
    <?php include('estilos.html') ?>
    <style>
        table {
            text-align: center;
        }
        
        table thead{
            background-color: #3875FF !important;
            color: #FFF !important;
        }
        
        table thead tr th{
            text-align: center;
        }
    
    </style>
</head>
<body>
   <p>Elija que tipo de reporte le interesa</p>
  
        <input id="radio1"  name="filtro" data-filtro="VentasDiarias" value="1" type="radio"/><label for="radio1">Diaria</label>
        <input id="radio2"  name="filtro" data-filtro="VentasSemanales" value="2" type="radio"/><label for="radio2">Semanal</label>
        <input id="radio3"  name="filtro" data-filtro="VentasMensuales" value="3" type="radio"/><label for="radio3">Mensual</label>
        <input id="radio4"  name="filtro" data-filtro="VentasAnuales" value="4" type="radio"/><label for="radio4">Anual</label>

     <div style="margin:10px;">
        <label>Fecha Desde</label>
        <input id="fechaDesde" type="date"  />
        <label>Fecha Hasta</label>
        <input id="fechaHasta" type="date"  />
        
        <input id="btnArmarGrafico" type="button" value="Graficar" />
    </div>

    <div id="div_grafico_y_tabla" style="width: 80%; margin: 0 auto;">
        <div id="container_grafico" >
        </div>
        <div id="div_tabla_resultado_totales" style="margin-top:20px;">
<!--
            <table id="tabla_resultado_totales" style="margin: 0 auto; text-align: center;" class="table table-striped table-bordered table-condensed table-hover">
            </table>
-->
        </div>
    </div>

    <?php include('scripts.html') ?>
     <script src="js/lib/highcharts/highcharts.js"></script>
     <script src="js/lib/highcharts/exporting.js"></script>
     <script src="js/lib/highcharts/offline-exporting.js"></script>
     <script src="js/Graficos.js"></script>
     <script src="js/lib/Grilla.js"></script>
     <script>
         $(document).ready(function(){
             Graficos.init();
    
        });
   
    
     </script>
</body>
</html>