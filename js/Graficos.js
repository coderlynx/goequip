var Graficos = {
    init: function() {
        var _this = this;
        
        //seteo los campos fecha a hoy
        var d = new Date();
        var month = d.getMonth()+1;
        var day = d.getDate();
        //tengo que setear un dia antes al campo fechaDesde porque sino el grafico no traia nada
        d.setDate(d.getDate() - 1);
        var dayBefore = d.getDate();
        
        var fechaDesdeDefault = d.getFullYear() + '-' +
            ((''+month).length<2 ? '0' : '') + month + '-' +
            ((''+day).length<2 ? '0' : '') + dayBefore;       
        var fechaHastaDefault = d.getFullYear() + '-' +
            ((''+month).length<2 ? '0' : '') + month + '-' +
            ((''+day).length<2 ? '0' : '') + day;
        
        
        $('#fechaDesde').attr("value", fechaDesdeDefault);
        $('#fechaHasta').attr("value", fechaHastaDefault);
        
        
        $('#btnArmarGrafico').click(function () {
            var tipo = $('[name="filtro"]:checked').data('filtro');
            if(!tipo) {
                alert("Debe seleccionar un reporte");
                return;
            }
            var fechaDesde = $('#fechaDesde').val();
            var fechaHasta = $('#fechaHasta').val();
            var titulo = "Ventas Anuales";
            var subTitulo = $('[name="filtro"]:checked').next().html();
            var divGrafico = 'container_grafico';
            var divTabla = 'div_tabla_resultado_totales';
            //var tabla = 'tabla_resultado_totales';
            _this.GraficoYTabla(tipo,fechaDesde,fechaHasta,subTitulo,divGrafico,divTabla);
        });
    },
    GraficoYTabla: function (tipo, fechaDesde,fechaHasta,  subTitulo, div_grafico, div_tabla) {
        var _this = this;
        
        var filtros = {
            tipo: tipo,//1 es mensual
            fechaDesde: fechaDesde,
            fechaHasta: fechaHasta
        };
        
        var jsonFiltros = JSON.stringify(filtros);
        
         $.post('php/controllerReporte.php', {filtros:jsonFiltros}, function(respuestaJson) {
            var rta = JSON.parse(respuestaJson);
            if(rta.length != 0) {
                _this.ArmarGrafico(rta, subTitulo, div_grafico);
                _this.DibujarTabla(rta, div_tabla);
            } else {
                alert("No hay datos para mostrar");
            }
        }).error(function(e){
                console.log('Error al ejecutar la petición por:' + e);
            }
        );
       
    },
    ArmarGrafico: function (resultado, subTitulo, div_grafico) {
        
        var categorias = [];
        var serie = [];
        
        $.each( resultado, function( key, value ) {
            categorias.push(value.mes);
            serie.push(parseInt(value.total));
        });
        
        $('#' + div_grafico).highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Ventas'
                    },
                    subtitle: {
                        text: subTitulo
                    },
                    xAxis: {
                        categories: categorias,
                    },
                    yAxis: {
                        min:0,
                        title: {
                            text: 'Facturacion $'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>$ {point.y:.1f}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    series: [{
                        name: 'Monto de Ventas',
                        data: serie

                    }]
                });

    },
    DibujarTabla: function (resultado, div_tabla) {
        var _this = this;
        $("#" + div_tabla).empty();
       
        var divGrilla = $('#' + div_tabla);
        var datos = resultado;
        var columnas = [];
        var nombre = "";

        columnas.push(new Columna("Periodo", { generar: function (un_registro) { return un_registro.mes } }));
        columnas.push(new Columna("Total", { generar: function (un_registro) { return '$ ' + un_registro.total } }));
       

        _this.GrillaResumen = new Grilla(columnas);
        _this.GrillaResumen.SetOnRowClickEventHandler(function (un_registro) {
        });
        _this.GrillaResumen.CargarObjetos(datos);
        _this.GrillaResumen.DibujarEn(divGrilla);
    },

}