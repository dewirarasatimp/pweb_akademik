function validEmail(email){

    const re =
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    return re.test(email);

}

function tampilkanError(id,pesan){

    const el =
    document.getElementById(id);

    if(el){
        el.textContent = pesan;
        el.style.display = "block";
    }

}

function togglePassword(
    idInput,
    idIcon
){

    const input =
    document.getElementById(idInput);

    const icon =
    document.getElementById(idIcon);

    if(input.type === "password"){

        input.type = "text";
        icon.textContent = "🙈";

    }else{

        input.type = "password";
        icon.textContent = "👁️";

    }

}

document.addEventListener(
    "DOMContentLoaded",
    function(){

        const alerts =
        document.querySelectorAll(
            ".alert"
        );

        alerts.forEach(
            function(alert){

                setTimeout(
                    function(){

                        alert.style.opacity="0";

                        setTimeout(
                            ()=>alert.remove(),
                            500
                        );

                    },
                    4000
                );

            }
        );

    }
);