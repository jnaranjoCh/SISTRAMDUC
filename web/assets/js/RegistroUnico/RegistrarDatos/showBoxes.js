$("#miniPersonal").click(function(){
    if(!miniPersonal){
        if(miniRegistros)
            $("#miniRegistros").click();
        if(miniCargos)
            $("#miniCargos").click();
        if(miniHijos)
            $("#miniHijos").click();
        miniPersonal = true;
    }else
        miniPersonal = false;
});

$("#miniRegistros").click(function(){
    if(!miniRegistros){
        if(miniPersonal)
            $("#miniPersonal").click();
        if(miniCargos)
            $("#miniCargos").click();
        if(miniHijos)
            $("#miniHijos").click();
        miniRegistros = true;
    }else
        miniRegistros = false;
});

$("#miniCargos").click(function(){
    if(!miniCargos){
        if(miniPersonal)
            $("#miniPersonal").click();
        if(miniRegistros)
            $("#miniRegistros").click();
        if(miniHijos)
            $("#miniHijos").click();
        miniCargos = true;
    }else
        miniCargos = false;
});

$("#miniHijos").click(function(){
    if(!miniHijos){
        if(miniPersonal)
            $("#miniPersonal").click();
        if(miniRegistros)
            $("#miniRegistros").click();
        if(miniCargos)
            $("#miniCargos").click();
        miniHijos = true;
    }else
        miniHijos = false;
});