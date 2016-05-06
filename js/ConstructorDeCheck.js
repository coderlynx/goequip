var ConstructorDeCheck = {
    armarComboProvincia: function() {
		
        //quizas lo usamos
		var json = JSON.stringify('provincias');
		
		$.ajax({
			  async:false,    
			  cache:false,   
			  data:{provincias:json },
			  type: 'GET',   
			  url: "php/controllerCheckbox.php",
			  success:  function(respuestaJson){  
					var rta = JSON.parse(respuestaJson);
			
				for(i = 0; i < rta.length; i++) {
				 $('#provincia').append(new Option(rta[i].descripcion,rta[i].id,false, false))
				
				}
			  },
			  error:function(e){
				  console.log('Error al ejecutar la petición por:' + e);
			  }
		});
		
	
	},
	armarCheckBox: function(contenedor, atributo) {

		var json = JSON.stringify(atributo);

		$.ajax({
			  async:false,    
			  cache:false,   
			  data:{check:atributo },
			  type: 'GET',   
			  url: "php/controllerCheckbox.php",
			  success:  function(respuestaJson){  
					var rta = JSON.parse(respuestaJson);
				
					for(i = 0; i < rta.length; i++) {
						var res =  rta[i].descripcion.replace(" ", "_");
						$(contenedor).append('<p class="chk" ><input class="check" type="checkbox" data-descripcion=' + res + ' name=' + atributo +'[]  value=' + rta[i].id + ' />' + rta[i].descripcion + '</p>');
					}
			  },
			  error:function(e){
				  console.log('Error al ejecutar la petición por:' + e);
			  }
		});

	},
	armarCheckBoxColores: function(contenedor, atributo) {
        //tuve que hacer otro muy parecido a talles, pero como dibuja distinto lo cambie
		var json = JSON.stringify(atributo);
		
		
		$.ajax({
			  async:false,    
			  cache:false,   
			  data:{check:atributo },
			  type: 'GET',   
			  url: "php/controllerCheckbox.php",
			  success:  function(respuestaJson){  
					var rta = JSON.parse(respuestaJson);
				
					for(i = 0; i < rta.length; i++) {
						var res =  rta[i].descripcion.replace(" ", "_");
						$(contenedor).append('<p class="chk" ><input class="check" type="checkbox" data-descripcion=' + res + ' name=' + atributo +'[]  value=' + rta[i].id + ' /><span class="circle" style="background:' + rta[i].descripcion + '"></p>');
					}
			  },
			  error:function(e){
				  console.log('Error al ejecutar la petición por:' + e);
			  }
		});

	}
}