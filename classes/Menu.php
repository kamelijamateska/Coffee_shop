<?php
// Дефиниција на класата Menu, која се користи за управување со предметите во менито
class Menu {
    // Приватна променлива за чување на врската со базата на податоци
    private $conn;
    
    // Конструкторот ја иницијализира врската со базата на податоци
    public function __construct($db) {
        $this->conn = $db; // Зачувување на предадената врска во променливата $conn
    }
    
    // Метод addItem за додавање на нов предмет во менито
    public function addItem($name, $description, $price) {
        // SQL барање за внесување на нов предмет во табелата "menu"
        $query = "INSERT INTO menu (name, description, price) 
                  VALUES (:name, :description, :price)";
        
        try {
            // Подготовка на SQL барање
            $stmt = $this->conn->prepare($query);
            // Поврзување на параметрите со нивните вредности
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":price", $price);
            
            // Извршување на барањето; враќа true ако е успешно, во спротивно false
            return $stmt->execute();
        } catch(PDOException $e) {
            // Во случај на грешка, враќа false
            return false;
        }
    }
    
    // Метод getAllItems за добивање на сите предмети од менито
    public function getAllItems() {
        // SQL барање за добивање на сите редови од табелата "menu"
        $query = "SELECT * FROM menu";
        
        try {
            // Подготовка на SQL барањето
            $stmt = $this->conn->prepare($query);
            // Извршување на барањето
            $stmt->execute();
            // Враќање на сите резултати како асоцијативна низа
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // Во случај на грешка, враќа празна низа
            return [];
        }
    }
}
?>
