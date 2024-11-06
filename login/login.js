function togglePasswordVisibility() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.querySelector('.toggle-password');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.textContent = 'Скрыть';
    } else {
        passwordField.type = 'password';
        toggleIcon.textContent = 'Показать';
    }
}


window.addEventListener("beforeunload", unloading);

function unloading(e){
    localStorage.clear();
    fetch("../server/process_login.php", {
        headers:{
            "Content-Type":"application/json"
        },
        method: "POST",
        body: JSON.stringify({"action":"session_end"})
    }).then(res=>res.json()).then(data=>console.log(data));
}

const incorrect = document.querySelector(".incorrect");
if(incorrect){
    setTimeout(() => {
        incorrect.remove();
    }, 2000);
}