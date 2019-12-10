$.extend( jQuery.fn.dataTableExt.oSort, {
    "date-uk-pre": function ( a ) {
      console.log(a);
      if (a === null || a === "") {
          return 0;
      }
        
      var data = a.split(' ')[0];
      var horario = a.split(' ')[1];
      var horas = horario.split(':')[0];
      var minutos = horario.split(':')[1];  
        
      var ukDatea = data.split('.');
      return (ukDatea[2] + ukDatea[1] + ukDatea[0]);
      },

    "date-us-asc": function ( a, b ) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "date-us-desc": function ( a, b ) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
});