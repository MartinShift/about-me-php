<?php
namespace php\about\Models;
class Skill {
    public $id;
    public $name;

    public $progress;
    public $logo;
    private static $pdo;

    public function __construct($skillName,$skillProgress,$skillLogo) {
        $this->name = $skillName;
        $this->logo = $skillLogo;
        $this->progress = $skillProgress;
        self::$pdo = PDOHelper::getPDO();
    }

    public static function getOrAddSkill($skillName, $skillLogo) {
        $stmt = self::$pdo->prepare("SELECT id FROM skills WHERE name = :name");
        $stmt->execute([':name' => $skillName]);
        $skill = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($skill) {
            return $skill['id'];
        } else {
            $stmt = self::$pdo->prepare("INSERT INTO skills (name, logo) VALUES (:name, :logo)");
            $stmt->execute([':name' => $skillName, ':logo' => $skillLogo]);
            return self::$pdo->lastInsertId(); 
        }
    }
    
    public static function getAllSkills() {
        if (self::$pdo === null) {
            self::$pdo = PDOHelper::getPDO();
        }
        $stmt = self::$pdo->prepare("SELECT * FROM skills");
        $stmt->execute();
        $skillsArray = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $skill = new Skill($row['name'], '', $row['logo']);
            $skillsArray[] = $skill;
        }
        return $skillsArray;
    }
}