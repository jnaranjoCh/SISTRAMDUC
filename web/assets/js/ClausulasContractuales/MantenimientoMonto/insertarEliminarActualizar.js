var row = -1;
var band = false;
var rowAux;
var autoIncrementeLess = -1;
var idAux;

$('#tableMontos tbody').on( 'click', 'td', function () {
    var cellRegAux =  new Object();
    cellRegAux.row = tableMontos.cell( this ).index().row;
    cellRegAux.column = "1";
    cellRegAux.columnVisible = "0";
    row = tableMontos.cell( this ).index().row;
    $('#DescripcionMontoDatos').val(tableMontos.cell(cellRegAux).data());
    cellRegAux.column = "2";
    $('#MontoMontoDatos').val(tableMontos.cell(cellRegAux).data());
    cellRegAux.column = "0";
    if(tableMontos.cell(cellRegAux).data() >= 0)
    {
        band = false;
        state = 1;
    }
    else
    {
        rowAux = tableMontos.cell( this ).index().row;
        band = true;
        state = 0;
        idAux = tableMontos.cell(cellRegAux).data();
        row = autoIncrementeLess -1;
        autoIncrementeLess = autoIncrementeLess -1;
    }
    
    $("#agregarMonto").removeClass("btn-success");
    $("#agregarMonto").addClass("btn-primary");
    $("#agregarMonto").html("Actualizar&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-refresh'></i>");
});


$('#DescripcionMontoDatos').keyup(function(){
   if($('#DescripcionMontoDatos').val() == "" && $('#MontoMontoDatos').val() == "")
   {
       state = 0;
       band = false;
       $("#agregarMonto").removeClass("btn-primary");
       $("#agregarMonto").addClass("btn-success");
       $("#agregarMonto").html("Agregar&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-plus-square'></i>");
       row = autoIncrementeLess -1;
       autoIncrementeLess = autoIncrementeLess -1;
   }
});

$('#MontoMontoDatos').keyup(function(){
   if($('#DescripcionMontoDatos').val() == "" && $('#MontoMontoDatos').val() == "")
   {
       state = 0;
       band = false;
       $("#agregarMonto").removeClass("btn-primary");
       $("#agregarMonto").addClass("btn-success");
       $("#agregarMonto").html("Agregar&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-plus-square'></i>");
       row = autoIncrementeLess -1;
       autoIncrementeLess = autoIncrementeLess -1;
   }
});


$("#agregarMonto").click(function(){
    
    var monto = new Object();
    var cellRegAux =  new Object();
    var descriptionAux = "";
    
    monto.state = state;
    monto.descripcion = $('#DescripcionMontoDatos').val();
    monto.monto = $('#MontoMontoDatos').val();
    if(row >= 0 || (row < 0 && band))
    {
        
        cellRegAux.row = rowAux;
        cellRegAux.column = "1";
        cellRegAux.columnVisible = "0";
        cellRegAux.column = "0";
        if(tableMontos.cell(cellRegAux).data() < 0 && band)
        {
            monto.Id = idAux;
            alert(idAux);
            row = rowAux;
            actualizarMonto(monto);
        }
        
        cellRegAux.row = row;
        cellRegAux.column = "1";
        cellRegAux.columnVisible = "0";
        tableMontos.cell(cellRegAux).data($('#DescripcionMontoDatos').val());
        cellRegAux.column = "2";
        tableMontos.cell(cellRegAux).data($('#MontoMontoDatos').val());
        cellRegAux.column = "0";
        monto.Id = tableMontos.cell(cellRegAux).data();
        
    }else
    {
       monto.Id = autoIncrementeLess -1;
       autoIncrementeLess = autoIncrementeLess -1;
    }
        
    if($.trim($('#DescripcionMontoDatos').val()) && $.trim($('#MontoMontoDatos').val()) && !existe(monto) && !band)
    {
        if(monto.state == 0)
        {
            tableMontos.row.add( {
                "Id": monto.Id,
                "Descripcion": monto.descripcion,
                "Monto": monto.monto        
            }).draw();
        }
        amounts[countAmounts] = monto;
        countAmounts++;
    }
    
    alert(JSON.stringify(amounts));
});

function existe(monto)
{
    var exist = false;
    for(var i = 0; i < countAmounts; i++)
    {
        if(amounts[i].state == monto.state && amounts[i].descripcion == monto.descripcion && (amounts[i].Id == monto.Id || monto.Id < 0) && amounts[i].monto == monto.monto)
        {
            exist = true;
        }
        
        if(amounts[i].Id == monto.Id && amounts[i].Id >= 0)
        {
            amounts[i] = monto;
            exist = true;
        }
        
    }
    return exist;   
}


function actualizarMonto(monto)
{
    for(var i = 0; i < countAmounts; i++)
    {
        if(amounts[i].Id == monto.Id)
        {
            amounts[i] = monto;
            exist = true;
        }
        
    }
}

