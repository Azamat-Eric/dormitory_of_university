document.addEventListener("DOMContentLoaded", loaded);

function loaded() {
    const code = JSON.parse(document.querySelector(".code").innerHTML);

    let dormitory_name = document.getElementById("dormitory");
    let student_kurs = document.getElementById("kurs");
    let student_floor = document.getElementById("floor");
    let student_gender = document.getElementById("gen").value;
    const room_div = document.getElementById("room");

    // ------ 1. Автоматический выбор доступных общежитий в зависимости от курса ------
    student_kurs.addEventListener("change", function () {
        let selectedCourse = student_kurs.value;

        // Сначала сбрасываем все общежития
        for (let i = 0; i < dormitory_name.options.length; i++) {
            dormitory_name.options[i].disabled = true; // Отключаем все общежития
        }

        // Проходим по каждому общежитию и проверяем его доступность по курсу
        for (let i = 0; i < dormitory_name.options.length; i++) {
            let option = dormitory_name.options[i];
            let dormitory = code.find(d => d["dormitory-name"] === option.value);

            // Если общежитие доступно для выбранного курса, включаем его
            if (dormitory && Array.isArray(dormitory["available-kurs"]) && dormitory["available-kurs"].includes(selectedCourse)) {
                option.disabled = false; // Включаем общежитие
            }
        }

        // Автоматически выбираем первое доступное общежитие
        let firstAvailableIndex = [...dormitory_name.options].findIndex(option => !option.disabled);
        if (firstAvailableIndex !== -1) {
            dormitory_name.selectedIndex = firstAvailableIndex;
        } else {
            dormitory_name.selectedIndex = 0; // Если нет доступных, выбираем первую опцию (например, "Выберите общежитие")
        }

        // Обновляем список этажей для нового выбранного общежития
        updateFloors();
    });

    // ------ 2. Ограничение доступа к этажам по полу студента ------
    dormitory_name.addEventListener("change", updateFloors);
    document.getElementById("gen").addEventListener("change", updateFloors);

    function updateFloors() {
        let selectedGender = document.getElementById("gen").value;
        let selectedDormitory = dormitory_name.value;

        // Очищаем и сбрасываем список этажей
        student_floor.innerHTML = '<option value="" disabled selected>Выберите этаж</option>';

        // Находим выбранное общежитие
        let dorm = code.find(d => d["dormitory-name"] === selectedDormitory);

        if (dorm && selectedGender) {
            Object.keys(dorm.floors).forEach(floor => {
                let floorData = dorm.floors[floor];
                let option = document.createElement("option");
                option.value = floor;
                option.textContent = floor;

                // Включаем только этажи, соответствующие полу студента
                if (floorData["floor-gender"] === selectedGender) {
                    option.disabled = false; // Этаж доступен
                    student_floor.appendChild(option); // Добавляем его в выпадающий список
                }
            });

            // Если нет подходящих этажей, показываем сообщение
            if (student_floor.options.length === 1) {
                let placeholder = document.createElement("option");
                placeholder.textContent = "Нет доступных этажей для вашего пола";
                placeholder.disabled = true;
                placeholder.selected = true;
                student_floor.appendChild(placeholder);
            }
        }
    }

    // ------ 3. Показ комнат и выбор места ------
    student_floor.addEventListener("change", function () {
        let selectedDormitory = dormitory_name.value;
        let selectedFloor = student_floor.value;

        // Очищаем блок комнат
        room_div.innerHTML = '';

        // Находим общежитие и этаж
        let dorm = code.find(d => d["dormitory-name"] === selectedDormitory);
        if (dorm && dorm.floors[selectedFloor]) {
            let rooms = dorm.floors[selectedFloor]["rooms"];

            // Проходим по каждой комнате и создаем блок
            Object.keys(rooms).forEach(roomNumber => {
                let room = rooms[roomNumber];
                let roomBlock = document.createElement("div");
                roomBlock.classList.add("room-block");
                roomBlock.innerHTML = `<span>Комната ${roomNumber}</span>`;

                // Добавляем кровати
                for (let i = 1; i <= room.capacity; i++) {
                    let bedBlock = document.createElement("input");
                    bedBlock.type = "checkbox";
                    bedBlock.name = "room-number";
                    bedBlock.value = roomNumber;
                    bedBlock.classList.add("bed-block");

                    // Проверяем количество свободных мест и отключаем места при необходимости
                    if (i > room["free-places"]) {
                        bedBlock.classList.add("occupied"); // Место занято
                        bedBlock.disabled = true; // Дизаблим чекбокс
                    } else {
                        bedBlock.addEventListener("click", function () {
                            // Убираем выделение с предыдущих мест
                            document.querySelectorAll(".bed-block.selected").forEach(block => {
                                block.classList.remove("selected");
                                block.checked = false;
                            });
                            this.classList.add("selected"); // Выделяем выбранное место
                        });
                    }
                    roomBlock.appendChild(bedBlock);
                }

                // Если свободных мест меньше 2, отключаем соответствующее количество инпутов
                if (room["free-places"] < room.capacity) {
                    for (let j = room["free-places"] + 1; j <= room.capacity; j++) {
                        let occupiedBedBlock = roomBlock.querySelectorAll(".bed-block")[j - 1];
                        if (occupiedBedBlock) {
                            occupiedBedBlock.disabled = true; // Дизаблим инпуты с занятыми местами
                        }
                    }
                }

                room_div.appendChild(roomBlock);
            });
        }
    });
}
