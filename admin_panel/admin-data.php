<div class="admin-php">
    <div class="admin-php-inner">

        <div class="admin-php-left">
            <div class="admin-php-photo">
                <img src="<?php echo $json_data["profile_image"]; ?>" alt="Profile photo" id="profile-photo">
            </div>
            <div class="admin-php-left-text">
                <div class="admin-php-name translateable">
                    <?php echo $json_data["initials"]; ?>
                </div>
                <div class="admin-php-left-description">
                    <div class="admin-php-dormitory">
                        <?php
                        echo ($_SESSION["role"] == "first") ? "<span class='translateable dormitory_num'>№1 общежитие</span>" : "<span class='translateable dormitory_num'>№2 общежитие</span>";
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-php-right">

            <div class="admin-php-right-container">
                <div class="admin-php-right-name translateable">ФИО: </div>
                <div class="admin-php-right-name-paste translateable"><?php echo $json_data["initials"] ?></div>
            </div>
            <div class="admin-php-right-container">
                <div class="admin-php-right-email translateable">Электронная почта:</div>
                <div class="admin-php-right-email-paste translateable"><?php echo $json_data["email"] ?></div>
            </div>
            <div class="admin-php-right-container">
                <div class="admin-php-right-phone translateable">Номер телефона:</div>
                <div class="admin-php-right-phone-paste translateable"><?php echo $json_data["phone_number"]; ?></div>
            </div>
            <div class="admin-php-right-container">
                <div class="admin-php-right-btns">
                    <button class="admin-php-edit-btn translateable" onclick="change_div('close')">Закрыть</button>
                    <button class="admin-php-edit-btn translateable" onclick="change_div('admin-data-edit')">Редактировать</button>
                </div>
            </div>
        </div>
    </div>
</div>