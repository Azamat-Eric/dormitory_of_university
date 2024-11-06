const logoutBtn = document.getElementById("logout-btn");

logoutBtn.onclick = () => {
    fetch("../server/checking.php", {
        headers: {
            "Content-Type": "application/json"
        },
        method: "POST",
        body: JSON.stringify({ action: "session_end" })
    }).then(res => res.json()).
        then(data => {
            console.log("Session ended.");
            window.location.href = "../login/login.php";  // Вместо window.open
        }).catch(err => console.log(err));
}

function change_div(filename) {
    let container = document.querySelector(".admin-panel-container-inner");
    
    // Используем GET запрос для динамической загрузки
    fetch(`get-files.php?page=${filename}`, {
        headers: {
            "Content-Type": "text/html"
        },
        method: "GET"
    })
    .then(res => res.text())
    .then(data => {
        // container.innerHTML = data;  // Обновляем содержимое контейнера
    })
    .catch(err => console.error("Error loading page:", err));
}



// Превью аватара при загрузке изображения
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        const output = document.getElementById('profilePreview');
        output.src = reader.result;
        setFileName();
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Удаление аватара профиля
async function profile_img_delete() {
    fetch("../server/main.php", {
        headers: {
            "Content-Type": "application/json"
        },
        method: "POST",
        body: JSON.stringify({ action: "delete-profile-image" })
    })
    .then(res => res.json())
    .then(data => {
        console.log("Profile image deleted.");
        location.reload();
    }).catch(err => {
        console.log(err);
    });
}
