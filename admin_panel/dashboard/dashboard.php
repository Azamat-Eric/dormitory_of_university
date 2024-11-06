<link rel="stylesheet" href="dashboard/new.css">

<?php
// session_start(); // Убедитесь, что сессия начата

// Загружаем данные из JSON файла
$dormitory_datas = json_decode(file_get_contents("../files/dormitory-data.json"), true);

// Проверяем наличие данных
if (!$dormitory_datas) {
    echo "<p>Ошибка загрузки данных.</p>";
    exit;
}

// Определяем текущее общежитие на основе роли
$current_dormitory = null;
if ($_SESSION["role"] == "first") {
    $current_dormitory = "Общежитие №1"; // Укажите актуальное название
} else if ($_SESSION["role"] == "second") {
    $current_dormitory = "Общежитие №2"; // Укажите актуальное название
}

// Инициализация переменных для фильтрации
$rooms = [];

// Получаем комнаты для текущего общежития
foreach ($dormitory_datas as $dormitory_data) {
    // Проверяем, соответствует ли текущее общежитие
    if ($dormitory_data['dormitory-name'] == $_SESSION["role"]) {
        foreach ($dormitory_data['floors'] as $floor => $floor_data) {
            foreach ($floor_data['rooms'] as $room_number => $room_data) {
                $rooms[$floor][$room_number] = $room_data;
            }
        }
        break; // Выход из цикла после нахождения текущего общежития
    }
}
?>

<div class="filter-container">
    <label for="floor-select">Этаж:</label>
    <select id="floor-select" onchange="filterRooms()">
        <option value="all">Все этажи</option>
        <?php foreach (array_keys($rooms) as $floor): ?>
            <option value="<?php echo $floor; ?>"><?php echo $floor; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="room-select">Комната:</label>
    <select id="room-select" onchange="filterRooms()">
        <option value="all">Все комнаты</option>
        <?php foreach ($rooms as $floor => $floor_rooms): ?>
            <?php foreach ($floor_rooms as $room_number => $room_data): ?>
                <option value="<?php echo $room_number; ?>" class="room-option" data-floor="<?php echo $floor; ?>">Комната <?php echo $room_number; ?></option>
                <?php endforeach; ?>
        <?php endforeach; ?>
    </select>

    <!-- Форма для удаления всех студентов из выбранной комнаты -->
    <form method="POST" action="delete_all_students.php" id="delete-all-students-form">
        <input type="hidden" name="floor" id="selected-floor" value="">
        <input type="hidden" name="room" id="selected-room" value="">
        <button type="submit" onclick="setRoomData()">Удалить всех студентов из комнаты</button>
    </form>

</div>

<div class="dormitory-display">
    <?php
    // Отображаем данные по этажам и комнатам для текущего общежития
    foreach ($dormitory_datas as $dormitory_data) {
        if ($dormitory_data['dormitory-name'] == $_SESSION["role"]) {
            foreach ($dormitory_data['floors'] as $floor_number => $floor_data) {
                // Подсчитываем занятые места на этаже
                $total_occupied = 0;
                foreach ($floor_data['rooms'] as $room_data) {
                    $total_occupied += isset($room_data['students']) ? count($room_data['students']) : 0;
                }

                // Если этаж пустой, не отображаем его
                if (empty($floor_data['rooms'])) {
                    continue; // Пропускаем пустые этажи
                }

                $gender = $floor_data['floor-gender'] == 'male' ? 'Мужской' : 'Женский';
                $total_capacity = array_sum(array_column($floor_data['rooms'], 'capacity'));

                echo "<div class='floor-block' data-floor='$floor_number'>
                        <h2>Этаж $floor_number <span style='font-size: clamp(10px, 1rem, 18px); font-weight:400;'>(Пол: $gender) — Занято $total_occupied из $total_capacity</span></h2>";

                // Отображаем данные по комнатам на этаже
                foreach ($floor_data['rooms'] as $room_number => $room_data) {
                    $students = isset($room_data['students']) && is_array($room_data['students']) ? $room_data['students'] : [];
                    $occupied_places = count($students);
                    $capacity = $room_data['capacity'];
                    $free_places = $capacity - $occupied_places;

                    echo "<div class='room-block' data-floor='$floor_number' data-room='$room_number'>
                            <h4>Комната $room_number <span style='font-size: clamp(10px, 1rem, 18px); font-weight:400;'>(Занято $occupied_places из $capacity, Свободно мест: $free_places)</span></h4>
                            <details>
                              <summary>Проживающие/Студенты</summary>";

                    if ($occupied_places > 0) {
                        echo "<div class='table-display'><form method='POST' action='delete_student.php' class='table-form'><table>
                                <thead>
                                    <tr>
                                        <th>ФИО</th>
                                        <th>Номер телефона</th>
                                        <th>Группа</th>
                                        <th>Курс обучения</th>
                                        <th>Действие</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        foreach ($students as $student) {
                            echo "<tr>
                                    <td>{$student['fullname']}</td>
                                    <td><div class='wa-phone'>{$student['phone']} <a href='https://wa.me/{$student['phone']}'><img src='https://static.whatsapp.net/rsrc.php/v3/yP/r/rYZqPCBaG70.png' class='whatsapp-img' alt='WA' title='Whatsapp'></div></td>
                                    <td>{$student['group']}</td>
                                    <td>{$student['kurs']}</td>
                                    <td>
                                        <!-- Передаем данные конкретного студента для удаления -->
                                        <input type='hidden' name='fullname' value='{$student['fullname']}'>
                                        <input type='hidden' name='phone' value='{$student['phone']}'>
                                        <button class='delete-btn' type='submit'>Удалить</button>
                                    </td>
                                  </tr>";
                        }
                        echo "</tbody></table></form></div>";
                    } else {
                        echo "<p>Нет студентов</p>";
                    }

                    echo "</details>
                        </div>";
                }

                echo "</div>"; // Закрываем div.floor-block
            }
        }
    }
    ?>
</div>

<script>
    // Функция для фильтрации этажей и комнат
    function filterRooms() {
    const selectedFloor = document.getElementById('floor-select').value;
    const selectedRoom = document.getElementById('room-select').value;
    const roomBlocks = document.querySelectorAll('.room-block');
    const floorBlocks = document.querySelectorAll('.floor-block');

    // Скрыть все блоки комнат и этажей
    roomBlocks.forEach(block => {
        block.style.display = 'none';
    });

    floorBlocks.forEach(block => {
        block.style.display = 'none';
    });


    // Показать этажи
    if (selectedFloor === 'all') {
        floorBlocks.forEach(block => {
            block.style.display = 'block';
        });
    } else {
        const selectedFloorBlock = document.querySelector(`.floor-block[data-floor='${selectedFloor}']`);
        if (selectedFloorBlock) {
            selectedFloorBlock.style.display = 'block';
        }
    }

    // Показать комнаты на выбранном этаже
    if (selectedFloor !== 'all') {
        const selectedRoomOptions = document.querySelectorAll(`.room-option[data-floor='${selectedFloor}']`);
        selectedRoomOptions.forEach(option => {
            const block = document.querySelector(`.room-block[data-floor='${selectedFloor}'][data-room='${option.value}']`);
            if (block) {
                if (selectedRoom === 'all' || option.value === selectedRoom) {
                    block.style.display = 'block'; // Показываем только нужные комнаты
                }
            }
        });
    } else {
        // Если выбран "Все этажи", показываем все комнаты
        roomBlocks.forEach(block => {
            block.style.display = 'block';
        });
    }
}


    // Функция для установки данных о комнате перед отправкой формы
    function setRoomData() {
        const selectedFloor = document.getElementById('floor-select').value;
        const selectedRoom = document.getElementById('room-select').value;
        document.getElementById('selected-floor').value = selectedFloor;
        document.getElementById('selected-room').value = selectedRoom;
    }
</script>