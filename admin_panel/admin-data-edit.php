<link rel="stylesheet" href="admin-data-edit.css">
<div class="admin-edit-container">
    <form class="admin-edit-form" id="adminEditForm" action="../server/admin_update.php" method="post"
        enctype="multipart/form-data">

        <div class="admin-edit-left">
            <div class="profile-image-container">
                <img id="profilePreview" src="../images/<?php echo $json_data['profile_image']; ?>" alt="Profile Image">
                <div class="profile-image-container-inner">
                    <label for="profileImage" class="upload-label profile-image-btn">
                        <img src="../images/edit-icon.svg" alt="" class="edit-img"><span class="translateable">Выбрать фото профиля</span>
                    </label>
                    <input type="file" id="profileImage" name="profileImage" class="form-input"
                        accept="image/*" onchange="previewImage(event)" inert hidden>
                    <span class="profile-image-delete" onclick="profile_img_delete()">
                        <img src="../images/delete-icon.svg" alt="" class="delete-img"><span class="translateable">Удалить фото профиля</span></span>
                    <input type="text" id="filename" name="profile-photo-name" value="../images/<?php echo $json_data["profile_image"];?>" hidden inert>
                </div>
            </div>
        </div>

        <div class="admin-edit-right">
            <div class="admin-edit-right-group">
                <label for="fullName" class="form-label translateable">ФИО:</label>
                <input type="text" id="fullName" name="fullName" class="form-input"
                    value="<?php echo $json_data['initials']; ?>" required>
            </div>
            
            <div class="admin-edit-right-group">
                <label for="username" class="form-label translateable">Логин:</label>
                <input type="text" id="username" name="username" class="form-input"
                    value="<?php echo $json_data['username']; ?>" required>
            </div>
            
            <div class="admin-edit-right-group">
                <label for="password" class="form-label translateable">Пароль:</label>
                <input type="text" id="password" name="password" class="form-input"
                    value="<?php echo $json_data['password']; ?>" required>
            </div>
            

            <div class="admin-edit-right-group">
                <label for="email" class="form-label translateable">Электронная почта:</label>
                <input type="email" id="email" name="email" class="form-input"
                    value="<?php echo $json_data['email']; ?>">
            </div>

            <div class="admin-edit-right-group">
                <label for="phone" class="form-label translateable">Номер телефона:</label>
                <input type="text" id="phone" name="phone_number" class="form-input"
                    value="<?php echo $json_data['phone_number']; ?>" required>
            </div>

            <button class="submit-button translateable" onclick="change_div('admin-data')">Назад</button>
            <button type="submit" class="submit-button translateable">Сохранить изменения</button>
        </div>

    </form>
</div>

<!-- <iframe src="https://www.gov.kz/memleket/entities/sci/search/1?contentType=news%2Carticle%2Cdocuments%2Ccurators&lang=ru&searchText=университет&slug=sci" frameborder="2" style="width: 90vw; min-height: 500px;"></iframe> -->