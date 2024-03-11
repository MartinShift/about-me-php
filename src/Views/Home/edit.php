<?php
require_once __DIR__ . '/../../../public/common.php';
use php\about\Models\Skill;
use php\about\Models\User;
$skills = Skill::getAllSkills();
/**
 *  @var Skill $skill;
 * @var User $aboutMe;
 */
if($aboutMe == false){
    header("Location:login.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["firstName"])) {
        $aboutMe->Update($_POST);
        header("Location: index.php");
        exit;
    }
    if (isset($_POST["addSkill"])) {
        $newSkillName = "New Skill"; 
        $newSkillProgress = 0;
        $newSkillLogo = "";

        $aboutMe->addSkill($newSkillName, $newSkillProgress, $newSkillLogo);
    }
    if (isset($_POST["addExisting"])) {
        $selectedSkillName = $_POST["skillName"];
        $aboutMe->addExistingSkill($selectedSkillName);
    }

}
?>

<!DOCTYPE html>
<html>
<head>

<title><?=$aboutMe->lastName?> <?=$aboutMe->firstName?> - Edit</title>
<link rel="icon" type="image/png" href="<?=$aboutMe->profilePicture?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
        <h2>Edit About Me</h2>
        <form method="post" action="" enctype="multipart/form-data">
     <div class="mb-3">
                <label for="firstNameInput" class="form-label">First Name</label>
                <input name="firstName" class="form-control" value="<?=$aboutMe->firstName?>">
            </div>
            <div class="mb-3">
                <label for="lastNameInput" class"form-label">Last Name</label>
                <input name="lastName" class="form-control" value="<?=$aboutMe->lastName?>">
            </div>
            <div class="mb-3">
                <label for="ageInput" class="form-label">Age</label>
                <input name="age" class="form-control" value="<?=$aboutMe->age?>">
            </div>
            <div class="mb-3">
                <label for="descriptionInput" class="form-label">Description</label>
                <textarea name="description" class="form-control"><?=$aboutMe->description?></textarea>
            </div>
            <div class="mb-3">
            <div class="profile-image-upload">
                <input type="file" id="ProfileImageUpload" class="ProfileImageUpload form-control image-upload visually-hidden" accept="image/*">
                <input type="hidden" id="mainlogosrc" name="profilePicture" value="<?=$aboutMe->profilePicture?>">
                <label for="mainlogosrc" class="profile-image-button">
                    <img id="mainlogo" src="<?=$aboutMe->profilePicture?>" class="profile-image" />
                </label>
                <input type="file" id="ProfileImageUpload" name="ProfileImage" class="ProfileImageUpload form-control image-upload " accept="image/*" onchange="setLogoSrc(this)">
            </div>
        </div>



            <h3>Skills</h3>
            <div id="skills-container">
                <?php foreach ($aboutMe->skills as $index => $skill): ?>
                <div class="mb-3 skill-input">
                    <input name="skills[<?=$index?>][name]" class="form-control" value="<?=$skill->name?>" placeholder="Skill Name">
                    <input name="skills[<?=$index?>][progress]" class="form-control" value="<?=$skill->progress?>" placeholder="Skill Progress (0-100)">
                    <div class="input-group mb-3">
    <input type="hidden" id="logosrc<?=$index?>" name="skills[<?=$index?>][logo]" value="<?=$skill->logo?>">
    <input type="file"  accept="image/*" class="form-control" onchange="setSkillLogoSrc(this, <?=$index?>)">    
    <label class="input-group-text">Upload</label>
</div>

                    <img id="logoimg<?=$index?>" src="<?=$skill->logo?>" alt="Skill Logo" class="img-thumbnail small-logo">
                    <button class="btn btn-danger" onclick="removeSkill(<?=$index?>)" type="button">Delete</button>
                </div>
                <?php endforeach ?>
            </div>
         <div style="display:flex; flex-direction:row; gap: 10px;">     
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="index.php" class="btn btn-warning ">Cancel</a>
                </div>
        </form> 
        
        <form method="post" action="" enctype="multipart/form-data">
            <button type="submit" class="btn btn-success mt-3 mb-3" name="addSkill">Add Skill</button>
        </form>
        <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#addExistingSkillModal">
  Add Existing Skill
</button>


<div class="modal fade" id="addExistingSkillModal" tabindex="-1" aria-labelledby="addExistingSkillModalLabel" aria-hidden="true">
  <div class="modal-dialog">

    <form method="post" action="" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addExistingSkillModalLabel">Add Existing Skill</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <select name="skillName" class="form-select" id="existingSkillSelect">
        <?php foreach ($skills as $skill): ?>
        <option  value='<?=$skill->name?>'><?=$skill->name?></option>
        <?php endforeach?>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="addExisting" class="btn btn-primary" id="addSkillButton">Add</button>
      </div>

    </div>
    </form>
  </div>
</div>

    </div>


    <script>
       function addSkill() {
    const skillsContainer = document.getElementById('skills-container');
    const lastSkill = skillsContainer.lastElementChild.cloneNode(true);

    // Reset the values of the cloned input fields
    const clonedInputs = lastSkill.querySelectorAll('input, textarea');
    clonedInputs.forEach((input) => {
        input.value = '';
    });

    skillsContainer.appendChild(lastSkill);
}


        function removeSkill(index) {
            // Remove the skill input section by index
            const skillsContainer = document.getElementById('skills-container');
            const skillToRemove = skillsContainer.children[index];
            if (skillToRemove) {
                skillsContainer.removeChild(skillToRemove);
            }
        }
        function setLogoSrc(input) {
    const hiddenInput = document.getElementById(`mainlogosrc`);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            hiddenInput.value = e.target.result;
            document.getElementById(`mainlogo`).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
         function setSkillLogoSrc(input, index) {
    const hiddenInput = document.getElementById(`logosrc${index}`);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            hiddenInput.value = e.target.result;
            document.getElementById(`logoimg${index}`).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
document.addEventListener("DOMContentLoaded", function () {
  const existingSkillSelect = document.getElementById("existingSkillSelect");
  const addSkillButton = document.getElementById("addSkillButton");
  addSkillButton.addEventListener("click", function () {
    const selectedSkill = existingSkillSelect.value;
    const modal = new bootstrap.Modal(document.getElementById("addExistingSkillModal"));
    modal.hide();
  });
});

    </script>
</body>
</html>
