<?php
$booking_datas_json = json_decode(file_get_contents('../files/booking-datas.json'), true);
if (isset($_SESSION["role"]) && $_SESSION["role"] == "first") {
    $dormitory_name = "Первая общежития";
    $value = "first";
}
if (isset($_SESSION["role"]) && $_SESSION["role"] == "second") {
    $dormitory_name = "Вторая общежития";
    $value = "second";
}

if (isset($_SESSION["message"])) {
    $message = $_SESSION["message"];
    unset($_SESSION["message"]);
    echo "<div class='message-notification'>" . $message . "</div>";
}
?>
<link rel="stylesheet" href="dormitory-set.css">

<div class="rules-container">
    <details>
        <summary class="translateable">Важно прочитать!</summary>
        <ul class="rules">
            <li>
                <h3 class="translateable">Общежития (автоматический):</h3>
                <span class="translateable">
                    Это поле показывает общежитие, которое вы редактируете. Название общежития заполняется автоматически
                    и не требует ручного ввода.
                </span>
            </li>
            <li>
                <h3 class="translateable">Курсы, разрешенные для размещения:</h3>
                <span class="translateable">
                    Выберите курсы, студенты которых могут размещаться в этом общежитии. Вы можете выбрать несколько
                    курсов.
                    Если студент не относится к выбранным курсам, он не сможет выбрать это общежитие при бронировании
                    комнаты. Например, если разрешены только 1-й и 4-й курсы, студенты 3-го курса не смогут подать
                    заявку на
                    размещение. Постарайтесь выбрать максимальное количество курсов, чтобы расширить возможности
                    размещения.
                </span>
            </li>
            <li>
                <h3 class="translateable">Комнаты:</h3>
                <span class="translateable">
                    Введите количество комнат, доступных на этом этаже. Если этаж не предназначен для размещения
                    студентов,
                    укажите "0". Все добавленные или удаленные комнаты автоматически отобразятся ниже в виде блоков.
                    Предупреждение: Сначала определите точное количество комнат на этаже, а затем приступайте к
                    редактированию информации о них.
                </span>
            </li>
            <li>
                <h3 class="translateable">Категория пола:</h3>
                <span class="translateable">
                    В этом поле выберите пол студентов, которые могут размещаться на этом этаже.
                </span>
            </li>
            <li>
                <h3 class="translateable">Вместимость:</h3>
                <span class="translateable">
                    Укажите, сколько мест предусмотрено в каждой комнате для размещения студентов. По умолчанию это
                    значение
                    равно 4, но вы можете изменить его для каждой комнаты отдельно. Если комната не предназначена для
                    размещения, установите значение "0".
                </span>
            </li>
            <li>
                <h3 class="translateable">Уведомление от коменданта:</h3>
                <span class="translateable">
                    В этом поле комендант может оставить важное уведомление или объявление, касающееся общежития
                    (например,
                    цены, правила, важные изменения). Это уведомление будет отображаться на странице бронирования для
                    студентов, чтобы они были ознакомлены с важной информацией перед подачей заявки на бронирование.
                </span>
            </li>
        </ul>
</div>


<form action="../server/dormitory-setting.php" id="dormitory-setting-form" method="post">
    <h1 class="form-title translateable">Управления общежитием</h1>
    <div class="main-capacity translateable" hidden>Вместимость</div>

    <div class="form-container">
        <div class="main-settings">
            <label for="dormitory-name">
                <span class="translateable">Общежития (автоматический):</span>
                <select name="dormitory-name" id="dormitory-name">
                    <option value="<?php echo $value; ?>" class="translateable">
                        <?php echo $dormitory_name; ?>
                    </option>
                </select>
            </label>
            <div class="available-kurses">
                <span class="translateable">Курсы, разрешенные для размещения:</span>
                <div id="available-kurses">
                    <label for="kurs-1">
                        <input type="checkbox" id="kurs-1" name="available-kurs[]" value="1">
                        <span class="kurs-number">1</span>
                    </label>
                    <label for="kurs-2">
                        <input type="checkbox" id="kurs-2" name="available-kurs[]" value="2">
                        <span class="kurs-number">2</span>
                    </label>
                    <label for="kurs-3">
                        <input type="checkbox" id="kurs-3" name="available-kurs[]" value="3">
                        <span class="kurs-number">3</span>
                    </label>
                    <label for="kurs-4">
                        <input type="checkbox" id="kurs-4" name="available-kurs[]" value="4">
                        <span class="kurs-number">4</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="floors-container">

            <!-- floor 1 -->
            <div class="floor">
                <div class="floor-info">
                    <div class="floor-number">
                        <span class="translateable">Этаж 1</span>
                        <input type="number" min="0" value="1" hidden name="floors[1]">
                    </div>
                    <label for="floor-room-count">
                        <span class="translateable">Комнаты:</span>
                        <input type="number" min="0" name="floors[1][floor-room-count]" value="0" id="floor-room-count">
                        <input type="number" min="0" name="floors[1][floor-room-free]" hidden value="0" id="free-rooms">
                    </label>
                    <label for="floor-gender" class="floor-gender">
                        <span class="translateable">Категория пола:</span>
                        <select name="floors[1][floor-gender]" id="floor-gender">
                            <option value="male" class="translateable">Мужской</option>
                            <option value="female" class="translateable">Женский</option>
                        </select>
                    </label>
                </div>

                <div class="rooms"></div>
            </div>
            <!-- floor 2 -->
            <div class="floor">
                <div class="floor-info">
                    <div class="floor-number">
                        <span class="translateable">Этаж 2</span>
                        <input type="number" min="0" value="2" hidden name="floors[2]">
                    </div>
                    <label for="floor-room-count">
                        <span class="translateable">Комнаты:</span>
                        <input type="number" min="0" name="floors[2][floor-room-count]" value="0" id="floor-room-count">
                        <input type="number" min="0" name="floors[2][floor-room-free]" hidden value="0" id="free-rooms">
                    </label>
                    <label for="floor-gender" class="floor-gender">
                        <span class="translateable">Категория пола:</span>
                        <select name="floors[2][floor-gender]" id="floor-gender">
                            <option value="male" class="translateable">Мужской</option>
                            <option value="female" class="translateable">Женский</option>
                        </select>
                    </label>
                </div>

                <div class="rooms"></div>
            </div>
            <!-- floor 3 -->
            <div class="floor">
                <div class="floor-info">
                    <div class="floor-number">
                        <span class="translateable">Этаж 3</span>
                        <input type="number" min="0" value="3" hidden name="floors[3]">
                    </div>
                    <label for="floor-room-count">
                        <span class="translateable">Комнаты:</span>
                        <input type="number" min="0" name="floors[3][floor-room-count]" value="0" id="floor-room-count">
                        <input type="number" min="0" name="floors[3][floor-room-free]" hidden value="0" id="free-rooms">
                    </label>
                    <label for="floor-gender" class="floor-gender">
                        <span class="translateable">Категория пола:</span>
                        <select name="floors[3][floor-gender]" id="floor-gender">
                            <option value="male" class="translateable">Мужской</option>
                            <option value="female" class="translateable">Женский</option>
                        </select>
                    </label>
                </div>

                <div class="rooms"></div>
            </div>
            <!-- floor 4 -->
            <div class="floor">
                <div class="floor-info">
                    <div class="floor-number">
                        <span class="translateable">Этаж 4</span>
                        <input type="number" min="0" value="4" hidden name="floors[4]">
                    </div>
                    <label for="floor-room-count">
                        <span class="translateable">Комнаты:</span>
                        <input type="number" min="0" name="floors[4][floor-room-count]" value="0" id="floor-room-count">
                        <input type="number" min="0" name="floors[4][floor-room-free]" hidden value="0" id="free-rooms">
                    </label>
                    <label for="floor-gender" class="floor-gender">
                        <span class="translateable">Категория пола:</span>
                        <select name="floors[4][floor-gender]" id="floor-gender">
                            <option value="male" class="translateable">Мужской</option>
                            <option value="female" class="translateable">Женский</option>
                        </select>
                    </label>
                </div>

                <div class="rooms"></div>
            </div>
            <!-- floor 5 -->
            <div class="floor">
                <div class="floor-info">
                    <div class="floor-number">
                        <span class="translateable">Этаж 5</span>
                        <input type="number" min="0" value="5" hidden name="floors[5]">
                    </div>
                    <label for="floor-room-count">
                        <span class="translateable">Комнаты:</span>
                        <input type="number" min="0" name="floors[5][floor-room-count]" value="0" id="floor-room-count">
                        <input type="number" min="0" name="floors[5][floor-room-free]" hidden value="0" id="free-rooms">
                    </label>
                    <label for="floor-gender" class="floor-gender">
                        <span class="translateable">Категория пола:</span>
                        <select name="floors[5][floor-gender]" id="floor-gender">
                            <option value="male" class="translateable">Мужской</option>
                            <option value="female" class="translateable">Женский</option>
                        </select>
                    </label>
                </div>

                <div class="rooms"></div>
            </div>


        </div>

        <div class="notify">
            <div class="notify-title translateable">Уведомление от комменданта:</div>
            <div class="text">
                <textarea name="commander-notify"></textarea>
            </div>
        </div>

        <div class="form-btns">
            <button type="submit" class="translateable">Сохранить</button>
            <button class="translateable cancel-btn" type="button" onclick="change_div('close')">Отмена</button>
        </div>
    </div>
</form>

<script src="dormitory-set.js" type="module"></script>