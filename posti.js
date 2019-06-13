
$("documet").ready( function(){
    var sumamma =this;
    $("[type=button]").click(function(){
        $.post("prenotationsDB.php", {row: this.value[0],
                                      column: this.value[1],
                                      stato: this.className},
        function(data, status){
                if (status=="success"){
                    sumamma.classList.remove(sumamma.getAttribute('class'));
                    sumamma.classList.add(data);   
                }
        })   
    })
});