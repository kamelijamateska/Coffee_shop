<?php
// Дефиниција на класата Order која се користи за управување со нарачките
class Order {
    // Приватна променлива за чување на врската со базата на податоци
    private $conn;
    
    // Конструкторот ја иницијализира врската со базата на податоци
    public function __construct($db) {
        $this->conn = $db; // Зачувување на предадената врска во променливата $conn
    }
    
    // Метод placeOrder за поставување на нова нарачка
    public function placeOrder($userId, $itemId) {
        // SQL барање за внесување на нова нарачка во табелата "orders"
        $query = "INSERT INTO orders (user_id, item_id) VALUES (:userId, :itemId)";
        
        try {
            // Подготовка на SQL барањето
            $stmt = $this->conn->prepare($query);
            // Поврзување на параметрите со нивните вредности
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":itemId", $itemId);
            
            // Извршување на барањето; враќа true ако е успешно, во спротивно false
            return $stmt->execute();
        } catch(PDOException $e) {
            // Во случај на грешка, враќа false
            return false;
        }
    }
    
    // Метод getUserOrders за добивање на сите нарачки на одреден корисник
    public function getUserOrders($userId) {
        // SQL барање за добивање на нарачките на корисникот
        $query = "SELECT orders.id, users.username, orders.item_name  
                  FROM orders
                  JOIN users ON orders.user_id = .id 
                  WHERE o.user_id = :userId";
        
        try {
            // Подготовка на SQL барањето
            $stmt = $this->conn->prepare($query);
            // Поврзување на параметарот за userId
            $stmt->bindParam(":userId", $userId);
            // Извршување на барањето
            $stmt->execute();
            // Враќање на сите нарачки како асоцијативна низа
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // Во случај на грешка, враќа празна низа
            return [];
        }
    }
}
?>
