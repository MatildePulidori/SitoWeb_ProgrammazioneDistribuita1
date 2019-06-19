var sumamma;
$("documet").ready( function(){
    $("[type=checkbox]:not(.booked)").click(function(){
        sumamma =this;
        $.post("page_prenotationsDB.php", {column: this.value[0],
                                      row: this.value[1],
                                      stato: this.parentNode.className},
        
        function(data, status){

                if (status=="success"){
                    console.log(data);
                    if (data=="free" || data=="occupieduser" || data=="occupied" || data=="booked"){
                        sumamma.parentNode.classList.remove(sumamma.parentNode.getAttribute('class'));
                        sumamma.parentNode.classList.add(data);

                        var position = sumamma.value[0]+sumamma.value[1];
                        if (data=="free"){
                            $("#response")[0].innerHTML = "La prenotazione "+position+" è stata liberata correttamente";
                        } else if (data=="occupieduser"){
                            $("#response")[0].innerHTML = "La prenotazione "+position+" è stata fatta correttamente";
                        } else if (data=="booked"){
                            $("#response")[0].innerHTML = "La prenotazione "+position+" non è stata fatta perchè già fatta da un altro utente";
                        }
                    } else {
                        // it will probably be a message error
                        $("#response")[0].innerHTML = data;
                    }
                }

        })   
    })
});