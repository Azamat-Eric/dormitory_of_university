import { capacityText } from "./capacitytext.js";
window.addEventListener("DOMContentLoaded", domLoaded => {
    let floors = document.querySelectorAll(".floor");
    let floor_room_counts = document.querySelectorAll("#floor-room-count");
    let modified = false;
    let free_rooms = document.querySelectorAll("#free-rooms");
    let main_capacity = document.querySelector(".main-capacity");
    // console.log(main_capacity);

    for (let i = 0; i < floor_room_counts.length; i++) {
        floor_room_counts[i].oninput = () => {
            floors[i].querySelector(".rooms").innerHTML = "";
            if (floor_room_counts[i].value > 0) {
                capacityText();
                addRooms(i);
            }
        }
    }

    if (document.querySelector(".message-notification")) {
        document.querySelector(".message-notification").style.top = "150px";
        document.querySelector(".message-notification").style.opacity = 1;
        setTimeout(() => { document.querySelector(".message-notification").style.opacity = 0; }, 2000);
        setTimeout(() => { document.querySelector(".message-notification").remove(); }, 3000);
    }

    // Функция обновления свободных комнат
    function updateFreeRooms(i) {
        free_rooms[i].value = floor_room_counts[i].value;
    }

    for (let i = 0; i < floor_room_counts.length; i++) {
        // Инициализация значений при загрузке страницы
        updateFreeRooms(i);

        floor_room_counts[i].oninput = () => {
            updateFreeRooms(i);
            floors[i].querySelector(".rooms").innerHTML = "";
            capacityText(main_capacity);
            if (floor_room_counts[i].value > 0) {
                addRooms(i);
            }
        }
    }

    
    function addRooms(i) {
        console.log(main_capacity);
        for (let j = 0; j < floor_room_counts[i].value; j++) {
            let room = document.createElement("div");
            room.classList.add("room");
            room.innerHTML = `
                <div class="room-info">
                    <div class="room-number">${(i + 1) + "" + ("0" + j).substr(-2)}</div>
                    <input type="number" name="floors[${i + 1}][rooms][${i + 1 + "" + ("0" + j).substr(-2)}][number]" hidden>
                    <input type="number" min="0" value="${(i + 1) + "" + ("0" + j).substr(-2)}" name="floors[${i + 1}][rooms][${i + 1 + "" + ("0" + j).substr(-2)}]" hidden>
                    <label for="room-capacity-input-${j + 1}" class="room-capacity">
                        <span class="translateable">Вместимость:</span>
                        <input type="number" min="0" value="4" name="floors[${i + 1}][rooms][${i + 1 + "" + ("0" + j).substr(-2)}][capacity]"
                            id="room-capacity-input-${j + 1}" class="room-capacity-input">
                        <input type="number" min="0" name="floors[${i + 1}][rooms][${(i + 1) + "" + ("0" + j).substr(-2)}][free-places]"
                            value="4" id="room-free-places" hidden>
                        <input type="text" name="floors[${i + 1}][rooms][${(i + 1) + "" + ("0" + j).substr(-2)}][students][]" hidden>
                    </label>
                </div>
                <div class="room-visual">
                    <span class="squart">1</span>
                    <span class="squart">2</span>
                    <span class="squart">3</span>
                    <span class="squart">4</span>
                </div>`;
            modified = true;
            floors[i].querySelector(".rooms").appendChild(room);
        }
        addPlaces();
    }

    function addPlaces() {
        if (modified) {
            if (document.querySelectorAll(".room-visual").length > 0) {
                let room_visuals = document.querySelectorAll(".room-visual");
                let room_capacity_inputs = document.querySelectorAll(".room-capacity-input");
                let room_free_places = document.querySelectorAll("#room-free-places");

                for (let r = 0; r < room_capacity_inputs.length; r++) {
                    // Устанавливаем значение при инициализации
                    room_free_places[r].value = room_capacity_inputs[r].value;

                    // Обработчики для изменения значений
                    room_capacity_inputs[r].oninput = () => {
                        room_free_places[r].value = room_capacity_inputs[r].value;
                        addSquarts(room_capacity_inputs[r].value, r, room_visuals);
                    };
                    room_capacity_inputs[r].onchange = () => {
                        room_free_places[r].value = room_capacity_inputs[r].value;
                        addSquarts(room_capacity_inputs[r].value, r, room_visuals);
                    };
                }
            }
        }
    }

    function addSquarts(capacity, r, room_visuals) {
        room_visuals[r].innerHTML = "";
        for (let s = 0; s < capacity; s++) {
            let squart = document.createElement("span");
            squart.classList.add("squart");
            squart.textContent = s + 1;
            room_visuals[r].appendChild(squart);
        }
    }



});