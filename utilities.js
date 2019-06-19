function validate(u, p){
    var username = u.value;
    var pass = p.value;
    var regexpmail= /^([a-z0-9]+)((\.)?[a-z0-9\_\-]?)([@])([a-z0-9\-]+\.)+[a-z]{2,6}$/;
    var regexpwd= /^((([a-z])+([A-Z0-9])+)([a-zA-Z0-9]*)|(([A-Z0-9])+([a-z])+([a-zA-Z0-9]*)))$/;

    if (username == "" || pass == ""){
        alert("Dati mancanti.");
        return false;
    } else {
        if (!regexpmail.test(username) ) {
            alert("Inserisci una mail valida: deve contenere @, un nome di dominio ed un top-level domain - TLD). Esempio: ilmio.nome1@ilmiodominio.it, ilmionome@posta.ilmiodominio.com.");
            return false;
        } else if( !regexpwd.test(pass) ) {
            alert("Inserisci una password valida: deve contenere almeno una minuscola e almeno una maiuscola/un numero.");
            return false;
        }
    } 
    return true;

}


function testCookies(){
    document.cookie = 'cookietest=1';
    var cookiesEnabled = document.cookie.indexOf('cookietest=') !== -1;
    document.cookie = 'cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT';

    if (cookiesEnabled==true){
       return true; 
    }else{
        return false;
    }
}
