var sumamma;
$("documet").ready( function(){
    $("[type=checkbox]").click(function(){
        sumamma =this;
        $.post("prenotationsDB.php", {row: this.value[0],
                                      column: this.value[1],
                                      stato: this.parentNode.className},
        
        function(data, status){

                if (status=="success"){
                    console.log(data);
                    if (data=="free" || data=="occupieduser" || data=="occupied" || data=="booked"){
                        sumamma.parentNode.classList.remove(sumamma.parentNode.getAttribute('class'));
                        sumamma.parentNode.classList.add(data);
                    }
                }

        })   
    })
});