<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Graficos</title>
   
</head>
<body>
   <p>Elija que tipo de reporte le interesa</p>
  
        <input id="radio1"  name="filtro" data-filtro="getVentasDiarias" value="1" type="radio"/><label for="radio1">Diaria</label>
        <input id="radio2"  name="filtro" data-filtro="getVentasSemanales" value="2" type="radio"/><label for="radio2">Semanal</label>
        <input id="radio3"  name="filtro" data-filtro="getVentasMensuales" value="3" type="radio"/><label for="radio3">Mensual</label>
        <input id="radio4"  name="filtro" data-filtro="getVentasAnuales" value="4" type="radio"/><label for="radio4">Anual</label>

     <div style="margin:10px;">
        <label>Fecha Desde</label>
        <input id="fechaDesde" type="date"  />
        <label>Fecha Hasta</label>
        <input id="fechaHasta" type="date"  />
        
        <input id="btnArmarGrafico" type="button" value="Graficar" />
    </div>

    <div id="div_grafico_y_tabla" style="width: 80%; margin: 0 auto;">
        <div id="container_grafico_torta_totales" >
        </div>
        <div id="div_tabla_resultado_totales" style="margin-top:20px;">
            <table id="tabla_resultado_totales" style="margin: 0 auto;">
            </table>
        </div>
    </div>

    <script src="js/jquery-1.12.3.js"></script>
     <script src="js/highcharts/highcharts.js"></script>
     <script src="js/highcharts/exporting.js"></script>
     <script src="js/highcharts/offline-exporting.js"></script>
     <script src="js/Graficos.js"></script>
     <script src="js/Grilla.js"></script>
     <script>
         $(document).ready(function(){
             Graficos.init();
    
        });
   
    
     </script>
</body>
</html>